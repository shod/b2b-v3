/**
 * Created by Миг-к101 on 26.04.2018.
 */

function get_chart(date, type = 'spline'){
    date_chart = date;
    $.ajax({
        type: "GET",
        url: "/statistic/get-chart/?date=" + date,
        success: function(json){

            if ((json != 'null') && (json != "[]")){
                $(function () {
                    var jsonStr = JSON.parse(json);
                    chart_data = new Array;
                    chart_data_proxy = new Array;
                    chart_data_context = new Array;
                    chart_data_sum = new Array;
                    for (var i = 0; i < jsonStr.length; i++) {
                        date_arr = jsonStr[i].date_view.split(", ");
                        year = parseInt(date_arr[0]);
                        month = parseInt(date_arr[1]);
                        day = parseInt(date_arr[2]);
                        date = Date.UTC(year,  month, day);
                        view = jsonStr[i].view;
                        view_proxy = jsonStr[i].view_proxy;
                        view_context = jsonStr[i].view_context;
                        chart_data.push([parseInt(date), parseInt(view)]);
                        chart_data_proxy.push([parseInt(date), parseInt(view_proxy)]);
                        chart_data_context.push([parseInt(date), parseInt(view_context)]);
                        chart_data_sum.push([parseInt(date), parseInt(view)+parseInt(view_proxy)+parseInt(view_context)]);

                    }
                    Highcharts.setOptions({
                        lang: {
                            loading: 'Загрузка...',
                            months: ['Декабрь','Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь'],
                            weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                            shortMonths: ['Дек','Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб'],
                            exportButtonTitle: "Экспорт",
                            printButtonTitle: "Печать",
                            rangeSelectorFrom: "С",
                            rangeSelectorTo: "По",
                            rangeSelectorZoom: "Период",
                            downloadPNG: 'Скачать PNG',
                            downloadJPEG: 'Скачать JPEG',
                            downloadPDF: 'Скачать PDF',
                            downloadSVG: 'Скачать SVG',
                            printChart: 'Напечатать график'
                        }
                    });
                    $('#chart').highcharts({
                        chart: {
                            type: type
                        },
                        title: {
                            text: 'Статистика кликов по дням ' + date_chart
                        },
                        xAxis: {
                            type: 'datetime',
                            dateTimeLabelFormats: { // don't display the dummy year
                                month: '%e. %b',
                                year: '%b'
                            },
                            title: {
                                text: 'Дата'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Количество'
                            },
                            min: 0
                        },
                        tooltip: {
                            headerFormat: '<b>{series.name}</b><br>',
                            pointFormat: '{point.x:%e %b}: {point.y}'
                        },

                        series: [
                        {
                            name: 'Всего',
                            data: chart_data_sum
                        },{
                            name: 'Количество кликов',
                            data: chart_data
                        },{
                            name: 'Количество переходов',
                            data: chart_data_proxy
                        },{
                            name: 'Количество по контекстной рекламе',
                            data: chart_data_context
                        }]
                    });
                });
            }

        }
    });
}

function get_chart_ctr(date){
    date_chart = date;
    $.ajax({
        type: "GET",
        url: "/statistic/get-chart-ctr/?date=" + date,
        success: function(json){

            if (json != 'null'){
                $(function () {
                    var jsonStr = JSON.parse(json);
                    chart_data = new Array;
                    chart_data_all = new Array;
                    for (var i = 0; i < jsonStr.length; i++) {
                        date_arr = jsonStr[i].date_view.split(", ");
                        year = parseInt(date_arr[0]);
                        month = parseInt(date_arr[1]);
                        day = parseInt(date_arr[2]);
                        date = Date.UTC(year,  month, day);
                        view = jsonStr[i].view;
                        view_all = jsonStr[i].view_all;

                        chart_data.push([parseInt(date), parseFloat(view)]);
                        chart_data_all.push([parseInt(date), parseFloat(view_all)]);
                    }

                    Highcharts.setOptions({
                        lang: {
                            loading: 'Загрузка...',
                            months: ['Декабрь','Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь'],
                            weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                            shortMonths: ['Дек','Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб'],
                            exportButtonTitle: "Экспорт",
                            printButtonTitle: "Печать",
                            rangeSelectorFrom: "С",
                            rangeSelectorTo: "По",
                            rangeSelectorZoom: "Период",
                            downloadPNG: 'Скачать PNG',
                            downloadJPEG: 'Скачать JPEG',
                            downloadPDF: 'Скачать PDF',
                            downloadSVG: 'Скачать SVG',
                            printChart: 'Напечатать график'
                        }
                    });

                    $('#chart_ctr').highcharts({
                        chart: {
                            type: 'spline'
                        },
                        title: {
                            text: 'Статистика CTR ' + date_chart
                        },
                        xAxis: {
                            type: 'datetime',
                            dateTimeLabelFormats: { // don't display the dummy year
                                month: '%e. %b',
                                year: '%b'
                            },
                            title: {
                                text: 'Дата'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Значение'
                            },
                            min: 0
                        },
                        tooltip: {
                            headerFormat: '<b>{series.name}</b><br>',
                            pointFormat: '{point.x:%e %b}: {point.y}%'
                        },

                        series: [{
                            name: 'CTR магазина',
                            data: chart_data
                        },{
                            name: 'Средний CTR по всем магазинам',
                            data: chart_data_all
                        },]
                    });
                });
            }

        }
    });
}

