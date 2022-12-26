<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\SysObjectProperty;
use app\models_ex\BillAccount;
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
    public function beforeAction($action)
    {
		if (Yii::$app->request->post() && ($action->id == 'sign-up-checklogin')){			
			return true;
		}
     
        if ((\Yii::$app->getUser()->isGuest) && ($action->id != 'login') && ($action->id != 'login-ads') && ($action->id != 'admin-test') && ($action->id != 'sign-up') && ($action->id != 'rules')) {
            $this->redirect(Yii::$app->params['b2b_url'] . '/site/login');
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

    public function actionRules(){
        $this->layout = false;
        $res =  \Yii::$app->db->createCommand("select * from texts where id=211")->queryOne();

        $vars["title"] = $res["name"];
        $vars["text"] = $res["text"];
        return $this->render('rules',$vars);
    }

    public function actionAdminTest()
    {
        $filename = $filename = "seller/logo$1500.jpg";
        SiteService::resize($filename, array(90, 35));
    }

    public function actionGetInfoModal(){
        $name = Yii::$app->request->get('name');
        $type = Yii::$app->request->get('type');
        $json["header"] = $name;
        $json["body"] = $this->renderPartial('modals/'.$type);
        echo Json::encode($json);
    }

    public function actionSaveFeature(){
        $seller_id = Yii::$app->user->identity->getId();
        $seller = Seller::find()->where(['id' => $seller_id])->one();
        $setting_bit = $seller->setting_bit;
        $setting_bit =  SiteService::set_bitvalue($setting_bit,33554432,0);
        $seller->setting_bit = $setting_bit;
        $seller->save();
        echo 'success';
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

		$allow_login_admin_user_ip = \Yii::$app->params['allow_login_admin_user_ip'];
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $seller_id = Yii::$app->user->identity->getId();

            $sql = "call pc_recovery_product_seller_data({$seller_id});";
            \Yii::$app->db->createCommand($sql)->execute();
            $ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
            $is_ads = (isset($_SERVER['HTTP_X_REAL_IP']) && ($_SERVER['HTTP_X_REAL_IP'] == $allow_login_admin_user_ip)) ? 1 : 0;
            \Yii::$app->db->createCommand("insert into b2b_login_log (seller_id,ip,date_login,is_admin,version) values ({$seller_id}, '{$ip}',NOW(),{$is_ads}, 1)")->execute();

            return $this->goBack();
        }
        return $this->render('login', ['model' => $model]);
    }

    public function actionLoginAds()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        $model = new LoginForm();
        $model->username = Yii::$app->request->get('username');

        $allow_login_admin_user_ip = \Yii::$app->params['allow_login_admin_user_ip'];
        //$is_ads = (isset($_SERVER['HTTP_X_REAL_IP']) && ( in_array($_SERVER['HTTP_X_REAL_IP'],$allow_login_admin_user_ip)));
		$is_ads = (isset($_SERVER['HTTP_REFERER']) && ( strpos($_SERVER['HTTP_REFERER'],'admin.vendee.by')));
		
        if($is_ads){
            $model->password = 'Sudoku-2020';
            if ($model->login()) {
                $seller_id = Yii::$app->user->identity->getId();
                $sql = "call pc_recovery_product_seller_data({$seller_id});";
                \Yii::$app->db->createCommand($sql)->execute();
                $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['REMOTE_ADDR'];
                \Yii::$app->db->createCommand("insert into b2b_login_log (seller_id,ip,date_login,is_admin,version) values ({$seller_id}, '{$ip}',NOW(),1,1)")->execute();
                $action = Yii::$app->request->get('action');

                if($action){
                    $this->redirect($action.'/?is_admin=1');
                } else {
                    return $this->goBack();
                }

            } else {
                return 'Неверные данные для входа!';
            }
        }

        return 'Ошибка доступа!';
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

	/** Проверка логина на доступность	
	*/
	public function actionSignUpChecklogin(){
		$res = [
				"valid" => false,
				"message" => "Пользователь с таким логином был зарегистрирован ранее. Введите другой!"
			];
		$login = stripslashes(Yii::$app->request->post("login"));	
		$member = Member::find()->where(['login' => $login])->one();
		
		if(!empty($login) && $member == null){
			$res = [
				"valid" => true,
				"message" => ""
			];
		}
		return json_encode($res);
	}
	
    public function actionSignUp()
    {
        $this->layout = false;
        if (Yii::$app->request->post()) {
            \Yii::info(Yii::$app->request->post(), 'registration');
            $recaptcha = Yii::$app->request->post('g-recaptcha-response');
            if (isset($recaptcha) && !empty($recaptcha)) {
                //your site secret key
                $secret = Yii::$app->params['recaptcha_secret'];//'6LdRrQ0UAAAAAOazxlJaOlEz9jswYSzrGCGStDij';
                //get verify response data
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha);
                $responseData = json_decode($verifyResponse);
                if ($responseData->success) {
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
                    $seller_email = Yii::$app->request->post("email");
                    $member = new Member();
                    $member->login = $login;
                    $member->pwd = crypt($pass, "");
                    $member->type_reg = 7;
                    $member->email = $seller_email;
                    $member->f_reg_confirm = 0;
                    $save_res = $member->save();

                    $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
                    $currency_rate = $curs * 10000;
                    $setting_data = serialize(array("currency_base" => "byn", "currency_rate" => $currency_rate));

                    $seller = new Seller();
                    $seller->name = addslashes(Yii::$app->request->post("shop"));
                    $seller->member_id = $member->id;
                    $seller->active = 0;
                    $seller->setting_data = $setting_data;
                    $seller->f_offerta = $offerta;
                    $seller->work_time = 'a:7:{i:1;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:2;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:3;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:4;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:5;a:2:{i:0;s:5:"09:00";i:1;s:5:"18:00";}i:6;a:2:{i:0;s:5:"";i:1;s:5:"";}i:0;a:2:{i:0;s:0:"";i:1;s:0:"";}}';
                    $seller->save();

                    $property = SysObjectProperty::find()->where(["object_type_id" => 7, 'name' => ['email', 'phone', 'fio', 'company_name']])->all();
                    $member_ex = Member::find()->where(['id' => $seller->member_id])->one();
                    foreach ((array)$property as $name) {
                        $value = addslashes(Yii::$app->request->post($name->name));
                        if ($value) {
                            $member_ex->setMemberProperty($name->name, $value, $name->id);
                        }
                    }

                    $admin_emails = Yii::$app->params['saleEmails'];
                    $seller_data = Yii::$app->request->post();
                    foreach ($seller_data as $key => $value){
                        $seller_data[$key] = is_string($value) ? addslashes($value) : $value;
                    }

                    foreach ($admin_emails as $email) {
                        \app\helpers\SysService::sendEmail($email, \Yii::$app->params['migom_name']." - Регистрация продавца ID [ {$seller->id} ]", Yii::$app->params['fromEmail'], NULL, 'seller/registration_admin', array_merge($seller_data, ['seller_id' => $seller->id, 'offerta' => $seller->f_offerta & 1, 'offerta_no_nds' => $seller->f_offerta & 2, 'seller_email' => $seller_email]));
                    }
                    \app\helpers\SysService::sendEmail($seller_email, \Yii::$app->params['migom_name'].' - Регистрация продавца', Yii::$app->params['fromEmail'], NULL, 'seller/registration', $seller_data);

                    return $this->render('splash-reg');
                } else {
                    return $this->render('sign-up');
                }
            } else {
                return $this->render('sign-up');
            }

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

    public function actionAsk()
    {
        $json["header"] = 'Обратная связь';
        $json["body"] = $this->renderPartial('aks_form');
        echo Json::encode($json);
    }

    public function actionSendQuestion()
    {
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
        $admin_emails = Yii::$app->params['questionEmails'];
        foreach ($admin_emails as $email) {
            \app\helpers\SysService::sendEmail($email, 'Обратная связь', Yii::$app->params['fromEmail'], $text_email);
        }
        echo 'Ваше сообщение отправлено! Вам ответят в ближайшее время!';
    }
}
