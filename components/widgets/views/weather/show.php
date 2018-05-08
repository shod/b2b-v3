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
        <div class="ks-widget-weather-and-datetime-datetime-block-datetime"><div id="clock"><span class="hour">hh</span>:<span class="min">mm</span></div></div>
        <div class="ks-widget-weather-and-datetime-datetime-block-location">Минск</div>
        <span style="font-size: 60px" class="ks-icon wi wi-wu-<?= strtolower($weather['weather'][0]['main']) ?>"></span>
    </div>
</div>
<script type="text/javascript">
    function update() {
        var clock = document.getElementById('clock');

        var date = new Date(); // (*)

        var hours = date.getHours();
        if (hours < 10) hours = '0' + hours;
        clock.children[0].innerHTML = hours;

        var minutes = date.getMinutes();
        if (minutes < 10) minutes = '0' + minutes;
        clock.children[1].innerHTML = minutes;
    }
    function clockStart() { // запустить часы
        timerId = setInterval(update, 1000);
        update(); // (*)
    }
    clockStart();
</script>