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
            <h2>Акт № <?= $seller_id ?>/ 000<?= $docnum ?>/ <?= $month ?></h2>
        </td>
    </tr>
    <tr>
        <td colspan="10" style="padding-left:20px;"><br> 
            от <?= $dat ?> 20<?= $year ?><br/><br/>
            <h4>Заказчик: <?= stripcslashes($company_name) ?><br/>
            Р/сч: <?= $ras_schet ?> в <?= stripcslashes($bank_name) ?> <?= $bank_address ?> код <?= $bank_code ?><br>
            УНП: <?= $unn ?><br>
            Адрес: <?= $zip_code_law ?>, г. <?= $city_law ?>, <?= $address_law ?><br>
            Тел.: <?= $fax ?> <br>
            </h4><br/><br/>
            Публичный договор<br>
            <br>
            Настоящий Акт составлен о том, что согласно Публичному договору возмездного оказания рекламных услуг, размещенному по адресу: https://www.maxi.by/page/public-contract/, Исполнителем оказаны Заказчику следующие услуги: <br><br>
        </td>
    </tr>
    <tr>
 
 <td colspan="10" style=' text-align:center;'>
     <br/>
     <h2> </h2>
 </td>
</tr>
    <tr style="height: 42px">
              
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s12">№</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s13">Наименование работы (услуги)</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s14">Единица<br>изме-<br>рения</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s15">Коли-<br>чество</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s15">Цена, бел. руб.</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s15">Сумма, бел. руб.</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s15">Ставка<br>НДС, %</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s15">Сумма<br>НДС, бел. руб.</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s15">Всего<br>с НДС , бел. руб.</td>
         </tr>
         <tr style="height: 60px">
              
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s17">1</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s18">Размещение рекламных материалов</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s17">шт</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s17">1</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s19"></td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s19">0</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s17">20%</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s19">0</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s19">0</td>
         </tr>
         <tr style="height: 16px">
              
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td  style='text-align:right; padding-left:1px;' class="s20">Итого:</td>
            <td  style='border: 1px solid; text-align:center; padding-left:1px;' class="s21"><?= $sum; ?></td>
         </tr>
         <tr style="height: 16px">
              
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td  style='text-align:right; padding-left:1px;' class="s22 softmerge">
               <div class="softmerge-inner" style="width:84px;left:-28px"> Сумма НДС:</div>
            </td>
            <td style='border: 1px solid; text-align:center; padding-left:1px;' class="s23"><?= $sum_nds; ?></td>
         </tr>
         <tr style="height: 16px">
             
            <td></td>
            <td></td>
            <td class="s24"> </td>
            <td></td>
            <td></td>
            <td></td> 
            <td  style='text-align:right; padding-left:1px;' class="s25" colspan="2">Всего с НДС:</td>
            <td style='border: 1px solid; text-align:center; padding-left:1px;' class="s21"><?= $sum_nds; ?></td>
         </tr>
         <tr style="height: 16px">
             
            <td></td>
            <td class="s2"></td>
            <td class="s24"></td>
            <td class="s2"></td>
            <td class="s2"></td>
            <td class="s2"></td>
            <td class="s2"></td>
            <td class="s26"></td>
            <td class="s26"></td>
            <td class="s27"></td>
         </tr>
         <tr style="height: 48px">
             
            <td></td>
            <td class="s28" colspan="9">Всего на сумму: <?= $sum_all_str; ?><br/>
            <br/>
            Вышеперечисленные услуги  оказаны полностью и в срок. Заказчик претензий по объему, качеству и срокам оказания услуг не имеет.</span><br>
            <br/>
            Настоящий Акт составлен Исполнителем единолично в одностороннем порядке в соответствии с п.6 ст.10 Закона Республики Беларусь от 12.07.2013 г. №57-З «О бухгалтерском учете и отчетности», постановлением Министерства финансов Республики Беларусь от 12.02.2018 г. №13 «О единоличном составлении первичных учетных документов и признании утратившим силу постановления Министерства финансов Республики Беларусь от 21.12.2015 г. №58».<br/>
            <br/>Акт возврату Исполнителю не подлежит.<br/>
            <br/><br/>
            <br/>
            ИСПОЛНИТЕЛЬ:</td> 
          </tr>
          <tr style="height: 19px">
              
             <td></td>
             <td class="s32" colspan="2">ООО «Макси Бай Медиа»</td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
          </tr>
          <tr style="height: 16px">
              
             <td></td>
             <td class="s33 softmerge">
                <div class="softmerge-inner" style="width:431px;left:-1px">Заместитель начальника отдела по работе с клиентами </div>
             </td>
             <td class="s34"></td>
             <td class="s35"></td>
             <td class="s35"></td>
             <td class="s36"></td>
             <td class="s36"></td>
             <td></td>
             <td></td>
             <td></td>
          </tr>
          <tr style="height: 14px">
              
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
          </tr>
          <tr style="height: 16px">
              
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
          </tr>
    <tr>
        <td colspan="3" style="padding-left:20px;"><br>
            <img width=200px src="<?= $official_faximille ?>">  
        </td>
        <td colspan="7" style="padding-left:20px;"><br>
             <u style="position:relative; top:-53px" >Новикова Ж.А.</u>  (На основавни доверенности №5 от 30.12.2022 года)
        </td>
    </tr>
    </tbody>
</table>