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
    <title>B2B.<?= strtoupper(\Yii::$app->params['migom_name']) ?> <?= Html::encode($this->title) ?></title>
    <style>
        @media only screen and (max-width: 780px) {
            a:not(.btn-success):not(.btn-danger) > .ks-action {
                color: #25628f;
            }
            a:not(.btn-success):not(.btn-danger) > .ks-description{
                color: #8997c3;
            }
        }
    </style>
    <?php $this->head() ?>
	<link href="/favicon.png?v=3" rel="shortcut icon">
</head>
<body class="ks-navbar-fixed ks-sidebar-default ks-sidebar-position-fixed ks-page-header-fixed ks-theme-white"> <!-- remove ks-page-header-fixed to unfix header -->
<!-- remove ks-page-header-fixed to unfix header -->

<?php $this->beginBody() ?>

<?php
    $seller_id = Yii::$app->user->identity->getId();
	if(isset($seller_id)){
		$seller = \app\models\Seller::find()
		->where(['id' => $seller_id])
		->one();
		if($seller->bill_account_id){
			$bill_account = \app\models_ex\BillAccount::find()
			->where(['id' => $seller->bill_account_id])
			->one();
			$bonus_account_id = \app\models_ex\BillAccount::find()->where(['owner_id' => $seller->bill_account_id])->one();
		}
	}

    $this->registerJs(
        "if ($seller->setting_bit & 33554432){
            getModalByurl('Добро пожаловать в обновленную B2B панель ".\Yii::$app->params['migom_domain']."!','features');
        }"
    );
    
    

    //$res = $whirl->dbd->query("select type from bill_transaction where account_id={$account_id} and type in ('deactivate', 'deactivate_b2b') order by id desc limit 1");
    //$denied = ((count($res) && $res[0][0]=='deactivate') || $vars["date_start"] == NULL);
    $denied = false;
    $activation = isset($seller) && $seller->active ? "deactivate" : ( (isset($bill_account) && $bill_account->balance > 0) ? ($denied ? 'activate_denied' : 'activate') : "activate_none");
    $activation_button = isset($seller) && $seller->active ? "<a class=\"btn btn-danger\" data-remote=\"/selller/activate/?type={$activation}\" data-toggle=\"ajaxModal\">
                        <span class=\"ks-action\"> Поставить </span>
                        <span class=\"ks-description\"> на паузу </span>
                    </a>" : "<a class=\"btn btn-success\" data-remote=\"/selller/activate/?type={$activation}\" data-toggle=\"ajaxModal\">
                        <span class=\"ks-action\"> Возобновить </span>
                        <span class=\"ks-description\"> аккаунт </span>
                    </a>";
