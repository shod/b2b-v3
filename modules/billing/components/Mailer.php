<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\components;

use app\helpers\SysService;
use app\models\Seller;
/**
 * Description of Mailer
 *
 * @author Schemelev E.
 */
class Mailer {

    private $to;
    private $from;
    private $subject = 'seller subject';
    private $email_test;
    private $opts = [] ;

    function __construct() {
        $this->to = \Yii::$app->params['b2bEmails'];
        $this->from = \Yii::$app->params['fromEmail'];
        $this->email_test = \Yii::$app->params['testEmails'];
    }
    
    private function mail($tamplate, $params, $opts=[])
    {
        $this->opts = $opts;
        $to = $this->getParam('to');
        if(is_array($to)){
            foreach ($to as $to_item) {
                SysService::sendEmail($to_item, $this->getParam('subject'), $this->getParam('from'), NULL , 'seller/' . $tamplate, $params);
            }
            return;
        }
        SysService::sendEmail($to, $this->getParam('subject'), $this->getParam('from'), NULL , 'seller/' . $tamplate, $params);
    }
    
    
    private function getParam($name){
        return $this->opts[$name] ?? $this->$name;
    }
    
    private function getSeller($id) {
        return Seller::findOne($id);
    }

    public function balance_0($seller_id) {
        $seller = $this->getSeller($seller_id);
        $email = $this->email_test;

        $this->mail('balance_0_admin', array('seller_id' => $seller_id, 'name' => $seller->name), array('subject' => "Отключение продавца"));

        return $this->mail('balance_0', array('name' => $seller->name), array('subject' => "Migom.by Ваш аккаунт заблокирован", 'to' => $email));
    }

    public function balance_30($seller_id) {
        $seller = $this->getSeller($seller_id);
        
        $emails = $this->email_test;
        $emails[] = $seller->email;

        return $this->mail('balance_30', ['name' => $seller->name], ['subject' => "Migom.by Отключены дополнительные услуги", 'to' => $emails]);
    }

    public function balance_day_1($seller_id) {

        $seller = $this->getSeller($seller_id);
        return $this->mail('balance_day_1', ['name' => $seller->name], ['subject' => "Migom.by ”Уведомление о минимальном балансе", 'to' => $this->email_test]);
    }
    
    public function sections_admin($data) {
        $res = \app\models\BillCatalog::find()->all();
        $_data = [];
        foreach ((array) $res as $r)
            $_data[$r['id']] = $r->attributes;

        foreach ((array) $data['data_before'] as $r) {
            $id = $r['id'];
            $d = $_data[$id];
            $mode = $r['f_tarif'] ? '' : ' (просмотры)';
            if ($d['f_tarif'])
                $data['tarifs_before'][] = "{$d['name']}{$mode}";
            else
                $data['tarifs_before'][] = "{$d['name']}{$mode}";
        }
        
        $data['tarifs_before']  = join(", ", (array) $data['tarifs_before']);
        $data['sections_before'] = join(", ", (array) $data['tarifs_before']);

        foreach ((array) $data['data_after'] as $r) {
            $id = $r['id'];
            $d = $_data[$id];
            $mode = $r['f_tarif'] ? '' : ' (просмотры)';
            if ($d['f_tarif'])
                $data['tarifs'][] = "{$d['name']}{$mode}";
            else
                $data['sections'][] = "{$d['name']}{$mode}";
        }
        
        $data['tarifs'] = isset($data['tarifs']) ? join(", ", (array) $data['tarifs']) : "";
        $data['sections'] = isset($data['sections']) ? join(", ", (array) $data['sections']) : "";

        $this->mail('sections_admin', $data, ['subject' => "Изменение тарифного плана"] );
    }

    /**
     * Активация акаунта продавца
     * */
    public function activate($seller_id) {
        $seller = $this->getSeller($seller_id);
        return $this->mail('activate', array('name' => $seller->name), array('subject' => "Migom.by Ваш аккаунт активирован", 'to' => $this->email_test));
    }

}
