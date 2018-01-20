<?php

namespace app\controllers;

use app\helpers\SiteService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class ProductController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    public function behaviors()
    {
        $this->seller_id = Yii::$app->user->identity->getId();
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    public function actionOnSale()
    {
        $prod_stat = \Yii::$app->db->createCommand("select cnt_all, cnt_bill, round(cnt_bill/cnt_all*100) as active_percent
													from (select count(1) as cnt_all, sum(if(active=1,1,0)) as cnt_bill
													from product_seller as ps
													where ps.seller_id = {$this->seller_id} and ps.product_id > 0) as qq")->queryAll();

        if (count($prod_stat) > 0){
            $vars['prod_stat_cnt_all'] = $prod_stat[0]['cnt_all'];
            $vars['prod_stat_cnt_bill'] = $prod_stat[0]['cnt_bill'];
            $vars['prod_active_percent'] = $prod_stat[0]['active_percent'];
        } else {
            $vars['prod_stat_cnt_all'] = 0;
            $vars['prod_stat_cnt_bill'] = 0;
            $vars['prod_active_percent'] = 0;
        }
        $vars['data'] = $this->getDataCatalog();
        $vars['status'] = $this->getDateUpdate();

        return $this->render('on-sale', $vars);
    }

    public function actionCatalog()
    {
        return $this->render('catalog');
    }

    public function actionPrice()
    {
        return $this->render('price');
    }

    public function actionProcess(){
        $action = Yii::$app->request->get("action");
        $order_id = Yii::$app->request->get("order_id");
        switch ($action) {
            case "refresh":
                \Yii::$app->db->createCommand("call pc_product_seller_actual({$this->seller_id});")->execute();
                return $this->redirect(['product/on-sale']);
                break;
            // TODO: transactions
        }
    }

    private function getDataCatalog()
    {
        $res = \Yii::$app->db->createCommand("
			select cs.catalog_id as id, count(ps.id) as cnt
			from product_seller ps
			inner join products p on (p.id=ps.product_id)
			inner join catalog_subject cs on (cs.subject_id=p.section_id)
			where ps.seller_id={$this->seller_id} and ps.active=1
			group by cs.catalog_id
			")->queryAll();

        $data = array();
        foreach ((array)$res as $r)
        {
            $data[$r["id"]] = $r["cnt"];
        }


        $html = '';
        /*Запрос на выборку разделов подключенных*/
        $res = \Yii::$app->db->createCommand("select bc.* 
				from bill_catalog as bc, bill_catalog_seller as bbs
				where bbs.seller_id={$this->seller_id} and bc.id = bbs.catalog_id
				and bc.hidden = 0")->queryAll();

        foreach ((array)$res as $r)
        {
            $html_iterate = "";
            $res1 = \Yii::$app->db->createCommand("
					select distinct c.id, c.name, vcs.catalog_type
					from bill_catalog_section bcs
					inner join catalog_subject cs on (cs.subject_id=bcs.section_id)
					inner join catalog c on (c.id=cs.catalog_id)
					inner join v_catalog_sections vcs on (vcs.section_id=bcs.section_id)					
					where bcs.catalog_id={$r["id"]} and c.hidden=0
					order by c.name;
					")->queryAll();

            foreach ((array)$res1 as $r1)
            {
                $cnt = isset($data[$r1["id"]]) ? $data[$r1["id"]] : '-';

                if ($r1["catalog_type"] == "price_type") {

                    $href_goods = '/product/catalog/?catalog_id='.$r1['id'];
                    $html_iterate .= $this->renderPartial('tmpl/iterate-price', array(
                        "id" => $r1["id"],
                        "name" => $r1["name"],
                        "cnt" => $cnt ? $cnt : "-",
                        "href" => $href_goods
                    ));

                }
                else
                {
                    $price_cnt = \Yii::$app->db->createCommand("
							select count(1) as cnt
							from products as ip, product_seller as ps, v_catalog_sections as vcs
							where 
							ps.product_id = ip.id and 
							vcs.section_id = ip.section_link_id
							AND ip.section_id <> ip.section_link_id
							and ps.seller_id = {$this->seller_id} and ps.active = 1 and vcs.catalog_id = {$r1['id']} 
							 and not EXISTS (SELECT 1 from product_double_link where product_id = ps.product_id)
						")->queryAll();
                    $cnt_goods = $price_cnt[0]['cnt'];
                    $href_goods = $href_goods = '/product/catalog/?catalog_id='.$r1['id'] . "&goods=1";
                    $html_iterate .= $this->renderPartial('tmpl/iterate-product', array(
                        "id" => $r1["id"],
                        "name" => $r1["name"],
                        "cnt" => $cnt ? $cnt : "-",
                        "href" => '/product/catalog/?catalog_id='.$r1['id'],
                        "cnt_goods" => $cnt_goods ? "<a href='{$href_goods}' target=_blank >(+ {$cnt_goods} в товарах без описания)</a>" : ""
                    ));
                }
            }


            $html .= $this->renderPartial('tmpl/iterate-section', array(
                "name" => $r["name"],
                "data" => $html_iterate
            ));
        }

        return $html;
    }

    private function getDateUpdate(){
        $res = \Yii::$app->db->createCommand("select UNIX_TIMESTAMP(last_dat_update) as date from seller_export_info where seller_id={$this->seller_id}")->queryOne();
        if (!$res){
            $res = \Yii::$app->db->createCommand("select max(start_date) as date from product_seller where seller_id={$this->seller_id}")->queryOne();
        } else {
            $time1 = $res['date'];
            $res1 = \Yii::$app->db->createCommand("select max(start_date) as date from product_seller where seller_id={$this->seller_id}")->queryOne();
            $time2 = $res1['date'];
            if ($time2 > $time1){
                $res = $res1;
            }
        }
        $now = time();
        $time = $res['date'];

        $dt = $now - $time;
        $days = floor($dt / 86400);

        if (date("Y.m.d", $time) == date("Y.m.d")) {
            $res = "<font color=\"#009900\">сегодня</font> " . date("H:i", $time);
        }
        elseif (date("Y.m.d", $time) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))))
        {
            $res = "<font color=\"#009900\">вчера</font> " . date("H:i", $time);
        }
        elseif (date("Y.m.d", $time) == date("Y.m.d", mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))))
        {
            $res = "<font color=\"#009900\">позавчера</font> " . date("H:i", $time);
        }
        elseif ($days > 14)
        {

            $m = SiteService::getDataMothStr($time);
            $res = "<font color=\"#ff0000\">давно " . " {$m}</font>";
        }
        else
        {
            $days = ceil($dt / 86400);
            $w = ($days % 10 == 1 && $days != 11) ? "день" : (
            (($days % 10 == 2 && $days != 12) || ($days % 10 == 3 && $days != 13) || ($days % 10 == 4 && $days != 14)) ? "дня" : "дней"
            );

            $m = SiteService::getDataMothStr($time);
            $res = "<font color=\"#FC9E10\">{$days} {$w} назад </font>, " .  " {$m}";
        }

        return $res;
    }
}
