<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Promise extends \yii\base\Widget {

    public $viewFile = 'promise/show';
    public $sid;
    public function run() {
        $pay = \Yii::$app->db->createCommand("select * from seller_promice_pay where seller_id = {$this->sid} and is_repaid=0")->queryOne();
        $seller = \app\models\Seller::find()
            ->where(['id' => $this->sid])
            ->one();
        $bill_account = \app\models_ex\BillAccount::find()
            ->where(['id' => $seller->bill_account_id])
            ->one();
        if ((count($pay) > 0) && ($pay['sum'] > 0)){
            $sum = round($pay['sum'],2);
            $date = date("d.m.y",strtotime("+ 3 days",strtotime($pay['date'])));
            $button = "<a style='width: 100%' class='btn btn-default' href='/balance/promise'>Перейти</a>";
            $html = "<div class=\"ks-price-ratio-amount\">{$sum} ТЕ</div>
                        <div class=\"ks-price-ratio-progress\">
                            <div class=\"ks-text\">до {$date}</div>
                        </div><br>";
        } else {
            $html = "";
            $button = "<a style='width: 100%' class='btn btn-default' href='/balance/promise'><b>Ввести обещанный платеж</b></a>";
        }

        echo $this->render($this->viewFile, ['btn' => $button, 'html' => $html]);
    }
}
