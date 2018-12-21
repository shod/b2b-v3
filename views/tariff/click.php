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
                    <div class="alert alert-success  ks-solid-light" role="alert">Для смены обязательной информации обратитесь к вашему менеджеру <a href="mailto:sale@migomby.by">sale@migomby.by</a> или по телефону <a href="tel:+375291124545">+375(29)112-45-45</a> .</div>
                </div>
            </div>
            <?= $order_setting ?>
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
                                        <input style="margin-bottom: 5px;" class="btn btn-primary btn-sm" type="submit" value="Подтвердить актуальность цен"  onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                                    </form>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 content-end">
                                    <button class="btn btn-primary" data-remote="/product/get-curs/" data-toggle="ajaxModal"
                                            data-target=".bd-example-modal-lg">
                                        <span class="la la-cog ks-icon"></span>
                                        <span class="ks-text">Настройка валюты</span>
                                    </button>

                                </div>
                            </div>

                        </div>
                        <div class="card-block" style="overflow: auto">
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
