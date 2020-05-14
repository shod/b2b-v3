<div class="ks-subscription" style="margin-right: 15px; margin-left: 15px;">
    <div class="ks-header">
        <span class="ks-name">Своя сумма (<?= \Yii::$app->params['currency'] ?>)</span>
        <?php if (0 /*$pay_type == 'clicks'*/): ?>
            <div>Пополнение любой суммы (стоимость клика 0.4 <?= \Yii::$app->params['currency'] ?>)<br>
                Минимальная сумма 10 <?= \Yii::$app->params['currency'] ?>
            </div>
            <input id="custom_sum" type="text" onblur="change_href('my-sum','my_sum', 20)" class="form-control">
        <?php else: ?>
            <input id="custom_sum" type="text" onkeyup="change_href('my-sum','my_sum', 0)" class="form-control">
        <?php endif; ?>
    </div>
    <div class="ks-body">
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=html" target="_blank"
           class="btn btn-info-outline btn-block my-sum disabled">Открыть счет</a>
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=xlsx" target="_blank"
           class="btn btn-info-outline btn-block my-sum disabled">Скачать в xlsx</a>
        <!--a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=pdf" target="_blank"
           class="btn btn-info-outline btn-block my-sum disabled">Скачать в pdf</a-->
    </div>
</div>