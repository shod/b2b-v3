<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\components\Transaction;
use app\models_ex\BillAccount;

/**
 * Description of Up
 *
 * @author MIG102-ssd
 */
abstract class Down extends Transaction {
    
    /**
     * $this->billing->account->balance = $this->balance_before - $value;
     * @param type $value
     */
    protected function processBase($value) {
        if(!$value){
            return;
        }
        $this->billing->getAccount()->balance = $this->balance_before - $value;
        $this->billing->getAccount()->save();
        if(!$this->billing->getAccount()->save()){
            throw new TransactionException(print_r($this->billing->getAccount()->getErrors(), 1));
        }

        $this->value = -$value;
    }
    
    protected function endNotify() {
        if ($this->billing->getAccount()->balance > 0.0) {
            $days = $this->billing->getAccount()->get_days_left();
            
            if ($days <= 2) {
                $this->mail('balance_day_1', $this->billing->seller_id);
            }

            if ($this->_notify_data['balance'] >= BillAccount::MIN_BALANCE
                && $this->billing->getAccount()->balance < BillAccount::MIN_BALANCE
            ) {
                $this->mail('balance_30', $this->billing->seller_id);
            }
        }
    }
}
