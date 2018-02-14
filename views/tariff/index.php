<?php
    $this->title = 'Калькулятор тарифов';
    $this->registerJsFile(Yii::$app->request->baseUrl.'/web/scripts/js/tariff.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>


    <div class="ks-page-content-body ks-projects-grid-board">




        <div class="ks-projects-grid-board-projects">
            <div class="ks-projects-grid-board-header">
                <div class="ks-search">
                    <div class="input-icon icon-right icon icon-lg icon-color-primary">
                        <input id="input-group-icon-text" type="text" class="form-control" placeholder="Search">
                        <span class="icon-addon">
                                    <span class="la la-search"></span>
                                </span>
                    </div>
                </div>
            </div>
            <div class="ks-projects-grid-board-body ks-scrollable">
                <div class="ks-scroll-wrapper ks-rows-section">
                    <div class="row">


                       <?= isset($pack_items) ? $pack_items : "" ?>
                    </div>



                </div>
            </div>
        </div>







        <div class="ks-projects-grid-board-tasks">
            <div class="ks-projects-grid-board-tasks-list">
                <div class="ks-projects-grid-board-tasks-header">
                    <span class="ks-text"><h3>Ваш тариф</h3></span>
                    <div class="ks-progress ks-progress-inline">
                        <div>
                            Стоимость указана без учета НДС
                        </div>
                    </div>
                </div>
                <div class="ks-projects-grid-board-tasks-body ks-scrollable">
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
                        </label>
                    </div>
                </div>
            </div>


            <table class="ks-projects-grid-board-tasks-statistics">
                <tr>
                    <td rowspan="2" class="ks-chart">
                        <div id="ks-projects-progress-chart" class="ks-radial-progress-chart ks-purple"></div>
                    </td>
                    <td class="ks-statistic-item">
                        <span class="ks-amount">22</span>
                        <span class="ks-text">projects finished</span>
                    </td>
                </tr>
                <tr>
                    <td class="ks-statistic-item">
                        <span class="ks-amount">4</span>
                        <span class="ks-text">projects in progress</span>
                    </td>
                </tr>
            </table>

        </div>


    </div>

