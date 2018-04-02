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
    });

    $('.notify-button').click(function() {
        var $this = $(this);
        action = $(this).attr('id')=='email' ? 'edit-email' : 'edit-phone';
        data = $("#"+$(this).attr('id')+"-value").val();
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

    page_addition();

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
                type: 'blue',
                content: 'Для продолжения работы нажмите ОК',
            });
            $city.focus();
            return false;
        }
        if ($street.val() == '') {
            $.alert({
                title: "Введите название улицы.",
                type: 'blue',
                content: 'Для продолжения работы нажмите ОК',
            });

            $street.focus();
            return false;
        }
        if ($house.val() == '') {
            $.alert({
                title: "Введите номер дома (корпуса).",
                type: 'blue',
                content: 'Для продолжения работы нажмите ОК',
            });


            $house.focus();
            return false;
        }
        if ($type.length == 0) {
            $.alert({
                title: "Выберите хотя бы один тип объекта.",
                type: 'blue',
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
                type: 'blue',
                content: 'Для продолжения работы нажмите ОК',
            });

            $geo_id.focus();
            return false;
        }
        if ($type_id.length == 0) {
            $.alert({
                title: "Выберите тип доставки.",
                type: 'blue',
                content: 'Для продолжения работы нажмите ОК',
            });

            $type_id.focus();
            return false;
        }

        if (($type_id[0].value == 3) && (($cost[0].value == "") || (!isNumber($cost[0].value)))) {
            $.alert({
                title: "Введите стоимость доставки.",
                type: 'blue',
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
                        type: 'blue',
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
                        type: 'blue',
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

function getAjaxData(form_id,url,table_id){
    $("[data-dashboard-widget]").LoadingOverlay("show", {
        color: 'rgba(255, 255, 255, 0.8)',
        image: '',
        fontawesome : "la la-refresh la-spin",
        zIndex: 11

    });
    $.post( url, $('#'+form_id).serialize(), function (data) {
        $("#" + table_id ).html("");
        $("#brands").html("<option value='0'>Производитель</option>");
        $("#pages").html("");
        $("#pages-2").html("");
        json = JSON.parse(data);
        $(json.data).hide().prependTo("#" + table_id).fadeIn();
        $(json.brands).hide().appendTo("#brands").fadeIn();
        $(json.pages).hide().prependTo("#pages").fadeIn();
        $(json.pages).hide().prependTo("#pages-2").fadeIn();
        work_type($("#work_type").prop("checked"));
        page_addition();
        $('ul a.page-link').click(function() { getAjaxData('theForm',$(this).prop('href'), 'productTable');return false;});
        $("[data-dashboard-widget]").LoadingOverlay("hide");
    } );
}

function saveAjaxProducts(form_id,url,table_id) {
    $("[data-dashboard-widget]").LoadingOverlay("show", {
        color: 'rgba(255, 255, 255, 0.8)',
        image: '',
        fontawesome : "la la-refresh la-spin",
        zIndex: 11

    });
    $.post( url, $('#'+form_id).serialize(), function (data) {
        $("[data-dashboard-widget]").LoadingOverlay("hide");
        getAjaxData('theForm','/product/get-data-products/?','productTable');
    } );
}

function work_type(flag) {

    if(flag){
        $("th.product-item").hide();
        $('#productTable').find("td.product-item").hide();

        $("th.product-addation-tr").show();
        $('#productTable').find("td.product-addation-tr").show();

    } else{
        $("th.product-item").show();
        $('#productTable').find("td.product-item").show();

        $("th.product-addation-tr").hide();
        $('#productTable').find("td.product-addation-tr").hide();
    }
}

function page_addition(){
    $('ul a.page-link').click(function() { getAjaxData('theForm',$(this).prop('href'), 'productTable');return false;});
    $(".do_clone").click(function(){
        var tr = $(this).closest("tr")
        tr.after(tr.clone(true)).next("tr").css({"background-color" : "#ffffbb"})
    })

    $("#default_wh_state").change(function(){ $("td .wh_state").val( $(this).val() ) })
    var t_desc;

    $("#default_desc").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text").val( $("#default_desc").val() ) }, 300 )
    })
    $("#default_desc").on('paste', function() {
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text").val( $("#default_desc").val() ) }, 300 )
    })

    $("#default_desc_manufacturer").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_manufacturer").val( $("#default_desc_manufacturer").val() ) }, 300 )
    })
    $("#default_desc_manufacturer").on('paste', function() {
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_manufacturer").val( $("#default_desc_manufacturer").val() ) }, 300 )
    })

    $("#default_desc_import").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_import").val( $("#default_desc_import").val() ) }, 300 )
    })
    $("#default_desc_import").on('paste', function() {
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_import").val( $("#default_desc_import").val() ) }, 300 )
    })

    $("#default_desc_import").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_import").val( $("#default_desc_import").val() ) }, 300 )
    })
    $("#default_desc_service").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_service").val( $("#default_desc_service").val() ) }, 300 )
    })
    $("#default_desc_service").on('paste', function() {
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_service").val( $("#default_desc_service").val() ) }, 300 )
    })

    $("#default_delivery_day").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_delivery_day").val( $("#default_delivery_day").val() ) }, 300 )
    })
    $("#default_delivery_day").on('paste', function() {
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_delivery_day").val( $("#default_delivery_day").val() ) }, 300 )
    })

    $("#default_term_use").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_term_use").val( $("#default_term_use").val() ) }, 300 )
    })
    $("#default_term_use").on('paste', function() {
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_term_use").val( $("#default_term_use").val() ) }, 300 )
    })

    $("#default_garant").keypress(function(){
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_garant").val( $("#default_garant").val() ) }, 300 )
    })
    $("#default_garant").on('paste', function() {
        clearTimeout(t_desc)
        t_desc = setTimeout( function(){  $(".desc_text_garant").val( $("#default_garant").val() ) }, 300 )
    })
    $(".do_delete").click(function(){
        var tr = $(this).closest("tr").css({"background-color" : "#eeeeee"})
        $("td a", tr).css({color: "#aaaaaa"})
        $("select.wh_state", tr).val(3)
        $("input.del_input", tr).val(-1)
        $(this).hide(0)
    })
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

