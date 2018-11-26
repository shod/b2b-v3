<?php

namespace app\controllers;

use app\helpers\NumberService;
use app\helpers\SiteService;
use app\models\BlankTypes;
use app\models\SellerInfo;
use app\models_ex\Member;
use app\models\Seller;
use app\models\SysStatus;
use app\models_ex\BillAccount;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class BalanceController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;

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

    public function actionGetBlanks(){
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $type = Yii::$app->request->get("type");
        $f_offerta = $seller->f_offerta;
        $choise = "";
        if(($f_offerta & 1) && ($f_offerta & 2)){
            $checked = $type=='true' ? "checked" : "";
            $choise = $this->renderPartial('tmpl/nds', ['checked' => $checked]);
        }

        if($type=='true'){
            $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
            $nds = 0;
        } else {
            $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;
            $nds = 1;
        }
        $blanks = $this->getBlanks($seller, $curs, $nds);
        $array_data = [
            'html' => $choise . $blanks,
            'te' => $curs
        ];
        return Json::encode($array_data);
    }

    public function actionBlankop(){
        $id = Yii::$app->request->get("id");
        $type = Yii::$app->request->get("type");
        $render_type = Yii::$app->request->get("render-type");
        $my_sum = Yii::$app->request->get("my_sum");

        if($type==1){
            $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
            $nds = 0;
            $official_data = array(
                "official_name" => "ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ШМЫК ОЛЕГ ДМИТРИЕВИЧ",
                "official_unp" => "УНП 191182046",
                "official_address" => "220045, г.Минск, пр-т Дзержинского, 131-305",
                "official_rs" => "BY26 REDJ 3013 1009 2300 1000 0933 в BYN ЗАКРЫТОЕ АКЦИОНЕРНОЕ ОБЩЕСТВО &quot;РРБ-БАНК&quot; ЦБУ №9, 220005, пр-т Независимости, 58, Минск, Республика Беларусь, БИК: REDJBY22",
                "official_phone" => "тел.: +375 (29) 112 45 45",
                "official_faximille" => "https://b2b.".\Yii::$app->params['migom_domain']."/img/design/faximille_od.jpg",
                "official_owner" => "Шмык О. Д.",
                "official_percent" => "",
                "official_nds" => "",
            );
        } else {
            $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;
            $nds = 1;
            $official_data = array(
                "official_name" => "ООО &quot;Альметра&quot;",
                "official_unp" => "УНП 192147793 ОКПО 381393215000",
                "official_address" => "220007, г. Минск, ул. Могилевская 2/2, помещение 10-1",
                "official_rs" => "р/с BY43ALFA30122078930080270000, в банке ЗАО &quot;Альфа-Банк&quot;. Центральный офис, код 270, ул. Сурганова, 43-47, 220013 Минск, БИК ALFABY2X",
                "official_phone" => "тел.: +375 (29) 112 45 45",
                "official_faximille" => "https://b2b.".\Yii::$app->params['migom_domain']."/img/design/faximille.jpg",
                "official_owner" => "Кладухина О.Н.",
                "official_percent" => "20",
                "official_nds" => "Сумма НДС:",
            );
            $official_data = array(
                "official_name" => "ООО &quot;Марталь&quot;",
                "official_unp" => "УНП 192583317",
                "official_address" => "г.Минск, ул.Могилевская, д.2, корп.2, пом.18",
                "official_rs" => "р/с BY54ALFA30122122470010270000, в банке ЗАО &quot;Альфа-Банк&quot;.  Центральный офис ул.Советская, 12, 220030, г.Минск, БИК ALFABY2X",
                "official_phone" => "тел.: +375 (29) 112 45 45",
                "official_faximille" => "https://b2b.".\Yii::$app->params['migom_domain']."/img/design/faximille_martal.jpg",
                "official_owner" => "Шмык О.Д.",
                "official_percent" => "20",
                "official_nds" => "Сумма НДС:",
            );
        }

        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $seller_info = SellerInfo::find()->where(['seller_id' => $this->seller_id])->one();
        $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();
        $member = Member::find()->where(['id' => $seller->member_id])->one();
        $member_data = $member->getMemberProperties();

        if($my_sum){
            $sum = $my_sum;
        } else {
            $blank = BlankTypes::find()->where(['id' => $id])->one();
            if ($blank->sum > 0){
                $sum = $blank->sum * $curs;
            } else {
                $sum = $blank->count_day * $bill_account->getDayDownCatalog()  * $curs;
            }
            if($blank->add_promise){
                $pay = \Yii::$app->db->createCommand("select * from seller_promice_pay where seller_id = {$this->seller_id} and is_repaid=0")->queryOne();
                $sum_promise = (count($pay)) > 0 ? (float)round($pay['sum']*$curs,2) : 0;

                $balance = round($bill_account->balance*$curs,2);
                $sum_promise += $balance < 0 ? -$balance : 0;
                $sum += $sum_promise;
            }
        }


        $sum_str = NumberService::num2str($sum);
        $sum_nds = $nds ? $sum * 0.2 : 0;
        $nds_str = $nds ? NumberService::num2str($sum_nds) : "";
        $sum_all = $sum + $sum_nds;
        $sum_all_str = NumberService::num2str($sum_all);
        $this->layout = false;

        \Yii::$app->db->createCommand('start transaction;')->execute();
        \Yii::$app->db->createCommand("call pc_getsellerblanknum()")->execute();
        $res = \Yii::$app->db->createCommand("select max(num) as num from seller_blank_number where seller_id = 1500")->queryOne();
        \Yii::$app->db->createCommand('commit;')->execute();

        $docnum = $res['num'];

        $vars = array_merge($official_data, $member_data);
        $vars = array_merge([
            "seller_id" => $this->seller_id,
            "docnum" => $docnum,
            "date_p" => date('d m Y'),
            "sum" => number_format(round($sum, 2), 2,'.',' '),
            "sum_str" => $sum_str,
            "sum_nds" => number_format($sum_nds, 2,'.',' '),
            "nds_str" => $nds_str,
            "sum_all" => number_format(round($sum_all, 2), 2,'.',' '),
            "sum_all_str" => $sum_all_str,
            "contract_number" => $seller_info->contract_number,
            "contract_date" => date('d.m.Y',$seller_info->contract_date),
            "fax" => isset($member_data['fax']) ? $member_data['fax'] : "",
            "text" => isset($blank) ? "id {$this->seller_id} " . $blank->blank_text : "id {$this->seller_id} Услуги по размещению рекламных материалов <br>
            на сайте ".\Yii::$app->params['migom_name']." на сумму"
        ],$vars);
        if($render_type == 'html'){
            return $this->render('tmpl/blankop/html-type', $vars);
        }

        if($render_type == 'xlsx'){
            $html_data = $this->render('tmpl/blankop/xlsx-type', $vars);
            header('Content-Type: text/html; charset=windows-1251');
            header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');
            header('Content-transfer-encoding: binary');
            header("Content-Disposition: attachment; filename=bill-{$this->seller_id}.xls");
            header('Content-Type: application/x-unknown');
            header('Content-Encoding: UTF-8');
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            echo $html_data;
            exit;
        }

        if($render_type == 'pdf'){
            try {
                $html_data = $this->render('tmpl/blankop/pdf-type', $vars);
                $mpdf = new \mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
                $mpdf->charset_in = 'cp1251';
                $mpdf->WriteHTML($html_data);
                $mpdf->Output("bill-{$this->seller_id}.pdf", 'I');;
                exit;

            } catch (Exception $e) {
                echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            }
            exit;
        }

    }

    public function actionGetPromise(){
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();
        $f_offerta = $seller->f_offerta;

        if(!($f_offerta & 1) && ($f_offerta & 2)){
            $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
        } else {
            $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;
        }

        $sum = (int)Yii::$app->request->post('sum');
        $max = (int)Yii::$app->request->post('max');
        $type_promice = Yii::$app->request->post('type_promice');
        if ($type_promice == 'fixed'){
            $res = \Yii::$app->db->createCommand("select promise_delivery from seller_info where seller_id = {$this->seller_id}")->queryAll();
            if (($sum*1 <= $max*1)&&($res[0]['promise_delivery'] == 0)){
                \Yii::$app->db->createCommand("update seller_info set promise_delivery=1 where seller_id = {$this->seller_id}")->execute();

                $te = round(($sum / $curs) * 1.0,2);
                Yii::$app->db->createCommand("INSERT INTO seller_promice_pay (seller_id,sum, date) VALUES ('{$this->seller_id}', {$te},NOW())")->execute();
                \Yii::$app->billing->transaction($this->seller_id, 'up_promice_pay', $te);
            }
        } else {
            $seller_choise = Yii::$app->request->post('seller_choise');
            if ($seller_choise == 'set_sum_pay'){

                $sum = (float)Yii::$app->request->post('set_sum_pay');
                $te = ($sum / $curs) * 1.0;
                $clicks = round($te/0.4);

                \Yii::$app->db->createCommand("update seller_info set promise_delivery=1 where seller_id = {$this->seller_id}")->execute();
                \Yii::$app->db->createCommand("INSERT INTO seller_promice_pay (seller_id,sum, date) VALUES ('{$this->seller_id}', {$te},NOW())")->execute();

                $sql = "select bct.id, cost_click from seller_click_tarif as st, bill_click_tarif as bct
                                    where st.seller_id = {$this->seller_id} and bct.id = st.bill_click_tarif_id ORDER BY st.inserted_at desc LIMIT 1;";
                $res = \Yii::$app->db->createCommand($sql)->queryAll();

                if ($res[0]['id'] == 1){
                    \Yii::$app->billing->transaction($this->seller_id, 'up_promice_pay', $te);
                } else {
                    \Yii::$app->billing->transaction($this->seller_id, 'up_click', $clicks);
                }
            }
        }

        return $this->redirect(['balance/promise']);
    }

    public function actionAdd()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();

        $f_offerta = $seller->f_offerta;

        if(!($f_offerta & 1) && ($f_offerta & 2)){
            $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
            $nds = 0;
        } else {
            $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;
            $nds = 1;
        }
        $vars['curs'] = $curs;
        $member = Member::find()->where(['id' => $seller->member_id])->one();
        $member_data = $member->getMemberProperties();
        if(count($member_data) < 5){
            $vars['blanks'] = "<h3>Для выставления счета необходимо заполнить <a href='/settings'>информацию о юридическом лице!</a> </h3>";
        } else {
            if(($f_offerta & 1) && ($f_offerta & 2)){
                $vars['choise'] = $this->renderPartial('tmpl/nds', ['checked' => '']);
            }
            $vars['blanks'] = $this->getBlanks($seller, $curs, $nds);
        }

        $vars['info'] = $this->getInfo($seller);
        $vars['f_offerta'] = $seller->f_offerta;
        $vars['pay_type'] = $seller->pay_type;
        $vars['seller_id'] = $this->seller_id;

        return $this->render('add', $vars);
    }

    public function actionPromise()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();

        $f_offerta = $seller->f_offerta;

        if(!($f_offerta & 1) && ($f_offerta & 2)){
            $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
        } else {
            $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;
        }

        $vars['day_down'] = (round($bill_account->getDayDownCatalog(),2)*4*(float)$curs/100)*100;
        $res = \Yii::$app->db->createCommand("SELECT
                                            si.b2b_karma,
                                            si.promise_delivery,
                                            IF((((IF(s.date_act >= IFNULL(s.date_deact, NOW()), NOW(), s.date_deact) < DATE_ADD(now(), INTERVAL - 1 MONTH)) and s.active = 0) or (s.date_act is null and s.date_deact is null)),1,0) as block_deact
                                        FROM
                                            seller_info as si, seller as s
                                        WHERE
                                            si.seller_id = {$this->seller_id} and si.seller_id = s.id")->queryAll();

        $res_check = \Yii::$app->db->createCommand("select 1 as check_dost
                                                                            from seller as sl, bill_transaction as bt
                                                                            where sl.id = {$this->seller_id}
                                                                            and bt.account_id = sl.bill_account_id
                                                                            and bt.type not in ('up_promice_pay')
                                                                            and date_begin > DATE_ADD(now(),INTERVAL -3 MONTH)
                                                                            order by bt.id desc
                                                                            limit 1")->queryAll();
        if ($res[0]['promise_delivery'] == 1 ){
            $vars["disabled"] = 'disabled';
            $vars['text'] = '<div class="alert alert-danger ks-solid" role="alert">Вы уже брали обещанный платеж</div>';
        } elseif ($res[0]['b2b_karma'] != 1){
            $vars["disabled"] = 'disabled';
            $vars['text'] = '<div class="alert alert-danger  ks-solid" role="alert">Вам недоступен обещанный платеж!</div>';
        } elseif (count($res_check) != 1){
            $vars["disabled"] = 'disabled';
            $vars['text'] = '<div class="alert alert-danger  ks-solid" role="alert">Вам недоступен обещанный платеж, в связи с отсутствием активности в течении 3-х месяцев.</div>';
        } elseif($res[0]['block_deact'] == 1){
            $vars["disabled"] = 'disabled';
            $vars['text'] = '<div class="alert alert-danger  ks-solid" role="alert"> Обещанный платеж недоступен! Вы были неактивны более месяца!</div>';
        } else {
            $vars["disabled"] = '';
        }

        if($seller->pay_type == 'fixed'){
            $vars['day_down'] = (round($bill_account->getDayDown(1),2)*4*(float)$curs/100)*100;
            //$vars['page_data'] = $whirl->processor->process_template(null, "content_billing", "tmpl/promice_fixed", $vars);
            return $this->render('promise',$vars);
        } else {
            $sql = "select ROUND(avg(cnt_click)*cost_click*4) as click_cost
                            from (select seller_id, cnt_click from seller_clicks_stat
                            where seller_id = {$this->seller_id} and cnt_click>0 order by date_stat desc limit 10) as qw
                            , seller_click_tarif as ct, bill_click_tarif as bc
                            where ct.seller_id = qw.seller_id
                            and bc.id = ct.bill_click_tarif_id;";
            $res_sum =\Yii::$app->db->createCommand($sql)->queryAll();
            $vars['day_te'] = round($res_sum[0]['click_cost'],2);
            $vars['sum_click'] = intval($vars['day_te'] / 0.4);
            $vars['day_down'] = ($vars['day_te']*(float)$curs/100)*100;
            //$vars['page_data'] = $whirl->processor->process_template(null, "content_billing", "tmpl/promice_clicks", $vars);
            return $this->render('promise_clicks',$vars);
        }

    }

    private function getInfo($seller){
        $html = "";
        if($seller->pay_type == 'fixed'){
            $sql_actions = "select bc.name, DATE_FORMAT(date_expired, '%d.%m.%Y') as date
            from bill_cat_sel_discount as bcd, bill_catalog as bc
            where bcd.seller_id = {$this->seller_id}
            and bc.id = bcd.catalog_id
			and date_expired >= now();";

            $actions = \Yii::$app->db->createCommand($sql_actions)->queryAll();
            if(count($actions) > 0){
                $actions_html = "";
                foreach ($actions as $action){
                    $actions_html .= $this->renderPartial('tmpl/action_item', $action);
                }
                $html .= $this->renderPartial('tmpl/action_table', ['actions' => $actions_html]);
            } else {
                $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();

                $vars['balance_clicks'] = $bill_account->balance;
                $vars["click_bill_text"] = "Баланс: ";

                $html .= $this->renderPartial('tmpl/clicks_data', $vars);
                $days_left = $bill_account->get_days_left();
                $html .= "Прогноз отключения: <mark>" . SiteService::human_plural_form($days_left, array("сутки", "суток", "суток")) . "</mark>";
            }
        } elseif ($seller->pay_type =='clicks'){
            $sql = "select bct.id, cost_click from seller_click_tarif as st, bill_click_tarif as bct
                    where st.seller_id = {$this->seller_id} and bct.id = st.bill_click_tarif_id ORDER BY st.inserted_at desc LIMIT 1;";
            $res = \Yii::$app->db->createCommand($sql)->queryAll();
            $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();
            if ((count($res) == 1) && floor((float)$bill_account->balance / (float)$res[0]['cost_click']) > 0){
                $vars['balance_clicks'] = floor((float)$bill_account->balance / (float)$res[0]['cost_click']);
                $vars['balance_clicks'] = $vars['balance_clicks'] > 0 ? $vars['balance_clicks'] : "0.4 ТЕ";
                $vars["click_bill_text"] = "Стоимость клика: <span class='badge'>0.4 ТЕ</span> ";
            } else {
                $vars['balance_clicks'] = $bill_account->balance_clicks;
                $vars["click_bill_text"] = "Баланс показов/переходов: ";
            }
            $html .= $this->renderPartial('tmpl/clicks_data', $vars);
        }
        return $html;
    }

    private function getBlanks($seller, $curs, $nds){
        $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();

        $pay = \Yii::$app->db->createCommand("select * from seller_promice_pay where seller_id = {$this->seller_id} and is_repaid=0")->queryOne();
        $sum = (count($pay)) > 0 ? (float)round($pay['sum']*$curs,2) : 0;

        $balance = round($bill_account->balance*$curs,2);
        $sum += $balance < 0 ? -$balance : 0;

        $blanks = BlankTypes::find()->where(['seller_type' => $seller->pay_type, 'hidden' => 0])->all();
        $blanks_items = '';
        foreach ($blanks as $key => $blank){
            $blank_array = $blank->toArray();

            if ($blank->sum > 0){
                $blank_array['pay_sum'] = $blank->sum * $curs;
            } else {
                $blank_array['pay_sum'] = $blank->count_day * $bill_account->getDayDownCatalog()  * $curs;
            }

            $blank_array["finish"] = $blank_array['pay_sum'];

            if($blank->add_promise){
                $pay = \Yii::$app->db->createCommand("select * from seller_promice_pay where seller_id = {$this->seller_id} and is_repaid=0")->queryOne();
                $sum_promise = (count($pay)) > 0 ? (float)round($pay['sum']*$curs,2) : 0;
                $balance = round($bill_account->balance*$curs,2);
                $sum_promise += $balance < 0 ? -$balance : 0;

                $blank_array['sum_promise'] = $sum_promise;
                $blank_array["finish"] += $sum_promise;
            } else {
                $blank_array['sum_promise'] = 0;
            }

            if($nds){
                $blank_array["finish"] = $blank_array['finish'] * 1.2;
                $blank_array["nds"] = $blank_array['finish'] * 0.2;;
            } else {
                $blank_array["nds"] = 0;
            }

            $blanks_items .= $this->renderPartial('tmpl/bill-item', $blank_array);
        }
        $blanks_items .= $this->renderPartial('tmpl/bill_item_my_sum', ['id' => 0, 'nds' => $nds, 'pay_type' => $seller->pay_type, 'curs' => $curs]);

        return $blanks_items;
    }

}
