<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Balance extends \yii\base\Widget {

    public $viewFile = 'balance/show';
    public $sid;
    public function run() {
        $seller = \app\models\Seller::find()
            ->where(['id' => $this->sid])
            ->one();
        $bill_account = \app\models_ex\BillAccount::find()
            ->where(['id' => $seller->bill_account_id])
            ->one();
        $bonus_account_id = \app\models_ex\BillAccount::find()->where(['owner_id' => $seller->bill_account_id])->one();
        echo $this->render($this->viewFile, ['balance' => round($bill_account->balance,2), 'bonus' => isset($bonus_account_id) ? round($bonus_account_id->balance,2) : 0]);
    }
}
