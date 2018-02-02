<tr>
    <td><input class='click_checkbox' onclick="verify_chboxes()" name="catalog_check[<?= isset($catalog_id) ? $catalog_id : "" ?>]" type='checkbox' <?= isset($checked) ? $checked : "" ?> /></td>
    <td><a href="/product/catalog/?catalog_id=<?= isset($catalog_id) ? $catalog_id : "" ?>"><?= isset($name) ? $name : "" ?></a></td>
    <td><?= isset($count_products) ? $count_products : "" ?> <?= isset($count_goods) ? $count_goods : "" ?></td>
</tr>