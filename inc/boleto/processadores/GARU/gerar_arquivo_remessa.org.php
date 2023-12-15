<?php

/**
 * @param $contrato_id
 */
function gerar_arquivo_remessa($contrato_id, $conexao_BD_1, $tipo = "", $parcelas_id = "", $dados_parcela = []){

    // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Arquivos Remessa Entrou');

    date_default_timezone_set('America/Sao_Paulo');

    include_once(getenv('CAMINHO_RAIZ') . "/repositories/contratos/contratos.db.php");
    include_once(getenv('CAMINHO_RAIZ') . "/repositories/boletos_avulso/boletos_avulso.db.php");

    $contratos_funcoes_DB = new contratosDB();
    $boletos_avulsos_funcoes_DB = new boletos_avulsoDB();

    include 'src/Arquivo.php';
    require_once('src/Funcoes.php');

    $funcoes = new Funcoes();
    $arquivo = new Arquivo();

    //$contrato_id = 99;


    if ($tipo === "BOLETO_AVULSO"){
        $boletos_avulso_id = $contrato_id;
        $contrato_id = "null";
        $parcelas = $boletos_avulsos_funcoes_DB->dados_boleto_avulso($conexao_BD_1, $boletos_avulso_id);
    }
    else if ($tipo === 'PARCELA') {
        $boletos_avulso_id = "null";
        $nova_parcela_id = $contratos_funcoes_DB->exclui_parcela_e_cria_nova($conexao_BD_1, $parcelas_id, $dados_parcela);
        if ($nova_parcela_id > 0) {
            $parcelas = $contratos_funcoes_DB->dados_boleto($conexao_BD_1, $contrato_id,$nova_parcela_id);
        }     
        else {
            echo "erro ao gerar arquivo";
            exit;
        }
    } else {
        $boletos_avulso_id = "null";
        $parcelas = $contratos_funcoes_DB->dados_boleto($conexao_BD_1, $contrato_id);
    }
    
    // if ($tipo === 'PARCELA') {
    // } else {
        
        if (count($parcelas)) {
            $milissegundos = str_pad(round((microtime(true) - floor(microtime(true))) * 1000000),7,0,STR_PAD_LEFT);
            $nm_ini =  "CB_UNICRED_" . date('dmy')."_".$milissegundos;
            $select_sufixo = " SELECT count(*) total FROM arquivos WHERE date(dt_arq) = date(now()) and tp_arq= 'REMESSA' and origem = 'BOLETO' and status <> 'CORROMPIDO'";
            $regSufixo = $conexao_BD_1->query($select_sufixo);
            $contArq=$regSufixo[0]['total'];
            while(1){
                $contArq++;
                $sufixo = str_pad($contArq, 2, 0, STR_PAD_LEFT);
                $nm_arq = $nm_ini.$sufixo;
                $nm_arq_bd = $nm_arq.".REM";
                if(!file_exists(getenv('CAMINHO_RAIZ') . '/boletos/remessa/' . $nm_arq_bd)){
                    break;
                }
            }
            
            // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Arquivos Remessa ' . $nm_arq_bd);

            $dt_arq = date('Y-m-d G:i:s');
            $tp_arq = 'REMESSA';
            $origem = 'BOLETO';

            $arquivo_id = $arquivo->inserir($conexao_BD_1, $nm_arq_bd, $dt_arq, $tp_arq, $contrato_id, $origem, $boletos_avulso_id);
            $arquivo->setFilename(getenv('CAMINHO_RAIZ') . '/boletos/remessa/' . $nm_arq);

            //configurando o arquivo de remessa
            $config['codigo_empresa'] = '80009087';
            $config['razao_social'] = 'MOTTA E ETCHEPARE LTDA ME';
            $config['numero_remessa'] = $arquivo_id;
            $config['data_gravacao'] = date('dmy');

            //configurando remessa
            $arquivo->config($config);

            $linha_arq = 1;

            foreach ($parcelas as $parcela) {

                //for ($i = 0; $i < 20; $i++) {
                //adicionando boleto
                $boleto['agencia'] = '1103';
                $boleto['agencia_dv'] = '7';
                $boleto['razao_conta_corrente'] = '0000';
                $boleto['conta'] = '095279';
                $boleto['conta_dv'] = '6';
                $boleto['carteira'] = '021';
                $boleto['numero_controle'] = '';//'5219';
                $boleto['habilitar_debito_compensacao'] = false;
                $boleto['habilitar_multa'] = true;
                $boleto['percentual_multa'] = '0200';
                $boleto['nosso_numero'] = $parcela["nosso_numero"];
                $boleto['nosso_numero_dv'] = $funcoes->digito_verificador_nosso_numero($parcela["nosso_numero"]);
                $boleto['desconto_dia'] = '0';
                $boleto['rateio'] = false;
                $boleto['numero_documento'] = $parcela["nosso_numero"];
                $boleto['vencimento'] = $parcela["dt_vencimento"];
                $boleto['valor'] = $parcela["vl_corrigido"];
                $boleto['data_emissao_titulo'] = $parcela["dt_contrato"];
                $boleto['valor_dia_atraso'] = '0054';

                if ($boleto['valor' ] < 100.00){
                    $mora_dia = round($boleto['valor'] * 0.006,2); // Critica enviada pela UNICRED - Na posição 161 a 173, valor de mora diário deve ser menor que 0,6% do valor do documento;
                    $mora_dia = str_replace(",","", $mora_dia);
                    $boleto['valor_dia_atraso'] = str_replace(".","", $mora_dia);
                }
                $boleto['data_limite_desconto'] = '000000';
                $boleto['valor_desconto'] = '0';
                $boleto['valor_iof'] = '0';
                $boleto['valor_abatimento_concedido'] = '0';
                $boleto['numero_inscricao'] = $funcoes->remove_formatacao($parcela["cpf_cnpj"]);

                if (strlen($boleto['numero_inscricao']) == 11) {
                    $boleto['tipo_inscricao_pagador'] = 'CPF';
                }
                elseif (strlen($boleto['numero_inscricao']) == 14) {
                    $boleto['tipo_inscricao_pagador'] = 'CNPJ';
                }
                else{
                    $boleto['tipo_inscricao_pagador'] = 'OUTROS';
                }

                $boleto['nome_pagador'] = substr($funcoes->remover_acentos($parcela["nome"]),0,40);
                $boleto['endereco_pagador'] = $funcoes->remover_acentos($parcela["rua"] . ',' . $parcela["numero"]);
                $boleto['primeira_mensagem'] = '';

                $boleto['cep_pagador'] = "00000";
                $boleto['sufixo_cep_pagador'] = "000";
                if (strlen($parcela["cep"])>0) {
                    $boleto['cep_pagador'] = substr($parcela["cep"], 0, 5);
                    $boleto['sufixo_cep_pagador'] = substr($parcela["cep"], -3);
                }
                $bairro_pagador = $funcoes->montar_branco($funcoes->remover_acentos(substr($parcela["bairro"],0,12)),12, 'right');
                $cidade_pagador = $funcoes->montar_branco($funcoes->remover_acentos(substr($parcela["cidade"],0,20)),20, 'right');
                $estado_pagador = $funcoes->montar_branco($funcoes->remover_acentos(substr($parcela["estado"],0,2)),2, 'right');
                $boleto['sacador_segunda_mensagem'] = $bairro_pagador.$cidade_pagador.$estado_pagador;
                $boleto['bairro_pagador'] = $bairro_pagador;
                $boleto['cidade_pagador'] = $cidade_pagador;
                $boleto['estado_pagador'] = $estado_pagador;

                //adicionando boleto
                $arquivo->add_boleto($boleto);

                $linha_arq++;

                //atualiza a parcela com id do arquivo
                $contratos_funcoes_DB->atualiza_parcela_arquivo($conexao_BD_1, $parcela["nosso_numero"], $tp_arq, $arquivo_id, $linha_arq);
            }
            
            // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Arquivos Remessa ');

            $arquivo->save();
        } //else {
        //    echo "Nenhuma parcela para gerar arquivos de REMESSA para o contrato.";
        //}
    
    // }
}