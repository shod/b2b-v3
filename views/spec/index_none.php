<?php
$this->title = 'Спецпредложения';
$this->registerJsFile(Yii::$app->request->baseUrl.'/web/scripts/js/spec.js',['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="row">
    <div class="col-lg-6">
        <div class="ks-nav-body">
            <div class="ks-nav-body-wrapper">
                Для участия в спецпредложениях баланс должен быть не менее <?= isset($min_balance) ? $min_balance : "" ?> ТЕ.
            </div>
        </div>
    </div>
    <div class="col-lg-6 content-end">
        <div class="ks-nav-body">
            <div class="ks-nav-body-wrapper">
                <a href="/spec/add" class="btn btn-primary ks-light">Добавить спецпредложения</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="ks-nav-body">
            <div class="ks-nav-body-wrapper">
                <div class="container-fluid" style="overflow: auto">
                    <form method="post" id="formSpec" action="/spec/process">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <input type="hidden" name="action" value="save" />

                        <table id="ks-datatable" class="table table-striped table-bordered table-condenced" width="100%">
                            <thead>
                            <tr>
                                <th class="head-title">Категория</th>
                                <th class="head-title">Показывать везде</th>
                                <th class="head-title">Показов вчера</th>
                                <th class="head-title">Прогноз расхода<br/>(за сутки)</th>
                                <th class="head-title bet">Позиция и цена</th>
                                <th class="head-title">Ваша ставка <br />за 1000 показов</th>
                                <td></td>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="head-title">Категория</th>
                                <th class="head-title">Показывать везде</th>
                                <th class="head-title">Показов вчера</th>
                                <th class="head-title">Прогноз расхода<br/>(за сутки)</th>
                                <th class="head-title bet">Позиция и цена</th>
                                <th class="head-title">Ваша ставка <br />за 1000 показов</th>
                                <td></td>
                            </tr>
                            </tfoot>
                            <tbody>

                            <?= isset($data) ? $data : "" ?>

                            </tbody>
                        </table>
                        <div class="content-end" style="margin-bottom: 15px;">
                            <input class="btn btn-primary" type="submit" value="Сохранить"/>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="alert alert-primary ks-solid-light" role="alert">
            <h4><?= $title ?></h4>
            <br>
            <?= stripcslashes($text) ?>
        </div>
    </div>
</div>
