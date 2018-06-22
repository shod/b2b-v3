<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Deactivate;

/**
 * Description of Activate
 *
 * @author Schemelev E.
 */
class Deactivate_b2b extends Deactivate {

    const COST = 5;

    protected function _process($data) {
        $this->processBase(self::COST);

        $this->object_id = $this->billing->getSellerId();
        parent::_process($data);
    }

}
