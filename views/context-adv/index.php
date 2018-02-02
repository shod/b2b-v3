<?php
    $this->title = "Контекстная реклама";
?>

<div class="row" data-dashboard-widget>
    <div class="col-lg-12">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <div class="card-header">
                Подключение контекстной рекламы товаров в Яндекс.Директ и Google AdWords
            </div>
            <div class="card-block">

                        <?= isset($error_msg) ? $error_msg : ""?>
                        <form method="post" action="/context-adv/process">
                            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                            <input type="hidden" name="action" value="save_setting" />

                            <input id='check_inp' type="checkbox" name='agree' onclick="show_hide(this);" <?= isset($checked_ads) ? $checked_ads : ""?> value=1/> <b>Контекстная реклама товаров в</b> Яндекс.Директ и Google AdWords
                            <br><br>
                            <div class="alert alert-danger ks-solid-light" role="alert">Контекстная реклама временно недоступна по техническим причинам. Приносим свои извинения!</div>
                            <p>Цена за клик CPC (cost per click)  – <b>0,15 ТЕ.</b></p>
                            <div class='mydiv'>
                                <h4>Ограничение бюджета</h4>
                                <label>
                                    <input type='radio' name='max' value=5 <?= isset($checked_5) ? $checked_5 : ""?>>  до 5 ТЕ в сутки
                                </label><br>
                                <label>
                                    <input type='radio' name='max' value=10 <?= isset($checked_10) ? $checked_10 : ""?>>  до 10 ТЕ в сутки
                                </label><br>
                                <label>
                                    <input type='radio' name='max' value=30 <?= isset($checked_30) ? $checked_30 : ""?>>  до 30 ТЕ в сутки
                                </label>
                            </div>

                            <input type='submit' value='Сохранить' class='btn btn-primary' />
                        </form>

                    </div>
                </div>
                <style>
                    .disabledbutton {
                        pointer-events: none;
                        opacity: 0.4;
                    }
                </style>



    </div>
</div>

<div class="row" data-dashboard-widget>
    <div class="col-lg-12">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <div class="card-header">
                <a href="/context-adv/blacklist" style="margin-bottom: 15px;">Управление разделами для Яндекс.Директ и Google AdWords</a>

            </div>
        </div>
    </div>
</div>

<div class="row" data-dashboard-widget>
    <div class="col-lg-12">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <div class="card-header">

            </div>
            <div class="card-block">
                <div>
                    <p>*Контекстная реклама – это текстовые объявления, которые показываются пользователям по запросам. Объявления показываются пользователю именно в тот момент, когда он сам проявил интерес к товару или услуге и, возможно, готов к покупке.
                        Контекстное объявление состоит из заголовка, текста, ссылки (целевой страницы, на которую попадет пользователь, когда кликнет по объявлению) и дополнительных элементов.</p>

                    <p>Принцип работы состоит в том, что Ваши рекламные торговые предложения могут быть доступны в поиске с переходом на Ваш магазин на торговой площадке MIGOM.by, либо на Ваш сайт интернет-магазина с системой оплаты за клик.
                        <br><b>Плата за клик или PPC (Pay Per Click)</b> –  это разновидность рекламы, при которой магазин оплачивает каждый клик, совершенный пользователем для перехода на сайт, либо на конкретное торговое предложение магазина, размещенного на торговой площадке MIGOM.by.</p>

                    <p>Ваши товары попадают в специально настроенные рекламные кампании, привлекая дополнительных клиентов. Мы анализируем интересы пользователей к Вашим товарам и формируем рекламные блоки. При переходе с такого рекламного блока пользователь видит только Ваше предложение и информацию о товаре. Предложения других продавцов не показываются. В таких рекламных кампаниях участвуют  только страницы конкретных товаров, а не общие списки, чем достигается максимально точный таргетинг. </p>

                    <p>Оплата производится за фактические просмотры с общего счета.</p>

                    <p>Как выглядит контекстная реклама: </p>

                    <div style="overflow: auto">
                        <img src="/img/migom-info/context1.jpg" />
                        <br>
                        <img src="/img/migom-info/context2.jpg" />
                        <br><br><br>
                        <p>На сайте предложение будет выглядеть следующим образом</p>
                        <img src="/img/migom-info/context_migom1.jpg" />
                        <br>
                        <img src="/img/migom-info/context_migom2.jpg" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>