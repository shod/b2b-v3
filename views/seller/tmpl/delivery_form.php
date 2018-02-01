<form method="POST" id="frmPlaceEdit" action="/seller/delivery-actions">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <input NAME="action" TYPE=Hidden VALUE="delivery_edit">
    <input NAME="delivery_id" TYPE=Hidden VALUE="<?= $delivery_id ?>">
    <div class="col-lg-12" style="overflow: auto">
        <table style="width: 100%; background-color: #ecf0f1;" class='table'>
            <tr>
                <th>Регион</th>
                <th>Тип</th>
            </tr>
            <tr>
                <td  id='form_geo_id' style="vertical-align: top; width: 40%"><?= $geo_id ?></td>
                <td style="vertical-align: top;">
                    <div class="btn-group" data-toggle="buttons" style="<?= isset($display_delivery_type) ? $display_delivery_type : "" ?>">
                        <label class="btn btn-primary  btn-sm" onclick="delivery_notice_edit(2);">
                            <input name="type_id" value="2" id="type_id_2" type="radio" autocomplete="off"> бесплатная
                        </label>
                        <label class="btn btn-primary  btn-sm" onclick="delivery_notice_edit(3);">
                            <input type="radio" name="type_id" id="type_id_3" value="3"  autocomplete="off"> платная
                        </label>
                        <label class="btn btn-primary  btn-sm" onclick="delivery_notice_edit(4);">
                            <input name="type_id" value="4" id="type_id_4" type="radio" autocomplete="off"> зависит от заказа
                        </label>
                        <label class="btn btn-primary  btn-sm" id="form_region_check" onclick="delivery_notice_edit(5);" style="display:none">
                            <input type="radio" name="type_id" value="5" id="type_id_5"  autocomplete="off"> уточнить
                        </label>
                        <label class="btn btn-primary  btn-sm" onclick="delivery_notice_edit(1);">
                            <input type="radio" name="type_id" value="1" id="type_id_1"  autocomplete="off"> отсутствует
                        </label>

                    </div><br><br>


                    <div id='geo_d' >
                        <div id="delivery_text_edit" style="display:none"></div>
                        <div class='delivery_options form-inline' style="display:none" id='paid_edit'>Стоимость: <br><input name="cost_data[cost]" class='form-control' style='width:30%' type='text'  value="<?= $cost ?>"/> руб.<br></div>
                        <div class='delivery_options form-inline' style="display:none" id='is_order_paid_edit'>
                            <p>Если доставка зависит от стоимости товара, то необходимо указать стоимость, меньше которой доставка осуществляется платно.</p>
                            <p>Например: Если стоимость товара меньше 100р, то стоимость доставки 5р.</p>
                            <table style="width:100%" id="cost_data_table_edit">
                                <tr>
                                    <td>Стоимость заказа до:<br></td>
                                    <td>Стоимость доставки:</td>
                                </tr>
                                <?= $cost_items ?>
                            </table>
                            <a onclick="add_pay_edit()" style="cursor:pointer">Еще стоимость</a>
                            <p id='free_for'></p></div>
                        <div class='delivery_options' style="display:none" id='addition_edit'>Примечание: <br />
                            <textarea class="form-control" name="description"><?= $description ?></textarea></div>
                    </div>

                </td>
            </tr>
            <tr><td></td><td>	<div class='delivery_options' style="display:none" id='button_delivery_edit'><input class="btn btn-primary" type="submit" value="Сохранить изменения"/></div> </td></tr>
        </table>
    </div>
</form>

<script>

    function add_pay_edit(){
        i = $( ".tr_tbl" ).length;
        $( "#cost_data_table_edit" ).append( "<tr class='tr_tbl'><td><input name='cost_data["+i+"][pay_until]' class='form-control pay_until' style='width:90%' type='text' /> руб. </td><td><input name='cost_data["+i+"][cost_until]' style='width:90%' class='form-control cost_until' type='text' /> руб.</td></tr>" );
    }

    function delivery_notice_edit(id){
        $(".delivery_options").hide();
        $("#delivery_text_edit").hide();
        switch (id) {
            case 1:
                $("#delivery_text_edit").html("<p>Магазин не осуществляет доставку.</p>");
                $("#delivery_text_edit").show();
                $("#button_delivery_edit").show();
                break;
            case 2:
                $("#addition_edit").show();
                $("#delivery_text_edit").html("<p>Магазин осуществляет бесплатную доставку.</p>");
                $("#delivery_text_edit").show();
                $("#button_delivery_edit").show();
                break;
            case 3:
                $("#paid_edit").show();
                $("#addition_edit").show();
                $("#button_delivery_edit").show();
                break;
            case 4:
                $("#is_order_paid_edit").show();
                $("#addition_edit").show();
                $("#button_delivery_edit").show();
                break;
            case 5:
                $("#addition_edit").show();
                $("#button_delivery_edit").show();
                break;

        }

        //$('.chosen').chosen();
    }

    $("#frmPlaceEdit").submit(function(){
        var $f = $(this),
            $geo_id = $f.find("input[name='geo_id[]']:checked"),
            $type_id = $f.find("input[name='type_id']:checked"),
            $cost = $f.find("input[name='cost_data[cost]']"),
            $pay_until = $f.find(".pay_until"),
            $cost_until = $f.find(".cost_until")

        if (($type_id[0].value == 3) && (($cost[0].value == "") || (!isNumber($cost[0].value)))){
            alert("Введите стоимость доставки.");
            $cost.focus();
            return false;
        }

        if (($type_id[0].value == 4)){
            fl = true;
            $pay_until.each(function( index ) {
                val = $( this ).val();
                if ((val == "") || (!isNumber(val))){
                    alert("Введите стоимость заказа до.");
                    $( this ).focus();
                    fl = false;
                }
                console.log( index + ": " + $( this ).val() );
            });

            $cost_until.each(function( index ) {
                val = $( this ).val();
                if ((val == "") || (!isNumber(val))){
                    alert("Введите стоимость доставки при заказе до определенной суммы.");
                    $( this ).focus();
                    fl = false;
                }
                console.log( index + ": " + $( this ).val() );
            });
            if(!fl){
                return fl;
            }

        }
        return true;
    })


</script>