<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h4>Мы рады сообщить Вам о запуске новой версии панели управления магазина на <?= \Yii::$app->params['migom_name'] ?>! </h4>
        </div>
    </div>

    <!--div class="row">
        <div class="col-lg-2"><img src="/img/anounce/web-development.png" style="height: 70px;"/> </div>
        <div class="col-lg-10">
            <h5>Мы заметили, что работа в старой B2B вызывала некоторые трудности:</h5>
            - слишком сложно было работать с навигацией;<br>
            - не было удобной возможности использовать B2B с мобильных устройств;<br>
            - устаревший дизайн требовал переработки.<br>
        </div>
    </div-->
    <div class="row">
        <div class="col-lg-2" style="text-align: center"><img src="/img/anounce/settings.png" style="height: 70px; padding-bottom: 10px"/> </div>
        <div class="col-lg-10">
            <!--h5>Поэтому мы запустили процесс обновления B2B</h5-->
            - мы обновили дизайн и улучшили юзабилити проекта;<br>
            - ускорили работу и обработку страниц;<br>
            - адаптировали проект для работы с мобильных устройств.<br>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2" style="text-align: center"><img src="/img/anounce/data.png" style="height: 70px;  padding-bottom: 10px"/> </div>
        <div class="col-lg-10">Для комфортного перехода на новую версию B2B, мы оставили возможность работать в старой версии еще 3 месяца. Для того чтобы перейти к старой версии нажмите кнопку в шапке панели управления.
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2" style="text-align: center"><img src="/img/anounce/web-optimization.png" style="height: 70px;  padding-bottom: 10px"/> </div>
        <div class="col-lg-10">
            <h5>Несколько советов для комфортной работы в новой версии B2B: </h5>
            - в верхней панели кнопка "Тур по изменениям" – инструкция по навигации новой версии; <br>
            - также в верхней панели представлена оперативная информация по аккаунту (статус магазина, баланс) и контакты технической поддержки;<br>
            - в боковой панели основное меню: баланс, продвижение магазина, настройка магазина, мои товары, аналитика и т.д.  <br>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2" style="text-align: center"><img src="/img/anounce/consulting.png" style="height: 70px;  padding-bottom: 10px"/> </div>
        <div class="col-lg-10">
            <p>Будем рады получить от Вас обратную связь и ответим на любые вопросы <br> по тел. <a href="tel:+375291124545">+375 29 112-45-45 (Velcom)</a> и еmail: <a href="mailto:<?= Yii::$app->params['saleManager'] ?>"><?= Yii::$app->params['saleManager'] ?></a></p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: center">
            <h4>Спасибо, что Вы с нами! </h4>
        </div>
        <div class="col-lg-12" style="text-align: center">
            <img src="/img/anounce/team.png" style="height: 100px;"/>
        </div>
        <div class="col-lg-12" style="text-align: center">
            <p>Ваша команда <?= \Yii::$app->params['migom_name'] ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div style="text-align: center; padding: 15px;">
                <button class="btn btn-primary" onclick="saveAjaxFeturePopup()">Все понятно</button>
            </div>
        </div>
    </div>

</div>


