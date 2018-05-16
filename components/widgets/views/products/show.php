<div class="card ks-card-widget ks-widget-payment-total-amount ks-green-light" style="height: 100%;">
    <h5 class="card-header" style="padding-bottom: 5px;">
        Товары в продаже
    </h5>
    <div class="card-block" style="padding-bottom: 5px; padding-top: 0px;">

        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="font-size: 25px;"><?= $cnt_all ?></span>
            </div>
            <div class="ks-payment-total-amount-item-description" style="font-size: 8px;">
                Всего товаров
            </div>
        </div>
        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="font-size: 25px;"><?= $active_percent ?>%</span>
            </div>
            <div class="ks-payment-total-amount-item-description" style="font-size: 8px;">
                Процент активных
            </div>
        </div>
        <div style="width: 100%; text-align: center; padding-top: 10px;"><a href="/tariff" class="btn btn-primary ks-light btn-sm">Мой тариф</a></div>

    </div>
    <h5 class="card-header">
        Цены на ваши товары
    </h5>
    <div class="card-block">

        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="font-size: 28px; color: #007a05;"><?= $cost_min ?>%</span>
            </div>
            <div class="ks-payment-total-amount-item-description" style="font-size: 8px;">
                самая низкая цена
            </div>
        </div>
        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="font-size: 28px; color: #d52626;"><?= $cost_max ?>%</span>
            </div>
            <div class="ks-payment-total-amount-item-description" style="font-size: 8px;">
                самая высокая цена
            </div>
        </div>
        <div style="width: 100%; text-align: center; padding-top: 10px;"><a href="/statistic/cost-analysis" class="btn btn-primary ks-light btn-sm">Анализ цен</a></div>
    </div>
</div>