<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\components\Down;

/**
 * Description of Up
 *
 * @author Schemelev E.
 */
abstract class DownBonus extends Down {
    
    /**
     * $this->billing->account->balance = $this->balance_before - $value;
     * @param type $value
     */
    protected function processBase($value) {
        if(!$value){
            return;
        }
        $this->setAccountId($this->billing->getBonusAccount()->id);
        
        $this->balance_before = $this->billing->getBonusAccount()->balance;

        $this->billing->getBonusAccount()->balance -= $value;
        if(!$this->billing->getBonusAccount()->save()){
            throw new TransactionException(print_r($this->billing->getBonusAccount()->getErrors(), 1));
        }
        
        $this->value = -$value;
    }
}
