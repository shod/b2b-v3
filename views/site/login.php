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
    <title>KOSMO - Multi Purpose Bootstrap 4 Admin Template</title>

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

<div class="ks-page">
    <div class="ks-page-header">
        <a href="https://www.migom.by" class="ks-logo" target="_blank">migom.by</a>
    </div>
    <div class="ks-page-content">
        <div class="ks-logo">B2B.MIGOM.BY</div>

        <div class="card panel panel-default ks-light ks-panel ks-login">
            <div class="card-block">
                <?php $form = ActiveForm::begin(); ?>

                <form class="form-container">
                    <h4 class="ks-header">Войти в систему</h4>
                    <div class="form-group">
                        <div class="input-icon icon-left icon-lg icon-color-primary">
                            <?= $form->field($model, 'username')->label('Логин'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-icon icon-left icon-lg icon-color-primary">
                            <?= $form->field($model, 'password')->label('Пароль'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                    <div class="ks-text-center">
                       Нет аккаунта? <a href="/site/sign-up">Зарегистрироваться</a>
                    </div>
                </form>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
    <div class="ks-footer">
        <span class="ks-copyright">&copy; 2018 migom.by</span>
        <ul>
            <li>
                <a href="#">Контакты</a>
            </li>
        </ul>
    </div>
</div>

<script src="/web/libs/jquery/jquery.min.js"></script>
<script src="/web/libs/tether/js/tether.min.js"></script>
<script src="/web/libs/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>