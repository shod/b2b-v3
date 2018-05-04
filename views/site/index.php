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

                <div class="col-xl-4 col-lg-4 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'balance', 'sid' => $sid]) ?>"
                     data-toggle="ajaxWidget">
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'products', 'sid' => $sid]) ?>"
                     data-toggle="ajaxWidget">
                </div>

                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'promotion', 'sid' => $sid]) ?>"
                     data-toggle="ajaxWidget">
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card card-block">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'actions', 'sid' => $sid]) ?>"
                     data-toggle="ajaxWidget">

                </div>
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'reviews', 'sid' => $sid]) ?>"
                     data-toggle="ajaxWidget">

                </div>
                <div class="col-xl-4 col-lg-4" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'complaint', 'sid' => $sid]) ?>"
                     data-toggle="ajaxWidget">

                </div>
            </div>

            <div class="row">

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="card ks-widget-payment-price-ratio ks-purple" style="height: 100%;">
                        <div class="ks-price-ratio-title">
                            Обещанный платеж
                        </div>
                        <div class="ks-price-ratio-amount">5 ТЕ</div>
                        <div class="ks-price-ratio-progress">
                            <div class="ks-text">до 24.05.17</div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="card ks-card-widget ks-widget-payment-total-amount ks-green-light" style="height: 100%;">
                        <h5 class="card-header">
                            Цены на ваши товары

                            <div class="dropdown ks-control">
                                <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <span class="ks-icon la la-ellipsis-h"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="#">Мои товары</a>
                                    <a class="dropdown-item" href="#">Анализ цен</a>
                                </div>
                            </div>
                        </h5>
                        <div class="card-block">

                            <div class="ks-payment-total-amount-item-body">
                                <div class="ks-payment-total-amount-item-amount">
                                    <span class="ks-amount" style="color: #007a05;">42 %</span>
                                </div>
                                <div class="ks-payment-total-amount-item-description">
                                    самая низкая цена
                                </div>
                            </div>
                            <div class="ks-payment-total-amount-item-body">
                                <div class="ks-payment-total-amount-item-amount">
                                    <span class="ks-amount" style="color: #d52626;">17 %</span>
                                </div>
                                <div class="ks-payment-total-amount-item-description">
                                    самая высокая цена
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                <div class="col-xl-4 col-lg-4">

                    <div class="card ks-card-widget ks-widget-payment-card-rate-details" style="height: 100%;">
                        <h5 class="card-header">
                            Аукционы
                        </h5>
                        <div class="card-block">
                            <div class="ks-card-widget-datetime">
                                Фиксированная ставка
                            </div>

                            <table class="table ks-payment-card-rate-details-table">
                                <tbody>
                                <tr>
                                    <td class="ks-currency">
                                        Водонагреватели (бойлеры)
                                    </td>
                                    <td class="ks-amount" style="width: 100px;">4 место</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Мобильные телефоны
                                    </td>
                                    <td class="ks-amount" style="width: 100px;">1 место</td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <a href="#" class="btn btn-primary ks-light">Перейти к аукционам</a>
                        </div>
                    </div>

                </div>


                <div class="col-xl-4 col-lg-4">

                    <div class="card ks-card-widget ks-widget-payment-card-rate-details" style="height: 100%;">
                        <h5 class="card-header">
                            Аукционы
                        </h5>
                        <div class="card-block">
                            <div class="ks-card-widget-datetime">
                                Онлайн ставка
                            </div>

                            <table class="table ks-payment-card-rate-details-table">
                                <tbody>
                                <tr>
                                    <td class="ks-currency">
                                        Автоусилители
                                    </td>
                                    <td class="ks-amount" style="width: 100px;">1 место</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Другие товары
                                    </td>
                                    <td class="ks-amount" style="width: 100px;">12 место</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Маникюрные наборы
                                    </td>
                                    <td class="ks-amount" style="width: 100px;">4 место</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Электробигуди
                                    </td>
                                    <td class="ks-amount" style="width: 100px;">5 место</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Яйцеварки
                                    </td>
                                    <td class="ks-amount" style="width: 100px;">2 место</td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <a href="#" class="btn btn-primary ks-light">Перейти к аукционам</a>
                        </div>
                    </div>

                </div>


                <div class="col-xl-4 col-lg-4">

                    <div class="card ks-card-widget ks-widget-payment-budget" style="height: 100%;">
                        <a class="card-header">Изменение тарифов</a>
                        <div class="ks-card-widget-datetime">Дата <span class="ks-text-bold">2018.04.15</span>
                        </div>
                        <div style="margin-left: 20px;margin-right: 20px;">
                            <p>Уважаемые партнеры!<br><br>Мы изменили <a
                                        href="https://b2b.migom.by/?admin=products&amp;&amp;action=bill_catalog_new"
                                        target="_blank">тарифы</a> на размещение.<br><br>Тарифные планы стали еще
                                выгоднее:</p>
                            <ul>
                                <li>еще больше разделов в тарифных пакетах;</li>
                                <li>еще больше доступных товаров;</li>
                                <li>меньше затрат на рекламу;</li>
                                <li>еще больше покупателей!</li>
                            </ul>
                            <p><br>Вам необходимо до <span style="color: #ff0000;">15 мая 2018 г.</span> выбрать
                                подходящий <a
                                        href="https://b2b.migom.by/?admin=products&amp;&amp;action=bill_catalog_new"
                                        target="_blank">тарифный пакет</a> и перейти на него.<br><br>Старые тарифные
                                пакеты действуют до 15 мая 2018 г.<br><br>Будем рады получить от Вас обратную связь и
                                ответим на любые вопросы по тел.&nbsp; +375 29 111-45-<br>45 (Velcom) +375 29 777-45- 45
                                (МТС) и еmail:&nbsp;<a href="mailto:sale@migom.by" target="_blank">sale@migom.by</a></p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

