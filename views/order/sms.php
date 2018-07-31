<?php
$this->title = "Обратный звонок";
$this->registerJsFile(Yii::$app->request->baseUrl . '/web/scripts/js/charts.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs(
    "
    $(document).ready(function () {
        get_sms_chart();
    });
    "
);
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card ks-panel" style="height: 100%">
                        <h5 class="card-header">
                            Подключение услуги
                        </h5>
                        <div class="card-block">
                            <div class="ks-text-block">
                                <div class="row">

                                    <div class="col-lg-10 col-md-12  col-xs-12">
                                        <div class="ks-text-block">
                                            <div class="gray-name">Статус услуги</div>
                                            <div>
                                                <label class="ks-checkbox-switch ks-success">
                                                    <input id="active-sms" type="checkbox"
                                                           value="1" <?= isset($id_active) ? $id_active : "" ?> >
                                                    <span class="ks-wrapper"></span>
                                                    <span class="ks-indicator"></span>
                                                    <span class="ks-on">Вкл</span>
                                                    <span class="ks-off">Выкл</span>
                                                </label>
                                                <br><br>
                                                <div class="ks-text-block">
                                                    <div class="ks-name">Email для заказов</div>

                                                    <div class="input-group">
                                                        <input id="email-value" type="text" class="form-control" placeholder="Email"
                                                               value="<?= isset($notice_email) ? $notice_email : "" ?>">
                                                        <span class="input-group-btn">
                        <button id="email" class="btn btn-primary notify-button" type="button">Сохранить</button>
                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card ks-panel">
                        <h5 class="card-header">
                            Настройка СМС о заказе
                        </h5>
                        <div class="card-block">
                            <div class="ks-text-block">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-xs-2">
                                        <div class=" ks-card-widget ks-widget-payment-earnings">
                                            <div class="ks-payment-earnings-amount"><?= isset($po_balance) ? $po_balance : "" ?></div>
                                            <div class="ks-payment-earnings-amount-description">
                                                Осталось СМС
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-xs-10">
                                        <div class="ks-name">Номер телефона для СМС</div>

                                        <div class="input-icon icon-left icon-lg icon-color-primary input-group">
                                            <input id="phone-value" style="padding-left: 50px" type="text" class="form-control"
                                                   placeholder="Номер" value="<?= isset($notice_phone) ? $notice_phone : "" ?>">
                                            <span class="icon-addon">
                             <span>+375</span>
                        </span>
                                            <span class="input-group-btn">
                                <button id="phone" class="btn btn-primary notify-button"
                                        type="button">Сохранить</button>
                        </span>
                                        </div>
                                        <br>

                                        <button id="addsms_30_5"
                                                class="btn btn-primary-outline ks-solid btn-block add_sms">
                                            Добавить 30
                                            СМС за 5 ТЕ
                                        </button>
                                        <br>
                                        <button id="addsms_100_10"
                                                class="btn btn-primary-outline ks-solid btn-block add_sms">
                                            Добавить 100
                                            СМС за 10 ТЕ
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-xs-12">
                    <div class="card ks-panel">
                        <h5 class="card-header">
                            Как это работает
                        </h5>
                        <div class="card-block">
                            Покупатель нажимает на кнопку «Купить», расположенную рядом с вашими ценовыми предложениями.
                            Заполняет данные для покупки, и заявка от покупателя приходит на указанный вами email. <b>Не забудьте добавить email для заказов!</b>
                            Мы рекомендуем Вам оперативно реагировать на заявки от покупателей. Все заявки дублируются на странице Мои заказы в личном кабинете. Отмечайте те, которые уже обработаны. Функционал бесплатный!
                            За дополнительную плату Вы можете подключить услугу SMS-заказы. В таком случае, заявки будут дублироваться через СМС на номер мобильного телефона, указанного в настройках.
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card ks-card-widget ks-widget-payment-table-invoicing">
                        <h5 class="card-header">
                            Заказы
                        </h5>
                        <div class="card-block">
                            <div style="overflow: auto">
                                <table class="table ks-payment-table-invoicing">
                                    <tbody>
                                    <?= isset($orders) ? $orders : "" ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card ks-card-widget ks-widget-payment-table-invoicing">
                        <h5 class="card-header">
                            История
                        </h5>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-6">
                                    <ul class="pagination pagination-sm" style="margin-top: 20px">
                                        <?= isset($history_pages) ? $history_pages : "" ?>
                                    </ul>
                                </div>
                                <div class="col-lg-6 content-end">
                                    <form type="get" action="/order/process/">
                                        <input type="hidden" name="action" value="delete-history">
                                        <input type="submit" class="btn btn-primary" value="Очистить историю"/>
                                    </form>
                                </div>
                            </div>
                            <div style="overflow: auto">
                                <table class="table ks-payment-table-invoicing">
                                    <tbody id="history-body">
                                    <?= isset($history) ? $history : "" ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12" id="chart"></div>
            </div>
        </div>
    </div>
</div>
