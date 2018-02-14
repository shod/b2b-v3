<?php
$this->title = 'Статистика размещения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <div class="container-fluid" style="overflow: auto">
                                <div><h5>Статистика по месяцам</h5></div>
                                <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Количество просмотров <br>товаров в месяц</th>
                                        <th>Количество показов<br>контактной информации</th>
                                        <th>Количество переходов<br> на сайт в месяц</th>
                                        <th>Количество <br>SMS-заказов <?= isset($po_active) ? $po_active : "" ?></th>
                                        <th>Контекстная реклама</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Количество просмотров <br>товаров в месяц</th>
                                        <th>Количество показов<br>контактной информации</th>
                                        <th>Количество переходов<br> на сайт в месяц</th>
                                        <th>Количество <br>SMS-заказов <?= isset($po_active) ? $po_active : "" ?></th>
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
                                <div><h5>Жалобы покупателей на недоступность телефонов</h5></div>
                                <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Телефон</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Дата</th>
                                        <th>Телефон</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    <?= isset($data_complaint) ? $data_complaint : "" ?>
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
