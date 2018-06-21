<?php

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Down;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Транзакция зачисление кликов на счет продавцу
 */
class Down_posms extends Down {

    protected function _process($data) {
           
        if(!isset($data['po_balance_count'])){
            throw new TransactionException("not set parametr po_balance_count");
        }
        if(!isset($data['value'])){
            throw new TransactionException("not set parametr value");
        }
        if(!is_numeric($data['value'])){
            throw new TransactionException("value  is not numeric");
        }
        
        $value = $data['value'];
        $poBalanceCount = $data['po_balance_count'];
        
        if (($this->balance_before - $value) > 0) {

            $this->processBase($value);

            $this->object_id = $data['po_balance_count'];

            $SellerInfo = \app\models\SellerInfo::find()->where(['seller_id' => $this->billing->getSellerId()])->one();
            $SellerInfo->updateCounters(['po_balance' => $poBalanceCount]);
        } else {
            throw new TransactionException("Balance is empty");
        }
    }

}
