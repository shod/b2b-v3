<tr id="product_tr_<?= $id ?>_<?= $product_id ?>">
    <td  align="center">
            <span style="font-size: 20px; cursor:pointer" class="la la-trash-o icon-bad do_delete"></span>
    </td>
    <td >
        <span style="font-size: 20px;cursor:pointer" class="la la-plus-square-o icon-good do_clone"></span>
        <a href="<?= isset($href_product) ? $href_product : "" ?>" target="_blank"><?= isset($name) ? $name : "" ?></a>
        <input type="hidden" name="del[<?= $product_id ?>][<?= $id ?>]" value="0" class="form-control del_input">
    </td>
    <td  align="center">
        <label>
            <input type="checkbox" name="no_auto[<?= $product_id ?>][<?= $id ?>][]" <?= isset($checked_no_auto) ? $checked_no_auto : "" ?> >
        </label>
    </td>
    <td >
        <input type="text" name="cost[<?= $product_id ?>][<?= $id ?>][]" value="<?= isset($cost) ? $cost : "" ?>" class="form-control cost_input" style="width: 110px">
        <br><?= isset($cost_filter) ? $cost_filter : "" ?>
    </td>
    <td class="desc product-item" style="width: 300px">
        <textarea style="width: 100%" name="desc[<?= $product_id ?>][<?= $id ?>]][]" class="form-control desc_text"><?= isset($description) ? $description : "" ?></textarea>
    </td>
    <td class="product-item">
        <select name="wh_state[<?= $product_id ?>][<?= $id ?>][]" class="wh_state form-control">
            <option value="1" <?= isset($selected_1) ? $selected_1 : "" ?> > В наличии </option>
            <option value="2" <?= isset($selected_2) ? $selected_2 : "" ?> > Под заказ </option>
            <option value="3" <?= isset($selected_3) ? $selected_3 : "" ?> > Отсутствует </option>
        </select>
    </td>
    <td class="product-item">
        <input type="text" class="form-control desc_text_delivery_day" name="delivery_day[<?= $product_id ?>][<?= $id ?>][]" value="<?= isset($delivery_day) ? $delivery_day : "" ?>" class="form-control" style="width: 50px">
    </td>
    <td class="product-item">
        <input type="text" name="garant[<?= $product_id ?>][<?= $id ?>][]" value="<?= isset($garant) ? $garant : "" ?>" class="form-control desc_text_garant" style="width: 50px">
    </td>

    <td class="product-addation-tr manufacturer">
        <textarea style="width: 100%" name="manufacturer[<?= $product_id ?>][<?= $id ?>][]" class="form-control desc_text_manufacturer"><?= isset($manufacturer) ? $manufacturer : "" ?></textarea>
    </td>
    <td class=" product-addation-tr importer">
        <textarea style="width: 100%" name="importer[<?= $product_id ?>][<?= $id ?>][]" class="form-control desc_text_import"><?= isset($importer) ? $importer : "" ?></textarea>
    </td>
    <td class="product-addation-tr service">
        <textarea style="width: 100%" name="service[<?= $product_id ?>][<?= $id ?>][]" class="form-control desc_text_service"><?= isset($service) ? $service : "" ?></textarea>
    </td>
    <td class="product-addation-tr">
        <input type="text" class="form-control desc_text_term_use" name="term_use[<?= $product_id ?>][<?= $id ?>][]" value="<?= isset($term_use) ? $term_use : "" ?>" class="form-control" style="width: 50px">
    </td>
    <td class="product-addation-tr">
        <input type="text" name="link[<?= $product_id ?>][<?= $id ?>][]" value="<?= isset($link) ? $link : "" ?>" class="form-control">
    </td>
</tr>