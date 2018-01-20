<?php
    $this->title = "Товары в продаже";
    $this->registerJs(
        " 
           $( document ).ready(function() {
            work_type(false);
            $('ul a').click(function() { getAjaxData('theForm',$(this).prop('href'), 'productTable');return false;});
           });
        "
    );
?>
<div class="row">
    <div class="col-xl-12 ks-draggable-column">
        <div class="card panel panel-table" data-dashboard-widget>
            <h5 class="card-header">
                Раздел <?= $catalog_name ?>
                <div class="ks-controls">
                    <a href="#" class="ks-control" data-control-fullscreen>
                        <span class="ks-icon la la-expand" data-control-icon></span>
                    </a>
                </div>
            </h5>
            <div class="card-block ks-scrollable" data-height="700" data-widget-content>
                <div class="table-addition">
                    <div>
                        <form id="theForm" class="form-inline" method="get">
                            <select name="mode" onchange="getAjaxData('theForm','/product/get-data-products/?','productTable')" class="form-control">
                                <option value="0">Мои товары</option>
                                <option value="all" <?= isset($selected_mode_all) ? $selected_mode_all : "" ?>>Каталог Migom.by</option>
                            </select>

                            <select name="catalog_id" onchange="getAjaxData('theForm','/product/get-data-products/?', 'productTable')" class="form-control">
                                <!--option value="0">Показать все</option-->
                                <?= isset($catalog_options) ? $catalog_options : "" ?>
                            </select>
                            <select id="brands" name="brand" onchange="getAjaxData('theForm','/product/get-data-products/?', 'productTable')" class="form-control">
                                <option value="0">Производитель</option>
                                <?= isset($brand_options) ? $brand_options : "" ?>
                            </select>

                            <input class="form-control" type="text" name="search" placeholder="Наименование" value="<?= isset($search) ? $search : "" ?>" style="width: 150px"/>
                            <input class="btn btn-primary" type="button" value="Найти" onclick="getAjaxData('theForm','/product/get-data-products/?', 'productTable')"/>

                        </form>
                    </div>
                    <div class="row" style="width: 99%">
                        <div class="col-lg-6">
                            <ul id="pages" class="pagination pagination-sm" style="margin: 10px;">
                                <?= isset($pages) ? $pages : "" ?>
                            </ul>

                        </div>
                        <div class="col-lg-6 content-end">
                            <label class="custom-control custom-checkbox">
                                <input  id="work_type" onclick="work_type(this.checked)" type="checkbox" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Дополнительный режим</span>
                            </label>
                        </div>
                    </div>




                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th style="width: 10px"></th>
                        <th  style="width: 200px">Наименование товара</th>
                        <th style="width: 70px">Цена <br /> $vars[currency]</th>
                        <th class="product-item" style="width: 350px">
                            Особенности (цвет, комплектация, гарантия) <br />
                            <textarea style="width: 100%" class="form-control" id="default_desc"></textarea>
                        </th>
                        <th class="product-item" style="width: 150px">
                            Наличие <br /><br />
                            <select id="default_wh_state" class="form-control">
                                <option>Выберите...</option>
                                <option value="1">В наличии </option>
                                <option value="2">Под заказ </option>
                                <option value="3">Отсутствует </option>
                            </select>
                        </th>
                        <th class="product-item" style="width: 50px"><nobr>Срок <br>доставки</nobr> (дн.)<br />
                            <input type="text" id="default_delivery_day" class="form-control" style="width: 50px">
                        </th>
                        <th class="product-item" style="width: 50px">Гарантия (мес.)<br />
                            <input type="text" id="default_garant" class="form-control" style="width: 50px">
                        </th>
                        <th class="product-addation-tr" style="width: 200px">
                            Изготовитель<br />
                            <textarea style="width: 100%" class="form-control" id="default_desc_manufacturer"></textarea>
                        </th>
                        <th class="product-addation-tr" style="width: 200px">
                            Импортер<br />
                            <textarea style="width: 100%" class="form-control" id="default_desc_import"></textarea>
                        </th>
                        <th class="product-addation-tr" style="width: 200px">
                            Сервисные центры<br />
                            <textarea style="width: 100%" class="form-control" id="default_desc_service"></textarea>
                        </th>
                        <th class="product-addation-tr" style="width: 50px">
                            Срок <br>службы (мес.)<br />
                            <input type="text" id="default_term_use" class="form-control" style="width: 50px">
                        </th>
                        <th class="product-addation-tr" style="width: 200px" title="Ссылка на вашу страницу товара">Ссылка</th>
                    </tr>
                    </thead>
                    <tbody id="productTable">
                    <?= isset($data) ? $data : "" ?>
                    </tbody>
                </table>
                <div class="table-addition">
                    <ul id="pages-2" class="pagination pagination-sm" style="margin: 10px;">
                        <?= isset($pages) ? $pages : "" ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>