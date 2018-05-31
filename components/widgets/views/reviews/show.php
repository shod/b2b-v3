<div class="card ks-card-widget ks-widget-payment-table-invoicing" style="height: 100%;">
    <h5 class="card-header">
        Последний отзыв
    </h5>
    <div class="card-block">
        <div class="ks-item">
            <span><?= $review['title'] ?></span><br>
            <span class="ks-card-widget-datetime"><?= $review['review'] ?></span><br>
        </div>
        <div>
            <div style="float: right"><?= date("d.m.y",$review['date']) ?></div>
            <div><?= $review['name'] ?></div>
        </div>
    </div>
</div>