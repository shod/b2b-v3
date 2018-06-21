<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\DownBonus;

/**
 * Description of Down_auction
 *
 * @author Schemelev E.
 */
class Down_auction_bonus extends DownBonus {

    protected $type = 'auction';

    protected function _process($data) {

        $this->assert(( isset($data['catalog_id']) ), 'not set parametr catalog_id');
        $this->assert(( isset($data['value']) ), 'not set parametr value');
        
        $this->processBase($data['value']);

        $this->object_id = $data['catalog_id'];
    }

}
