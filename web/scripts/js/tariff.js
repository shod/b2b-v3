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

        if(type == 'section'){
            if($("#calc_section_"+id).length > 0){
                $("#calc_section_"+id).prop('checked', true);
            } else {
                name = $(this).parents('tr').find('strong.section-name').html();
                cost = $(this).parents('tr').find('span.section-cost').html();
                $("#calc_sections").append('<label class="custom-control custom-checkbox ks-checkbox ks-checkbox-success"><input type="checkbox" class="custom-control-input pack-line" checked id="calc_section_'+id+'"><span class="custom-control-indicator"></span><span class="custom-control-description">'+name+'</span><span class="custom-control-cost" style="float: right">'+cost+' ТЕ</span></label>');
                pack();
            }
        }
    } else {
        $(this).parents('.card').css('background', 'transparent');
        $(this).parents('tr').css('background', 'transparent');
        if(type == 'pack'){
            $("#calc_pack_"+id).prop('checked', false);
        } else {
            $("#calc_section_"+id).prop('checked', false);
        }
    }
    sum_recount();
});
pack();

function pack() {
    $('.pack-line').change(function () {
        id = $(this).attr('id').split("_");
        type = id[1];
        id = id[2];

        console.log(type);
        if ($(this).is(':checked')) {
            if(type == 'pack'){
                if($("#pack_"+id).length > 0){
                    $("#pack_"+id).prop('checked', true);
                    $("#pack_"+id).parents('.card').css('background', 'rgba(0,0,0,.05)');
                }
            } else {
                if($("#section_"+id).length > 0){
                    $("#section_"+id).prop('checked', true);
                    $("#section_"+id).parents('tr').css('background', 'rgba(0,0,0,.05)');
                }
            }
        } else {
            if(type == 'pack'){
                $("#pack_"+id).prop('checked', false);
                $("#pack_"+id).parents('.card').css('background', 'transparent');
            } else {
                $("#section_"+id).prop('checked', false);
                $("#section_"+id).parents('tr').css('background', 'transparent');
            }
        }
        sum_recount();
    });

}

function sum_recount() {
    sum = 0;
    sum_section = 0;
    sum_pack = 0;
    $( ".pack-line" ).each(function() {
        id = $(this).attr('id').split("_");
        type = id[1];
        id = id[2];
        if ($(this).is(':checked')) {
            s = parseInt($(this).parents('label').children(".custom-control-cost").html());
            console.log(s);
            if(type == 'pack'){
                sum_pack += s;
            } else {
                sum_section += s;
            }
        }
    });
    sum = sum_pack + sum_section;
    $("#sum_pack").html(sum_pack);
    $("#sum_section").html(sum_section);
    $("#sum_all").html(sum);
    $("#sum_all_day").html(Number((sum/30).toFixed(1)));
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

function save_tariff() {
    active_packs = [];
    active_sections = [];
    $( ".pack-line" ).each(function() {
        id = $(this).attr('id').split("_");
        type = id[1];
        id = id[2];
        if ($(this).is(':checked')) {
            if(type == 'pack'){
                active_packs.push(id);
            } else {
                active_sections.push(id);
            }
        }
    });

    sum = $("#sum_all").html();
    sum_day = $("#sum_all_day").html();

    if (sum > 9){
        $.confirm({
            title: 'Внимание!',
            content: 'Сумма списания за размещение в каталоге составит <b>'+sum+'</b> ТЕ/мес   (<b>'+sum_day+'</b>/день) <br>Вы уверены в выборе?',
            type: 'primary',
            columnClass: 'col-md-4 col-md-offset-4',
            buttons: {
                confirm: {
                    text: 'Сохранить', // With spaces and symbols
                    btnClass: 'btn-primary',
                    action: function () {

                        $.ajax({
                            method: "GET",
                            url: "/tariff/process/?action=save&pack="+JSON.stringify(active_packs)+"&section="+JSON.stringify(active_sections),
                            dataType: 'html',
                        })
                            .done(function( msg ) {
                                $.alert({
                                    title: "Тариф успешно сохранен",
                                    type: 'blue',
                                    content: 'Для продолжения работы нажмите ОК',
                                });
                            });
                    }
                },
                cancel: {
                    text: 'Отменить'
                }
            }
        });
    } else {
        $.alert({
            title: "Предупреждение",
            type: 'blue',
            content: 'Ваш тариф (сумма выбранных пакетов и/или разделов) не может быть менее 12 ТЕ.',
        });
    }


}