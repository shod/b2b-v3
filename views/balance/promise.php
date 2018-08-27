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
                                Migom.by до внесения денежных средств на лицевой счет на максимальный срок — 3 дня.<br>
                                Сумма «обещанного платежа» не должна превышать среднюю сумму расходов магазина за 4 дня.<br>
                                После внесения «обещанного платежа», баланс магазина пополняется на указанную сумму.<br>

                                <dt>«Обещанный платеж» не доступен в случае:</dt>
                                <ul>
                                    <li>отсутствия первого платежа по тарифу;</li>
                                    <li>отсутствия погашения предыдущей суммы по обещанному платежу;</li>
                                    <li>просрочке платежа.</li>
                                </ul>
                                По всем вопросам можете связаться с нами по тел. +375 (29) 111-45-45<br>

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
                            <form method="post" class="form-inline" action="/balance/get-promise">
                                <input type="hidden" name="_csrf"
                                       value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                <input type="hidden" name="type_promice" value="fixed">
                                <input type="hidden" name="max" value="<?= $day_down; ?>">
                                <div class="input-group" style="width: 100%">
                                    <input name="sum" class="form-control" type="text"
                                           placeholder="Максимальная сумма <?= $day_down; ?> руб.">
                                    <span class="input-group-btn">
                           <input class="btn btn-primary" type="submit" <?= $disabled; ?>
                                  value="Получить обещанный платеж"/>
                        </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






