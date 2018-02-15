<div class="col-xl-4 col-lg-12" style="padding-bottom: 20px;">
    <div class="card panel panel-default ks-project" style="height: 100%; <?= $active_style ?>"  >
        <div class="ks-project-header">
            <label class="custom-control custom-checkbox ks-checkbox ks-checkbox-success" style="margin-right: 0px;">
                <input type="checkbox" class="custom-control-input pack-checkbox" <?= $checked ?> >
                <span class="custom-control-indicator"></span>
            </label>
        </div>
        <div class="ks-project-body">
            <a href="#" class="ks-name">
                <span class="ks-text"><?= $name ?></span>
            </a>
            <div class="ks-description" style="max-height: 150px; overflow: auto;">
                <?= $sections ?>
            </div>
            <div class="ks-widget-payment-simple-amount-item" style="padding: 0px;min-height: 0px;">
                <div class="payment-simple-amount-item-body" style="text-align: center">
                    <div class="payment-simple-amount-item-amount">
                        <span class="ks-amount" style="color: rgb(37, 98, 143)"><?= $cost['cost'] ?> ТЕ</span>
                        <span class="ks-amount" style="font-size: 16px;"><?= $cost['c1'] ?> в день</span>
                    </div>
                    <div class="payment-simple-amount-item-amount">
                        <mark><span class="ks-progress-type">Экономия <?= $evalue ?> ТЕ</span></mark>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>