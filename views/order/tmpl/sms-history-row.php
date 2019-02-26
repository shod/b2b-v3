<tr>
    <td class="ks-text-light" style="width: 10px;"><span class="badge badge-default">#<?= $order_id ?></span></td>
    <td class="ks-text-bold">+<?= $phone ?></td>
    <td class="ks-text-light"><?= $user_name ?></td>
    <td class="ks-text-light"><span class="badge badge-default-outline"><?= $time_at ?>   <?= $date_at ?></span></td>
    <td style="max-width: 300px;">
        <wrap><?= isset($product_name) ? $product_name : "" ?></wrap> / <span class="font-11"><?= $section_name ?></span>
        <div><?= $description ?></div>
        <div><span class="font-11" style="color:rgba(47,0,185,0.91)"><?= $admin_description ?></span></div>
    </td>
    <td class="ks-text-bold"><?= $cost_byn ?> руб.</td>
    <td class="ks-text-light" style="width: 10px;"><?= $class_order ?></td>
    <td class="ks-text-light">
        <?php if ($is_btn_challenge == TRUE): ?>
            <button class="btn btn-primary-outline btn-sm ks-solid ks-no-text button-sms" id="challenge_<?= $order_id ?>" title="Оспорить">
                <span style="font-size: 20px;" class="la la-houzz"></span>
            </button>
        <?php endif; ?>        
    </td>    
</tr>
