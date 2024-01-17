<div class="wtitle">Возобновление</div>
<div class="wcontent">
    <form method="get" id="ajaxForm" action="/selller/activate-process">
        <input type="hidden" name="action" value="activate" />
        <p style="margin-bottom: 10px">Ценовые предложения и контактная информация вашего магазина станут доступны для посетителей <?= \Yii::$app->params['migom_name'] ?>. </p>
        <p style="margin-bottom: 10px">За возобновление аккаунта плата не взимается.</p>
        <p style="margin-bottom: 30px">Возобновить аккаунт? </p>
        <div style="text-align: center">
            <input class="btn btn-success" type="button" value="Да" style="width: 100px;" onclick="ajaxSubmit(this,'ajaxForm')"/>
            <input class="btn btn-danger" type="button" value="Нет" onclick="$('#myDefaultModal').modal('hide')"  style="width: 100px;"/>
        </div>
    </form>
</div>
