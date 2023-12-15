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
include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.db.php");

    $arquivos    = new arquivos();

    if(isset($argv[1])) {
        $data = $argv[1];
    } else {
        $data = date('Y-m-d');
    }


    $select = "
        SELECT id, nm_arq
        FROM arquivos 
        WHERE 1=1
        and date(dt_arq) = '".$data."'
        and tp_arq = 'REMESSA'
        and nm_arq like 'CB_UNICRED%'
        and isnull(dt_envio_banco)
    ";


    $result = $conexao_BD_1->query($select);	

    $copy_total = 0;

    // Pega uma única data e hora de envio para todos os arquivos
    $arquivos->dt_envio_banco 		 = date('Y-m-d H:i:s');	
    $arquivos->pessoas_id_envio      = '4';	        

    foreach ($result as $row) {
        exec('/bin/cp -f '.$raiz_remessa.$row["nm_arq"] . ' ' . $skyunix_OUTBOX. ' 2>/dev/null', $retLine, $retValue);
        
        if($retValue) {
            $erro_copy = true;
        } else {
            $copy_total++;

            $arquivos->id = $row["id"]; 

            if (!$conexao_BD_1->update($arquivos)){
                $erro_db = true;
                $erro_db_file[$row['id']] = $row['id'] . " - " . $row['nm_arq'];
            } 
        }
    }

    if($erro_copy) {
        echo "Ocorreu um erro durante a cópia de um ou mais arquivo(s).\n";
    } 
    echo "Foram copiado(s) " . $copy_total. " arquivo(s) de um total de ". count($result). ".\n";

    if($erro_db) {
        echo "\nOcorreu um erro durante a atualização da base de dados.";
        if(count($erro_db_file) == 1) {
            echo "Verifique se o arquivo abaixo foi enviado:\n";
        } else {
            echo "Verifique se os arquivos abaixo foram enviados:\n";
        }
        foreach($erro_db_file as $item) {
            echo $item . "\n";
        }
    }
?>