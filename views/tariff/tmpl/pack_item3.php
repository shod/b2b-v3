<div class="col-xl-6 col-lg-12" style="padding-bottom: 20px;">
    <div class="card panel panel-default ks-project" style="height: 100%; <?= $active_style ?>" id="card_<?= $id ?>">
        <div class="ks-project-header">
            <label class="custom-control custom-checkbox ks-checkbox ks-checkbox-success" style="margin-right: 0px;">
                <input type="radio" name="r_tarif" class="custom-control-input pack-radio" <?= $checked ?> id="pack_<?= $id ?>">
                <span class="custom-control-indicator"></span>
            </label>
        </div>
        <div class="ks-project-body">
            <a href="#" class="ks-name">
                <span class="ks-text pack-name"><?= $name ?></span>
            </a>
            <div class="ks-description pack-sections" style="max-height: 150px; overflow: auto;">
                <?= $sections ?>
            </div>
            <div class="ks-widget-payment-simple-amount-item" style="padding: 0px;min-height: 0px;">
                <div class="payment-simple-amount-item-body" style="text-align: center">
                    <div class="payment-simple-amount-item-amount">
                        <span class="ks-amount" style="color: rgb(37, 98, 143)"><span class="pack-cost"><?= $cost['cost'] ?></span> ТЕ / месяц</span> -
                        <span class="ks-amount" style="font-size: 16px;"><?= $cost['cost'] * 3 ?> руб./ месяц</span>
                    </div>
                    <div class="payment-simple-amount-item-amount">
                        <span> <?= $bonus_value['text'] ?></span>
                        <b><span> Бонус на продвижение (ТЕ) – <?= $bonus_value['bonus'] ?></span></b><br />
                        <b><span> Доступный прайс – <?= $bonus_value['prod_count'] ?> товаров</span></b>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>