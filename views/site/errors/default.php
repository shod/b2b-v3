<div class="ks-page-content-body ks-error-page">
    <div class="ks-error-code"><?= Yii::$app->response->statusCode ?></div>
    <div class="ks-error-description">Что-то пошло не так, и мы будем благодарны, если вы свяжетесь с нами и опишите проблему <b>email:</b> <?= Yii::$app->params['saleManager'] ?> </div>
    <a href="/" class="btn btn-primary ks-light">Перейти на главную</a>
</div>