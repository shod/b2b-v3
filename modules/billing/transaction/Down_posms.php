<?php

namespace app\modules\billing\transaction;

use app\modules\billing\components\Down;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Транзакция списание за sms
 */
class Down_posms extends Down {

    protected function _process($data) {
           
        $this->assert(( isset($data['po_balance_count']) ), 'not set parametr po_balance_count');
        $this->assert(( isset($data['value']) ), 'not set parametr value');
        $this->assertNumeric($data['value']);
        
        $value = $data['value'];
        
        $this->assert(( ($this->balance_before - $value) >= 0 ), 'Balance is empty');
        
        $this->processBase($value);

        $this->object_id = $data['po_balance_count'];

        $SellerInfo = \app\models\SellerInfo::find()->where(['seller_id' => $this->billing->getSellerId()])->one();
        $SellerInfo->updateCounters(['po_balance' => $data['po_balance_count']]);
            
    }

}
