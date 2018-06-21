<?php

namespace app\helpers;

class SysService {

    /**
     * Для обратного звонка
     */
    const CALL_ME_REQUEST = 'call_me_request';

    /**
     * добавление списка товаров в сравнение
     */
    const ADD_COMPARE = 'add_compare';

    /**
     * Отправка писем
     * @param array ['type'=>'seller', 'action'=>'po_sms', 'order_id'=>$order_id]
     */
    const SEND_MAIL = 'mail_send';

    /**
     * Отправка нотификаций
     *
     */
    const NOTIFIED = 'notified';

    /**
     * лайк на отзывы
     * @param array ['id' => int, 'field' => str]
     */
    const PRODUCT_REVIEW_VOICES = 'product_review_voices';

    /**
     * добавить отзыв на товар
     */
    const PRODUCT_REVIEW_ADD = 'product_review_add';

    /**
     * индекс для картинок
     */
    const PRODUCT_IMAGE_INDEX = 'product_image_index';

    /**
     * отзыв на продавца
     */
    const SELLER_ADD_REVIEW = 'seller_add_review';

    /**
     * отзыв на продавца
     */
    const PRODUCT_OWNER_UPD = 'product_owner_upd';


    /**
     * индексы на список товаров на каталоги и рецепты
     */
    const CATALOG_PRODUCT_LIST = 'catalog_product_list';

    /**
     * F nfv
     * @param type $name
     * @return type
     */
    const INDEX_PRODUCT_COMPARE = 'index_product_compare';

    const MODEL_INSERT = 'insert';

    private static $data;

    public static function get($name){
        if(isset(self::$data[$name])){
            return self::$data[$name];
        }
        $res = \Yii::$app->db->createCommand("SELECT value FROM `sys_status` WHERE `name` = '{$name}' LIMIT 1")->queryOne();
        self::$data[$name] = $res['value'];
        return $res['value'];
    }

    public static function set($name, $value){
        \Yii::$app->db->createCommand("call prc_sys_status_insert('{$name}','{$value}');")->execute();
    }

    /*
     * Добавление событие в базу обработки событий системы
     * $key - Событие
     * $value - Значение
     */
    public static function EventAdd($key, array $value) {

        $str_value = json_encode($value, JSON_UNESCAPED_UNICODE);
        \Yii::$app->db_event->createCommand("call ps_sys_events_add('{$key}','{$str_value}');")->execute();
    }

    public static function EventAddOne($key, $value) {
        \Yii::$app->db_event->createCommand("call ps_sys_events_add('{$key}','{$value}');")->execute();
    }

    /**
     * Инсерт через ивент
     * @param object $model
     */
    public static function eventForInsert($model, $class) {
        $value = $model->getAttributes();
        foreach ($value as &$item){
            $item = addslashes($item);
            $item = nl2br($item);
            $item = str_replace(array("\r","\n")," ",$item);
        }
        $data = ['class' => addslashes( $class ), 'attributes' => $value];
        self::EventAdd(self::MODEL_INSERT, $data);
    }

    /**
     *
     * @param type $email
     * @param type $text
     * @param type $tamplate
     * @param type $params
     */
    public static function sendEmail($email, $subject, $from = 'noreply@migom.by', $text = false, $tamplate = 'simple', $params = []) {
        $arr_mail = (array)$params;
        if(is_array($email)){
            $email = implode(',', $email);
        }
        $arr_mail['email'] = $email;
        $arr_mail['tmpl'] = $tamplate;
        $arr_mail['from'] = $from;
        $arr_mail['subject'] = $subject;
        if($text){
            $arr_mail['text'] = $text;
        }
        foreach ($arr_mail as &$item){
            $item = nl2br($item);
            $item = str_replace(array("\r","\n")," ",$item);
        }
        self::EventAdd(self::SEND_MAIL, $arr_mail); ;
    }
}
