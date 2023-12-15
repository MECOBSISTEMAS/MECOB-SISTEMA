<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$datai = isset($_POST['filtro_data']) ? $_POST['filtro_data'] : null;

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Boletos Adimplência ' . json_encode($datai));

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";

include_once(getenv('CAMINHO_RAIZ')."/repositories/relatorios/relatorios.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

$relatoriosDB  = new relatoriosDB();


header( "Content-type: application/vnd.ms-excel" );
// Força o download do arquivo
header( "Content-type: application/force-download" );
// Seta o nome do arquivo
header( "Content-Disposition: attachment; filename=relatorio_boletos_adimplencia_" . date( 'Y-m-d_His' ) . ".csv" );
header( "Pragma: no-cache" );


$retorno = $relatoriosDB->lista_boletos_adimplencia($conexao_BD_1, $datai);
// echo json_encode($retorno);


// $dt_atual = new datetime(date('Y-m-d H:i:s'));

$total = 0;
$pago  = 0;
$n_pago = 0;
$vl_total = 0;
$vl_pago  = 0;
$vl_n_pago = 0;


// Header do arquivo XLS
// echo "Data do relatório: ".$dt_atual. PHP_EOL;
echo "Data do relatório: " . date( 'd/m/Y H:i:s' ) . PHP_EOL;
echo "Vencimento;Total;Valor Total;Pago;Valor Pago;Não Pago;Valor não pago;% Inadimplência;% Valor Inadimplência" . PHP_EOL;

foreach ($retorno as $line => $item) {
    // echo "<br>";
    // echo ConverteData($item['data']) .";";
    echo ConverteData($item['vencimento']) . ";";
    echo number_format($item['total'], 0, ',', '.') . ";";
    echo number_format($item['vl_total'], 2, ',', '.') . ";";
    echo number_format($item['pago'], 0, ',', '.') . ";";
    echo number_format($item['vl_pago'], 2, ',', '.') . ";";
    echo number_format($item['n_pago'], 0, ',', '.') . ";";
    echo number_format($item['vl_n_pago'], 2, ',', '.') . ";";
    echo number_format($item['inadimplencia'], 2, ',', '.') . ";";
    echo number_format($item['vl_n_pago'] / $item['vl_total'] * 100, 2, ',', '.') . ";";
    echo PHP_EOL;
    $total += $item['total'];
    $pago += $item['pago'];
    $n_pago += $item['n_pago'];
    $vl_total += $item['vl_total'];
    $vl_pago += $item['vl_pago'];
    $vl_n_pago += $item['vl_n_pago'];

}

// Imprime totais
echo PHP_EOL;
echo "Total geral;";
echo number_format($total, 0, ',', '.') .";";
echo number_format($vl_total, 2, ',', '.') .";";
echo number_format($pago, 0, ',', '.') .";";
echo number_format($vl_pago, 2, ',', '.') .";";
echo number_format($n_pago, 0, ',', '.') .";";
echo number_format($vl_n_pago, 2, ',', '.') .";";
echo number_format($n_pago / $total * 100, 2, ',', '.') .";";
echo number_format($vl_n_pago / $vl_total * 100, 2, ',', '.') .";";
// echo json_encode($retorno);


exit();
?>