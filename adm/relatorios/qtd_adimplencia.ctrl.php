<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$datai = isset($_POST['filtro_data']) ? $_POST['filtro_data'] : null;
$dataf = isset($_POST['filtro_data_fim']) ? $_POST['filtro_data_fim'] : null;

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Ocorrências dias ' . json_encode($tipo_sem_oc));

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
header( "Content-Disposition: attachment; filename=relatorio_qtd_adimplencia_" . date( 'Y-m-d_His' ) . ".csv" );
header( "Pragma: no-cache" );


$retorno = $relatoriosDB->lista_qtd_adimplencia($conexao_BD_1, $datai, $dataf);

$busca = array('/,/', '/;/');
$troca = array('.', ' ');
$dt_atual = new datetime(date('Y-m-d H:i:s'));

$t_qtd = 0;
$t_valor = 0;

// Header do arquivo XLS
echo "Data;Nome;Qtde;Valor" . PHP_EOL;

foreach ($retorno as $line => $item) {
    // echo "<br>";
    echo ConverteData($item['data']) .";";
    echo $item['nome'] .";";
    echo number_format($item['total'], 0, ',', '.') .";";
    echo " " . number_format($item['valor'], 2, ',', '.') .";";
    echo PHP_EOL;
    $t_qtd += $item['total'];
    $t_valor += $item['valor'];
}

// Imprime totais
echo ";Total;";
echo number_format($t_qtd, 0, ',', '.') .";";
echo " " . number_format($t_valor, 2, ',', '.') .";";
// echo json_encode($retorno);


exit();
?>