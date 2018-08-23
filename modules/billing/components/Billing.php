<?php

namespace app\modules\billing\components;

use app\modules\billing\models\BillCatSelDiscount;
use app\modules\billing\components\TransactionException;
use app\models_ex\BillAccount;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Billing {

    public $seller_id;
    private $seller;
    private $account;
    private $bonus_account;

    public function getSellerId() {
        return $this->seller_id;
    }
    
    public function getSeller() {
        return $this->seller;
    }

    private function init($seller_id) {
        $this->seller_id = $seller_id;
        $this->seller = \app\models\Seller::findOne($seller_id);
        if ($this->seller->bill_account_id) {
            $this->account = BillAccount::findOne($this->seller->bill_account_id);
            $this->setBonusAccount();
        }
    }
    
    /**
     * 
     * @return \app\models\BillAccount
     */
    public function getAccount() : BillAccount {
        return $this->account;
    }
    
    /**
     * 
     * @return \app\models\BillAccount
     */
    public function getBonusAccount() : BillAccount {
        return $this->bonus_account;
    }
    
    public function setBonusAccount() {
        $this->bonus_account = BillAccount::find()->where(['owner_id' => $this->account->id])->one();
        if (!$this->bonus_account) {
            $billAccount = new BillAccount();
            $billAccount->owner_id = $this->account->id;
            $billAccount->save();
            
            $this->bonus_account = $billAccount;
        }
        return $this;
    }

    public function transaction($seller_id, string $type, $data = null) {
        $this->init($seller_id);
        $class = "app\\modules\\billing\\transaction\\" . ucfirst($type);
        $tr = new $class($this);
        return $tr->process($data);
    }

}
