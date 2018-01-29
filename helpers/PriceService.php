<?php
namespace app\helpers;
use app\models\Seller;
use yii;

class PriceService
{
    public static function getCsv($seller_id, $type, $catalog_id=0)
    {
        $data_header[] = array('Категория','Название','Цена','Примечания','Ссылка на страницу','Ссылка на картинку','Наличие','Срок доставки','Гарантия','Срок службы (месяцы)','Безнал','Кредит','Рассрочка','Доставка по Минску','Доставка по стране','Изготовитель','Импортер','Сервисные центры');
        $obj_seller = Seller::find()->where(['id' => $seller_id])->one();
        $name = $obj_seller->name;
        $filename = "price_{$name}";

        if($type == 'my_price'){
            $data2 = PriceService::getDataSellerPrice($seller_id, $obj_seller);
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename={$filename}.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            header('Content-Encoding: UTF-8');
            header('Content-type: text/csv; charset=UTF-8');
            echo "\xEF\xBB\xBF"; // UTF-8 BOM

            $data_output = array_merge($data_header,$data2);
            PriceService::outputCSV($data_output);
        }

        if($type == 'price_template'){
            PriceService::getDataTemplate($seller_id, $obj_seller, $catalog_id, $data_header);
        }

    }

    public static function getDataSellerPrice($seller_id, $obj_seller){
        $setting_data = $obj_seller->setting_data;
        $curr_data = unserialize($setting_data);
        $curr = $curr_data["currency_base"];
        if($obj_seller->pay_type == 'fixed'){
            $goods_to = ($seller_id == 4956) ? 'id' : 'owner_id';
            $res = \Yii::$app->db->createCommand(" select cown.id, cown.name
                        from v_bill_seller_tarif_catalog as vcs, 
                            catalog as c, 
                            catalog as cown
                        where 
                            vcs.seller_id = {$seller_id} 
                            and c.id = vcs.catalog_id
                            and cown.id = c.{$goods_to}
                        group by cown.id, cown.name
                        order by cown.position")->queryAll();
        } else {
            $res = \Yii::$app->db->createCommand(" SELECT c.id, c.name from catalog as c
                                        left join catalog as co on (c.id = co.owner_id)
                                        LEFT JOIN bill_click_catalog_blacklist as bcc on (bcc.seller_id = {$seller_id} and bcc.catalog_id = co.id)
                                        WHERE c.owner_id = 0 and bcc.id is NULL
                                        GROUP BY c.id ORDER BY c.position")->queryAll();
        }
        $data = array();
        foreach ((array)$res as $r)
        {
            $html_iterate = "";
            $res1 = \Yii::$app->db->createCommand("
				select id, name from catalog where owner_id ={$r["id"]} and hidden=0 order by name
				")->queryAll();
            if (count($res1) == 0){
                $res1 = \Yii::$app->db->createCommand("
				select id, name from catalog where id ={$r["id"]} and hidden=0 order by name
				")->queryAll();
            }
            $data1 = array();

            foreach ((array)$res1 as $r1)
            {
                $res = \Yii::$app->db->createCommand("
					select p.id as madeid, p1.id as modelid, p.section_id, cs.name
					from properties p, properties p1, v_catalog_sections cs
					where cs.catalog_id={$r1["id"]} and cs.f_main=1 and p.section_id=cs.section_id and p.type=2 and p1.section_id=p.section_id and p1.type=4
				")->queryAll();
                $prop = $res[0];
                $res2 = \Yii::$app->db->createCommand("
					select SQL_BUFFER_RESULT 
                            ip.basic_name as name, 
                            ps.*
                        from product_seller ps, 
                            index_product ip, 
                            catalog_subject as cs/*, 
                            index_brand as ib*/
                        where cs.catalog_id={$r1["id"]} 
                            and cs.f_main=1
                            and ps.seller_id={$seller_id} 
                            and ps.active=1 
                            and ps.wh_state<3 and ps.cost_us>0 
                            and ip.product_id=ps.product_id
                            and ip.index_section_id = cs.subject_id 
                            /*and ib.id = ip.brand_id*/
                        order by ip.basic_name
				")->queryAll();


                //$data2 = array();
                $wh_state_list = array('Уточнить', 'В наличии', 'Под заказ', "Отсутствует");
                $bool_list = array('', 'да','нет');
                $pay_list = array('', 'платная','бесплатная');
                foreach ((array)$res2 as $r2)
                {
                    if ($curr == 'byn'){
                        $cost_price = $r2["cost_by"]/10000;
                    } else {
                        $cost_price = ($curr == "br") ? $r2["cost_by"] : $r2["cost_us"];
                    }
                    //$r2["id"] = $r2["product_id"];
                    $rd["catalog_name"] = $prop["name"];
                    $rd["name"] = addslashes("{$r2["name"]}");
                    $rd["cost"] = $cost_price;
                    $rd["description"] = addslashes($r2["description"]);
                    $rd["link"] = $r2["link"];
                    $rd["img"] = $r2["img_url"];
                    $rd["wh_state"] = $wh_state_list[$r2["wh_state"]];
                    $rd["delivery_day"] = ($r2["delivery_day"] == 0 ) ? '' : $r2["delivery_day"];
                    $rd["garant"] = $r2["garant"];
                    $rd["term_use"] = ($r2["term_use"] == 0) ? '' : $r2["term_use"];
                    $rd["beznal"] = isset($r2["beznal"]) ? $bool_list[$r2["beznal"]] : "";
                    $rd["credit"] = isset($bool_list[$r2["credit"]]) ? $bool_list[$r2["credit"]] : "";
                    $rd["rassrochka"] = isset($bool_list[$r2["rassrochka"]]) ? $bool_list[$r2["rassrochka"]] : "";
                    $rd["delivery_m"] = isset($pay_list[$r2["delivery_m"]]) ? $pay_list[$r2["delivery_m"]] : "";
                    $rd["delivery_rb"] = isset($pay_list[$r2["delivery_rb"]]) ? $pay_list[$r2["delivery_rb"]] : "";
                    $rd["manufacturer"] = $r2["manufacturer"];
                    $rd["importer"] = $r2["importer"];
                    $rd["service"] = $r2["service"];
                    $data2[] = $rd;
                }
                if (isset($data2) && count($data2)) {
                    $data1[] = array("id" => $r1["id"], "name" => $r1["name"], "data" => $data2);
                }
            }
            if (isset($data1) && count($data1)) {
                $data[] = array("id" => $r["id"], "name" => $r["name"], "data" => $data1);
            }
        }
        return $data2;
    }

    public static function getDataTemplate($seller_id, $obj_seller, $catalog_id, $data_header){
        $filename = "price_{$obj_seller->name}";
        $tmp_filename = "price_{$seller_id}.csv";
        $tmp_filename = 'files/'.$tmp_filename;
        //unlink($tmp_filename);
        $sql_catalog = "";
        if ($catalog_id) {
            $bill_catalog_id = str_replace("bill_", "", $catalog_id);
            if ($bill_catalog_id == $catalog_id) {
                // раздел
                $res = \Yii::$app->db->createCommand("
					select *
					from bill_catalog
					where id in (
						select catalog_id
						from bill_catalog_seller
						where seller_id={$seller_id}
					) and id in (select catalog_id from bill_catalog_section where section_id in (
						select subject_id from catalog_subject where catalog_id={$catalog_id}
						))
					")->queryAll();
                $sql_catalog = " and id={$catalog_id}";
            } else
                $res = \Yii::$app->db->createCommand("select * from bill_catalog where id={$bill_catalog_id}")->queryAll();
        }
        else
        {
            $res = \Yii::$app->db->createCommand("select * from bill_catalog where id in (select catalog_id from bill_catalog_seller where seller_id={$seller_id}) order by position")->queryAll();
        }
        $data = array();
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
				) and hidden=0 {$sql_catalog}
				order by name
				")->queryAll();
            $data1 = array();
            foreach ((array)$res1 as $r1)
            {
                $res = \Yii::$app->db->createCommand("
					select p.id as madeid, p1.id as modelid
					from properties p, properties p1, catalog_subject cs
					where cs.catalog_id={$r1["id"]} and cs.f_main=1 and p.section_id=cs.subject_id and p.type=2 and p1.section_id=p.section_id and p1.type=4
					")->queryAll();
                $prop = $res[0];

                $res2 = \Yii::$app->db->createCommand("
					select pp2.value as brand, pp1.value as model
					from product_properties pp1
					inner join products as prod on (prod.id=pp1.product_id)
					left join product_properties pp2 on (pp2.product_id=pp1.product_id and pp2.property_id={$prop["madeid"]})
					where pp1.property_id={$prop["modelid"]} and prod.is_archive=0
					order by pp1.value, pp2.value
					")->queryAll();
                $data2 = array();
                foreach ((array)$res2 as $r2)
                {

                    //$r2["id"] = $r2["product_id"];
                    $r2["name"] = addslashes("{$r2["brand"]} {$r2["model"]}");
                    $data2[] = $r2;
                    unset($r2);
                }
                if (count($data2)) {
                    $data1[] = array("id" => $r1["id"], "name" => $r1["name"], "data" => $data2);
                }
            }
            if (count($data1)) {
                $data[] = array("id" => $r["id"], "name" => $r["name"], "data" => $data1);
            }
        }

        unset($data2);
        unset($data1);

        //parsing
        $out_data = "";
        $out_data2 = "";
        $si=0;

        $file = fopen($tmp_filename,"w");

        foreach ((array)$data as $r)
        {
            $out_data1 = "";
            foreach ((array)$r["data"] as $r1)
            {

                foreach ((array)$r1["data"] as $r2)
                {
                    $si++;
                    $rd["catalog_name"] = str_replace(",", " ", $r1["name"]);
                    $rd["name"] = addslashes("{$r2["brand"]} {$r2["model"]}");
                    $rd["cost"] = '';
                    $rd["description"] = '';
                    $rd["link"] = '';
                    $rd["img"] = '';
                    $rd["wh_state"] = '';
                    $rd["delivery_day"] = '';
                    $rd["garant"] = '';
                    $rd["term_use"] = '';
                    $rd["beznal"] = '';
                    $rd["credit"] = '';
                    $rd["rassrochka"] = '';
                    $rd["delivery_m"] = '';
                    $rd["delivery_rb"] = '';
                    $rd["manufacturer"] = '';
                    $rd["importer"] = '';
                    $rd["service"] = '';
                    //$out_data2[] = $rd;
                    fputcsv($file,$rd);
                    unset($rd);
                }
                /*if (count($out_data2)) {
                    $out_data1[] = array("id" => $r1["id"], "name" => $r1["name"], "data" => $out_data2);
                }*/

            }
            unset($out_data1);
            /*if (count($out_data1)) {
                $out_data[] = array("id" => $r["id"], "name" => $r["name"], "data" => $out_data1);
            }*/
        }

        //$out = $whirl->processor->process_template(null, "content_products", "export/csv/index", array("data" => $out_data));

        fclose($file);

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$filename}.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        PriceService::outputCSVfomFile($tmp_filename, $data_header);
        //outputCSV($data_output);
        unlink($tmp_filename);
    }

    /*Вывод данных в поток вывода*/
    public static function outputCSV($list) {
        $output = fopen("php://output", "w");

        foreach ($list as $row) {
            fputcsv($output, $row, ';');
        }
        fclose($output);
    }

    public static function outputCSVfomFile($fileoutput, $header) {
        $output = fopen("php://output", "w");
        $handle = fopen($fileoutput , "r");

        if ($handle) {
            fputcsv($output, $header[0], ';');
            while (($line = fgets($handle)) !== false) {
                $line = str_replace('"','',$line);
                fputcsv($output, explode(',',$line), ';');
            }
            fclose($handle);
        } else {
            echo 'Ошибка формирования прайса!';
        }

        fclose($output);
    }
}