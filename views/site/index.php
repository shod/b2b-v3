<?php
$this->title = "Добро пожаловать в b2b.migom.by!";
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

                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="card ks-card-widget ks-widget-payment-total-amount ks-purple-light" style="height: 100%;">
                        <h5 class="card-header">
                            Баланс

                            <div class="dropdown ks-control">
                                <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <span class="ks-icon la la-ellipsis-h"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="#">Пополнить баланс</a>
                                </div>
                            </div>
                        </h5>
                        <div class="card-block">
                            <div class="ks-payment-total-amount-item-icon-block">
                                <span class="la la-info-circle la-3x"></span>
                            </div>

                            <div class="ks-payment-total-amount-item-body">
                                <div class="ks-payment-total-amount-item-amount">
                                    <span class="ks-amount">-207.3 TE</span>
                                </div>
                                <div class="ks-payment-total-amount-item-description">
                                    Бонус 10.4 ТЕ
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="card ks-card-widget ks-widget-payment-total-amount ks-green-light" style="height: 100%;">
                        <h5 class="card-header">
                            Товары в продаже

                            <div class="dropdown ks-control">
                                <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <span class="ks-icon la la-ellipsis-h"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="#">Мои товары</a>
                                    <a class="dropdown-item" href="#">Мой тариф</a>
                                </div>
                            </div>
                        </h5>
                        <div class="card-block">

                            <div class="ks-payment-total-amount-item-body">
                                <div class="ks-payment-total-amount-item-amount">
                                    <span class="ks-amount">86162</span>
                                </div>
                                <div class="ks-payment-total-amount-item-description">
                                    Всего товаров
                                </div>
                            </div>
                            <div class="ks-payment-total-amount-item-body">
                                <div class="ks-payment-total-amount-item-amount">
                                    <span class="ks-amount">100%</span>
                                </div>
                                <div class="ks-payment-total-amount-item-description">
                                    Процент активных
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4">
                    <div class="card ks-card-widget ks-widget-tasks-statuses-progress" style="height: 100%;">
                        <h5 class="card-header">
                            Использование продвижения

                            <div class="dropdown ks-control">
                                <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <span class="ks-icon la la-ellipsis-h"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="#">Размещение товаров</a>
                                    <a class="dropdown-item" href="#">Аукционы</a>
                                    <a class="dropdown-item" href="#">Спецпредложения</a>
                                    <a class="dropdown-item" href="#">Баннерные спецпредложения</a>
                                    <a class="dropdown-item" href="#">Обратный звонок</a>
                                    <a class="dropdown-item" href="#">Отсутствие жалоб на недоступность телефонов</a>
                                    <a class="dropdown-item" href="#">Контекстная реклама в Яндекс и Google</a>
                                </div>
                            </div>
                        </h5>
                        <div class="card-block">

                            <div class="tasks-total-statuses-progress">
                                <div class="progress ks-progress-xs">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 79%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ks-amount">79%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4">
                    <div class="card ks-card-widget ks-widget-payment-table-invoicing" style="height: 100%;">
                        <h5 class="card-header">
                            Подключенные пакеты по акции
                        </h5>
                        <div class="card-block">
                            <table class="table ks-payment-table-invoicing">
                                <tbody>
                                <tr>
                                    <td>Детские товары</td>
                                    <td><mark>до 05.08.2018</mark></td>
                                </tr>
                                <tr>
                                    <td>Крупная бытовая техника</td>
                                    <td><mark>до 05.08.2018</mark></td>
                                </tr>
                                <tr>
                                    <td>Другие товары</td>
                                    <td><mark>до 05.08.2018</mark></td>
                                </tr>
                                <tr>
                                    <td>Спорт</td>
                                    <td><mark>до 05.08.2018</mark></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4">
                    <div class="card ks-card-widget ks-widget-payment-table-invoicing" style="height: 100%;">
                        <h5 class="card-header">
                            Последний отзыв
                        </h5>
                        <div class="card-block">
                            <mark>Не очень хорошего качества!</mark>
                            <p>
                                Здравствуйте, купил велосипед Аист Fly26-670, товар доставили курьером по заявленной цене и вовремя, как и договаривались - это плюс!
                                Велик привезли, но чек не дали. Когда я распаковал коробку, то увидел, что руль был полностью ржавый и краска на нем слезла! Придется самому разбирать и красить руль! Для меня не проблема минус 200 тыс., но все равно неприятно, когда ты его купил в подарок! При заказе менеджер был вежливым, но не очень хорошо знал особенности товара! И самое главное то, что я просил велосипед для взрослого человека, но мне привезли почему-то подростковый, самый маленький во всей этой серии!</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4">
                    <div class="card ks-card-widget ks-widget-payment-table-invoicing" style="height: 100%;">
                        <h5 class="card-header">
                            Жалобы
                        </h5>
                        <div class="card-block">
                            <table class="table ks-payment-table-invoicing">
                                <tbody>
                                <tr>
                                    <td><span class="badge badge-default">05-02 01:17:30</span></td>
                                    <td>сайт : belpro.by</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-default">05-01 13:50:02</span></td>
                                    <td>375 29 957-55-68</td>
                                </tr>
                                <tr>
                                    <td><span class="badge badge-default">05-01 13:49:10</span></td>
                                    <td>375 29 957-55-68</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                    <span class="ks-amount">42 %</span>
                                </div>
                                <div class="ks-payment-total-amount-item-description">
                                    самая низкая цена
                                </div>
                            </div>
                            <div class="ks-payment-total-amount-item-body">
                                <div class="ks-payment-total-amount-item-amount">
                                    <span class="ks-amount">17 %</span>
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
                                    <td class="ks-amount">4</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Мобильные телефоны
                                    </td>
                                    <td class="ks-amount">1</td>
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
                                    <td class="ks-amount">1</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Другие товары
                                    </td>
                                    <td class="ks-amount">2</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Маникюрные наборы
                                    </td>
                                    <td class="ks-amount">4</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Электробигуди
                                    </td>
                                    <td class="ks-amount">5</td>
                                </tr>
                                <tr>
                                    <td class="ks-currency">
                                        Яйцеварки
                                    </td>
                                    <td class="ks-amount">2</td>
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
        <div class="ks-dashboard-tabbed-sidebar-sidebar">
            <div class="ks-tabs-container ks-tabs-default ks-tabs-with-separator ks-tabs-header-default ks-tabs-info">
                <ul class="nav ks-nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-toggle="tab" data-target="#tabbed-sidebar-activity">
                                        <span class="ks-icon la la-flash">
                                            <span class="ks-amount">3</span>
                                        </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="tab" data-target="#tabbed-sidebar-comments">
                                        <span class="ks-icon la la-comments">
                                            <span class="ks-amount">1</span>
                                        </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="tab" data-target="#tabbed-sidebar-posts">
                                        <span class="ks-icon la la-book">
                                            <span class="ks-amount">6</span>
                                        </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="tab" data-target="#tabbed-sidebar-favourites">
                                        <span class="ks-icon la la-star-o">
                                            <span class="ks-amount">4</span>
                                        </span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tabbed-sidebar-activity" role="tabpanel">
                        <div class="tab-pane-content">
                            <div class="ks-tabbed-sidebar-activity">
                                <div class="ks-tabbed-sidebar-tab-content-header">
                                    <h5>Activity</h5>
                                    <div class="input-icon icon-right icon icon-lg icon-color-primary">
                                        <input id="input-group-icon-text" type="text" class="form-control"
                                               placeholder="Search">
                                        <span class="icon-addon">
                                                        <span class="la la-search"></span>
                                                    </span>
                                    </div>
                                </div>

                                <div class="ks-tabbed-sidebar-activity-items ks-scrollable" data-auto-height=""
                                     style="height: 884px; overflow: hidden; padding: 0px; width: 393px;">


                                    <div class="jspContainer" style="width: 393px; height: 884px;">
                                        <div class="jspPane" style="padding: 0px; top: 0px; left: 0px; width: 393px;">
                                            <div class="ks-tabbed-sidebar-activity-item ks-activity-item-status-active">
                                                <div class="ks-action-wrapper">
                                                    <img src="assets/img/avatars/avatar-6.jpg" alt=""
                                                         class="ks-avatar rounded-circle" width="25" height="25">
                                                    <a href="#" class="ks-action-message">Hi, What you think about new
                                                        deal</a>
                                                </div>
                                                <span class="badge badge-primary ks-sm">New</span>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item ks-activity-item-status-active">
                                                <div class="ks-action-wrapper">
                                                    <img src="assets/img/avatars/avatar-11.jpg" alt=""
                                                         class="ks-avatar rounded-circle" width="25" height="25">
                                                    <a href="#" class="ks-action-message">Hi, What you think about new
                                                        deal</a>
                                                </div>
                                                <span class="badge badge-success ks-sm">Completed</span>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item ks-activity-item-status-active">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-star ks-color-warning ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">GOOG:US, 300 @ 145.32
                                                        opportunity</a>
                                                </div>
                                                <span class="badge badge-danger ks-sm">Canceled</span>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-check ks-color-success ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Filled — Forson Inc. — 300 —
                                                        $5,600</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-check ks-color-success ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Filled — Marta Skyson — 2000 —
                                                        $34,600</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                    <img src="assets/img/avatars/avatar-5.jpg" alt=""
                                                         class="ks-avatar rounded-circle" width="25" height="25">
                                                    <a href="#" class="ks-action-message">Hi, What you think about new
                                                        deal</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-flash ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Gold (-1,22%), estimated 3%
                                                        loss</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-star ks-color-warning ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Samsung Note 7 Users Urged to
                                                        Turn Phone</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-plus-circle ks-color-info ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">New — Shell Inc. — 120 —
                                                        $5600</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                    <img src="assets/img/avatars/avatar-2.jpg" alt=""
                                                         class="ks-avatar rounded-circle" width="25" height="25">
                                                    <a href="#" class="ks-action-message">Hi Konstantin, Sent you
                                                        quarter report</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-star ks-color-pink ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Asset Reporting Lored</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-archive ks-color-gray ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Gold (-1,22%), estimated 3%
                                                        loss</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-calendar ks-color-info ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Gold (-1,22%), estimated 3%
                                                        loss</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-plus-circle ks-color-info ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">New — Shell Inc. — 120 —
                                                        $5600</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                    <img src="assets/img/avatars/avatar-3.jpg" alt=""
                                                         class="ks-avatar rounded-circle" width="25" height="25">
                                                    <a href="#" class="ks-action-message">Hi Konstantin, Sent you
                                                        quarter report</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-star ks-color-pink ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Asset Reporting Lored</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-archive ks-color-gray ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Gold (-1,22%), estimated 3%
                                                        loss</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-calendar ks-color-info ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Gold (-1,22%), estimated 3%
                                                        loss</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-plus-circle ks-color-success ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">New — Shell Inc. — 120 —
                                                        $5600</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                    <img src="assets/img/avatars/avatar-8.jpg" alt=""
                                                         class="ks-avatar rounded-circle" width="25" height="25">
                                                    <a href="#" class="ks-action-message">Hi Konstantin, Sent you
                                                        quarter report</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-bookmark-o ks-color-danger ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Asset Reporting Lored</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-archive ks-color-gray ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Gold (-1,22%), estimated 3%
                                                        loss</a>
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-activity-item">
                                                <div class="ks-action-wrapper">
                                                        <span class="ks-action-icon">
                                                            <span class="la la-calendar ks-color-info ks-icon"></span>
                                                        </span>
                                                    <a href="#" class="ks-action-message">Gold (-1,22%), estimated 3%
                                                        loss</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabbed-sidebar-comments" role="tabpanel">
                        <div class="tab-pane-content">
                            <div class="ks-tabbed-sidebar-comments">
                                <div class="ks-tabbed-sidebar-tab-content-header">
                                    <h5>Comments</h5>
                                    <div class="input-icon icon-right icon icon-lg icon-color-primary">
                                        <input type="text" class="form-control" placeholder="Search">
                                        <span class="icon-addon">
                                                        <span class="la la-search"></span>
                                                    </span>
                                    </div>
                                </div>

                                <div class="ks-tabbed-sidebar-comment-items ks-scrollable" data-auto-height=""
                                     style="height: 1134px; overflow: hidden; padding: 0px; width: 0px;">


                                    <div class="jspContainer" style="width: 0px; height: 1134px;">
                                        <div class="jspPane" style="padding: 0px; top: 0px; left: 0px; width: 100px;">
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-3.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Matthew Arnold</a>
                                                        <span class="ks-description">added a new task to the project <a
                                                                    href="#" class="ks-color-info">Website redesign</a></span>

                                                        <div class="ks-datetime">
                                                            September 18, 2016 at 12:38 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-4.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Rachel Matthews</a>
                                                        <span class="ks-description">leave a comment <a href="#"
                                                                                                        class="ks-color-info">Lake Hall Beer and Pizza</a></span>

                                                        <div class="ks-datetime">
                                                            September 26, 2016 at 19:25 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    It had to end sometime. Apple’s incredible growth that saw the
                                                    company report record quarterly earnings over a span of 13 years was
                                                    untenable.
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-10.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Marilyn Fox</a>
                                                        <span class="ks-description">leave a comment <a href="#"
                                                                                                        class="ks-color-info">Sample Post</a></span>

                                                        <div class="ks-datetime">
                                                            September 17, 2016 at 11:00 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-3.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Matthew Arnold</a>
                                                        <span class="ks-description">added a new task to the project <a
                                                                    href="#" class="ks-color-info">Website redesign</a></span>

                                                        <div class="ks-datetime">
                                                            September 18, 2016 at 12:38 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-3.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Matthew Arnold</a>
                                                        <span class="ks-description">added a new task to the project <a
                                                                    href="#" class="ks-color-info">Website redesign</a></span>

                                                        <div class="ks-datetime">
                                                            September 18, 2016 at 12:38 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-3.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Matthew Arnold</a>
                                                        <span class="ks-description">added a new task to the project <a
                                                                    href="#" class="ks-color-info">Website redesign</a></span>

                                                        <div class="ks-datetime">
                                                            September 18, 2016 at 12:38 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-3.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Matthew Arnold</a>
                                                        <span class="ks-description">added a new task to the project <a
                                                                    href="#" class="ks-color-info">Website redesign</a></span>

                                                        <div class="ks-datetime">
                                                            September 18, 2016 at 12:38 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-3.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Matthew Arnold</a>
                                                        <span class="ks-description">added a new task to the project <a
                                                                    href="#" class="ks-color-info">Website redesign</a></span>

                                                        <div class="ks-datetime">
                                                            September 18, 2016 at 12:38 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                            <div class="ks-tabbed-sidebar-comment-item">
                                                <div class="ks-tabbed-sidebar-comment-action">
                                                    <img src="assets/img/avatars/avatar-3.jpg" class="ks-avatar"
                                                         width="25" height="25">
                                                    <div class="ks-action">
                                                        <a href="#" class="ks-name">Matthew Arnold</a>
                                                        <span class="ks-description">added a new task to the project <a
                                                                    href="#" class="ks-color-info">Website redesign</a></span>

                                                        <div class="ks-datetime">
                                                            September 18, 2016 at 12:38 PM
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ks-tabbed-sidebar-comment-item-message">
                                                    Perhaps you'll take me out one day - or do I have to make an
                                                    appointment?
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tabbed-sidebar-posts" role="tabpanel">
                        <div class="tab-pane-content">
                            <div class="ks-tabbed-sidebar-posts">
                                <div class="ks-tabbed-sidebar-tab-content-header">
                                    <h5>Posts</h5>
                                    <div class="input-icon icon-right icon icon-lg icon-color-primary">
                                        <input type="text" class="form-control" placeholder="Search">
                                        <span class="icon-addon">
                                                        <span class="la la-search"></span>
                                                    </span>
                                    </div>
                                </div>

                                <div class="ks-tabbed-sidebar-post-items ks-scrollable" data-auto-height=""
                                     style="height: 1134px; overflow: hidden; padding: 0px; width: 0px;">


                                    <div class="jspContainer" style="width: 0px; height: 1134px;">
                                        <div class="jspPane" style="padding: 0px; top: 0px; left: 0px; width: 100px;"><a
                                                    href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-1.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a><a href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-2.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a><a href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-3.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a><a href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-4.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a><a href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-5.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a><a href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-1.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a><a href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-1.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a><a href="#" class="ks-tabbed-sidebar-post-item">
                                                <img src="assets/img/thumbs/ph-1.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>
                                            </a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tabbed-sidebar-favourites" role="tabpanel">
                        <div class="tab-pane-content">
                            <div class="ks-tabbed-sidebar-favourites">
                                <div class="ks-tabbed-sidebar-tab-content-header">
                                    <h5>Favourites</h5>
                                    <div class="input-icon icon-right icon icon-lg icon-color-primary">
                                        <input type="text" class="form-control" placeholder="Search">
                                        <span class="icon-addon">
                                                        <span class="la la-search"></span>
                                                    </span>
                                    </div>
                                </div>

                                <div class="ks-tabbed-sidebar-favourites-items ks-scrollable" data-auto-height=""
                                     style="height: 1134px; overflow: hidden; padding: 0px; width: 0px;">


                                    <div class="jspContainer" style="width: 0px; height: 1134px;">
                                        <div class="jspPane" style="padding: 0px; top: 0px; left: 0px; width: 100px;"><a
                                                    href="#" class="ks-tabbed-sidebar-favourite-item">
                                                <img src="assets/img/thumbs/ph-1.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon ks-action-file">
                                                        <span class="la la-file-word-o ks-color-info ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">annual_report_2016.docx</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">156 KB</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon ks-action-file">
                                                        <span class="la la-file-pdf-o ks-color-danger ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">certificate.pdf</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">88 KB</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                <img src="assets/img/avatars/avatar-7.jpg" alt="" class="ks-avatar"
                                                     width="36" height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Barbara Curtis</span>
                                                        <span class="ks-extra-info">Product Manager</span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon">
                                                        <span class="la la-link ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">The Verge</span>
                                                        <span class="ks-extra-info ks-color-info">HTTP://www.theverge.com</span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                <img src="assets/img/thumbs/ph-3.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon ks-action-file">
                                                        <span class="la la-file-word-o ks-color-info ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">annual_report_2016.docx</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">156 KB</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon ks-action-file">
                                                        <span class="la la-file-pdf-o ks-color-danger ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">certificate.pdf</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">88 KB</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                <img src="assets/img/avatars/avatar-7.jpg" alt="" class="ks-avatar"
                                                     width="36" height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Barbara Curtis</span>
                                                        <span class="ks-extra-info">Product Manager</span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon">
                                                        <span class="la la-link ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">The Verge</span>
                                                        <span class="ks-extra-info ks-color-info">HTTP://www.theverge.com</span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                <img src="assets/img/thumbs/ph-2.png" alt="" class="ks-thumb" width="36"
                                                     height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Road trip essentials</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">1200 views</span>
                                                            <span class="ks-amount-block">34 comments</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon ks-action-file">
                                                        <span class="la la-file-word-o ks-color-info ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">annual_report_2016.docx</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">156 KB</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon ks-action-file">
                                                        <span class="la la-file-pdf-o ks-color-danger ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">certificate.pdf</span>
                                                        <span class="ks-extra-info">
                                                            <span class="ks-amount-block">88 KB</span>
                                                        </span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                <img src="assets/img/avatars/avatar-7.jpg" alt="" class="ks-avatar"
                                                     width="36" height="36">
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">Barbara Curtis</span>
                                                        <span class="ks-extra-info">Product Manager</span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a><a href="#" class="ks-tabbed-sidebar-favourite-item">
                                                    <span class="ks-action-icon">
                                                        <span class="la la-link ks-icon"></span>
                                                    </span>
                                                <span href="#" class="ks-description">
                                                        <span class="ks-name">The Verge</span>
                                                        <span class="ks-extra-info ks-color-info">HTTP://www.theverge.com</span>
                                                    </span>

                                                <button class="btn btn-primary-outline ks-light ks-no-text ks-remove">
                                                    <span class="la la-trash-o ks-icon"></span>
                                                </button>
                                            </a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

