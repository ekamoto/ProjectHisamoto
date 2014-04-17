$(function() {
    var placeholder = $("#placeholder");
    placeholder.unbind();
    data = data_1_grafico_4;
    $.plot('#placeholder', data, {
        series: {
            pie: {
                show: true
            }
        },
        legend: {
            show: false
        }
    });
    function labelFormatter(label, series) {
        return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }
    function setCode(lines) {
        $("#code").text(lines.join("\n"));
    }
});