<?php

date_default_timezone_set('America/Sao_Paulo');

include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$arquivos_imp    = new arquivos();

$dir_arquivos = getenv('CAMINHO_RAIZ').'/boletos/retorno/a_importar/';
$dir_erro     = getenv('CAMINHO_RAIZ').'/boletos/retorno/erro/';
$dir_sucesso  = getenv('CAMINHO_RAIZ').'/boletos/retorno/importados/';

$total_arquivos   = 0;
$total_arquivos_sucesso = 0;
$total_arquivos_erro    = 0;

// $codigos_banco_lay = array('136', '237');
$codigos_banco_lay = array('136', '237'); // Array com os bancos que possuem layout

$listaArquivos = array_diff(scandir($dir_arquivos), array('..', '.'));
foreach($listaArquivos as $arquivo){

    $arquivo_com_erro = false;
    $linhas = file ($dir_arquivos.$arquivo);

    $arquivos_imp->nm_arq = $arquivo;
    $arquivos_imp->dt_arq = date('Y-m-d G:i:s');
    $arquivos_imp->tp_arq = "RETORNO";
    $arquivos_imp->origem = "BOLETO";


    $total_arquivos++;
    foreach ($linhas as $nu_linha => $linha) {
    	//echo "Linha #<b>{$nu_linha}</b> : " . $linha . "<br>\n";

    	if ($nu_linha == 0){

    	    $literal_retorno = trim(substr($linha, 2, 7));
    	    $codigo_banco    = trim(substr($linha, 76, 3));
            
            if(!in_array($codigo_banco, $codigos_banco_lay)) {
                // Valida se é de um banco com layout conhecido.
                $total_arquivos_erro++;
    	        $arquivo_com_erro = true;
                $arquivos_imp->status = "INVALIDO";  
                $arquivos_imp->log = " Tentando importar um arquivo com layout que não é da UNICRED ou do Bradesco. Código do Banco ". $codigo_banco .". Favor verificar com a TI";
                $arquivos_imp->tp_arq = $literal_retorno;

                //insere o arquivo com log de erro
                $conexao_BD_1->insert($arquivos_imp);

                $ret = array('arq_status' => $arquivos_imp->status,
                                'arq_msg' => $arquivos_imp->log);

                //move o arquivo para erro
                rename( $dir_arquivos.$arquivo, $dir_erro.$arquivo );

                // return $ret; // Finaliza retornando array com status da importacao    
                break;
            }

    	    if ($literal_retorno != 'RETORNO'){
    	        $total_arquivos_erro++;
    	        $arquivo_com_erro = true;
                $arquivos_imp->log = " Tentando importar um arquivo de ".$literal_retorno.". Todos os arquivos para este importador devem ser de RETORNO de boletos.";
                $arquivos_imp->tp_arq = $literal_retorno;

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


        $tipo_registro = trim(substr($linha, 0, 1));

        // syslog(158, 'MECOB - Importacao Linha antes ' . $linha . ' - ' . $nu_linha);
        // syslog(158, 'MECOB - Importacao Codigo do Banco ' . $codigo_banco);

    	if ($tipo_registro == 1){

            if ($codigo_banco == '237') {
                // Bradesco Layout
                // syslog(158, 'MECOB - Importacao Entrou Bradesco');

                $nosso_numero  = trim(substr($linha, 70, 12));
                $id_ocorrencia = trim(substr($linha, 108, 2));
                $descricao = ocorrencia($id_ocorrencia);
                $data_banco_aux    = trim(substr($linha, 110, 6));
                $dt_banco = substr( $data_banco_aux, 4, 2 ) . "-" . substr( $data_banco_aux, 2, 2 ) . "-" . substr( $data_banco_aux, 0, 2 );


                $dt_venc_aux = trim(substr($linha, 146, 6));
                $dt_venc = substr( $dt_venc_aux, 4, 2 ) . "-" . substr( $dt_venc_aux, 2, 2 ) . "-" . substr( $dt_venc_aux, 0, 2 );

                $vl_boleto = 0;
                $vl_boleto_aux = trim(substr($linha, 152, 13));
                if (ltrim(substr( $vl_boleto_aux, 0, 11 ),0) != "" ) {
                    $vl_boleto = ltrim(substr($vl_boleto_aux, 0, 11) . "." . substr($vl_boleto_aux, -2), 0);
                }

                $vl_pago = 0;
                $vl_pago_aux = trim(substr($linha, 253, 13));
                if (ltrim(substr( $vl_pago_aux, 0, 11 ),0) != "" ) {
                    $vl_pago = ltrim(substr($vl_pago_aux, 0, 11) . "." . substr($vl_pago_aux, -2), 0);
                }

                $vl_juros = 0;
                $vl_juros_aux = trim(substr($linha, 266, 13));
                if (ltrim(substr( $vl_juros_aux, 0, 11 ),0) != "" ) {
                    $vl_juros = ltrim(substr($vl_juros_aux, 0, 11) . "." . substr($vl_juros_aux, -2), 0);
                }

                $dt_credito_aux       = trim(substr($linha, 295, 6));
                $dt_credito = substr( $dt_credito_aux, 4, 2 ) . "-" . substr( $dt_credito_aux, 2, 2 ) . "-" . substr( $dt_credito_aux, 0, 2 );

                $motivo_rejeicao    = trim(substr($linha, 318, 10));
            } elseif ($codigo_banco == '136') {
                // UNICRED Layout

                // syslog(158, 'MECOB - Importacao Entrou UNICRED');

                $nosso_numero  = trim(substr($linha, 45, 17));
                // syslog(158, 'MECOB - Importacao Linha Nosso Numero ... ' . $nosso_numero);

                $id_ocorrencia = trim(substr($linha, 108, 2));
                $descricao = ocorrencia($id_ocorrencia);
                $data_banco_aux    = trim(substr($linha, 110, 6));
                $dt_banco = substr( $data_banco_aux, 4, 2 ) . "-" . substr( $data_banco_aux, 2, 2 ) . "-" . substr( $data_banco_aux, 0, 2 );


                $dt_venc_aux = trim(substr($linha, 146, 6));
                $dt_venc = substr( $dt_venc_aux, 4, 2 ) . "-" . substr( $dt_venc_aux, 2, 2 ) . "-" . substr( $dt_venc_aux, 0, 2 );

                $vl_boleto = 0;
                $vl_boleto_aux = trim(substr($linha, 152, 13));
                if (ltrim(substr( $vl_boleto_aux, 0, 11 ),0) != "" ) {
                    $vl_boleto = ltrim(substr($vl_boleto_aux, 0, 11) . "." . substr($vl_boleto_aux, -2), 0);
                }

                $vl_pago = 0;
                $vl_pago_aux = trim(substr($linha, 253, 13));
                if (ltrim(substr( $vl_pago_aux, 0, 11 ),0) != "" ) {
                    $vl_pago = ltrim(substr($vl_pago_aux, 0, 11) . "." . substr($vl_pago_aux, -2), 0);
                }

                $vl_juros = 0;
                $vl_juros_aux = trim(substr($linha, 266, 13));
                if (ltrim(substr( $vl_juros_aux, 0, 11 ),0) != "" ) {
                    $vl_juros = ltrim(substr($vl_juros_aux, 0, 11) . "." . substr($vl_juros_aux, -2), 0);
                }

                $dt_credito_aux       = trim(substr($linha, 175, 6));
                $dt_credito = substr( $dt_credito_aux, 4, 2 ) . "-" . substr( $dt_credito_aux, 2, 2 ) . "-" . substr( $dt_credito_aux, 0, 2 );

                $motivo_rejeicao    = trim(substr($linha, 318, 8));
            }

            //insere dados arquivos para processamento
            $insert_dados = "
                    INSERT INTO dados_arquivo_retorno
                    (id, nosso_numero, id_ocorrencia, descricao, dt_banco, dt_vencimento, 
                    vl_boleto, vl_pago, vl_juros, dt_credito, motivo_ocorrencia, 
                    arquivos_id, nu_linha) 
                    
                    VALUES (NULL, '".$nosso_numero."', '".$id_ocorrencia."', '".$descricao."', '".$dt_banco."', '".$dt_venc."',
                    ".$vl_boleto.", ".$vl_pago.", ".$vl_juros.", '".$dt_credito."', '".$motivo_rejeicao."',
                    ".$arquivos_imp->id.", ".$nu_linha.")            ";
            $conexao_BD_1->query_inserir($insert_dados);

            // syslog(158, 'MECOB - Importacao Linha Insert ' . json_encode($insert_dados));

        }

    }
}

// syslog(158, 'MECOB - Importacao vai chamar o processador ');

include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/importador/processador_retorno_boleto.php");

//echo "<br /> >>>> Sucesso ".$total_arquivos_sucesso;
//echo "<br /> >>>> Erro ".$total_arquivos_erro;
//echo "<br /> >>>> Total ".$total_arquivos;


function ocorrencia($id_ocorrencia){

    switch ($id_ocorrencia){

        case '02':
            return "02..Entrada Confirmada (verificar motivo na posição 319 a 328 )";
            break;
        case '03':
            return "03..Entrada Rejeitada ( verificar motivo na posição 319 a 328)";
            break;
        case '06':
            return "06..Liquidação normal (sem motivo)";
            break;
        case '09':
            return "09..Baixado Automat. via Arquivo (verificar motivo posição 319 a 328)";
            break;
        case '10':
            return "10..Baixado conforme instruções da Agência(verificar motivo pos.319 a 328)";
            break;
        case '11':
            return "11..Em Ser - Arquivo de Títulos pendentes (sem motivo)";
            break;
        case '12':
            return "12..Abatimento Concedido (sem motivo)";
            break;
        case '13':
            return "13..Abatimento Cancelado (sem motivo)";
            break;
        case '14':
            return "14..Vencimento Alterado (sem motivo)";
            break;
        case '15':
            return "15..Liquidação em Cartório (sem motivo)";
            break;
        case '16':
            return "16..Título Pago em Cheque – Vinculado";
            break;
        case '17':
            return "17..Liquidação após baixa ou Título não registrado (sem motivo)";
            break;
        case '18':
            return "18..Acerto de Depositária (sem motivo)";
            break;
        case '19':
            return "19..Confirmação Receb. Inst. de Protesto (verificar motivo pos.295 a 295)";
            break;
        case '20':
            return "20..Confirmação Recebimento Instrução Sustação de Protesto (sem motivo)";
            break;
        case '21':
            return "21..Acerto do Controle do Participante (sem motivo)";
            break;
        case '22':
            return "22..Título Com Pagamento Cancelado";
            break;
        case '23':
            return "23..Entrada do Título em Cartório (sem motivo)";
            break;
        case '24':
            return "24..Entrada rejeitada por CEP Irregular (verificar motivo pos.319 a 328)";
            break;
        case '25':
            return "25..Confirmação Receb.Inst.de Protesto Falimentar (verificar pos.295 a 295)";
            break;
        case '27':
            return "27..Baixa Rejeitada (verificar motivo posição 319 a 328)";
            break;
        case '28':
            return "28..Débito de tarifas/custas (verificar motivo na posição 319 a 328)";
            break;
        case '29':
            return "29..Ocorrências do Pagador (NOVO)";
            break;
        case '30':
            return "30..Alteração de Outros Dados Rejeitados (verificar motivo pos.319 a 328)";
            break;
        case '32':
            return "32..Instrução Rejeitada (verificar motivo posição 319 a 328)";
            break;
        case '33':
            return "33..Confirmação Pedido Alteração Outros Dados (sem motivo)";
            break;
        case '34':
            return "34..Retirado de Cartório e Manutenção Carteira (sem motivo)";
            break;
        case '35':
            return "35..Desagendamento do débito automático (verificar motivos pos. 319 a 328)";
            break;
        case '40':
            return "40 Estorno de pagamento (NOVO)";
            break;
        case '55':
            return "55 Sustado judicial (NOVO)";
            break;
        case '68':
            return "68..Acerto dos dados do rateio de Crédito (verificar motivo posição de status do registro tipo 3)";
            break;
        case '69':
            return "69..Cancelamento dos dados do rateio (verificar motivo posição de status do registro tipo 3)";
            break;
        case '73':
            return "073..Confirmação Receb. Pedido de Negativação (NOVO)";
            break;
        case '74':
            return "074..Confir Pedido de Excl de Negat (com ou sem baixa) (NOVO)";
            break;
    }
}