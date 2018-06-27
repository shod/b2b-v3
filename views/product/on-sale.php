<?php
$this->title = "Товары в продаже";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">

            <?php if ($prod_stat_cnt_all == 0): ?>
                Загрузите ваш прайс! <a href="/product/price">Импорт/экспорт прайсов</a>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <p>Всего товаров:
                            <mark><?= isset($prod_stat_cnt_all) ? $prod_stat_cnt_all : "" ?></mark>
                            ,
                            из них активных
                            <mark><?= isset($prod_stat_cnt_bill) ? $prod_stat_cnt_bill : "" ?></mark>
                            .<a href="/tariff">
                                Процент активных:
                                <mark><?= isset($prod_active_percent) ? $prod_active_percent : "" ?>%</mark>
                            </a></p>
                        <p><a href="/tariff" class="btn btn-primary btn-sm">Подключить еще разделы или пакеты</a></p>

                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <p><span class="badge badge-default">Обновлены <?= isset($status) ? $status : "" ?></span></p>
                        <form type="get" action="/product/process/">
                            <input type="hidden" name="action" value="refresh">
                            <p><input type="submit" class="btn btn-primary  btn-sm"
                                      value="Подтвердить актуальность цен">
                            </p>
                        </form>

                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 content-end">
                        <button data-remote="/product/get-curs/" data-toggle="ajaxModal"
                                data-target=".bd-example-modal-lg" class="btn btn-primary">Настройка валюты
                        </button>

                    </div>
                </div>
            <?php endif; ?>


            <div class="row" style="margin-top: 0px;">
                <div class="col-lg-12">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <div class="container-fluid" style="overflow: auto">
                                <span class="rl-li fa fa-star active"></span>
                                <table id="ks-datatable" class="table table-bordered table-condenced" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Наименование</th>
                                        <th>Ваши предложения</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Наименование</th>
                                        <th>Ваши предложения</th>
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