<?php
$this->title = "Контекстная реклама";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row" data-dashboard-widget>
                <div class="col-lg-12">
                    <div class="content-end"><a class="btn btn-primary ks-light" href="#" onclick="getModalByurl('Помощь по размещению контекстной рекламы','context')">ПОМОЩЬ</a>
                    </div>

                </div><br><br><br>
                <div class="col-lg-12">
                    <div class="card ks-card-widget ks-widget-payment-table-invoicing">
                        <div class="card-header" style="line-height: 23px;">
                            Подключение контекстной рекламы товаров в Яндекс.Директ и Google AdWords
                        </div>
                        <div class="card-block">

                            <?= isset($error_msg) ? $error_msg : "" ?>
                            <form method="post" action="/context-adv/process">
                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                <input type="hidden" name="action" value="save_setting"/>

                                <input id='check_inp' type="checkbox" name='agree'
                                       onclick="show_hide(this);" <?= isset($checked_ads) ? $checked_ads : "" ?>
                                       value=1/> <b>Контекстная реклама товаров в</b> Яндекс.Директ и Google AdWords
                                <br><br>
                                <div class="alert alert-danger ks-solid-light" role="alert">Контекстная реклама временно
                                    недоступна по техническим причинам. Приносим свои извинения!
                                </div>
                                <p>Цена за клик CPC (cost per click) – <b>0,15 ТЕ.</b></p>
                                <div class='mydiv'>
                                    <h4>Ограничение бюджета</h4>
                                    <label>
                                        <input type='radio' name='max'
                                               value=5 <?= isset($checked_5) ? $checked_5 : "" ?>> до 5 ТЕ в сутки
                                    </label><br>
                                    <label>
                                        <input type='radio' name='max'
                                               value=10 <?= isset($checked_10) ? $checked_10 : "" ?>> до 10 ТЕ в сутки
                                    </label><br>
                                    <label>
                                        <input type='radio' name='max'
                                               value=30 <?= isset($checked_30) ? $checked_30 : "" ?>> до 30 ТЕ в сутки
                                    </label>
                                </div>

                                <input type='submit' value='Сохранить' class='btn btn-primary'/>
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
                        <div class="card-header" style="line-height: 23px;">
                            <a href="/context-adv/blacklist" style="margin-bottom: 15px;">Управление разделами для
                                Яндекс.Директ и Google AdWords</a>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>