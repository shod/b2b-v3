<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Products extends \yii\base\Widget {

    public $viewFile = 'products/show';
    public $sid;
    public function run() {
        $prod_stat = \Yii::$app->db->createCommand("select cnt_all, cnt_bill, round(cnt_bill/cnt_all*100) as active_percent
													from (select count(1) as cnt_all, sum(if(active=1,1,0)) as cnt_bill
													from product_seller as ps
													where ps.seller_id = {$this->sid} and ps.product_id > 0) as qq")->queryAll();

        if (count($prod_stat) > 0){
            $prod_stat_cnt_all = $prod_stat[0]['cnt_all'];
            $prod_active_percent = $prod_stat[0]['active_percent'];
        } else {
            $prod_stat_cnt_all = 0;
            $prod_active_percent = 0;
        }
        echo $this->render($this->viewFile, ['cnt_all' => $prod_stat_cnt_all, 'active_percent' => $prod_active_percent]);
    }
}