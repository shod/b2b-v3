<?php
$this->title = "Новости площадки";
?>

<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <ul class="pagination pagination-sm" style="margin-bottom: 30px;">
                        <?= isset($pages) ? $pages : "" ?>
                    </ul>
                </div>
                <div class="col-lg-12">
                    <?= $items ?>

                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <ul class="pagination pagination-sm" style="margin-top: 30px;">
                        <?= isset($pages) ? $pages : "" ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