?>
<!-- BEGIN HEADER -->
<nav class="navbar ks-navbar" >
    <!-- BEGIN HEADER INNER -->
    <!-- BEGIN LOGO -->
    
    <div href="index.html" class="navbar-brand">
        
        <!-- BEGIN RESPONSIVE SIDEBAR TOGGLER -->
        <a href="#" class="ks-sidebar-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
        <a href="#" class="ks-sidebar-mobile-toggle"><i class="ks-icon la la-bars" aria-hidden="true"></i></a>
        <!-- END RESPONSIVE SIDEBAR TOGGLER -->

        <div class="ks-navbar-logo">
            <a href="/" class="ks-logo">B2B.<?= strtoupper(\Yii::$app->params['migom_name']) ?></a>
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

                <div class="nav-item ks-notifications">                    
                    <a class="nav-link" href="/order/sms">Мои заказы<span style="color: #e79716;margin: 10px;font-weight: 900;" id="po_notify"></span></a>
                </div>

                <div class="nav-item ks-notifications">
                    <a onclick="show_annotation()" class="nav-link " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Гид по B2B.<?= \Yii::$app->params['migom_name'] ?></a>
                </div>

                <!-- BEGIN NAVBAR NOTIFICATIONS -->
                <div class="nav-item nav-link ks-btn-action">
                    <a class="nav-link " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <?= isset($seller) && $seller->active ? "<span style='padding-top: 7px;' class=\"badge badge-success\">АКТИВНЫЙ</span>" : "<span style='padding-top: 7px;' class=\"badge badge-danger\">ОТКЛЮЧЕН</span>"; ?>
                    </a>
                </div>
                <!-- END NAVBAR NOTIFICATIONS -->

                <?php if ($seller->pay_type == 'fixed'): ?>
                    <div class="nav-item nav-link btn-action-block">
                        <a class="btn" href="/balance/add">
                            <span class="ks-action" style="margin-top: -5px;">Баланс <?= isset($bill_account) ? round($bill_account->balance,2) : 0; ?> <?= Yii::$app->params['currency'] ?><br>
                             <?= isset($bill_account) ? round($bill_account->getDayDownCatalog()*30, 2) : 0 ?>/месяц (<?= isset($bill_account) ? round($bill_account->getDayDownCatalog(), 2) : 0 ?>/день) </span>
                            <span class="ks-description">Бонус  <?= isset($bonus_account_id) ? round($bonus_account_id->balance,2) : 0 ?> <?= Yii::$app->params['currency'] ?></span>
                        </a>
                    </div>
                <?php else: ?>
                    <?php

                        $sql = "select bct.id, cost_click from seller_click_tarif as st, bill_click_tarif as bct
                        where st.seller_id = {$seller_id} and bct.id = st.bill_click_tarif_id ORDER BY st.inserted_at desc LIMIT 1;";
                            $res = \Yii::$app->db->createCommand($sql)->queryOne();
                            if ($res['id'] == 1){
                                $balance_clicks = "";
                                $balance_text = "Стоимость клика: 0.4 " . Yii::$app->params['currency'];
                            } else {
                                $balance_clicks = $bill_account->balance_clicks;
                                $balance_text = "Баланс показов: {$balance_clicks}";
                            }


                    ?>
                    <div class="nav-item nav-link btn-action-block">
                        <a class="btn" href="/balance/add">
                            <span class="ks-action" style="margin-top: -5px;">Баланс <?= isset($bill_account) ? round($bill_account->balance,2) : 0; ?> <?= Yii::$app->params['currency']?> <br>
                             ( <?= isset($balance_text) ? $balance_text : ""  ?>)
                            </span>
                            <span class="ks-description">Бонус  <?= isset($bonus_account_id) ? round($bonus_account_id->balance,2) : 0 ?> </span>
                        </a>
                    </div>
                <?php endif; ?>


                <div class="nav-item nav-link btn-action-block">
                    <?= $activation_button ?>
                </div>


                <!-- BEGIN NAVBAR MESSAGES -->
				<?= $this->render('/support_info/staff.php') ?>
                
                <!-- END NAVBAR MESSAGES -->

                <div class="nav-item dropdown ks-user">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span>

                        </span>
                        <span class="ks-info">
                            <span class="ks-name"><?= isset($seller) && $seller->name ? $seller->name : ""; ?></span>
                            <span class="ks-description">ID: <?= isset($seller) && $seller->id ? $seller->id : ""; ?></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Preview">
                        <a class="dropdown-item" href="/site/logout">
                            <span class="la la-sign-out ks-icon" aria-hidden="true"></span>
                            <span>Выйти</span>
                        </a>
                    </div>
                </div>

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
                        <span class="ks-icon la la-bullhorn" id="po_cnt"></span>
                        <span>Продвижение магазина</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/auction">Аукционы</a>
                        <a class="dropdown-item" href="/spec">Спецпредложения</a>
                        <!--a class="dropdown-item" href="/context-adv">Контекстная реклама</a-->
                        <a class="dropdown-item" href="/order/sms">Мои заказы<span style="color: #e79716;margin: 10px;font-weight: 900;" id="po_notify"></span></a>
                        <a class="dropdown-item" href="/info/?page=my_news_articles">Мои новости и обзоры</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-cogs"></span>
                        <span>Настройка магазина</span>
                    </a>
                    <div class="dropdown-menu">
                        <!--a class="dropdown-item" href="/tariff<?= ($seller->pay_type == 'fixed') ? '' : '/click' ?>">Мой тариф</a-->
                        <a class="dropdown-item" href="/settings/user-info">Информация для покупателей</a>
                        <a class="dropdown-item" href="/settings">Реквизиты магазина</a>
                        <a class="dropdown-item" href="/selller/delivery">Условия доставки</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-shopping-cart"></span>
                        <span>Мои товары</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?= ($seller->pay_type == 'fixed') ? '/product/on-sale' : '/tariff/click' ?>">Товары в продаже</a>
                        <a class="dropdown-item" href="/product/price">Импорт/экспорт прайсов</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-bar-chart-o"></span>
                        <span>Аналитика</span>
                    </a>
                    <div class="dropdown-menu">
                        <?php if ($seller->getFlag('stat')): ?>
                            <a class="dropdown-item" href="/statistic">Статистика магазина</a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="/statistic/cost-analysis">Анализ цен конкурентов</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-comment-o" id="review_owner"></span>
                        <span>Отзывы на магазин</span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/reviews"> Отзывы покупателей <span style="color: #e79716;margin: 10px;font-weight: 900;" id="review_notify"></span></a>
                        <a class="dropdown-item" href="/reviews/complaint">Жалобы покупателей <span style="color: #e79716;margin: 10px;font-weight: 900;" id="complaint_notify"></span></a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="ks-icon la la-file-pdf-o"></span>
                        <span>Условия работы</span>
                    </a>
                    <div class="dropdown-menu">
                        <?php if ($seller->f_offerta & 1): ?>
                            <!--<a class="dropdown-item" target="_blank" href="<?= \Yii::$app->params['STATIC_URL_FULL'] ?>/files/Dogovor-oferty.pdf">Договор публичной<br> оферты (с НДС)</a> -->
							<a class="dropdown-item" href="https://www.maxi.by/page/public-contract/" target="blank">Публичный&nbsp;договор</a>
                        <?php endif; ?>
                        <?php if ($seller->f_offerta & 2): ?>
                            <!--<a class="dropdown-item" target="_blank" href="<?= \Yii::$app->params['STATIC_URL_FULL'] ?>/files/Dogovor-oferty-bez-nds.pdf">Договор публичной <br>оферты (без НДС)</a>-->
							<a class="dropdown-item" href="https://www.maxi.by/page/public-contract/" target="blank">Публичный&nbsp;договор</a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="/info/?page=rules_placement">Правила размещения</a>
                        <a class="dropdown-item" href="<?= \Yii::$app->params['STATIC_URL_FULL'] ?>/files/assignmet_contract.docx">Договор передачи <br>прав магазина</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/news">
                        <span class="ks-icon la la-newspaper-o"></span>
                        <span>Новости</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-remote="/site/ask" data-toggle="ajaxModal"
                       data-target=".bd-example-modal-md">
                        <span class="ks-icon la la-question-circle"></span>
                        <span>Обратная связь</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="http://b2bmigomby.reformal.ru/" target="_blank">
                        <span class="ks-icon la la-lightbulb-o"></span>
                        <span>Ваши предложения</span>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="/site/logout">
                        <span class="la la-sign-out ks-icon"></span>
                        <span>Выйти</span>
                    </a>
                </li>
            </ul>
            <div class="ks-sidebar-extras-block">
                <div class="ks-extras-block-item"><?= isset($this->params['customParam']) ? $this->params['customParam'] : ""; ?></div>
                <div class="ks-sidebar-copyright">© <?= date("Y"); ?> <?= strtolower(\Yii::$app->params['migom_name']) ?></div>
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

        <div class="ks-page-content" >

                        <?= $content;?>


        </div>
    </div>
</div>
<div id="modal-div"></div>


<div class="ks-mobile-overlay"></div>

<div id="defaultModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="defaultModalHeader"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la la-close"></span>
                </button>
            </div>
            <div class="modal-body" id="defaultModalBody" style="overflow: auto">

            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
<div name="chat">
<?= $this->render('_bitrix') ?>
</div>
</body>
</html>
<?php $this->endPage() ?>
