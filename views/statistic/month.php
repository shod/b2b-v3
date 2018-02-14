<?php
$this->title = 'Статистика размещения за месяц';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs(
    "
    $(document).ready(function () {
        get_chart('$date');
        get_chart_sections('$date');
        get_chart_ctr('$date');
    });
    "
);
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block">
                        <div id="chart"></div>
                    </div>
                    <div class="card card-block">
                        <div id="chart_sections"></div>
                    </div>
                    <div class="card card-block">
                        <div id="chart_sections_small"></div>
                    </div>
                    <div class="card card-block">
                        <div id="chart_ctr"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <div class="container-fluid" style="overflow: auto">
                                <div><h5>Статистика по дням</h5></div>
                                <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Количество кликов</th>
                                        <th>Количество переходов</th>
                                        <th>Контекстная реклама</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Количество кликов</th>
                                        <th>Количество переходов</th>
                                        <th>Контекстная реклама</th>
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <div class="container-fluid" style="overflow: auto">
                                <div><h5>Статистика по разделам</h5></div>
                                <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Раздел</th>
                                        <th>Количество кликов</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Раздел</th>
                                        <th>Количество кликов</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    <?= isset($data_sections) ? $data_sections : "" ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BEGIN BOOTSTRAP MODALS -->
            <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog"
                 aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Статистика</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="la la-close"></span>
                            </button>
                        </div>
                        <div class="modal-body" id="modal-body">
                            <div class="ks-tabs-container ks-tabs-default ks-tabs-no-separator">
                                <ul class="nav ks-nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#" data-toggle="tab" data-target="#detail">Расширенная</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" data-toggle="tab"
                                           data-target="#group">Групповая</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel" id="detail">

                                    </div>
                                    <div class="tab-pane" id="group" role="tabpanel">

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
