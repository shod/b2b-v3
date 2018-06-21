<?php

namespace app\controllers;

use app\models\SysObjectProperty;
use app\models_ex\Member;
use app\models\Seller;
use app\models\SysStatus;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        if ((\Yii::$app->getUser()->isGuest)&&($action->id != 'login')&&($action->id != 'sign-up')) {
            $this->redirect('site/login');
        } else {
            return parent::beforeAction($action);
        }
    }

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
                    'logout' => ['get'],
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

    public function actionWidget($widget_name)
    {
        $get = $_GET;
        unset($get['widget_name']);
        $widget_path = 'app\components\widgets\\' . ucfirst($widget_name);
        return $widget_path::widget($get);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->view->params['customParam'] = 'B2B главная.';
        $seller_id = Yii::$app->user->identity->getId();
        return $this->render('index', ['sid' => $seller_id]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = false;
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionSignUp()
    {
        $this->layout = false;
        if (Yii::$app->request->post()) {
            $login = stripslashes(Yii::$app->request->post("login"));
            $pass = stripslashes(Yii::$app->request->post("pass"));

            $f_offerta = Yii::$app->request->post("f_offerta");

            $offerta = 0;
            foreach ((array)$f_offerta as $f) {
                $offerta = $offerta & ~(int)$f | (1 * $f);
            }

            if ($login == '' or $pass == '0') {
                $this->redirect('site/sign-up');
            }
            $member = new Member();
            $member->login = $login;
            $member->pwd = crypt($pass);
            $member->type_reg = 7;
            $member->email = Yii::$app->request->post("email");
            $member->f_reg_confirm = 0;
            $member->save();


            $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
            $currency_rate = $curs * 10000;
            $setting_data = serialize(array("currency_base" => "byn", "currency_rate" => $currency_rate));

            $seller = new Seller();
            $seller->name = Yii::$app->request->post("shop");
            $seller->member_id = $member->id;
            $seller->active = 0;
            $seller->setting_data = $setting_data;
            $seller->f_offerta = $offerta;
            $seller->work_time = 'a:7:{i:1;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:2;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:3;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:4;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:5;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:6;a:2:{i:0;s:5:"";i:1;s:5:"";}i:0;a:2:{i:0;s:0:"";i:1;s:0:"";}}';
            $seller->save();

            $property = SysObjectProperty::find()->where(["object_type_id" => 7, 'name' => ['email', 'phone', 'fio']])->all();
            $member_ex = Member::find()->where(['id' => $seller->member_id])->one();
            foreach ((array) $property as $name) {
                $value = Yii::$app->request->post($name->name);
                if($value){
                    $member_ex->setMemberProperty($name->name,$value,$name->id);
                }
            }

            //Seller registration
            \app\helpers\SysService::EventAdd(\app\helpers\SysService::SEND_MAIL, array(
                    'tmpl'=>'seller_registration_email',
                    'subject'=>'Migom.by - Регистрация продавца',
                    'time' => date("Y-m-d H:i")
                )
            );

           /* $str = "URL: " . $_SERVER['HTTP_REFERER'] . " TIME: " . date('d.m.Y H:i') . " IP: " . $_SERVER['REMOTE_ADDR'] . " QUERY_STRING: " . $_SERVER['QUERY_STRING'];
            foreach ((array)$_SERVER as $index => $ars) {
                $str .= "[" . $index . "]=" . $ars . "\n";
            }

            $f = fopen($_SERVER["DOCUMENT_ROOT"] . '/logs/registration.txt', 'a');
            fputs($f, $str . PHP_EOL);
            fclose($f);

            $P->data["seller_id"] = $seller_id;
            $P->data["f_offerta"] = $offerta;

            $whirl->mailer('seller')->delayed(0, 'registration', $P->data);*/
            return $this->render('splash-reg');
        } else {
            return $this->render('sign-up');
        }

    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionAsk(){
        $json["header"] = 'Обратная связь';
        $json["body"] = $this->renderPartial('aks_form');
        echo Json::encode($json);
    }

    public function actionSendQuestion(){
        $text = str_replace('"', "", Yii::$app->request->post("question"));
        $seller_id = Yii::$app->user->identity->getId();
        $seller = Seller::find()->where(['id' => $seller_id])->one();
        $member = Member::find()->where(['id' => $seller->member_id])->one();
        $member_data = $member->getMemberProperties();
        $name = str_replace('"', "", $seller->name);
        $email = $member_data['email'];
        $fio = isset($member_data['fio']) ? str_replace('"', "", $member_data['fio']) : "";
        $phone = isset($member_data['fax']) ? $member_data['fax'] : "";
        $phone2 = isset($member_data['phone']) ? $member_data['phone'] : "";

        $text_email = "<p><b>Продавец:</b> [{$seller_id}] {$name}</p>";
        $text_email .= "<p><b>Email:</b> {$email}</p>";
        $text_email .= "<p><b>ФИО:</b> {$fio}</p>";
        $text_email .= "<p><b>Телефон:</b> {$phone} / {$phone2}</p>";
        $text_email .= "<p><b>Текст сообщения:</b></p><p>{$text}</p>";
        $admin_emails = ["admin@migom.by","promo@migom.by","sale@migom.by"];
        //$admin_emails = ["nk@migom.by"];
        foreach ($admin_emails as $email){
            \app\helpers\SysService::sendEmail($email, 'Обратная связь', 'support@migom.by', $text_email);
        }
        echo 'Ваше сообщение отправлено! Вам ответят в ближайшее время!';
    }
}
