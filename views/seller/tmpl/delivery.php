<tr>
    <td><i class="la la-map-marker font-16"></i> <?= $geo_id ?></td>
    <td><mark><?= $type_id ?></mark></td>
    <td><?= $description ?></td>
    <td><?= $cost ?></td>
    <td><a style="cursor:pointer" onclick="edit_delivery(<?= $id ?>)">редактировать</a> <a href="/seller/delivery-actions/?action=delivery_delete&id=<?= $id ?>">удалить</a></td>
</tr>
