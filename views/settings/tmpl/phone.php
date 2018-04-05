<tr <?= isset($style) ? $style : "" ?> id="tmpl_id">
    <td valign="middle" style="vertical-align:middle; padding:0;">
        <input type="hidden" name="phone_id[]" value="<?= $id ?>" />

        8 - (<select name="phone_code[<?= $id ?>]" class="<?= isset($hidden) ? $hidden : "" ?>">
            <option value="0">код</option>
            <option value="017" <?= isset($selected_017) ? $selected_017 : "" ?>>017</option>
            <option value="029" <?= isset($selected_029) ? $selected_029 : "" ?>>029</option>
            <option value="033" <?= isset($selected_033) ? $selected_033 : "" ?>>033</option>
            <option value="025" <?= isset($selected_025) ? $selected_025 : "" ?>>025</option>
            <option value="044" <?= isset($selected_044) ? $selected_044 : "" ?>>044</option>
        </select>) -
        <input type="text" name="phone[<?= $id ?>]" value="<?= isset($phone) ? $phone : "" ?>" style="width: 80px;" maxlength=7 <?= isset($disable) ? $disable : "" ?> />

        <?= isset($text_op) ? $text_op : "" ?>
        <select name="phone_op[<?= $id ?>]" class=""<?= isset($hidden) ? $hidden : "" ?>">
            <option value="0">оператор</option>
            <option value="velcom" <?= isset($selected_velcom) ? $selected_velcom : "" ?> >Velcom</option>
            <option value="mts" <?= isset($selected_mts) ? $selected_mts : "" ?> >МТС</option>
            <option value="life" <?= isset($selected_life) ? $selected_life : "" ?> >Life:)</option>
            <option value="btk" <?= isset($selected_btk) ? $selected_btk : "" ?> >городской</option>
        </select>
        <select name="phone_type[<?= $id ?>]" class=""<?= isset($hidden) ? $hidden : "" ?>">
            <option value="0">Общий</option>
            <option value="credit" <?= isset($selected_credit) ? $selected_credit : "" ?> >Кредит</option>
            <option value="beznal" <?= isset($selected_beznal) ? $selected_beznal : "" ?> >Безнал</option>
        </select>
        &nbsp;&nbsp; Доступен в <label>viber <input type='checkbox' name="viber[<?= isset($checked_viber) ? $checked_viber : "" ?>]" /></label>
        &nbsp;&nbsp; <label>telegram <input type='checkbox' name="telegram[<?= isset($checked_telegram) ? $checked_telegram : "" ?>]" /></label>
        &nbsp;&nbsp; <label>whats app <input type='checkbox' name="whatsapp[<?= isset($checked_whatsapp) ? $checked_whatsapp : "" ?>]" /></label>
        <br />
        <div style="padding:0 0 10px 5px">
            <select data-placeholder="для всех разделов" class="chosen-select" name="phone_section[<?= $id ?>][]" tabindex="4" style="width:750px" multiple>
                <option value="0">для всех разделов</option>
                <?= $section_options ?>
            </select>
        </div>
    </td></tr>