<div class="ks-subscription <?= $class_active ?>" style="margin-right: 15px; margin-left: 15px; margin-top: 40px">
    <div class="ks-header">
        <span class="ks-name"><?= $name ?></span>
        <span class="ks-price">
            <span class="ks-amount"><?= $sum ?></span>ТЕ
        </span>
        <span class="ks-price">
            <span class="ks-amount"><?= number_format(round($pay_sum, 2), 2, '.', ' '); ?></span>BYN*
        </span>
        <footer><cite title="Source Title"><?= $blank_text ?></cite></footer>
    </div>
    <div class="ks-body">
        <ul>
            <li class="ks-item">
                <span class="ks-icon la la-info-circle"></span>
                <span class="ks-text">Задолженность</span>
                <span class="ks-amount"><?= number_format(round($sum_promise, 2), 2, '.', ' '); ?> <?= Yii::$app->params['currency']; ?></span>
            </li>
            <li class="ks-item">
                <span class="ks-icon la la-info-circle"></span>
                <span class="ks-text">Сумма</span>
                <span class="ks-amount"><?= number_format(round($pay_sum, 2), 2, '.', ' '); ?> <?= Yii::$app->params['currency']; ?></span>
            </li>
            <!--li class="ks-item">
                <span class="ks-icon la la-info-circle"></span>
                <span class="ks-text">К оплате</span>
                <span class="ks-amount"><? //= number_format(round($finish,2), 2,'.',' ') 
                                        ?>  <?= Yii::$app->params['currency']; ?></span>
            </li-->
        </ul>

        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=html" target="_blank" class="btn btn-block <?= $class_button_disable ?> <?= $class_button ?> <?= $class_active ?>">Открыть счет</a>
        <!--a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=xlsx" target="_blank" class="btn btn-info-outline btn-block">Скачать в xlsx</a-->
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=pdf" target="_blank" class="btn btn-block <?= $class_button_disable ?> <?= $class_button ?> <?= $class_active ?>">Скачать в pdf</a>
    </div>
</div>