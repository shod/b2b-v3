/**
 * Created by Миг-к101 on 26.04.2018.
 */

$( document ).ready(function() {
    page_addition();
});

$(document).keypress(function (e) {
    if (e.which == 13) {
        tr = $(document.activeElement).closest('tr');
        bg = tr.css('background-color');
        if(bg=='rgb(238, 238, 238)'){
            type = 'delete';
        } else if(bg=='rgb(255, 255, 187)'){
            type = 'create';
        } else {
             type = 'update';
        }

        tr.css( "background-color", "rgb(210, 210, 210)" );
        id = tr.attr('id').split("_")[2];
        product_id = tr.attr('id').split("_")[3];
        console.log(product_id);

        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        cost = tr.find("[name*='cost']").val();
        desc = tr.find("[name*='desc']").val();
        wh = tr.find("[name*='wh_state']").val();
        delivery = tr.find("[name*='delivery_day']").val();
        garant = tr.find("[name*='garant']").val();
        manufacturer = tr.find("[name*='manufacturer']").val();
        importer = tr.find("[name*='importer']").val();
        service = tr.find("[name*='service']").val();
        term = tr.find("[name*='term_use']").val();
        link = tr.find("[name*='link']").val();
        no_auto = tr.find("[name*='no_auto']").prop('checked');
        if(no_auto){
            no_auto = 1;
        } else {
            no_auto = 0;
        }


        $.ajax({
            method: "POST",
            url: "/product/save-one-prod",
            data: { _csrf : csrfToken, id: id, product_id: product_id, type: type, cost: cost, desc: desc, wh_state: wh,  delivery: delivery, garant:garant, manufacturer: manufacturer, importer:importer, service:service, term:term, link:link, no_auto: no_auto},
            success: function (html) {
                console.log( "Data Saved: " + html );
                if(type == 'create'){
                    id = 'product_tr_'+html+"_"+product_id;
                    tr.attr('id',id);
                    tr.css( "background-color","#f5f6fa" );
                } else {
                    tr.css( "background-color",bg );
                }

            },
            error: function () {
                $.alert({
                    title: 'Что-то пошло не так :( попробуйте еще раз!',
                    type: 'red',
                    content: 'Для продолжения работы нажмите ОК',
                });
            }
        });

    }
});

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
        console.log(json);
        $(json.data).hide().prependTo("#" + table_id).fadeIn();
        $(json.brands).hide().appendTo("#brands").fadeIn();
        $(json.pages).hide().prependTo("#pages").fadeIn();
        $(json.pages).hide().prependTo("#pages-2").fadeIn();
        work_type($("#work_type").prop("checked"));
        page_addition();
        $('ul a.page-link').click(function() { getAjaxData('theForm',$(this).prop('href'), 'productTable');return false;});
        $('#catalog_name_header').html("Раздел " + $( "#product_catalog_name option:selected" ).text());
        $('#catalog_id_form').val(json.catalog_id);
        $("[data-dashboard-widget]").LoadingOverlay("hide");
        $('.card-block').animate({scrollTop: 0},200);
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
        url_data = $("#pages").find("li.active").find("a").attr('href');
        getAjaxData('theForm',url_data,'productTable');
    } );
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
        $("select.wh_state", tr).focus();
    })
}

