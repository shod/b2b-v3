<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Регистрация магазина. B2B.<?= strtoupper(\Yii::$app->params['migom_name']) ?></title>

    <meta http-equiv="X-UA-Compatible" content=="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/fonts/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/fonts/open-sans/styles.css">
    <link rel="stylesheet" type="text/css" href="/libs/tether/css/tether.min.css">
    <link rel="stylesheet" type="text/css" href="/styles/common.min.css">
    <link rel="stylesheet" type="text/css" href="/styles/pages/auth.min.css">
    
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
            <div class="ks-logo" style="color: white">B2B.<?= strtoupper(\Yii::$app->params['migom_name']) ?></div>

            <div class="card panel panel-default ks-light ks-panel ks-signup" style="max-width: 364px;">
                <div class="card-block">
                    <form class="form-container" action="/site/sign-up" method="post">
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                        <h4 class="ks-header">Регистрация магазина</h4>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Имя" name="fio" data-validation="length" data-validation-length="min4"
                                       data-validation-error-msg="Введите имя (минимум 4 символов)">
                                <span class="icon-addon">
                                <span class="la la-user"></span>
                            </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Телефон" name="phone" value="+375" data-validation="length" data-validation-length="min11"
                                       data-validation-error-msg="Введите телефон">
                                <span class="icon-addon">
                                <span class="la la-phone"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Email" name="email" data-validation="email" data-validation-error-msg="Введите email">
                                <span class="icon-addon">
                                <span class="la la-at"></span>
                            </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Город" name="city" data-validation="length" data-validation-length="min5"
                                       data-validation-error-msg="Введите город">
                                <span class="icon-addon">
                                <span class="la la-map-marker"></span>
                            </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form-control" placeholder="Название магазина" name="shop" data-validation="length" data-validation-length="min4"
                                       data-validation-error-msg="Введите название магазина">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <input type="text" class="form-control" placeholder="Название организации" name="company_name" data-validation="length" data-validation-length="min4"
                                       data-validation-error-msg="Введите название организации">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-icon icon-left icon-lg icon-color-primary">
                                <input type="text" class="form-control" placeholder="Логин" name="login" data-validation="server" data-validation-url="/site/sign-up-checklogin" data-validation="length" data-validation-length="min3"
                                       data-validation-error-msg="Введите название логин (минимум 3 символа)">
                                <span class="icon-addon">
                                <span class="la la-at"></span>
                            </span>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="input-icon icon-left icon-lg icon-color-primary">
                                    <input type="password" class="form-control" placeholder="Пароль" name="pass" data-validation="length" data-validation-length="min6"
                                           data-validation-error-msg="Введите название пароль (минимум 6 символов)">
                                    <span class="icon-addon">
                                    <span class="la la-key"></span>
                                </span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="input-icon icon-left icon-lg icon-color-primary">
                                    <input type="password" class="form-control" placeholder="Еще раз" name="pass1" data-validation="length" data-validation-length="min6"
                                           data-validation-error-msg="Повторите пароль (минимум 6 символов)">
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
                                    <input type="checkbox" class="custom-control-input offerta-check" onchange="check_offerta()" name="f_offerta[]" value="1">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description"><a href="<?= \Yii::$app->params['STATIC_URL_FULL'] ?>/files/Dogovor-oferty.pdf" target="_blank" >Договора оферты</a></span>
                                </label>
                            </div>
                            <!--div class="col-lg-6 col-md-6 col-sm-6">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input offerta-check" onchange="check_offerta()" name="f_offerta[]" value="2">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description"><a href="<?= \Yii::$app->params['STATIC_URL_FULL'] ?>/files/Dogovor-oferty-bez-nds.pdf" target="_blank" >Договора оферты без НДС</a></span>
                                </label>
                            </div-->
                        </div>
                        <p>
							<input  data-validation="recaptcha" data-validation-recaptcha-sitekey="<?= Yii::$app->params['recaptcha_sitekey'] ?>">
						</p>
                        <br>
                        <div class="form-group">
                            <input id="btn-submit" type="submit" class="btn btn-primary btn-block" disabled readonly value="Зарегистрироваться">
                        </div>
                        <div class="ks-text-center">
                            <span class="text-muted">Нажимая "Зарегистрироваться" я соглащаюсь с </span> <a href="/site/rules">правилами размещения в каталоге <?= \Yii::$app->params['migom_name'] ?></a>
                        </div>
                        <div class="ks-text-center">
                            Уже есть аккаунт? <a href="/site/login">Войти</a>
                        </div>
                        <br>
                        <div class="ks-text-center">
                            По вопросам работы <?= \Yii::$app->params['migom_name'] ?> пишите на почту <a href="mailto:<?= Yii::$app->params['saleManager'] ?>"><?= Yii::$app->params['saleManager'] ?></a>
                            или звоните по телефону <a href="tel:<?= \Yii::$app->params['phones']['ahref'] ?>"><?= \Yii::$app->params['phones']['info'] ?></a> velcom
                        </div>
                    </form>
                    <br>
                    <div class="form-group row hidden-lg-up hidden-md-up">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <span class="ks-copyright" >&copy; 2018 <?= strtolower(\Yii::$app->params['migom_name']) ?></span>
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
    <script src="/libs/jquery-form-validator/jquery.form-validator.min.js"></script>
    <script>
        $.validate({
			modules : 'security',
			reCaptchaSiteKey: '<?= Yii::$app->params['recaptcha_sitekey'] ?>',
			reCaptchaTheme: 'light',
		});
    </script>

</body>
</html>