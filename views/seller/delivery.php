<?php
$this->title = "Условия доставки";
$this->registerJs(
    "
    $(document).ready(function () {
		
    });
    "
);
?>

<div class="row" data-dashboard-widget>
    <div class="col-lg-12">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <div class="card-header">
                Доставка
            </div>
            <div class="card-block">
                <form method="POST">
                    <input NAME="block" TYPE=Hidden VALUE="content_settings">
                    <input NAME="action" TYPE=Hidden VALUE="delivery">
                    <input NAME="check_seller_id" TYPE=Hidden VALUE="$vars[seller_id]">

                    <table class="table" style="min-width: 300px;">
                        <tr class="active">
                            <th>Адрес</th>
                            <th>Тип</th>
                            <th>Примечание</th>
                            <th>Стоимость доставки</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?= isset($data_delivery) ? $data_delivery : ""?>
                        <tr>
                            <td colspan=4><a onclick="$('#divAddDelivery').toggle('slow')"
                                             style="text-decoration: none; cursor:pointer;">Добавить <i class="la la-arrow-down"></i></a></td>
                            <!--td align="right">	<input type="button" value="Сохранить" onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/> </td-->
                        </tr>
                    </table>

                </form>

                <div id="divAddDelivery" style="display: none; margin-top: 20px;">
                    <form method="POST" id="frmPlaceAdd">
                        <input NAME="block" TYPE=Hidden VALUE="content_settings">
                        <input NAME="action" TYPE=Hidden VALUE="delivery_add">

                        <table style="width: 100%; background-color: #ecf0f1;" class='table'>
                            <tr>
                                <th>Регион</th>
                                <th>Тип</th>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; width: 40%">
                                    <div class="btn-group" data-toggle="buttons">
                                        <?= isset($delivery_minsk) ? $delivery_minsk : ""?>
                                        <?= isset($delivery_regions) ? $delivery_regions : ""?>
                                    </div>
                                    <br><br>
                                    <div id='city_select' style='display:none;'>
                                        <?= isset($delivery_select) ? $delivery_select : ""?>
                                    </div>
                                </td>
                                <td style="vertical-align: top;">
                                    <div class="btn-group" data-toggle="buttons" style="$vars[display_delivery_type]">
                                        <label class="btn btn-primary" onclick="delivery_notice(2);">
                                            <input name="type_id" value="2" type="radio" autocomplete="off"> бесплатная
                                        </label>
                                        <label class="btn btn-primary" onclick="delivery_notice(3);">
                                            <input type="radio" name="type_id" value="3" autocomplete="off"> платная
                                        </label>
                                        <label class="btn btn-primary" onclick="delivery_notice(4);">
                                            <input name="type_id" value="4" type="radio" autocomplete="off"> зависит от
                                            заказа
                                        </label>
                                        <label class="btn btn-primary" id="region_check" onclick="delivery_notice(5);"
                                               style="display:none">
                                            <input type="radio" name="type_id" value="5" autocomplete="off"> уточнить
                                        </label>
                                        <label class="btn btn-primary" onclick="delivery_notice(1);">
                                            <input type="radio" name="type_id" value="1" autocomplete="off"> отсутствует
                                        </label>
                                    </div>
                                    <br><br>


                                    <div id='geo_d'>
                                        <div id="delivery_text" style="display:none"></div>
                                        <div class='delivery_options form-inline' style="display:none" id='paid'>
                                            Стоимость: <br><input
                                                    name="cost_data[cost]" class='form-control' style='width:30%'
                                                    type='text'/> руб.<br>
                                        </div>
                                        <div class='delivery_options form-inline' style="display:none"
                                             id='is_order_paid'>
                                            <p>Если доставка зависит от стоимости товара, то необходимо указать
                                                стоимость,
                                                меньше которой доставка осуществляется платно.</p>
                                            <p>Например: Если стоимость товара меньше 100р, то стоимость доставки
                                                5р.</p>
                                            <table style="width:100%" id="cost_data_table">
                                                <tr>
                                                    <td>Стоимость заказа до:<br></td>
                                                    <td>Стоимость доставки:</td>
                                                </tr>
                                                <tr class='tr_tbl'>
                                                    <td><input name="cost_data[0][pay_until]"
                                                               class='form-control pay_until'
                                                               style='width:90%' type='text'/> руб.
                                                    </td>
                                                    <td><input name="cost_data[0][cost_until]" style='width:90%'
                                                               class='form-control cost_until' type='text'/> руб.
                                                    </td>
                                                </tr>
                                            </table>
                                            <a onclick="add_pay()" style="cursor:pointer">Еще стоимость</a>
                                            <p id='free_for'></p></div>
                                        <div class='delivery_options' style="display:none" id='addition'>Примечание:
                                            <br/>
                                            <textarea class="form-control" name="description"></textarea></div>
                                    </div>

                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right">
                                    <div class='delivery_options' style="display:none" id='button_delivery'><input
                                                class="btn btn-success" type="submit" value="Добавить"/></div>
                                </td>
                            </tr>
                        </table>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-12" style="margin-top: 10px">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <div class="card-header">
                Доставка почтой
            </div>
            <div class="card-block">
                <p>
                    <label><input type="checkbox" value=1 name="f_post" <?= isset($checked_f_post) ? $checked_f_post : ""?> id="f_post"/> Доставка
                        почтой
                        возможна</label>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-12" style="margin-top: 10px">
        <div class="card ks-card-widget ks-widget-payment-table-invoicing">
            <div class="card-header">
                Адреса магазинов и пунктов выдачи товаров
            </div>
            <div class="card-block">
                <form method="POST">
                    <input NAME="block" TYPE=Hidden VALUE="content_settings">
                    <input NAME="action" TYPE=Hidden VALUE="places">

                    <table class="table" style="min-width: 300px;">
                        <tr class="active">
                            <th>Адрес</th>
                            <th>Тип</th>
                            <th></th>
                        </tr>
                        <?= isset($data) ? $data : "" ?>
                        <tr>
                            <td colspan=3><a href="#" onclick="$('#divAdd').toggle('slow')"
                                             style="text-decoration: none;">Добавить <i class="la la-arrow-down"></i></a></td>
                            <!--td align="right">	<input type="button" value="Сохранить" onclick="this.disabled=true;this.value='Подождите...';this.form.submit();"/> </td-->
                        </tr>
                    </table>

                </form>


                <div id="divAdd" style="display: none; margin-top: 20px; background-color: #ecf0f1">
                    <form method="POST" id="frmAdd">
                        <input NAME="block" TYPE=Hidden VALUE="content_settings">
                        <input NAME="action" TYPE=Hidden VALUE="places_add">

                        <table class="table" style="width: 100%">
                            <tr class="active">
                                <th style="width: 60%">Адрес</th>
                                <th>Тип</th>
                            </tr>
                            <tr>
                                <td style="vertical-align: bottom;">
                                    <table>
                                        <tr>
                                            <td style="padding-left: 0px; padding-bottom: 0; vertical-align: bottom;"><span>город<br/><input
                                                            class="form-control" type="text" name="city" value="Минск"
                                                            style="width:80px;"></span></td>
                                            <td style="padding-bottom: 0; vertical-align: bottom;"><span>улица<br/><input
                                                            class="form-control" type="text" name="street"
                                                            style="width:150px;"></span>
                                            </td>
                                            <td style="padding-bottom: 0; vertical-align: bottom;"><span>дом (корпус)<br/><input
                                                            class="form-control" type="text" name="house"
                                                            style="width:70px;"></span>
                                            </td>
                                            <td style="padding-bottom: 0; vertical-align: bottom;"><span>офис (если есть)<br/><input
                                                            class="form-control" type="text" name="flat"
                                                            style="width:100px;"></span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td style="vertical-align: bottom;">
                                    <label><input type="radio" name="type[]" value="1" checked/> салон-магазин</label>
                                    <label><input type="radio" name="type[]" value="2"/> пункт выдачи</label>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right"><input class="btn btn-success" type="submit"
                                                                     value="Добавить"
                                                                     onclick_="this.disabled=true;this.value='Подождите...';this.form.submit();"/>
                                </td>
                            </tr>
                        </table>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>




