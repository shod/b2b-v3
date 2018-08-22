<div class="card">
    <div class="card-header" style="line-height: 1.3;">
        <?= $news->title ?>
    </div>
    <div class="card-block">
        <?= stripcslashes($news->text) ?>
        <div class="content-end" style="color: grey"><?= $news->date ?></div>
    </div>
</div>
