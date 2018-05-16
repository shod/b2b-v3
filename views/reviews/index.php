<?php
$this->title = "Отзывы";
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row" style="margin-top: 0px;">
                <div class="col-lg-12">
                    <div class="card panel panel-default ks-solid ks-bg-light-gray">
                        <div class="card-block">
                            <h3 class="card-title">Модератор отзывов</h3>
                            <p class="card-text"><b>E-mail:</b> <a href="mailto:report@migom.by">report@migom.by</a> </p>
                            <p class="card-text"><b>Время работы:</b> с 9:00 до 18:00</p>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <form method="post" action="/reviews/save-answers">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                <div class="row" style="margin-top: 0px;">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <ul class="pagination pagination-sm" style="margin: 10px;">
                            <?= isset($pages) ? $pages : "" ?>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 content-end">
                        <input type="submit" class="btn btn-primary" value="Сохранить">
                    </div>
                </div>
                <div class="row" style="margin-top: 0px;">
                    <div class="col-lg-12">
                        <div class="ks-nav-body">
                            <div class="ks-nav-body-wrapper">
                                <div class="container-fluid" style="overflow: auto">
                                    <span class="rl-li fa fa-star active"></span>
                                    <table id="ks-datatable" class="table table-striped table-bordered table-condenced"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Отзыв</th>
                                            <th>Ваш ответ</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Отзыв</th>
                                            <th>Ваш ответ</th>
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
                <div class="row" style="margin-top: 0px;">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <ul class="pagination pagination-sm" style="margin: 10px;">
                            <?= isset($pages) ? $pages : "" ?>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 content-end">
                        <input type="submit" class="btn btn-primary" value="Сохранить">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>