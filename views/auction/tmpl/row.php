<tr>
    <td><?= $name ?></td>
    <td><?= $views ?></td>
    <td><?= $forecast ?></td>
    <td class="data"><div id="data_<?= $catalog_id ?>"><?= $data ?></div></td>
    <td><input type="text" name="value[<?= $id ?>]" value="<?= $value ?>" style="width: 70px; text-align: right;" <?= $disabled ?> class="form-control cost"/></td>
    <td><input type="checkbox" name="f_auto[<?= $id ?>]" value="1" <?= $auto_checked ?> class="auto_check"/></td>
    <td style='width:60px' class="delete"><a style="color: red" href="<?= $href_delete ?>" title="Отказываюсь от участия"> <i class="fa fa-times-circle do_delete" style="cursor: pointer"></i> </a></td>
</tr>