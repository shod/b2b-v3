<div class="card ks-card-widget ks-widget-payment-total-amount ks-green-light" style="height: 100%;">
    <h5 class="card-header">
        Товары в продаже

        <div class="dropdown ks-control">
            <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <span class="ks-icon la la-ellipsis-h"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                <a class="dropdown-item" href="/product/on-sale">Мои товары</a>
                <a class="dropdown-item" href="/tariff">Мой тариф</a>
            </div>
        </div>
    </h5>
    <div class="card-block">

        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount"><?= $cnt_all ?></span>
            </div>
            <div class="ks-payment-total-amount-item-description">
                Всего товаров
            </div>
        </div>
        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount"><?= $active_percent ?>%</span>
            </div>
            <div class="ks-payment-total-amount-item-description">
                Процент активных
            </div>
        </div>
    </div>
</div>