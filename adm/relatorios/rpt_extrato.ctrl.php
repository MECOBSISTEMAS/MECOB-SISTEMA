<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$data = isset($_POST['filtro_data']) ? $_POST['filtro_data'] : null;

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Ocorrências dias ' . json_encode($tipo_sem_oc));

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";

include_once(getenv('CAMINHO_RAIZ')."/repositories/relatorios/relatorios.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

$relatoriosDB  = new relatoriosDB();

// header( "'Content-Type: text/html; charset=utf-8" );

header( "Content-type: application/vnd.ms-excel " );

// Força o download do arquivo
header( "Content-type: application/force-download" );
// Seta o nome do arquivo
header( "Content-Disposition: attachment; filename=extrato_retorno_" . $data . "_" . date( 'Y-m-d_His' ) . ".xls" );
header( "Pragma: no-cache" );

// include($raiz."/partial/html_ini.php");

$class_table="  border:solid 1px #e4e4e4;
				font-size:14px; 
				font-weight:100; 
				color:#000; 
				background-color:#fff ;
				padding:10px 10px 10px 10px !important;  
                ";

$retorno = $relatoriosDB->lista_rpt_extrato($conexao_BD_1, $data);

$dt_atual = new datetime(date('Y-m-d H:i:s'));

$t_qtd = 0;
$t_vl_boleto = 0;
$t_vl_juros  = 0;
$t_vl_pago   = 0;

$tt_vl_boleto = 0;
$tt_vl_juros  = 0;
$tt_vl_pago = 0;

$swap = 'S';

?>
<head>
<meta charset="utf-8">
</head>

<table id="listagem_contratos"  class="table  table-bordered"  style=" <?php echo $class_table;?>" >
    <thead style=" <?php echo $class_table;?>">
    <tr style=" <?php echo $class_table;?>">
        <th id="th_id" class="hidden-xs hidden-sm pointer" >Id Parcela</th>
        <th>Nosso Número</th>
        <th>Vencimento</th>
        <th>Crédito</th>
        <th>Valor do Boleto</th>
        <th>Valor dos Juros</th>
        <th>Valor do Pago</th>
        <th>Número da Parcela</th>
        <th>ID Contrato</th>
        <th>Processado</th>

    </tr>
    </thead>
    <tbody id="tbody_contratos" style=" <?php echo $class_table;?>">

<?php

foreach ($retorno as $line => $item) {
    // echo "<br>";
    if($swap != $item['fl_processado'] && $t_vl_pago > 0) {
        // Imprime totais
        echo '<tr>';
        echo "<td></td><td></td><td></td><td>Total</td>";
        echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_boleto, 2, ',', '.') . '</td>';
        echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_juros, 2, ',', '.') . '</td>';
        echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_pago, 2, ',', '.') . '</td>';
        echo '</tr>';

        echo '<tr></tr>';

        $t_vl_boleto = 0;
        $t_vl_juros  = 0;
        $t_vl_pago   = 0;

        $swap = $item['fl_processado'];          

    }

    echo '<tr style="'.$class_table.'">';
    echo '<td style="'.$class_table.'">' . $item['id_parcela'] . '</td>';
    echo '<td style="'.$class_table.'">"' . $item['nosso_numero'] . '"</td>';
    echo '<td style="'.$class_table.'">' . ConverteData($item['dt_vencimento']) . '</td>';
    echo '<td style="'.$class_table.'">' . ConverteData($item['dt_credito']) . '</td>';
    echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($item['vl_boleto'], 2, ',', '.') . '</td>';
    echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($item['vl_juros'], 2, ',', '.') . '</td>';
    echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($item['vl_pago'], 2, ',', '.') . '</td>';
    echo '<td style="'.$class_table.'">' . $item['nu_parcela'] . '</td>';
    echo '<td style="'.$class_table.'">' . $item['contratos_id'] . '</td>';
    echo '<td style="'.$class_table.'">' . $item['fl_processado'] . '</td>';

    $t_qtd++;
    $t_vl_boleto += $item['vl_boleto'];
    $t_vl_juros  += $item['vl_juros'];
    $t_vl_pago   += $item['vl_pago'];

    $tt_vl_boleto += $item['vl_boleto'];
    $tt_vl_juros  += $item['vl_juros'];
    $tt_vl_pago   += $item['vl_pago'];

    echo '</tr>';
}

// Imprime totais
echo '<tr>';
echo "<td></td><td></td><td></td><td>Total</td>";
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_boleto, 2, ',', '.') . '</td>';
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_juros, 2, ',', '.') . '</td>';
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_pago, 2, ',', '.') . '</td>';
echo '</tr>';

// Imprime totais Geral
echo '<tr></tr>'; // Pula linha

echo '<tr>';
echo "<td></td><td></td><td></td><td>Total Geral</td>";
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($tt_vl_boleto, 2, ',', '.') . '</td>';
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($tt_vl_juros, 2, ',', '.') . '</td>';
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($tt_vl_pago, 2, ',', '.') . '</td>';
echo '</tr>';

echo '</tbody>';

echo '</table>';

exit();
?>
