<tr>
    <td class="manufacturer">
        <textarea style="width: 100%" name="manufacturer[<?= $product_id ?>][<?= $id ?>][]" class="form-control desc_text_manufacturer"><?= isset($manufacturer) ? $manufacturer : "" ?></textarea>
    </td>
    <td class="importer">
        <textarea style="width: 100%" name="importer[<?= $product_id ?>][<?= $id ?>][]" class="form-control desc_text_import"><?= isset($importer) ? $importer : "" ?></textarea>
    </td>
    <td class="service">
        <textarea style="width: 100%" name="service[<?= $product_id ?>][<?= $id ?>][]" class="form-control desc_text_service"><?= isset($service) ? $service : "" ?></textarea>
    </td>
    <td>
        <input type="text" class="form-control desc_text_term_use" name="term_use[<?= $product_id ?>][<?= $id ?>][]" value="<?= isset($term_use) ? $term_use : "" ?>" class="form-control" style="width: 50px">
    </td>
    <td>
        <input type="text" name="link[<?= $product_id ?>][<?= $id ?>][]" value="<?= isset($link) ? $link : "" ?>" class="form-control">
    </td>
</tr>