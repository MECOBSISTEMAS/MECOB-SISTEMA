<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$dias_sem_oc = isset($_POST['dias_sem_oc']) ? $_POST['dias_sem_oc'] : 0;
$tipo_sem_oc = isset($_POST['tipo_sem_oc']) ? $_POST['tipo_sem_oc'] : ['adimplencia'];
$status_sem_oc = isset($_POST['status_sem_oc']) ? $_POST['status_sem_oc'] : ['confirmado'];

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
header( "Content-Disposition: attachment; filename=relatorio_sem_ocorrencias_" . date( 'Y-m-d_His' ) . ".csv" );
header( "Pragma: no-cache" );


$retorno = $relatoriosDB->lista_sem_ocorrencias($conexao_BD_1, $dias_sem_oc, $tipo_sem_oc, $status_sem_oc);

$busca = array('/,/', '/;/');
$troca = array('.', ' ');
$dt_atual = new datetime(date('Y-m-d H:i:s'));

// Header do arquivo XLS
echo "ID;Descricao;Tipo do contrato;Status do contrato;Data Vencimento;Dias em atraso;Data ocorrencia;Dias sem ocorrência;Nome" . PHP_EOL;

foreach ($retorno as $line => $item) {
    // Calcula a quantidade de dias sem ocorrencia
    $dt_oc   = new datetime($item['data_ocorrencia']);
    $oc_dias = $dt_oc->diff($dt_atual); 

    // Calcula a quantidade de dias emm atraso
    $dt_atraso   = new datetime($item['dt_vencimento']);
    $atraso_dias = $dt_atraso->diff($dt_atual); 

    echo $item['id'] .";";
    echo preg_replace($busca, $troca, ltrim($item['descricao'], "\x00..\x1F")) .";";
    echo $item['tp_contrato'] .";";
    echo $item['status'] .";";
    echo ConverteData($item['dt_vencimento']) .";";
    echo $atraso_dias->format('%r%a') . ";";
    echo ConverteData($item['data_ocorrencia']) .";";
    echo $oc_dias->format('%r%a') . ";";
    echo $item['nome'] .";";
    echo PHP_EOL;
}

// echo json_encode($retorno);


// exit();
?>