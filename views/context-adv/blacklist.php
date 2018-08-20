<?php
$this->title = "Управление разделами для Яндекс.Директ и Google AdWords";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">На этой странице Вы можете управлять разделами с товарами Вашего магазина для
                    участия в контекстной рекламе.
                    Вы можете выбрать как все товары магазина, так и продвигать по конкретному разделу.
                </div>
            </div>
            <div class="row" data-dashboard-widget>
                <div class="col-lg-12">

                    <form method="post" action="/context-adv/process">
                        <input style="margin-bottom: 10px;" type='submit' value='Сохранить' class='btn btn-primary'/>
                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                        <input type="hidden" name="action" value="save_blacklist"/>
                        <div class='mydiv'>
                            <table class="table table-bordered table-striped table-condensed">
                                <tr>
                                    <th style="width:20px">Вкл. <input type='checkbox' id="all_check"
                                                                       name='all_checked_rule'
                                                                       onclick='check_all(this.checked)'/></th>
                                    <th>Раздел</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <?= isset($data) ? $data : "" ?>
                            </table>
                        </div>
                        <input type='submit' value='Сохранить' class='btn btn-primary'/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>