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

        if($prod_stat[0]['cnt_all'] == 0){
            echo $this->render('products/empty');
        } else {
            if (count($prod_stat) > 0){
                $prod_stat_cnt_all = $prod_stat[0]['cnt_all'];
                $prod_active_percent = $prod_stat[0]['active_percent'];
            } else {
                $prod_stat_cnt_all = 0;
                $prod_active_percent = 0;
            }

            \Yii::$app->db->createCommand("call migombyha.pc_stat_seller_place({$this->sid},1)")->execute();
            $data_cost = \Yii::$app->db->createCommand("
					select seller_id
					, round(sum(prod_cnt_cost_max)/sum(prod_cnt_all)*100) as perc_max
					, round(sum(prod_cnt_cost_min)/sum(prod_cnt_all)*100) as perc_min
					from migombyha.stat_seller_cost_place
					where seller_id = {$this->sid}
					GROUP BY seller_id
			")->queryAll();
            if(count($data_cost > 0)){
                $cost_min = $data_cost[0]["perc_min"];
                $cost_max = $data_cost[0]["perc_max"];
            } else {
                $cost_min = 0;
                $cost_max = 0;
            }
            echo $this->render($this->viewFile, ['cnt_all' => $prod_stat_cnt_all, 'active_percent' => $prod_active_percent, 'cost_min' => $cost_min, 'cost_max' => $cost_max]);
        }

    }
}
