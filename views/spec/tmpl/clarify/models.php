<div id="models_<?= $catalog_id ?>_<?= $id ?>" style="display:none;" class="select_models">

    <table border=0>
        <tr>
            <td>
                <select multiple=1 style="width: 300px; height: 150px;">
                    <?= $data ?>
                </select>
                <br/>
                <span style="font-size:10px; margin-top: 5px; display:block;"><img src="/web/img/design/ctrl_lm_ico.gif" border=0/> — выбор нескольких позиций</span>
            </td>
            <td>
                <input type="button" value="&rArr;"/> <br/>
                <input type="button" value="&lArr;"/> <br/>
            </td>
            <td>
                <select name="models_x[<?= $catalog_id ?>][<?= $id ?>][]" multiple=1 size="10"
                        style="width: 300px; height: 150px;" class="models_x">
                    <?= $data_x ?>
                </select>
            </td>
        </tr>
    </table>
</div>