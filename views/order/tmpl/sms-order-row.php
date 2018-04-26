<tr id="tr_<?= $order_id ?>">
    <td class="ks-text-light" width="1"><span class="badge badge-mantis">#<?= $order_id ?></span></td>
    <td class="ks-text-bold">+<?= $phone ?></td>
    <td class="ks-text-left"><?= $user_name ?></td>
    <td class="ks-text-light"><span class="badge badge-default-outline"><?= $time_at ?>   <?= $date_at ?></span></td>
    <td style="max-width: 300px;">
        <mark><?= isset($product_name) ? $product_name : "" ?></mark> / <span class="ks-text-light"><?= $section_name ?></span>
    </td>
    <td class="ks-text-bold"><mark><?= $cost_byn ?> руб.</mark> </td>
    <td>
        <button class="btn btn-primary-outline btn-sm ks-solid ks-no-text button-sms" id="complete_<?= $order_id ?>">
            <span style="font-size: 20px;" class="la la-check"></span>
        </button>
        <button class="btn btn-primary-outline btn-sm ks-solid ks-no-text button-sms" id="rejected_<?= $order_id ?>">
            <span style="font-size: 20px;" class="la la-trash-o"></span>
        </button>
    </td>
</tr>
