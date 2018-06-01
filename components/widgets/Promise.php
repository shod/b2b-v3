<?php

namespace app\components\widgets;

use app\models\SysStatus;
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
            $f_offerta = $seller->f_offerta;

            if(!($f_offerta & 1) && ($f_offerta & 2)){
                $curs = SysStatus::find()->where(['name' => 'curs_te_nonds'])->one()->value;
            } else {
                $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;
            }
            if($seller->pay_type == 'fixed'){
                $vars['day_down'] = (round($bill_account->getDayDown(1),2)*4*(float)$curs/100)*100;

            } else {
                $sql = "select ROUND(avg(cnt_click)*cost_click*4) as click_cost
                            from (select seller_id, cnt_click from seller_clicks_stat
                            where seller_id = {$this->sid} and cnt_click>0 order by date_stat desc limit 10) as qw
                            , seller_click_tarif as ct, bill_click_tarif as bc
                            where ct.seller_id = qw.seller_id
                            and bc.id = ct.bill_click_tarif_id;";
                $res_sum =\Yii::$app->db->createCommand($sql)->queryAll();
                $vars['day_te'] = round($res_sum[0]['click_cost'],2);
                $vars['sum_click'] = intval($vars['day_te'] / 0.4);
                $vars['day_down'] = ($vars['day_te']*(float)$curs/100)*100;

            }
            $html = "<h5>Доступная сумма {$vars['day_down']} руб. </h5>";
            $button = "<a style='width: 100%' class='btn btn-default' href='/balance/promise'><b>Ввести обещанный платеж</b></a>";
        }

        echo $this->render($this->viewFile, ['btn' => $button, 'html' => $html]);
    }
}
