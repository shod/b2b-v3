<?php
$this->title = "Обратный звонок";
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <h5 class="card-header">
                Заказы
            </h5>
            <div class="card-block">
                <div style="overflow: auto">
                    <table class="table ks-payment-table-invoicing">
                        <tbody>
                        <?= isset($orders) ? $orders : "" ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <h5 class="card-header">
                История
            </h5>
            <div class="card-block">

                <div style="overflow: auto">

                        <ul class="pagination pagination-sm" style="margin-top: 20px">
                            <?= isset($history_pages) ? $history_pages : "" ?>
                        </ul>

                    <table class="table ks-payment-table-invoicing">
                        <tbody>
                        <?= isset($history) ? $history : "" ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
