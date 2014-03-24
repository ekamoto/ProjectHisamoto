$(function () {
    if(data_1_grafico_4.length>0) {
        var chart;
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico4',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.point.name +'</b>: '+ this.point.y +' conta(s)';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return this.point.name;
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: data_1_grafico_4
            }]
        });
    }
});