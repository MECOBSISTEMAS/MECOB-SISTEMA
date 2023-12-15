<?php

include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$arquivosDB_proc  = new arquivosDB();
$arquivos_proc    = new arquivos();
$arquivos_proc->tp_arq = "RETORNO";

$filtros["filtro_status_arquivo"] = "'CAPTURADO', 'PACIALMENTE'";
$listaArquivos = $arquivosDB_proc->lista_arquivos($arquivos_proc, $conexao_BD_1, $filtros, "",0, "N");

$total_arquivos   = 0;
$total_arquivos_sucesso = 0;
$total_arquivos_erro    = 0;

// syslog(158, 'MECOB - Importacao Processador ' . json_encode($listaArquivos) ); 

foreach($listaArquivos as $arquivo) {

    //busca os boletos do arquivo não processados
    $select = "select * 
               from dados_arquivo_retorno 
               where arquivos_id = " . $arquivo['id'] . " and fl_processado = 'N' ";
    $listaBoletos = $conexao_BD_1->query($select);

    $log = "";

    // syslog(158, 'MECOB - Importacao Processador select ' . json_encode($select) ); 
    // syslog(158, 'MECOB - Importacao Processador Boletos ' . json_encode($listaBoletost) ); 
   
    foreach ($listaBoletos as $boleto) {

        // syslog(158, 'MECOB - Importacao Processador boletos ' . json_encode($boletost) ); 

        //print "<pre>";
        //print_r($boleto);

        $numero_boleto     = substr($boleto["nosso_numero"], 0, -1);
        $id_ocorrencia     = $boleto["id_ocorrencia"];
        $motivo_ocorrencia = $boleto["motivo_ocorrencia"];
        $descricao         = $boleto["descricao"];

        //buscar o boleto no sistema
        $select_boleto_sistema = "select * from contrato_parcelas where id = " . $numero_boleto;

        $conexao_BD_1->query($select_boleto_sistema);
        if ($conexao_BD_1->numeroDeRegistros() > 0) {

            $update_fl_processado = "update dados_arquivo_retorno
                                             set fl_processado = 'S'
                                             where id = " . $boleto["id"];

            if ($id_ocorrencia == '06') { // 06..Liquidação normal (sem motivo)

                $regBoleto = $conexao_BD_1->leRegistro();


                //print "<pre> Boleto Arquivo ";
                //print_r($boleto);
                //print "</pre>";
                //print "<pre> Boleto Sistema ";
                //print_r($regBoleto);
                //print "</pre>";

                if ($regBoleto["vl_pagto"] == 0) {
                    $update_valores = "update contrato_parcelas
                                        set dt_pagto = '" . $boleto["dt_banco"] . "',
                                            vl_pagto = " . $boleto["vl_pago"] . ",
                                            vl_juros_pagto = " . $boleto["vl_juros"] . ",
                                            dt_credito = '" . $boleto["dt_credito"] . "',
                                            arquivos_id_retorno = '" . $boleto["arquivos_id"] . "',
                                            nu_linha_retorno = '" . $boleto["nu_linha"] . "',
                                            dt_processo_pagto = '" . date('Y-m-d G:i:s') . "'
                                        where id = " . $numero_boleto;
                    $conexao_BD_1->query_atualizacao($update_valores);

                    $conexao_BD_1->query_atualizacao($update_fl_processado);
                    $log .= "Linha ".$boleto["nu_linha"].": Boleto ".$numero_boleto." liquidado.<br>";

                } else {
                    $log .= "Linha ".$boleto["nu_linha"].": Este boleto ".$numero_boleto." já está pago.<br>";
                }
            }
            elseif (($id_ocorrencia == '02')&&($motivo_ocorrencia == "0000000000")) {//02..Entrada Confirmada (verificar motivo na posição 319 a 328 )
                $conexao_BD_1->query_atualizacao($update_fl_processado);
                $log .= "Linha ".$boleto["nu_linha"].": Boleto ".$numero_boleto." Registrado.<br>";
            }
            else {
                $log .= "Linha ".$boleto["nu_linha"].": Ocorrência ainda não tratada". $id_ocorrencia ." ".$descricao."<br>";
                
            }
        }
        else {
            $log .= "Linha ".$boleto["nu_linha"].": Boleto não registrado no sistema " . $numero_boleto ."<br>";
        }
    }
    $arquivos_proc->log               = $log;
    $arquivos_proc->status            = "PROCESSADO";
    $arquivos_proc->dt_processamento  = date('Y-m-d G:i:s');
    $arquivos_proc->id                = $arquivo['id'];
    $conexao_BD_1->update($arquivos_proc);
}