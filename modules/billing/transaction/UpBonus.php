<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\components\Up;
use app\modules\billing\components\TransactionException;

/**
 * Description of Up
 *
 * @author Schemelev E.
 */
abstract class UpBonus extends Up {

    protected function processBase($value) {
        $this->assertNumeric($value);
        $this->assert(($value >= 0), 'value < 0');

        $this->setAccountId($this->billing->getBonusAccount()->id);

        $this->balance_before = $this->billing->getBonusAccount()->balance;

        $this->billing->getBonusAccount()->balance += $value;
        $this->billing->getBonusAccount()->save();

        $this->value = $value;
    }

}
