$(document).ready(function () {

    $('[data-toggle="ajaxWidget"]').each(function () {
        var $this = $(this),
            $remoteUrl = $this.data('remote');
        $(this).load($remoteUrl);

    });

    get_notifications();
    $.validate();

    page_url = window.location.pathname;
    $("ul.nav-stacked a.dropdown-item").each(function () {
        href = $(this).attr('href');
        if (href == page_url) {
            $(this).addClass('ks-active');
            $(this).parents("li.dropdown").addClass('open');
        }
    });

    var $ajaxModalLinks = $('[data-toggle="ajaxModal"]');

    $ajaxModalLinks.on('click', function (e) {
        $("[data-dashboard-widget]").LoadingOverlay("show", {
            color: 'rgba(255, 255, 255, 0.8)',
            image: '',
            fontawesome: "la la-refresh la-spin",
            zIndex: 11

        });
        var $this = $(this),
            $remoteUrl = $this.data('remote') || $this.attr('href'),
            $modal = $('<div id="myDefaultModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="modal-title"></h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="la la-close"></span></button></div><div class="modal-body" id="modal-body"></div></div></div></div>');
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


    $('.button-sms').click(function () {
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
                        $this.attr('disabled', 'disabled');
                        $.ajax({
                            url: "/order/process/?order_id=" + $p[1] + "&action=" + $p[0],
                            type: 'get',
                            dataType: 'html',
                            success: function (html) {
                                document.getElementById("tr_" + $p[1]).style.display = 'none';
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

    $('#active-sms').click(function () {
        var $this = $(this);
        action = $(this).prop('checked') ? 'active' : 'deactive';
        if (($("#email-value").val() == '') && (action == 'active')) {
            $.alert({
                title: "Поле Email обязательно для заполнения!",
                type: 'red',
                content: 'Для продолжения работы нажмите ОК',
            });
            $(this).prop('checked', false);
        } else {
            $.ajax({
                url: "/order/process/?action=" + action,
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

    $('.notify-button').click(function () {
        var $this = $(this);
        action = $(this).attr('id') == 'email' ? 'edit-email' : 'edit-phone';
        data = $("#" + $(this).attr('id') + "-value").val();
        if (($(this).attr('id') == 'email') && (data == '')) {
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
                fontawesome: "la la-refresh la-spin",
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
                        action: function () {
                            closeCallback();
                        }
                    },
                    cancel: function () {
                    }
                }
            });
        }
    });

    $("#frm_import input[name=url]").focus(function () {
        $("#frm_import input:radio").val(["url"])
    });
    $("#frm_import label.file-label").click(function () {
        $("#frm_import input:radio").val(["file"])
    });

    $('#geo_4 optgroup option').click(function () {
        return false;
    })
    $('#geo_4 optgroup').click(function (e) {
        $(this).find('option').attr('selected', true);
    })

    if ($('.click_checkbox:checked').length == $('.click_checkbox').length) {
        $('#all_check').prop("checked", true);
    }

    if (!$('#check_inp').prop("checked")) {
        $(".mydiv").addClass("disabledbutton");
    }
});

function show_annotation() {
    var anno = new Anno([{
        target: 'nav:first',
        content: "Панель с балансом и статусом продавца",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    }, {
        target: 'div.ks-sidebar',
        position: 'right',
        content: "Основное меню",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    },{
        target: 'div.ks-widget-payment-total-amount:first',
        position: 'bottom',
        content: "Ваш баланс",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    },{
        target: 'div.ks-widget-payment-price-ratio:first',
        position: 'bottom',
        content: "Обещанный платеж",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    },{
        target: 'div.ks-widget-tasks-statuses-progress:first',
        position: 'bottom',
        content: "Использование возможностей продвижения на площадке migom.by",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    },{
        target: 'div.ks-widget-payment-total-amount:eq(1)',
        position: 'bottom',
        content: "Ваши товары в продаже и цены на ваши товары",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    },{
        target: 'div.ks-widget-payment-card-rate-details',
        position: 'bottom',
        content: "Участие в аукционах",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    },{
        target: 'div.row:eq(1)',
        position: 'bottom',
        content: "Информация о подключенных акциях, отзывах и жалобах на магазин и последняя новость для продавцов.",
        buttons: [AnnoButton.NextButton, AnnoButton.DoneButton]
    },{
        target: 'div.row:eq(2)',
        position: 'top',
        content: "Справочная информация.",
        buttons: [AnnoButton.DoneButton]
    }]);
    anno.show();
}

function show_hide(obj) {
    if (!$(obj).prop("checked")) {
        $(".mydiv").addClass("disabledbutton");
    } else {
        $(".mydiv").removeClass("disabledbutton");
    }
}

function check_all(checked) {
    $('.click_checkbox').prop("checked", checked);
}

function verify_chboxes() {
    if ($('.click_checkbox:checked').length == $('.click_checkbox').length) {
        $('#all_check').prop("checked", true);
    } else {
        $('#all_check').prop("checked", false);
    }
}

function ajaxSubmit(obj) {
    var frm = $('#' + obj);

    frm.submit(function (e) {

        e.preventDefault();

        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
                $('#myDefaultModal').modal('hide');
                $.alert({
                    title: data,
                    type: 'blue',
                    content: 'Для продолжения работы нажмите ОК',
                });
                console.log('Submission was successful.');
                console.log(data);
            },
            error: function (data) {
                $('#myDefaultModal').modal('hide');
                $.alert({
                    title: 'Что-то пошло не так :( попробуйте еще раз!',
                    type: 'red',
                    content: 'Для продолжения работы нажмите ОК',
                });
                console.log('An error occurred.');
                console.log(data);
            },
        });
    });
    frm.submit();
}


function get_report_data_more(type) {
    m = $('#select_m').val();
    date_from = $('#date_from').val();
    date_to = $('#date_to').val();
    $("span.btn-sm").removeClass("btn-success");
    $("#" + type).addClass("btn-success");
    $("#more_res").html("");
    $("#more_res").LoadingOverlay("show", {
        color: 'rgba(255, 255, 255, 0.8)',
        image: '',
        fontawesome: "la la-refresh la-spin",
        zIndex: 1100
    });

    $.ajax({
        method: "GET",
        url: "/bill-report/get-more-data?m=" + m + "&type=" + type + "&date_from=" + date_from + "&date_to=" + date_to
    })
        .done(function (msg) {
            $("#more_res").LoadingOverlay("hide");
            $("#more_res").html(msg + "<br><a href='/bill-report/get-more-data-xlsx/?m=" + m + "&date_from=" + date_from + "&date_to=" + date_to + "&type=" + type + "' target='_blank'>Скачать</a>");
        });
    //$('#myModal').modal();
}

function get_blanks(type) {
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

function change_href(cl, add_name, add_value) {
    $("." + cl).each(function () {
        href = $(this).attr('href');
        $(this).attr('href', href + '&' + add_name + '=' + add_value);
    });
}

function set_sum(obj, max){
    $("#sub_button").addClass("disabled");
    max_sum = typeof max !== 'undefined' ? max : $("#max_sum").text();

    if (obj.value != ''){
        if (parseFloat(obj.value) <= parseFloat(max_sum) && parseFloat(obj.value) > 0){
            $("#sub_button").removeClass("disabled");
        } else {
            alert('обещанный платеж не должен превышать '+max_sum+' сумму и должен быть больше 0!');
        }
    }
}

function get_notifications() {
    $.ajax({
        method: "GET",
        url: "/notifications/process/?action=get_notify"
    })
        .done(function (msg) {
            if (msg) {
                data = JSON.parse(msg);
                $.confirm({
                    title: 'Обратите внимание',
                    content: data.tmpl,
                    columnClass: 'col-md-7',
                    buttons: {
                        confirm: {
                            text: data.button_name,
                            action: function () {
                                id = data.id;
                                $.ajax({
                                    method: "GET",
                                    url: "/notifications/process/?action=set_notify&id=" + id
                                })
                                    .done(function (msg) {
                                        if (msg) {
                                            //alert(msg);
                                        }
                                    });
                                location.href = data.href;
                            }
                        },
                        cancel: {
                            text: 'Закрыть'
                        }
                    }
                });
            }
        });

    $.ajax({
        method: "GET",
        url: "/notifications/process/?action=get_notify_reviews"
    })
        .done(function (msg) {
            if (msg) {
                data = JSON.parse(msg);
                sum = 0;
                for (var k in data) {
                    $("#" + k + "_notify").html("+" + data[k]);
                    sum += data[k];
                }
                if (sum > 0) {
                    $("#review_owner").html("<span class='badge badge-pill badge-crusta ks-label'>" + sum + "</span>");
                }
            }
        });

    $.ajax({
        method: "GET",
        url: "/notifications/process/?action=get_notify_po_order"
    })
        .done(function (msg) {
            if (msg) {
                data = JSON.parse(msg);
                if (data['po_cnt'] > 0) {
                    $("#po_cnt").html("<span class='badge badge-pill badge-crusta ks-label'>" + data['po_cnt'] + "</span>");
                    $("#po_notify").html("+" + data['po_cnt']);
                }
            }
        });
}


function add_pay() {
    i = $(".tr_tbl").length;
    $("#cost_data_table").append("<tr class='tr_tbl'><td><input name='cost_data[" + i + "][pay_until]' class='form-control pay_until' style='width:90%' type='text' /> руб. </td><td><input name='cost_data[" + i + "][cost_until]' style='width:90%' class='form-control cost_until' type='text' /> руб.</td></tr>");
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

