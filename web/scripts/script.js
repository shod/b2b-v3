$( document ).ready(function() {
    var $ajaxModalLinks = $('[data-toggle="ajaxModal"]');
    console.log($ajaxModalLinks);
    $ajaxModalLinks.on('click', function (e) {
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
                $modal.modal('show');
            },
            error: function () {
                console.log('modal ajax error');
            }
        });
    });
});

