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
 * @author Schemelev E.
 */
abstract class Group extends Transaction {
    
    protected function endNotify() {}
    
    protected function _process($data)
    {
        $_data = isset($data['data']) ? $data['data'] : $data;
        foreach((array)$_data as $t)
        {
            $this->billing->transaction($this->billing->getSellerId(), $t[0], $t[1]);
        }
    }
    
    
}
