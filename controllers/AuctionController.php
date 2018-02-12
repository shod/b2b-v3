<?php

namespace app\controllers;

use app\models\BillAuction;
use app\models\Seller;
use app\models_ex\BillAccount;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class AuctionController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    var $flag_disabled = false;
    var $min_balance = 10; /*Min баланс участия в аукционе*/
    var $min_stavka = 1; /*Min баланс участия в аукционе*/
    var $_min_start = 10; /*Минимальный старт в аукционе*/
    var $_min_start_fix = 1; /*Минимальный старт в аукционе суточном*/
    var $_step = 0.1; /*Минимальный шаг в аукционе*/
    var $_step_fix = 1; /*Минимальный шаг в аукционе суточном*/
    var $auction_stop_time = array(17,40); /*Окончание аукциона время 17:40*/
    var $auction_stop_down_time = array(17,'00'); /*Запрещается снижение ставок 17:00*/
    var $auction_blind_time = array(17,30); /*Ставки вслепую 17:30*/

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
        if ($this->flag_disabled = !$this->is_auction_access())
        {
            \Yii::$app->db->createCommand("update bill_auction set cost=0 where owner_id={$this->seller_id} and type_id=1")->execute();
        }
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

    /*Проверка на участие в аукционе*/
    function is_auction_access(){
        $res = true;

        /*Участвует в акции*/
        $resdata = \Yii::$app->db->createCommand("select count(1) as cnt from bill_cat_sel_discount as bsc where seller_id = {$this->seller_id} and now() <= date_expired;")->queryAll();
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $baccount_main = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();
        $balance = $baccount_main->balance;
        $balance_all = $baccount_main->balance_all;
        $balance_child = $baccount_main->getChildBillAccount();

        if($resdata[0]['cnt'] == 0){
            if(($balance_all < $this->min_balance) && ($balance_child->balance <=0)){ // добавлено условие, что осн. баланс меньше min_balance и бонус меньше либо равен 0
                $res = false;
            }
        }else{
            /*Child account*/
            if(($balance)<=0 && $balance_child->balance<=0){
                $res = false;
            }
        }


        return $res;
    }
    public function actionProcess()
    {
        $action = Yii::$app->request->post("action");
        $action = isset($action) ? $action : Yii::$app->request->get("action");
        switch ($action) {
            case "add":
                $ids = Yii::$app->request->post("ids");
                foreach((array)$ids as $catalog_id)
                {
                    $bill_auction = new BillAuction();
                    $bill_auction->owner_id = $this->seller_id;
                    $bill_auction->type_id = 1;
                    $bill_auction->object_id = $catalog_id;
                    $bill_auction->cost = 0;
                    $bill_auction->date = date("Y-m-d H:i:s");
                    $bill_auction->f_auto = 1;
                    $bill_auction->save();
                }
                return $this->redirect(['auction/index']);
                break;
        }

    }

    public function actionIndex()
    {
        $res = \Yii::$app->db->createCommand("select * from texts where id=214")->queryAll();
        $vars["title"] = $res[0]["name"];
        $vars["text"] = $res[0]["text"];
        $vars["text"] = str_replace(array('$vars[min_stavka]','$vars[min_step]','$vars[min_balance]'),array($this->min_stavka,$this->_step,$this->min_balance),$res[0]["text"]);

        $res = \Yii::$app->db->createCommand("select * from texts where id=191")->queryAll();
        $vars["title_online"] = $res[0]["name"];
        $vars["text_online"] = $res[0]["text"];
        $vars["text_online"] = str_replace(array('$vars[min_stavka]','$vars[min_step]','$vars[min_balance]'),array($this->min_stavka,$this->_step,$this->min_balance),$res[0]["text"]);

        $fix_html = $this->getDataHtmlFix();
        if(strlen($fix_html)>0){
            $vars["data_catalog_fix"] = $fix_html;
        }
        $online_html = $this->getDataHtml();
        if($online_html != ""){
            $vars["data_catalog_minute"] = $online_html;
        }

        return $this->render('index', $vars);
    }

    public function actionAdd()
    {
        $vars['data'] = $this->getDataAddHtml();
        return $this->render('add', $vars);
    }

    function getDataAddHtml()
    {
        $html = "";

        $res = \Yii::$app->db->createCommand("
			select ba.object_id as id, min(ba.cost) as cost_min, max(ba.cost) as cost_max, count(ba.owner_id) as cnt
			from bill_auction ba
			inner join seller s on (s.id=ba.owner_id)
			inner join bill_account bacc on (bacc.id=s.bill_account_id)
			where ba.type_id=1 and ba.cost>0 and s.active=1 and bacc.balance >= {$this->min_balance}
			group by object_id order by cnt desc	
		")->queryAll();

        $data = $ids = array();
        foreach((array)$res as $r)
        {
            $data[$r["id"]] = $r;
            $ids[] = $r["id"];
        }
        $ids_str = implode(",",$ids);

        $res_click = \Yii::$app->db->createCommand("select if(IFNULL(pay_type,'fixed') = 'clicks',1,0) as is_clicks from seller where id = ". $this->seller_id)->queryAll();

        if($res_click[0]['is_clicks'] == 1){
            $res = \Yii::$app->db->createCommand("select * from bill_catalog WHERE id = 223")->queryAll();
        }else{
            $res = \Yii::$app->db->createCommand("select * from bill_catalog where id in (select catalog_id from bill_catalog_seller where seller_id={$this->seller_id}) order by position")->queryAll();
        }

        foreach((array)$res as $r)
        {
            $html_row = "";

            if($res_click[0]['is_clicks'] == 1){
                $res1 =  \Yii::$app->db->createCommand("
                                            SELECT
                                                c.id,
                                                c.name, f_is_setting_bit_set(c.setting_bit, 'catalog', 'auction_day') as is_fix
                                            FROM
                                                catalog AS c,
                                                v_catalog_sections AS vcs,
                                                product_seller AS ps
                                            WHERE
                                                ps.seller_id = {$this->seller_id}
                                            AND ps.active = 1
                                            AND vcs.section_id = ps.prod_sec_id
                                            AND c.id = vcs.catalog_id
											and not c.id in (select object_id from bill_auction where owner_id={$this->seller_id} and type_id=1)	
                                            GROUP BY
                                                ps.prod_sec_id")->queryAll();
                $r["name"] = "Доступные разделы";
            }else{
                $res1_old = \Yii::$app->db->createCommand("
				select id, name, bsel.cnt
				from catalog c, (select ba.object_id, min(ba.cost) as cost_min, max(ba.cost) as cost_max, count(ba.owner_id) as cnt
							from bill_auction ba
							inner join seller s on (s.id=ba.owner_id)
							inner join bill_account bacc on (bacc.id=s.bill_account_id)
							where ba.type_id=1 and ba.cost>0 and s.active=1 and bacc.balance >= 10
							group by object_id) as bsel
				where id in (
					select cs.catalog_id 
					from catalog_subject cs				
					where cs.subject_id in (
						select bcs.section_id 					
						from bill_catalog_section bcs 
						inner join sections s on (s.id=bcs.section_id)
						where bcs.catalog_id={$r["id"]} and (s.type<>'price' OR s.type is null)
					)  					
				) and hidden=0  and not id in (select object_id from bill_auction where owner_id={$this->seller_id} and type_id=1)
				and bsel.object_id = c.id
				order by bsel.cnt desc, name
				")->queryAll();

                $res1 = \Yii::$app->db->createCommand("
				select id, name, bsel.cnt, f_is_setting_bit_set(c.setting_bit, 'catalog', 'auction_day') as is_fix
				from catalog c
					left join (select ba.object_id, min(ba.cost) as cost_min, max(ba.cost) as cost_max, count(ba.owner_id) as cnt
							from bill_auction ba
							inner join seller s on (s.id=ba.owner_id)
							inner join bill_account bacc on (bacc.id=s.bill_account_id)
							where ba.type_id=1 and ba.cost>0 and s.active=1 and bacc.balance >= 10
							group by object_id) as bsel on (bsel.object_id = c.id)
				where c.id in (
					select cs.catalog_id 
					from catalog_subject cs				
					where cs.subject_id in (
						select bcs.section_id 					
						from bill_catalog_section bcs 
						inner join sections s on (s.id=bcs.section_id)
						where bcs.catalog_id={$r["id"]} and (s.type<>'price' OR s.type is null)
					)  					
				) and hidden=0  
				and not id in (select object_id from bill_auction where owner_id={$this->seller_id} and type_id=1)				
				order by bsel.cnt desc, name
				")->queryAll();
            }

            foreach((array)$res1 as $r1)
            {
                $cost_min = isset($data[$r1["id"]]["cost_min"]) ? $data[$r1["id"]]["cost_min"] : "";
                $cost_max = isset($data[$r1["id"]]["cost_max"]) ? $data[$r1["id"]]["cost_max"] : "";
                $cnt = isset($data[$r1["id"]]["cnt"]) ? $data[$r1["id"]]["cnt"] : "";

                $cost_from = $cost_min ? "от {$cost_min}" : " от {$this->_min_start} ";
                $cost_to = ($cost_max && $cost_min!=$cost_max) ? " до {$cost_max}" : "";
                list($views) = $this->getViews($r1["id"]);
                $html_row .=  $this->renderPartial("tmpl/add_row", array(
                    "id" => $r1["id"],
                    "name" => $r1["name"],
                    "fix" => $r1["is_fix"] ? "(фиксированный)" : "",
                    "cost" => "{$cost_from}{$cost_to}",
                    "cnt" => $cnt ? $cnt : "0",
                    "views" => $views
                ));
            }
            $add_text = ($html_row == "") ? " <span style='color: #a1a1a1;font-size: 10px;'>Все доступные разделы подключены</span>" : "";
            $html .= $this->renderPartial("tmpl/catalog", array(
                "name" => $r["name"] . $add_text,
                "data" => $html_row,
            ));
        }

        return $html;
    }

    private function getDataHtml()
    {
        $html = "";
        $res = \Yii::$app->db->createCommand("select ba.* from bill_auction as ba, catalog as ss
where f_is_setting_bit_set(ss.setting_bit, 'catalog', 'auction_day') = 0 and ss.id = ba.object_id and type_id=1 and ba.owner_id={$this->seller_id}")->queryAll();

        if(!is_array($res)){
            return '';
        }

        $cost = $ids = $cost_auto = $f_auto = array();
        foreach((array)$res as $r)
        {
            $id = $r["id"];
            $cost[$id] = $r["cost"];
            $cost_auto[$id] = $r["cost_auto"];
            $ids[$id] = $r["object_id"];
            $f_auto[$id] = $r["f_auto"];
        }
        $ids_str = implode(",", $ids);


        $html_row = "";
        $res1 = \Yii::$app->db->createCommand("
			select distinct ba.id as id, cat.name as name, ba.object_id as catalog_id
			from bill_auction as ba
			inner join (
				select id, name
				from catalog c
				where hidden=0 and id in ({$ids_str})
			) as cat on (cat.id=ba.object_id)
			where ba.owner_id={$this->seller_id} and ba.type_id=1
			order by name
			")->queryAll();

        foreach((array)$res1 as $r1)
        {
            list($views, $forecast) =  $this->getViews($r1["catalog_id"], $cost[$r1["id"]]);
            $html_row .= $this->renderPartial("tmpl/row", array(
                "id" => $r1["id"],
                "catalog_id" => $r1["catalog_id"],
                "views" => $views,
                "forecast" => $forecast,
                "name" => $r1["name"],
                "data" => $this->getPositionDataHtml($ids[$r1["id"]]),
                "value" => $f_auto[$r1["id"]] ? round($cost_auto[$r1["id"]],2) : round($cost[$r1["id"]],2),
                "disabled" => $this->flag_disabled ? "disabled" : "",
                "auto_checked" => $f_auto[$r1["id"]] ? "checked" : "",
                "href_delete" => "",
                "min_step" => $this->_step
            ));
        }


        //$whirl->debug->message($html_row);

        $html .= $this->renderPartial("tmpl/catalog", array(
            "name" => isset($r["name"]) ? $r['name'] : "",
            "data" => $html_row,
        ));


        return $html;
    }

    private function getDataHtmlFix()
    {
        $html = "";
        $res = \Yii::$app->db->createCommand("select ba.* from bill_auction as ba, catalog as ss
                where f_is_setting_bit_set(ss.setting_bit, 'catalog', 'auction_day') = 1 and ss.id = ba.object_id and type_id=1 and ba.owner_id={$this->seller_id}")->queryAll();

        if(!is_array($res)){
            return '';
        }

        $cost = $ids = $cost_auto = $f_auto = array();
        foreach((array)$res as $r)
        {
            $id = $r["id"];
            $cost[$id] = $r["cost"];
            $cost_auto[$id] = $r["cost_auto"];
            $ids[$id] = $r["object_id"];
            $f_auto[$id] = $r["f_auto"];
        }
        $ids_str = implode(",", $ids);


        $html_row = "";
        $res1 = \Yii::$app->db->createCommand("
			select distinct ba.id as id, cat.name as name, ba.object_id as catalog_id
			from bill_auction as ba
			inner join (
				select id, name
				from catalog c
				where hidden=0 and id in ({$ids_str})
			) as cat on (cat.id=ba.object_id)
			where ba.owner_id={$this->seller_id} and ba.type_id=1
			order by name
			")->queryAll();


        if($this->seller_id == 1500){
            $res1 = \Yii::$app->db->createCommand("
			select distinct ba.id as id, cat.name as name, ba.object_id as catalog_id
			from bill_auction as ba
			inner join (
				select id, name
				from catalog c
				where id in ({$ids_str})
			) as cat on (cat.id=ba.object_id)
			where ba.owner_id={$this->seller_id} and ba.type_id=1
			order by name
			")->queryAll();
        }

        foreach((array)$res1 as $r1)
        {
            list($views, $forecast) =  $this->getViews($r1["catalog_id"], $cost[$r1["id"]]);
            $html_row .= $this->renderPartial("tmpl/row_fix", array(
                "id" => $r1["id"],
                "catalog_id" => $r1["catalog_id"],
                "views" => $views,
                "forecast" => $forecast,
                "name" => $r1["name"],
                "data" => $this->getPositionDataHtml($ids[$r1["id"]], false),
                "value" => $f_auto[$r1["id"]] ? round($cost_auto[$r1["id"]],2) : round($cost[$r1["id"]],2),
                "disabled" => $this->flag_disabled ? "disabled" : "",
                "auto_checked" => $f_auto[$r1["id"]] ? "checked" : "",
                "href_delete" => "",
                "min_step" => $this->_step
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
        $res = \Yii::$app->db->createCommand("
			select CONCAT('~',(Round(view/10,0))*10) as v1, IFNULL(Round((Round(view/10,0))*10*{$cost}/1000,2), 0) as v2
			from migombyha.stat_view_yesterday
			where seller_id={$this->seller_id} and catalog_id={$catalog_id}
		")->queryAll();

        if(isset($res[0])){
            return array($res[0]["v1"], $res[0]["v2"]);
        } else {
            return array(0, 0);
        }

    }

    function getPositionDataHtml($catalog_id, $show_name = true)
    {
        $html = "";
        $res = \Yii::$app->db->createCommand("
			select id, owner_id, type_id, object_id, cost, cost_auto, date, place_old, place, f_notify, f_show, f_auto, name, balance, seller_action from (
		select ba.*, TRIM(s.name) as name, bacc.balance_all as balance, s.pay_type
			,(select count(1) as cnt from bill_cat_sel_discount as bsc
            where bsc.seller_id = s.id and now() <= date_expired) as seller_action
			from bill_auction  ba
			inner join seller s on (s.id=ba.owner_id)
			inner join bill_account bacc on (bacc.id=s.bill_account_id)
			where ba.type_id=1 and ba.object_id={$catalog_id} and ba.cost>0 and s.active=1 
			) as query 
						/*where ((IFNULL(seller_action,0)*10)+balance + (IF(pay_type = 'fixed', 0, 2) * 10)) >= {$this->min_balance}*/
						order by cost desc, date asc;		
		")->queryAll();


        foreach((array)$res as $r)
        {
            $selected = ($r["owner_id"]==$this->seller_id) ? "style=\"background: rgb(245, 222, 227);font-size: 11px;\"" : "style=\"font-size: 11px;\"";
            $cost = round($r["cost"],2);
            $name = $r['name'];

            $name = htmlspecialchars($name);

            if(false == $show_name)
            {
                $name = '';
            }

            $html .= "<li {$selected} title=\"{$name}\"> <div class=\"name-seller-au\"><b>{$cost}&nbsp;</b><span style='color: rgba(129, 129, 129, 0.57)'>{$name}</span></div></li>";

            if(false == $show_name)
            {
                $d = getdate(); // использовано текущее время
                $now = time();

                /*Окончание аукциона*/
                $auction_blind = mktime($this->auction_blind_time[0],$this->auction_blind_time[1],0,$d['mon'],$d['mday'],$d['year']);
                $auction_stop_time = mktime($this->auction_stop_time[0],$this->auction_stop_time[1],0,$d['mon'],$d['mday'],$d['year']);

                if(($auction_blind-$now)<0 && ($auction_stop_time-$now)>0){
                    $html = '';
                }
            }
        }
        //$html = $html ? $html : "-";
        $html = "<ol>{$html}</ol>";

        return stripcslashes($html);
    }
}
