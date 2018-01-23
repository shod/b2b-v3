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

        <a href="#" class="btn btn-info-outline btn-block">Открыть счет</a>
        <a href="#" class="btn btn-info-outline btn-block">Скачать в xlsx</a>
        <a href="#" class="btn btn-info-outline btn-block">Скачать в pdf</a>
    </div>
</div>