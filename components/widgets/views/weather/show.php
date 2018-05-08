<div class="card ks-widget-weather-and-datetime ks-sunny" style="height: 100%;">
    <div class="ks-widget-weather-and-datetime-weather-block">
        <div class="ks-widget-weather-and-datetime-weather-block-amount">
            <?= $weather['main']['temp'] > 0 ? '+' . $weather['main']['temp'] : '-' . $weather['main']['temp'] ?>º
        </div>
        <div class="ks-widget-weather-and-datetime-weather-block-type">
            <?= $weather['weather'][0]['description'] ?>
        </div>
    </div>
    <div class="ks-widget-weather-and-datetime-datetime-block">
        <div class="ks-widget-weather-and-datetime-datetime-block-datetime">6:18 pm</div>
        <div class="ks-widget-weather-and-datetime-datetime-block-location">Минск</div>
        <span style="font-size: 60px" class="ks-icon wi wi-wu-<?= strtolower($weather['weather'][0]['main']) ?>"></span>
    </div>
</div>