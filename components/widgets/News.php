<?php

namespace app\components\widgets;

use app\models\B2bNews;
use yii;
use yii\helpers\Url;

class News extends \yii\base\Widget {

    public $viewFile = 'news/show';
    public $sid;
    public function run() {
        $news = B2bNews::find()->where(['hidden' => 1])->orderBy(['id' => SORT_DESC])->limit(1)->one();
        echo $this->render($this->viewFile, ['news' => $news]);
    }
}
