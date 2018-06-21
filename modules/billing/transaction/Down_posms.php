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
        $whirl->config->read_config("bill_account");
        $value = $whirl->config->get('posms_' . $data);

        if (is_numeric($value)>0){
            if (($this->balance_before - $value) > 0) {
                
                $this->processBase($value);
               
                $this->object_id = $data;
                
                $SellerInfo = \app\models\SellerInfo::find()->where(['seller_id' => $this->billing->getSellerId()])->one();
                $SellerInfo->updateCounters(['po_balance' => $data]);
             
                return "true";
            } else {
                throw new TransactionException("Balance is empty");
            }
        }else {
            throw new TransactionException("data is not numeric");
        }
    }

}
