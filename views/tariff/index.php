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
                    <h3>Пакетное размещение</h3><br>
                </div>
                <div class="row" style="padding-left: 15px;">
                    <h5>Готовые пакеты, включающие набор разделов каталога</h5>
                </div>
                <div class="row">


                    <?= isset($pack_items) ? $pack_items : "" ?>
                </div>

                <div class="row" style="padding-left: 15px;">
                    <h3>Размещение по разделам</h3>
                </div>
                <div class="row" style="padding-left: 15px;">
                    <h5>Выбор отдельных разделов каталога для подключения</h5>
                </div>
                <div class="row">
                    <table class="table table-condensed">
                        <?= isset($section_items) ? $section_items : "" ?>
                    </table>

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
                    <div style="text-align: center; padding: 20px; border-bottom: 1px solid #dee0e1">Всего <span
                                id="sum_pack"><?= $pack_sum ?></span>
                        ТЕ
                    </div>
                    <div style="text-align: center; padding-top: 10px;padding-bottom: 0px;"><strong>РАЗДЕЛЫ</strong>
                    </div>
                    <div class="jspPane-padding" id="calc_sections">
                        <?= $section_lines ?>
                    </div>
                    <div style="text-align: center; padding: 20px; border-bottom: 1px solid #dee0e1">Всего <span
                                id="sum_section"><?= $section_sum ?></span> ТЕ
                    </div>
                </div>
            </div>


            <table class="ks-projects-grid-board-tasks-statistics">
                <tr>
                    <td class="ks-statistic-item">
                        <span class="ks-amount" style="color: rgb(37, 98, 143)"><span
                                    id="sum_all"><?= $all_sum ?></span> ТЕ</span>
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



