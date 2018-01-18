<tr>
    <td class="ks-text-light" width="1"><span class="badge badge-mantis">#<?= $order_id ?></span></td>
    <td class="ks-text-bold">+<?= $phone ?></td>
    <td class="ks-text-left"><?= $user_name ?></td>
    <td class="ks-text-light"><span class="badge badge-default-outline"><?= $time_at ?>   <?= $date_at ?></span></td>
    <td style="max-width: 300px;">
        <mark><?= isset($product_name) ? $product_name : "" ?></mark> / <span class="ks-text-light"><?= $section_name ?></span>
    </td>
    <td class="ks-text-bold"><mark><?= $cost_byn ?> руб.</mark>  <span class="ks-text-light font-11">(<?= $cost_us ?> руб.)</span></td>
    <td>
        <button class="btn btn-primary-outline ks-solid ks-no-text"><span class="la la-check ks-icon"></span></button>
        <button class="btn btn-primary-outline ks-solid ks-no-text"><span class="la la-trash-o ks-icon"></span></button>
    </td>
</tr>
