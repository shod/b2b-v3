<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>B2B.migom.by <?= Html::encode($this->title) ?></title>
    <style>
        @media only screen and (max-width: 780px) {
            a:not(.btn-danger) > .ks-action {
                color: #25628f;
            }
            a:not(.btn-danger) > .ks-description{
                color: #8997c3;
            }
        }
    </style>
    <?php $this->head() ?>
</head>
<body class="ks-navbar-fixed ks-sidebar-default ks-sidebar-position-fixed ks-page-header-fixed ks-theme-white"> <!-- remove ks-page-header-fixed to unfix header -->
<!-- remove ks-page-header-fixed to unfix header -->

<?php $this->beginBody() ?>

<?php
    $seller_id = Yii::$app->user->identity->getId();
    $seller = \app\models\Seller::find()
    ->where(['id' => $seller_id])
    ->one();
    $bill_account = \app\models_ex\BillAccount::find()
    ->where(['id' => $seller->bill_account_id])
    ->one();
    $bonus_account_id = \app\models_ex\BillAccount::find()->where(['owner_id' => $seller->bill_account_id])->one();
?>

<!-- BEGIN HEADER -->
<nav class="navbar ks-navbar">
    <!-- BEGIN HEADER INNER -->
    <!-- BEGIN LOGO -->
    <div href="index.html" class="navbar-brand">
        <!-- BEGIN RESPONSIVE SIDEBAR TOGGLER -->
        <a href="#" class="ks-sidebar-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
        <a href="#" class="ks-sidebar-mobile-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
        <!-- END RESPONSIVE SIDEBAR TOGGLER -->

        <div class="ks-navbar-logo">
            <a href="/" class="ks-logo">B2B.MIGOM.BY</a>
        </div>
    </div>
    <!-- END LOGO -->

    <!-- BEGIN MENUS -->
    <div class="ks-wrapper">
        <nav class="nav navbar-nav">
            <!-- BEGIN NAVBAR MENU -->
            <div class="ks-navbar-menu">

            </div>
            <!-- END NAVBAR MENU -->
            <div>
            </div>

            <!-- BEGIN NAVBAR ACTIONS -->
            <div class="ks-navbar-actions">

                <div class="nav-item dropdown ks-user">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span>
                            <img src="http://static.migom.by/img/seller/logo$<?= $seller_id ?>.jpg" height="36">
                        </span>
                        <span class="ks-info">
                            <span class="ks-name"><?= $seller->name; ?></span>
                            <span class="ks-description"><?= $seller->id; ?></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                        <a class="dropdown-item" href="/site/logout">
                            <span class="la la-sign-out ks-icon" aria-hidden="true"></span>
                            <span>Выйти</span>
                        </a>
                    </div>
                </div>

                <!-- BEGIN NAVBAR NOTIFICATIONS -->
                <div class="nav-item ks-notifications">
                    <a class="nav-link " data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <?= $seller->active ? "<span style='padding-top: 7px;' class=\"badge badge-success\">АКТИВНЫЙ</span>" : "<span style='padding-top: 7px;' class=\"badge badge-danger\">ОТКЛЮЧЕН</span>"; ?>
                    </a>
                </div>
                <!-- END NAVBAR NOTIFICATIONS -->


                <div class="nav-item nav-link btn-action-block">
                    <a class="btn" href="#">
                        <span class="ks-action">Баланс <?= round($bill_account->balance,2); ?> - <?= $bill_account->getDayDownCatalog()*30 ?>/месяц (<?= $bill_account->getDayDownCatalog() ?>/день) </span>
                        <span class="ks-description">Бонус  <?= round($bonus_account_id->balance,2); ?> </span>
                    </a>
                </div>

                <div class="nav-item nav-link btn-action-block">
                    <a class="btn btn-danger" href="#">
                        <span class="ks-action"> Поставить </span>
                        <span class="ks-description"> на паузу </span>
                    </a>
                </div>






                <!-- BEGIN NAVBAR MESSAGES -->
                <div class="nav-item dropdown ks-messages">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-comments ks-icon" aria-hidden="true">
                        </span>
                        <span class="ks-text">Служба поддержки</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview" style="height: 235px;">
                        <ul class="nav nav-tabs ks-nav-tabs ks-info" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-tech" role="tab">Служба технической поддержки</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-users" role="tab">Ваш менеджер</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-reviews" role="tab">Модератор отзывов</a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane ks-messages-tab active" id="ks-navbar-tech" role="tabpanel" style="height: 150px;">
                                <div class="ks-wrapper">
                                    <div class="ks-message" style="height: 61%;">
                                        <div class="">
                                            <img src="http://b2b.migom.by/img/design/alena.jpg" style="margin-right: 10px;" height="130">
                                        </div>
                                        <div class="ks-info">
                                            <div class="ks-user-name"><h4>Алена</h4></div>
                                            <div class="ks-text">
                                                <b>Тел:</b> +375 29 <span style="color:#AA0000; font-size: 13px;">688-45-46</span> (Velcom)<br>
                                                <b>Тел:</b> +375 29 <span style="color:#AA0000; font-size: 13px;">858-45-46</span> (МТС)<br>
                                                <b>Skype:</b> admin.migom <br>
                                                <b>E-mail:</b> <a href="mailto:admin@migom.by">admin@migom.by</a> <br>
                                                <b>Время работы:</b> с 9:00 до 18:00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane ks-messages-tab" id="ks-navbar-users" role="tabpanel" style="height: 150px;">
                                <div class="ks-wrapper">
                                    <div class="ks-message" style="height: 61%;">
                                        <div class="">
                                            <img src="http://b2b.migom.by/img/design/janna.jpg" style="margin-right: 10px;" height="130">
                                        </div>
                                        <div class="ks-info">
                                            <div class="ks-user-name"><h4>Жанна</h4></div>
                                            <div class="ks-text">
                                                <b>Тел:</b> +375 29 <span style="color:#AA0000; font-size: 13px;">111-45-45</span> (Velcom) <span class="viber-icon"></span><br>
                                                <b>Тел:</b> +375 29 <span style="color:#AA0000; font-size: 13px;">777-45-45</span> (МТС) <br>
                                                <b>Skype:</b> sale.migom <br>
                                                <b>E-mail:</b> <a href="mailto:sale@migom.by">sale@migom.by</a> <br>
                                                <b>Время работы:</b> с 9:00 до 18:00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane ks-messages-tab" id="ks-navbar-reviews" role="tabpanel" style="height: 150px;">
                                <div class="ks-wrapper">
                                    <div class="ks-message" style="height: 61%;">
                                        <div class="ks-info">
                                            <div class="ks-user-name"></div>
                                            <div class="ks-text">
                                                <b>E-mail:</b> <a href="mailto:report@migom.by">report@migom.by</a> <br>
                                                <!--b>Контактное лицо:</b> Ольга<br /-->
                                                <b>Время работы:</b> с 9:00 до 18:00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- END NAVBAR MESSAGES -->

            <!-- END NAVBAR ACTIONS -->
        </nav>

        <!-- BEGIN NAVBAR ACTIONS TOGGLER -->
        <nav class="nav navbar-nav ks-navbar-actions-toggle">
            <a class="nav-item nav-link" href="#">
                <span class="la la-info la-2x ks-icon ks-open"></span>
                <span class="la la-close ks-icon ks-close"></span>
            </a>
        </nav>
        <!-- END NAVBAR ACTIONS TOGGLER -->

    </div>
    <!-- END MENUS -->
    <!-- END HEADER INNER -->
