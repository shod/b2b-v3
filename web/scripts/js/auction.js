var varUpdateInterval = 10000
var varUpdateTime = 10000

var updateData = function()
{
    $.getJSON("/auction/process",{action:"update", time: varUpdateTime}, function(json)
    {
        varUpdateTime = json.time

        $.each(json.data, function(catalog_id, data){
            var html = "";
            $.each(data, function(i, obj){
                var selected = (parseInt(obj.selected)) ? 'style="background: rgb(245, 222, 227);font-size: 11px;"' : 'style="font-size: 11px;"';
                html = html + '<li ' +selected+' title="'+obj.name+'"> <div class="name-seller-au"><b>' +obj.cost+'&nbsp;</b><span style="color: rgba(129, 129, 129, 0.57)">'+obj.name+'</span></div></li>';

            })
            $("#data_"+catalog_id).html("<ol>"+html+"</ol>")

        })

        setTimeout(updateData, varUpdateInterval)
    })

}
setTimeout(updateData, varUpdateInterval)