<?php
$this->title = "Разделы для размещения";
$this->registerJs(
    " 
           $( document ).ready(function() {
                 work_type(false);
           });
           
        "
);
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="height: 100%">

                        <div class="card-block">
                            <div class="row">
                                <div class="col-lg-9">
                                    <p>Доступные разделы для размещения в каталоге.</p>
                                    <p style="color:red">Любой раздел можно отключить от размещения. Для этого нужно убрать галочку и нажать кнопку "Сохранить" внизу страницы.</p>

                                    <form method="post" action="/tariff/process">
                                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                        <input type="hidden" name="action" value="refresh" />
                                        Обновлены <?= $status ?>
                                        <input class="btn btn-primary btn-sm" type="submit" value="Подтвердить актуальность цен"  onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                                    </form>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 content-end">
                                    <button data-remote="/product/get-curs/" data-toggle="ajaxModal"
                                            data-target=".bd-example-modal-lg" class="btn btn-primary">Настройка валюты
                                    </button>

                                </div>
                            </div>

                        </div>
                        <div class="card-block">
                            <form method="post" action="/tariff/process">
                                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                <input type="hidden" name="action" value="save_catalogs" />
                                <table class="table table-bordered table-striped table-condensed">
                                    <tr>
                                        <th style="width:20px">Вкл. <input type='checkbox' name='all_checked_rule' onclick='check_all(this.checked)' /></th>
                                        <th>Раздел</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    <?= $data ?>
                                </table>
                                <input type='submit' value='Сохранить' class='btn btn-primary' />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
