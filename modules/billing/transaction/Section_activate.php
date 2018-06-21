<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Group;
use app\modules\billing\models\BillCatalogSeller;
use app\models\BillCatalog;

/**
 * Description of Up
 *
 * @author MIG102-ssd
 */
class Section_activate extends Group {
    
    protected function _process($data)
    {
        $id = (int) $data;
        $this->assert(($id > 0), 'catalog_id not INTEGER');
        
        $bill = new BillCatalogSeller();
        $bill->catalog_id = $id;
        $bill->seller_id = $this->billing->getSellerId();
        $bill->created_at = date('Y-m-d H:i:s',time());
        $bill->save();
        
        $billCatalog = BillCatalog::findOne($id);
        $value = $billCatalog->cost / 30;
        
        $balance = $this->billing->getAccount()->balance;
        $this->billing->getAccount()->balance = $balance - $value;
        $this->billing->getAccount()->save();

        $this->value = -$value;
        $this->object_id = $id;
    }
}
