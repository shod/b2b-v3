<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Регистрация магазина. B2B.migom.by</title>

    <meta http-equiv="X-UA-Compatible" content=="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/web/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/web/fonts/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/web/fonts/open-sans/styles.css">
    <link rel="stylesheet" type="text/css" href="/web/libs/tether/css/tether.min.css">
    <link rel="stylesheet" type="text/css" href="/web/styles/common.min.css">
    <link rel="stylesheet" type="text/css" href="/web/styles/pages/auth.min.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script>
        function check_offerta(){
            length = $(".offerta-check:checked").length;
            if(length > 0){
                $('#btn-submit').prop('disabled', false);
                $('#btn-submit').prop('readonly', false);
            } else{
                $('#btn-submit').prop('disabled', true);
                $('#btn-submit').prop('readonly', true);
            }
        }
    </script>

</head>
<body>

<div class="ks-page" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url(<?= \Yii::$app->params['migom_url'] ?>/images/landing_files/ad4bcc61ffaa86d1d0e724caf0cc7fc0.png); min-height: 1100px">
    <div class="ks-page-content">
        <div class="ks-logo" style="color: white">B2B.migom.by</div>

        <div class="card panel panel-default ks-light ks-panel ks-signup" style="width: 1000px;">
            <div class="card-block" style="width: 1000px;">
                <?= $text; ?>

                <div class="ks-text-center">
                    Нет аккаунта? <a href="/site/sign-up">Зарегистрироваться</a>
                </div>
                <div class="ks-text-center">
                    Уже есть аккаунт? <a href="/site/login">Войти</a>
                </div>
                <br>
                <div class="ks-text-center">
                    По вопросам работы <?= \Yii::$app->params['migom_name'] ?> пишите на почту <a href="mailto:<?= Yii::$app->params['saleManager'] ?>"><?= Yii::$app->params['saleManager'] ?></a>
                    или звоните по телефону <a href="tel:+375291114545">+375 (29) 111-45-45</a> velcom
                </div>
                <div class="form-group row hidden-lg-up hidden-md-up">
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <span class="ks-copyright" >&copy; 2018 migom.by</span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <a href="<?= \Yii::$app->params['migom_url'] ?>/page/about/" >О проекте</a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <a href="<?= \Yii::$app->params['migom_url'] ?>/page/adv/" >Реклама на сайте</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="ks-footer hidden-xs-down">
        <span class="ks-copyright" style="color: white">&copy; <?= date("Y"); ?> migom.by</span>
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

<script src="/web/libs/jquery/jquery.min.js"></script>
<script src="/web/libs/jquery-form-validator/jquery.form-validator.min.js"></script>
<script>
    $.validate();
</script>

</body>
</html>