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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="ks-navbar-fixed ks-sidebar-default ks-sidebar-position-fixed ks-page-header-fixed ks-theme-primary"> <!-- remove ks-page-header-fixed to unfix header -->
<!-- remove ks-page-header-fixed to unfix header -->

<?php $this->beginBody() ?>

<?php
    $seller_id = Yii::$app->user->identity->getId();
    $seller = \app\models\Seller::find()
    ->where(['id' => $seller_id])
    ->one();
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

            <!-- BEGIN NAVBAR ACTIONS -->
            <div class="ks-navbar-actions">
                <!-- BEGIN NAVBAR MESSAGES -->
                <div class="nav-item dropdown ks-messages">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-envelope ks-icon" aria-hidden="true">
                            <span class="badge badge-pill badge-info">3</span>
                        </span>
                        <span class="ks-text">Messages</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                        <section class="ks-tabs-actions">
                            <a href="#"><i class="la la-plus ks-icon"></i></a>
                            <a href="#"><i class="la la-search ks-icon"></i></a>
                        </section>
                        <ul class="nav nav-tabs ks-nav-tabs ks-info" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-toggle="tab" data-target="#ks-navbar-messages-inbox" role="tab">Inbox</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="tab" data-target="#ks-navbar-messages-sent" role="tab">Sent</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="tab" data-target="#ks-navbar-messages-archive" role="tab">Archive</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane ks-messages-tab active" id="ks-navbar-messages-inbox" role="tabpanel">
                                <div class="ks-wrapper ks-scrollable">
                                    <a href="#" class="ks-message">
                                        <div class="ks-avatar ks-online">
                                            <img src="/web/img/avatars/avatar-1.jpg" width="36" height="36">
                                        </div>
                                        <div class="ks-info">
                                            <div class="ks-user-name">Emily Carter</div>
                                            <div class="ks-text">In golf, Danny Willett (pictured) wins the M...</div>
                                            <div class="ks-datetime">1 minute ago</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="ks-view-all">
                                    <a href="#">View all</a>
                                </div>
                            </div>
                            <div class="tab-pane ks-empty" id="ks-navbar-messages-sent" role="tabpanel">
                                There are no messages.
                            </div>
                            <div class="tab-pane ks-empty" id="ks-navbar-messages-archive" role="tabpanel">
                                There are no messages.
                            </div>
                        </div>
                    </div>

                </div>
                <!-- END NAVBAR MESSAGES -->

                <!-- BEGIN NAVBAR NOTIFICATIONS -->
                <div class="nav-item dropdown ks-notifications">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-bell ks-icon" aria-hidden="true">
                            <span class="badge badge-pill badge-info">7</span>
                        </span>
                        <span class="ks-text">Notifications</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                        <ul class="nav nav-tabs ks-nav-tabs ks-info" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-toggle="tab" data-target="#navbar-notifications-all" role="tab">All</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane ks-notifications-tab active" id="navbar-notifications-all" role="tabpanel">
                                <div class="ks-wrapper ks-scrollable">
                                    <a href="#" class="ks-notification">
                                        <div class="ks-avatar">
                                            <img src="/web/img/avatars/avatar-3.jpg" width="36" height="36">
                                        </div>
                                        <div class="ks-info">
                                            <div class="ks-user-name">Emily Carter <span class="ks-description">has uploaded 1 file</span></div>
                                            <div class="ks-text"><span class="la la-file-text-o ks-icon"></span> logo vector doc</div>
                                            <div class="ks-datetime">1 minute ago</div>
                                        </div>
                                    </a>
                                </div>

                                <div class="ks-view-all">
                                    <a href="#">Show more</a>
                                </div>
                            </div>
                            <div class="tab-pane ks-empty" id="navbar-notifications-activity" role="tabpanel">
                                There are no activities.
                            </div>
                            <div class="tab-pane ks-empty" id="navbar-notifications-comments" role="tabpanel">
                                There are no comments.
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END NAVBAR NOTIFICATIONS -->


                <!-- BEGIN NAVBAR MESSAGES -->
                <div class="nav-item dropdown ks-messages">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-question-circle ks-icon" aria-hidden="true">
                        </span>
                        <span class="ks-text">Служба поддержки</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview" style="height: 235px;">
                        <ul class="nav nav-tabs ks-nav-tabs ks-info" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-tech" role="tab">Служба технической поддержки</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" style="margin: 10px;" data-toggle="tab" data-target="#ks-navbar-users" role="tab">Отдел по работе с клиентами</a>
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
                <span class="la la-ellipsis-h ks-icon ks-open"></span>
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
                <li class="nav-item ks-user dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="true">
                        <div class="ks-info" style="margin-right: 10px;">
                            <div class="ks-name">
                                <span class="ks-icon la la-<?= $seller->active ? "dot" : "times"; ?>-circle-o" style="color: <?= $seller->active ? "#15ba15" : "#db4242"; ?>;font-size: 30px;"></span>
                            </div>
                        </div>
                        <img src="http://static.migom.by/img/seller/logo$<?= $seller_id ?>.jpg" class="seller-logo">
                        <div class="ks-info">
                            <div class="ks-name"><?= $seller->name; ?></div>
                            <div class="ks-text"><?= $seller->id; ?></div>
                        </div>
                    </a>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile-social-profile.html">Профиль магазина</a>
                        <a class="dropdown-item" href="/seller/settings">Настройки аккаунта</a>
                        <a class="dropdown-item" href="/seller/delivery">Настройки доставки</a>
                        <a class="dropdown-item" href="profile-settings-general.html">Настройки юридической информации</a>
                        <a class="dropdown-item" href="/site/logout">Выйти</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="true">
                        <span class="ks-icon la la-money"></span>
                        <span>Баланс</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/product/tariff">Настройка тарифа</a>
                        <a class="dropdown-item" href="/balance/add">Пополнить баланс</a>
                        <a class="dropdown-item" href="/balance/promise">Обещаный платеж</a>
                        <a class="dropdown-item" href="/balance/akt">Акт приемки-сдачи выполненных работ</a>
                        <a class="dropdown-item" href="/balance/report">Финансовый отчет</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-shopping-cart"></span>
                        <span>Товары</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/product/on-sale">Товары в продаже</a>
                        <a class="dropdown-item" href="/product/price">Работа с прайсом</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-bullhorn"></span>
                        <span>Продвижение</span>
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
                        <span class="ks-icon la la-bar-chart-o"></span>
                        <span>Аналитика</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/statistic">Статистика по месяцам</a>
                        <a class="dropdown-item" href="/statistic/cost-analysis">Анализ цен конкурентов</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/reviews">
                        <span class="ks-icon la la-comment"></span>
                        <span>Отзывы</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/news">
                        <span class="ks-icon la la-newspaper-o"></span>
                        <span>Новости</span>
                    </a>
                </li>
            </ul>
            <div class="ks-sidebar-extras-block">
                <!--div class="ks-extras-block-item">
                    <div class="ks-name">Monthly Badwidth Transfer</div>
                    <div class="ks-progress">
                        <div class="progress ks-progress-xs">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 84%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="ks-description">
                        <span class="ks-amount">84%</span>
                        <span class="ks-text">(8 400 MB of 10 000)</span>
                    </div>
                </div>
                <div class="ks-extras-block-item">
                    <div class="ks-name">Disk Space Usage</div>
                    <div class="ks-progress">
                        <div class="progress ks-progress-xs">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 36%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="ks-description">
                        <span class="ks-amount">36%</span>
                        <span class="ks-text">(3 600 MB of 10 000)</span>
                    </div>
                </div-->
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
                    <div class="ks-sub-title"></div>
                </div>
            </section>
        </div>

        <div class="ks-page-content">
            <div class="ks-page-content-body">
                <div class="ks-dashboard-tabbed-sidebar">
                    <div class="ks-dashboard-tabbed-sidebar-widgets">
                        <?= $content;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-div"></div>


<div class="ks-mobile-overlay"></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
