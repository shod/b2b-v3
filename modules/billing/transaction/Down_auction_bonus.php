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
 * @author MIG102-ssd
 */
class Down_auction_bonus extends DownBonus {

    protected $type = 'auction';

    protected function _process($data) {

        if (!isset($data['catalog_id'])) {
            throw new TransactionException("not set parametr catalog_id");
        }
        if (!isset($data['value'])) {
            throw new TransactionException("not set parametr value");
        }
        
        $this->processBase($data['value']);

        $this->object_id = $data['catalog_id'];
    }

}
