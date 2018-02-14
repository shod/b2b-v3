<?php
$this->title = "Пополнение баланса";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <h5 class="card-title">Курс тарифной единицы</h5>
                            <h3 class="ks-payment-earnings-amount">1ТЕ = <span id="te"><?= $curs; ?></span> руб.</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <h5 class="card-title">Информация о балансе</h5>
                            <?= $info; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ks-pricing-subscriptions-page">
                <div class="ks-header">
                    <h3 class="ks-name">Сформировать счет на оплату</h3>
                    <div class="ks-description">* Первый платеж магазина должен быть не меньше 1 месяца размещения в
                        эквиваленте. Иначе магазин не подключится!
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

