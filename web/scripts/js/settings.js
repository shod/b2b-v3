/**
 * Created by Миг-к101 on 03.04.2018.
 */
$(document).ready(function () {
    $('.del-img').on('click', function(event){
        $this = $(this);
        var $id = $(this).attr('id');
        $.ajax({
            url: "/settings/process/?action=del_img_registration&file_name="+$id,
            success: function(data){
                var data = eval("(" + data + ")");
                if(data.success) {
                    $this.parent().empty();
                    $.alert({
                        title: "Документ был успешно удален",
                        type: 'blue',
                        content: 'Для продолжения работы нажмите ОК',
                    });
                } else {
                    $.alert({
                        title: "Произошла ошибка при удалении документа.",
                        type: 'blue',
                        content: 'Попробуйте выполнить операцию еще раз.',
                    });
                }
            }
        });
    });

    $(function(){
        var imgs = new Array();
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var btnUpload=$('#upload');
        var count = 0;
        var content = '';
        new AjaxUpload(btnUpload, {
            action: '/settings/process/?action=add_img_registration',
            name: 'img[]',
            multiple: true,
            data: {_csrf : csrfToken},
            onSubmit: function(file, ext) {
                /*|JPG|PNG|JPEG|GIF*/
                if (! (ext && /^(jpg|png|jpeg|gif|JPG|PNG|JPEG|GIF)$/.test(ext))){
                    $('#status').text('Только jpg, png, gif файлы');
                    $('#status').show();
                    return false;
                }
                ++count;
                $('#procces_load_img').show();

            },
            onComplete: function(file, response) {

                var data = JSON.parse(response);

                if(data.status) {
                    --count;
                    if(count == 0) {
                        $('#procces_load_img').hide();
                    }
                    var content = '';
                    for (var i in data.src) {
                        content += '<div class="item-info-file"><div class="del-img" id="'+data.file_name[i]+'"></div><img src="'+data.src[i]+'" width="50" /></div>';
                    }

                    $(content).appendTo('#files');

                } else {
                    alert(data.text);
                }
            }
        });
    });
});