/**
 * Created by Миг-к101 on 14.02.2018.
 */
$('.pack-checkbox').change(function () {
    if ($(this).is(':checked')) {
        $(this).parents('.card').css('background', 'rgba(0,0,0,.05)');
        $(this).parents('tr').css('background', 'rgba(0,0,0,.05)');
    } else {
        $(this).parents('.card').css('background', 'transparent');
        $(this).parents('tr').css('background', 'transparent');
    }
});

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