function get_chart_sections(date){
    chart_data = new Array();
    chart_data_small = new Array();
    $( ".sections_clicks" ).each(function( index ) {
        tds = $( this ).children();

        name = tds[0].textContent;
        cnt = tds[1].textContent;
        if(parseInt(cnt) < 10){
            chart_data_small.push([name, parseInt(cnt)]);
        } else {
            chart_data.push([name, parseInt(cnt)]);
        }

    });
    if (chart_data.length > 1){
        Highcharts.chart('chart_sections', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Статистика по разделам '+ date
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Количество кликов'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
            },

            series: [{
                name: 'Раздел',
                //colorByPoint: true,
                data: chart_data
            }]
        });
    }
    //console.log(chart_data_small);
    if (chart_data_small.length > 1){
        Highcharts.chart('chart_sections_small', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Статистика по разделам (количество кликов меньше 10) ' + date
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Количество кликов'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
            },

            series: [{
                name: 'Раздел',
                //colorByPoint: true,
                data: chart_data_small
            }]
        });
    }
}

function get_stat(date) {
    $.ajax({
        type: "GET",
        url: "/statistic/get-day-stat/?date=" + date,
        success: function (data) {
            html = data;
            $('#detail').html("<div id='chart_hours'></div>" + html);
            $.ajax({
                type: "GET",
                url: "/statistic/get-chart-hours/?date=" + date,
                success: function(json){

                    if (json != 'null'){
                        $(function () {
                            var jsonStr = JSON.parse(json);
                            chart_data = new Array;
                            for (var i = 0; i < jsonStr.length; i++) {
                                name = jsonStr[i].date_view;
                                view = jsonStr[i].view;
                                chart_data.push([name+":00", parseInt(view)]);
                            }
                            console.log(chart_data);

                            Highcharts.chart('chart_hours', {
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'Статистика по часам ' + date
                                },
                                xAxis: {
                                    type: 'category'
                                },
                                yAxis: {
                                    title: {
                                        text: 'Количество кликов'
                                    }

                                },
                                legend: {
                                    enabled: false
                                },
                                plotOptions: {
                                    series: {
                                        borderWidth: 0,
                                        dataLabels: {
                                            enabled: true,
                                            format: '{point.y}'
                                        }
                                    }
                                },

                                tooltip: {
                                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                                },

                                series: [{
                                    name: 'Раздел',
                                    data: chart_data
                                }]
                            });


                        });
                    }

                }
            });
        }
    });

    $.ajax({
        type: "GET",
        url: "/statistic/get-stat-group/?date=" + date,
        success: function (data) {
            html = data;
            $('#group').html("<div id='section_group_chart'></div>" + html);

            chart_data = new Array();
            $( ".sections_clicks_group" ).each(function( index ) {
                tds = $( this ).children();
                name = tds[0].textContent;
                cnt = tds[1].textContent;
                chart_data.push([name, parseInt(cnt)]);
            });
            if (chart_data.length > 1){
                Highcharts.chart('section_group_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Статистика по разделам ' + date
                    },
                    xAxis: {
                        type: 'category'
                    },
                    yAxis: {
                        title: {
                            text: 'Количество кликов'
                        }

                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}'
                            }
                        }
                    },

                    tooltip: {
                        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
                    },

                    series: [{
                        name: 'Раздел',
                        data: chart_data
                    }]
                });
            }

            $('#myModal').modal('show');
        }
    });
}