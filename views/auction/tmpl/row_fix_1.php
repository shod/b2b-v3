<tr>
    <td><?= $name ?>
        <p style="font-size:10px">Переносить ставку на следующий день <input type="checkbox" name="f_auto[<?= $id ?>]" value="1" <?= $auto_checked ?> class="auto_check"></p>
    </td>
    <td class="data"><div id="data_<?= $catalog_id ?>"><?= $data ?></div></td>
    <td colspan="2">
        <div class="row">
            <div class="col-lg-2"><input type="text" name="value[<?= $id ?>]" value="<?= $value ?>" style="width: 70px; text-align: right;" <?= $disabled ?> class="form-control form-inline cost" /></div>
            <div class="col-lg-10"><a style="margin-top: 5px" class='btn btn-primary ks-light' data-remote="/auction/process/?action=get_arch&baid=<?= $catalog_id ?>" data-toggle="ajaxModal" data-target=".bd-example-modal-lg">Аукцион вчера</a></div>
        </div>
    </td>
    <td style='width:100px' class="delete" align="center"><a style="color: red" href="<?= $href_delete ?>" title="Отказываюсь от участия"> <i class="la la-close la-2x do_delete" style="cursor: pointer"></i> </a></td>
</tr>