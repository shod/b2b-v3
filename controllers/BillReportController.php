<?php

namespace app\controllers;

use app\helpers\NumberService;
use app\helpers\SiteService;
use app\models\BillTransaction;
use app\models\BillTransactionType;
use app\models_ex\BillAccount;
use app\models_ex\Member;
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

class BillReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;

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

    public function actionGetAkt(){
        $this->layout = false;
        $load = Yii::$app->request->post('load');
        $month_data = array(
            1 => '31 января',
            2 => '28 февраля',
            3 => '31 марта',
            4 => '30 апреля',
            5 => '31 мая',
            6 => '30 июня',
            7 => '31 июля',
            8 => '31 августа',
            9 => '30 сентября',
            10 => '31 октября',
            11 => '30 ноября',
            12 => '31 декабря'
        );
//exit;
        $month = Yii::$app->request->post('month');
        $dat = $month_data[$month];
        $year = Yii::$app->request->post('year');
        if ($month == 12){
            $year_2 = $year+1;
            $month_2 = 1;
        } else {
            $year_2 = $year;
            $month_2 = $month+1;
        }
        $res = \Yii::$app->db->createCommand("select owner_id from seller where id = {$this->seller_id}")->queryAll();
        $owner_id = $res[0]['owner_id'];

        $sql_rep = "SELECT
													bs.*, ss.id AS seller_id,
													sov.`value`
												FROM
													bank_statement AS bs
												LEFT JOIN seller AS ss ON (
													bs.member_id = ss.owner_id
													AND ss.owner_id = ss.member_id
												)
												LEFT JOIN sys_object_value AS sov ON (
													sov.object_id = bs.member_id
													AND sov.object_property_id = 26
												)
												WHERE
													1 = 1
												AND bs.tdate > '20{$year}-{$month}-01'
												AND bs.tdate < '20{$year_2}-{$month_2}-01'
												and ss.owner_id = {$owner_id}";
        //$whirl->debug->log($sql_rep);
        $res = \Yii::$app->db->createCommand($sql_rep)->queryAll();
        $sum = 0.00;

        foreach ($res as $r){
            $sum += $r['amount'];
        }

        $res = \Yii::$app->db->createCommand("select id from seller where member_id = {$owner_id}")->queryOne();
        $docnum = $res['id'];

        $sum_all = $sum;

        if($year >= 16){
            $sum = $sum_all / 1.2;
        }

        $sum_nds = $sum * 0.2;

        $denomination_date = ((($year*10).$month)*1);
        if($denomination_date >= 1607)
        {
            /*Точность до 2-х знаков*/
            $sum_str = NumberService::num2str($sum);
            $sum_all_str = NumberService::num2str($sum_all);
            $sum_nds_str = NumberService::num2str($sum_nds);
            $sum = number_format($sum,2,'.',' ');
            $sum_nds = number_format($sum_nds,2,'.',' ');
            $sum_all = number_format($sum_all,2,'.',' ');
        } else {
            $sum_str = NumberService::num2str_2016($sum);
            $sum_all_str = NumberService::num2str_2016($sum_all);
            $sum_nds_str = NumberService::num2str_2016($sum_nds);
            $sum = number_format($sum,0,'.',' ');
            $sum_nds = number_format($sum_nds,0,'.',' ');
            $sum_all = number_format($sum_all,0,'.',' ');
        }

        $nds = 20;
        if ($year < 16){
            $sum_nds = 0;
            $sum_all = $sum;
            $sum_all_str = $sum_str;
            $sum_nds_str = "ноль рублей";
            $nds = "";
        }

        $date_p = date('d m Y');
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $seller_info = SellerInfo::find()->where(['seller_id' => $this->seller_id])->one();
        $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();
        $member = Member::find()->where(['id' => $seller->member_id])->one();
        $member_data = $member->getMemberProperties();

        $contract_date_tm = $seller_info->contract_date;
        $contract_date = date('d.m.Y',$contract_date_tm);

        $fax1 = $member_data['fax'];
        //print_r(strlen($fax1));
        if (strlen($fax1) > 40){

            $phones = explode(";", $fax1);
            $fax1 = $phones[0];
        }

        $vars = array_merge(array(
                "seller_id" => $this->seller_id,
                "docnum" => $docnum,
                "date_p" => $date_p,
                "contract_number" => $seller_info->contract_number,
                "contract_date" => $contract_date,
                "fax1" => $fax1,
                "sum" => $sum,
                "sum_nds" => $sum_nds,
                "sum_all" => $sum_all,
                "sum_str" => $sum_str,
                "sum_all_str" => $sum_all_str,
                "sum_nds_str" => $sum_nds_str,
                "dat" => $dat,
                "nds" => $nds,
                "year" => $year,
                "month" => $month
            )
            , $member_data);
        $html_data = $this->render('tmpl/html-akt', $vars);

        if ($load == 'xlsx'){
            //echo "aasasasasasas";
            $html_data = $this->render('tmpl/xlsx-akt', $vars);
            header('Content-Type: text/html; charset=windows-1251');
            header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
            header('Content-transfer-encoding: binary');
            header("Content-Disposition: attachment; filename=act-{$this->seller_id}-{$month}-{$year}.xls");
            header('Content-Type: application/x-unknown');
            header('Content-Encoding: UTF-8');
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            echo $html_data;
            exit;
        }

        if ($load == 'pdf'){
            try {

                $html_data = $this->render('tmpl/pdf-akt', $vars);
                $mpdf = new \mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
                $mpdf->charset_in = 'cp1251';
                $mpdf->WriteHTML($html_data);
                $mpdf->Output("act-{$this->seller_id}-{$month}-{$year}.pdf", 'I');
                exit;

            } catch (Exception $e) {
                echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            }
            exit;

        }

        echo $html_data;
        exit;
    }

    public function actionAllReport(){
        $json["header"] = 'Общая статистика';
        $json["body"] = $this->getReportDataAll(); //$this->renderPartial('tmpl/analysis_access');
        echo Json::encode($json);
    }

    public function actionGetMoreData(){
        $html = $this->getMoreData();
        echo $html;
        exit;
    }

    public function actionGetMoreDataXlsx(){
        $html = $this->getMoreData();

        header('Content-Type: text/html; charset=utf-8');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-transfer-encoding: binary');
        header("Content-Disposition: attachment; filename=report-{$this->seller_id}.xls");
        header('Content-Type: application/x-unknown');

        echo $html;
        exit;
    }

    public function actionGetReportAuction(){
        $d = strtotime(Yii::$app->request->get("d"));
        $c = intval(Yii::$app->request->get("c"));

        $res = \Yii::$app->db->createCommand("SELECT sum(VIEW) AS view, cost, sum(VIEW * cost) / 1000 AS s 
				FROM migombyha.product_seller_stat, ( SELECT p.id FROM catalog_subject AS cs, products AS p WHERE cs.catalog_id = {$c} AND p.section_id = cs.subject_id ) AS selp 
				WHERE seller_id={$this->seller_id} and date={$d} AND cost > 0 AND product_id = selp.id GROUP BY cost ORDER BY cost;
		")->queryAll();

        $html = "<table class='table'><tr><th>Кол-во показов</th><th>Ставка</th><th>Списалось</th></tr>";

        $view_all = $cost_all = $sum_all = 0;

        foreach((array)$res as $r)
        {
            $r["s"] = round($r["s"], 4);
            $html .= "<tr><td>{$r["view"]}</td><td>{$r["cost"]}</td><td>{$r["s"]}</td></tr>";

            $view_all += $r['view'];
            $sum_all += $r['s'];
        }
        $cost_all = $view_all ? round($sum_all / $view_all * 1000, 4) : 0;

        $html .= '<tr><th colspan=3>Итого</th></tr>';
        $html .= "<tr><th>{$view_all}</th><th>{$cost_all}</th><th>{$sum_all}</th></tr>";
        $html .= "</table>";

        $json["header"] = 'Данные аукциона';
        $json["body"] = $html; //$this->renderPartial('tmpl/analysis_access');
        echo Json::encode($json);

    }

    public function actionGetReportSpec(){
        $d = strtotime(Yii::$app->request->get("d"));
        $c = intval(Yii::$app->request->get("c"));

        $res = \Yii::$app->db->createCommand("
			select sum(view) as view, cost, sum(view*cost)/1000 as s
			from stat_spec
			where seller_id={$this->seller_id} and date={$d} and product_id in (select id from products where section_id in (select subject_id from catalog_subject where catalog_id={$c}))
			group by cost
			order by cost
		")->queryAll();

        $html = "<table class='table'><tr><th>Кол-во показов</th><th>Ставка</th><th>Списалось</th></tr>";
        $view_all = $cost_all = $sum_all = 0;

        foreach((array)$res as $r)
        {
            $r["s"] = round($r["s"], 4);
            $html .= "<tr><td>{$r["view"]}</td><td>{$r["cost"]}</td><td>{$r["s"]}</td></tr>";

            $view_all += $r['view'];
            $sum_all += $r['s'];
        }

        $cost_all = $view_all ? round($sum_all / $view_all * 1000, 4) : 0;

        $html .= '<tr><th colspan=3>Итого</th></tr>';
        $html .= "<tr><th>{$view_all}</th><th>{$cost_all}</th><th>{$sum_all}</th></tr>";
        $html .= "</table>";
        $json["header"] = 'Данные спецпредложений';
        $json["body"] = $html;
        echo Json::encode($json);
    }

    public function actionIndex()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $vars["month_options"] = $this->getMonthOptions($seller);
        $vars["data"] = $this->getTransactionsHtml($seller);
        $vars["date_from"] = Yii::$app->request->get("date_from");
        $vars["date_to"] = Yii::$app->request->get("date_to");
        return $this->render('index', $vars);
    }

    public function actionAkt()
    {
        return $this->render('akt');
    }

    private function getMoreData(){
        $obj = Seller::find()->where(['id' => $this->seller_id])->one();
        $account_id = $obj->bill_account_id;
        $m = Yii::$app->request->get("m");
        $type = Yii::$app->request->get("type");
        if(isset($m)){
            $m = explode("_", $m);
        }

        $date_from = Yii::$app->request->get("date_from");
        $date_to = Yii::$app->request->get("date_to");

        if ($date_from && $date_to){
            $sql_m = " and date_begin BETWEEN '{$date_from}' AND '{$date_to} 23:59:59'";
        } else {
            if(count($m) > 0)
            {
                $sql_m = " and YEAR(date_begin)={$m[0]} and MONTH(date_begin)={$m[1]}";
            }
            else
            {
                $res = \Yii::$app->db->createCommand("SELECT YEAR(date_begin) as y, MONTH(date_begin) as m FROM bill_transaction WHERE account_id={$this->account_id} order by date_begin desc limit 1")->queryAll();
                if (count($res))
                {
                    $y = $res[0]["y"];
                    $m = $res[0]["m"];
                }
                else
                {
                    $y = date("Y");
                    $m = date("m");
                }
                $sql_m = " and (YEAR(date_begin - INTERVAL 1 DAY)={$y} and MONTH(date_begin - INTERVAL 1 DAY)={$m})";
            }
        }

        $sql = "SELECT vcs.name as catalog, vcs.catalog_id, vbc.main,
            if(vbc.main=1, 'Основной', 'Бонусный') as acc_name,
             bt.type,
             SUM(bt. VALUE) as sum_all,
             (select name from bill_transaction_type as btt where btt.code = bt.type) as name
            FROM
             bill_transaction AS bt
            , v_bill_account_owner as vbc,
            v_catalog_sections as vcs
            WHERE
             (
              vbc.account_id = {$account_id}  
             )
            AND bt.account_id = vbc.id and vcs.catalog_id = bt.object_id
            {$sql_m}
            AND NOT (date_end IS NULL)
            and type='{$type}'
            GROUP BY
             vbc.main,bt.type, bt.object_id
            order by vbc.main desc, bt.type";
        //echo "<p>{$sql}</p>";

        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        $html = "<table class='table'><tr><th>&nbsp;</th><th>Бонусный</th><th>Основной</th></tr>";
        $array_catalog = array();
        foreach ((array) $res as $r)
        {
            $r['sum_all'] = -1 * $r['sum_all'];
            //$html .= "<tr><td>{$r['acc_name']}</td><td>{$r['name']}</td><td>{$r['sum_all']}</td></tr>";
            $array_catalog[$r['catalog_id']]['name'] = $r['name'];
            $array_catalog[$r['catalog_id']]['catalog'] = $r['catalog'];
            if ($r['main'] == 1){
                $array_catalog[$r['catalog_id']]['sum_main'] = $r['sum_all'];
            } else {
                $array_catalog[$r['catalog_id']]['sum_bonus'] = $r['sum_all'];
            }
        }
        foreach ((array) $array_catalog as $r)
        {
            $sum_bonus = isset($r['sum_bonus']) ? str_replace('.', ',', $r['sum_bonus']) : 0;
            $sum_main = isset($r['sum_main']) ? str_replace('.', ',', $r['sum_main']) : 0;
            $html .= "<tr><td>{$r['catalog']}</td><td>{$sum_bonus} TE</td><td>{$sum_main} TE</td></tr>";
        }


        $html .= "</table>";

        return $html;
    }

    private function getReportDataAll(){
        $obj = Seller::find()->where(['id' => $this->seller_id])->one();
        $account_id = $obj->bill_account_id;
        $m = Yii::$app->request->get("m");
        if(isset($m)){
            $m = explode("_", $m);
            $mm = "{$m[0]}_{$m[1]}";
        }
        $date_from = Yii::$app->request->get("date_from");
        $date_to = Yii::$app->request->get("date_to");

        if ($date_from && $date_to){
            $sql_m = " and date_begin BETWEEN '{$date_from}' AND '{$date_to} 23:59:59'";
        } else {
            if(count($m) > 0)
            {
                $sql_m = " and YEAR(date_begin)={$m[0]} and MONTH(date_begin)={$m[1]}";
            }
            else
            {
                $res = \Yii::$app->db->createCommand("SELECT YEAR(date_begin) as y, MONTH(date_begin) as m FROM bill_transaction WHERE account_id={$account_id} order by date_begin desc limit 1")->queryAll();
                if (count($res))
                {
                    $y = $res[0]["y"];
                    $m = $res[0]["m"];
                }
                else
                {
                    $y = date("Y");
                    $m = date("m");
                }
                $sql_m = " and (YEAR(date_begin - INTERVAL 1 DAY)={$y} and MONTH(date_begin - INTERVAL 1 DAY)={$m})";
            }
        }

        $res = \Yii::$app->db->createCommand("
			SELECT
            if(vbc.main=1, 'Основной', 'Бонусный') as acc_name,
             bt.type,
             SUM(bt. VALUE) as sum_all,
             (select name from bill_transaction_type as btt where btt.code = bt.type) as name
            FROM
             bill_transaction AS bt
            , v_bill_account_owner as vbc
            WHERE
             (
              vbc.account_id = {$account_id}  
             )
            AND bt.account_id = vbc.id
            {$sql_m}
            AND NOT (date_end IS NULL)
            and type in ('down_auction','down_catalog','down_adv_spec','down_banner','down_click','down_spec')
            GROUP BY
             vbc.main,bt.type
            order by vbc.main desc, bt.type
		")->queryAll();
        $html = "<table class='table'><tr><th>Счет</th><th>Транзакция</th><th>Сумма списаний</th></tr>";
        foreach ((array) $res as $r)
        {
            $r['sum_all'] = -1 * $r['sum_all'];
            $html .= "<tr><td>{$r['acc_name']}</td><td>{$r['name']}</td><td>{$r['sum_all']} TE</td></tr>";
        }
        $html .= "</table><div>
                            <p>Подробнее по разделам: </p>
                            <span id='down_auction' class='btn btn-primary btn-sm' onclick=\"get_report_data_more('down_auction');\">Аукционы</span> <span id='down_spec' class='btn btn-primary btn-sm' onclick=\"get_report_data_more('down_spec');\">Спецпредложения</span> <span id='down_adv_spec' class='btn btn-primary btn-sm' onclick=\"get_report_data_more('down_adv_spec');\">Баннерные спецпредложения</span>
                        </div>
                        <div id='more_res'></div>";
        return $html;
    }

    private function getMonthOptions($seller){
        $html = '';
        $m_value = Yii::$app->request->get("m");
        $res = \Yii::$app->db->createCommand("
			SELECT DISTINCT YEAR(date_begin) as y, MONTH(date_begin) as m
			FROM bill_transaction
			where account_id={$seller->bill_account_id} or account_id in (select id from bill_account where owner_id={$seller->bill_account_id})
			order by y desc, m desc limit 2
		")->queryAll();
        foreach ((array) $res as $r)
        {
            $m = SiteService::getMonthByIndex($r["m"]);
            $arr_month[] = $value = "{$r["y"]}_{$r["m"]}";
            $selected = $m_value == $value ? " selected" : "";
            $html .= "<option value=\"{$value}\"{$selected}>{$m} {$r["y"]}</option>";
        }
        return $html;
    }

    private function getTransactionsHtml($seller){
        $m = Yii::$app->request->get("m");
        if(isset($m)){
            $m = explode("_", $m);
            $mm = "{$m[0]}_{$m[1]}";
        }
        $date_from = Yii::$app->request->get("date_from");
        $date_to = Yii::$app->request->get("date_to");

        if ($date_from && $date_to){
            $sql_m = " and ((type in ('down_catalog') and (date_begin - INTERVAL 1 DAY) BETWEEN '{$date_from}' AND '{$date_to} 23:59:59') OR (not(type in ('down_catalog')) and date_begin BETWEEN '{$date_from}' AND '{$date_to} 23:59:59'))";
        } else {
            if(isset($mm))
            {
                $sql_m = " and YEAR(date_begin)={$m[0]} and MONTH(date_begin)={$m[1]}";
            }
            else
            {
                $res = \Yii::$app->db->createCommand("SELECT YEAR(date_begin) as y, MONTH(date_begin) as m FROM bill_transaction WHERE account_id={$seller->bill_account_id} order by date_begin desc limit 1")->queryAll();
                if (count($res))
                {
                    $y = $res[0]["y"];
                    $m = $res[0]["m"];
                }
                else
                {
                    $y = date("Y");
                    $m = date("m");
                }
                $sql_m = " and ((type in ('down_catalog') and YEAR(date_begin - INTERVAL 1 DAY)={$y} and MONTH(date_begin - INTERVAL 1 DAY)={$m}) OR (not(type in ('down_catalog')) and YEAR(date_begin)={$y} and MONTH(date_begin)={$m}))";
            }
        }

        $res = \Yii::$app->db->createCommand("
			select *, IF(type in ('down_catalog','back_down_catalog'), date_begin - INTERVAL 1 DAY, date_begin - INTERVAL 1 HOUR) as date_begin
			from bill_transaction
			where (account_id={$seller->bill_account_id} or account_id in (select id from bill_account where owner_id={$seller->bill_account_id})) {$sql_m} and not(date_end is null)
			order by date_end desc, id desc
		")->queryAll();

        $data = array();
        foreach ((array) $res as $r)
        {
            $key = date("d.m.Y", strtotime($r["date_begin"]));
            if (!isset($data[$key]))
                $data[$key] = array();
            $data[$key][] = $r;
        }

        $html = "";
        foreach ((array) $data as $day => $r_day)
        {
            $n = count($r_day);
            $flag_first = true;
            $html_data = "";
            $sum = $sum_bonus = $balance = $balance_bonus = 0.0;
            foreach ((array) $r_day as $r)
            {
                $is_bonus = ($r["account_id"]!=$seller->bill_account_id);

                $obj = BillTransaction::find()->where(['id' => $r['id']])->one();
                $obj_type = BillTransactionType::find()->where(['code' => $obj->type])->one();

                $desc = $obj_type->name;
                if ($error = $obj->error)
                {
                    $desc = "{$desc} <br /><b>{$error}</b>";
                    $class = "class=\"error\"";
                }
                elseif ($obj->value > 0)
                    $class = "class=\"up\"";

                $time = in_array($obj->type, array('down_catalog','back_down_catalog')) ? '' : date('H:i', strtotime($obj->date_begin));

                if ($flag_first)
                {
                    $d = strtotime($day);
                    $date_day = date("d.m.Y", mktime(0, 0, 0, date("n", $d), date("j", $d), date("Y", $d)));

                    if ($d<=mktime(0,0,0,12,7,2011))
                        $date_day_popup = date("d.m.Y", mktime(0, 0, 0, date("n", $d), date("j", $d)-1, date("Y", $d)));
                    else
                        $date_day_popup = date("d.m.Y", mktime(0, 0, 0, date("n", $d), date("j", $d), date("Y", $d)));

                    $day_html = $flag_first ? "<td rowspan=\"{$n}\" valign=\"top\" class=\"day\"><span class='badge badge-primary-outline' style='font-size: 14px;'>{$date_day}</span></td>" : "";
                }
                else
                {
                    $day_html = "";
                }

                if ($is_bonus)
                {
                    $balance_bonus = $obj->balance_before;
                }
                else
                {
                    $balance = $obj->balance_before;
                }

                $value = $obj->value;
                $balance_before = round($obj->balance_before,2);
                if ($is_bonus)
                {
                    $value .= " (бонус)";
                    $balance_before .= " (бонус)";
                }
                $href = "";

                if ($r["type"]=="down_auction")
                {
                    $value = "<a data-remote=\"/bill-report/get-report-auction/?d={$date_day_popup}&c={$r["object_id"]}\" data-toggle=\"ajaxModal\" data-target=\".bd-example-modal-lg\">{$value}</a>";
                }
                if ($r["type"]=="down_spec")
                {
                    $value = "<a data-remote=\"/bill-report/get-report-spec\?d={$date_day_popup}&c={$r["object_id"]}\" data-toggle=\"ajaxModal\" data-target=\".bd-example-modal-lg\" >{$value}</a>";
                }
                if ($r["type"]=="down_adv_spec")
                {
                    $value = "<a data-remote=\"/bill-report/get-report-adv-spec\?d={$date_day_popup}&c={$r["object_id"]}\" data-toggle=\"ajaxModal\" data-target=\".bd-example-modal-lg\" >{$value}</a>";
                }

                if (in_array($r["type"], array('info_back_auction','info_back_adv_spec')))
                {
                    if ($is_bonus)
                        $desc = "{$desc}: <b>{$obj->data['value']} (бонус)</b>";
                    else
                        $desc = "{$desc}: <b>{$obj->data['value']}</b>";
                    $value = "";
                    $balance_before = "";
                    $time = "";
                }

                $html_data .= $this->renderPartial("tmpl/report-row", array(
                "class" => isset($class) ? $class : "",
                "day_html" => $day_html,
                "desc" => $desc,
                "balance_before" => isset($balance_before) ? $balance_before : "",
                "value" => $value,
                'time' => $time
            )) ;


                $flag_first = false;
                if (!in_array($r["type"], array('info_back_auction','info_back_adv_spec')))
                {
                    if ($is_bonus)
                    {
                        $sum_bonus += $obj->value;
                    }
                    else
                    {
                        $sum += $obj->value;
                    }
                }
            }

            $d = strtotime($day);

            $balance = round($balance + $sum, 2);
            $balance_bonus = round($balance_bonus + $sum_bonus, 2);
            $sum = round($sum, 2);
            $sum_bonus = round($sum_bonus, 2);

            $html .= $this->renderPartial("tmpl/report-day", array(
                "data" => $html_data,
                "sum" => "{$sum}<br />{$sum_bonus}(бонус)",
                "balance" => "{$balance}<br />{$balance_bonus}(бонус)"
            ));
        }

        return $html;
    }
}
