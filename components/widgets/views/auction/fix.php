<div class="card ks-card-widget ks-widget-payment-card-rate-details" style="height: 100%;">
    <h5 class="card-header">
        Аукционы
    </h5>
    <div class="ks-tabs-container ks-tabs-default ks-tabs-with-separator ks-tabs-header-default" style="border-bottom: 0px; border-right: 0px; border-left: 0px;">
        <ul class="nav ks-nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-toggle="tab" data-target="#tab4" aria-expanded="true">Фиксированный</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="tab" data-target="#tab5" aria-expanded="false">Онлайн</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab4" role="tabpanel" aria-expanded="true">
                <div class="ks-card-widget-datetime">
                    Фиксированная ставка
                </div>

                <table class="table ks-payment-card-rate-details-table">
                    <tbody>
                    <?= $html_fix ?>
                    </tbody>
                </table>
                <br>
                <a href="/auction" class="btn btn-primary ks-light btn-sm">Перейти к аукционам</a>
            </div>
            <div class="tab-pane" id="tab5" role="tabpanel" aria-expanded="false">
                <div class="ks-card-widget-datetime">
                    Онлайн ставка
                </div>

                <table class="table ks-payment-card-rate-details-table">
                    <tbody>
                    <?= $html_online ?>
                    </tbody>
                </table>
                <br>
                <a href="/auction" class="btn btn-primary ks-light btn-sm">Перейти к аукционам</a>
            </div>
        </div>
    </div>

</div>