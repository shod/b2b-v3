<?php
$this->title = 'Калькулятор тарифов';
$this->registerJsFile(Yii::$app->request->baseUrl . '/scripts/js/tariff.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
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

        <div class="ks-projects-grid-board-body ks-scrollable">
            <div class="ks-scroll-wrapper ks-rows-section">
                <div class="row" style="padding-left: 15px;">
                    <h3>Варианты размещения</h3>
                    <br>
                    <!-- div class="col-lg-3 col-md-3 col-sm-3 content-end">
                        <button class="btn btn-primary" data-remote="/product/get-curs/" data-toggle="ajaxModal" data-target=".bd-example-modal-lg">
                            <span class="la la-cog ks-icon"></span>
                            <span class="ks-text">Настройка валюты</span>
                        </button>
                    </!-->
                </div>
                <div class="row">
                    <?= isset($pack_items) ? $pack_items : "" ?>
                    <div class="col-xl-12 col-lg-12" style="padding-bottom: 20px;">
                        <div class="card panel panel-default ks-project" style="height: 100%;">
                            <h3>Действует система скидок при оплате на 3/6/12 месяцев – 10%, 15%, 25% соответственно</h3>
                        </div>
                    </div>
                </div>
                <p style="color:red">* Cтоимость без учётом НДС</p>

                <div class="row">
                    <div class="col-xl-12 col-lg-12" style="padding-bottom: 20px;">
                        <div class="card panel panel-default ks-project" style="height: 100%;">
                            <h3>
                                * Бонусы можно использовать на дополнительные сервесы для магазина
                                <br />
                                ** Публикации в разделе Новости от магазина Акции, скидки, trade-in от магазина
                                <br />
                                *** Специальные условия от маркетингового агентства MAXI.BY media
                                <br />
                                <br />
                                <span>
                                    ! Прайсы, выгружаемые сверх ограничения, будут автоматически сокращены до необходимого кол-ва.
                                </span>
                            </h3>
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>


    <div class="ks-projects-grid-board-tasks" style="background-color: #f2f2f2;">

        <div class="ks-projects-grid-board-tasks-list">
            <div style="text-align: center; padding: 20px; border-bottom: 1px solid #dee0e1">
                <button class="btn btn-primary btn-lg" onclick="save_tariff()">Сохранить тариф</button>
            </div>
            <!-- div class="ks-projects-grid-board-tasks-header">
                <span class="ks-text">
                    <h4>Ваш тариф</h4>
                </span>
            </!-->
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
                    <span class="ks-amount" style="color: rgb(37, 98, 143)"><span id="sum_all"><?= $all_sum ?></span> TE</span>
                    <span class="ks-text">в месяц</span>
                </td>
            </tr>
            <tr>
                <td class="ks-statistic-item">
                    <span class="ks-amount"><span id="sum_all_day"><?= round($all_sum / 30, 2) ?></span> в день</span>
                </td>
            </tr>
        </table>
        <div style="text-align: center; padding: 20px; margin-bottom:30px; border-top: 1px solid #dee0e1">

        </div>
    </div>


</div>