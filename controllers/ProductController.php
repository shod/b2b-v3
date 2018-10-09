<?php

namespace app\controllers;

use app\helpers\PriceService;
use app\helpers\ProductService;
use app\helpers\SiteService;
use app\helpers\SysService;
use app\models\IndexProduct;
use app\models\ProductSeller;
use app\models\Seller;
use app\models\SellerInfo;
use PHPUnit\Util\Xml;
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
    public $offset = 100;
    public $seller_curs;
    public $cnt_all = 0;
    public $curr_do_percent = 300;

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

    public function actionGetCurs(){
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $setting_data = $seller->setting_data;
        $setting_data = unserialize($setting_data);
        $vars["selected_{$setting_data["currency_base"]}"] = "selected";
        $vars["currency_rate"] = $setting_data["currency_rate"];
        $vars["currency_rate_byn"] = (float)$setting_data["currency_rate"] / 10000;
        $price_correct = isset($setting_data["price_correct"]) ? $setting_data["price_correct"] : "";
        $vars["price_correct_{$price_correct}"] = "selected";
        $price_correct_value = isset($setting_data["price_correct_value"]) ? $setting_data["price_correct_value"] : "";
        $vars["price_correct_value"] = $price_correct_value;
        $vars["base_rate_str"] = $setting_data["currency_base"] == "usd" ? "USD" : "бел. руб.";


        $curr = SysService::get('currency_nbrb')/10000.0;
        $vars['curr_ot'] = $curr - $curr * 0.1;
        $vars['curr_do'] = $curr + $curr * (0.1 * $this->curr_do_percent);
        $vars['curr_bank'] = $curr;

        //$vars["selected_round_{$vars['cost_round_num']}"] = "selected";
        $vars["currency_rate"] =  ($vars["currency_rate"] > 0) ?  $vars["currency_rate"] : $vars['curr_bank'];

        $vars['body'] = $this->renderPartial('tmpl/curs-setting', $vars);
        $vars['header'] = "Настройка валюты прайса";
        $json = json_encode($vars);
        echo $json;
        exit;
    }

    public function actionSaveCurs(){
        $curr_base = Yii::$app->request->post("currency_base");
        $curr_rate = Yii::$app->request->post("currency_rate");
        $curr_rate = str_replace(",", ".", $curr_rate);
        if ($curr_rate < 100){
            $curr_rate = (float)$curr_rate * 10000;
        }
        $price_correct = Yii::$app->request->post("price_correct");
        $price_correct_value = Yii::$app->request->post("price_correct_value");
        $setting_data = serialize(array("currency_base" => $curr_base, "currency_rate" => $curr_rate,"price_correct" => $price_correct, "price_correct_value" => $price_correct_value));

        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $old_str_setting = $seller->setting_data;
        $old_setting = unserialize($old_str_setting);
        $old_rate = $old_setting["currency_rate"];
        $old_base = $old_setting["currency_base"];
        $seller->setting_data = $setting_data;
        $seller->save();

        // пересчет цен по курсу
        if ($old_base != $curr_base) {
            if (($curr_base == "br") && $curr_rate)
            {
                \Yii::$app->db->createCommand("update product_seller set cost_by = ROUND(cost_us * {$curr_rate}, -2) where seller_id={$this->seller_id}")->execute();
            }
            else
            {
                \Yii::$app->db->createCommand("update product_seller set cost_us = ROUND(cost_by / {$curr_rate}, 2) where seller_id={$this->seller_id}")->execute();
            }
        } elseif ($old_rate != $curr_rate) {
            if (($curr_base == "br") && $curr_rate)
            {
                \Yii::$app->db->createCommand("update product_seller set cost_us = ROUND(cost_by / {$curr_rate}, 2) where seller_id={$this->seller_id}")->execute();
            }
            else
            {
                \Yii::$app->db->createCommand("update product_seller set cost_by = ROUND(cost_us * {$curr_rate}, -2) where seller_id={$this->seller_id}")->execute();
            }
        }

        $cost_round_num = Yii::$app->request->post("cost_round_num");
        $seller_info = SellerInfo::find()->where(['seller_id' => $this->seller_id])->one();
        $seller_info->cost_round_num = $cost_round_num;
        $seller_info->save();
        \Yii::$app->db->createCommand("call pc_cost_round({$this->seller_id})")->execute();
        echo "Курс успешно изменен!";
        exit();
        //$this->redirect('/product/on-sale');
    }

    public function actionGetDataProducts(){
        $catalog_id = Yii::$app->request->post("catalog_id");
        $brand = Yii::$app->request->post("brand") ?  Yii::$app->request->post("brand") : 0;
        $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
        $search = Yii::$app->request->post("search") ?  Yii::$app->request->post("search") : 0;
        $mode = Yii::$app->request->post("mode") ?  Yii::$app->request->post("mode") : 0;
        $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $setting_data = $obj_seller->setting_data;
        $curr_data = unserialize($setting_data);
        $curr = $curr_data["currency_base"];
        $vars['is_goods'] = Yii::$app->request->post("goods") ?  "input type='hidden' name='goods' value=1 />" : "";
        $vars['data'] = $this->getDataCatalogProducts($catalog_id,$page,$brand,$search,$mode,$curr);
        $vars['brands'] = $this->getBrandOptions($catalog_id, $brand);
        $vars['pages'] = $this->getPages($catalog_id,$brand,$search,$mode,$page);
        $vars['catalog_id'] = $catalog_id;
        $json = json_encode($vars);
        echo $json;
    }

    public function actionPriceDownload(){
        PriceService::getCsv($this->seller_id, 'my_price');
        exit;
    }

    public function actionPriceTemplate(){
        $catalog_id = Yii::$app->request->post("catalog_id");
        PriceService::getCsv($this->seller_id, 'price_template', $catalog_id);
        exit;
    }

    public function actionPriceImport(){
        if (Yii::$app->request->post("type") == "file") {
            $file = $_FILES["file"];
            if ($file["tmp_name"] != "none" && $file["name"] != '') {
                $filename = "{$file["name"]}.{$this->seller_id}";

                $filename = SiteService::transliterate($filename);
                $filename_to = "price/{$filename}";

                if (file_exists($filename_to))
                    unlink($filename_to);

                if (move_uploaded_file($file["tmp_name"], $filename_to)) {
                    $home = \yii\helpers\Url::base(true);
                    $url = $home . "/price/{$filename}";
                }
            }
        }
        else
        {
            $url = Yii::$app->request->post("url");
        }

        if (isset($url)) {
            $url = rawurlencode($url);
            $check_delete = Yii::$app->request->post("check_delete");
            file_get_contents("https://up.migom.by/?block=price_import_now&seller_id={$this->seller_id}&check_delete={$check_delete}&url={$url}");
        }
        $this->redirect('/product/price');
    }

    public function actionSaveProducts(){
        if($this->seller_id == 1082){
            \Yii::info('START SAVE ALL PRODUCTS ' . $this->seller_id, 'debug');
            $time_start = time();
        }
        $del =Yii::$app->request->post("del");
        $cost = Yii::$app->request->post("cost");
        $desc = Yii::$app->request->post("desc");
        $wh_state = Yii::$app->request->post("wh_state");
        $link = Yii::$app->request->post("link");
        $garant = Yii::$app->request->post("garant");
        $manufacturer = Yii::$app->request->post("manufacturer");
        $importer = Yii::$app->request->post("importer");
        $service = Yii::$app->request->post("service");
        $delivery_day = Yii::$app->request->post("delivery_day");
        $term_use = Yii::$app->request->post("term_use");
        $catalog_id = Yii::$app->request->post("catalog_id");
        $no_auto = Yii::$app->request->post("no_auto");

        $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $setting_data = $obj_seller->setting_data;
        $curr_data = unserialize($setting_data);
        $curr = $curr_data["currency_base"];
        $rate_by = $this->get_curs($obj_seller);

        $ids = array();
        \Yii::$app->db->createCommand('start transaction;')->execute();
        foreach ((array)$cost as $product_id => $cost_data)
        {

            foreach ((array)$cost_data as $ps_id => $ps_data)
            {
                $flag_update = ($ps_id > 1);

                foreach ((array)$ps_data as $i => $c)
                {

                    if ($del[$product_id][$ps_id] == 0 and $c > 0) {
                        $c = str_replace(" ", "", $c);
                        $c = (float)$c;
                        $_desc = $desc[$product_id][$ps_id];
                        $_desc = str_replace(array("<br>", "<br />", "<br >", "<br/>"), " ", $_desc);
                        $_desc = strip_tags($_desc);
                        $garant_month = preg_replace("/[^0-9]/","",$garant[$product_id][$ps_id][$i]);
                        $_wh_state = $wh_state[$product_id][$ps_id][$i];
                        if((int)$_wh_state == 0){
                            $_wh_state = 1;
                        }
                        if ($curr == 'byn'){
                            $c = $c * 10000;
                            $cost_us = $c / $rate_by;
                            $cost_by = $c;
                        } else {
                            $cost_us = $curr == "br" ? $c / $rate_by : $c;
                            $cost_by = $curr == "br" ? $c : $c * $rate_by;
                        }
                        $r = array(
                            "cost_us" => $cost_us,
                            "cost_by" => $cost_by,
                            "description" => $_desc,
                            "wh_state" => $_wh_state,
                            "garant" => $garant_month ? $garant_month : "",
                            "link" => isset($link[$product_id][$ps_id][$i]) ? $link[$product_id][$ps_id][$i] : "",
                            "manufacturer" => $this->clear_text($manufacturer[$product_id][$ps_id][$i]),
                            "importer" => $this->clear_text($importer[$product_id][$ps_id][$i]),
                            "service" => $this->clear_text($service[$product_id][$ps_id][$i]),
                            "delivery_day" => $delivery_day[$product_id][$ps_id][$i],
                            "no_auto" => isset($no_auto[$product_id][$ps_id][$i]) ? 1 : 0,
                            "term_use" => isset($term_use[$product_id][$ps_id][$i]) ? $term_use[$product_id][$ps_id][$i] : ""
                        );


                        if ($flag_update) {
                                $sql = "
												update product_seller as ps set 
													description = f_clear_prod_desc('{$r["description"]}'), 
													cost_us='{$r["cost_us"]}', 
													cost_by='{$r["cost_by"]}',
													wh_state = '{$r["wh_state"]}',
													garant = '{$r["garant"]}',
													delivery_day = '{$r["delivery_day"]}',
                                                    link = '{$r["link"]}',
                                                    manufacturer = '{$r["manufacturer"]}',
                                                    importer = '{$r["importer"]}',
                                                    service = '{$r["service"]}',
                                                    term_use = '{$r["term_use"]}',
                                                    setting_bit = f_setting_bit_set(ps.setting_bit, 8, {$r['no_auto']}),
													title = if(ps.title = '', (SELECT basic_name from index_product WHERE product_id = ps.product_id), ps.title)
													where id = {$ps_id}
											";
                            \Yii::$app->db->createCommand($sql)->execute();

                            $flag_update = false;
                        }
                        else
                        {
                            $res =  \Yii::$app->db->createCommand("select id from product_seller where product_id={$product_id} and seller_id={$this->seller_id} and cost_us={$r["cost_us"]} and description='{$r["description"]}'")->queryAll();
                            if (count($res)) {
                                //ignore
                            }
                            else
                            {
                                $sql = "insert into product_seller (product_id, seller_id, title, cost_us, cost_by, description, wh_state, garant, start_date, delivery_day, 
                                                link, manufacturer, importer, service, term_use, setting_bit ) values 
											({$product_id}, {$this->seller_id}, (SELECT basic_name from index_product WHERE product_id = {$product_id}), '{$r["cost_us"]}', '{$r["cost_by"]}', 
											f_clear_prod_desc('{$r["description"]}'), '{$r["wh_state"]}', '{$r["garant"]}',  UNIX_TIMESTAMP(NOW()), '{$r["delivery_day"]}',
											'{$r["link"]}','{$r["manufacturer"]}','{$r["importer"]}','{$r["service"]}','{$r["term_use"]}',f_setting_bit_set(0, 8, {$r['no_auto']}))";

                                \Yii::$app->db->createCommand($sql)->execute();
                            }
                        }
                    }
                    elseif ($ps_id)
                    {
                        if($del[$product_id][$ps_id]<0){
                           $sql = "delete from product_seller where id={$ps_id}";
                            \Yii::$app->db->createCommand($sql)->execute();
                        }
                        $flag_update = false;
                    }
                }
            }
        }
        \Yii::$app->db->createCommand('commit;')->execute();
        \Yii::$app->db->createCommand("update product_seller set start_date=UNIX_TIMESTAMP(NOW()) where seller_id={$this->seller_id}")->execute();
        \Yii::$app->db->createCommand("call pc_cost_round({$this->seller_id})")->execute();
        \Yii::$app->db->createCommand("call pc_product_sel_cost_filter_catalog({$this->seller_id},{$catalog_id})")->execute(); //10 sec
        \Yii::$app->db->createCommand("call pc_product_seller_actual_limit({$this->seller_id})")->execute(); //5 sec
        \Yii::$app->db->createCommand("call ps_seller_export_info_update({$this->seller_id})")->execute(); //10 sec
        \Yii::$app->db->createCommand("call pc_stop_word_mark_catalog({$this->seller_id},{$catalog_id})")->execute(); // 5 sec
        if($this->seller_id == 1082){
            $time = time() - $time_start;
            \Yii::info('END SAVE ALL PRODUCTS ' . $this->seller_id . " TIME: " . $time, 'debug');
        }
        exit;
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
        $vars['status'] = ProductService::getDateUpdate($this->seller_id);

        return $this->render('on-sale', $vars);
    }

    public function actionCatalog()
    {
        $catalog_id = Yii::$app->request->get("catalog_id");
        $brand = Yii::$app->request->get("brand") ?  Yii::$app->request->get("brand") : 0;
        $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
        $search = Yii::$app->request->get("search") ?  Yii::$app->request->get("search") : 0;
        $mode = Yii::$app->request->get("mode") ?  Yii::$app->request->get("mode") : 0;
        $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $setting_data = $obj_seller->setting_data;
        $curr_data = unserialize($setting_data);
        $curr = $curr_data["currency_base"];
        $vars["selected_mode_all"] = $mode ? "selected" : "";
        $vars['catalog_id'] = $catalog_id;
        $data_section =  \Yii::$app->db->createCommand("select * from v_catalog_sections where catalog_id = {$catalog_id}")->queryOne();
        $vars["catalog_name"] = $data_section['name'];
        $vars['data'] = $this->getDataCatalogProducts($catalog_id,$page,$brand,$search,$mode,$curr);
        $vars['catalog_options'] = $this->getCatalogOptions($catalog_id);
        $vars['brand_options'] = $this->getBrandOptions($catalog_id, $brand);
        $vars['pages'] = $this->getPages($catalog_id,$brand,$search,$mode,$page);
        $vars['is_goods'] = Yii::$app->request->get("goods") ?  "<input type='hidden' name='goods' value=1 />" : "";
        $vars['currency'] = $curr;
        $vars['mode'] = $mode;
        $vars['search'] = $search;

        return $this->render('catalog', $vars);
    }

    public function actionPrice()
    {
        $vars["catalog_options"] = $this->getCatalogOptionsForExport();
        $vars["cnt_all"] = $this->cnt_all;
        $vars["results"] = $this->getImportResultsHtml();
        $vars['seller_id'] = $this->seller_id;
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $vars["pay_type"] = $seller->pay_type;
        $vars['md5_seller'] = md5($this->seller_id . "panda");
        return $this->render('price', $vars);
    }


    public function actionProcess(){
        $action = Yii::$app->request->get("action");
        $order_id = Yii::$app->request->get("order_id");
        switch ($action) {
            case "refresh":
                \Yii::$app->db->createCommand("call pc_product_seller_actual({$this->seller_id});")->execute();
                return $this->redirect(['product/on-sale']);
                break;
        }
    }

    private function getCatalogOptionsForExport(){
        $res = \Yii::$app->db->createCommand("
			select cs.catalog_id as id, count(p.id) as cnt
			from products p
			inner join catalog_subject cs on (cs.subject_id=p.section_id)
			where p.is_archive = 0
			group by cs.catalog_id
			")->queryAll();
        $data = array();
        foreach ((array)$res as $r)
        {
            $data[$r["id"]] = $r["cnt"];
        }

        $html = '';
        $res = \Yii::$app->db->createCommand("select * from bill_catalog where id in (select catalog_id from bill_catalog_seller where seller_id={$this->seller_id}) order by position")->queryAll();
        foreach ((array)$res as $r)
        {
            $html_iterate = "";
            $cnt = 0;
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
                $cnt1 = isset($data[$r1["id"]]) ? $data[$r1["id"]] : "";
                //$selected = ($r1["id"] == $this->catalog_id) ? "selected" : "";
                $selected = "";
                $html_iterate .= "<option value=\"{$r1["id"]}\"{$selected}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$r1["name"]} ({$cnt1})</option>";
                $cnt += $cnt1;
            }

            $html .= "<option /><option value=\"bill_{$r["id"]}\">{$r["name"]} ({$cnt})</option>{$html_iterate}";
            $this->cnt_all += $cnt;
        }

        return $html;
    }

    private function getImportResultsHtml(){
        $res_data = file_get_contents("https://up.migom.by/?load_block=all_seller_process&mode=b2b&sid=".$this->seller_id, False);
        $res_data = unserialize($res_data);

        $res = isset($res_data[$this->seller_id]) ? $res_data[$this->seller_id] : "";
        $url = "";
        $status = "";

        if(is_array($res)){
            if (($res["module"] == "price_import_sliv") && ($res["message"] == '')) {
                $cnt = $this->get_cnt();
                $status = "<font color=\"#009900\">Обновлен</font>";
                $status .= "<br> Всего позиций: <b>{$cnt["all"]}</b><br>Добавлено предложений: <b>{$cnt["ok"]}</b>";
            }
            elseif ($res["error"] == 0 && $res["message"]<>'')
            {
                $status = $res["message"];
            }
            elseif ($res["error"] == 0)
            {
                $status = "в процессе...";
            }
            else
            {
                $message = htmlspecialchars($res["message"]);
                $cdate = $res["cdate"];
                $status = "<font color=\"#ff0000\" title=\"{$message}\">Произошла ошибка ({$message}).</font> {$cdate} <br/> Проверьте корректность формата прайса и попробуйте импорт еще раз. Если это не поможет, обратитесь в службу технической поддержки.";
            }


            if ($res["url"]){
                if (strpos($res["url"], "b2b.migom.by/files/prices") === false) {
                    $url = "<b>URL:</b> {$res["url"]}";
                }
                else
                {
                    $url = "<b>Файл:</b> " . basename($res["url"], ".{$this->seller_id}");
                }
            }

        }

        if(!empty($status)){
            $status = 'Статус: '.$status;
        }


       $html = $this->renderPartial('tmpl/import-results', array(
            "date_update" => ProductService::getDateUpdate($this->seller_id),
            "url" => $url,
            "status" => $status
        ));

        return $html;
    }

    private function getBrandOptions($catalog_id, $brand=0){
        $res =  \Yii::$app->db->createCommand("SELECT br.brand from index_brand_section_rating as br 
											inner join catalog_subject as cs on (cs.catalog_id={$catalog_id})
											WHERE br.section_id = cs.subject_id ORDER BY brand")->queryAll();

        $html = '';
        foreach ((array)$res as $r)
        {
            $selected = ($brand && ($r["brand"] == $brand)) ? "selected" : "";
            $value = $r["brand"];
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

    private function getDataCatalogProducts($catalog_id, $page=0, $brand = 0, $search = 0, $mode='', $curr){
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
						, ps.delivery_day, ps.term_use, if(ps.product_id is not null, f_prod_avgcost_check (ps.product_id, ps.cost_us), 1) AS cost_filter, f_is_setting_bit_set(ps.setting_bit, 'product_seller', 'no_auto') as no_auto
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
            $goods = Yii::$app->request->post("goods");
            $goods = isset($goods) ? $goods : Yii::$app->request->get("goods");

            if($goods){
                //$sql_join = "JOIN products as p on (pp2.product_id = p.id)";
                $sect = " and p.section_link_id";
                $no_sect = " and p.section_id != {$prop["section_id"]}";
            } else {
                $sect = " and pp2.index_section_id";
                $sql_join = "";
                $no_sect = "";
            }

            $str_sql = "select '' as brand, pp2.basic_name as model, ps.*, f_prod_avgcost_check(ps.product_id,ps.cost_us) as cost_filter, f_is_setting_bit_set(ps.setting_bit, 'product_seller', 'no_auto') as no_auto
				from product_seller ps				
				inner JOIN index_product pp2 ON (pp2.product_id = ps.product_id)
				JOIN products as p on (pp2.product_id = p.id)
				where ps.seller_id={$this->seller_id} and ps.active = 1 {$sect} = {$prop["section_id"]} {$no_sect}  {$sql_brand}{$sql_search} and p.is_archive <= 1
				order by pp2.brand_value, pp2.product_name, ps.id
				limit {$start},{$this->offset}
				";

            $res = \Yii::$app->db->createCommand($str_sql)->queryAll();
        }

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
            $r["href_product"] = "http://www.migom.by/-{$r["product_id"]}/info_seller/";
            $r["selected_{$r["wh_state"]}"] = "selected";
            $r["garant"] = preg_replace("/[^0-9]/","",$r["garant"]);
            $r["delivery_day"] = ($r["delivery_day"] == 0) ? '' : $r["delivery_day"];
            $r["term_use"] = ($r["term_use"]) ? $r["term_use"] : '';
            $r["cost_filter"] = ($r["cost_filter"] == 1) ? '' : '<span style="font-size:10px;color:#9f0000;">Подозрительная цена</span>';
            $r['checked_no_auto'] = ($r['no_auto'] == 1) ? 'checked' : '';
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
                        "cnt_goods" => $cnt_goods ? "<a style=\"text-decoration: underline\" href='{$href_goods}' target=_blank >(+ {$cnt_goods} в товарах без описания)</a>" : ""
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

    public function actionChangeCost(){
        $ps_id = Yii::$app->request->post("ps_id");
        $cost_by = Yii::$app->request->post("cost");


        $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $rate_by = $this->get_curs($obj_seller);

        $c = $cost_by * 10000;
        $cost_us = round($c / $rate_by,2);
        $cost_by = $c;

        $product_seller = ProductSeller::find()->where(['id' => $ps_id])->one();
        $product_seller->cost_us = $cost_us;
        $product_seller->cost_by = $cost_by;
        $product_seller->save();
    }

    public function actionSaveOneProd(){
       // dd(Yii::$app->request->post());
        $type = Yii::$app->request->post('type');

        $id = Yii::$app->request->post('id');
        $cost = Yii::$app->request->post('cost');
        $desc = Yii::$app->request->post('desc');
        $wh_state = Yii::$app->request->post('wh_state');
        $delivery = Yii::$app->request->post('delivery');
        $garant = Yii::$app->request->post('garant');
        $manufacturer = Yii::$app->request->post('manufacturer');
        $importer = Yii::$app->request->post('importer');
        $service = Yii::$app->request->post('service');
        $term = Yii::$app->request->post('term');
        $link = Yii::$app->request->post('link');
        $product_id = Yii::$app->request->post('product_id');
        $no_auto = Yii::$app->request->post('no_auto');

        $desc = str_replace(array("<br>", "<br />", "<br >", "<br/>"), " ", $desc);
        $desc = strip_tags($desc);
        $garant = preg_replace("/[^0-9]/","",$garant);

        $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $setting_data = $obj_seller->setting_data;
        $curr_data = unserialize($setting_data);

        $curr = $curr_data["currency_base"];
        $rate_by = $this->get_curs($obj_seller);

        if ($curr == 'byn'){
            $cost = $cost * 10000;
            $cost_us = $cost / $rate_by;
            $cost_by = $cost;
        } else {
            $cost_us = $curr == "br" ? $cost / $rate_by : $cost;
            $cost_by = $curr == "br" ? $cost : $cost * $rate_by;
        }



        if($type == 'update'){
            $ps = ProductSeller::find()->where(['id' => $id])->one();
            $ps->cost_us = $cost_us;
            $ps->cost_by = $cost_by;
            $ps->description = $desc;
            $ps->wh_state = $wh_state;
            $ps->delivery_day = $delivery;
            $ps->garant = $garant;
            $ps->manufacturer = $manufacturer;
            $ps->importer = $importer;
            $ps->service = $service;
            $ps->term_use = $term;
            $ps->link = $link;
            $ps->setting_bit = SiteService::set_bitvalue($ps->setting_bit,8,$no_auto);
            $ps->save();
            \Yii::$app->db->createCommand("update product_seller set start_date=UNIX_TIMESTAMP(NOW()) where seller_id={$this->seller_id} and product_id={$product_id}")->execute();
            echo $ps->id;
        }

        if($type == 'create'){

            $product = IndexProduct::find()->where(['product_id' => $product_id])->one();
            $ps = new ProductSeller();
            $ps->seller_id = $this->seller_id;
            $ps->product_id = $product_id;
            $ps->active = 1;
            $ps->cost_us = $cost_us;
            $ps->cost_by = $cost_by;
            $ps->description = $desc;
            $ps->wh_state = $wh_state;
            $ps->delivery_day = $delivery;
            $ps->garant = $garant;
            $ps->manufacturer = $manufacturer;
            $ps->importer = $importer;
            $ps->service = $service;
            $ps->term_use = $term;
            $ps->link = $link;
            $ps->setting_bit = SiteService::set_bitvalue(0,8,$no_auto);
            $ps->prod_sec_id = $product->index_section_id;
            $ps->save();
            echo $ps->id;
        }

        if($type == 'delete'){
            $ps = ProductSeller::find()->where(['id' => $id])->one();
            if($ps){
                $ps->delete();
            }
            echo $id;
        }

    }

    private /*Получение курса валют*/
    function get_curs($obj_seller)
    {
        if (empty($this->seller_curs)) {

            $setting_data = $obj_seller->setting_data;
            $curr_data = unserialize($setting_data);
            $curr = $curr_data["currency_base"];

            /*Если валюта белруб, то берем курс по нацбанку*/
            if($curr == 'br' || $curr == 'byn'){
                $res = \Yii::$app->db->createCommand('select rate from currency_nbrb order by id desc limit 1')->queryOne();
                $this->seller_curs = $res['rate'];
            }else{
                $this->seller_curs = $curr_data['currency_rate'];
            }

            if (empty($this->seller_curs)) {
                $res = \Yii::$app->db->createCommand('select rate from currency_nbrb order by id desc limit 1')->queryOne();
                $this->seller_curs = $res['rate'];
                //$this->seller_curs = file_get_contents("config/curs");
            }
        }
        return $this->seller_curs;
    }

    private function clear_text($text){
        $text = str_replace(array("<br>", "<br />", "<br >", "<br/>"), " ", $text);
        $text = stripcslashes($text);
        return strip_tags($text);
    }

    private function get_cnt()
    {
        $res =  \Yii::$app->db->createCommand("select * from seller_export_info where seller_id={$this->seller_id}")->queryAll();
        $res_prod = \Yii::$app->db->createCommand("select count(product_id) as cnt from product_seller where seller_id={$this->seller_id}")->queryOne();
        $cnt_ok1 = $res_prod['cnt'];
        if (count($res)) {
            $r = $res[0];
            return array("ok" => $cnt_ok1, "all" => $r["cnt_all"]);
        }
        else
        {
            $res = \Yii::$app->db->createCommand("select count(id) as cnt from product_price where seller_id={$this->seller_id} and section_id>0")->queryOne();
            $cnt_ok2 = $res['cnt'];
            $res = \Yii::$app->db->createCommand("
				select count(1) as cnt
				from price_import as t
				left join price_export_dict_ignore ti on (ti.seller_id=t.seller_id and ti.product_name=t.`name`)
				where t.seller_id={$this->seller_id} and t.product_id is null and ti.id is null				
			")->queryOne();
            $cnt_fail = $res['cnt'];

            $arr_res = array("ok" => $cnt_ok1 + $cnt_ok2, "all" => $cnt_ok1 + $cnt_ok2 + $cnt_fail);
            return $arr_res;
        }
    }
}
