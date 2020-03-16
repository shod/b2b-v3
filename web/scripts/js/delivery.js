/**
 * Created by Миг-к101 on 27.04.2018.
 */
$( document ).ready(function() {
    $('#f_post').change(function () {
        var $this = $(this)
        $this.attr('disabled', true)
        $.get("/seller/delivery-actions", {
            action: "set_f_post",
            value: $this.attr("checked") ? 1 : 0
        }, function () {

        })
        $this.attr('disabled', false)
    })

    $("#frmAdd").submit(function () {
        var $f = $(this),
            $city = $f.find("input[name=city]"),
            $street = $f.find("input[name=street]"),
            $house = $f.find("input[name=house]"),
            $type = $f.find("input[name='type[]']:checked")

        if ($city.val() == '') {
            $.alert({
                title: "Введите город.",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });
            $city.focus();
            return false;
        }
        if ($street.val() == '') {
            $.alert({
                title: "Введите название улицы.",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });

            $street.focus();
            return false;
        }
        if ($house.val() == '') {
            $.alert({
                title: "Введите номер дома (корпуса).",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });


            $house.focus();
            return false;
        }
        if ($type.length == 0) {
            $.alert({
                title: "Выберите хотя бы один тип объекта.",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });

            $type.focus();
            return false;
        }
        return true;
    });

    $("#frmPlaceAdd").submit(function () {
        var $f = $(this),
            $geo_id = $f.find("input[name='geo_id[]']:checked"),
            $type_id = $f.find("input[name='type_id']:checked"),
            $cost = $f.find("input[name='cost_data[cost]']"),
            $pay_until = $f.find(".pay_until"),
            $cost_until = $f.find(".cost_until"),
            $delivery_desc = $f.find("#delivery_desc")

        if ($geo_id.length == 0) {
            $.alert({
                title: "Выберите регион доставки.",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });

            $geo_id.focus();
            return false;
        }
        if ($type_id.length == 0) {
            $.alert({
                title: "Выберите тип доставки.",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });

            $type_id.focus();
            return false;
        }

        if (($type_id[0].value == 3) && (($cost[0].value == "") || (!isNumber($cost[0].value)))) {
            $.alert({
                title: "Введите стоимость доставки.",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });

            $cost.focus();
            return false;
        }

        if (($type_id[0].value == 5) && ($delivery_desc[0].value == "")){
            $.alert({
                title: "Введите примечание.",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });
            $delivery_desc.focus();
            return false;
        }

        if (($type_id[0].value == 4)) {
            fl = true;
            $pay_until.each(function (index) {
                val = $(this).val();
                if ((val == "") || (!isNumber(val))) {
                    $.alert({
                        title: "Введите стоимость заказа до.",
                        type: 'red',
                        content: 'Для продолжения работы нажмите ОК',
                    });

                    $(this).focus();
                    fl = false;
                }
            });

            $cost_until.each(function (index) {
                val = $(this).val();
                if ((val == "") || (!isNumber(val))) {
                    $.alert({
                        title: "Введите стоимость доставки при заказе до определенной суммы.",
                        type: 'red',
                        content: 'Для продолжения работы нажмите ОК',
                    });

                    $(this).focus();
                    fl = false;
                }
            });
            return fl;

        }
        return true;
    })
});

function edit_delivery(id) {
    $.ajax({
        method: "GET",
        url: "/seller/delivery-actions/?action=delivery_get_edit_data&delivery_id=" + id
    })
        .done(function (msg) {
            var jsonStr = JSON.parse(msg);
            $("#modal-body").html(jsonStr.html);
            $("#type_id_" + jsonStr.id).click();
            geo = $("#form_geo_id").text();

            /*if (geo != 'Минск') {
             $('#form_region_check').show();
             } else {
             $('#form_region_check').hide();
             }*/
        });
    $('#myModal').modal();
}


function delivery_notice(id) {
    $(".delivery_options").hide();
    $("#delivery_text").hide();
    switch (id) {
        case 1:
            $("#delivery_text").html("<p>Магазин не осуществляет доставку.</p>");
            $("#delivery_text").show();
            $("#button_delivery").show();
            break;
        case 2:
            $("#addition").show();
            $("#delivery_text").html("<p>Магазин осуществляет бесплатную доставку.</p>");
            $("#delivery_text").show();
            $("#button_delivery").show();
            break;
        case 3:
            $("#paid").show();
            $("#button_delivery").show();
            break;
        case 4:
            $("#is_order_paid").show();
            $("#addition").show();
            $("#button_delivery").show();
            break;
        case 5:
            $("#addition").show();
            $("#button_delivery").show();
            break;

    }

    //$('.chosen').chosen();
}
function add_pay() {
    i = $(".tr_tbl").length;
    $("#cost_data_table").append("<tr class='tr_tbl'><td><i onclick='delete_pay(this)' style=\"color:red;\" class=\"la la-2x la-close\"></i></td><td><input name='cost_data[" + i + "][pay_until]' class='form-control pay_until' style='width:90%' type='text' /> руб. </td><td><input name='cost_data[" + i + "][cost_until]' style='width:90%' class='form-control cost_until' type='text' /> руб.</td></tr>");
}

function delete_pay(obj) {
    el = $(obj).parent().parent();
    el.remove();
}
