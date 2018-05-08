<?php
$this->title = "Добро пожаловать в b2b.migom.by!";
$this->registerJsFile(Yii::$app->request->baseUrl . '/web/scripts/js/charts.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs(
    "
    $(document).ready(function () {
        get_chart('2018-05');
    });
    "
);
?>
<style>
    .done {
        background-color: #b8f8b8
    }

    .transaction {
        background-color: rgba(255, 198, 33, 0.34);
    }
</style>
<!--div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block">
                        <h3>Все страницы для работы</h3>
                        <div class="done">1 -  <a href="/balance/add">Баланс пополнить 10-12 ч</a></div>
                        <div class="done">2 - <a href="/balance/akt">Баланс выгрузка актов 3 ч</a></div>
                        <div class="done">3 - <a href="/order/sms">SMS заказы 6 ч</a></div>
                        <div class="done">4 - <a href="/product/on-sale">Товары в продаже 6 ч</a></div>
                        <div class="done">5 - <a href="/product/catalog">Редактирование товаров в разделе 12 ч</a></div>
                        <div class="transaction">6 - <a href="/statistic/cost-analysis">Анализ цен конкурентов 6 ч</a></div>
                        <div class="done">7 - <a href="/reviews">Отзывы 6 ч</a></div>
                        <div class="done">8 - <a href="/statistic">Статистика 6 ч</a></div>
                        <div class="done">9 - <a href="/statistic/month">Статистика по месяцам 3 ч</a></div>
                        <div class="done">10 - <a href="/product/price">Товары работа с прайсами 6 ч</a></div>
                        <div class="done">11 - <a href="/balance/report">Баласн Финансовый отчет 6 ч</a></div>
                        <div class="transaction">12 - <a href="/balance/promise">Баланс обещаный платех 3 ч</a></div>
                        <div class="done">13 - <a href="/seller/delivery">Продавец информация о доставке 12 ч</a></div>
                        <div class="done">14 - <a href="/context-adv">Контекстная реклама добавление 2 ч</a></div>
                        <div class="done">15 - <a href="/context-adv/blacklist">Контекстная реклама работа с разделами 1 ч</a></div>
                        <div class="done">16 - <a href="/info/?page=rules_placement">Информационная страница 1ч</a></div>
                        <div class="done">17 - <a href="/reviews/complaint">Жалобы покупателей 1ч</a></div>
                        <div class="done">18 - <a href="/spec/add">Спецпредложения добавление 3ч</a></div>
                        <div class="transaction">19 - <a href="/spec">Спецпредложения 4ч</a></div>
                        <div class="done">20 - <a href="/auction/add">Аукционы добавление</a></div>
                        <div class="transaction">21 - <a href="/auction">Аукционы</a></div>
                        <div class="transaction">22 - <a href="/tariff">Калькулятор тарифов</a></div>
                        <div class="done">23 - <a href="/settings/user-info">Продавец информация для пользователей</a></div>
                        <div class="done">24 - <a href="/settings">Продавец настройки</a></div>

                        <div>3 - <a href="/news">Страница новостей</a></div>

                        <div style="background-color: #ffd7d6">5 - <a href="/banner-spec">Баннерные спецпредложения</a></div>
                        <div style="background-color: #ffd7d6">6 - <a href="/banner-spec/add">Баннерные спецпредложения добавление</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div-->

<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'balance', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-4 col-lg-4 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'products', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'promotion', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card card-block">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'actions', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'reviews', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'complaint', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'promise', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'cost', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="card ks-widget-payment-price-ratio ks-green" style="height: 100%;">
                        <div class="ks-price-ratio-title">
                            Курс НБРБ (USD)
                        </div>
                        <div class="ks-price-ratio-amount">2.0082</div>
                        <div class="ks-price-ratio-progress">
                            <span class="ks-icon ks-icon-circled-up-right"></span>
                            <div class="ks-text">0.32%</div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="card ks-widget-weather-and-datetime ks-sunny" style="height: 100%;">
                        <div class="ks-widget-weather-and-datetime-weather-block">
                            <div class="ks-widget-weather-and-datetime-weather-block-amount">
                                +24º
                            </div>
                            <div class="ks-widget-weather-and-datetime-weather-block-type">
                                Солнечно
                            </div>
                        </div>
                        <div class="ks-widget-weather-and-datetime-datetime-block">
                            <div class="ks-widget-weather-and-datetime-datetime-block-datetime">6:18 pm</div>
                            <div class="ks-widget-weather-and-datetime-datetime-block-location">Минск</div>
                            <span class="ks-icon wi wi-day-cloudy"></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'auction', 'sid' => $sid, 'type' => 'fix']) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'auction', 'sid' => $sid, 'type' => 'online']) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'news', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
            </div>

        </div>
    </div>
</div>

