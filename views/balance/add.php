<?php
$this->title = "Пополнение баланса";
$this->registerJs(
    " 
           $( document ).ready(function() {
                
           });
           
        "
);
?>
<div class="ks-pricing-subscriptions-page">
    <div class="ks-header">
        <h3 class="ks-name">Сформировать счет на оплату</h3>
        <div class="ks-description">* Первый платеж магазина должен быть не меньше 1 месяца размещения в эквиваленте. Иначе магазин не подключится!</div>
    </div>
    <div class="ks-subscriptions">

        <?= isset($choise) ? $choise : ""; ?>
        <?= $blanks; ?>
    </div>
</div>

