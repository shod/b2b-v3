<?php

namespace app\modules\billing\components;

use \app\modules\billing\components\Mailer;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class Transaction implements ITransaction {

    protected $billing;
    protected $object_id = 0;
    protected $value = 0;
    private $owner_id;
    private $account_id;
    protected $type;
    private $error = '';
    private $bill_transaction;
    protected $balance_before;
    
    CONST TYPE_FIXED = 'fixed';

    public function __construct( Billing $billing, $owner_id = 0) {
        $this->owner_id = $owner_id;

        $this->billing = $billing;
        $this->account_id = $billing->getAccount()->id;
        $this->balance_before = $billing->getAccount()->balance;
        $this->type = $this->type();
    }
    
    /*
    protected function getAccount(){
        return $this->billing->getAccount();
    }*/

    protected function setAccountId($id) {
        $this->account_id = $id;
    }

    public function type() {
        if($this->type){
            return $this->type;
        }
        $class = get_class($this);
        $arr = explode('\\', strtolower($class));
        return end($arr);
    }
    
    protected function getBalance() {
        return $this->billing->getAccount()->balance;
    }
    
    protected function getBonusBalance() {
        return $this->billing->getBonusAccount()->balance;
    }

    private function begin() {

        $this->bill_transaction = new \app\models\BillTransaction();
        $this->bill_transaction->type = $this->type;
        $this->bill_transaction->user_id = 0;
        $this->bill_transaction->value = 0;
        $this->bill_transaction->object_id = 0;
        $this->bill_transaction->balance_before = $this->balance_before;
        $this->bill_transaction->account_id = $this->account_id;
        $this->bill_transaction->date_begin = date('Y-m-d H:i:s');
        $this->bill_transaction->owner_id = $this->owner_id;
        if(!$this->bill_transaction->save()){
            throw new TransactionException(print_r($this->bill_transaction->getErrors(), 1));
        }
        
    }

    private function end() {
        if (abs(round($this->value, 5)) > 0 || in_array($this->type, array('activate', 'deactivate', 'activate_b2b',
                'deactivate_b2b', 'section_group', 'section_activate', 'section_deactivate',
                'section_mode_tarif', 'section_mode_views', 'down_click', 'down_click_move', 'up_click'))) {
            $this->bill_transaction->date_end = date('Y-m-d H:i:s');
            $this->bill_transaction->balance_before = $this->balance_before;
            $this->bill_transaction->account_id = $this->account_id;
            $this->bill_transaction->value = $this->value;
            $this->bill_transaction->object_id = $this->object_id;
            if(!$this->bill_transaction->save()){
                throw new TransactionException(print_r($this->bill_transaction->getErrors(), 1));
            }
        }
    }

    private function abort($error) {
        $this->error = $error;
        $this->bill_transaction->error = $error;
        if(!$this->bill_transaction->save()){
            throw new TransactionException(print_r($this->bill_transaction->getErrors(), 1));
        }
    }

    public function process($data = null) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->begin();
            $this->beginNotify();
            $this->_process($data);
            $this->end();
            $this->endNotify();
            
            $transaction->commit();
            
        } catch (TransactionException $e) {
            $transaction->rollBack();
            dd($e->getMessage());
            $this->abort($e->getMessage());
            return FALSE;
        }
        return TRUE;
    }

    abstract protected function _process($data);

    protected function beginNotify() {

        $balance = $this->billing->getAccount()->balance;
        if ($this->billing->getBonusAccount()) {
            $balance += $this->billing->getBonusAccount()->balance;
        }
        $this->_notify_data = ['balance' => $balance];
    }

    abstract protected function endNotify();

    protected function mail($method, $param) {
        $mailer = new Mailer();
        $mailer->$method($param);
    }
    
    protected function assert($param, $message) {
        if(!$param){
            throw new TransactionException($message . ' in class ' . get_class($this));
        }
    }
    
    protected function assertNumeric($param) {
        if(!is_numeric($param)){
            throw new TransactionException('is not numeric');
        }
    }

}
