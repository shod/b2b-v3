<h4>Заявки на добавления товаров, прочие вопросы</h4>
<form id="question_form" method="post" action="/site/send-question">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
    <textarea id="seller_question" name="question" class="form-control" style="height: 200px;"></textarea>
</form>
<div class="content-end" style="padding-top: 20px;">
    <button onclick="if($('#seller_question').val() != ''){ajaxSubmit(this,'question_form')}else{$.alert({title: 'Вы не задали вопрос!',type: 'blue',content: 'Для продолжения задайте вопрос и нажмите отправить или закройте форму.'});}" class=" btn btn-primary">Отправить</button>
</div>