<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$datai  = isset($_POST['filtro_data']) ? $_POST['filtro_data'] : null;
$dataf  = isset($_POST['filtro_data_fim']) ? $_POST['filtro_data_fim'] : null;
$filtro_status = isset($_POST['filtro_status']) ? $_POST['filtro_status'] : null;

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
header( "Content-Disposition: attachment; filename=relatorio_saldo_cliente_" . date( 'Y-m-d_His' ) . ".csv" );
header( "Pragma: no-cache" );


$retorno = $relatoriosDB->lista_saldo_cliente($conexao_BD_1, $datai, $dataf, $filtro_status);

$busca = array('/,/', '/;/');
$troca = array('.', ' ');
$dt_atual = new datetime(date('Y-m-d H:i:s'));

$t_receber = 0;
$t_recebido = 0;
$t_total = 0;
$t_pagar   = 0;
$t_pagar_avulso   = 0;

// Header do arquivo XLS
echo "Data do relatório;" . date( 'd/m/Y H:i:s' ) . PHP_EOL;
echo "Status selecionado(s);" . implode(", ", $filtro_status) . PHP_EOL;
echo "ID;Nome;À Receber;Recebido;Total;A Pagar;A Pagar avulso" . PHP_EOL;

foreach ($retorno as $line => $item) {
    // echo "<br>";
    // echo ConverteData($item['data']) .";";
    echo $item['cliente_id'] . ";";
    echo $item['nome'] . ";";
    echo number_format($item['receber'], 2, ',', '.') . ";";
    echo number_format($item['recebido'], 2, ',', '.') . ";";
    echo number_format($item['receber_total'], 2, ',', '.') . ";";
    echo number_format($item['pagar'], 2, ',', '.') . ";";
    echo number_format($item['pagar_avulso'], 2, ',', '.') . ";";
    echo PHP_EOL;
    $t_receber += $item['receber'];
    $t_recebido += $item['recebido'];
    $t_total += $item['receber_total'];
    $t_pagar += $item['pagar'];
    $t_pagar_avulso += $item['pagar_avulso'];
}

// Imprime totais
echo ";Total;";
echo number_format($t_receber, 2, ',', '.') .";";
echo number_format($t_recebido, 2, ',', '.') .";";
echo number_format($t_total, 2, ',', '.') .";";
echo number_format($t_pagar, 2, ',', '.') .";";
echo number_format($t_pagar_avulso, 2, ',', '.') .";";
// echo json_encode($retorno);


exit();
?>