$( document ).ready(function() {

    $.validate();

    page_url = window.location.pathname;
    $( "ul.nav-stacked a.dropdown-item" ).each(function() {
        href = $( this ).attr('href');
        if(href == page_url){
            $( this ).addClass('ks-active');
            $( this ).parents( "li.dropdown" ).addClass('open');
        }
    });

    var $ajaxModalLinks = $('[data-toggle="ajaxModal"]');

    $ajaxModalLinks.on('click', function (e) {
        $("[data-dashboard-widget]").LoadingOverlay("show", {
            color: 'rgba(255, 255, 255, 0.8)',
            image: '',
            fontawesome : "la la-refresh la-spin",
            zIndex: 11

        });
        var $this = $(this),
            $remoteUrl = $this.data('remote') || $this.attr('href'),
            $modal = $('<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="modal-title"></h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="la la-close"></span></button></div><div class="modal-body" id="modal-body"></div></div></div></div>');
        $('#modal-div').append($modal);
        $.ajax({
            url: $remoteUrl,
            type: 'get',
            dataType: 'html',
            success: function (html) {
                json = JSON.parse(html);
                $modal.find('.modal-body').html(json.body);
                $modal.find('.modal-title').html(json.header);
                $("[data-dashboard-widget]").LoadingOverlay("hide");
                $modal.modal('show');
            },
            error: function () {
                $("[data-dashboard-widget]").LoadingOverlay("hide");
                console.log('modal ajax error');
            }
        });
    });

    $('.button-sms').click(function() {
        var $this = $(this);
        var $p = $(this).attr('id').split('_');
        $.confirm({
            title: 'Внимание!',
            content: 'После обработки заказ <span class="badge badge-mantis">#' + $p[1] + " </span> Будет перемещен в историю заказов.",
            type: 'primary',
            buttons: {
                confirm: {
                    text: 'ОК', // With spaces and symbols
                    btnClass: 'btn-primary',
                    action: function () {
                        $this.attr('disabled','disabled');
                        $.ajax({
                            url: "/order/process/?order_id="+$p[1]+"&action="+$p[0],
                            type: 'get',
                            dataType: 'html',
                            success: function (html) {
                                document.getElementById("tr_"+$p[1]).style.display = 'none';
                                $(html).hide().prependTo("#history-body").fadeIn();
                            },
                            error: function () {
                                console.log('ajax error');
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Отменить'
                }
            }
        });
    });

    $('#active-sms').click(function() {
        var $this = $(this);
        action = $(this).prop('checked') ? 'active' : 'deactive';
        if(($("#email-value").val() == '') && (action == 'active')){
            $.alert({
                title: "Поле Email обязательно для заполнения!",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });
            $(this).prop('checked',false);
        } else {
            $.ajax({
                url: "/order/process/?action=" + action ,
                type: 'get',
                dataType: 'html',
                success: function (html) {
                    $.alert({
                        title: html,
                        type: 'blue',
                        content: 'Для продолжения работы нажмите ОК',
                    });
                },
                error: function () {
                    console.log('ajax error');
                }
            });
        }

    });

    $('.notify-button').click(function() {
        var $this = $(this);
        action = $(this).attr('id')=='email' ? 'edit-email' : 'edit-phone';
        data = $("#"+$(this).attr('id')+"-value").val();
        if(($(this).attr('id')=='email') && (data == '')){
            $.alert({
                title: "Поле Email обязательно для заполнения!",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });
        } else {
            console.log(data);
            $.ajax({
                url: "/order/process/?action=" + action + "&val=" + data,
                type: 'get',
                dataType: 'html',
                success: function (html) {
                    $.alert({
                        title: html,
                        type: 'blue',
                        content: 'Для продолжения работы нажмите ОК',
                    });
                },
                error: function () {
                    console.log('ajax error');
                }
            });
        }
    });

    $('[data-dashboard-widget]').KosmoWidgetControls({
        onRefresh: function (element) {
            var zIndex = 1;

            if (element.hasClass($.fn.KosmoWidgetControls.defaults.fullScreenClass)) {
                zIndex = 11;
            }

            element.LoadingOverlay("show", {
                color: 'rgba(255, 255, 255, 0.8)',
                image: '',
                fontawesome : "la la-refresh la-spin",
                zIndex: zIndex
            });

            setTimeout(function () {
                element.LoadingOverlay("hide");
            }, 2000);
        },
        onFullScreen: function (element, isFullScreen) {

        },
        onClose: function (element, closeCallback) {
            $.confirm({
                title: 'Danger!',
                content: 'Are you sure you want to remove this widget from dashboard?',
                type: 'danger',
                buttons: {
                    confirm: {
                        text: 'Yes, remove',
                        btnClass: 'btn-danger',
                        action: function() {
                            closeCallback();
                        }
                    },
                    cancel: function () {}
                }
            });
        }
    });

    $("#frm_import input[name=url]").focus(function(){$("#frm_import input:radio").val(["url"])});
    $("#frm_import label.file-label").click(function(){$("#frm_import input:radio").val(["file"])});

    $('#geo_4 optgroup option').click(function () {
        return false;
    })
    $('#geo_4 optgroup').click(function (e) {
        $(this).find('option').attr('selected', true);
    })

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
            $cost_until = $f.find(".cost_until")

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

    if($('.click_checkbox:checked').length == $('.click_checkbox').length){
        $('#all_check').prop("checked",true);
    }

    if(!$('#check_inp').prop( "checked" )){
        $(".mydiv").addClass("disabledbutton");
    }
});

function show_hide(obj){
    if(!$(obj).prop( "checked" )){
        $(".mydiv").addClass("disabledbutton");
    } else {
        $(".mydiv").removeClass("disabledbutton");
    }
}

function check_all(checked){
    $('.click_checkbox').prop("checked",checked);
}
function verify_chboxes(){
    if($('.click_checkbox:checked').length == $('.click_checkbox').length){
        $('#all_check').prop("checked",true);
    } else {
        $('#all_check').prop("checked",false);
    }
}

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

            if (geo != 'Минск') {
                $('#form_region_check').show();
            } else {
                $('#form_region_check').hide();
            }
        });
    $('#myModal').modal();
}

function get_report_data_more(type){
    m = $('#select_m').val();
    date_from = $('#date_from').val();
    date_to = $('#date_to').val();
    $("span.btn-sm").removeClass("btn-success");
    $("#"+type).addClass("btn-success");
    $.ajax({
        method: "GET",
        url: "/bill-report/get-more-data?m="+ m+"&type="+type+"&date_from=" + date_from + "&date_to=" + date_to
    })
        .done(function(msg){
            $("#more_res").html(msg+"<br><a href='/bill-report/get-more-data-xlsx/?m="+ m+"&date_from="+date_from+"&date_to="+ date_to +"&type="+type+"' target='_blank'>Скачать</a>");
        });
    //$('#myModal').modal();
}

function get_blanks(type){
    $.ajax({
        url: "/balance/get-blanks/?type=" + type,
        type: 'get',
        dataType: 'html',
        success: function (html) {
            json = JSON.parse(html);
            $('#subscriptions').html(json.html);
            $('#te').html(json.te);
        },
        error: function () {
            console.log('ajax error');
        }
    });
}

function change_href(cl,add_name, add_value){
    $( "." + cl ).each(function() {
        href = $( this ).attr('href');
        $( this ).attr('href', href+'&'+add_name+'='+add_value);
    });
}



function add_pay() {
    i = $(".tr_tbl").length;
    $("#cost_data_table").append("<tr class='tr_tbl'><td><input name='cost_data[" + i + "][pay_until]' class='form-control pay_until' style='width:90%' type='text' /> руб. </td><td><input name='cost_data[" + i + "][cost_until]' style='width:90%' class='form-control cost_until' type='text' /> руб.</td></tr>");
}
function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
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
            $("#addition").show();
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

