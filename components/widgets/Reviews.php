<?php

namespace app\components\widgets;

use yii;
use yii\helpers\Url;

class Reviews extends \yii\base\Widget {

    public $viewFile = 'reviews/show';
    public $sid;
    public function run() {
        $review = \Yii::$app->db->createCommand("
			select r.*, r1.review as answer
			from review r
			left join review r1 on (r1.owner_id=r.id)
			where r.object_id={$this->sid} and r.type = 1 and r.active=1 and r.owner_id=0
			order by date desc
			limit 1
			")->queryOne();
        if(!$review){
            $review['title'] = "К сожалению отзывов на ваш магазин нет.";
            $review['review'] = "Напоминайте покупателям оставлять отзывы на ".\Yii::$app->params['migom_domain'].".";
        }
        echo $this->render($this->viewFile, ['review' => $review]);
    }
}
