var varUpdateInterval = 10000;
var varUpdateTime = 100;

var updateData = function () {
    $.getJSON("/spec/process/", {action: "update", time: varUpdateTime}, function (json) {
        varUpdateTime = json.time

        $.each(json.data, function (catalog_id, data) {
            var html = "";
            $.each(data, function (i, obj) {
                var selected = (parseInt(obj.selected)) ? ' class="selected"' : ""
                html = html + "<li" + selected + ' title="' + obj.name + '">' + obj.cost + "</li>";
            })
            $("#data_" + catalog_id).html("<ol>" + html + "</ol>")

        })

        setTimeout(updateData, varUpdateInterval)
    })

}
setTimeout(updateData, varUpdateInterval)

$(document).ready(function () {
    $("#formSpec input:text").keydown(function (e) {
        if (e.which == 13) {
            $("#formAuction").submit()
        }
    });

    $("#formSpec").submit(function () {
        $(this).find("input:submit").attr({disabled: true, value: "Подождите..."})
        $(this).find(".models_x option").attr('selected', 'yes')
    })
});

var clarify = function (catalog_id) {
    if ($("#content_clarify_" + catalog_id).length == 0)
        $("#td_" + catalog_id).append('<div id="content_clarify_' + catalog_id + '">Загрузка...</div>')

    $.ajax({
        url: "/spec/process/?action=clarify&catalog_id="+ catalog_id,
        type: 'get',
        dataType: 'html',
        success: function (html) {
            var $this = $("#content_clarify_" + catalog_id);
            $this.html(html);
            $this.find(".brands").change(function () {
                $this.find(".select_models").hide()
                $this.find("#models_" + catalog_id + "_" + $(this).val()).show()
            })

            $this.find(".select_models").each(function () {
                var $d = $(this).closest(".select_models"), $s1 = $d.find("select:first"), $s2 = $d.find("select:last");
                $(this).find("input:button:first").click(function () {
                    $s1.find("option:selected").appendTo($s2);
                });
                $(this).find("input:button:last").click(function () {
                    $s2.find("option:selected").appendTo($s1);
                });
            })
        },
        error: function () {
            console.log('ajax error');
        }
    });

}
