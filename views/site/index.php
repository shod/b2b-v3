<?php
$this->title = "Добро пожаловать в b2b.".\Yii::$app->params['migom_domain']."!";
$this->registerJsFile(Yii::$app->request->baseUrl . '/scripts/js/charts.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs(
    "
    $(document).ready(function () {
        var now = new Date();
        var month = now.getUTCMonth()+1;
        get_chart(now.getUTCFullYear()+'-'+month,'areaspline');
    });
    "
);
?>
<div class="ks-page-content-body" style="background-color: #f4f4f4">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'balance', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div><br>
                    <div data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'promise', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'promotion', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'products', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'auction', 'sid' => $sid, 'type' => 'fix']) ?>" data-toggle="ajaxWidget"></div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'actions', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'reviews', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'complaint', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                <div class="col-xl-3 col-lg-3 col-md-6" data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'news', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
            </div>

            <div class="row">
                <!--div class="col-xl-10 col-lg-10 col-md-9">
                    <div class="card card-block" style="height: 100%">
                        <div id="chart"></div>
                    </div>
                </div-->
                <div class="col-xl-2 col-lg-2 col-md-3">
                    <!--div data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'currency', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div><br-->
                    <div data-remote="<?= yii\helpers\Url::to(['site/widget', 'widget_name' => 'weather', 'sid' => $sid]) ?>" data-toggle="ajaxWidget"></div>
                </div>

            </div>


        </div>
    </div>
</div>

