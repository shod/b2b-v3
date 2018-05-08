<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Currency extends \yii\base\Widget {

    public $viewFile = 'currency/show';
    public $sid;
    public $type;
    public function run() {
        $res = \Yii::$app->db->createCommand("SELECT round(rate/10000,4) as rate, round((1 - rate/old_rate),4) as perc from currency_nbrb as curr1, 
                    (SELECT rate as old_rate from currency_nbrb WHERE FROM_UNIXTIME(created_at, '%Y-%m-%d') = SUBDATE(CURDATE(),1) 
                    GROUP BY FROM_UNIXTIME(created_at, '%Y-%m-%d') ORDER BY id desc limit 1) as tbl
                    GROUP BY FROM_UNIXTIME(created_at, '%Y-%m-%d') ORDER BY id desc limit 1")->queryOne();


        echo $this->render($this->viewFile . $this->type, ['res' => $res]);
    }
}
