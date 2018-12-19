<?php

namespace app\modules\billing\transaction;

use app\modules\billing\components\Down;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Транзакция списание за заказ
 */
class Down_popup extends Down {

    protected function _process($data) {
        $this->assertNumeric($data);

        $this->processBase($data);
        
        echo $this->object_id = $this->billing->getSellerId();
    }

}
