<?php
$this->title = 'Калькулятор тарифов';
$this->registerJsFile(Yii::$app->request->baseUrl . '/web/scripts/js/tariff.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<meta name="viewport" content="initial-scale=0.1">
<style>
    ::-webkit-scrollbar {
        width: 5px;
    }

    ::-webkit-scrollbar-thumb {
        border-width: 1px 1px 1px 2px;
        border-color: #777;
        background-color: #aaa;
    }

    ::-webkit-scrollbar-thumb:hover {
        border-width: 1px 1px 1px 2px;
        border-color: #7e7e7e;
        background-color: #8c8c8c;
    }

    ::-webkit-scrollbar-track {
        border-width: 0;
    }

    ::-webkit-scrollbar-track:hover {
        border-left: solid 1px #aaa;
        background-color: #eee;
    }
</style>

<div class="ks-page-content-body ks-projects-grid-board" id="tariff-page">


    <div class="ks-projects-grid-board-projects">
        <div class="ks-projects-grid-board-header">
            <div class="ks-search">
                <div class="input-icon icon-right icon icon-lg icon-color-primary">
                    <input id="input-group-icon-text" type="text" class="form-control" placeholder="Найти"
                           onkeyup="search_str(this.value);">
                    <span class="icon-addon">
                                    <span class="la la-search"></span>
                                </span>
                </div>
            </div>
        </div>
        <div class="ks-projects-grid-board-body ks-scrollable">
            <div class="ks-scroll-wrapper ks-rows-section">
                <div class="row" style="padding-left: 15px;">
                    <h3>Варианты размещения</h3> 
                    <br>
                </div>        
                <div class="row">
                    <?= isset($pack_items) ? $pack_items : "" ?>
                </div>
                <p style="color:red">* Cтоимость с учётом НДС</p>
                <div class="row" style="padding-left: 15px;">
                    <h3>Размещение по разделам</h3>
                </div>
                <div class="row" style="padding-left: 15px;">
                    <h5>Выбор отдельных разделов каталога для подключения</h5>
                </div>
                <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="height: 100%">

                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-9">
                                    <p>Доступные разделы для размещения в каталоге.</p>
                                    <p style="color:red">Любой раздел можно отключить от размещения. Для этого нужно убрать галочку и нажать кнопку "Сохранить" внизу страницы.</p>

                                    <form method="post" action="/tariff/process">
                                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                        <input type="hidden" name="action" value="refresh" />
                                        <!-- Обновлены  -->
                                        <input style="margin-bottom: 5px;" class="btn btn-primary btn-sm" type="submit" value="Подтвердить актуальность цен"  onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                                    </form>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 content-end">
                                    <button class="btn btn-primary" data-remote="/product/get-curs/" data-toggle="ajaxModal"
                                            data-target=".bd-example-modal-lg">
                                        <span class="la la-cog ks-icon"></span>
                                        <span class="ks-text">Настройка валюты</span>
                                    </button>

                                </div>
                            </div>

                        </div>
                        <div class="card-block" style="overflow: auto">
                            <form method="post" action="/tariff/process">
                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                <input type="hidden" name="action" value="save_catalogs" />
                                <input type="hidden" name="page" value="#" />
                                <table class="table table-bordered table-striped table-condensed">
                                    <tr>
                                        <th style="width:20px">Вкл. <input type='checkbox' name='all_checked_rule' onclick='check_all(this.checked)' /></th>
                                        <th>Раздел</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <?= $data ?>
                                </table>
                                <input type='submit' value='Сохранить' class='btn btn-primary' />
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            </div>
        </div>
    </div>


    <div class="ks-projects-grid-board-tasks" style="background-color: #f2f2f2;">
            <div class="ks-projects-grid-board-tasks-list">

                <div class="ks-projects-grid-board-tasks-header">
                    <span class="ks-text"><h4>1 ТЕ = <?= $curs ?> руб.</h4></span>
                    <div class="ks-progress ks-progress-inline">
                        <div>
                            без учета НДС 20%
                        </div>
                    </div>
                </div>
                <div class="ks-projects-grid-board-tasks-header">
                    <span class="ks-text"><h4>1 ТЕ = <?= $curs * 1.2 ?> руб.</h4></span>
                    <div class="ks-progress ks-progress-inline">
                        <div>
                            с учетом НДС 20%
                        </div>
                    </div>
                </div>
                <div class="ks-projects-grid-board-tasks-header">
                    <span class="ks-text"><h4>Ваш тариф</h4></span>
                </div>
                <div class="ks-projects-grid-board-tasks-body ks-scrollable">
                    <div style="text-align: center; padding-top: 10px;padding-bottom: 0px;"><strong>ПАКЕТЫ</strong>
                    </div>
                    <div class="jspPane-padding" id="calc_packs">
                        <?= $pack_lines ?>
                    </div>        
                </div>
            </div>
            <table class="ks-projects-grid-board-tasks-statistics" style="height: 150px">
                <tr>
                    <td class="ks-statistic-item">
                        <span class="ks-amount" style="color: rgb(37, 98, 143)"><span
                                    id="sum_all"><?= $all_sum ?></span> TE</span>
                        <span class="ks-text">в месяц</span>
                    </td>
                </tr>
                <tr>
                    <td class="ks-statistic-item">
                        <span class="ks-amount"><span id="sum_all_day"><?= round($all_sum / 30, 2) ?></span></span>
                        <span class="ks-text">в день</span>
                    </td>
                </tr>
            </table>
            <table class="ks-projects-grid-board-tasks-statistics" style="height: 80px">
                <tr>
                    <td class="ks-statistic-item">
                        <span class="ks-text">При добавлении пакетов и/или разделов происходит списание за суточное размещение следующего дня.</span>
                    </td>
                </tr>
            </table>
            <div style="text-align: center; padding: 20px; border-top: 1px solid #dee0e1">
                <button class="btn btn-primary btn-lg" onclick="save_tariff()">Сохранить тариф</button>
            </div>
    </div>


</div>