</nav>
<!-- END HEADER -->






<div class="ks-page-container ks-dashboard-tabbed-sidebar-fixed-tabs">

    <!-- BEGIN DEFAULT SIDEBAR -->
    <div class="ks-column ks-sidebar ks-info">
        <div class="ks-wrapper ks-sidebar-wrapper">
            <ul class="nav nav-pills nav-stacked">
                <!--li class="nav-item ks-user">
                    <a class="nav-link"  href="#" role="button" aria-haspopup="true" aria-expanded="true">
                        <div class="ks-info">
                            <div class="ks-name"><?= $seller->name; ?></div>
                            <div class="ks-text"><?= $seller->id; ?></div>
                        </div>
                    </a>
                </li-->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="true">
                        <span class="ks-icon la la-money"></span>
                        <span>Баланс</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/balance/add">Пополнить баланс</a>
                        <a class="dropdown-item" href="/balance/promise">Обещаный платеж</a>
                        <a class="dropdown-item" href="/bill-report">Финансовый отчет</a>
                        <a class="dropdown-item" href="/bill-report/akt">Акты</a>
                    </div>
                </li>
                <li id="" class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-bullhorn"></span>
                        <span>Продвижение магазина</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/auction">Аукционы</a>
                        <a class="dropdown-item" href="/spec">Спецпредложения</a>
                        <a class="dropdown-item" href="/context-adv">Контекстная реклама</a>
                        <a class="dropdown-item" href="/order/sms">Обратный звонок</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-cogs"></span>
                        <span>Настройка магазина</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/tariff">Мой тариф</a>
                        <a class="dropdown-item" href="/seller/settings">Информация для покупателей</a>
                        <a class="dropdown-item" href="/settings">Реквизиты магазина</a>
                        <a class="dropdown-item" href="/seller/delivery">Условия доставки</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-shopping-cart"></span>
                        <span>Мои товары</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/product/on-sale">Товары в продаже</a>
                        <a class="dropdown-item" href="/product/price">Импорт/экспорт прайсов</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-bar-chart-o"></span>
                        <span>Аналитика</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/statistic">Статистика магазина</a>
                        <a class="dropdown-item" href="/statistic/cost-analysis">Анализ цен конкурентов</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-comment-o"></span>
                        <span>Отзывы на магазин</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/reviews">Отзывы покупателей</a>
                        <a class="dropdown-item" href="/reviews/complaint">Жалобы покупателей</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-file-pdf-o"></span>
                        <span>Условия работы</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" target="_blank" href="https://static.migom.by/files/Dogovor-oferty.pdf">Договор публичной<br> оферты (с НДС)</a>
                        <a class="dropdown-item" target="_blank" href="https://static.migom.by/files/Dogovor-oferty-bez-nds.pdf">Договор публичной <br>оферты (без НДС)</a>
                        <a class="dropdown-item" href="/info/?page=rules_placement">Правила размещения</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/news">
                        <span class="ks-icon la la-newspaper-o"></span>
                        <span>Новости</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/help">
                        <span class="ks-icon la la-question-circle"></span>
                        <span>Помощь</span>
                    </a>
                </li>
            </ul>
            <div class="ks-sidebar-extras-block">
                <div class="ks-extras-block-item"><?= isset($this->params['customParam']) ? $this->params['customParam'] : ""; ?></div>
                <div class="ks-sidebar-copyright">© 2018 migom.by</div>
            </div>
        </div>
    </div>
    <!-- END DEFAULT SIDEBAR -->


    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title-and-subtitle">
                <div class="ks-title-block">
                    <h3 class="ks-main-title"><?= Html::encode($this->title) ?></h3>
                    <!--div class="ks-sub-title"></div-->
                    <!--div id="calc"></div-->
                </div>
            </section>
        </div>

        <div class="ks-page-content">


                        <?= $content;?>


        </div>
    </div>
</div>
<div id="modal-div"></div>


<div class="ks-mobile-overlay"></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
