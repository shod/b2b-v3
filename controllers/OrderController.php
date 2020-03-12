<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\IndexProduct;
use app\models\Seller;
use app\models\SellerInfo;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class OrderController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    public $kurs;
    public $offset = 20;
    public $po_types = [
        30 => 5,
        100 => 10
    ];

    public function beforeAction($action) {
        if ((\Yii::$app->getUser()->isGuest)&&($action->id != 'login')&&($action->id != 'sign-up')) {
            $this->redirect('/site/login');
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

    public function actionGetChartSms(){
        $sql = "select *, concat(SUBSTR(mdate,1,4),'-',SUBSTR(mdate,5)) as sms_date from po_history
                            WHERE
                                seller_id = {$this->seller_id}
                                and  mdate > DATE_FORMAT(DATE_ADD(NOW(),INTERVAL -15 MONTH),'%Y%m')
                            ORDER BY
                                id DESC limit 20";
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $array_data = array();
        foreach((array)$data as $r)
        {
            $a['date_view'] = date($r['sms_date']);
            $a['cnt'] = $r['cnt'];
            $array_data[]= $a;
        }
        $json = Json::encode($array_data);
        echo $json;
        exit;
    }

    public function actionProcess(){
        $action = Yii::$app->request->get("action");
        $order_id = Yii::$app->request->get("order_id");
        switch ($action) {
            case "complete":
                \Yii::$app->db->createCommand("update po_order set status=1, done_at='".date("Y-m-d H:i:s")."' where id={$order_id}")->execute();
                echo $this->getHistoryRow($order_id);
                break;
            case "rejected":
                \Yii::$app->db->createCommand("update po_order set status=-1, done_at='".date("Y-m-d H:i:s")."' where id={$order_id}")->execute();
                echo $this->getHistoryRow($order_id);
                break;
            case "processorder":
                \Yii::$app->db->createCommand("update po_order set status=2, done_at='".date("Y-m-d H:i:s")."' where id={$order_id}")->execute();
                echo $this->getHistoryRow($order_id);
                break;
            case "active":
                \Yii::$app->db->createCommand("update seller_info set po_active=1 where seller_id={$this->seller_id}")->execute();
                echo 'Активация прошла успешно!';
                break;
            case "deactive":
                \Yii::$app->db->createCommand("update seller_info set po_active=0 where seller_id={$this->seller_id}")->execute();
                echo 'Деактивация прошла успешно!';
                break;
            case "edit-phone":
                $edit_data ='375'. Yii::$app->request->get("val");
                \Yii::$app->db->createCommand("update seller_info set po_phone='{$edit_data}' where seller_id={$this->seller_id}")->execute();
                echo 'Телефон для уведомлений успешно отредактирован!';
                break;
            case "edit-email":
                $edit_data = Yii::$app->request->get("val");
                \Yii::$app->db->createCommand("update seller_info set po_email='{$edit_data}' where seller_id={$this->seller_id}")->execute();
                echo 'Email для уведомлений успешно отредактирован!';
                break;
            case "delete-history":
                if($this->seller_id){
                    \Yii::$app->db->createCommand("update po_order set seller_id = 100 where seller_id = {$this->seller_id} and status != 0 and status != 2")->execute();
                }
                return $this->redirect(['order/sms']);
                break;
            case "addsms":
                $count = Yii::$app->request->get("count");
                $result = \Yii::$app->billing->transaction($this->seller_id, 'down_posms', ['po_balance_count' => $count, 'value' => $this->po_types[$count]]);
                echo json_encode(array('success'=>$result));
                break;
            case "challenge":
                \Yii::$app->db->createCommand("update po_order set status=3, done_at='".date("Y-m-d H:i:s")."' where id={$order_id}")->execute();                
                \app\helpers\SysService::sendEmail(Yii::$app->params['reportEmail'], \Yii::$app->params['migom_name'].' - Оспорить заказ #'.$order_id, Yii::$app->params['fromEmail'], $text = "Продавец {$this->seller_id} Order_id #".$order_id);
                echo $this->getHistoryRow($order_id);                
                break;            
        }
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSms()
    {
        $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
        $obj_seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $setting_data = $obj_seller->setting_data;
        $res = unserialize($setting_data);
        $this->kurs = $res["currency_rate"];

        $data_history = $this->history($page);
        $vars['history'] = $data_history['data_history'];
        $vars['history_pages'] = $data_history['pages'];
        $vars['orders'] = $this->orders();

        $info_seller = SellerInfo::find()->where(["seller_id" => $this->seller_id])->one();

        if(!empty($info_seller)) {
            $vars['po_balance'] = $info_seller->po_balance;
            $vars['notice_phone'] = $info_seller->po_phone;

            if(!empty($info_seller->po_phone)) {
                $vars['notice_zone_code'] = "+375";
                $vars['notice_phone_var'] = "+".substr($vars['notice_phone'],0,3)." ".substr($vars['notice_phone'],3,2).htmlspecialchars_decode(" <b>".substr($vars['notice_phone'],5,7)."</b>");
                $vars['notice_phone'] = substr($vars['notice_phone'],3,9);
            } else {
                $vars['notice_zone_code'] = "+375";
                $vars['notice_phone_var'] = "введите номер";
            }

            if($info_seller->po_active == 1) {
                $vars['po_active'] = "Выключить услугу";
                $vars['id_active'] = "checked";
            } else {
                $vars['po_active'] = "Включить услугу";
                $vars['id_active'] = "";
            }
            if(!empty($info_seller->po_email)) {
                $vars['notice_email'] = stripcslashes($info_seller->po_email);
                $vars['notice_email_var'] = $info_seller->po_email;
            } else {
                $vars['notice_email_var'] = "введите e-mail";
            }
        } else {
            $vars['po_active'] = "Включить услугу";
            $vars['notice_phone_var'] = "введите номер";
            $vars['notice_email_var'] = "введите e-mail";
        }
        return $this->render('sms', $vars);
    }

    private function orders(){
        $orders = \Yii::$app->db->createCommand("
            select c.phone, c.name, o.product_id, o.id as order_id, o.cost_us, o.created_at, o.status, p.section_id, vcs.name as section_name, o.product_title, o.description
            from po_order as o
            left join po_contact as c on (o.po_contact_id = c.id)
            left join products as p on (p.id = o.product_id)
            left join v_catalog_sections as vcs on (vcs.section_id = p.section_id)
            where o.seller_id = {$this->seller_id} and (o.status = 0 or o.status = 2)
			order by o.created_at desc
		")->queryAll();
        $data_orders = "";
        if(!empty($orders)) {
            foreach($orders as $ar) {
                $r = [
                    "order_id" => $ar['order_id'],
                    "phone" => substr($ar['phone'],0,3)." ".substr($ar['phone'],3,2).htmlspecialchars_decode(" <b>".substr($ar['phone'],5,7)."</b>"),
                    "phone_tel" => $ar['phone'],
                    "user_name" => $ar['name'],
                    "product_name" => $ar['product_title'] ? $ar['product_title'] : IndexProduct::find()->where(["product_id" => $ar['product_id']])->one()->basic_name,
                    "cost_us" => $this->parseCostBy(round($ar['cost_us'])*10000),
                    "cost_by" => $this->parseCostBy($ar['cost_us']),
                    "cost_byn" => $ar['cost_us'],
                    "time_at" => date("H:i",strtotime($ar['created_at'])),
                    "date_at" => date("d.m.Y",strtotime($ar['created_at'])),
                    "section_name" => $ar['section_name'],
                    "status" => $ar['status'],
                    "description" => $ar['description'],
                    "product_id" => $ar['product_id'],
                    "product_href" => "http://www." . Yii::$app->params['redirect_domain'] . "/-{$ar["product_id"]}/info_seller/"
                ];
                $data_orders .= $this->renderPartial('tmpl/sms-order-row', $r);
            }
        } else {
            $data_orders = "Список заказов пуст";
        }
        return $data_orders;
    }

    private function history($page=0) {
        $start = $page*$this->offset;
        $history = \Yii::$app->db->createCommand("
            select c.phone, c.name, o.product_id, o.id as order_id, o.cost_us, o.created_at, o.status, o.product_title, DATEDIFF(NOW(),o.created_at) as day_order, o.description
                , o.admin_description,
			(select count(*) from po_order where seller_id = {$this->seller_id} and (status = 1 or status = -1)) as all_count, p.section_id, vcs.name as section_name
            from po_order as o
            left join po_contact as c on (o.po_contact_id = c.id)
            left join products as p on (p.id = o.product_id)
            left join v_catalog_sections as vcs on (vcs.section_id = p.section_id)
            where o.seller_id = {$this->seller_id} and (o.status = 1 or o.status = -1 or o.status > 2)
			order by o.created_at desc
            limit {$start},{$this->offset}
		")->queryAll();
        $data_history = "";
        if(!empty($history)) {
            foreach($history as $ar) {
                if($ar['status'] == '1') {$status_order = "<span style='color:rgba(17,188,16,0.93)'>Доставлен</span>";}
                if($ar['status'] == '-1') {$status_order = "<span style='color:rgba(214,0,38,0.9)'>Отклонен</span>";}
                if($ar['status'] == '2') {$status_order = "<span style='color:rgba(47,0,185,0.91)'>Обработан</span>";}
                if($ar['status'] == '3') {$status_order = "<span style='color:#ffb300'>Спор</span>";}
                if($ar['status'] == '4') {$status_order = "<span style='color:rgba(47,0,185,0.91)'>Возврат средств</span>";}
                if($ar['status'] == '5') {$status_order = "<span style='color:rgba(47,0,185,0.91)'>Спор отклонен</span>";}
                if($ar['status'] == '0') {$status_order = "";}
                
                $is_btn_challenge = FALSE;
                if($ar['status'] == -1 && $ar['day_order'] <=3){
                    $is_btn_challenge = TRUE;
                }
                
                if(strlen($ar['admin_description'])>0){
                    $ar['admin_description'] = 'Ответ модератора: ' . $ar['admin_description'];
                }
                
                $r = [
                    "order_id" => $ar['order_id'],
                    "phone" => substr($ar['phone'],0,3)." ".substr($ar['phone'],3,2).htmlspecialchars_decode(" <b>".substr($ar['phone'],5,7)."</b>"),
                    "user_name" => $ar['name'],
                    "product_name" => $ar['product_title'] ? $ar['product_title'] : IndexProduct::find()->where(["product_id" => $ar['product_id']])->one()->basic_name,
                    "cost_us" => $this->parseCostBy($ar['cost_us']*10000),
                    "cost_by" => $this->parseCostBy($ar['cost_us'] * $this->kurs),
                    "cost_byn" => $ar['cost_us'],
                    "time_at" => date("H:i",strtotime($ar['created_at'])),
                    "date_at" => date("d.m.Y",strtotime($ar['created_at'])),
                    "class_order" => $status_order,
                    "section_name" => $ar['section_name'],
                    "status" => $ar['status'],
                    "description" => $ar['description'],
                    "admin_description" => $ar['admin_description'],                    
                    "is_btn_challenge" => $is_btn_challenge
                ];

                $data_history .= $this->renderPartial('tmpl/sms-history-row', $r);
            }
            $count = $history[0]['all_count'];
            $page_all = ceil($count / $this->offset);
            $first = 1;
            $last = $page_all;
            $url = "/order/sms/?";

            $pages = SiteService::get_pages($page, $first, $last, $url);

        } else {
            $data_history = "Список истории пуст";
            $pages = "";
        }


        //$data_history = "{$data_history}<br/>".$page_str;

        return ['data_history' => $data_history, 'pages' => $pages];
    }

    private function getHistoryRow($order_id){
        $order = \Yii::$app->db->createCommand("
            select c.phone, c.name, o.product_id, o.id as order_id, o.cost_us, o.created_at, o.status,
			 p.section_id, vcs.name as section_name, DATEDIFF(NOW(),o.created_at) as day_order
            from po_order as o
            left join po_contact as c on (o.po_contact_id = c.id)
            left join products as p on (p.id = o.product_id)
            left join v_catalog_sections as vcs on (vcs.section_id = p.section_id)
            where o.seller_id = {$this->seller_id} and o.id = {$order_id}
		")->queryOne();
        if($order['status'] == '1') {$status_order = "<span style='color:rgba(17,188,16,0.93)'>Доставлен</span>";}
        if($order['status'] == '-1') {$status_order = "<span style='color:rgba(214,0,38,0.9)'>Отклонен</span>";}
        if($order['status'] == '2') {$status_order = "<span style='color:rgba(47,0,185,0.91)'>Обработан</span>";}
        if($order['status'] == '3') {$status_order = "<span style='color:#ffb300'>Спор</span>";}
        if($order['status'] == '4') {$status_order = "<span style='color:rgba(47,0,185,0.91)'>Возврат средств</span>";}
        if($order['status'] == '5') {$status_order = "<span style='color:rgba(47,0,185,0.91)'>Спор отклонен</span>";}
        if($order['status'] == '0') {$status_order = "";}
        
        $is_btn_challenge = FALSE;
        if($order['status'] == -1 && $order['day_order'] <=3){
            $is_btn_challenge = TRUE;
        }
        
        $r = [
            "order_id" => $order['order_id'],
            "phone" => substr($order['phone'],0,3)." ".substr($order['phone'],3,2).htmlspecialchars_decode(" <b>".substr($order['phone'],5,7)."</b>"),
            "user_name" => $order['name'],
            "product_name" => IndexProduct::find()->where(["product_id" => $order['product_id']])->one()->basic_name,
            "cost_us" => $this->parseCostBy($order['cost_us']),
            "cost_by" => $this->parseCostBy($order['cost_us'] * $this->kurs),
            "cost_byn" => $order['cost_us']/10000,
            "time_at" => date("H:i",strtotime($order['created_at'])),
            "date_at" => date("d.m.Y",strtotime($order['created_at'])),
            "class_order" => $status_order,            
            "section_name" => $order['section_name'],
            "status" => $order['status'],
            "is_btn_challenge" => $is_btn_challenge
        ];

        $tr = $this->renderPartial('tmpl/sms-history-row', $r);
        return $tr;
    }

    private function parseCostBy($cost) {
        $cost = strrev($cost);
        $cost = substr($cost,0,3)." ".substr($cost,3,3)." ".substr($cost,6,3);
        $cost = trim(strrev($cost));
        return $cost;
    }
}
