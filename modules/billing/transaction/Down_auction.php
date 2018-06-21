<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Down;

/**
 * Description of Down_auction
 *
 * @author MIG102-ssd
 */
class Down_auction extends Down {
    //put your code here
    protected $type = 'auction';
    
    protected function _process($data)
    {
        if(!isset($data['catalog_id'])){
            throw new TransactionException("not set parametr catalog_id");
        }
        if(!isset($data['value'])){
            throw new TransactionException("not set parametr value");
        }
        
        $value = $data['value'];
        $this->object_id = $data['catalog_id'];
       
        $bonus_balance = $this->getBonusBalance();
        if ($bonus_value = min($value, $bonus_balance))
        {
            if ($bonus_value > 0.0001)
            {
                $this->billing->transaction($this->billing->getSellerId(), 'Down_auction_bonus', ['catalog_id' => $data['catalog_id'], 'value' => $bonus_value]);
                $value -= $bonus_value;
            }
        }

        $this->processBase($value);
    }
    
}
