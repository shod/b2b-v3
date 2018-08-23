<?php
$this->title = 'Статистика магазина';
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
                                <?php if (isset($data) && $data != ""): ?>
                                    <div><h5>Статистика по месяцам</h5></div>
                                    <div class="alert alert-danger ks-solid-light" role="alert">Внимание! В период
                                        1.02.2018 - 1.06.2018 произошел сбой в системе сбора статистики. Статистика
                                        выводится не в полном объеме.
                                    </div>
                                    <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Дата</th>
                                            <th>Количество просмотров <br>товаров в месяц</th>
                                            <th>Количество показов<br>контактной информации</th>
                                            <th>Количество переходов<br> на сайт в месяц</th>
                                            <th>Количество <br>SMS-заказов <?= isset($po_active) ? $po_active : "" ?>
                                            </th>
                                            <th>Контекстная реклама</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Дата</th>
                                            <th>Количество просмотров <br>товаров в месяц</th>
                                            <th>Количество показов<br>контактной информации</th>
                                            <th>Количество переходов<br> на сайт в месяц</th>
                                            <th>Количество <br>SMS-заказов <?= isset($po_active) ? $po_active : "" ?>
                                            </th>
                                            <th>Контекстная реклама</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        <?= isset($data) ? $data : "" ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    Для возобновления свяжитесь с Вашим менеджером по тел.  <a
                                            href="tel:+375 29 111-45-45">+375 29 111-45-45 (Velcom)</a>   и еmail: <a
                                            href="mailto:sale@migom.by">sale@migom.by</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--div class="row">
                <div class="col-lg-12">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <div class="container-fluid" style="overflow: auto">
                                <?php if (isset($alert) && $alert != ""): ?>
                                    <div><h5>Жалобы покупателей на недоступность телефонов, сайта или электронной
                                            почты</h5></div>
                                    <?= isset($alert) ? $alert : "" ?>
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
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div-->
        </div>
    </div>
</div>
