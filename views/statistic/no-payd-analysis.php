<?php


$this->title = 'Анализ цен конкурентов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block">
                        <div class="alert alert-danger  ks-solid-light" role="alert"><strong><?= isset($cost_max) ? $cost_max : '-' ?>%</strong> ваших товаров имеют самую высокую цену</div>
                        <div class="alert alert-success  ks-solid-light" role="alert"><strong><?= isset($cost_min) ? $cost_min : '-' ?>%</strong> ваших товаров имеют самую низкую цену</div>
                        <button data-remote="/statistic/get-access/" data-toggle="ajaxModal"
                                data-target=".bd-example-modal-lg" class="btn btn-primary">Получить доступ
                        </button><br><br>
                        <h5 class="panel-title">Аналитика цен конкурентов – оперативный и точный мониторинг ценовых
                            предложений, представленных в каталоге товаров MIGOM.by.</h5>
                        <div style="text-align:center; overflow: auto; margin-bottom: 20px">
                            <img style="border: 1px solid black;"
                                 src="/img/design/example_analysis_1.jpg">
                        </div>

                        <h5 class="panel-title">В режиме online проводится анализ прайса вашего магазина в сравнении с
                            ценами конкурентов,
                            на основе которого вы можете оперативно актуализировать ценовые предложения для
                            покупателей.</h5>

                        <div style="text-align:center;overflow: auto; margin-bottom: 20px">
                            <img style="border: 1px solid black;"
                                 src="/img/design/example_analysis_2.jpg">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
