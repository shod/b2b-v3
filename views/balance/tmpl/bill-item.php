<div class="ks-subscription" >
    <div class="ks-header">
        <span class="ks-name"><?= $name ?></span>
        <span class="ks-price">
            <span class="ks-amount"><?= $finish ?></span>руб
        </span>
    </div>
    <div class="ks-body">
        <ul>
            <li class="ks-item">
                <span class="ks-icon la la-info-circle"></span>
                <span class="ks-text">Сумма обещанного</span>
                <span class="ks-amount"><?= $sum_promise; ?> руб</span>
            </li>
            <li class="ks-item">
                <span class="ks-icon la la-info-circle"></span>
                <span class="ks-text">Сумма</span>
                <span class="ks-amount"><?= $pay_sum; ?> руб</span>
            </li>
            <li class="ks-item">
                <span class="ks-icon la la-info-circle"></span>
                <span class="ks-text">Сумма НДС</span>
                <span class="ks-amount"><?= $nds ?>  руб.</span>
            </li>
            <li class="ks-item">
                <span class="ks-icon la la-info-circle"></span>
                <span class="ks-text">К оплате</span>
                <span class="ks-amount"><?= $finish ?>  руб.</span>
            </li>
        </ul>

        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=html" target="_blank" class="btn btn-info-outline btn-block">Открыть счет</a>
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=xlsx" target="_blank" class="btn btn-info-outline btn-block">Скачать в xlsx</a>
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=pdf" target="_blank" class="btn btn-info-outline btn-block">Скачать в pdf</a>
    </div>
</div>