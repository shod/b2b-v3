<?php
$this->title = "Обещаный платеж";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <p>
                                «Обещанный платеж» — это возможность продлить срок размещения магазина на площадке
                                <?= \Yii::$app->params['migom_name'] ?> до внесения денежных средств на лицевой счет на максимальный срок — 3 дня.<br>
                                Сумма «обещанного платежа» не должна превышать среднюю сумму расходов магазина за 4 дня.<br>
                                После внесения «обещанного платежа», баланс магазина пополняется на указанную сумму.<br>

                                <dt>«Обещанный платеж» не доступен в случае:</dt>
                            <ul>
                                <li>отсутствия первого платежа по тарифу;</li>
                                <li>отсутствия погашения предыдущей суммы по обещанному платежу;</li>
                                <li>просрочке платежа.</li>
                            </ul>
                            По всем вопросам можете связаться с нами по тел. +375 (29) 111-45-45, +375 (29)
                            777-45-45.<br>

                            Сумма указывается в BYN!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <?= isset($text) ? $text : ""; ?>
                            <h4>Максимальная сумма <?= $day_down; ?> руб. </h4>
                            <form method="post" action="/balance/get-promise">
                                <input type="hidden" name="_csrf"
                                       value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                <input type="hidden" name="max" value="<?= $day_down; ?>">
                                <input type="hidden" name="type_promice" value="clicks">
                                <label>
                                    <input id='set_sum_check' type='radio'
                                           onclick='set_sum(document.getElementById("set_sum_pay"), <?= $day_down; ?>)'
                                           name='seller_choise' value='set_sum_pay'> &nbsp;&nbsp;Взять обещанный за
                                    размещение магазина по кликам. Максимальная доступная сумма <span id='max_sum'
                                                                                                      style="font-size: 18px;color: red;"><?= $day_down; ?></span>
                                    руб.
                                    <small>(<?= $day_te; ?> ТЕ ~ <?= $sum_click; ?> кликов. Стоимость клика - 0.4 ТЕ)
                                    </small>
                                </label><br> <input id='set_sum_pay' onfocus='$("#set_sum_check").prop("checked", true)'
                                                    onkeyup='set_sum(this, <?= $day_down; ?>)' class='form-control'
                                                    name='set_sum_pay' type='text' style='width:300px;'
                                                    value='<?= $day_down; ?>'/><br>
                                <input id='sub_button' type="submit" value="Получить обещанный платеж" <?= $disabled; ?>
                                       class="btn btn-success btn-lg disabled">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






