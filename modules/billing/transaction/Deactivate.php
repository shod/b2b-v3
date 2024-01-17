<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\components\Down;

/**
 * Description of Activate
 *
 * @author Schemelev E.
 */
class Deactivate extends Down {

    protected function _process($data) {
        $this->billing->getSeller()->active = 0;
        $this->billing->getSeller()->save();

        \app\helpers\SysService::EventAdd('seller_deactivate', [$this->billing->getSellerId()]);

        $this->object_id = $this->billing->getSellerId();
    }

    public function endNotify() {
        $this->mail('deactivate', $this->billing->getSellerId());
    }

}
