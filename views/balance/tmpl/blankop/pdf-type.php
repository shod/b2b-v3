<table style="width: 100%;" border="1|0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td colspan="6" style='width:50%; border: 1px solid; padding-left:20px;'>
            <br>
            <h3>Поставщик:</h3>
            <h4><?= $official_name ?>	</h4>
            <?= $official_unp ?>	 <br>
            <?= $official_address ?><br>
            <?=  $official_rs ?> <br>
            <?= $official_phone ?><br>
        </td>
        <td colspan="4" style='width:50%; border: 1px solid; text-align:center;'>
            <h2>СЧЕТ</h2>
            <h3>№ <?= $seller_id ?>/<?= $docnum ?></h3>
            <h3><?= $date_p ?></h3>
        </td>
    </tr>
    <tr>
        <td colspan="10" style="padding-left:20px;"><br>
            <h4>Плательщик:</h4>
            <h4><?= stripcslashes($company_name) ?></h4>
            Юр. адрес: <?= $zip_code_law ?>, г. <?= $city_law ?>, <?= $address_law ?><br>
            УНП: <?= $unn ?><br>
            р/с: <?= $ras_schet ?>, в  <?= stripcslashes($bank_name) ?>, код <?= $bank_code ?><br>
            <?= $bank_address ?><br>
            тел.: <?= $fax ?><br>
            Основание: Договор публичной оферты (ст. 405 ГК, п. 2 ст. 407 ГК)<br> <br><br>
        </td>
    </tr>
    <tr>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><b>№</b></td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;' colspan="2"><b>Наименование товара</b></td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Единица<br>
                изме-<br>
                рения</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Коли-<br>
                чество</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'><b>Цена, руб.</b>	</td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'><b>Сумма, руб.</b></td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Ставка<br>
                НДС, %</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Сумма<br>
                НДС, руб.</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'><b>Всего, руб.</b></td>
    </tr>
    <tr>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>1</td>
        <td colspan="2" style='border: 1px solid; text-align:center; padding-left:1px;'><?= $text ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>Штука</td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>1</td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $sum ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $sum ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $official_percent ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $sum_nds ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $sum_all ?></td>
    </tr>
    <tr>
        <td colspan="3" style='text-align:right; padding-right:5px;'>	<br>ИТОГО:<br><br></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>x</td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>x</td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>x</td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $sum ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>x</td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $sum_nds ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><?= $sum_all ?></td>
    </tr>
    <tr>
        <td colspan="10" style="padding-left:20px;"><br>
            <h4><?= $official_nds ?>	<?= $nds_str ?></h4>
            <h4>Всего к оплате:	<?= $sum_all_str ?></h4>
            <h4>Счет действителен в течение 5 рабочих дней.</h4> <br><br>
        </td>
    </tr>
    <tr>
        <td colspan="10" style="padding-left:20px;"><br>
            Поставщик: <img width=200px style="position:relative; top:53px" src="<?= $official_faximille ?>"> <u><?= $official_owner ?></u>
        </td>
    </tr>
    </tbody>
</table>