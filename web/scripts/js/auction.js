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
setTimeout(updateData, varUpdateInterval);

$(document).ready(function(){
    $("#formAuction input:text").keydown(function (e) {
        if (e.which == 13)
        {
            $("#formAuction").submit()
        }
    })

    var t_cost;
    $("#cost_all").keypress(function(){
        var $this = $(this);
        clearTimeout(t_cost);
        t_cost = setTimeout( function(){ $('.cost').val($this.val()) }, 300 )
    })


    setTimeout(reverse_time, 1000);


})

/*Обновление времени до конца аукционов*/
var reverse_time = function ()
{
    var a=new Date;
    var b = new Date;
    b.setHours(17,40,00);
    var sek= parseInt(+b- +a)/1000
    var str = parseInt(sek/3600)+':'+parseInt((sek%3600)/60)+':'+parseInt((sek%3600)%60)
    str = str +" до завершения";

    if(sek<=0){
        $("#btnSaveFix").prop("disabled",true);
        str = "Прием ставок завершен";
    }else{
        setTimeout(reverse_time, 1000);
    }
    $("#reverce_time").text(str);
}