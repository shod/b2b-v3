<div class="card ks-card-widget ks-widget-payment-total-amount ks-purple-light" >
    <h5 class="card-header">
        Баланс
    </h5>
    <div class="card-block">
        <div class="ks-payment-total-amount-item-icon-block">
            <span class="la la-money la-3x"></span>
        </div>

        <div class="ks-payment-total-amount-item-body">
            <div class="ks-payment-total-amount-item-amount">
                <span class="ks-amount" style="color: #25628f"><?= $balance == 0 ? '0' : $balance ?> <?= Yii::$app->params['currency'];?></span>
            </div>
            <div class="ks-payment-total-amount-item-description" style="font-size: 15px;">
                Бонус <?= $bonus ?> <?= Yii::$app->params['currency'];?>
            </div>
        </div>

        <a class="btn btn-primary ks-light" style="width: 100%; margin: 8px;" href="/balance/add">Пополнить баланс</a>
    </div>
</div>