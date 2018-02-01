<?php

namespace app\controllers;

use app\models\Delivery;
use app\models\DeliveryLink;
use app\models\Seller;
use app\models\SellerPlaces;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SellerController extends Controller
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

    public function actionDeliveryActions(){
        $action = Yii::$app->request->post("action");
        $action = isset($action) ? $action : Yii::$app->request->get("action");

        switch ($action) {
            case "delivery_add":
                $cost_data = Yii::$app->request->post("cost_data");
                $arr_data = array();
                if(Yii::$app->request->post("type_id") == 4){
                    foreach((array) $cost_data as $c){
                        if(($c['pay_until'] != "") && ($c['cost_until'] != "")){

                            $c["free_from"] = $c["pay_until"];
                            $arr_data[]=$c;
                        }
                    }
                }
                $json_data["cost_data"] = $arr_data;
                $json_data["cost"] = $cost_data['cost'];
                $cost_data = json_encode($json_data);
                $type_id = Yii::$app->request->post("type_id");
                $desc = Yii::$app->request->post("description");
                $delivery = new Delivery();
                $delivery->seller_id = $this->seller_id;
                $delivery->type_id = $type_id;
                $delivery->description = $desc;
                $delivery->cost_data = $cost_data;
                $delivery->save();

                foreach ((array) (Yii::$app->request->post("geo_id")) as $geo_id)
                {
                    if ($geo_id != 0){
                        $delivery_link = new DeliveryLink();
                        $delivery_link->delivery_id = $delivery->id;
                        $delivery_link->delivery_geo_id = $geo_id;
                        $delivery_link->save();
                    }
                }
                return $this->redirect(['seller/delivery']);
                break;
            case "delivery_edit":
                $cost_data = Yii::$app->request->post("cost_data");
                $id = Yii::$app->request->post("delivery_id");
                $arr_data = array();

                if(Yii::$app->request->post("type_id") == 4){
                    foreach((array) $cost_data as $c){
                        if((isset($c['pay_until']) && ($c['pay_until'] != "")) && (isset($c['cost_until'])&&($c['cost_until'] != "")) && !(isset($c['cost']))){

                            $c["free_from"] = $c["pay_until"];
                            $arr_data[]=$c;
                        }
                    }
                }
                if((Yii::$app->request->post("type_id") == 4) ){
                    $json_data["cost_data"] = $arr_data;
                    $json_data["cost"] = "";
                    $cost_data = json_encode($json_data);
                } elseif((Yii::$app->request->post("type_id") == 3)){
                    $json_data["cost_data"] = array();
                    $json_data["cost"] = $cost_data['cost'];
                    $cost_data = json_encode($json_data);
                } else {
                    $cost_data = '{"cost_data":[],"cost":""}';
                }
                $desc = Yii::$app->request->post("description");
                $type_id = Yii::$app->request->post("type_id");
                if(isset($id) && ($id > 0)){
                    $delivery = Delivery::find()->where(['id' => $id])->one();
                    $delivery->type_id = $type_id;
                    $delivery->description = $desc;
                    $delivery->cost_data = $cost_data;
                    $delivery->save();
                }

                return $this->redirect(['seller/delivery']);
                break;

            case "delivery_delete":
                $id = Yii::$app->request->get("id");
                \Yii::$app->db->createCommand("delete from delivery where id={$id}")->execute();
                \Yii::$app->db->createCommand("delete from delivery_link where delivery_id={$id}")->execute();
                return $this->redirect(['seller/delivery']);
                break;
            case "delivery_get_edit_data":
                $delivery_id = Yii::$app->request->get("delivery_id");
                $r = array();
                $data = \Yii::$app->db->createCommand("select d.*,
						(SELECT GROUP_CONCAT(name SEPARATOR ', ') from delivery_geo where id in (select delivery_geo_id from delivery_link where delivery_id=d.id)) as geo_id
						from delivery as d
						inner join delivery_type t on (t.id=d.type_id)
						where d.id={$delivery_id} ")->queryAll();

                $r['geo_id'] = $data[0]['geo_id'];
                $r['description'] = $data[0]['description'];
                $r['delivery_id'] = $delivery_id;

                $json = json_decode($data[0]['cost_data'], true);
                $r['cost'] = $json['cost'];
                $cost_items = $json['cost_data'];
                $items = "";
                if(count($cost_items) > 0){

                    foreach((array) $cost_items as $key => $c){
                        $c['key'] = $key;
                        $items .= $this->renderPartial("tmpl/delivery_form_item", $c);
                    }

                } else {
                    $c['key'] = 0;
                    $items = $this->renderPartial("tmpl/delivery_form_item", $c);
                }
                $r['cost_items'] = $items;
                $html = $this->renderPartial("tmpl/delivery_form", $r);
                $res['html'] =  $html;
                $res['id'] = $data[0]['type_id'];
                //print_r( $res);
                echo json_encode($res, true);
                break;
            case "set_f_post":
                $sql = "update seller set f_post = if(f_post=0,1,0) where id = " . $this->seller_id;
                \Yii::$app->db->createCommand($sql)->execute();
                return $this->redirect(['seller/delivery']);
                break;
        }
    }

    public function actionPlaceActions(){
        $action = Yii::$app->request->post("action");
        $action = isset($action) ? $action : Yii::$app->request->get("action");

        switch ($action) {
            case "places_add":

                $type = array_sum(Yii::$app->request->post("type"));
                $city = Yii::$app->request->post("city");
                $street = Yii::$app->request->post("street");
                $house = Yii::$app->request->post("house");
                $flat = Yii::$app->request->post("flat");
                $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
                $seller_place = new SellerPlaces();
                $seller_place->city = $city;
                $seller_place->street = $street;
                $seller_place->house = $house;
                $seller_place->flat = $flat;
                $seller_place->seller_id = $this->seller_id;
                $seller_place->type = $type;
                $seller_place->save();


                //update f_auto
                $res = \Yii::$app->db->createCommand("select count(1) as cnt from seller_places where seller_id={$this->seller_id} and type>=1")->queryAll();
                $obj_seller->f_auto = ($res[0]['cnt'] > 0) ? 1 : 0;
                $obj_seller->save();

                $name = urlencode($obj_seller->name);
                $city = urlencode($seller_place->city);
                $address = urlencode($seller_place->street. ", ".$seller_place->house);
                $str = "http://maps.migom.by/api/organisation/createorganisation/?seller_id={$this->seller_id}&name={$name}&id={$seller_place->id}&city={$city}&address={$address}&t={$type}&flat={$seller_place->flat}";

                file_get_contents($str);
                return $this->redirect(['seller/delivery']);
                break;
            case "place_delete":
                $id = Yii::$app->request->get("id");
                \Yii::$app->db->createCommand("delete from seller_places where seller_id={$this->seller_id} and id={$id}")->execute();

                //update f_auto
                $res = \Yii::$app->db->createCommand("select count(1) as cnt from seller_places where seller_id={$this->seller_id} and type>=1")->queryAll();

                $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
                $obj_seller->f_auto = ($res[0]['cnt'] > 0) ? 1 : 0;
                $obj_seller->save();

                $str = "http://maps.migom.by/api/organisation/DelOrganisation/?id=".$id;

                file_get_contents($str);
                return $this->redirect(['seller/delivery']);
                break;
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        return $this->render('settings');
    }

    public function actionDelivery()
    {
        $res = \Yii::$app->db->createCommand("select * from seller_places where seller_id={$this->seller_id} order by id")->queryAll();
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $vars['data'] = '';
        foreach ((array) $res as $r)
        {
            $type = array();
            if ($r["type"] & 1)
                $type[] = "салон-магазин";
            if ($r["type"] & 2)
                $type[] = "пункт выдачи";
            $r["type"] = join(", ", $type);
            $vars["data"] .= $this->renderPartial("tmpl/place", $r);
        }

        $res = \Yii::$app->db->createCommand("
					select d.*,
						(SELECT GROUP_CONCAT(name SEPARATOR ', ') from delivery_geo where id in (select delivery_geo_id from delivery_link where delivery_id=d.id)) as geo_id,
						t.name as type_id
					from delivery as d
					inner join delivery_type t on (t.id=d.type_id)
					where seller_id={$this->seller_id} order by d.id
				")->queryAll();
        $vars['data_delivery'] ="";
        foreach ((array) $res as $r)
        {
            if (isset($r["cost_data"])){
                $r = array_merge($r, json_decode($r["cost_data"], true));
            }
            $r['cost'] = isset($r['cost']) && ($r['cost'] > 0) ? "<b>{$r['cost']} руб.</b> " : "";
            if (isset($r['pay_until']) && isset($r['cost_until'])){
                $r['cost'] = "При заказе до <b>{$r['pay_until']} руб.</b> доставка <b>{$r['cost_until']} руб.</b>";
            }
            if ($r['cost_data'] && ($r['type_id'] == 'Зависит от заказа')){
                foreach ((array) $r['cost_data'] as $c){
                    $r['cost'] .= "При заказе до <b>{$c['pay_until']} руб.</b> доставка <b>{$c['cost_until']} руб.</b><br>";
                }
            }
            $vars["data_delivery"] .= $this->renderPartial("tmpl/delivery", $r);
        }


        $select_delivery = \Yii::$app->db->createCommand("SELECT * from delivery_geo where id in (636, 637, 639, 640, 638, 641) and id not in (SELECT delivery_geo_id from delivery as d
														join delivery_link as dl on (dl.delivery_id = d.id)
														join delivery_geo as dg2 on (dg2.id = dl.delivery_geo_id)
														 WHERE seller_id = {$this->seller_id})")->queryAll();
        $city_select = "";
        foreach ((array) $select_delivery as $r)
        {
            if($r['id'] == 636){
                $vars['delivery_minsk'] = "<label class='btn btn-primary active' onclick=\"$('#city_select').hide();$('#region_check').hide();$('#geo_4 option').prop('selected', false);$('.chosen').chosen();\">
								<input name=\"geo_id[]\" value=\"{$r['id']}\" type=\"radio\" autocomplete=\"off\" checked> {$r['name']}
							  </label>";
            } else {
                $vars['delivery_regions'] = "<label class=\"btn btn-primary\" onclick=\"$('#geo_4 option').prop('selected', true);$('.chosen').chosen();$('#city_select').show();$('#region_check').show();\">
								<input type=\"radio\" name=\"geo_id[]\" value=0  autocomplete=\"off\"> Беларусь
							  </label>";
                $city_select .= "<option value=\"{$r['id']}\">{$r['name']}</option>";
            }
        }
        $vars['delivery_select'] = "<select class='chosen' name=\"geo_id[]\" multiple style=\"width: 300px;height: 150px; margin-top: 3px;\" id=\"geo_4\">{$city_select}</select>";
        $vars['display_delivery_type'] = (isset($vars['delivery_regions']) || isset($vars['delivery_minsk'])) ? "" : "display:none";

        $vars["checked_f_post"] = (isset($seller->f_post) && $seller->f_post) ? "checked" : "";
        $vars["data_geo"] = $this->get_geo_html();
        return $this->render('delivery', $vars);
    }

    private function get_geo_html()
    {
        $html = "";
        $res = \Yii::$app->db->createCommand("
			select g1.id as owner_id, g1.name as owner_name, g3.id as id, g3.name as name
			from delivery_geo g1
			inner join delivery_geo g2 on (g2.owner_id=g1.id)
			inner join delivery_geo g3 on (g3.owner_id=g2.id)
			where g1.owner_id=1 and g3.id<>636
			order by g1.name, g3.name
		")->queryAll();
        $data = array();

        foreach ((array) $res as $r)
        {
            $o = "<option value=\"{$r["id"]}\">&nbsp;&nbsp;&nbsp;{$r["name"]}</option>";

            if (array_key_exists($r["owner_id"], $data))
            {
                $data[$r["owner_id"]]["data"][] = $o;
            }
            else
            {
                $data[$r["owner_id"]] = array("id" => $r["id"], "name" => $r["owner_name"], "data" => array($o));
            }
        }

        foreach ($data as $r)
        {
            $opts = join("", $r["data"]);
            $html .= "<optgroup label=\"{$r["name"]}\">{$opts}</optgroup>";
        }

        return $html;
    }
}
