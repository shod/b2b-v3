<?php

namespace app\controllers;

use app\helpers\NumberService;
use app\helpers\SiteService;
use app\models_ex\BillAccount;
use app\models_ex\Member;
use app\models\Seller;
use app\models\SellerInfo;
use Yii;
use yii\filters\AccessControl;
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAkt()
    {
        return $this->render('akt');
    }
}
