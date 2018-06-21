<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\components\Down;

/**
 * Description of Down_auction
 *
 * @author Schemelev E.
 */
class Down_auction extends Down {

    //put your code here
    protected $type = 'auction';

    protected function _process($data) {
        $this->assert(( isset($data['catalog_id'])), 'not set parametr catalog_id');
        $this->assert(( isset($data['value'])), 'not set parametr value');

        $value = $data['value'];
        $this->object_id = $data['catalog_id'];

        if ($bonus_value = min($value, $this->getBonusBalance())) {
            if ($bonus_value > 0.0001) {
                $this->billing->transaction($this->billing->getSellerId(), 'Down_auction_bonus', [
                    'catalog_id' => $data['catalog_id'],
                    'value' => $bonus_value]);
                $value -= $bonus_value;
            }
        }

        $this->processBase($value);
    }

}
