<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\B2bNews;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    public $pg;
    public $offset;
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

    public function actionIndex()
    {
        $this->pg = Yii::$app->request->get('page') - 1;

        if ($this->pg < 0){
            $this->pg = 0;
        }

        if ($this->pg > 0){
            $this->offset = $this->pg * 6;
        } else {
            $this->offset = 0;
        }

        $news = B2bNews::find()->where(['hidden' => 1])->orderBy(['id' => SORT_DESC])->limit(6)->offset($this->offset)->all();
        $count_news = B2bNews::find()->count();
        $pages = SiteService::get_pages($this->pg,1,ceil($count_news/6),'/news/?');
        $items = "";
        foreach ($news as $n){
          $items .= $this->renderPartial('tmpl/item', ['news' => $n]);
        }
        return $this->render('index', ['items' => $items, 'pages' => $pages]);
    }
}
