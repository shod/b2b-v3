<?php

namespace app\controllers;

use app\models\BillAuction;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SpecController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    var $flag_disabled = false;
    var $min_balance = 10; /*Min баланс участия в аукционе*/
    var $min_stavka = 10; /*Min ставка участия в аукционе*/
    var $min_step = 0.1; /*Min шаг ставка*/
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
            case "clarify":
                $catalog_id = Yii::$app->request->get("catalog_id");
                $auction = BillAuction::find()->where(["owner_id" => $this->seller_id, "type_id" => 2, "object_id" => $catalog_id])->one();

                //and ps.product_id in (select product_id from bill_auctions_products where auction_id={$auction_id})
                $res =\Yii::$app->db->createCommand("
						select distinct ip.product_id as pid, ib.id as brand_id, ifnull(ib.brand,'==Noname==') as made, REPLACE(ip.basic_name, IFNULL(ib.brand,''), '') as model
						from product_seller ps
						left join index_product ip on (ip.product_id=ps.product_id)
						left join index_brand ib on (ib.id=ip.brand_id)
						where ps.seller_id={$this->seller_id}
							and ip.index_section_id in (select subject_id from catalog_subject where catalog_id={$catalog_id})							
							and exists (select 1 from bill_auctions_products as bpp where auction_id={$auction->id} and bpp.product_id = ps.product_id)
						order by made, model
					")->queryAll();
                $b_array[] = 0;
                foreach ((array) $res as $r)
                {
                    $brands[$r["brand_id"]] = $r["made"];
                    $b_array[] = $r["brand_id"];
                    $data_x[$r["brand_id"]][] = "<option value=\"{$r["pid"]}\">{$r["model"]}</option>";
                }

                $is_group = false;
                $html_brands = "";
                if(isset($brands) && count($brands)>0){
                    $html_brands .= "<optgroup label=\"Выбранные производители\">";
                    $is_group = true;
                    foreach ((array) $brands as $id => $made)
                    {
                        $html_brands .= "<option value=\"{$id}\">{$made}</option>";
                    }
                }


                if($is_group){
                    $html_brands .= "</optgroup>";
                    $html_brands .= "<optgroup label=\"Другие производители\">";
                }

                //and ps.product_id not in (select product_id from bill_auctions_products where auction_id={$auction_id})
                $res = \Yii::$app->db->createCommand("
						select distinct ip.product_id as pid, ib.id as brand_id, ib.brand as made, REPLACE(ip.basic_name, IFNULL(ib.brand,''),'') as model
						from product_seller ps
						left join index_product ip on (ip.product_id=ps.product_id)
						left join index_brand ib on (ib.id=ip.brand_id)
						where ps.seller_id={$this->seller_id}
							and ip.index_section_id in (select subject_id from catalog_subject where catalog_id={$catalog_id})							
							and not exists (select 1 from bill_auctions_products as bpp where auction_id={$auction->id} and bpp.product_id = ps.product_id)
						order by made, model
					")->queryAll();

                foreach ((array) $res as $r)
                {
                    $rmade = $r["made"];
                    if($rmade==''){
                        $rmade = '==Noname==';
                    }
                    $brands[$r["brand_id"]] = $rmade;
                    $data[$r["brand_id"]][] = "<option value=\"{$r["pid"]}\">{$r["model"]}</option>";
                }

                $html_data = "";
                if(isset($brands)){
                    foreach ((array)$brands as $brand_id=>$made)
                    {
                        $sdata = (count($data[$brand_id])>0) ? $data[$brand_id] : '';

                        $html_data .= $this->renderPartial("tmpl/clarify/models", array(
                            "id" => $brand_id,
                            "catalog_id" => $catalog_id,
                            "data" => implode("",(array)$sdata),
                            "data_x" => isset($data_x) ? implode("",(array)$data_x[$brand_id]) : "",
                        ));
                        //print_r($html_data);
                    }

                    foreach ((array) $brands as $id => $made)
                    {
                        $html_brands .= "<option value=\"{$id}\">{$made}</option>";
                    }

                    if($is_group){
                        $html_brands .= "</optgroup>";
                    }
                }


                $html = $this->renderPartial("tmpl/clarify/index", array(
                    "id" => $catalog_id,
                    "brands" => $html_brands,
                    "data" => $html_data
                ));

                die($html);
                break;
            case "add":
                $ids = Yii::$app->request->post("ids");
                foreach ((array) $ids as $catalog_id)
                {
                    $bill_auction = new BillAuction();
                    $bill_auction->owner_id = $this->seller_id;
                    $bill_auction->type_id = 2;
                    $bill_auction->object_id = $catalog_id;
                    $bill_auction->cost = 0;
                    $bill_auction->date = date("Y-m-d H:i:s");
                    $bill_auction->save();
                }
                return $this->redirect(['spec']);
                break;
            case "delete":
                $id = Yii::$app->request->get("id");
                \Yii::$app->db->createCommand("delete from bill_auction where owner_id={$this->seller_id} and id={$id}")->execute();
                return $this->redirect(['spec']);
                break;
        }
    }

    public function actionIndex()
    {
        $vars["data"] = $this->getDataHtml();
        $res = \Yii::$app->db->createCommand("select * from texts where id=203")->queryOne();
        $vars["title"] = $res["name"];
        $vars["text"] = $res["text"];
        $vars["text"] = str_replace(array('$vars[min_stavka]','$vars[min_step]','$vars[min_balance]'),array($this->min_stavka,$this->min_step,$this->min_balance),$res["text"]);

        return $this->render('index', $vars);
    }

    public function actionAdd()
    {
        $vars["data"] = $this->getDataAddHtml();
        return $this->render('add', $vars);
    }

    private function getDataHtml(){
        $res = \Yii::$app->db->createCommand("select * from bill_auction where type_id=2 and owner_id={$this->seller_id}")->queryAll();
        $html = "";
        if(!is_array($res)){
            return '';
        }

        $cost = $ids = array();
        foreach ((array) $res as $r)
        {
            $cost[$r["id"]] = $r["cost"];
            $ids[$r["id"]] = $r["object_id"];
        }
        $ids_str = implode(",", $ids);


        $html_row = "";
        $res1 = \Yii::$app->db->createCommand("
			select distinct ba.id as id, cat.name as name, ba.object_id as catalog_id, ba.f_show
			from bill_auction as ba
			inner join (
				select id, name
				from catalog c
				where hidden=0 and id in ({$ids_str})
			) as cat on (cat.id=ba.object_id)
			where ba.owner_id={$this->seller_id} and ba.type_id=2
			order by name
			")->queryAll();
        foreach ((array) $res1 as $r1)
        {
            list($views, $forecast) = $this->getViews($r1["catalog_id"], $cost[$r1["id"]]);
            $html_row .= $this->renderPartial("tmpl/row", array(
                "id" => $r1["id"],
                "catalog_id" => $r1["catalog_id"],
                "checked_f_show" => $r1["f_show"] ? "checked" : "",
                "views" => $views,
                "forecast" => $forecast,
                "name" => $r1["name"],
                "data" => $this->getPositionDataHtml($ids[$r1["id"]]),
                "value" => $cost[$r1["id"]] ==0 ? "0.0" : round($cost[$r1["id"]], 1),
                "disabled" => $this->flag_disabled ? "disabled" : "",
                "href_delete" => "/spec/process/?action=delete&id=". $r1["id"]
            ));
        }


        $html .= $this->renderPartial("tmpl/catalog", array(
            "name" => isset($r["name"]) ? $r["name"] : "",
            "data" => $html_row,
        ));


        return $html;
    }

    private function getViews($catalog_id, $cost=0)
    {
        $date_to = mktime(0, 0, 0);
        $date_from = mktime(0, 0, 0, date("n"), date("j") - 1, date("Y"));

        $res = \Yii::$app->db->createCommand("
			select IFNULL(sum(view), 0) as v1, IFNULL(Round(sum(view)*{$cost}/1000,2), 0) as v2
			from stat_spec pss
			inner join products p on (p.id=pss.product_id)
			inner join catalog_subject cs on (cs.subject_id=p.section_id and cs.catalog_id={$catalog_id})
			where pss.date>={$date_from} and pss.date<{$date_to} and seller_id={$this->seller_id}
		")->queryAll();

        return array($res[0]["v1"], $res[0]["v2"]);
    }

    private function getPositionDataHtml($catalog_id)
    {
        $html = "";
        $res = \Yii::$app->db->createCommand("
			select ba.*, TRIM(s.name) as name
			from bill_auction  ba
			inner join seller s on (s.id=ba.owner_id)
			inner join bill_account bacc on (bacc.id=s.bill_account_id)
			where ba.type_id=2 and ba.object_id={$catalog_id} and ba.cost>0 and s.active=1 and bacc.balance_all >= {$this->min_balance}
			order by ba.cost desc, date asc
		")->queryAll();
        foreach ((array) $res as $r)
        {
            $selected = ($r["owner_id"] == $this->seller_id) ? "style=\"background: rgb(245, 222, 227);font-size: 11px;\"" : "style=\"font-size: 11px;\"";
            $cost = round($r["cost"],2);
            $name = $r['name'];
            $name = htmlspecialchars($name);
            $html .= "<li {$selected} title=\"{$name}\"><div><b>{$cost}&nbsp;</b><span style='color: rgba(129, 129, 129, 0.57)'>{$name}</span></div></li>";

        }
        $html = $html ? $html : "-";
        $html = "<ol>{$html}</ol>";

        return $html;
    }

    private function getDataAddHtml()
    {
        $html = "";
        $res = \Yii::$app->db->createCommand("
			select ba.object_id as id, min(ba.cost) as cost_min, max(ba.cost) as cost_max, count(ba.owner_id) as cnt
			from bill_auction ba
			inner join seller s on (s.id=ba.owner_id)
			inner join bill_account bacc on (bacc.id=s.bill_account_id)
			where type_id=2 and ba.cost>0 and s.active=1 and bacc.balance >= {$this->min_balance}
			group by object_id
		")->queryAll();
        $data = $ids = array();
        foreach ((array) $res as $r)
        {
            $data[$r["id"]] = $r;
            $ids[] = $r["id"];
        }
        $ids_str = implode(",", $ids);
        $res_click = \Yii::$app->db->createCommand("select if(IFNULL(pay_type,'fixed') = 'clicks',1,0) as is_clicks from seller where id = ". $this->seller_id)->queryAll();

        if($res_click[0]['is_clicks'] == 1){
            $res = \Yii::$app->db->createCommand("SELECT * from bill_catalog WHERE id = 223")->queryAll();
        }else{
            $res = \Yii::$app->db->createCommand("select * from bill_catalog where id in (select catalog_id from bill_catalog_seller where seller_id={$this->seller_id}) order by position")->queryAll();
        }
        foreach ((array) $res as $r)
        {
            $html_row = "";
            if($res_click[0]['is_clicks'] == 1){
                $res1 = \Yii::$app->db->createCommand("
                                            SELECT
                                                c.id,
                                                c.name
                                            FROM
                                                catalog AS c,
                                                v_catalog_sections AS vcs,
                                                product_seller AS ps
                                            WHERE
                                                ps.seller_id = {$this->seller_id}
                                            AND ps.active = 1
                                            AND vcs.section_id = ps.prod_sec_id
                                            AND c.id = vcs.catalog_id
                                            GROUP BY
                                                ps.prod_sec_id")->queryAll();
                $r["name"] = "Доступные разделы";
            }else{
                $res1 = \Yii::$app->db->createCommand("
				select id, name
				from catalog c
				where id in (
					select cs.catalog_id
					from catalog_subject cs
					where cs.subject_id in (
						select bcs.section_id
						from bill_catalog_section bcs
						inner join sections s on (s.id=bcs.section_id)
						where bcs.catalog_id={$r["id"]} and (s.type<>'price' OR s.type is null)
					)  /*and cs.f_main=1*/
				) and hidden=0  and not id in (select object_id from bill_auction where owner_id={$this->seller_id} and type_id=2)
				order by name
				")->queryAll();
            }

            foreach ((array) $res1 as $r1)
            {
                $cost_min = isset($data[$r1["id"]]["cost_min"]) ? $data[$r1["id"]]["cost_min"] : 0;
                $cost_max = isset($data[$r1["id"]]["cost_max"]) ? $data[$r1["id"]]["cost_max"] : 0;
                $cnt = isset($data[$r1["id"]]["cnt"]) ? $data[$r1["id"]]["cnt"] : 0;

                $cost_from = $cost_min ? "от {$cost_min}" : " от {$this->min_stavka} ";
                $cost_to = ($cost_max && $cost_min != $cost_max) ? " до {$cost_max}" : "";
                list($views) = $this->getViews($r1["id"]);
                $html_row .= $this->renderPartial("tmpl/add_row", array(
                    "id" => $r1["id"],
                    "name" => $r1["name"],
                    "cost" => "{$cost_from}{$cost_to}",
                    "views" => $views
                ));
            }


            $html .= $this->renderPartial("tmpl/catalog", array(
                "name" => isset($r["name"]) ? $r["name"] : "",
                "data" => $html_row,
            ));
        }

        return $html;
    }
}
