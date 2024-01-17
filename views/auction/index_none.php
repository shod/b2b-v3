<?php
$this->title = 'Аукционы';
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            Для участия в спецпредложениях баланс должен быть не
                            менее <?= isset($min_balance) ? $min_balance : "" ?> <?= Yii::$app->params['currency'];?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 content-end">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <a href="/auction/add" class="btn btn-primary ks-light">Добавить аукцион</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
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

                                    <?= isset($data) ? $data : "" ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

