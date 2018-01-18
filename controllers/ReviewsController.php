<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\Review;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class ReviewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    public $offset = 10;
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
        $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
        $vars["data"] = $this->get_data($page);
        $vars["pages"] = $this->get_pages($page);
        //$vars["submit"] = $whirl->processor->process_template(null, "content_reviews", "tmpl/submit", array());
        $vars["page"] = $page;
        return $this->render('index', $vars);
    }

    public function actionSaveAnswers(){
        if ($this->seller_id)
        {
            $answer = Yii::$app->request->post("answer");

            foreach((array)$answer as $id=>$text)
            {
                $review_answer = Review::find()->where(['owner_id' => $id])->one();
                if(isset($review_answer)){
                    $sql = "update review set review = '{$text}' WHERE owner_id = {$id}";
                    \Yii::$app->db->createCommand($sql)->execute();
                } else {
                    $time = time();
                    $sql = "INSERT into review (owner_id,review, type, object_id, date, active,moderate) values ({$id}, '{$text}', 1, {$this->seller_id}, {$time}, 1, 1)";
                    \Yii::$app->db->createCommand($sql)->execute();

                }
            }
            return $this->redirect(['reviews/index']);
        }
    }

    private function  get_data($page){
        $start = $page * $this->offset;
        $res = \Yii::$app->db->createCommand("
			select r.*, r1.review as answer
			from review r
			left join review r1 on (r1.owner_id=r.id)
			where r.object_id={$this->seller_id} and r.type = 1 and r.active=1 and r.owner_id=0
			order by date desc
			limit {$start},{$this->offset}
			")->queryAll();
        $html = "";
        foreach((array)$res as $r)
        {
            $r["date"] = SiteService::getStrDate(($r["date"]));
            $r["stars"] = SiteService::starIterate($r["popular"] / 100.0);
            $r["delivery"] = $r["delivery"] ? "<b>Доставка:</b> {$r["delivery"]} <br />" : "";
            $r["cost"] = $r["cost"] ? "<b>Цена:</b> {$r["cost"]} <br />" : "";
            $r["tmpl_conclusion"] = in_array($r["conclusion"],array("yes", "no")) ? $this->renderPartial('tmpl/conclusion_'.$r["conclusion"]) : "";
            $html .= $this->renderPartial('tmpl/row', $r);
        }
        return $html;
    }

    private function get_pages($page){
        $res = \Yii::$app->db->createCommand("select count(1) as cnt from review where object_id={$this->seller_id} and active=1")->queryOne();

        $count = $res['cnt'];
        $page_all = ceil($count / $this->offset);
        $first = 1;
        $last = $page_all;
        $url = "/reviews/?";

        $pages = SiteService::get_pages($page, $first, $last, $url);
        return $pages;
    }
}
