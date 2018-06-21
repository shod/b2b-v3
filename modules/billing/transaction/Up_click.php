<?php

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Up;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Транзакция зачисление кликов на счет продавцу
 */
class Up_click extends Up {

    protected function _process($data) {
        if ($data > 0) {
            $balance_before = $this->billing->getAccount()->balance_clicks;

            $res_bval = $balance_before + $data;
            $this->billing->getAccount()->balance_clicks = $res_bval;
            $this->billing->getAccount()->save();
            
            $this->object_id = $res_bval;
            $this->value = 0;
        }
    }

}
