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

        <div class="subscription-toggle">
            <div class="ks-checkbox-text-slider">
                <span class="ks-text ks-color-info">С НДС</span>
                <label class="ks-checkbox-slider ks-info">
                    <input type="checkbox" value="1">
                    <span class="ks-indicator"></span>
                </label>
                <span class="ks-text">Без НДС</span>
            </div>
        </div>

        <div class="ks-subscription ks-active">
            <div class="ks-header">
                <span class="ks-name">300 ТЕ +40 ТЕ на бонусный счет </span>
                <span class="ks-price">
                                        <span class="ks-amount">828,00</span>руб
                                    </span>
            </div>
            <div class="ks-body">
                <ul>
                    <li class="ks-item">
                        <span class="ks-icon la la-info-circle"></span>
                        <span class="ks-text">Сумма обещанного</span>
                        <span class="ks-amount">0 руб</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-info-circle"></span>
                        <span class="ks-text">Сумма</span>
                        <span class="ks-amount">690,00 руб</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-info-circle"></span>
                        <span class="ks-text">Сумма НДС</span>
                        <span class="ks-amount">138,00  руб.</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-info-circle"></span>
                        <span class="ks-text">К оплате</span>
                        <span class="ks-amount">828,00  руб.</span>
                    </li>
                </ul>

                <a href="#" class="btn btn-info-outline btn-block">Открыть счет</a>
                <a href="#" class="btn btn-info-outline btn-block">Скачать в xlsx</a>
                <a href="#" class="btn btn-info-outline btn-block">Скачать в pdf</a>
            </div>
        </div>
        <div class="ks-subscription ks-active">
            <div class="ks-header">
                <span class="ks-name">Growth</span>
                <span class="ks-price">
                                        <span class="ks-amount">$99</span>/month
                                    </span>
            </div>
            <div class="ks-body">
                <ul>
                    <li class="ks-item">
                        <span class="ks-icon la la-briefcase"></span>
                        <span class="ks-text">Leads</span>
                        <span class="ks-amount">50,000</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-users"></span>
                        <span class="ks-text">Customers</span>
                        <span class="ks-amount">25,000</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-paper-plane"></span>
                        <span class="ks-text">Emails</span>
                        <span class="ks-amount">25,000</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-bell"></span>
                        <span class="ks-text">Notifications</span>
                        <span class="ks-amount">3</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-comments"></span>
                        <span class="ks-text">Chat Agents</span>
                        <span class="ks-amount">3</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-ticket"></span>
                        <span class="ks-text">Support Tickets Replies</span>
                        <span class="ks-amount">500</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-male"></span>
                        <span class="ks-text">Team Members</span>
                        <span class="ks-amont">3</span>
                    </li>
                </ul>
                <a href="#" class="btn btn-info btn-block ks-active">Start free trial</a>
            </div>
        </div>
        <div class="ks-subscription">
            <div class="ks-header">
                <span class="ks-name">Startup</span>
                <span class="ks-price">
                                        <span class="ks-amount">$199</span>/month
                                    </span>
            </div>
            <div class="ks-body">
                <ul>
                    <li class="ks-item">
                        <span class="ks-icon la la-briefcase"></span>
                        <span class="ks-text">Leads</span>
                        <span class="ks-amount">50,000</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-users"></span>
                        <span class="ks-text">Customers</span>
                        <span class="ks-amount">25,000</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-paper-plane"></span>
                        <span class="ks-text">Emails</span>
                        <span class="ks-amount">25,000</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-bell"></span>
                        <span class="ks-text">Notifications</span>
                        <span class="ks-amount">3</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-comments"></span>
                        <span class="ks-text">Chat Agents</span>
                        <span class="ks-amount">3</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-ticket"></span>
                        <span class="ks-text">Support Tickets Replies</span>
                        <span class="ks-amount">500</span>
                    </li>
                    <li class="ks-item">
                        <span class="ks-icon la la-male"></span>
                        <span class="ks-text">Team Members</span>
                        <span class="ks-amont">3</span>
                    </li>
                </ul>
                <a href="#" class="btn btn-info-outline btn-block">Start free trial</a>
            </div>
        </div>
    </div>
</div>