function get_chart(date){
    date_chart = date;
    $.ajax({
        type: "GET",
        url: "/statistic/get-chart/?date=" + date,
        success: function(json){

            if (json != 'null'){
                $(function () {
                    var jsonStr = JSON.parse(json);
                    chart_data = new Array;
                    chart_data_proxy = new Array;
                    chart_data_context = new Array;
                    chart_data_sum = new Array;
                    for (var i = 0; i < jsonStr.length; i++) {
                        date_arr = jsonStr[i].date_view.split(", ");
                        year = parseInt(date_arr[0]);
                        month = parseInt(date_arr[1]);
                        day = parseInt(date_arr[2]);
                        date = Date.UTC(year,  month, day);
                        view = jsonStr[i].view;
                        view_proxy = jsonStr[i].view_proxy;
                        view_context = jsonStr[i].view_context;
                        chart_data.push([parseInt(date), parseInt(view)]);
                        chart_data_proxy.push([parseInt(date), parseInt(view_proxy)]);
                        chart_data_context.push([parseInt(date), parseInt(view_context)]);
                        chart_data_sum.push([parseInt(date), parseInt(view)+parseInt(view_proxy)+parseInt(view_context)]);

                    }
                    Highcharts.setOptions({
                        lang: {
                            loading: 'Загрузка...',
                            months: ['Декабрь','Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь'],
                            weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                            shortMonths: ['Дек','Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб'],
                            exportButtonTitle: "Экспорт",
                            printButtonTitle: "Печать",
                            rangeSelectorFrom: "С",
                            rangeSelectorTo: "По",
                            rangeSelectorZoom: "Период",
                            downloadPNG: 'Скачать PNG',
                            downloadJPEG: 'Скачать JPEG',
                            downloadPDF: 'Скачать PDF',
                            downloadSVG: 'Скачать SVG',
                            printChart: 'Напечатать график'
                        }
                    });
                    $('#chart').highcharts({
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Статистика кликов по дням ' + date_chart
                        },
                        xAxis: {
                            type: 'datetime',
                            dateTimeLabelFormats: { // don't display the dummy year
                                month: '%e. %b',
                                year: '%b'
                            },
                            title: {
                                text: 'Дата'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Количество'
                            },
                            min: 0
                        },
                        tooltip: {
                            headerFormat: '<b>{series.name}</b><br>',
                            pointFormat: '{point.x:%e %b}: {point.y}'
                        },

                        series: [{
                            name: 'Количество кликов',
                            data: chart_data
                        },{
                            name: 'Количество переходов',
                            data: chart_data_proxy
                        },{
                            name: 'Количество по контекстной рекламе',
                            data: chart_data_context
                        },{
                            name: 'Всего',
                            data: chart_data_sum
                        }]
                    });
                });
            }

        }
    });
}

