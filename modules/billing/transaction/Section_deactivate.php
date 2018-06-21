<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Group;
use app\modules\billing\models\BillCatalogSeller;

/**
 * Description of Up
 *
 * @author Schemelev E.
 */
class Section_deactivate extends Group {
    
    protected function _process($data)
    {
        $id = (int) $data;
        $this->assert(($id > 0), 'catalog_id not INTEGER');
        
        BillCatalogSeller::deleteAll(['catalog_id' => $id, 'seller_id' => $this->billing->getSellerId()]);
        
        $this->object_id = $id;
    }
}
