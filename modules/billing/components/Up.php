<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\components;

use app\modules\billing\components\Transaction;

/**
 * Description of Up
 *
 * @author Schemelev E.
 */
abstract class Up extends Transaction {

    protected function _process($data) {
        $this->processBase($data);
    }

    /**
     * $this->billing->account->balance = $this->balance_before + $value;
     * @param type $value
     */
    protected function processBase($value) {
        $this->assertNumeric($value);

        $this->billing->getAccount()->balance = $this->balance_before + $value;
        $this->billing->getAccount()->save();
        $this->value = $value;
    }

    protected function endNotify() {
        $days = $this->billing->getAccount()->get_days_left();
        if (($this->balance_before <= 0.0 || $days <= 1) && $this->billing->getAccount()->balance > 0) {

            if ($this->billing->getSeller()->date_act > 0 && $this->billing->getSeller()->active == 0) {
                $this->billing->transaction($this->billing->getSellerId(), 'activate');
            }
        }
    }

}
