<?php

namespace app\controllers;

use app\helpers\SiteService;
use app\models\Seller;
use app\models\SellerInfo;
use app\models\SysObjectProperty;
use app\models_ex\Member;
use http\Url;
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
        if (Yii::$app->user->identity) {
            $this->seller_id = Yii::$app->user->identity->getId();     
        } else {
            $this->redirect('/site/login');
            return false;
        }

        if ((\Yii::$app->getUser()->isGuest)&&($action->id != 'login')&&($action->id != 'sign-up')) {
            $this->redirect('/site/login');
        } else {
            return parent::beforeAction($action);
        }
    }
    public function behaviors()
    {
        if (Yii::$app->user->identity) {
          
            $this->seller_id = Yii::$app->user->identity->getId();
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
                $seller = Seller::find()->where(['id' => $this->seller_id])->one();
                $member = Member::find()->where(['id' => $seller->member_id])->one();
                $names = SysObjectProperty::find()->where(["object_type_id" => 7])->all();
                foreach ((array) $names as $name) {
                    $value = Yii::$app->request->post($name->name);
                    if($value){
                        $member->setMemberProperty($name->name,$value,$name->id);
                    }
                }
                return $this->redirect(['settings/index']);
                break;
            case "save_user":
                $_SESSION["errormsg"] =  null;
                $name = Yii::$app->request->post("name");
                $register_date = Yii::$app->request->post("register_date");
                $description = Yii::$app->request->post("description");
                $icq = Yii::$app->request->post("icq");
                $skype = Yii::$app->request->post("skype");
                $phone_id = Yii::$app->request->post("phone_id");
                $phone = Yii::$app->request->post("phone");
                $viber = Yii::$app->request->post("viber");
                $whatsapp = Yii::$app->request->post("whatsapp");
                $telegram = Yii::$app->request->post("telegram");
                $phone_code = Yii::$app->request->post("phone_code");
                $phone_op = Yii::$app->request->post("phone_op");
                $phone_type = Yii::$app->request->post("phone_type");
                $phone_section = Yii::$app->request->post("phone_section");
                $email = Yii::$app->request->post("email");
                $site = Yii::$app->request->post("site");
                $site_alias = Yii::$app->request->post("site_alias");
                $address = Yii::$app->request->post("address");
                $work_time = Yii::$app->request->post("work_time");
                $delivery = Yii::$app->request->post("delivery");
                $f_beznal = Yii::$app->request->post("f_beznal") ? 1 : 0;
                $f_nal = Yii::$app->request->post("f_nal") ? 1 : 0;
                $f_credit = Yii::$app->request->post("f_credit") ? 1 : 0;
                $f_rassrochka = Yii::$app->request->post("f_rassrochka") ? 1 : 0;
                $f_description = Yii::$app->request->post("f_description") ? 1 : 0;
                $f_download = Yii::$app->request->post("f_allow_download") ? 1 : 0;
                $offer_default_desc = Yii::$app->request->post("offer_default_desc");
                $importers_data = array_values(array_filter(Yii::$app->request->post("importers")));

                $bit_setting = Yii::$app->request->post("bit_setting");
                $seller = Seller::find()->where(['id' => $this->seller_id])->one();
                $setting_bit = $seller->setting_bit;
                //dd(Yii::$app->request->post());
                //защита от сторонних скриптов
                $checked_items = array($description, $delivery);
                foreach ((array) $checked_items as $item)
                {					
                    if((strpos($item, 'iframe') !== false) || (strpos($item, 'script') !== false)){
						//var_dump(strpos($item, 'script'));					
                        //$_SESSION["errormsg"]="<div id='alert'><div class=' alert alert-block alert-danger fade in center'>Вы пытаетесь сохранить недопустимые данные! Обратитесь в службу технической поддержки.</div></div>";
                    }
                }

                if ($_SESSION["errormsg"]){
                    echo $_SESSION["errormsg"];
                    break;
                }
                $res = $this->get_payment_list(); 
                foreach($res as $item){
                     
                    var_dump(isset($bit_setting['bit_'.$item['code']]) ? $bit_setting['bit_'.$item['code']] : 0);
                    $setting_bit =  SiteService::set_bitvalue($setting_bit,$item['bit'],isset($bit_setting['bit_'.$item['code']]) ? $bit_setting['bit_'.$item['code']] : 0);
                }
                
                foreach ((array) $importers_data as $key=>$value)
                {
                    $value = str_replace('"', "'", $value);
                    $importers_data[$key] = $value;
                }

                $importers = addslashes(serialize($importers_data));
                $centers = array_values(array_filter(Yii::$app->request->post("service_centers")));
                foreach ((array) $centers as $key=>$value)
                {
                    $value = str_replace('"', "'", $value);
                    $centers[$key] = $value;
                }

                $service_centers = addslashes(serialize($centers));

                $phones = array(0 => array());
                foreach ((array) $phone_id as $id)
                {
                    $phone[$id] = trim(preg_replace('/[^\\d]/', '', $phone[$id]));

                    if ($phone[$id] != '')
                    {
                        if (array_key_exists($id, (array)$phone_section))
                        {
                            foreach((array)$phone_section[$id] as $cat_id)
                            {
                                if ($phone_code[$id] && $phone[$id] && $phone_op[$id]){
                                    $f_viber = isset($viber[$id]) && $viber[$id] ? 1 : 0;
                                    $f_telegram = isset($telegram[$id]) && $telegram[$id] ? 1 : 0;
                                    $f_whatsapp = isset($whatsapp[$id]) && $whatsapp[$id] ? 1 : 0;
                                    $phones[$cat_id][] = array("code" => "{$phone_code[$id]}", "phone" => "{$phone[$id]}", "op" => "{$phone_op[$id]}", "type" => "{$phone_type[$id]}", "viber" => "{$f_viber}", "telegram" => "{$f_telegram}", "whatsapp" => "{$f_whatsapp}");
                                }
                            }
                        }
                        else
                        {
                            if ($phone_code[$id] && $phone[$id] && $phone_op[$id]){
                                $f_viber = isset($viber[$id]) && $viber[$id] ? 1 : 0;
                                $f_telegram = isset($telegram[$id]) && $telegram[$id] ? 1 : 0;
                                $f_whatsapp = isset($whatsapp[$id]) && $whatsapp[$id] ? 1 : 0;
                                $phones[0][] = array("code" => "{$phone_code[$id]}", "phone" => "{$phone[$id]}", "op" => "{$phone_op[$id]}", "type" => "{$phone_type[$id]}", "viber" => "{$f_viber}", "telegram" => "{$f_telegram}", "whatsapp" => "{$f_whatsapp}");
                            }
                        }
                    }
                }

                $phones[0] = array_reduce($phones[0], function($a, $b) {
                    static $stored = array();
                    $hash = md5(serialize($b));
                    if (!in_array($hash, $stored)) {
                        $stored[] = $hash;
                        $a[] = $b;
                    }
                    return $a;
                }, array());
                $phones = serialize($phones);

                $seller->name = $name;
                $seller->description = $description;
                $seller->phone = $phones;
                $seller->email = $email;
                $seller->site = $site;
                $seller->site_alias = $site_alias;
                $seller->address = $address;
                $seller->work_time = serialize($work_time);
                $seller->delivery = $delivery;
                $seller->icq = $icq;
                $seller->f_credit = $f_credit;
                $seller->f_rassrochka = $f_rassrochka;
                $seller->f_beznal = $f_beznal;
                $seller->f_nal = $f_nal;
                $seller->skype = $skype;
                $seller->register_date = $register_date;
                $seller->setting_bit = $setting_bit;
                $seller->save();

                $seller_info = SellerInfo::find()->where(['seller_id' => $this->seller_id])->one();
                if($seller_info){
                    \Yii::$app->db->createCommand("update seller_info set offer_default_desc=f_clear_prod_desc('{$offer_default_desc}'), importers='{$importers}', service_centers='{$service_centers}', f_b2b_description={$f_description}, f_allow_download={$f_download} where seller_id=".$this->seller_id)->execute();
                }

                $fl_logo_exist = $this->checkRemoteFile(\Yii::$app->params['STATIC_URL_FULL'] . "/img/seller/logo$" . $this->seller_id . ".jpg");
                $bit_logoauto = $seller->setting_bit & 131072;
                $fl_auto_logo = (($fl_logo_exist && $bit_logoauto) || !$fl_logo_exist);
                if(isset($_FILES["logo"])){
                    $logo = $_FILES["logo"];
                    if ($logo["tmp_name"] != "none" && $logo["name"] != '')
                    {
                        foreach (glob("seller/*\${$this->seller_id}.jpg") as $filename)
                        {
                            unlink($filename);
                        }

                        $filename = "seller/logo\${$this->seller_id}.jpg";

                        if($_FILES['logo']['type'] == "image/jpeg") {
                            if (move_uploaded_file($logo["tmp_name"], $filename)) {								
                                SiteService::resize($filename, array(90, 35));
                                $filename = 'logo$'.$this->seller_id.'.jpg';
                                $home = \yii\helpers\Url::base(true);
                                $path_local = "{$home}/seller/{$filename}";
                                $path = \Yii::$app->params['STATIC_URL_FULL'] . "/img_upload.php?act=add_logo_seller&fname={$filename}&url=".$path_local;
								
                                file_get_contents($path, NULL, NULL, 0, 14);
                                $setting_bit = SiteService::set_bitvalue($setting_bit,131072,0);
                                $seller->setting_bit = $setting_bit;
                                $seller->save();

                            }
                        } else {exit("Не верный формат загружаемого файла. Файл должен быть в формате JPG");}
                    } elseif($fl_auto_logo) {
                        foreach (glob("seller/*\${$this->seller_id}.jpg") as $filename)
                        {
                            unlink($filename);
                        }
                        $filename = "seller/logo\${$this->seller_id}.jpg";


                        // Create the image
                        $im = imagecreatetruecolor(90, 35);

                        // Create some colors
                        $white = imagecolorallocate($im, 255, 255, 255);
                        $grey = imagecolorallocate($im, 56, 56, 56);
                        $black = imagecolorallocate($im, 0, 0, 0);
                        imagefilledrectangle($im, 0, 0, 90, 35, $white);

                        // Replace path by your own font path
                        $font = 'css/fonts/aavantenr_book.ttf';

                        $text = iconv("windows-1251", "utf-8", $name);
                        // Add the text
                        $n =  preg_match('/[А-Яа-яЁё]/u', $name) ? 6 : 1;
                        $l = strlen($name);

                        $fontsize = ($l < 5) ? 22 : ((110 + $l*$n) / $l);
                        $x = ($l < 5) ? 20  : 5;
                        imagettftext($im, $fontsize, 0, $x, 25, $grey, $font, $text);

                        // Using imagepng() results in clearer text compared with imagejpeg()
                        imagejpeg($im, $filename);
                        imagedestroy($im);

                        $filename = 'logo$'.$this->seller_id.'.jpg';
                        $home = \yii\helpers\Url::base(true);
                        $path_local = "{$home}/seller/{$filename}";
                        $path = \Yii::$app->params['STATIC_URL_FULL'] . "/img_upload.php?act=add_logo_seller&fname={$filename}&url=".$path_local;

                        file_get_contents($path, NULL, NULL, 0, 14);
                        $setting_bit = SiteService::set_bitvalue($setting_bit,131072,1);
                        $seller->setting_bit = $setting_bit;
                        $seller->save();
                    }
                }

                return $this->redirect(['settings/user-info']);
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
                                mkdir($dir_sel_doc, 0775);
                                chmod($dir_sel_doc, 0775);
                            }

                            if(move_uploaded_file($_FILES["img"]["tmp_name"][$i], $new_file)) {
                                $src[] = '/seller/registration/'.$this->seller_id.'/'.$new_file_name;
                                $file_name[] = $new_file_name;

                                $home = \yii\helpers\Url::base(true);
                                $path_local = $home . '/seller/registration/'.$this->seller_id.'/'.$new_file_name;
                                $path = \Yii::$app->params['STATIC_URL_FULL'] . "/img_upload.php?act=add_registration_seller&fname={$new_file_name}&url={$path_local}&sid={$this->seller_id}";								
                                $res = file_get_contents($path, NULL, NULL, 0, 14);
								
								$status = ($res == 'false') ? false : true;								
								if($status){
									$status = (strpos($res,'ERROR') === false) ? true : false;								
								}
								
								if($status){
									//$this->data_img_registration();
									$text[] = 'Файл: '.$new_file_name.' загружен.<br>';									
								}else{
									$text[] = 'Файл: '.$new_file_name.' не загружен на сервер.<br>';																		
								}
								
                            }else{
								$status = 0;
								$text[] = 'Файл: '.$new_file_name.' не загружен на сервер.<br>';								
								$file_name = "";
								$path_local = "";
							}							
                        } else {
							$status = 0;
                            $src[] = $i;
                            $text[] = "ОШИБКА в при загрузке файла  ".$new_file_name." !!! Не верный формат загружаемого файла или слишком большой общий размер файлов.<br>";											
							$file_name = "";
							$path_local = "";
                        }
                    }
                }
				
                $this->data_img_registration();

                echo json_encode(array('status'=>$status,'text'=>$text,'file_name'=>$file_name,'src'=>$src, 'path_local' => $path_local));
                exit;
                break;
            case "del_img_registration":
                $success = false;
                $file_name = Yii::$app->request->get("file_name");
                if(file_exists('seller/registration/'.$this->seller_id.'/'.$file_name) && unlink('seller/registration/'.$this->seller_id.'/'.$file_name)) {
                    $success = true;
                    $this->data_img_registration();
                }
                echo $path = \Yii::$app->params['STATIC_URL_FULL'] . "/img_delete.php?seller_registration=1&fname={$file_name}&sid={$this->seller_id}";		
                $res = file_get_contents($path, NULL, NULL, 0, 14);
				$success = ($res == 'false') ? false : true;
                echo json_encode(array('success'=>$success));
                exit;
                break;

            case "add_img_document":
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

                            $dir_sel_doc = 'seller/document/'.$this->seller_id.'/';
                            $new_file = $dir_sel_doc.'/'.$new_file_name;
                            if(!is_dir($dir_sel_doc)) {
                                mkdir($dir_sel_doc, 0775);
                                chmod($dir_sel_doc, 0775);
                            }

                            if(move_uploaded_file($_FILES["img"]["tmp_name"][$i], $new_file)) {
                                $src[] = '/seller/document/'.$this->seller_id.'/'.$new_file_name;
                                $home = \yii\helpers\Url::base(true);
                                $path_local = $home . '/seller/document/'.$this->seller_id.'/'.$new_file_name;
                                $path = \Yii::$app->params['STATIC_URL_FULL'] . "/img_upload.php?act=add_document_seller&fname={$new_file_name}&url={$path_local}&sid={$this->seller_id}";
                                file_get_contents($path, NULL, NULL, 0, 14);
                                $file_name[] = $new_file_name;
								//print_r($path);							
								//die('settingController');
                            }
                            $text[] = 'Файл: '.$new_file_name.' загружен.<br>';								
                        } else {
							$status = 0;
                            $src[] = $i;
                            $text[] = "ОШИБКА в при загрузке файла  ".$new_file_name." !!! Не верный формат загружаемого файла или слишком большой общий размер файлов.<br>";
                        }
                    }
                }
                $this->data_img_document();

                echo json_encode(array('status'=>$status,'text'=>$text,'file_name'=>$file_name,'src'=>$src, 'path_local' => $path_local));
                exit;
                break;

            case "del_img_document":
                $success = true;
                $file_name = Yii::$app->request->get("file_name");
                if(file_exists('seller/document/'.$this->seller_id.'/'.$file_name) && unlink('seller/document/'.$this->seller_id.'/'.$file_name)) {
                    $success = true;
                    $this->data_img_document();
                }
                $path = \Yii::$app->params['STATIC_URL_FULL'] . "/img_delete.php?seller_document=1&fname={$file_name}&sid={$this->seller_id}";
                file_get_contents($path, NULL, NULL, 0, 14);
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
		$member_data['company_name'] = stripcslashes($member_data['company_name']);

        $res = \Yii::$app->db->createCommand("select f_registration from seller_info where seller_id = {$this->seller_id}")->queryOne();
        $none = $res['f_registration'] ? "style='display:none'" : "";
        $img_registration = $this->get_img_registration($none);
        return $this->render('index', array_merge($member_data, ['img_registration' => $img_registration]));
    }

    public function actionUserInfo(){
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();
        $seller_info = SellerInfo::find()->where(['seller_id' => $this->seller_id])->one();
        $work_time = $this->work_time_html($seller->work_time);
        $phones = $this->get_phones($seller->phone, $seller->pay_type);
        $res = $this->get_payment_list();
        foreach($res as $item){
            $vars['bit_'.$item['code']] = $item['f_check'] ? "checked" : "";
        }
        $vars["cont_importers"] = $this->deserilize('importers',$seller_info->importers);
        $vars["cont_service_centers"] = $this->deserilize('service_centers',$seller_info->service_centers);
        $vars['img_documents'] = $this->get_img_documents();

        $logo_url = \Yii::$app->params['STATIC_URL_FULL'] .  "/img/seller/logo$" . $this->seller_id . ".jpg";
        $fl_logo_exist = $this->checkRemoteFile($logo_url);

        $version_time = time();
        $vars["logo"] = $fl_logo_exist ? "<img src='$logo_url?v={$version_time}' border=0 title='{$seller->name}' alt='{$seller->name}'><br>" : "";

        $vars['bit_logoauto'] = ($seller->setting_bit & 131072) ? "checked" : "";
        if($fl_logo_exist && ($vars['bit_logoauto'] == "")) {
            $vars['dis_logo'] = "readonly disabled";
            $email = Yii::$app->params['adminEmail'];
            $vars['dis_logo_text'] = "<p>Для замены логотипа пришлите запрос на смену и новый логотип на <a href='mailto:{$email}'>{$email}</a> с указанием ID магазина</p>";
        }
        if(Yii::$app->request->get('is_admin') && Yii::$app->request->get('is_admin') == 1){
            $vars['dis_logo'] = "";
        }

        return $this->render('user_info', array_merge($vars, ['seller' => $seller, 'work_time' => $work_time, 'phones' => $phones, 'seller_info' => $seller_info]));
    }

    private function data_img_registration() {
        $documents_data = file_get_contents(\Yii::$app->params['STATIC_URL_FULL'] . "/img_info.php?act=seller_registration&seller_id={$this->seller_id}");
        $documents_data = json_decode($documents_data);		
        \Yii::$app->db->createCommand("update seller_info set img_registration = '".json_encode($documents_data)."', f_registration = 0 where seller_id={$this->seller_id}")->execute();
	}

    private function data_img_document() {
        $documents_data = file_get_contents(\Yii::$app->params['STATIC_URL_FULL'] . "/img_info.php?act=seller_document&seller_id={$this->seller_id}");
        $documents_data = json_decode($documents_data);
        \Yii::$app->db->createCommand("update seller_info set img_documents = '".json_encode($documents_data)."' where seller_id={$this->seller_id}")->execute();
    }

    private function get_img_registration($none = 0) {
        $arrContextOptions = array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        ); 
        $documents_data = file_get_contents(\Yii::$app->params['STATIC_URL_FULL'] . "/img_info.php?act=seller_registration&seller_id={$this->seller_id}", false, stream_context_create($arrContextOptions));
        $imgs = json_decode($documents_data);
        $data = "";
        if(count((array)$imgs)){
            foreach($imgs as $file) {
                $r['file_name'] = $file;
                $r['src'] = \Yii::$app->params['STATIC_URL_FULL'] . '/img/seller/registration/'.$this->seller_id.'/'.$file;
                $r['none'] = $none;
                $data .= $this->renderPartial("tmpl/img_registration", ['vars' => $r]);
            }
        }

        return $data;
    }

    function get_img_documents() {
        $arrContextOptions = array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        ); 
        $documents_data = file_get_contents(\Yii::$app->params['STATIC_URL_FULL'] . "/img_info.php?act=seller_document&seller_id={$this->seller_id}", false, stream_context_create($arrContextOptions));
        $imgs = json_decode($documents_data);
        $data = "";
        if(count((array)$imgs)){
            foreach($imgs as $file) {
                $r['file_name'] = $file;
                $r['src'] = \Yii::$app->params['STATIC_URL_FULL'] . '/img/seller/document/'.$this->seller_id.'/'.$file;
                $data .= $this->renderPartial("tmpl/img_document", $r);
            }
        }

        return $data;
    }

    private function work_time_html($wt)
    {
        $html = "";
        $days = array(1 => "Понедельник", 2 => "Вторник", 3 => "Среда", 4 => "Четверг", 5 => "Пятница", 6 => "Суббота", 0 => "Воскресенье");
        $time = array("07:00", "07:30" , "08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00", "22:30", "23:00", "23:30");
        $wt = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        },$wt );
        $wt = unserialize($wt);
        foreach ((array) $days as $d => $d_str)
        {
            $options = [0=>'', 1=> ''];
            foreach (array(0, 1) as $i)
            {
                $options[$i] .= "<option></option>";
                foreach ((array) $time as $t)
                {
                    $selected = ($wt[$d][$i] == $t) ? "selected" : "";
                    $options[$i] .= "<option value=\"{$t}\" {$selected}>{$t}</option>";
                }
            }
            $html .= $this->renderPartial("tmpl/work_time", ['vars' => array(
                "d" => $d, "d_str" => $d_str, "options0" => $options[0], "options1" => $options[1]
            )]);
        }
        return $html;
    }

    private function get_phones($phone, $pay_type)
    {
        $html = "";
        $phones = unserialize($phone);

        $type_op = array('velcom'=>'A1','a1'=>'A1','life'=>'Life:)','mts'=>'МТС','diallog'=>'Diallog','btk'=>'Городской',);

        if (($phones === false) || !is_array($phones))
        {
            return $this->get_phones_old($phone);
        }
        else
        {
            $cnt = 0;

            if ($pay_type == 'fixed'){
                $res = \Yii::$app->db->createCommand("
                    select c1.id as id, c1.name as name, c2.id as owner_id, c2.name as owner_name
                    from bill_catalog_seller sel
                    inner join bill_catalog_section sec on (sec.catalog_id=sel.catalog_id)
                    inner join catalog_subject cs on (cs.subject_id=sec.section_id)
                    inner join catalog c1 on (c1.id=cs.catalog_id and c1.hidden=0)
                    inner join catalog c2 on (c2.id=c1.owner_id and c2.hidden=0)
                    where sel.seller_id={$this->seller_id}
                    group by c2.id, c1.id
                    order by c2.position, c1.position
                ")->queryAll();
            } else {
                $res = \Yii::$app->db->createCommand("
                   SELECT DISTINCT vb.catalog_id as id, vb.`name`, vb.owner_id, c2.name as owner_name
                    FROM v_catalog_sections AS vb
                    LEFT JOIN (
                        SELECT
                            catalog_id
                        FROM
                            bill_click_catalog_blacklist AS bcl
                        WHERE
                            seller_id = {$this->seller_id}
                    ) AS qoff ON (qoff.catalog_id = vb.catalog_id)
                    left JOIN catalog c2 ON (c2.id = vb.owner_id AND c2.hidden = 0)
                    join product_seller as ps on (ps.seller_id = {$this->seller_id} and ps.prod_sec_id = vb.section_id)
                    WHERE vb.hidden = 0 AND qoff.catalog_id IS NULL AND NOT EXISTS (
                        SELECT
                            1
                        FROM
                            bill_click_catalog_blacklist AS bcl
                        WHERE
                            seller_id = 0
                        AND vb.catalog_id = bcl.catalog_id
                    )
                    ORDER BY c2.position, vb.name
                   ")->queryAll();
            }

            foreach ((array) $phones[0] as $i => $phone)
            {
                $owner_id = 0;
                $options = "";

                foreach ((array) $res as $r)
                {
                    if ($r["owner_id"] <> $owner_id)
                    {
                        $owner_id = $r["owner_id"];
                        $options .= "<option value=\"{$owner_id}\"><b>{$r["owner_name"]}</b></option>";
                    }
                    $options .= "<option value=\"{$r["id"]}\">&nbsp;&nbsp;&nbsp;{$r["name"]}</option>";
                }

                if(!empty($phone["op"])) {
                    $hidden ='displayNone';
                    $text_op = $type_op[$phone["op"]];
                } else {
                    $text_op = '';
                }
                $checked_viber = isset($phone["viber"]) && $phone["viber"] ? "checked" : "";
                $checked_telegram = isset($phone["telegram"]) && $phone["telegram"] ? "checked" : "";
                $checked_whatsapp = isset($phone["whatsapp"]) && $phone["whatsapp"] ? "checked" : "";

                $html .= $this->renderPartial("tmpl/phone", array(
                    "id" => $i,
                    "phone" => $phone["phone"],
                    "phone_code" => $phone["code"],
                    "selected_{$phone["op"]}" => "selected",
                    "selected_{$phone["code"]}" => "selected",
                    "selected_{$phone["type"]}" => "selected",
                    "section_options" => $options,
                    "checked_viber" => $checked_viber,
                    "checked_telegram" => $checked_telegram,
                    "checked_whatsapp" => $checked_whatsapp,
                    'text_op'=>$text_op,
                ));
                $cnt++;
            }

            $res1 = \Yii::$app->db->createCommand("select id from catalog where hidden=0 order by owner_id, position")->queryAll();
            foreach ((array) $res1 as $r)
            {
                $cat_id = $r["id"];
                if (array_key_exists($cat_id, $phones))
                {
                    foreach ((array) $phones[$cat_id] as $i => $phone)
                    {
                        $owner_id = 0;
                        $options = "";
                        foreach ((array) $res as $r)
                        {
                            if ($r["owner_id"] <> $owner_id)
                            {
                                $owner_id = $r["owner_id"];
                                $selected = $this->get_phones_catalog_selected($owner_id, $phone, $phones);
                                $options .= "<option value=\"{$owner_id}\"{$selected}><b>{$r["owner_name"]}</b></option>";
                            }

                            $selected = $this->get_phones_catalog_selected($r["id"], $phone, $phones);
                            $options .= "<option value=\"{$r["id"]}\"{$selected}>&nbsp;&nbsp;&nbsp;{$r["name"]}</option>";
                        }
                        $checked_viber = $phone["viber"] ? "checked" : "";
                        $html .= $this->renderPartial("tmpl/phone", [
                            "id" => "{$cat_id}_{$i}",
                            "phone" => $phone["phone"],
                            "phone_code" => $phone["code"],
                            "selected_{$phone["code"]}" => "selected",
                            "selected_{$phone["op"]}" => "selected",
                            "selected_{$phone["type"]}" => "selected",
                            "checked_viber" => $checked_viber,
                            "section_options" => $options
                        ]);
                        $cnt++;
                    }
                }
            }

            $owner_id = 0;
            $options = "";
            foreach ((array) $res as $r)
            {
                if ($r["owner_id"] <> $owner_id)
                {
                    $owner_id = $r["owner_id"];
                    $options .= "<option value=\"{$owner_id}\"><b>{$r["owner_name"]}</b></option>";
                }
                $options .= "<option value=\"{$r["id"]}\">&nbsp;&nbsp;&nbsp;{$r["name"]}</option>";
            }

            for ($i = $cnt; $i < 4; $i++)
            {
                $html .= $this->renderPartial("tmpl/phone", array("id" => "_{$i}", "section_options" => $options, 'tmpl_id'=>'id="tmpl_id"'));
            }

            //tmpl
            $html .= $this->renderPartial("tmpl/phone", array("id" => "{{id}}", "section_options" => $options, 'tmpl_id'=>'id="tmpl_id"','style'=>'style="display:none"'));
        }

        return $html;
    }

    private function get_phones_catalog_selected($catalog_id, $phone, &$phones1)
    {
        $res = false;
        if (array_key_exists($catalog_id, $phones1))
        {
            $phones = $phones1[$catalog_id];
            foreach ((array) $phones as $i=>$p)
            {
                if (($p["code"] == $phone["code"]) && ($p["phone"] == $phone["phone"]) && ($p["op"] == $phone["op"]))
                {
                    $res = true;
                    unset($phones1[$catalog_id][$i]);
                    break;
                }
            }
        }
        return $res ? " selected" : "";
    }

    private function get_phones_old($phone)
    {
        $html = "";
        $res = \Yii::$app->db->createCommand("
            select c1.id as id, c1.name as name, c2.id as owner_id, c2.name as owner_name
            from bill_catalog_seller sel
            inner join bill_catalog_section sec on (sec.catalog_id=sel.catalog_id)
            inner join catalog_subject cs on (cs.subject_id=sec.section_id)
            inner join catalog c1 on (c1.id=cs.catalog_id and c1.hidden=0)
            inner join catalog c2 on (c2.id=c1.owner_id and c2.hidden=0)
            where sel.seller_id={$this->seller_id}
            group by c2.id, c1.id
            order by c2.position, c1.position
        ")->queryAll();
        $owner_id = 0;
        $options = "";
        foreach ((array) $res as $r)
        {
            if ($r["owner_id"] <> $owner_id)
            {
                $owner_id = $r["owner_id"];
                $options .= "<option value=\"{$owner_id}\"><b>{$r["owner_name"]}</b></option>";
            }
            $options .= "<option value=\"{$r["id"]}\">&nbsp;&nbsp;&nbsp;{$r["name"]}</option>";
        }

        $phone = explode(";", $phone);
        for ($i = 0; $i < 4; $i++)
        {
            $vars = array();
            $p = ($i < count($phone)) ? $phone[$i] : "";
            if (preg_match('/^8-\\((\\d+)\\)-([^\\s]+)/', $p, $res))
            {
                if (preg_match('/\\(([^\\s]+)\\)$/', $p, $res1))
                    $vars["selected_{$res1[1]}"] = "selected";
                $vars["phone_code"] = $res[1];
                $vars["phone"] = $res[2];
            }
            else
            {
                $vars["phone"] = $p;
            }
            $vars["section_options"] = $options;
            $vars["id"] = $i;
            $html .= $this->renderPartial("tmpl/phone", $vars);
        }

        return $html;
    }

    function get_payment_list(){

        $res = \Yii::$app->db->createCommand("select sd.bit, sd.description, sd.`code`, s.payment_mode_bit, IF(s.setting_bit & sd.bit>0,1,0) as f_check
			from sys_doc
			INNER JOIN sys_docstatus as sd on (sd.sysdoc_id = sys_doc.id)
			INNER JOIN seller as s on (s.id = {$this->seller_id})
			where sys_doc.`code` = 'seller'")->queryAll();

        return $res;
    }

    function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch)!==FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function deserilize($type_field,$data) {
        $data_array = "";
        $data = preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        },$data );
        $data = unserialize($data);
        //print_r($data);
        if(count((array)$data) > 0 && $data !== false) {
            foreach($data as $item) {
                $data_array .= '<input class="form-control" type="text" name="'.$type_field.'[]" value="'.$item.'" /><br>';
            }
        } else {
            $data_array = '<input class="form-control" type="text" name="'.$type_field.'[]" /><br>';
        }
        return $data_array;
    }


}
