<?php

namespace app\controllers;

use app\models\Seller;
use app\models_ex\Member;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SettingsController extends Controller
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

    public function actionProcess(){
        $action = Yii::$app->request->post("action");
        $action = isset($action) ? $action : Yii::$app->request->get("action");
        switch ($action) {
            case "save":
                break;
            case "add_img_registration":
                $status = 1;
                $f_check = false;
                set_time_limit(0);
                $type_permissible = array('image/jpeg','image/gif','image/png');
                $size_permissible = 1024 * 1024 * 10;
                if ($_FILES["img"]) {
                    for($i=0;$i<count($_FILES['img']['name']);$i++) {
                        $p_mane = explode('.',$_FILES['img']['name'][$i]);
                        $exp = $p_mane[count($p_mane)-1];

                        $new_file_name = substr(md5($_FILES['img']['name'][$i]),0,5).'.'.$exp;
                        if(in_array($_FILES['img']['type'][$i],$type_permissible) && $_FILES['img']['size'][$i] <= $size_permissible) {

                            $dir_sel_doc = 'seller/registration/'.$this->seller_id.'/';
                            $new_file = $dir_sel_doc.'/'.$new_file_name;

                            if(!is_dir($dir_sel_doc)) {
                                mkdir($dir_sel_doc, 0777);
                                chmod($dir_sel_doc, 0777);
                            }

                            if(move_uploaded_file($_FILES["img"]["tmp_name"][$i], $new_file)) {
                                $src[] = 'seller/registration/'.$this->seller_id.'/'.$new_file_name;
                                $file_name[] = $new_file_name;
                            }

                            $text[] = 'Файл: '.$new_file_name.' загружен.<br>';
                        } else {
                            $src[] = $i;
                            $text[] = "ОШИБКА в при загрузке файла  ".$new_file_name." !!! Не верный формат загружаемого файла или слишком большой общий размер файлов.<br>";
                        }
                    }
                }
                $this->data_img_registration($file_name);

                echo json_encode(array('status'=>$status,'text'=>$text,'file_name'=>$file_name,'src'=>$src));
                exit;
                break;
            case "del_img_registration":
                $success = false;
                $file_name = Yii::$app->request->get("file_name");
                if(unlink('seller/registration/'.$this->seller_id.'/'.$file_name)) {
                    $success = true;
                    $this->data_img_registration();
                }
                echo json_encode(array('success'=>$success));
                exit;
                break;
        }
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $member = Member::find()->where(['id' => $seller->member_id])->one();
        $member_data = $member->getMemberProperties();
        $img_registration = $this->get_img_registration();
        return $this->render('index', array_merge($member_data, ['img_registration' => $img_registration]));
    }

    private function data_img_registration() {
		$documents_data = scandir('seller/registration/'.$this->seller_id.'/');
		unset($documents_data[0],$documents_data[1]);
        \Yii::$app->db->createCommand("update seller_info set img_registration = '".json_encode($documents_data)."', f_registration = 0 where seller_id={$this->seller_id}")->execute();
	}

    private function get_img_registration($none = 0) {

        $dir = 'seller/registration/'.$this->seller_id.'/';
        $data = "";
        if(is_dir($dir)) {
            $documents_path = scandir('seller/registration/'.$this->seller_id.'/');

            foreach($documents_path as $file) {
                if($file != "." && $file != "..") {

                    $r['file_name'] = $file;
                    $r['src'] = 'seller/registration/'.$this->seller_id.'/'.$file;
                    $r['none'] = $none;
                    $data .= $this->renderPartial("tmpl/img_registration", ['vars' => $r]);
                }
            }
        }
        return $data;
    }


}
