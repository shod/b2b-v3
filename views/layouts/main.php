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
<body class="ks-navbar-fixed ks-sidebar-default ks-sidebar-position-fixed ks-page-header-fixed ks-theme-primary ks-page-loading"> <!-- remove ks-page-header-fixed to unfix header -->
<!-- remove ks-page-header-fixed to unfix header -->

<?php $this->beginBody() ?>



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

                <!-- BEGIN NAVBAR USER -->
                <div class="nav-item dropdown ks-user">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-avatar">
                            <img src="/web/img/avatars/avatar-13.jpg" width="36" height="36">
                        </span>
                        <span class="ks-info">
                            <span class="ks-name">Robert Dean</span>
                            <span class="ks-description">Premium User</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                        <a class="dropdown-item" href="#">
                            <span class="la la-user ks-icon"></span>
                            <span>Profile</span>
                        </a>
                        <a href='/site/logout' class="dropdown-item">
                            <span class="la la-user ks-icon"></span>
                            <span>Выйти</span>
                        </a>
                        <?//= Html::a('Logout', ['site/logout'], ['data' => ['method' => 'post']]) ?>
                        <!--a class="dropdown-item" href='/site/logout' methods="post">
                            <span class="la la-user ks-icon"></span>
                            <span>Выйти</span>
                        </a-->
                    </div>
                </div>
                <!-- END NAVBAR USER -->
            </div>
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
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="/web/img/avatars/avatar-12.jpg" width="36" height="36" class="ks-avatar rounded-circle">
                        <div class="ks-info">
                            <div class="ks-name">Peter Armstrong</div>
                            <div class="ks-text">Product Manager</div>
                        </div>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile-social-profile.html">Profile</a>
                        <a class="dropdown-item" href="profile-settings-general.html">Settings</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-dashboard"></span>
                        <span>Dashboard</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item ks-active" href="index.html">Default</a>
                        <a class="dropdown-item" href="dashboards-draggable-widgets.html">Draggable Widgets</a>
                        <a class="dropdown-item" href="dashboards-mail.html">Mail</a>
                        <a class="dropdown-item" href="dashboards-projects.html">Projects</a>
                        <a class="dropdown-item" href="dashboards-widgets-and-activity.html">Activity</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="email-templates.html">
                        <span class="ks-icon la la-envelope-o"></span>
                        <span>Email Templates</span>
                    </a>
                </li>
            </ul>
            <div class="ks-sidebar-extras-block">
                <div class="ks-extras-block-item">
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
                </div>
                <div class="ks-sidebar-copyright">© 2016 Kosmo. All right reserved</div>
            </div>
        </div>
    </div>
    <!-- END DEFAULT SIDEBAR -->


    <div class="ks-column ks-page">
        <div class="ks-page-header">
            <section class="ks-title-and-subtitle">
                <div class="ks-title-block">
                    <h3 class="ks-main-title"><?= Html::encode($this->title) ?></h3>
                    <div class="ks-sub-title"><?= $this->params['customParam']; ?></div>
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

<script type="application/javascript">
    (function ($) {
        $(document).ready(function () {
            c3.generate({
                bindto: '#ks-next-payout-chart',
                data: {
                    columns: [
                        ['data1', 6, 5, 6, 5, 7, 8, 7]
                    ],
                    types: {
                        data1: 'area'
                    },
                    colors: {
                        data1: '#81c159'
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    show: false
                },
                point: {
                    show: false
                },
                axis: {
                    x: {show:false},
                    y: {show:false}
                }
            });

            c3.generate({
                bindto: '#ks-total-earning-chart',
                data: {
                    columns: [
                        ['data1', 6, 5, 6, 5, 7, 8, 7]
                    ],
                    types: {
                        data1: 'area'
                    },
                    colors: {
                        data1: '#4e54a8'
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    show: false
                },
                point: {
                    show: false
                },
                axis: {
                    x: {show:false},
                    y: {show:false}
                }
            });

            c3.generate({
                bindto: '.ks-chart-orders-block',
                data: {
                    columns: [
                        ['Revenue', 150, 200, 220, 280, 400, 160, 260, 400, 300, 400, 500, 420, 500, 300, 200, 100, 400, 600, 300, 360, 600],
                        ['Profit', 350, 300,  200, 140, 200, 30, 200, 100, 400, 600, 300, 200, 100, 50, 200, 600, 300, 500, 30, 200, 320]
                    ],
                    colors: {
                        'Revenue': '#f88528',
                        'Profit': '#81c159'
                    }
                },
                point: {
                    r: 5
                },
                grid: {
                    y: {
                        show: true
                    }
                }
            });

            setTimeout(function () {
                new Noty({
                    text: '<strong>Welcome to Kosmo Admin Template</strong>! <br> You successfully read this important alert message.',
                    type   : 'information',
                    theme  : 'mint',
                    layout : 'topRight',
                    timeout: 3000
                }).show();
            }, 1500);

            var maplace = new Maplace({
                map_div: '#ks-payment-widget-table-and-map-map',
                controls_on_map: false
            });
            maplace.Load();
        });
    })(jQuery);
</script>

<div class="ks-mobile-overlay"></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
