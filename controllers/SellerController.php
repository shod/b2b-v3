<?php

namespace app\controllers;

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
        $vars['data'] = '';
        foreach ((array) $res as $r)
        {
            $type = array();
            if ($r["type"] & 1)
                $type[] = "салон-магазин";
            if ($r["type"] & 2)
                $type[] = "пункт выдачи";
            $r["type"] = join(", ", $type);
            //$r["href"] = $whirl->parms->create_url(array("block" => "content_settings", "action" => "place_delete", "id" => $r["id"]));
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
            //$r["href"] = $whirl->parms->create_url(array("block" => "content_settings", "action" => "delivery_delete", "id" => $r["id"]));
            if (isset($r["cost_data"])){
                $r = array_merge($r, json_decode($r["cost_data"], true));
            }
            $r['cost'] = isset($r['cost']) ? "{$r['cost']} руб. " : "";
            if (isset($r['pay_until']) && isset($r['cost_until'])){
                $r['cost'] = "При заказе до {$r['pay_until']} руб. доставка {$r['cost_until']} руб.";
            }
            if ($r['cost_data'] && ($r['type_id'] == 'Зависит от заказа')){
                foreach ((array) $r['cost_data'] as $c){
                    $r['cost'] .= "При заказе до {$c['pay_until']} руб. доставка {$c['cost_until']} руб.<br>";
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
        $vars['display_delivery_type'] = ($vars['delivery_regions'] || $vars['delivery_minsk']) ? "" : "display:none";

        $vars["checked_f_post"] = (isset($vars["f_post"]) && $vars["f_post"]) ? "checked" : "";
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
