<?php
$this->title = "Новости площадки";
?>

<div class="ks-page-content-body">
    <div class="ks-dashboard-tabbed-sidebar">
        <div class="ks-dashboard-tabbed-sidebar-widgets">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <ul class="pagination pagination-sm" style="margin: 10px;">
                        <?= isset($pages) ? $pages : "" ?>
                    </ul>
                </div>
                <div class="col-lg-12">
                    <?= $items ?>

                </div>
            </div>
        </div>
    </div>
</div>


