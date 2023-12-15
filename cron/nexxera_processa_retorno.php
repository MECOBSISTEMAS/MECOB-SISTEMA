<?php
include_once("cron_config.php");

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
$cron = true;

$raiz_remessa = $raiz.'/boletos/remessa/';
$skyunix        = '/home/skyunix/';
$skyunix_INBOX  = $skyunix.'INBOX/';
$skyunix_OUTBOX = $skyunix.'OUTBOX/';

$erro_copy = false;
$erro_db   = false;
$erro_db_file = array();

include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");

// Processa todos os arquivos na pasta ./boletos/retorno/a_importar 
// depois move todos para ./boletos/retorno/importados
include(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/importador/importador_retorno_boleto.php");

    // Verifica se o processamento ocorreu OK
    $data = date('Y-m-d');
    $erro_db_ret = false;
    $erro_db_file = array();

    $select = "
        SELECT *
        FROM arquivos 
        WHERE 1=1
        and date(dt_arq) = '".$data."'
        and tp_arq = 'RETORNO'
    ";

    $result = $conexao_BD_1->query($select);	
    foreach ($result as $row) {
        if(!$row['status'] == 'PROCESSADO') {
            $erro_db_ret = true;
            $erro_db_file[$row["id"]] = $row["id"] . " - " . $row["nm_arq"];
        }
    }
    
    echo "Foram carregados " . count($result) . " arquivos de retorno.\n";

    if($erro_db_ret){
        if(count($erro_db_file) == 1 ) {
            echo "Ocorreu um ou erro no processamento do arquivo de Retorno.\n";
            echo "Verifique o arquivo abaixo:\n";
        } elseif (count($erro_db_file) > 1) {
            echo "Ocorreram erros no processamento dos arquivos de Retorno.\n";
            echo "Verifique os arquivos abaixo:\n";
        }
        foreach($erro_db_file as $item) {
            echo $item . "\n";
        }
    }
?>