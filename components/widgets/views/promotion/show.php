<div class="card ks-card-widget ks-widget-tasks-statuses-progress" style="height: 100%;">
    <h5 class="card-header">
        Использование продвижения

        <div class="dropdown ks-control">
            <a class="btn btn-link ks-no-text ks-no-arrow" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <span class="ks-icon la la-ellipsis-h"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                <a class="dropdown-item" href="/tariff">Размещение товаров</a>
                <a class="dropdown-item" href="/auction">Аукционы</a>
                <a class="dropdown-item" href="/spec">Спецпредложения</a>
                <!--a class="dropdown-item" href="#">Баннерные спецпредложения</a-->
                <a class="dropdown-item" href="/order/sms">Обратный звонок</a>
                <a class="dropdown-item" href="/reviews/complaint">Отсутствие жалоб на недоступность телефонов</a>
                <a class="dropdown-item" href="/context-adv">Контекстная реклама в Яндекс и Google</a>
            </div>
        </div>
    </h5>
    <div class="card-block">

        <div class="tasks-total-statuses-progress">
            <div class="progress ks-progress-xs">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $quality ?>%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="ks-amount"><?= $quality ?>%</span>
        </div>
    </div>
</div>