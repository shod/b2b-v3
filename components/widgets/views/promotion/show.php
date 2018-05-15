<div class="card ks-card-widget ks-widget-tasks-statuses-progress" style="height: 100%;">
    <h4 class="card-header">
        Использование продвижения
    </h4>
    <div class="card-block">
        <table class="tasks-statuses-progress-table">
            <tbody>
            <tr>
                <td style="padding-bottom: 0px" class="ks-progress-status"><a href="/tariff">Размещение товаров</a></td>
                <td style="padding-bottom: 0px" width="1" class="ks-text-light ks-text-right"><?= $vars['products'] ?>%</td>
            </tr>
            <tr>
                <td style="padding-bottom: 0px" class="ks-progress-status"><a href="/auction">Аукционы</a></td>
                <td style="padding-bottom: 0px" width="1" class="ks-text-light ks-text-right"><?= $vars['auction'] ?>%</td>
            </tr>
            <tr>
                <td style="padding-bottom: 0px" class="ks-progress-status"><a href="/spec">Спецпредложения</a></td>
                <td style="padding-bottom: 0px" width="1" class="ks-text-light ks-text-right"><?= $vars['spec'] ?>%</td>
            </tr>
            <tr>
                <td style="padding-bottom: 0px" class="ks-progress-status"><a href="/order/sms">Обратный звонок</a></td>
                <td style="padding-bottom: 0px" width="1" class="ks-text-light ks-text-right"><?= $vars['posms'] ?>%</td>
            </tr>
            <tr>
                <td style="padding-bottom: 0px" class="ks-progress-status"><a href="/reviews/complaint">Отсутствие жалоб на недоступность телефонов</a></td>
                <td style="padding-bottom: 0px" width="1" class="ks-text-light ks-text-right"><?= $vars['phone_fail'] ?>%</td>
            </tr>
            <tr>
                <td style="padding-bottom: 0px" class="ks-progress-status"><a href="/context-adv">Контекстная реклама в Яндекс и Google</a></td>
                <td style="padding-bottom: 0px" width="1" class="ks-text-light ks-text-right"><?= $vars['context'] ?>%</td>
            </tr>
            </tbody>
        </table>

        <hr>

        <div class="tasks-total-statuses-progress">
            <div class="progress ks-progress-xs">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $quality ?>%"
                     aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="ks-amount"><?= $quality ?>%</span>
        </div>
    </div>

</div>