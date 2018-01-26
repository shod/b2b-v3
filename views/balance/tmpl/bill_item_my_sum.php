<div class="ks-subscription" >
    <div class="ks-header">
        <span class="ks-name">Своя сумма</span>
        <input type="text" onchange="change_href('my-sum','my_sum', $(this).val())" class="form-control">
    </div>
    <div class="ks-body">
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=html" target="_blank" class="btn btn-info-outline btn-block my-sum">Открыть счет</a>
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=xlsx" target="_blank" class="btn btn-info-outline btn-block my-sum">Скачать в xlsx</a>
        <a href="/balance/blankop/?id=<?= $id; ?>&type=<?= $nds > 0 ? 0 : 1 ?>&render-type=pdf" target="_blank" class="btn btn-info-outline btn-block my-sum">Скачать в pdf</a>
    </div>
</div>