<?php
$this->title = 'Калькулятор тарифов';
$this->registerJsFile(Yii::$app->request->baseUrl . '/web/scripts/js/tariff.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

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

<div class="ks-page-content-body ks-projects-grid-board">


    <div class="ks-projects-grid-board-projects">
        <div class="ks-projects-grid-board-header">
            <div class="ks-search">
                <div class="input-icon icon-right icon icon-lg icon-color-primary">
                    <input id="input-group-icon-text" type="text" class="form-control" placeholder="Найти" onkeyup="search_str(this.value);">
                    <span class="icon-addon">
                                    <span class="la la-search"></span>
                                </span>
                </div>
            </div>
        </div>
        <div class="ks-projects-grid-board-body ks-scrollable">
            <div class="ks-scroll-wrapper ks-rows-section">
                <div class="row" style="padding-left: 15px;">
                    <h3>Пакетные предложения</h3>
                </div>
                <div class="row">


                    <?= isset($pack_items) ? $pack_items : "" ?>
                </div>

                <div class="row" style="padding-left: 15px;">
                    <h3>Разделы</h3>
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
                <span class="ks-text"><h4>Ваш тариф</h4></span>
                <div class="ks-progress ks-progress-inline">
                    <div>
                        Стоимость указана без учета НДС
                    </div>
                </div>
            </div>
            <div class="ks-projects-grid-board-tasks-body ks-scrollable">
                <div style="text-align: center; padding-top: 10px;padding-bottom: 0px;"><strong>ПАКЕТЫ</strong></div>
                <div class="jspPane-padding">
                    <?= $pack_lines ?>
                </div>
                <div style="text-align: center; padding: 20px; border-bottom: 1px solid #dee0e1">Всего <?= $pack_sum ?>
                    ТЕ
                </div>
                <!--div style="text-align: center; padding-top: 10px;padding-bottom: 0px;"><strong>РАЗДЕЛЫ</strong></div>
                <div class="jspPane-padding">
                    <label class="custom-control custom-checkbox ks-checkbox ks-checkbox-success">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Анлим</span>
                    </label>
                    <label class="custom-control custom-checkbox ks-checkbox ks-checkbox-success">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Красота и здоровье</span>
                    </label>
                    <label class="custom-control custom-checkbox ks-checkbox ks-checkbox-success">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Телефония</span>
                        <span class="custom-control-cost" style="float: right">130 ТЕ</span>

                    </label>
                </div>
                <div style="text-align: center; padding: 20px; border-bottom: 1px solid #dee0e1">Всего 150 ТЕ</div-->
            </div>
        </div>


        <table class="ks-projects-grid-board-tasks-statistics">
            <tr>
                <td class="ks-statistic-item">
                    <span class="ks-amount" style="color: rgb(37, 98, 143)"><?= $pack_sum ?> ТЕ</span>
                    <span class="ks-text">в месяц</span>
                </td>
            </tr>
            <tr>
                <td class="ks-statistic-item">
                    <span class="ks-amount"><?= $pack_sum / 30 ?></span>
                    <span class="ks-text">в день</span>
                </td>
            </tr>
        </table>
        <div style="text-align: center; padding: 20px; border-top: 1px solid #dee0e1">
            <button class="btn btn-primary btn-lg">Сохранить тариф</button>
        </div>

    </div>


</div>



