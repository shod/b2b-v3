<?php

namespace app\controllers;

use app\models\NotifierMessageB2b;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class NotificationsController extends Controller
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

    public function actionProcess(){
        $action = Yii::$app->request->get("action");
        switch ($action) {
            case "get_notify":
                $notification = NotifierMessageB2b::find()->where(['seller_id' => $this->seller_id, 'type' => 'popup', 'status'=>'0'])->one();

                if(count($notification) > 0){
                    $data["id"] = $notification->id;
                    $params = $notification->param;
                    $params = json_decode($params, true);
                    $data["href"] = $params['href'];
                    $data["button_name"] = $params["button_name"];

                    $tmpl = $this->renderPartial($notification->tmpl, $params);
                    $data["tmpl"] = $tmpl;

                    echo json_encode($data);
                }
                break;
            case "set_notify":
                $id = Yii::$app->request->get("id");
                $notification = NotifierMessageB2b::find()->where(['seller_id' => $this->seller_id, 'id' => $id])->one();
                $notification->status = 1;
                $notification->save();
                break;
        }
    }

}
