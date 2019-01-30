<tr id="tr_<?= $order_id ?>" class="<?= $status > 0 ? 'row-checked' : '' ?>">
    <td class="ks-text-light" width="1"><span class="badge badge-mantis">#<?= $order_id ?></span></td>
    <td class="ks-text-bold"><a href="tel:+<?= $phone_tel ?>"/>+<?= $phone ?></td>
    <td class="ks-text-left"><?= $user_name ?></td>
    <td class="ks-text-light"><span class="badge badge-default-outline"><?= $time_at ?>   <?= $date_at ?></span></td>
    <td style="max-width: 300px;">
        <mark>        
            <a style="text-decoration: underline" target="_blank" href="<?= isset($product_href) ? $product_href : "" ?>"><?= isset($product_name) ? $product_name : "" ?></a>
        </mark> / <span class="ks-text-light"><?= $section_name ?></span>
        <div><?= $description ?></div>
    </td>
    <td class="ks-text-bold"><mark><?= $cost_byn ?> руб.</mark> </td>
    <td>
        <?php if ($status == 0): ?>
            <button class="btn btn-primary-outline btn-sm ks-solid ks-no-text button-sms" id="processorder_<?= $order_id ?>" title="Обработан">
                <span style="font-size: 20px;" class="la la-cogs"></span>
            </button>
        <?php endif; ?>
        <button class="btn btn-primary-outline btn-sm ks-solid ks-no-text button-sms" id="complete_<?= $order_id ?>" title="Доставлен">
            <span style="font-size: 20px;" class="la la-check"></span>
        </button>
        <button class="btn btn-primary-outline btn-sm ks-solid ks-no-text button-sms" id="rejected_<?= $order_id ?>" title="Отклонен">
            <span style="font-size: 20px;" class="la la-trash-o"></span>
        </button>
    </td>
</tr>
