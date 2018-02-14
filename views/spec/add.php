<?php
$this->title = 'Добавление спецпредложений';

?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">

            <div class="row">
                <div class="col-lg-6">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 content-end">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <a href="/spec" class="btn btn-primary ks-light">Мои спецпредложения</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ks-nav-body">
                        <div class="ks-nav-body-wrapper">
                            <div class="container-fluid" style="overflow: auto">
                                <form method="post" action="/spec/process">
                                    <input type="hidden" name="_csrf"
                                           value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                    <input type="hidden" name="action" value="add"/>

                                    <table class="table table-striped table-bordered table-condenced">
                                        <tr class="active">
                                            <th>Категория</th>
                                            <th>Кол-во участников</th>
                                            <th>Интервал цен</th>
                                            <th></th>
                                        </tr>

                                        <?= isset($data) ? $data : "" ?>

                                    </table>
                                    <div class="content-end"><input class="btn btn-primary" type="button"
                                                                    value="Добавить"
                                                                    onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>