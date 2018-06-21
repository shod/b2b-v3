<?php

namespace app\modules\billing\transaction;

use app\modules\billing\transaction\Group;
use app\modules\billing\models\BillCatalogSeller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Транзакция зачисление кликов на счет продавцу
 */
class Section_group extends Group {

    private $_notify_data;
    
    private function getData() {
        $data = [];
        $res = BillCatalogSeller::find()->where(['seller_id' => $this->billing->getSellerId()])->all();
        foreach ((array) $res as $r) {
            $data[] = array('id' => $r->catalog_id, 'f_tarif' => $r->f_tarif);
        }
        return $data;
    }

    protected function beginNotify() {
        $balance = $this->billing->getAccount()->balance + $this->getBonusBalance();

        $this->_notify_data = [
            'balance' => $balance,
            'data' => $this->getData()
        ];
    }

    protected function endNotify() {
        $dat_bef = $this->_notify_data['data'];
        $dat_after = $this->getData();

        if ($dat_bef != $dat_after) {
            $this->mail('sections_admin', [
                'seller_id' => $this->billing->getSellerId(),
                'data_before' => $this->_notify_data['data'],
                'data_after' => $dat_after
            ]);
        }
    }

}
