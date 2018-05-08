<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Weather extends \yii\base\Widget {

    public $viewFile = 'weather/show';
    public $sid;
    public function run() {
        $data = file_get_contents('http://api.openweathermap.org/data/2.5/weather?id=625144&units=metric&lang=ru&APPID=59b01094a75cc87f65b5cc2d5d967ec2');
        $weather = yii\helpers\Json::decode($data);
        echo $this->render($this->viewFile, ['weather' => $weather]);
    }
}
