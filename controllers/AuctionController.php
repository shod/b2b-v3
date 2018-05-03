<?php

namespace app\controllers;

use app\models\BillAuction;
use app\models\Seller;
use app\models_ex\BillAccount;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
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
    var $_min_start = 0.7; /*Минимальный старт в аукционе*/
    var $_min_start_fix = 0.7; /*Минимальный старт в аукционе суточном*/
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
            case "save":
                $auction_type = Yii::$app->request->post("auction"); /*Тип аукциона*/
                $arr_auto = Yii::$app->request->post("f_auto");
                $auction_auto_max_val = 998;
                $ids = array_keys((array)$arr_auto);
                $ids = join(",", (array)$ids);

                $value = Yii::$app->request->post("value");
                $str_ids = implode(',',array_keys($value));

                \Yii::$app->db->createCommand("update bill_auction set f_auto=0 where owner_id={$this->seller_id} and id in ({$str_ids})")->execute();

                if($auction_type == 'fix'){
                    $this->_step = $this->_step_fix;
                    $this->_min_start = $this->_min_start_fix;

                    if ($ids!=''){
                        \Yii::$app->db->createCommand("update bill_auction set f_auto=1 where id in ({$ids}) and owner_id={$this->seller_id}")->execute();
                    }

                }else{

                    if ($ids!=''){
                        \Yii::$app->db->createCommand("update bill_auction set f_auto=1, cost=IF(cost>{$auction_auto_max_val},{$auction_auto_max_val}, cost) where id in ({$ids}) and owner_id={$this->seller_id}")->execute();
                        \Yii::$app->db->createCommand("update bill_auction set cost={$this->_min_start} where owner_id={$this->seller_id} and id in ({$ids}) and f_auto=1 and cost=0")->execute();
                    }
                }


                $res = \Yii::$app->db->createCommand("select * from bill_auction where owner_id={$this->seller_id} and type_id=1")->queryAll();
                $data_cost = $data_cost_auto = $data_f_auto = array();


                foreach((array)$res as $r)
                {
                    $data_cost[$r["id"]] = $r["cost"];
                    $data_cost_auto[$r["id"]] = $r["cost_auto"];
                    $data_f_auto[$r["id"]] = $r["f_auto"];
                }



                foreach((array)$value as $id=>$cost)
                {
                    $bill_auction = BillAuction::find()->where(['id' => $id])->one();
                    $cost = str_replace(',','.',$cost);

                    $cost = round($cost/$this->_step)*$this->_step;

                    if($cost > 0 && $cost < $this->_min_start){
                        $cost = $this->_min_start;
                    }

                    $data_cost_auto[$id] = str_replace(',','.',$data_cost_auto[$id]);

                    if (($auction_type != 'fix') && $data_f_auto[$id])
                    {
                        if ($cost != $data_cost_auto[$id])
                        {
                            $bill_auction->cost_auto = min($cost,$auction_auto_max_val);
                            $bill_auction->date = date("Y-m-d H:i:s");
                            $bill_auction->save();
                        }
                    }
                    else
                    {

                        if($cost > 0){
                            $cost = max($cost,$this->_min_start);
                        }

                        if ($cost != $data_cost[$id])
                        {
                            $bill_auction->cost_auto = $cost;
                            $bill_auction->date = date("Y-m-d H:i:s");
                            $bill_auction->save();


                            /*Фиксированный аукцион*/
                            if($auction_type == 'fix'){
                                $d = getdate(); // использовано текущее время
                                $now = time();

                                /*Окончание аукциона*/
                                $auction_stop = mktime($this->auction_stop_time[0],$this->auction_stop_time[1],0,$d['mon'],$d['mday'],$d['year']);
                                $auction_stop_down = mktime($this->auction_stop_down_time[0],$this->auction_stop_down_time[1],0,$d['mon'],$d['mday'],$d['year']);

                                if(($auction_stop_down-$now)<0){
                                    $cost_old = $bill_auction->cost;
                                    $cost = max($cost, $cost_old);
                                }

                                if(($auction_stop-$now)>0){
                                    $cost = min($cost, $this->get_max_bid());
                                    $this->_set($id, $cost, false);
                                }
                            }else{
                                $this->_set($id, $cost, true);
                            }
                        }
                    }

                }
                return $this->redirect(['auction/index']);
                break;
            case "get_arch":
                $json["header"] = 'Аукцион вчера';


                $id = Yii::$app->request->get("baid");
                $sql = "select name from catalog as ss where ss.id = {$id}";
                $res = \Yii::$app->db->createCommand($sql)->queryAll();
                $name = $res[0]['name'];

                $sql = "select place, cost, owner_id from index_auction_data_base where object_id = {$id} and type_id=1 order by place";

                $res = \Yii::$app->db->createCommand($sql)->queryAll();

                $data = '';

                $place_view = 0; // Место для вывода ставок
                foreach((array)$res as $r){
                    if($this->seller_id == $r['owner_id']){
                        $place_view = $r['place'];
                    }
                }

                foreach((array)$res as $r)
                {
                    if($this->seller_id == $r['owner_id']){
                        $data .= "<tr class='success'><td>{$r['place']}</td><td>{$r['cost']} - Ваша ставка</td></tr>";
                    }elseif(($place_view-2 < $r['place']) && ($place_view+2 > $r['place'])){
                        $data .= "<tr><td>{$r['place']}</td><td>{$r['cost']}</td></tr>";
                    }
                    else{
                        $data .= "<tr><td>{$r['place']}</td><td></td></tr>";
                    }
                }

                $auc_win = $this->renderPartial("tmpl/auc_arch_win", array('name'=>$name ,'data'=> $data));
                $json["body"] = $auc_win;
                echo Json::encode($json);
                exit;
                break;
            case "update":
                //$auction_time_last = $this->memcache->get("auction");
                $data = array(	"data" => array(), "time" => time() );
                $date = date("Y-m-d H:i:s", Yii::$app->request->post("time"));

                //if($auction_time_last > $P->get("time"))
                {

                    $sql = "select * from 
							(SELECT
								ba.*,
							IF (ba.owner_id = {$this->seller_id}, 1, 0) AS selected
							 , TRIM(s.name) as name
							,(select count(1) as cnt from bill_cat_sel_discount as bsc
										where bsc.seller_id = s.id and now() <= date_expired) as seller_action
							, bacc.balance
							, pay_type
							, f_is_setting_bit_set(cat.setting_bit, 'catalog', 'auction_day') as auction_fix
							FROM
								bill_auction ba							
							INNER JOIN seller s ON (s.id = ba.owner_id)
							INNER JOIN bill_account bacc ON (bacc.id = s.bill_account_id)
							INNER JOIN catalog as cat on (cat.id = ba.object_id)
							WHERE
								ba.object_id IN (
									SELECT
										bach.object_id
									FROM
										bill_auction AS own,
										bill_auction AS bach
									WHERE
										own.owner_id = {$this->seller_id}
									AND own.type_id = 1
									AND bach.object_id = own.object_id
									AND bach.date >= '{$date}'
								)							
							AND ba.cost > 0
							AND s.active = 1
							AND ba.type_id = 1
							) as qsel
							/*where ((IFNULL(seller_action,0)*10)+balance + (IF(pay_type = 'fixed', 0, 2) * 10)) >= {$this->min_balance}*/
							ORDER BY
								qsel.object_id,
								qsel.cost DESC,
								qsel.date ASC";

                    $res = \Yii::$app->db->createCommand($sql)->queryAll();

                    foreach((array)$res as $r)
                    {
                        $name = $r['name'];
                        //$name = iconv("windows-1251", "UTF-8", $name);
                        $name = htmlspecialchars($name);

                        /*Если суточный, то не показывать имена*/
                        if($r["auction_fix"] == 1){
                            $name = '';
                        }

                        $_data = array("cost"=>round($r["cost"],2), "selected"=>$r["selected"], 'name'=>stripslashes($name));
                        if (isset($data["data"][$r["object_id"]]) && is_array($data["data"][$r["object_id"]]))
                            $data["data"][ $r["object_id"] ][] = $_data;
                        else
                            $data["data"][ $r["object_id"] ] = array($_data);

                        /*Если ставки вслепую, то скрывать*/
                        if($r["auction_fix"] == 1){
                            $d = getdate(); // использовано текущее время
                            $now = time();

                            /*ставки вслепую*/
                            $auction_blind = mktime($this->auction_blind_time[0],$this->auction_blind_time[1],0,$d['mon'],$d['mday'],$d['year']);

                            if(($auction_blind-$now)<0){
                                $data["data"][ $r["object_id"]] = '';
                            }
                        }
                    }
                }

                die(json_encode($data));
                break;
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

            case "delete":
                $id = Yii::$app->request->get("id");

                $res_auc = \Yii::$app->db->createCommand("select f_is_setting_bit_set(ss.setting_bit, 'catalog', 'auction_day') as auction_fix from bill_auction as ba, catalog as ss
							where ba.id = {$id} and ss.id = ba.object_id and type_id=1")->queryAll();
                $auction_fix = $res_auc[0]['auction_fix'];

                /*Запрещено удаление ставки*/
                if($auction_fix == 1){
                    $d = getdate(); // использовано текущее время
                    $now = time();
                    $auction_stop_down = mktime($this->auction_stop_down_time[0],$this->auction_stop_down_time[1],0,$d['mon'],$d['mday'],$d['year']);
                    if(($auction_stop_down-$now)>0){
                        \Yii::$app->db->createCommand("delete from bill_auction where id={$id}")->execute();
                    }
                }else{
                     \Yii::$app->db->createCommand("delete from bill_auction where id={$id}")->execute();
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

        $vars["hour_stop"] = $this->auction_stop_time[0];
        $vars["minute_stop"] = $this->auction_stop_time[1];
        $vars["time_stop_down"] = $this->auction_stop_down_time[0].':'.$this->auction_stop_down_time[1];
        $vars["time_blind"] = $this->auction_blind_time[0].':'.$this->auction_blind_time[1];
        $vars["max_bid"] = $this->get_max_bid();

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
                $html_row .=  $this->renderPartial("tmpl/add_row", [
                    "id" => $r1["id"],
                    "name" => $r1["name"],
                    "fix" => $r1["is_fix"] ? "(фиксированный)" : "",
                    "cost" => "{$cost_from}{$cost_to}",
                    "cnt" => $cnt ? $cnt : "0",
                    "views" => $views
                ]);
            }
            $add_text = ($html_row == "") ? " <span style='color: #a1a1a1;font-size: 10px;'>Все доступные разделы подключены</span>" : "";
            $html .= $this->renderPartial("tmpl/catalog", [
                "name" => $r["name"] . $add_text,
                "data" => $html_row,
            ]);
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
        if($ids_str){
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
        } else {
            $res1 = [];
        }


        foreach((array)$res1 as $r1)
        {
            list($views, $forecast) =  $this->getViews($r1["catalog_id"], $cost[$r1["id"]]);
            $html_row .= $this->renderPartial("tmpl/row", [
                "id" => $r1["id"],
                "catalog_id" => $r1["catalog_id"],
                "views" => $views,
                "forecast" => $forecast,
                "name" => $r1["name"],
                "data" => $this->getPositionDataHtml($ids[$r1["id"]]),
                "value" => $f_auto[$r1["id"]] ? round($cost_auto[$r1["id"]],2) : round($cost[$r1["id"]],2),
                "disabled" => $this->flag_disabled ? "disabled" : "",
                "auto_checked" => $f_auto[$r1["id"]] ? "checked" : "",
                "href_delete" => "/auction/process/?action=delete&id=". $r1["id"],
                "min_step" => $this->_step
            ]);
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


        /*if($this->seller_id == 1500){
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
        }*/

        foreach((array)$res1 as $r1)
        {
            list($views, $forecast) =  $this->getViews($r1["catalog_id"], $cost[$r1["id"]]);
            $html_row .= $this->renderPartial("tmpl/row_fix", [
                "id" => $r1["id"],
                "catalog_id" => $r1["catalog_id"],
                "views" => $views,
                "forecast" => $forecast,
                "name" => $r1["name"],
                "data" => $this->getPositionDataHtml($ids[$r1["id"]], false),
                "value" => $f_auto[$r1["id"]] ? round($cost_auto[$r1["id"]],2) : round($cost[$r1["id"]],2),
                "disabled" => $this->flag_disabled ? "disabled" : "",
                "auto_checked" => $f_auto[$r1["id"]] ? "checked" : "",
                "href_delete" => "/auction/process/?action=delete&id=". $r1["id"],
                "min_step" => $this->_step
            ]);
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

    /*Максимальная ставка по суточному аукциону*/
    private function get_max_bid()
    {
        $Res = 0;

        $obj = Seller::find()->where(['id' => $this->seller_id])->one();
        $account_id = $obj->bill_account_id;

        $baccount_main = BillAccount::find()->where(['id' => $account_id])->one();
        // $balance = $baccount_main->get_property('balance');
        $balance_all = $baccount_main->balance_all;
        $day_down_cost = round($baccount_main->getDayDownCatalog(),2);
        $Res = FLOOR($balance_all - ($day_down_cost*2));

        return $Res;
    }

    private function _set($id, $cost, $flag_auc_minute = true)
    {
        $res = \Yii::$app->db->createCommand("
            SELECT ba.*
            FROM bill_auction ba
            inner join seller s on (s.id=ba.owner_id and s.active=1)
            inner join bill_auction ba1 on (ba1.object_id=ba.object_id and ba1.type_id=ba.type_id)
            where ba1.id={$id}
            order by ba.cost desc, ba.date asc
		")->queryAll();

        $cost = round($cost,2);

        $bill_auction = BillAuction::find()->where(['id' => $id])->one();

        $cost_old = $bill_auction->cost;

        // if seller already is first
        if ($flag_auc_minute){
            if (isset($res[0]['owner_id']) && ($res[0]['owner_id']==$this->seller_id) && ($cost>$cost_old) && ($cost_old>0))
            {
                return;
            }
        }

        if ($cost<>$cost_old)
        {

            $max_step = floor(10/$this->_step)*$this->_step;

            if($flag_auc_minute){
                $cost = min($cost, $cost_old+$max_step);
                $cost = ($cost>0) ? max($cost, $this->_step) : 0;
            }

            $bill_auction->cost = $cost;
            $bill_auction->date = date("Y-m-d H:i:s");
            $bill_auction->save();

            /*Запись о времени ставки в разделе*/

            //@$this->memcache->set('auction', time(), false, 3600);
        }

        foreach ((array) $res as $i=>$r)
        {
            $bill_auction_obj = BillAuction::find()->where(['id' => $r["id"]])->one();
            if ($i+1 > $r["place"])
            {
                $bill_auction_obj->place = $i+1;
                $bill_auction_obj->f_notify = 0;
                $bill_auction_obj->save();
            }
            elseif ($i+1 < $r["place"])
            {
                $bill_auction_obj->place = $i+1;
                $bill_auction_obj->place_old = $i+1;
                $bill_auction_obj->save();
            }

            if ($r["place_old"]==0)
            {
                $bill_auction_obj->place_old = $r["place"];
                $bill_auction_obj->save();
            }
        }
    }
}
