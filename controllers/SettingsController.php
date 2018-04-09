<?php

namespace app\controllers;

use app\models\Seller;
use app\models\SellerInfo;
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
                return $this->redirect(['settings/index']);
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
                                $file_name[] = $new_file_name;
                            }

                            $text[] = iconv('windows-1251','utf-8','Файл: '.$new_file_name.' загружен.<br>');
                        } else {
                            $src[] = $i;
                            $text[] = iconv('windows-1251','utf-8',"ОШИБКА в при загрузке файла  ".$new_file_name." !!! Не верный формат загружаемого файла или слишком большой общий размер файлов.<br>");
                        }
                    }
                }
                $this->data_img_document($file_name);

                echo json_encode(array('status'=>$status,'text'=>$text,'file_name'=>$file_name,'src'=>$src));
                exit;
                break;

            case "del_img_document":
                $success = false;
                $file_name = Yii::$app->request->get("file_name");
                if(unlink('seller/document/'.$this->seller_id.'/'.$file_name)) {
                    $success = true;
                    $this->data_img_document();
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

        $logo_url = "http://static.migom.by/img/seller/logo$" . $this->seller_id . ".jpg";
        $vars["logo"] = "<img src='$logo_url' border=0 title='{$seller->name}' alt='{$seller->name}'><br>";;
        $fl_logo_exist = $this->checkRemoteFile($logo_url);
        $vars['bit_logoauto'] = ($seller->setting_bit & 131072) ? "checked" : "";
        if($fl_logo_exist && ($vars['bit_logoauto'] == "")) {
            $vars['dis_logo'] = "readonly disabled";
            $vars['dis_logo_text'] = "<p>Для замены логотипа пришлите запрос на смену и новый логотип на <a href='mailto:admin@migom.by'>admin@migom.by</a> с указанием ID магазина</p>";
        }

        return $this->render('user_info', array_merge($vars, ['seller' => $seller, 'work_time' => $work_time, 'phones' => $phones, 'seller_info' => $seller_info]));
    }

    private function data_img_registration() {
		$documents_data = scandir('seller/registration/'.$this->seller_id.'/');
		unset($documents_data[0],$documents_data[1]);
        \Yii::$app->db->createCommand("update seller_info set img_registration = '".json_encode($documents_data)."', f_registration = 0 where seller_id={$this->seller_id}")->execute();
	}

    private function data_img_document() {
        $documents_data = scandir('seller/document/'.$this->seller_id.'/');
        unset($documents_data[0],$documents_data[1]);
        \Yii::$app->db->createCommand("update seller_info set img_documents = '".json_encode($documents_data)."' where seller_id={$this->seller_id}")->execute();
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

    private function work_time_html($wt)
    {
        $html = "";
        $days = array(1 => "Понедельник", 2 => "Вторник", 3 => "Среда", 4 => "Четверг", 5 => "Пятница", 6 => "Суббота", 0 => "Воскресенье");
        $time = array("07:00", "07:30" , "08:00", "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00", "22:30", "23:00", "23:30");
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

        $type_op = array('velcom'=>'Velcom','life'=>'Life:)','mts'=>'МТС','diallog'=>'Diallog','btk'=>'Городской',);

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
                $checked_viber = $phone["viber"] ? "checked" : "";
                $checked_telegram = $phone["telegram"] ? "checked" : "";
                $checked_whatsapp = $phone["whatsapp"] ? "checked" : "";
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
        $data = unserialize($data);
        //print_r($data);
        if(count($data) > 0 && $data !== false) {
            foreach($data as $item) {
                $data_array .= '<input class="form-control" type="text" name="'.$type_field.'[]" value="'.$item.'" /><br>';
            }
        } else {
            $data_array = '<input class="form-control" type="text" name="'.$type_field.'[]" /><br>';
        }
        return $data_array;
    }

    function get_img_documents() {

        $data = "";
        $dir = 'seller/document/'.$this->seller_id.'/';
        if(is_dir($dir)) {
            $documents_path = scandir('seller/document/'.$this->seller_id.'/');

            foreach($documents_path as $file) {
                if($file != "." && $file != "..") {
                    $r['file_name'] = $file;
                    $r['src'] = '/seller/document/'.$this->seller_id.'/'.$file;
                    $data .= $this->renderPartial("tmpl/img_document", $r);
                }
            }
        }
        return $data;
    }


}
