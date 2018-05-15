<div class="card ks-card-widget ks-widget-payment-total-amount ks-purple-light" >
    <h5 class="card-header">
        Баланс
        <div class="dropdown ks-control">
            <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <span class="ks-icon la la-ellipsis-h"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                <a class="dropdown-item" href="/balance/add">Пополнить баланс</a>
            </div>
        </div>
    </h5>
    <div class="card-block">
        <div class="ks-payment-total-amount-item-icon-block">
            <span class="la la-money la-3x"></span>
        </div>

        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="color: #25628f"><?= $balance == 0 ? '0' : $balance ?> TE</span>
            </div>
            <div class="ks-payment-total-amount-item-description">
                Бонус <?= $bonus ?> ТЕ
            </div>
        </div>
    </div>
</div>