<?php


$this->title = 'Анализ цен конкурентов';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(
    " 
       $(\"#btn_download_csv\").click(function(){
			sids = $(\"#sids\").val();
			var oldValue = $(this).val()
			$(this).attr({value:'Подождите...',disabled: true})
			setTimeout(function(btn){btn.attr({value:oldValue, disabled: false});}, 3000, $(this))
			window.location = '/statistic/cost-analysis-csv/?sids=' + sids
		})
    "
);
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline-secondary mb-3">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="analysis_form" method='get' class="form-inline">


                                        <input type="hidden" name="admin" value="cost_analysis"/>
                                        <select name='section_id' class="form-control">
                                            <option value=''>Выбор категории для анализа</option>
                                            <?= isset($sections) ? $sections : "" ?>
                                        </select>

                                        <select name='brand' class="form-control">
                                            <option value=''>Производитель</option>
                                            <?= isset($brands) ? $brands : "" ?>
                                        </select>

                                        <select name="wh_state" class="form-control">
                                            <option value=''>Наличие</option>
                                            <option value="1" <?= isset($wh_state_1) ? $wh_state_1 : "" ?> >В наличии
                                            </option>
                                            <option value="2" <?= isset($wh_state_2) ? $wh_state_2 : "" ?> >Под заказ
                                            </option>
                                            <option value="3" <?= isset($wh_state_3) ? $wh_state_3 : "" ?> >
                                                Отсутствует
                                            </option>
                                        </select>

                                        <input class="form-control" type="text" name="basic_name"
                                               value="<?= isset($basic_name) ? $basic_name : "" ?>"
                                               placeholder="Название товара">


                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">Формат (id,id..)</span>
                                            <input class="form-control" type="text" id="sids" name="sids"
                                                   value="<?= isset($sids) ? $sids : "" ?>"
                                                   placeholder="исключить ID магазинов">
                                        </div>

                                        <input class="btn btn-primary" type="submit" value="ОК">
                                    </form>
                                </div>
                                <div class="col-lg-12">
                                    <br>
                                    <div class="alert alert-danger  ks-solid-light" role="alert"><strong><?= isset($cost_max) ? $cost_max : '-' ?>%</strong> ваших товаров имеют самую высокую цену</div>
                                    <div class="alert alert-success  ks-solid-light" role="alert"><strong><?= isset($cost_min) ? $cost_min : '-' ?>%</strong> ваших товаров имеют самую низкую цену</div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 0px;">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <ul class="pagination pagination-sm" style="margin: 10px;">
                        <?= isset($pages) ? $pages : "" ?>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 content-end">
                    <button class="btn btn-primary" id="btn_download_csv">Выгрузить все данные в CSV</button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <div class="container-fluid" style="overflow: auto">
                                <table id="ks-datatable" class="table table-striped table-bordered" width="100%"
                                       data-height="500">
                                    <thead>
                                    <tr>
                                        <th>Наименование товарa</th>
                                        <th>Цена в прайсе</th>
                                        <th>Минимальная цена</th>
                                        <th>Максимальная цена</th>
                                        <th>Всего предложений</th>
                                        <th>Категория</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Наименование товарa</th>
                                        <th>Цена в прайсе</th>
                                        <th>Минимальная цена</th>
                                        <th>Максимальная цена</th>
                                        <th>Всего предложений</th>
                                        <th>Категория</th>
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
                    <ul class="pagination pagination-sm">
                        <?= isset($pages) ? $pages : "" ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>