function get_chart_ctr(date){
    date_chart = date;
    $.ajax({
        type: "GET",
        url: "/statistic/get-chart-ctr/?date=" + date,
        success: function(json){

            if (json != 'null'){
                $(function () {
                    var jsonStr = JSON.parse(json);
                    chart_data = new Array;
                    chart_data_all = new Array;
                    for (var i = 0; i < jsonStr.length; i++) {
                        date_arr = jsonStr[i].date_view.split(", ");
                        year = parseInt(date_arr[0]);
                        month = parseInt(date_arr[1]);
                        day = parseInt(date_arr[2]);
                        date = Date.UTC(year,  month, day);
                        view = jsonStr[i].view;
                        view_all = jsonStr[i].view_all;

                        chart_data.push([parseInt(date), parseFloat(view)]);
                        chart_data_all.push([parseInt(date), parseFloat(view_all)]);
                    }

                    Highcharts.setOptions({
                        lang: {
                            loading: 'Загрузка...',
                            months: ['Декабрь','Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь'],
                            weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                            shortMonths: ['Дек','Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб'],
                            exportButtonTitle: "Экспорт",
                            printButtonTitle: "Печать",
                            rangeSelectorFrom: "С",
                            rangeSelectorTo: "По",
                            rangeSelectorZoom: "Период",
                            downloadPNG: 'Скачать PNG',
                            downloadJPEG: 'Скачать JPEG',
                            downloadPDF: 'Скачать PDF',
                            downloadSVG: 'Скачать SVG',
                            printChart: 'Напечатать график'
                        }
                    });

                    $('#chart_ctr').highcharts({
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Статистика CTR ' + date_chart
                        },
                        xAxis: {
                            type: 'datetime',
                            dateTimeLabelFormats: { // don't display the dummy year
                                month: '%e. %b',
                                year: '%b'
                            },
                            title: {
                                text: 'Дата'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Значение'
                            },
                            min: 0
                        },
                        tooltip: {
                            headerFormat: '<b>{series.name}</b><br>',
                            pointFormat: '{point.x:%e %b}: {point.y}%'
                        },

                        series: [{
                            name: 'CTR магазина',
                            data: chart_data
                        },{
                            name: 'Средний CTR по всем магазинам',
                            data: chart_data_all
                        },]
                    });
                });
            }

        }
    });
}

function get_chart_sections(date){
    chart_data = new Array();
    chart_data_small = new Array();
    $( ".sections_clicks" ).each(function( index ) {
        tds = $( this ).children();

        name = tds[0].textContent;
        cnt = tds[1].textContent;
        if(parseInt(cnt) < 10){
            chart_data_small.push([name, parseInt(cnt)]);
        } else {
            chart_data.push([name, parseInt(cnt)]);
        }

    });
    if (chart_data.length > 1){
        Highcharts.chart('chart_sections', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Статистика по разделам '+ date
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Количество кликов'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
            },

            series: [{
                name: 'Раздел',
                //colorByPoint: true,
                data: chart_data
            }]
        });
    }
    //console.log(chart_data_small);
    if (chart_data_small.length > 1){
        Highcharts.chart('chart_sections_small', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Статистика по разделам (количество кликов меньше 10) ' + date
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Количество кликов'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
            },

            series: [{
                name: 'Раздел',
                //colorByPoint: true,
                data: chart_data_small
            }]
        });
    }
}

function get_stat(date) {
    $.ajax({
        type: "GET",
        url: "/statistic/get-day-stat/?date=" + date,
        success: function (data) {
            html = data;
            $('#detail').html("<div id='chart_hours'></div>" + html);
            $.ajax({
                type: "GET",
                url: "/statistic/get-chart-hours/?date=" + date,
                success: function(json){

                    if (json != 'null'){
                        $(function () {
                            var jsonStr = JSON.parse(json);
                            chart_data = new Array;
                            for (var i = 0; i < jsonStr.length; i++) {
                                name = jsonStr[i].date_view;
                                view = jsonStr[i].view;
                                chart_data.push([name+":00", parseInt(view)]);
                            }
                            console.log(chart_data);

                            Highcharts.chart('chart_hours', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Статистика по часам ' + date
                                },
                                xAxis: {
                                    type: 'category'
                                },
                                yAxis: {
                                    title: {
                                        text: 'Количество кликов'
                                    }

                                },
                                legend: {
                                    enabled: false
                                },
                                plotOptions: {
                                    series: {
                                        borderWidth: 0,
                                        dataLabels: {
                                            enabled: true,
                                            format: '{point.y}'
                                        }
                                    }
                                },

                                tooltip: {
                                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                                },

                                series: [{
                                    name: 'Раздел',
                                    data: chart_data
                                }]
                            });


                        });
                    }

                }
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "/statistic/get-stat-group/?date=" + date,
        success: function (data) {
            html = data;
            $('#group').html("<div id='section_group_chart'></div>" + html);

            chart_data = new Array();
            $( ".sections_clicks_group" ).each(function( index ) {
                tds = $( this ).children();
                name = tds[0].textContent;
                cnt = tds[1].textContent;
                chart_data.push([name, parseInt(cnt)]);
            });
            if (chart_data.length > 1){
                Highcharts.chart('section_group_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Статистика по разделам ' + date
                    },
                    xAxis: {
                        type: 'category'
                    },
                    yAxis: {
                        title: {
                            text: 'Количество кликов'
                        }

                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                    },

                    series: [{
                        name: 'Раздел',
                        data: chart_data
                    }]
                });
            }

            $('#myModal').modal('show');
        }
    });
}
