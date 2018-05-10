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

</head>
<body>

    <div class="ks-page" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url(https://www.migom.by/images/landing_files/ad4bcc61ffaa86d1d0e724caf0cc7fc0.png); min-height: 1100px">
        <div class="ks-page-content">
            <div class="ks-logo" style="color: white">B2B.migom.by</div>

            <div class="card panel panel-default ks-light ks-panel ks-signup" style="max-width: 364px;">
                <div class="card-block">
                    <form class="form-container" action="/site/sign-up" method="post">
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                        <h4 class="ks-header">Регистрация магазина</h4>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Имя" name="name">
                                <span class="icon-addon">
                                <span class="la la-user"></span>
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Телефон" name="phone">
                                <span class="icon-addon">
                                <span class="la la-phone"></span>
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Email" name="email">
                                <span class="icon-addon">
                                <span class="la la-at"></span>
                            </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Город" name="city">
                                <span class="icon-addon">
                                <span class="la la-map-marker"></span>
                            </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form-control" placeholder="Название магазина" name="shop">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form-control" placeholder="Название организации" name="org">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Логин" name="login">
                                <span class="icon-addon">
                                <span class="la la-at"></span>
                            </span>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="input-icon icon-left icon-lg icon-color-primary">
                                    <input type="password" class="form-control" placeholder="Пароль" name="password">
                                    <span class="icon-addon">
                                    <span class="la la-key"></span>
                                </span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="input-icon icon-left icon-lg icon-color-primary">
                                    <input type="password" class="form-control" placeholder="Еще раз" name="pass1">
                                    <span class="icon-addon">
                                    <span class="la la-key"></span>
                                </span>
                                </div>
                            </div>
                        </div>

                        <div class="ks-text-center">
                            Я принимаю условия
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="f_offerta[]" value="1">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Договора оферты</span>
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="f_offerta[]" value="2">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Договора оферты без НДС</span>
                                </label>
                            </div>
                        </div>
                        <div class="g-recaptcha" data-sitekey="6LfNZ1gUAAAAAJhGQVeavCN6V57rF0GpJYrjm6Up"></div>
                        <br>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block" value="Зарегистрироваться">
                        </div>
                        <div class="ks-text-center">
                            <span class="text-muted">Нажимая "Зарегистрироваться" я соглащаюсь с </span> <a href="pages-signup.html">правилами размещения в каталоге Migom.by</a>
                        </div>
                        <div class="ks-text-center">
                            Уже есть аккаунт? <a href="/site/login">Войти</a>
                        </div>
                        <br>
                        <div class="ks-text-center">
                            По вопросам работы Migom.by пишите на почту <a href="mailto:sale@migom.by">sale@migom.by</a>
                            или звоните по телефонам <a href="tel:+375291114545">+375 (29) 111-45-45</a> velcom, <a href="tel:+375297774545">+375 (29) 777-45-45</a> мтс
                        </div>
                    </form>
                    <br>
                    <div class="form-group row hidden-lg-up hidden-md-up">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <span class="ks-copyright" >&copy; 2018 migom.by</span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="https://www.migom.by/page/about/" >О проекте</a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="https://www.migom.by/page/adv/" >Реклама на сайте</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="ks-footer hidden-xs-down">
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
<script src="/web/tether/js/tether.min.js"></script>
<script src="/web/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>