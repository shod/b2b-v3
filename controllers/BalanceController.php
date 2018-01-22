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
        //$bill_account = BillAccount::find()->where(['id' => $seller->bill_account_id])->one();

        return $this->render('add');
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
