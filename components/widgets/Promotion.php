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

        $vars['products'] = $res['products'];
        $vars['auction'] = $res['auction'];
        $vars['spec'] = $res['spec'];
        $vars['banner'] = $res['banner'];
        $vars['posms'] = $res['posms'];
        $vars['phone_fail'] = $res['phone_fail'];
        $vars['context'] = $res['context'];

        $sql = "select count(1) as cnt from seller_products_quality as spq, seller as ss where prc_status >  {$quality} and ss.id = spq.seller_id and ss.active = 1";
        $res = \Yii::$app->db->createCommand($sql)->queryOne();
        if (count($res) > 0){
            $vars['cnt_hight'] = "<h4>Количество продавцов с более эффективным продвижением - <span class='badge'>{$res['cnt']}</span>.</h4>";
        }
        echo $this->render($this->viewFile, ['quality' => $quality, 'vars' => $vars]);
    }
}
