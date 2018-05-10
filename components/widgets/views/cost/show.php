<div class="card ks-card-widget ks-widget-payment-total-amount ks-green-light" style="height: 100%;">
    <h5 class="card-header">
        Цены на ваши товары

        <div class="dropdown ks-control">
            <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <span class="ks-icon la la-ellipsis-h"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                <a class="dropdown-item" href="/product/on-sale">Мои товары</a>
                <a class="dropdown-item" href="/statistic/cost-analysis">Анализ цен</a>
            </div>
        </div>
    </h5>
    <div class="card-block" id="container">

        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="color: #007a05;"><?= $cost_min ?> %</span>
            </div>
            <div class="ks-payment-total-amount-item-description">
                самая низкая цена
            </div>
        </div>
        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="color: #d52626;"><?= $cost_max ?> %</span>
            </div>
            <div class="ks-payment-total-amount-item-description">
                самая высокая цена
            </div>
        </div>
    </div>
</div>
