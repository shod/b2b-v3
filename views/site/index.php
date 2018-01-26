<?php
$this->title = "Добро пожаловать в b2b.migom.by!";
?>
<?= Yii::$app->user->identity->getId() ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-block">
            <h3>Все страницы для работы</h3>

            <p>1 - <a href="/auction">Аукционы</a></p>
            <p>2 - <a href="/auction/add">Аукционы добавление</a></p>
            <p style="background-color:#b8f8b8">3 -  <a href="/balance/add">Баланс пополнить</a></p>
            <p>4 - <a href="/balance/promise">Баланс обещаный платех</a></p>
            <p>5 - <a href="/balance/akt">Баланс выгрузка актов</a></p>
            <p>6 - <a href="/balance/report">Баласн Финансовый отчет</a></p>
            <p>7 - <a href="/context-adv">Контекстная реклама добавление</a></p>
            <p>8 - <a href="/context-adv/blacklist">Контекстная реклама работа с разделами</a></p>
            <p>9. <a href="/info">Информационная страница</a></p>
            <p>10 - <a href="/news">Страница новостей</a></p>
            <p style="background-color:#b8f8b8">11 - <a href="/order/sms">SMS заказы</a></p>
            <p style="background-color:#b8f8b8">12 - <a href="/product/on-sale">Товары в продаже</a></p>
            <p style="background-color:#b8f8b8">13 - <a href="/product/catalog">Редактирование товаров в разделе</a></p>
            <p>14 - <a href="/product/price">Товары работа с прайсами</a></p>
            <p style="background-color:#b8f8b8">25 - <a href="/statistic/cost-analysis">Анализ цен конкурентов</a></p>
            <p>15 - <a href="/product/tariff">Калькулятор тарифов</a></p>
            <p style="background-color:#b8f8b8">16 - <a href="/reviews">Отзывы</a></p>
            <p>17. <a href="/seller">Продавец сводная информация</a></p>
            <p>18 - <a href="/seller/settings">Продавец настройки</a></p>
            <p>19 - <a href="/seller/delivery">Продавец информация о доставке</a></p>
            <p>20 - <a href="/spec">Спецпредложения</a></p>
            <p>21 - <a href="/spec/add">Спецпредложения добавление</a></p>
            <p style="background-color:#b8f8b8">22 - <a href="/statistic">Статистика</a></p>
            <p style="background-color:#b8f8b8">23 - <a href="/statistic/month">Статистика по месяцам</a></p>
            <p>24. <a href="/banner-spec">Баннерные спецпредложения</a></p>
            <p>25. <a href="/banner-spec/add">Баннерные спецпредложения добавление</a></p>
        </div>
    </div>
</div>
