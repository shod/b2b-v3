<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\NotifierMessageB2b;
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
    public $offset = 40;

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
        $cnt = NotifierMessageB2b::find()->where(['seller_id' => $this->seller_id, 'type' => 'notify', 'status'=>'0', 'tmpl' => 'review'])->count();
        if($cnt > 0){
            $sql = "update notifier_message_b2b set status=1 WHERE seller_id = {$this->seller_id} and type='notify' and tmpl = 'review' and status=0";
            \Yii::$app->db->createCommand($sql)->execute();
        }

        $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
        $vars["data"] = $this->get_data($page);
        $vars["pages"] = $this->get_pages($page);
        //$vars["submit"] = $whirl->processor->process_template(null, "content_reviews", "tmpl/submit", array());
        $vars["page"] = $page;
        return $this->render('index', $vars);
    }

    public function actionComplaint(){
        $cnt = NotifierMessageB2b::find()->where(['seller_id' => $this->seller_id, 'type' => 'notify', 'status'=>'0', 'tmpl' => 'complaint'])->count();
        if($cnt > 0){
            $sql = "update notifier_message_b2b set status=1 WHERE seller_id = {$this->seller_id} and type='notify' and tmpl = 'complaint' and status=0";
            \Yii::$app->db->createCommand($sql)->execute();
        }
        $sql = "select po_active, po_balance from seller_info where  seller_id = {$this->seller_id}";
        $res = \Yii::$app->db->createCommand($sql)->queryOne();
        if (count($res) > 0){
            if (($res['po_active'] == 0) || ((int)$res['po_balance'] < 1)){
                if($res['po_active'] == 0) {
                    $vars['alert'] = "<div class=\"alert alert-danger ks-solid-light\" role=\"alert\"><a href='/order/sms'>Услуга \"Обратный звонок\" отключена</a><p>Чтобы не терять клиентов подключите услугу <a href='/order/sms' >SMS-заказы</a></p></div>";
                } else {
                    $vars['alert'] = "<div class=\"alert alert-danger ks-solid-light\" role=\"alert\"><a href='/order/sms'>Предоплаченные СМС уведомления закончились</a><p>Чтобы не терять клиентов подключите услугу <a href='/order/sms' >SMS-заказы</a></p></div>";
                }
            }
        }
        
        $sql = "SELECT
								f.id,
								f.seller_id,
								f.phone,
								FROM_UNIXTIME(f.created_at) AS date,
								f.product_id,
								f.status,
								si.po_balance,
								si.po_phone,
								si.po_email,
								s.work_time,

							IF (
								po_active = 1
								AND po_balance > 0
								AND (
									(po_phone IS NOT NULL)
									OR (po_email IS NOT NULL)
								),
								1,
								0
							) AS po_active,
							 s.name
							FROM
								migombyha.stat_seller_phone_fail f
							JOIN migomby.seller_info si ON (f.seller_id = si.seller_id)
							JOIN migomby.seller AS s ON (s.id = si.seller_id)
							WHERE
								f. STATUS = 0
                            AND FROM_UNIXTIME(f.created_at) > (DATE_SUB(NOW(), INTERVAL 1 MONTH))
							AND f.seller_id = {$this->seller_id} order by f.created_at desc";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();

        $vars['data_complaint'] = '';
        foreach((array)$res as $r)
        {
            $vars['data_complaint'] .= $this->renderPartial('/statistic/tmpl/complaint-item', $r);
        }
        return $this->render('complaint', $vars);
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
            $r["date"] = SiteService::getStrDateNoTime(($r["date"]));
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
