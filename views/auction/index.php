<?php
    $this->title = 'Аукционы';
?>

<style type="text/css">
    .auction ol li {cursor:pointer;}
</style>
<div class="alert alert-danger ks-solid-light" role="alert"><b>Запрещается использовать сторонний программный код (скрипты). При обнаружении использования подобных скриптов для аккаунта  будет приостановлена услуга "Аукционы" до выяснения всех обстоятельств.</b></div>

<div class="row">
    <div class="col-lg-12">
        <div class="ks-nav-body">
            <div class="ks-nav-body-wrapper">
                <div class="container-fluid" style="overflow: auto">
                    <table id="ks-datatable" class="table table-striped table-bordered table-condenced" width="100%">
                        <thead>
                        <tr>
                            <th style='width:70px'>Категория</th>
                            <th style='width:70px'>Показов вчера</th>
                            <th style='width:70px'>Прогноз расхода за сутки</th>
                            <th>Позиция и цена</th>
                            <th>
                                Ваша ставка за 1000 показов <br />
                                <input class="form-control" type="text" id="cost_all" style="width: 50px; text-align: right;"/>
                            </th>
                            <th>Автобюджет <br />
                                <input type="checkbox" onclick="$('.auction .auto').attr('checked', $(this).attr('checked'))" />
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
                                Ваша ставка за 1000 показов <br />
                                <input class="form-control" type="text" id="cost_all" style="width: 50px; text-align: right;"/>
                            </th>
                            <th>Автобюджет <br />
                                <input type="checkbox" onclick="$('.auction .auto').attr('checked', $(this).attr('checked'))" />
                            <td></td>
                        </tr>
                        </tfoot>
                        <tbody>

                        <?= isset($data_catalog_fix) ? $data_catalog_fix : "" ?>

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
                    <table id="ks-datatable" class="table table-striped table-bordered table-condenced" width="100%">
                        <thead>
                        <tr>
                            <th style='width:70px'>Категория</th>
                            <th style='width:70px'>Показов вчера</th>
                            <th style='width:70px'>Прогноз расхода за сутки</th>
                            <th>Позиция и цена</th>
                            <th>
                                Ваша ставка за 1000 показов <br />
                                <input class="form-control" type="text" id="cost_all" style="width: 50px; text-align: right;"/>
                            </th>
                            <th>Автобюджет <br />
                                <input type="checkbox" onclick="$('.auction .auto').attr('checked', $(this).attr('checked'))" />
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
                                Ваша ставка за 1000 показов <br />
                                <input class="form-control" type="text" id="cost_all" style="width: 50px; text-align: right;"/>
                            </th>
                            <th>Автобюджет <br />
                                <input type="checkbox" onclick="$('.auction .auto').attr('checked', $(this).attr('checked'))" />
                            <td></td>
                        </tr>
                        </tfoot>
                        <tbody>

                        <?= isset($data_catalog_minute) ? $data_catalog_minute : "" ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3><?= isset($title) ? $title : "" ?></h3>
        <p><?= isset($text) ? $text : "" ?></p>
    </div>
</div>



