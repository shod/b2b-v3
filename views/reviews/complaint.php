<?php
$this->title = 'Жалобы покупателей на недоступность телефонов';
?>
<div class="row">
    <div class="col-lg-12">
        <div class="ks-nav-body">
            <div class="ks-nav-body-wrapper">
                <div class="container-fluid" style="overflow: auto">
                    <?= isset($alert) ? $alert : "" ?>

                    <table id="ks-datatable" class="table table-striped table-bordered table-condenced" width="100%">
                        <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Телефон</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Дата</th>
                            <th>Телефон</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <?= isset($data_complaint) ? $data_complaint : "" ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

