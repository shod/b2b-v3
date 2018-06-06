<?php
$this->title = "Пополнение баланса";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <h3 class="ks-payment-earnings-amount">1ТЕ = <span id="te"><?= $curs; ?></span> руб.
                                <span class="ks-description small">
                                    без учета НДС 20%
                                </span>
                            </h3>
                            <h3 class="ks-payment-earnings-amount">1ТЕ = <span id="te"><?= $curs * 1,2; ?></span> руб.
                                <span class="ks-description small">
                                с учетом НДС 20%
                                </span>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <!--h5 class="card-title">Информация о балансе</h5-->
                            <?= $info; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ks-pricing-subscriptions-page">
                <div class="ks-header">
                    <h3 class="ks-name">Пополнить баланс</h3>
                    <div class="ks-description">
                        * Первый платеж магазина должен быть не меньше 1 месяца размещения в
                        эквиваленте. Иначе магазин не подключится!
                    </div>
                    <div class="ks-description">
                        ** При первом подключении магазина к торговому порталу MIGOM.by при стоимости размещения менее или равно 49 ТЕ в месяц,
                        единоразовый платеж должен быть равен или более 50 ТЕ.
                    </div>
                    <div class="ks-description">
                        *** Если ваш магазин не был активен более 90 дней, то для повторного включения сумма оплаты должна
                        быть не менее суммы месячного размещения согласно вашего тарифа.
                    </div>
                </div>
                <div class="ks-subscriptions" id="subscriptions">

                    <?= isset($choise) ? $choise : ""; ?>

                    <?= $blanks; ?>

                </div>
            </div>
        </div>
    </div>
</div>

