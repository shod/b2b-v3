<tr>
    <td class="ks-text-light" width="1"><span class="badge badge-default">#<?= $order_id ?></span></td>
    <td class="ks-text-bold">+<?= $phone ?></td>
    <td class="ks-text-light"><?= $user_name ?></td>
    <td class="ks-text-light"><span class="badge badge-default-outline"><?= $time_at ?>   <?= $date_at ?></span></td>
    <td style="max-width: 300px;">
        <wrap><?= isset($product_name) ? $product_name : "" ?></wrap> / <span class="font-11"><?= $section_name ?></span>
    </td>
    <td class="ks-text-bold"><?= $cost_byn ?> руб.  <span class="ks-text-light font-11">(<?= $cost_us ?> руб.)</span></td>
</tr>
