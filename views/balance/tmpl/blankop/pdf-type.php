<table style="width: 100%;" border="1|0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td colspan="10" style=' padding-left:20px;'>
            <br> 
            <h4 style="text-decoration:underline"><?= $official_name ?>	</h4>
            <br/>
            <?=  $official_rs ?> <?= $official_unp ?><br>
            Адрес: <?= $official_address ?><br>
            <?= $official_phone ?><br>
        </td> 
    </tr>
    <tr>
 
        <td colspan="10" style=' text-align:center;'>
            <br/>
            <h2>Счет № <?= $seller_id ?>/ <?= $docnum ?> от <?= $date_p ?></h2>
        </td>
    </tr>
    <tr>
        <td colspan="10" style="padding-left:20px;"><br> 
            <h4>Заказчик: <?= stripcslashes($company_name) ?><br/><br/>
            Плательщик:  <?= $zip_code_law ?>, г. <?= $city_law ?>, <?= $address_law ?>, УНП: <?= $unn ?>, тел.: <?= $fax ?><br>
            р/с: <?= $ras_schet ?>, в  <?= stripcslashes($bank_name) ?>, код <?= $bank_code ?><br>
            <?= $bank_address ?><br>
            </h4><br/><br/>
            Основание: Публичный договор<br> <br><br>
        </td>
    </tr>
    <tr>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'><b>№</b></td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;' colspan="2"><b>Товары (работы, услуги)</b></td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Единица<br>
                изме-<br>
                рения</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Коли-<br>
                чество</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'><b>Цена, бел. руб.</b>	</td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'><b>Сумма, бел. руб.</b></td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Ставка<br>
                НДС, %</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'>
            <b>Сумма<br>
                НДС, бел. руб.</b>
        </td>
        <td style='border: 1px solid; text-align:center;padding-left:1px;'><b>Всего, бел. руб.</b></td>
    </tr>
    <tr>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>1</td>
        <td colspan="2" style='border: 1px solid; text-align:center; padding-left:1px;'><?= $text ?></td>
        <td style='border: 1px solid; text-align:center; padding-left:1px;'>Шт</td>
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
            <h4>Всего к оплате на сумму с НДС:	<?= $sum_all_str ?></h4>
            <h4>Счет действителен в течение 5 рабочих дней.</h4> <br><br>

            <p>1. Счет выставлен на основании Публичного договора возмездного оказания рекламных услуг.</p>
            <p>2. Счет действителен в течение 5 рабочих дней.	</p>
            <p>3. Счет считать действительным с факсимильной копией печати и подписи.</p>
            <br/>
            <br/>
            <p>ИСПОЛНИТЕЛЬ:							</p>
            <p>Заместитель начальника отдела по работе с клиентами</p>
            <p>На основании доверенности №5 от 30.12.2022 года</p>

        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding-left:20px;"><br>
            <img width=200px src="<?= $official_faximille ?>">  
        </td>
        <td colspan="7" style="padding-left:20px;"><br>
             <u style="position:relative; top:-53px" >Новикова Ж.А.</u>
        </td>
    </tr>
    </tbody>
</table>