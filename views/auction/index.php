<?php
$this->title = 'Аукционы';
$this->registerJsFile(Yii::$app->request->baseUrl . '/web/scripts/js/auction.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<style type="text/css">
    .auction ol li {
        cursor: pointer;
    }
</style>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="alert alert-danger ks-solid-light" role="alert">
                <b>
                    Запрещается использовать сторонний программный код (скрипты).
                    При обнаружении использования подобных скриптов для аккаунта будет приостановлена услуга "Аукционы"
                    до выяснения
                    всех обстоятельств.
                </b>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h3>Аукционы на сутки по фиксированной ставке</h3>
                    <p>Доступно к аукциону: <?= $max_bid ?> ТЕ /
                        <span style="color:red;">C <?= $time_stop_down ?> запрещается снижение ставок</span> / <span
                                style="color:red;">C <?= $time_blind ?> ставки вслепую</span> /
                        <span id="reverce_time"></span>
                    </p>
                </div>
            </div>

            <div class="row" style="padding-top: 10px; margin-top: 0px;">
                <div class="col-lg-9">
                    <div><a class="btn btn-primary ks-light" href="#" onclick="$('#help-block').toggle(500)">ПОМОЩЬ</a>
                    </div>
                    <div id="help-block" style="display: none;" class="alert alert-primary ks-solid-light" role="alert">
                        <h3><?= isset($title) ? $title : "" ?></h3>
                        <p><?= isset($text) ? $text : "" ?></p>
                    </div>
                </div>
                <div class="col-lg-3 content-end">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <a href="/auction/add/?fix=1" class="btn btn-primary ks-light">Добавить аукцион с фиксированной
                                ставкой</a>
                        </div>
                    </div>
                </div>
            </div>

            <form method="post" action="/auction/process" id="formAuctionFix">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <input type="hidden" name="action" value="save"/>
                <input type="hidden" name="auction" value="fix"/>

                <div class="row" style="padding-top: 10px; margin-top: 0px;">
                    <div class="col-lg-12">
                        <div class="content-end">
                            <input id="btnSaveFix" class="btn btn-primary" type="button" value="Сохранить"
                                   onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                        </div>
                        <br>
                        <div class="ks-nav-body">
                            <div class="ks-nav-body-wrapper">
                                <div class="container-fluid" style="overflow: auto">
                                    <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th style='width:260px' class="head-title">Категория</th>
                                            <th style='width:130px' class="head-title bet">Позиция и цена</th>
                                            <th class="head-title" colspan="2">
                                                Ваша ставка за размещение.
                                            </th>

                                            <td></td>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th style='width:260px' class="head-title">Категория</th>
                                            <th style='width:130px' class="head-title bet">Позиция и цена</th>
                                            <th class="head-title" colspan="2">
                                                Ваша ставка за размещение.
                                            </th>


                                            <td></td>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                        <?= isset($data_catalog_fix) ? $data_catalog_fix : "" ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="content-end">
                            <input id="btnSaveFix" class="btn btn-primary" type="button" value="Сохранить"
                                   onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                        </div>
                    </div>
                </div>

            </form>

            <div class="row">
                <div class="col-lg-12">
                    <h3>Аукционы с онлайн-ставками</h3>
                </div>
            </div>

            <div class="row" style="padding-top: 10px; margin-top: 0px;">
                <div class="col-lg-9">
                    <div><a class="btn btn-primary ks-light" href="#" onclick="$('#help-block-online').toggle(500)">ПОМОЩЬ</a>
                    </div>
                    <div id="help-block-online" style="display: none;" class="alert alert-primary ks-solid-light"
                         role="alert">
                        <h3><?= isset($title_online) ? $title_online : "" ?></h3>
                        <p><?= isset($text_online) ? $text_online : "" ?></p>
                    </div>
                </div>
                <div class="col-lg-3 content-end">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <a href="/auction/add" class="btn btn-primary ks-light">Добавить аукцион с онлайн
                                ставкой</a>
                        </div>
                    </div>
                </div>
            </div>

            <form method="post" action="/auction/process" id="formAuction">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <input type="hidden" name="action" value="save"/>
                <input type="hidden" name="auction" value="minute"/>

                <div class="row" style="padding-top: 10px; margin-top: 0px;">
                    <div class="col-lg-12">
                        <div class="content-end">
                            <input class="btn btn-primary" type="button" value="Сохранить"
                                   onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                        </div>
                        <br>

                        <div class="ks-nav-body">
                            <div class="ks-nav-body-wrapper">
                                <div class="container-fluid" style="overflow: auto">
                                    <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th style='width:70px'>Категория</th>
                                            <th style='width:70px'>Показов вчера</th>
                                            <th style='width:70px'>Прогноз расхода за сутки</th>
                                            <th>Позиция и цена</th>
                                            <th>
                                                Ваша ставка за 1000 показов <br/>
                                                <input class="form-control" type="text" id="cost_all"
                                                       style="width: 50px; text-align: right;"/>
                                            </th>
                                            <th>Автобюджет <br/>
                                                <input type="checkbox"
                                                       onclick="$('.auction .auto').attr('checked', $(this).attr('checked'))"/>
                                            <td></td>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th style='width:70px'>Категория</th>
                                            <th style='width:70px'>Показов вчера</th>
                                            <th style='width:70px'>Прогноз расхода за сутки</th>
                                            <th>Позиция и цена</th>
                                            <th>
                                                Ваша ставка за 1000 показов <br/>
                                                <input class="form-control" type="text" id="cost_all"
                                                       style="width: 50px; text-align: right;"/>
                                            </th>
                                            <th>Автобюджет <br/>
                                                <input type="checkbox"
                                                       onclick="$('.auction .auto').attr('checked', $(this).attr('checked'))"/>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                        <?= isset($data_catalog_minute) ? $data_catalog_minute : "" ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="content-end">
                            <input class="btn btn-primary" type="button" value="Сохранить"
                                   onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


