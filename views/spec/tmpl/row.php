<tr>
    <td id="td_<?= $catalog_id ?>">
        <?= $name ?> (<a href="javascript:void(0)" onclick="clarify(<?= $catalog_id ?>)">уточнить</a>)
    </td>
    <td><input type="checkbox" name="f_show[<?= $id ?>]" value=1 <?= $checked_f_show ?>/></td>
    <td><?= $views ?></td>
    <td><?= $forecast ?></td>
    <td class="data"><div id="data_<?= $catalog_id ?>"><?= $data ?></div></td>
    <td><input class="form-control" type="text" name="value[<?= $id ?>]" value="<?= $value ?>" style="width: 70px; text-align: right;" <?= $disabled ?>/></td>
    <td class="delete"><a style="color: red" href="<?= $href_delete ?>" title="Отказываюсь от участия"> <i class="la la-times la-2x do_delete" style="cursor: pointer"></i> </a></td>
</tr>
<!--tr><td colspan=6 style="padding-bottom:10px"></td></tr-->