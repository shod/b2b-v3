<tr class="<?= isset($class_success) ? $class_success : "" ?> <?= isset($class_danger) ? $class_danger : "" ?>">

    <td><a class="ks-color-primary" href="http://www.migom.by/<?= isset($product_id) ? $product_id : "" ?>"
           target="_blank"><?= isset($basic_name) ? $basic_name : "" ?></a></td>
    <td><a class="ks-color-primary"
           href="/?admin=products&mode=0&catalog_id=<?= isset($catalog_id) ? $catalog_id : "" ?>&brand=0&search=<?= isset($basic_name) ? $basic_name : "" ?>"
           target="_blank"><?= isset($seller_cost_by) ? $seller_cost_by : "" ?></a></td>
    <td><?= isset($min_cost) ? $min_cost : "" ?></td>
    <td><?= isset($max_cost) ? $max_cost : "" ?></td>
    <td><a class="ks-color-primary"
           data-remote="/statistic/get-analysis/?pid=<?= isset($product_id) ? $product_id : "" ?>"
           data-toggle="ajaxModal" data-target=".bd-example-modal-lg"><?= isset($cnt_cost) ? $cnt_cost : "" ?></a></td>
    <td><?= isset($catalog_name) ? $catalog_name : "" ?></td>
    <td>
        <form id="form_<?= $ps_id ?>" method="post" action="/product/change-cost">
            <input type="hidden" name="_csrf"
                   value="<?= Yii::$app->request->getCsrfToken() ?>"/>
            <input type="hidden" name="ps_id" value="<?= $ps_id ?>">

            <div class="input-group">
                <input type="text" class="form-control" name="cost" style="min-width: 90px;"
                       value="<?= isset($seller_cost_by) ? $seller_cost_by : "" ?>">
                <span class="input-group-btn">
                                                    <button onclick="ajaxSubmit(this,'form_<?= $ps_id ?>',false)" class="btn btn-default" type="button">ОК</button>
                                                </span>
            </div>

        </form>

    </td>
</tr>