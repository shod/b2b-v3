<h4>Заявки на добавления товаров, прочие вопросы</h4>
<form id="question_form" method="post" action="/site/send-question">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <textarea name="question" class="form-control" style="height: 200px;"></textarea>
</form>
<div class="content-end" style="padding-top: 20px;">
    <button onclick="ajaxSubmit(this,'question_form')" class=" btn btn-primary">Отправить</button>
</div>