<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\Seller;
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
    public $offset = 50;
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

    public function actionGetDataProducts(){
        $catalog_id = Yii::$app->request->get("catalog_id");
        $brand = Yii::$app->request->get("brand") ?  Yii::$app->request->get("brand") : 0;
        $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
        $search = Yii::$app->request->get("search") ?  Yii::$app->request->get("search") : 0;
        $mode = Yii::$app->request->get("mode") ?  Yii::$app->request->get("mode") : 0;
        $vars['data'] = $this->getDataCatalogProducts($catalog_id,$page,$brand,$search,$mode);
        $vars['brands'] = $this->getBrandOptions($catalog_id, $brand);
        $vars['pages'] = $this->getPages($catalog_id,$brand,$search,$mode,$page);
        $json = json_encode($vars);
        echo $json;
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
        $catalog_id = Yii::$app->request->get("catalog_id");
        $brand = Yii::$app->request->get("brand") ?  Yii::$app->request->get("brand") : 0;
        $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
        $search = Yii::$app->request->get("search") ?  Yii::$app->request->get("search") : 0;
        $mode = Yii::$app->request->get("mode") ?  Yii::$app->request->get("mode") : 0;
        $vars['catalog_id'] = $catalog_id;
        $data_section =  \Yii::$app->db->createCommand("select * from v_catalog_sections where catalog_id = {$catalog_id}")->queryOne();
        $vars["catalog_name"] = $data_section['name'];
        $vars['data'] = $this->getDataCatalogProducts($catalog_id,$page,$brand,$search,$mode);
        $vars['catalog_options'] = $this->getCatalogOptions($catalog_id);
        $vars['brand_options'] = $this->getBrandOptions($catalog_id, $brand);
        $vars['pages'] = $this->getPages($catalog_id,$brand,$search,$mode,$page);
        return $this->render('catalog', $vars);
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

    private function getBrandOptions($catalog_id, $brand=0){
        $res =  \Yii::$app->db->createCommand("SELECT br.brand from index_brand_section_rating as br 
											inner join catalog_subject as cs on (cs.catalog_id={$catalog_id})
											WHERE br.section_id = cs.subject_id ORDER BY brand")->queryAll();

        $html = '';
        foreach ((array)$res as $r)
        {
            $selected = ($brand && ($r["brand"] == $brand)) ? "selected" : "";
            $value = urlencode($r["brand"]);
            $html .= "<option value=\"{$value}\"{$selected}>{$r["brand"]}</option>";
        }

        return $html;
    }

    private function getPages($catalog_id,$brand,$search,$mode,$page){
        $res = \Yii::$app->db->createCommand("
			select p.id as madeid, p1.id as modelid, cs.section_id
		   from properties p, properties p1, v_catalog_sections cs
		   where cs.catalog_id={$catalog_id} and cs.f_main=1 and p.section_id=cs.section_id and p.type=2 and p1.section_id=p.section_id and p1.type=4
			")->queryAll();

        $prop = $res[0];

        $sql_brand = $brand ? " and pp2.brand_value='{$brand}'" : "";
        $sql_search = $search ? " and pp2.basic_name like '%{$search}%'" : "";

        if ($mode && ($mode == "all")) {
            $res = \Yii::$app->db->createCommand("
				select count(1) as cnt
                from (
                    SELECT '' AS brand, pp2.basic_name AS model, ps.id, ps.owner_id, ps.seller_id, ps.cost_us, ps.cost_by, ps.description, ps.wh_state, ps.count, ps.link, ps.garant, ps.product_id AS prodsel_id, ps.manufacturer, ps.importer, ps.service, p.is_owner, p.id AS product_id
						, ps.delivery_day, ps.term_use, if(ps.product_id is not null, f_prod_avgcost_check (ps.product_id, ps.cost_us), 1) AS cost_filter
						FROM			
						products AS p 
						INNER JOIN index_product pp2 ON (
							pp2.product_id = p.id			
						)
						LEFT JOIN product_seller ps ON (
							ps.product_id = p.id
							AND ps.seller_id = {$this->seller_id} and ps.active = 1
						)
						WHERE p.section_id = {$prop["section_id"]} and p.is_archive = 0 {$sql_brand}{$sql_search}
				) as qba
                where qba.is_owner = 0 or (qba.is_owner = 1
                and qba.prodsel_id is not null)
				")->queryAll();

        }
        else
        {
            $goods = Yii::$app->request->get('goods');
            if($goods){
                //$sql_join = "JOIN products as p on (pp2.product_id = p.id)";
                $sect = " and p.section_link_id";
                $no_sect = " and p.section_id != {$prop["section_id"]}";
            } else {
                $sect = " and pp2.index_section_id";
                $sql_join = "";
                $no_sect = "";
            }

            $str_sql = "select count(1) as cnt
				from product_seller ps				
				inner JOIN index_product pp2 ON (pp2.product_id = ps.product_id)
				JOIN products as p on (pp2.product_id = p.id)
				where ps.seller_id={$this->seller_id} and ps.active = 1 {$sect} = {$prop["section_id"]} {$no_sect}  {$sql_brand}{$sql_search} and p.is_archive <= 1
				
				";
            $res = \Yii::$app->db->createCommand($str_sql)->queryAll();
        }
        $cnt_all = $res[0]['cnt'];


        $page_all = ceil($cnt_all / $this->offset);

        $first = 1;
        $last = $page_all;

        return SiteService::get_pages($page, $first, $last, '/product/get-data-products/?');

    }

    private function getCatalogOptions($catalog_id){
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


            $res_click = \Yii::$app->db->createCommand("select if(IFNULL(pay_type,'fixed') = 'clicks',1,0) as is_clicks from seller where id = ". $this->seller_id)->queryAll();

            if($res_click[0]['is_clicks'] == 1){
                $res = \Yii::$app->db->createCommand("select * from bill_catalog")->queryAll();
            }else{
                $res = \Yii::$app->db->createCommand("select * from bill_catalog where id in (select catalog_id from bill_catalog_seller where seller_id={$this->seller_id}) order by position")->queryAll();
            }

            foreach ((array)$res as $r)
            {
                $html_iterate = "";
                $res1 = \Yii::$app->db->createCommand("
                    select id, name
                    from catalog c
                    where id in (
                        select catalog_id from catalog_subject where subject_id in (
                            select section_id from bill_catalog_section where catalog_id={$r["id"]}
                        ) and f_main=1
                    ) and hidden=0
                    order by name
                    ")->queryAll();
                foreach ((array)$res1 as $r1)
                {
                    $cnt = isset($data[$r1["id"]]) ? $data[$r1["id"]] : 0;
                    $cnt = $cnt ? " ({$cnt})" : "";
                    $selected = ($r1["id"] == $catalog_id) ? "selected" : "";
                    $html_iterate .= "<option value=\"{$r1["id"]}\"{$selected}>{$r1["name"]}{$cnt}</option>";
                }


                $html .= "<optgroup label=\"{$r["name"]}\">{$html_iterate}</optgroup>";
            }
            return $html;
    }

    private function getDataCatalogProducts($catalog_id, $page=0, $brand = 0, $search = 0, $mode=''){
        $res = \Yii::$app->db->createCommand("
			select p.id as madeid, p1.id as modelid, cs.subject_id as section_id
			from properties p, properties p1, catalog_subject cs
			where cs.catalog_id={$catalog_id} and p.section_id=cs.subject_id and p.type=2 and p1.section_id=p.section_id and p1.type=4
			")->queryAll();
        $prop = $res[0];

        $sql_brand = $brand ? " and pp2.brand_value='{$brand}'" : "";
        $sql_search = $search ? " and pp2.basic_name like '%{$search}%'" : "";

        $start = $page * $this->offset;

        if ($mode && ($mode == "all")) {
            $str_sql = "
              select qba.*
                from (
                    SELECT '' AS brand, pp2.basic_name AS model, ps.id, ps.owner_id, ps.seller_id, ps.cost_us, ps.cost_by, ps.description, ps.wh_state, ps.count, ps.link, ps.garant, ps.product_id AS prodsel_id, ps.manufacturer, ps.importer, ps.service, p.is_owner, p.id AS product_id
						, ps.delivery_day, ps.term_use, if(ps.product_id is not null, f_prod_avgcost_check (ps.product_id, ps.cost_us), 1) AS cost_filter
						FROM			
						products AS p 
						INNER JOIN index_product pp2 ON (
							pp2.product_id = p.id			
						)
						LEFT JOIN product_seller ps ON (
							ps.product_id = p.id
							AND ps.seller_id = {$this->seller_id} and ps.active = 1
						)
						WHERE p.section_id = {$prop["section_id"]} and p.is_archive = 0 {$sql_brand}{$sql_search}
				) as qba
                where qba.is_owner = 0 or (qba.is_owner = 1
                and qba.prodsel_id is not null)
                order by model, id
				limit {$start},{$this->offset}";

            $res = \Yii::$app->db->createCommand($str_sql)->queryAll();
        }
        else
        {
            $goods = Yii::$app->request->get('goods');
            if($goods){
                //$sql_join = "JOIN products as p on (pp2.product_id = p.id)";
                $sect = " and p.section_link_id";
                $no_sect = " and p.section_id != {$prop["section_id"]}";
            } else {
                $sect = " and pp2.index_section_id";
                $sql_join = "";
                $no_sect = "";
            }

            $str_sql = "select '' as brand, pp2.basic_name as model, ps.*, f_prod_avgcost_check(ps.product_id,ps.cost_us) as cost_filter
				from product_seller ps				
				inner JOIN index_product pp2 ON (pp2.product_id = ps.product_id)
				JOIN products as p on (pp2.product_id = p.id)
				where ps.seller_id={$this->seller_id} and ps.active = 1 {$sect} = {$prop["section_id"]} {$no_sect}  {$sql_brand}{$sql_search} and p.is_archive <= 1
				order by pp2.brand_value, pp2.product_name, ps.id
				limit {$start},{$this->offset}
				";

            $res = \Yii::$app->db->createCommand($str_sql)->queryAll();
        }
        $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $setting_data = $obj_seller->setting_data;
        $curr_data = unserialize($setting_data);
        $curr = $curr_data["currency_base"];

        $html = '';

        /*$template = "products/iterate";
        if($this->prod_template == 2)
        {
            $template = "products/iterate_add";
        }*/

        foreach ((array)$res as $r)
        {
            $r["seller_id"] = $this->seller_id;
            $r["id"] = ($r["id"]) ? $r["id"] : 0;
            if ($curr == 'byn'){
                $r["cost"] = $r["cost_by"] / 10000;
            } else {
                $r["cost"] = $curr == "br" ? $r["cost_by"] : $r["cost_us"];
            }

            $r["name"] = "<b>{$r["brand"]}</b> {$r["model"]}";
            $r["href_product"] = "http://www.migom.by/{$r["product_id"]}/info_seller/";
            $r["selected_{$r["wh_state"]}"] = "selected";
            $r["garant"] = preg_replace("/[^0-9]/","",$r["garant"]);
            $r["delivery_day"] = ($r["delivery_day"] == 0) ? '' : $r["delivery_day"];
            $r["term_use"] = ($r["term_use"]) ? $r["term_use"] : '';
            $r["cost_filter"] = ($r["cost_filter"] == 1) ? '' : '<span style="font-size:10px;color:red;">Подозрительная цена</span>';
            $html .= $this->renderPartial('tmpl/price/iterate', $r);
        }
        return $html;
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
