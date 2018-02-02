<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class InfoController extends Controller
{
    /**
     * @inheritdoc
     */
    public $rules =
			array(
			    "products" => 183,
                "bill_catalog" => 182,
                "catalog" => 183,
                "import" => 184,
			    "reviews" => 185,
			    "billing" => 186,
			    "order" => 187,
			    "settings" => 188,
                "rules_placement" => 211
		    );
    public function behaviors()
    {
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
        $id = Yii::$app->request->get("page");
        $vars = [];
        if ($id) {
            $res =  \Yii::$app->db->createCommand("select * from texts where id={$this->rules[$id]}")->queryOne();

            $vars["title"] = $res["name"];
            $vars["text"] = $res["text"];
        }
        return $this->render('index', $vars);
    }
}
