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
header( "Content-Disposition: attachment; filename=baixas_boletos_" . $data . "_" . date( 'Y-m-d_His' ) . ".xls" );
header( "Pragma: no-cache" );

// include($raiz."/partial/html_ini.php");

$class_table="  border:solid 1px #e4e4e4;
				font-size:14px; 
				font-weight:100; 
				color:#000; 
				background-color:#fff ;
				padding:10px 10px 10px 10px !important;  
                ";

$retorno = $relatoriosDB->lista_rpt_baixas($conexao_BD_1, $data);

$dt_atual = new datetime(date('Y-m-d H:i:s'));

$t_qtd = 0;
$t_vl_parcela = 0;
$t_vl_pagto   = 0;
$swap = 'UNICRED';

?>
<head>
<meta charset="utf-8">
</head>

<table id="listagem_contratos"  class="table  table-bordered"  style=" <?php echo $class_table;?>" >
    <thead style=" <?php echo $class_table;?>">
    <tr style=" <?php echo $class_table;?>">
        <th id="th_id" class="hidden-xs hidden-sm pointer" >Id </th>

        <th>Vendedor</th>
        <th>Comprador</th>
        <th>Número da Parcela</th>
        <th>Valor da Parcela</th>
        <th>Valor do Pgto</th>
        <th>Vencimento</th>
        <th>Crédito</th>
        <th>Processamento</th>
        <th>Banco</th>
<!--        <th>Parcelas</th> -->
        <!-- <th>Total de Parcelas</th>
        <th>Parcelas quitadas</th>
        <th>1ª Parcela</th> -->
        <th>Evento</th>
        <th>Produto</th>

    </tr>
    </thead>
    <tbody id="tbody_contratos" style=" <?php echo $class_table;?>">

<?php

foreach ($retorno as $line => $item) {
    // echo "<br>";
    if($swap != $item['banco']) {
        // Imprime totais
        echo '<tr>';
        echo "<td></td><td></td><td></td><td>Total</td>";
        echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_parcela, 2, ',', '.') . '</td>';
        echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_pagto, 2, ',', '.') . '</td>';
        echo '</tr>';

        $t_vl_parcela = 0;
        $t_vl_pagto   = 0;

        $swap = $item['banco'];          

    }

    echo '<tr style="'.$class_table.'">';
    echo '<td style="'.$class_table.'">' . $item['id_contrato'] . '</td>';
    echo '<td style="'.$class_table.'">' . $item['vendedor'] . '</td>';
    echo '<td style="'.$class_table.'">' . $item['comprador'] . '</td>';
    echo '<td style="'.$class_table.'">' . $item['nu_parcela'] . '</td>';
    echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($item['vl_parcela'], 2, ',', '.') . '</td>';
    echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($item['vl_pagto'], 2, ',', '.') . '</td>';
    echo '<td style="'.$class_table.'">' . ConverteData($item['dt_vencimento']) . '</td>';
    echo '<td style="'.$class_table.'">' . ConverteData($item['dt_credito']) . '</td>';
    echo '<td style="width:800px;" style="'.$class_table.'">' . ConverteData($item['dt_processamento']) . '</td>';
    echo '<td style="'.$class_table.'">' . $item['banco'] . '</td>';
//    if ($item['tt_parcelas'] > 0) {
//        echo '<td style="'.$class_table.'">(' . $item['tt_quitadas'] .'/'. $item['tt_parcelas'] . ')</td>';
//    } else {
//        echo '<td style="'.$class_table.'">(1/1)</td>';
//    }
    // echo '<td style="'.$class_table.'">' . $item['tt_parcelas'] . '</td>';
    // echo '<td style="'.$class_table.'">' . $item['tt_quitadas'] . '</td>';
    // echo '<td style="'.$class_table.'">' . $item['parcela_primeiro_pagto'] . '</td>';
    echo '<td style="'.$class_table.'">' . $item['evento'] . '</td>';
    echo '<td style="'.$class_table.'">' . $item['produto'] . '</td>';

    $t_qtd++;
    $t_vl_parcela += $item['vl_parcela'];
    $t_vl_pagto   += $item['vl_pagto'];

    echo '</tr>';
}

// Imprime totais
echo '<tr>';
echo "<td></td><td></td><td></td><td>Total</td>";
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_parcela, 2, ',', '.') . '</td>';
echo '<td align="right" style="'.$class_table.'">R$ ' . number_format($t_vl_pagto, 2, ',', '.') . '</td>';
echo '</tr>';


echo '</tbody>';

echo '</table>';

exit();
?>
