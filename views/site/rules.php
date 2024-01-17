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
    <title>B2B <?= \Yii::$app->params['migom_name'] ?></title>

    <meta http-equiv="X-UA-Compatible" content=="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/fonts/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/fonts/open-sans/styles.css">
    <link rel="stylesheet" type="text/css" href="/libs/tether/css/tether.min.css">
    <link rel="stylesheet" type="text/css" href="/styles/common.min.css">
    <link rel="stylesheet" type="text/css" href="/styles/pages/auth.min.css">
</head>
<body>

<div class="ks-page" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url(<?= \Yii::$app->params['migom_url'] ?>/images/landing_files/ad4bcc61ffaa86d1d0e724caf0cc7fc0.png);">
    <div class="ks-page-content">
        <div class="ks-logo" style="color: white">B2B.<?= strtoupper(\Yii::$app->params['migom_name']) ?></div>

        <div class="card panel panel-default ks-light ks-panel ks-login" >
            <div class="card-block">
               <?= $text ?>



            </div>
        </div>
    </div>
    <div class="ks-footer">
        <span class="ks-copyright" style="color: white">&copy; <?= date("Y"); ?> <?= strtolower(\Yii::$app->params['migom_name']) ?></span>
        <ul>
            <li>
                <a href="<?= \Yii::$app->params['migom_url'] ?>/page/about/" style="color: white">О проекте</a>
            </li>
            <li>
                <a href="<?= \Yii::$app->params['migom_url'] ?>/page/adv/" style="color: white">Реклама на сайте</a>
            </li>
        </ul>
    </div>
</div>

<script src="/libs/jquery/jquery.min.js"></script>
<script src="/libs/tether/js/tether.min.js"></script>
<script src="/libs/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>