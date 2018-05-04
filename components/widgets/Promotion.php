<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Promotion extends \yii\base\Widget {

    public $viewFile = 'promotion/show';
    public $sid;
    public function run() {
        $sql = "call pc_seller_quality({$this->sid})";
        \Yii::$app->db->createCommand($sql)->execute();
        $sql = "select * from seller_products_quality where seller_id = {$this->sid}";
        $res = \Yii::$app->db->createCommand($sql)->queryOne();

        if (count($res) > 0){
            $quality = $res['prc_status'];
        }
        echo $this->render($this->viewFile, ['quality' => $quality]);
    }
}
