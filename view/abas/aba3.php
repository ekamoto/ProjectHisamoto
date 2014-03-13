<?php
$cont_desp_mes = array();
$cont_total_valor_desp_mes = array();
$cont_total_valor_desp_atras_mes = array();
for ($i = 1; $i <= 12; $i++) {
    if ($i < 10) {
        $i = '0' . $i;
    }
    $cont_desp_mes[$i]['parcelado'] = getQuantidadeConta(date('Y'), $i, 1);
    $cont_desp_mes[$i]['fixo'] = getQuantidadeConta(date('Y'), $i, 2);
    $cont_desp_mes[$i]['normal'] = getQuantidadeConta(date('Y'), $i, 3);
    $cont_total_valor_desp_mes[$i]['valor'] = getValorTotalMes(date('Y'), $i);
    $cont_total_valor_desp_atras_mes[$i]['valor'] = getValorTotalMesContasAtrasadas(date('Y'), $i);
}
?>
<script type="text/javascript">
    var data_1_grafico_1 = [<?= $cont_desp_mes['01']['fixo']; ?>, <?= $cont_desp_mes['02']['fixo']; ?>, <?= $cont_desp_mes['03']['fixo']; ?>, <?= $cont_desp_mes['04']['fixo']; ?>, <?= $cont_desp_mes['05']['fixo']; ?>, <?= $cont_desp_mes['06']['fixo']; ?>, <?= $cont_desp_mes['07']['fixo']; ?>, <?= $cont_desp_mes['08']['fixo']; ?>, <?= $cont_desp_mes['09']['fixo']; ?>, <?= $cont_desp_mes['10']['fixo']; ?>, <?= $cont_desp_mes['11']['fixo']; ?>, <?= $cont_desp_mes['12']['fixo']; ?>];
    var data_2_grafico_1 = [<?= $cont_desp_mes['01']['parcelado']; ?>, <?= $cont_desp_mes['02']['parcelado']; ?>, <?= $cont_desp_mes['03']['parcelado']; ?>, <?= $cont_desp_mes['04']['parcelado']; ?>, <?= $cont_desp_mes['05']['parcelado']; ?>, <?= $cont_desp_mes['06']['parcelado']; ?>, <?= $cont_desp_mes['07']['parcelado']; ?>, <?= $cont_desp_mes['08']['parcelado']; ?>, <?= $cont_desp_mes['09']['parcelado']; ?>, <?= $cont_desp_mes['10']['parcelado']; ?>, <?= $cont_desp_mes['11']['parcelado']; ?>, <?= $cont_desp_mes['12']['parcelado']; ?>];
    var data_3_grafico_1 = [<?= $cont_desp_mes['01']['normal']; ?>, <?= $cont_desp_mes['02']['normal']; ?>, <?= $cont_desp_mes['03']['normal']; ?>, <?= $cont_desp_mes['04']['normal']; ?>, <?= $cont_desp_mes['05']['normal']; ?>, <?= $cont_desp_mes['06']['normal']; ?>, <?= $cont_desp_mes['07']['normal']; ?>, <?= $cont_desp_mes['08']['normal']; ?>, <?= $cont_desp_mes['09']['normal']; ?>, <?= $cont_desp_mes['10']['normal']; ?>, <?= $cont_desp_mes['11']['normal']; ?>, <?= $cont_desp_mes['12']['normal']; ?>];
    var data_1_grafico_2 = [<?= $cont_total_valor_desp_mes['01']['valor']; ?>, <?= $cont_total_valor_desp_mes['02']['valor']; ?>, <?= $cont_total_valor_desp_mes['03']['valor']; ?>, <?= $cont_total_valor_desp_mes['04']['valor']; ?>, <?= $cont_total_valor_desp_mes['05']['valor']; ?>, <?= $cont_total_valor_desp_mes['06']['valor']; ?>, <?= $cont_total_valor_desp_mes['07']['valor']; ?>, <?= $cont_total_valor_desp_mes['08']['valor']; ?>, <?= $cont_total_valor_desp_mes['09']['valor']; ?>, <?= $cont_total_valor_desp_mes['10']['valor']; ?>, <?= $cont_total_valor_desp_mes['11']['valor']; ?>, <?= $cont_total_valor_desp_mes['12']['valor']; ?>];
    var data_1_grafico_3 = [<?= $cont_total_valor_desp_atras_mes['01']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['02']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['03']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['04']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['05']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['06']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['07']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['08']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['09']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['10']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['11']['valor']; ?>, <?= $cont_total_valor_desp_atras_mes['12']['valor']; ?>];
</script>
<div id="grafico" style="min-width: 80%; height: 400px; margin: 0 auto"></div><br/>
<div id="grafico2" style="min-width: 80%; height: 400px; margin: 0 auto"></div><br/>
<div id="grafico3" style="min-width: 80%; height: 400px; margin: 0 auto"></div>