<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

if(isset(Yii::$app->response->statusCode) && Yii::$app->response->statusCode == 404){
    $this->title = 'Ошибка 404';
    echo $this->render('errors/404');
}elseif(isset(Yii::$app->response->statusCode) && Yii::$app->response->statusCode == 503){
    $this->title = 'Что-то пошло не так :(';
    echo $this->render('errors/default');
}else{
    $this->title = 'Что-то пошло не так :(';
    echo $this->render('errors/default');
}

?>

