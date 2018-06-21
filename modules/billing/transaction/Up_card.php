<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\components\Up;
use app\modules\billing\models\BillCard;
use app\modules\billing\components\TransactionException;

/**
 * Транзакция пополнения карточкой
 * Дополнительная транзакция пополнение бонуснго счета
 * */
class Up_card extends Up {

    protected function _process($data) {
        $billCard = BillCard::find()->where(['code' => $data, 'active' => 1])->one();
        if ($billCard) {
            $this->object_id = $billCard->id;

            $this->processBase($billCard->value);

            /*
             * Сейчас всегда начислять бонусы 2013-02-25
             * && $this->balance_before > 0
             */
            if ($billCard->bonus > 0.1) {
                $this->billing->transaction($this->billing->getSellerId(), 'up_cash', ['value' => $billCard->bonus, 'is_bonus' => true]);
            }
            $billCard->active = 0;
            $billCard->save();
        } else {
            throw new TransactionException('wrong card code');
        }
    }

}
