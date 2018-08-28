/**
 * Created by Миг-к101 on 26.04.2018.
 */

$( document ).ready(function() {
    page_addition();
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

