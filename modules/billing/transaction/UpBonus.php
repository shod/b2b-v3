<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Up;
use app\modules\billing\components\TransactionException;

/**
 * Description of Up
 *
 * @author MIG102-ssd
 */
abstract class UpBonus extends Up {

    protected function processBase($value) {
         
        if(!is_numeric($value)){
            throw new TransactionException('cash data is not numeric');
        }
        if($value < 0){
            throw new TransactionException('value < 0');
        }
        $this->setAccountId($this->billing->getBonusAccount()->id);
        
        $this->balance_before = $this->billing->getBonusAccount()->balance;
        
        $this->billing->getBonusAccount()->balance += $value;
        $this->billing->getBonusAccount()->save();

        $this->value = $value;
    }
}
