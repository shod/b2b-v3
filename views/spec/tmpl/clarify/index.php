<input type="hidden" name="models_x[<?= $id ?>][1][]" value="1"/>

<select id="select_brands_<?= $id ?>" class="brands form-control" style="min-width: 150px">
    <option value="">Выберите производителя...</option>
    <?= $brands ?>
</select>
<br />
<?= $data ?>
