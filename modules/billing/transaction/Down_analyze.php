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
class Down_analyze extends Down {

    protected function _process($data) {
        if (is_numeric($data)) {
            $this->processBase($data);

            \Yii::$app->db->createCommand("update seller set setting_bit=f_setting_bit_set(setting_bit,262144,1) where id={$this->billing->seller_id}")
                ->execute();
        } else {
            throw new TransactionException('cash data is not numeric');
        }
    }
}
