$(function() {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'grafico',
                type: 'line',
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Contas Do Ano',
                x: -20 //center
            },
            subtitle: {
                text: sistema.getAno(),
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Qtd. Contas'
                },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + '</b><br/>' +
                            this.x + ': ' + this.y + ' Conta(s) ';
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{
                    name: 'Fixa',
                    data: data_1_grafico_1
                }, {
                    name: 'Parcelada',
                    data: data_2_grafico_1
                }, {
                    name: 'Normal',
                    data: data_3_grafico_1
                }]
        });
    });
});