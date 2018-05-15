<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Actions extends \yii\base\Widget {

    public $viewFile = 'actions/show';
    public $sid;
    public function run() {
        $sql = "select bc.name, DATE_FORMAT(date_expired, '%d.%m.%Y') as date
            from bill_cat_sel_discount as bcd, bill_catalog as bc
            where bcd.seller_id = {$this->sid}
            and bc.id = bcd.catalog_id
			and date_expired >= now();";

        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $html = "";
        foreach ($data as $d){
            $html .= "<tr><td>{$d['name']}</td><td><mark>до {$d['date']}</mark></td></tr>";
        }
        if($html == ""){
            $html = "<span class=\"ks-card-widget-datetime\">Не подключено. Подробную информацию уточняйте у менеджеров.</span>";
        }
        echo $this->render($this->viewFile, ['actions' => $html]);
    }
}
