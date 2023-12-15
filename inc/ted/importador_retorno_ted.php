<?php

date_default_timezone_set('America/Sao_Paulo');

include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.db.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.class.php");


$arquivos_imp = new arquivos();
$arquivos_log = new arquivos();
$arquivos_com = new arquivos();
$ted_imp_DB   = new tedsDB();
$ted_imp      = new teds();


$dir_arquivos = getenv('CAMINHO_RAIZ').'/teds/retorno/a_importar/';
$dir_erro     = getenv('CAMINHO_RAIZ').'/teds/retorno/erro/';
$dir_sucesso  = getenv('CAMINHO_RAIZ').'/teds/retorno/importados/';

$total_arquivos   = 0;
$total_arquivos_sucesso = 0;
$total_arquivos_erro    = 0;

$listaArquivos = array_diff(scandir($dir_arquivos), array('..', '.'));
foreach($listaArquivos as $arquivo){

    $arquivo_com_erro = false;
    $linhas = file ($dir_arquivos.$arquivo);

    $arquivos_imp->nm_arq = $arquivo;
    $arquivos_imp->dt_arq = date('Y-m-d G:i:s');
    $arquivos_imp->origem = "TED";

    $arquivos_com->nm_arq = $arquivo;
    $arquivos_verifica = $conexao_BD_1->select($arquivos_com);

    //print "<pre>";
    //print_r($arquivos_verifica);
    //print "</pre>";

    $total_arquivos++;
    $linha_arquivo = 0;
    foreach ($linhas as $nu_linha => $linha) {
    	//echo "Linha #<b>{$nu_linha}</b> : " . $linha . "<br>\n";

        $linha_arquivo = $nu_linha+1;
    	if ($nu_linha == 0){

    	    //echo "<br><br>Arquivo: ".
            $literal_retorno = trim(substr($linha, 171, 20));
    	    //echo "<br> Data geração arquivo:".
            $dt_geracao = trim(substr($linha, 147, 4))."-".trim(substr($linha, 145, 2))."-".trim(substr($linha, 143, 2));

            $arquivos_imp->tp_arq = $literal_retorno;

            if ((($literal_retorno != 'PREVIA')&&($literal_retorno != 'PROCESSAMEN')&&($literal_retorno != 'CONSOLIDADO'))||(isset($arquivos_verifica[0]["id"]) != "")){
    	        $total_arquivos_erro++;
    	        $arquivo_com_erro = true;
    	        if ($arquivos_verifica[0]["id"] != ""){
                    $arquivos_imp->log = " Arquivo já importado.";
                }
                else {
                    $arquivos_imp->log = " Tentando importar um arquivo de " . $literal_retorno . ". Todos os arquivos para este importador devem ser de PREVIA, PROCESSAMEN ou CONSOLIDADO.";
                }

                //insere o arquivo com log de erro
                $arquivos_imp->status = "CORROMPIDO";
                $conexao_BD_1->insert($arquivos_imp);

                //move o arquivo para erro
                rename( $dir_arquivos.$arquivo, $dir_erro.$arquivo );

    	        break;
            }
            else{
                $total_arquivos_sucesso++;
                $arquivos_imp->status = "CAPTURADO";
                $arquivos_imp->id = $conexao_BD_1->insert($arquivos_imp);

                //move o arquivo para importados
                rename( $dir_arquivos.$arquivo, $dir_sucesso.$arquivo );
            }
        }


        //echo "<br>Segmento:".
        $tipo_segmento = trim(substr($linha, 13, 1));

    	if ($tipo_segmento == "A"){

    	    //echo "<br> Número TED:".
            $nosso_numero  = trim(substr($linha, 73, 20));
            //echo "<br> Data pagamento TED:".
            $data_pagto  = trim(substr($linha, 97, 4))."-".trim(substr($linha, 95, 2))."-".trim(substr($linha, 93, 2));
            //echo "<br> Valor pagamento TED:".
            $valor_pagto  = (int)trim(substr($linha, 119, 13)).".".trim(substr($linha, 132, 2));
            //echo "<br> Data efetivação pagamento TED:".
            $data_efetivacao_pagto  = trim(substr($linha, 158, 4))."-".trim(substr($linha, 156, 2))."-".trim(substr($linha, 154, 2));
            //echo "<br> Valor efetivação pagamento TED:".
            $valor_efetivacao_pagto  = (int)trim(substr($linha, 162, 13)).".".trim(substr($linha, 175, 2));
            $ocorrencias  = trim(substr($linha, 230, 10));
            //echo "<br> Ocorrências TED:". ocorrencia($ocorrencias);
            //buscar dados TED
            $ted_imp->id = $nosso_numero;
            $dados_ted = $conexao_BD_1->select($ted_imp);
            //print "<pre>";
            //print_r($dados_ted);
            //print "</pre>";


            if ($ocorrencias == "BD"){
                $ted_imp_DB->atualiza_ted_arquivo($conexao_BD_1, $ted_imp->id, $literal_retorno, $arquivos_imp->id, $linha_arquivo);
                $arquivos_log->log               = "Arquivo Processado com sucesso.";
                $arquivos_log->status            = "PROCESSADO";
                $arquivos_log->dt_processamento  = date('Y-m-d G:i:s');
                $arquivos_log->id                = $arquivos_imp->id;
                $conexao_BD_1->update($arquivos_log);
            }
            elseif ($ocorrencias == "00"){
                $ted_imp_DB->atualiza_ted_arquivo($conexao_BD_1, $ted_imp->id, $literal_retorno, $arquivos_imp->id, $linha_arquivo);
                $arquivos_log->log               = "Crédito para TED ".$ted_imp->id." Efetivado.";
                $arquivos_log->status            = "PROCESSADO";
                $arquivos_log->dt_processamento  = date('Y-m-d G:i:s');
                $arquivos_log->id                = $arquivos_imp->id;
                $conexao_BD_1->update($arquivos_log);
            }
            else if ($ocorrencias == "AP"){
                 $ted_imp_DB->atualiza_ted_arquivo($conexao_BD_1, $ted_imp->id, $literal_retorno, $arquivos_imp->id, $linha_arquivo, 4);
                $arquivos_log->log               = "Data Lançamento Inválido.";
                $arquivos_log->status            = "CORROMPIDO";
                $arquivos_log->dt_processamento  = date('Y-m-d G:i:s');
                $arquivos_log->id                = $arquivos_imp->id;
                $conexao_BD_1->update($arquivos_log);

                //move o arquivo para erro
                rename( $dir_sucesso.$arquivo, $dir_erro.$arquivo );
                break;
            }
            else{

                $arquivos_log->log               = "Ocorrência não tratada (".$ocorrencias."). Entrar em contato com desenvolvimento.";
                $arquivos_log->status            = "CORROMPIDO";
                $arquivos_log->dt_processamento  = date('Y-m-d G:i:s');
                $arquivos_log->id                = $arquivos_imp->id;
                $conexao_BD_1->update($arquivos_log);

                //move o arquivo para erro
                rename( $dir_sucesso.$arquivo, $dir_erro.$arquivo );
                break;
            }
        }

    }
}

//include_once(getenv('CAMINHO_RAIZ')."/inc/ted/processador_retorno_boleto.php");

//echo "<br /> >>>> Sucesso ".$total_arquivos_sucesso;
//echo "<br /> >>>> Erro ".$total_arquivos_erro;
//echo "<br /> >>>> Total ".$total_arquivos;


function ocorrencia($id_ocorrencia){

    switch ($id_ocorrencia){

        case 'AP':
            return "AP = Data Lançamento Inválido";
            break;
        case 'BD':
            return "BD = Inclusão Efetuada com Sucesso";
            break;
        case '00':
            return "00 = Crédito ou Débito Efetivado!";
            break;

    }
}