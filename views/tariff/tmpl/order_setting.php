<div class="row">
    <div class="col-lg-12">
        
    <div class="card ks-card-widget ks-widget-payment-table-invoicing" style="height: 100%;">
        <h5 class="card-header">
            Настройка вариантов контактов покупателей с магазином
        </h5>                
        <div class="card-block">
            <p>
                <span class="ks-card-widget-datetime">Выберите подходящие варианты связи покупателей с магазином. Стоимость рассчитывается в % от стоимости товара за обращение покупателя.
                Должен быть выбран минимум один вариант.
                </span>
            </p>
            <div>
                <label class="ks-checkbox-switch ks-success">
                    <input name="is_order" onclick="saveOrderSettings(this)" type="checkbox" value="1"  <?= isset($is_order) ? $is_order : " " ?>>
                    <span class="ks-wrapper"></span>
                    <span class="ks-indicator"></span>
                    <span class="ks-on">Вкл</span>
                    <span class="ks-off">Выкл</span>
                </label>
                <span style="position: relative;top: 3px;">Оформление заказа (<?= $prc_order ?>% от стоимости товара)</span>            
                </div><div>
                <label class="ks-checkbox-switch ks-success">
                    <input name="is_phone" onclick="saveOrderSettings(this)" type="checkbox" value="1"  <?= isset($is_phone) ? $is_phone : " " ?>>
                    <span class="ks-wrapper"></span>
                    <span class="ks-indicator"></span>
                    <span class="ks-on">Вкл</span>
                    <span class="ks-off">Выкл</span>
                </label>
                <span style="position: relative;top: 3px;">Просмотр контактной информации (<?= $prc_phone ?>% от стоимости товара)</span>            
                </div><div>
                <label class="ks-checkbox-switch ks-success">
                    <input name="proxysite" onclick="saveOrderSettings(this)" type="checkbox" value="1"  <?= isset($proxysite) ? $proxysite : " " ?>>
                    <span class="ks-wrapper"></span>
                    <span class="ks-indicator"></span>
                    <span class="ks-on">Вкл</span>
                    <span class="ks-off">Выкл</span>
                </label>
                <span style="position: relative;top: 3px;">Переход на сайт (<?= $prc_proxy ?>% от стоимости товара)</span>            
            </div>
        </div>
    </div>        
    </div>    
</div>
