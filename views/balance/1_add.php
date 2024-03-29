<?php
$this->title = "Пополнение баланса";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card" style="height: 100%">
                        <div class="card-block">
                            <h3 class="ks-payment-earnings-amount">1ТЕ = <span id="te"><?= $curs; ?></span> руб.
                                <span class="ks-description small">
                                    без учета НДС 20%
                                </span>
                            </h3>
                            <h3 class="ks-payment-earnings-amount">1ТЕ = <span id="te"><?= $curs * 1.2; ?></span> руб.
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
                            <!--h5 class="card-title">Информация о балансе</h5-->
                            <?= $info; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ks-pricing-subscriptions-page" style="height: auto">
                <div class="ks-header">
                    <h3 class="ks-name">Пополнить баланс</h3>                    
                        <div class="ks">
                            ПО ВСЕМ ВОПРОСАМ ФОРМИРОВАНИЯ СЧЕТА ОБРАЩАЙТЕСЬ К ВАШЕМУ МЕНЕДЖЕРУ ЖАННА
							ПО ТЕЛЕФОНУ  +375 (29) 112-45-45 Viber, Telegram  
                        </div>                        
                </div>                
            </div>            
                <div class="col-lg-12">
                    <?php if (isset($f_offerta) && ((int)$f_offerta & 1)): ?>
                        <div id="text_nds">
                            <h4>Оплата  по реквизитам с НДС</h4>

                            <p>Вы можете произвести оплату:</p>

                            <p>- через кассу банка путем внесения наличных денежных средств на расчетный счет ООО «Альметра»<br>
                                - через интернет-банкинг перевод по реквизитам путем перечесления денежных средств расчетный счет ООО «Альметра»</p>

                            <p>При оплате  необходимо обязательно указать:</p>
                            <p>- в назначении платежа : « id <?= $seller_id ?> оплата услуг по размещению<br>рекламных материаловна сайте <?= \Yii::$app->params['migom_name'] ?> »;</p>

                            <p><mark>Оплата должна быть произведена по следующим реквизитам:</mark></p>

                            <p>ООО «Альметра», УНП 192147793, ОКПО 381393215000<br>
                                Юридический адрес: 220007, Беларусь, г. Минск, ул. Могилевская 2/2, помещение 10-1<br>
                                р/с BY43ALFA30122078930080270000<br>
                                БИК ALFABY2X в ЗАО «Альфа-Банк», ул. Сурганова, 43-47, 220013 Минск,Республика Беларусь, код 270</p>
                        </div>
                        <!--div id="text_nds">
                            <h4>Оплата  по реквизитам с НДС</h4>

                            <p>Вы можете произвести оплату:</p>

                            <p>- через кассу банка путем внесения наличных денежных средств на расчетный счет ООО «Марталь»<br>
                                - через интернет-банкинг перевод по реквизитам путем перечесления денежных средств расчетный счет ООО «Марталь»</p>

                            <p>При оплате  необходимо обязательно указать:</p>
                            <p>- в назначении платежа : « id <?= $seller_id ?> оплата услуг по размещению<br>рекламных материаловна сайте <?= \Yii::$app->params['migom_name'] ?> »;</p>

                            <!--p><mark>Оплата должна быть произведена по следующим реквизитам:</mark></p>

                            <p>ООО «Марталь», УНП 192583317<br>
                                Юридический адрес:  г.Минск, ул.Могилевская, д.2, корп.2, пом.18<br>
                                р/с BY54 ALFA 3012 2122 4700 1027 0000<br>
                                БИК ALFABY2X в ЗАО «Альфа-Банк», Центральный офис ул.Советская, 12, 220030, г.Минск</p>
                        </div-->
                    <?php endif; ?>
                    <br><br>
                    <?php if ((isset($f_offerta) && ((int)$f_offerta & 2))): ?>
                        <div id="text_no_nds">
                            <h4>Оплата  по реквизитам без НДС</h4>

                            <p>Вы можете произвести оплату:</p>

                            <p>- через кассу банка путем внесения наличных денежных средств на расчетный счет ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ШМЫК ОЛЕГ ДМИТРИЕВИЧ<br>
                                - через интернет-банкинг перевод по реквизитам путем перечесления денежных средств расчетный счет ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ШМЫК ОЛЕГ ДМИТРИЕВИЧ</p>

                            <p>При оплате  необходимо обязательно указать:</p>
                            <p>- в назначении платежа : « id <?= $seller_id ?> оплата услуг по размещению<br>рекламных материаловна сайте <?= \Yii::$app->params['migom_name'] ?> »;</p>

                            <p><mark>Оплата без НДС должна быть произведена по следующим реквизитам:</mark></p>

                            <p>ИНДИВИДУАЛЬНЫЙ ПРЕДПРИНИМАТЕЛЬ ШМЫК ОЛЕГ ДМИТРИЕВИЧ, УНП 191182046 <br>
                                Юридический адрес: 220045, г.Минск, пр-т Дзержинского, 131-305<br>
                                р/с BY26 REDJ 3013 1009 2300 1000 0933 в BYN ЗАКРЫТОЕ АКЦИОНЕРНОЕ ОБЩЕСТВО &quot;РРБ-БАНК&quot; БИК: REDJBY22<br>
                                ЦБУ №9, 220005, пр-т Независимости, 58, Минск, Республика Беларусь</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

