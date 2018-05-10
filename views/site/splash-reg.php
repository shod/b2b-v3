<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>B2B Migom.by</title>

    <meta http-equiv="X-UA-Compatible" content=="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/web/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/web/fonts/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/web/fonts/open-sans/styles.css">
    <link rel="stylesheet" type="text/css" href="/web/libs/tether/css/tether.min.css">
    <link rel="stylesheet" type="text/css" href="/web/styles/common.min.css">
    <link rel="stylesheet" type="text/css" href="/web/styles/pages/auth.min.css">
</head>
<body>

<div class="ks-page" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url(https://www.migom.by/images/landing_files/ad4bcc61ffaa86d1d0e724caf0cc7fc0.png);">
    <div class="ks-page-content">
        <div class="ks-logo" style="color: white">B2B.MIGOM.BY</div>

        <div class="card panel panel-default ks-light ks-panel ks-login" style="max-width: 364px;">
            <div class="card-block">
                <h3>Спасибо за регистрацию на b2b.migom.by</h3>
                <p>В ближайшее время администратор проекта свяжется с Вами по указанным контактным данным</p>
                <br/>
                <p><b>Внимание!</b> Магазин и указанный логин будут активированы только после проверки корректности данных администратором проекта. </p>
                <br>
                <p>
                    По вопросам работы Migom.by пишите на почту <a href="mailto:sale@migom.by">sale@migom.by</a>
                    или звоните по телефонам <a href="tel:+375291114545">+375 (29) 111-45-45</a> velcom, <a href="tel:+375297774545">+375 (29) 777-45-45</a> мтс
                </p>
            </div>
        </div>
    </div>
    <div class="ks-footer">
        <span class="ks-copyright" style="color: white">&copy; 2018 migom.by</span>
        <ul>
            <li>
                <a href="https://www.migom.by/page/about/" style="color: white">О проекте</a>
            </li>
            <li>
                <a href="https://www.migom.by/page/adv/" style="color: white">Реклама на сайте</a>
            </li>
        </ul>
    </div>
</div>

<script src="/web/libs/jquery/jquery.min.js"></script>
<script src="/web/libs/tether/js/tether.min.js"></script>
<script src="/web/libs/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>