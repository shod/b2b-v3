<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Auction extends \yii\base\Widget {

    public $viewFile = 'auction/';
    public $sid;
    public $type;
    public function run() {
        if($this->type == 'fix'){
            $res = \Yii::$app->db->createCommand("select ba.*, ss.name from bill_auction as ba, catalog as ss
                                        where f_is_setting_bit_set(ss.setting_bit, 'catalog', 'auction_day') = 1 
                                        and ss.id = ba.object_id and type_id=1 and ba.owner_id={$this->sid}")->queryAll();
        } else {
            $res = \Yii::$app->db->createCommand("select ba.*, ss.name from bill_auction as ba, catalog as ss
                   where f_is_setting_bit_set(ss.setting_bit, 'catalog', 'auction_day') = 0 and ss.id = ba.object_id and type_id=1 and ba.owner_id={$this->sid}")->queryAll();
        }
        $html = "";
        foreach ($res as $r){
            $html .= $this->render('auction/item', ['auction' => $r]);
        }
        if($html == ""){
            $html = "Вы не участвуете в аукционах.";
        }
        echo $this->render($this->viewFile . $this->type, ['html' => $html]);
    }
}
