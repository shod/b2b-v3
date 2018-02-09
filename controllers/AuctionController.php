<?php

namespace app\controllers;

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

    public function actionIndex()
    {
        $res = \Yii::$app->db->createCommand("select * from texts where id=214")->queryAll();
        $vars["title"] = $res[0]["name"];
        $vars["text"] = $res[0]["text"];
        $vars["text"] = str_replace(array('$vars[min_stavka]','$vars[min_step]','$vars[min_balance]'),array($this->min_stavka,$this->_step,$this->min_balance),$res[0]["text"]);

        return $this->render('index', $vars);
    }

    public function actionAdd()
    {
        return $this->render('add');
    }
}
