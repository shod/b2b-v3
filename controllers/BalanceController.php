<?php

namespace app\controllers;

use app\models\BlankTypes;
use app\models\Seller;
use app\models\SysStatus;
use app\models_ex\BillAccount;
use Yii;
use yii\filters\AccessControl;
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


    public function actionAdd()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();

        $f_offerta = $seller->f_offerta;

        if(($f_offerta & 1) && ($f_offerta & 2)){
            $vars['choise'] = $this->renderPartial('tmpl/nds');
        }

        if(!($f_offerta & 1) && ($f_offerta & 2)){
            $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
            $nds = 0;
        } else {
            $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;
            $nds = 1;
        }


        $pay = \Yii::$app->db->createCommand("select * from seller_promice_pay where seller_id = {$this->seller_id} and is_repaid=0")->queryOne();
        $sum = (count($pay)) > 0 ? (float)round($pay['sum']*$curs,2) : 0;

        $balance = round($bill_account->balance*$curs,2);
        $sum += $balance < 0 ? -$balance : 0;

        $blanks = BlankTypes::find()->where(['seller_type' => $seller->pay_type, 'hidden' => 0])->all();
        $vars['blanks'] = '';
        foreach ($blanks as $key => $b){
            $blank_array = $b->toArray();

            if($blank_array['sum'] > 0){
                $blank_array['pay_sum'] = $blank_array['sum'] * $curs;
            } else {
                $blank_array['pay_sum'] = $bill_account->getDayDown()*$blank_array['count_day'] * $curs;
            }
            $blank_array["finish"] = $blank_array['pay_sum'];
            if($nds){
                $blank_array["finish"] = $blank_array['pay_sum'] * 1.2;
                $blank_array["nds"] = $blank_array['pay_sum'] * 0.2;;
            } else {
                $blank_array["nds"] = 0;
            }
            if($blank_array['add_promise']){
                $blank_array['sum_promise'] = $sum;
                $blank_array["finish"] += $sum;
            } else {
                $blank_array['sum_promise'] = 0;
            }

            $vars['blanks'] .= $this->renderPartial('tmpl/bill-item', $blank_array);
        }

        return $this->render('add', $vars);
    }

    public function actionPromise()
    {
        return $this->render('promise');
    }

    public function actionAkt()
    {
        return $this->render('akt');
    }

    public function actionReport()
    {
        return $this->render('report');
    }

}
