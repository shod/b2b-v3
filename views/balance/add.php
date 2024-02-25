<?php
$this->title = "Пополнение баланса";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <!--div class="row">
                <div class="col-lg-6">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <h3 class="ks-payment-earnings-amount">1ТЕ = <span id="te"><?= $curs; ?></span> руб.
                                <span class="ks-description small">
                                    без учета НДС 20%
                                </span>
                            </h3>
                            <h3 class="ks-payment-earnings-amount" style="visibility: hidden;">1ТЕ = <span id="te"><?= $curs * 1.2; ?></span> руб.
                                <span class="ks-description small">
                                с учетом НДС 20%
                                </span>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <?= $info; ?>
                        </div>
                    </div>
                </div>
            </div-->

            <div class="ks-pricing-subscriptions-page" style="height: auto">
                <div class="ks-header">
                    <h3 class="ks-name">Пополнить баланс </h3>
                    <?php if ($pay_type == 'fixed') : ?>
                        <!--div class="ks-description">
                            * Первый платеж магазина должен быть не меньше 1 месяца размещения в
                            эквиваленте. Иначе магазин не подключится!
                        </div>
                        <div class="ks-description">
                            ** При первом подключении магазина к торговому порталу <?= \Yii::$app->params['migom_name'] ?> при стоимости размещения
                            менее
                            или равно 49 ТЕ в месяц,
                            единоразовый платеж должен быть равен или более 50 ТЕ.
                        </div>
                        <div class="ks-description">
                            *** Если ваш магазин не был активен более 90 дней, то для повторного включения сумма оплаты
                            должна
                            быть не менее суммы месячного размещения согласно вашего тарифа.
                        </div-->
                    <?php else : ?>
                        <div class="ks-description">
                            Сумма оплаты пакета является единой, не разбивается на несколько платежей.
                        </div>
                    <?php endif; ?>
                    <div class="ks-description" style="color:red;">
                        После оплаты копию платежа отправляйте на почту <?= \Yii::$app->params['saleManager'] ?>, дополнительно просьба указывать id магазина».
                    </div>
                    <div class="ks-description">
                        * Сумма без учета НДС.<br />
                        * ** Можно использовать на ежемесячное размещение, а также на продвижение и дополнительные сервисы.
                    </div>
                </div>
                <div class="ks-subscriptions" id="subscriptions">

                    <?= isset($choise) ? $choise : ""; ?>

                    <?= $blanks; ?>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php if (isset($f_offerta) && ((int)$f_offerta & 1)) : ?>
                        <div id="text_nds">
                            <h4>Оплата по реквизитам с НДС</h4>

                            <p>Вы можете произвести оплату:</p>

                            <p>- через кассу банка путем внесения наличных денежных средств на расчетный счет ООО «Макси Бай Медиа»<br>
                                - через интернет-банкинг перевод по реквизитам путем перечисления денежных средств расчетный счет ООО «Макси Бай Медиа»</p>

                            <p>При оплате необходимо обязательно указать:</p>
                            <p>- в назначении платежа : « id <?= $seller_id ?> оплата услуг по размещению<br>рекламных материалов»;</p>

                            <p><mark>Оплата должна быть произведена по следующим реквизитам:</mark></p>

                            <p> ООО "Макси Бай Медиа"<br>
                                Р/сч: BY85ALFA30122544640050270000 <br>
                                в ЗАО "Альфа-Банк" код ALFABY2X , <br>
                                УНП:191983656<br>
                                Адрес: 220070, г. Минск, ул. Чеботарева, дом No 7а, помещение 06, комната 6-6, этаж 4, <br>
                                тел. 8 (017) 388-24-23, 8 (029) 101-23-23
                            </p>
                        </div>
                    <?php endif; ?>
                    <br><br>
                    <?php if ((isset($f_offerta) && ((int)$f_offerta & 2))) : ?>
                        <div id="text_no_nds">
                            <h4>Оплата по реквизитам без НДС</h4>

                            <p>Вы можете произвести оплату:</p>

                            <p>- через кассу банка путем внесения наличных денежных средств на расчетный счет <br>
                                - через интернет-банкинг перевод по реквизитам путем перечесления денежных средств расчетный счет </p>

                            <p><mark>При оплате необходимо обязательно указать:</mark></p>
                            <p>- в назначении платежа :<b> « id <?= $seller_id ?> оплата услуг по размещению рекламных материалов»;</b></p>

                            <p><mark>Оплата без НДС должна быть произведена по следующим реквизитам:</mark></p>

                            <p></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>