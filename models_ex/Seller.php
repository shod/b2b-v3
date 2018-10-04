<?php

namespace app\models_ex;

class Seller extends \app\components\db\TSFlaggedActiveRecord {

    protected static $singleton_class = __CLASS__;
    
    public function flags() {
		return [
            
            'type_order' =>  '67108864',
            // ...
            'analyze' => '262144',
            'logoauto' => '131072',
            'proxysite' => '65536',
            'halva' => '32768',
            'instalments' => '16384',
            'context' => '8192',
            'hard' => '4096',
            'stat' => '2048',
            'bank' => '1024',
            'nal' => '512',
            'post' => '256',
            'easypay' => '128',
            'webmoney' => '64',
            'ipay' => '32',
            'erip' => '16',
            'card' => '8',
            'email' => '4',
            'demand' => '2',
            'phone' => '1',
        ];
	}
    
    public function paymentFlags() {
		return [
            'halva' => '32768',
            'bank' => '1024',
            'nal' => '512',
            'post' => '256',
            'easypay' => '128',
            'webmoney' => '64',
            'ipay' => '32',
            'erip' => '16',
            'card' => '8',
        ];
	}

    public function flagLabels() {
        return array(
            'halva' => 'По системе Халва',
            'bank' => 'Банковским переводом',
            'nal' => 'Наложным платежом',
            'post' => 'Через почтовый перевод',
            'easypay' => 'Через EasyPay',
            'webmoney' => 'Через WebMoney',
            'ipay' => 'Через iPay',
            'erip' => 'Через ЕРИП (АИС "Расчет")',
            'card' => 'Банковской пластиковой карточкой',
            
            'phone' => 'Оповещение СМС на телефон',
            'demand' => 'Запросы',
            'email' => 'Оповещение на emal',
            'stat' => 'Статистика по продавцу в b2b',
            'hard' => 'Признак строгости продавца при работе',
            'context' => 'Продвижение по контексту',
            'instalments' => 'Рассрочка',
            'proxysite' => 'Установка на переход на сайт',
            'logoauto' => 'Логотип автоматически',
            'analyze' => 'Доступ к анализу цен',
        );
    }

    public function afterFind ( ){
        $this->name = stripslashes($this->name);
    }
    
    /**
     * //MTODO кажется это нужно не сдесь делать
     * //MTODO если у продавца нет картинки, нужно сгенерировать ее из id
     * @return boolean
     */
    public function get_logo($css_class = "img-responsive pob-sp-logo center-block") : string {
        return  \yii\helpers\Html::img(\Yii::$app->params['STATIC_URL'] . "/img/seller/logo$" . $this->id . ".jpg", [
            //'data-original' => \Yii::$app->params['STATIC_URL'] . "/img/seller/logo$" . $this->id . ".jpg",
            'alt' => $this->name,
            'title' => $this->name,
            'class' => $css_class,
        ]); 
	}
    
    public function getSiteName() {
        return ($this->site_alias)?$this->site_alias:$this->site;
    }
    
    public function get_count_star() : float {
        $pop = round($this->popular, 2);
        $floor = floor($pop);
        $drob = $pop - $floor;

        if($drob && $drob > 0.25 && $drob < 0.75){
            $floor = $floor + 0.5;
        }elseif($drob > 0.75){
            $floor++; 
        }
		return (float) $floor;
	}

	public function get_phones() {
        $phones = unserialize($this->phone);
        $phones_types = [];
        foreach ($phones[0] as $phone){
            if(isset($phone['type']) && (($phone['type'] == 'credit') || ($phone['type'] == 'beznal'))){
                $phones_types[1][] = $phone;
            } else {
                $phones_types[0][] = $phone;
            }

        }

        return $phones_types;
    }
    
    public function get_review() : array {
        $count = $this->indexSeller->cnt_review;
        
		$href = \yii\helpers\Url::to(['/seller',  "id" => $this->id, "mode" => "reviews"]);
        $vars = ['count' => $count];
        
		if ($count > 0){
			$vars["review_href"] = $href;
			$vars["review_title"] = \Yii::t('const', 'Отзывы') . " ({$count})";
		}else{
			$vars["review_href"] = $href . "#add";
			$vars["review_title"] = \Yii::t('const', 'Написать отзыв');
		}
        $vars['review_add_link'] = $vars["review_title"];
		return $vars;
	}

}
