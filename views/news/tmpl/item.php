<div class="card">
    <div class="card-header">
        <?= $news->title ?>
    </div>
    <div class="card-block">
        <?= stripcslashes($news->text) ?>
        <div class="content-end" style="color: grey"><?= $news->date ?></div>
    </div>
</div>
