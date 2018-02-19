/**
 * Created by Миг-к101 on 14.02.2018.
 */
$('.pack-checkbox').change(function () {
    id = $(this).attr('id').split("_");
    type = id[0];
    id = id[1];

    if ($(this).is(':checked')) {
        $(this).parents('.card').css('background', 'rgba(0,0,0,.05)');
        $(this).parents('tr').css('background', 'rgba(0,0,0,.05)');
        if(type == 'pack'){
            if($("#calc_pack_"+id).length > 0){
                $("#calc_pack_"+id).prop('checked', true);
            } else {
                name = $(this).parents('.card').find('span.pack-name').html();
                cost = $(this).parents('.card').find('span.pack-cost').html();
                $("#calc_packs").append('<label class="custom-control custom-checkbox ks-checkbox ks-checkbox-success"><input type="checkbox" class="custom-control-input pack-line" checked id="calc_pack_'+id+'"><span class="custom-control-indicator"></span><span class="custom-control-description">'+name+'</span><span class="custom-control-cost" style="float: right">'+cost+' ТЕ</span></label>');
                pack();
            }
        }
    } else {
        $(this).parents('.card').css('background', 'transparent');
        $(this).parents('tr').css('background', 'transparent');
        if(type == 'pack'){
            $("#calc_pack_"+id).prop('checked', false);
        }
    }
});
pack();

function pack() {
    $('.pack-line').change(function () {
        id = $(this).attr('id').split("_");
        type = id[1];
        id = id[2];

        if ($(this).is(':checked')) {
            if(type == 'pack'){
                if($("#pack_"+id).length > 0){
                    $("#pack_"+id).prop('checked', true);
                    $("#pack_"+id).parents('.card').css('background', 'rgba(0,0,0,.05)');
                }
            }
        } else {
            if(type == 'pack'){
                $("#pack_"+id).prop('checked', false);
                $("#pack_"+id).parents('.card').css('background', 'transparent');
            }
        }
    });
}




function search_str(str) {
    $('.col-xl-4').show();
    $('tr').show();
    $(".pack-name").unmark();
    $(".pack-sections").unmark();
    $(".section-name").unmark();
    if(str){
        $( ".pack-name" ).each(function() {
            name = $( this ).html();
            if (~name.toLowerCase().indexOf(str.toLowerCase())){
                $( this ).parents('.col-xl-4').show();
            } else {
                $( this ).parents('.col-xl-4').hide();
            }
        });

        $( ".pack-sections" ).each(function() {
            name = $( this ).html();
            if (~name.toLowerCase().indexOf(str.toLowerCase())){
                $( this ).parents('.col-xl-4').show();
            }
        });

        $( ".section-name" ).each(function() {
            names = $( this ).html();
            if (~names.toLowerCase().indexOf(str.toLowerCase())){
                $( this ).parents('tr').show();
            } else {
                $( this ).parents('tr').hide();
            }
        });
        $( ".pack-name" ).mark(str);
        $( ".pack-sections" ).mark(str);
        $( ".section-name" ).mark(str);

    }

}