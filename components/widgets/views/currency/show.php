<div class="card ks-widget-payment-price-ratio ks-<?= $res['perc'] > 0 ? 'green' : 'purple' ?>" style="height: 100%;">
    <div class="ks-price-ratio-title">
        Курс НБРБ (USD)
    </div>
    <div class="ks-price-ratio-amount"><?= $res['rate'] ?></div>
    <div class="ks-price-ratio-progress">
        <span class="ks-icon ks-icon-circled-<?= $res['perc'] > 0 ? 'up-right' : 'down-left' ?>"></span>
        <div class="ks-text"><?= $res['perc'] ?>%</div>
    </div>
</div>