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