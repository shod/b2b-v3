<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\Seller;
use app\models_ex\BillAccount;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class ContextAdvController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    public $bit = 8192;

    public function beforeAction($action) {
        if ((\Yii::$app->getUser()->isGuest)&&($action->id != 'login')&&($action->id != 'sign-up')) {
            $this->redirect('site/login');
        } else {
            return parent::beforeAction($action);
        }
    }

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

    public function actionProcess(){
        $action = Yii::$app->request->post("action");
        $action = isset($action) ? $action : Yii::$app->request->get("action");

        switch ($action) {
            case "save_setting":
                $agree = Yii::$app->request->post("agree") ? 1 : 0;
                $seller = Seller::find()->where(['id' => $this->seller_id])->one();
                $setting_bit = $seller->setting_bit;

                $setting_bit = SiteService::set_bitvalue($setting_bit,$this->bit,$agree);

                $seller->setting_bit = $setting_bit;
                $seller->save();
                $max = Yii::$app->request->post("max");

                \Yii::$app->db->createCommand("REPLACE INTO seller_clicks_setting (seller_id, click_cost_max) VALUES ({$this->seller_id}, {$max})")->execute();
                return $this->redirect(['context-adv/index']);
                break;
            case "save_blacklist":
                $catalogs = Yii::$app->request->post("catalog_check");
                $good_ids = array();
                foreach ((array)$catalogs as $id => $r)
                {
                    $good_ids[] = $id;
                }
                $str_good_ids = implode(",", $good_ids);
                $sql = "SELECT DISTINCT
                        vb.catalog_id
                    FROM
                        v_catalog_sections AS vb
                    WHERE
                        vb.hidden = 0
                    AND vb.section_id != 1
                    AND NOT EXISTS (
                        SELECT
                            1
                        FROM
                            bill_context_catalog_blacklist AS bcl
                        WHERE
                            seller_id = 0
                        AND vb.catalog_id = bcl.catalog_id
                    )
                    AND vb.catalog_id NOT IN ({$str_good_ids});";
                $res = \Yii::$app->db->createCommand($sql)->queryAll();

                \Yii::$app->db->createCommand("delete from bill_context_catalog_blacklist where seller_id = {$this->seller_id}")->execute();
                foreach ((array)$res as $r)
                {
                    \Yii::$app->db->createCommand("insert into bill_context_catalog_blacklist (catalog_id, seller_id) values ({$r['catalog_id']}, {$this->seller_id})")->execute();
                }
                return $this->redirect(['context-adv/blacklist']);
                break;
        }
    }

    public function actionIndex()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();

        $balance = $bill_account->balance;
        if ($balance <= 0){
            $vars['error_msg'] = "<div class=\"alert alert-danger ks-solid-light\" role=\"alert\">Внимание! Контекстная реклама недоступна из-за низкого баланса! Пополните баланс!</div>";
        }
        $setting_bit = $seller->setting_bit;
        $vars["checked_ads"] = ($setting_bit & $this->bit) ? "checked" : "";
        $res = \Yii::$app->db->createCommand("select click_cost_max from seller_clicks_setting where seller_id = {$this->seller_id}")->queryAll();
        if(count($res)){
            $vars["checked_{$res[0]['click_cost_max']}"] = "checked";
        }

        return $this->render('index', $vars);
    }

    public function actionBlacklist()
    {
        $sql = "SELECT DISTINCT
                            vb.catalog_id,
                            vb.`name`,
                            qoff.catalog_id AS cat_off
                        FROM
                            v_catalog_sections AS vb
                        LEFT JOIN (
                            SELECT
                                catalog_id
                            FROM
                                bill_context_catalog_blacklist AS bcl
                            WHERE
                                seller_id = {$this->seller_id}
                        ) AS qoff ON (
                            qoff.catalog_id = vb.catalog_id
                        )
                        join sections as s on (s.id = vb.section_id)
                        WHERE
                            vb.hidden = 0 and vb.section_id != 1
                        AND NOT EXISTS (
                            SELECT
                                1
                            FROM
                                bill_context_catalog_blacklist AS bcl
                            WHERE
                                seller_id = 0
                            AND vb.catalog_id = bcl.catalog_id
                        ) order by vb.name";

        $res = \Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "select cs.catalog_id as id, count(ps.id) as cnt
                        from product_seller ps
                        inner join products p on (p.id=ps.product_id)
                        inner join catalog_subject cs on (cs.subject_id=p.section_id)
                        where ps.seller_id={$this->seller_id}
                        group by cs.catalog_id";
        $res_count = \Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "SELECT
                            cs.catalog_id AS id,
                            count(ps.id) AS cnt
                        FROM
                            product_seller ps
                        INNER JOIN products p ON (p.id = ps.product_id)
                        INNER JOIN catalog_subject cs ON (
                            cs.subject_id = p.section_link_id
                        )
                        WHERE
                            ps.seller_id = {$this->seller_id}
                        AND p.section_id != p.section_link_id
                        AND NOT EXISTS (
                            SELECT
                                1
                            FROM
                                product_double_link
                            WHERE
                                product_id = ps.product_id
                        )
                        GROUP BY
                            cs.catalog_id";
        $res_goods = \Yii::$app->db->createCommand($sql)->queryAll();

        $data = array();
        foreach ((array)$res_count as $r)
        {
            $data[$r["id"]] = $r["cnt"];
        }

        $data_goods = array();
        foreach ((array)$res_goods as $r)
        {
            $data_goods[$r["id"]] = $r["cnt"];
        }

        $vars['data'] = "";
        foreach((array)$res as $r)
        {
            $r['count_products'] = isset($data[$r['catalog_id']]) && ($data[$r['catalog_id']] > 0) ? $data[$r['catalog_id']] : "-";
            $r['count_goods'] = isset($data_goods[$r['catalog_id']]) && ($data_goods[$r['catalog_id']] > 0) ? "<a href='/?admin=products&&catalog_id={$r['catalog_id']}&goods=1' >(+{$data_goods[$r['catalog_id']]} в товарах без описания)</a>" : "";
            $r['checked'] = $r['cat_off'] > 0 ? "" : "checked";
            $vars['data'] .= $this->renderPartial('tmpl/item', $r);

        }

        $prod_stat = \Yii::$app->db->createCommand("select cnt_all, cnt_bill, round(cnt_bill/cnt_all*100) as active_percent
													from (select count(1) as cnt_all, sum(if(active=1,1,0)) as cnt_bill
													from product_seller as ps
													where ps.seller_id = {$this->seller_id}) as qq")->queryAll();
        if (isset($prod_stat)&&count($prod_stat) > 0){
            $prod_stat_cnt_all = $prod_stat[0]['cnt_all'];
            $prod_stat_cnt_bill = $prod_stat[0]['cnt_bill'];
            $prod_active_percent = $prod_stat[0]['active_percent'];
        } else {
            $prod_stat_cnt_all = 0;
            $prod_stat_cnt_bill = 0;
            $prod_active_percent = 0;
        }

        $vars["prod_stat_cnt_all"] = $prod_stat_cnt_all;
        $vars["prod_stat_cnt_bill"] = $prod_stat_cnt_bill;
        $vars["prod_active_percent"] = $prod_active_percent;
        return $this->render('blacklist', $vars);
    }

}
