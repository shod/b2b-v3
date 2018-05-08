<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Cost extends \yii\base\Widget {

    public $viewFile = 'cost/show';
    public $sid;
    public function run() {
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
        echo $this->render($this->viewFile, ['cost_min' => $cost_min, 'cost_max' => $cost_max]);
    }
}
