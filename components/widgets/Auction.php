<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Auction extends \yii\base\Widget {

    public $viewFile = 'auction/';
    public $sid;
    public $type;
    public function run() {

            $fix = \Yii::$app->db->createCommand("select ba.*, ss.name from bill_auction as ba, catalog as ss
                                        where f_is_setting_bit_set(ss.setting_bit, 'catalog', 'auction_day') = 1 
                                        and ss.id = ba.object_id and type_id=1 and ba.owner_id={$this->sid} limit 3")->queryAll();

            $online = \Yii::$app->db->createCommand("select ba.*, ss.name from bill_auction as ba, catalog as ss
                   where f_is_setting_bit_set(ss.setting_bit, 'catalog', 'auction_day') = 0 and ss.id = ba.object_id and type_id=1 and ba.owner_id={$this->sid} limit 3")->queryAll();

        $html_fix = "";
        foreach ($fix as $r){
            $html_fix .= $this->render('auction/item', ['auction' => $r]);
        }
        if($html_fix == ""){
            $html_fix = "Вы не участвуете в аукционах.";
        }

        $html_online = "";
        foreach ($online as $r){
            $html_online .= $this->render('auction/item', ['auction' => $r]);
        }
        if($html_online == ""){
            $html_online = "Вы не участвуете в аукционах.";
        }
        echo $this->render($this->viewFile . $this->type, ['html_fix' => $html_fix,'html_online' => $html_online]);
    }
}
