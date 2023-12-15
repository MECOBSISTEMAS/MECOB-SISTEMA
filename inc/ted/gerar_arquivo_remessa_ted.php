<?php

function gerar_arquivo_remessa_ted($conexao_BD_1, $ted_id)
{


    date_default_timezone_set('America/Sao_Paulo');

    include_once(getenv('CAMINHO_RAIZ') . "/repositories/arquivos/arquivos.class.php");
    include_once(getenv('CAMINHO_RAIZ') . "/repositories/teds/teds.db.php");
    include_once(getenv('CAMINHO_RAIZ') . "/inc/boleto/processadores/GARB/src/Funcoes.php");
    include_once(getenv('CAMINHO_RAIZ') . "/_configuracao/config.php");


    $tedsDB = new tedsDB();
    $funcoes = new Funcoes();
    $arquivo = new arquivos();

    // DADOS DA ME
    $cod_banco = 136; //237;
    $nm_banco = "UNICRED FLORIANOPOLIS ";//"BRADESCO";
    //tipo inscrição
    //'0' = Isento / Não Informado
    //'1' = CPF
    //'2' = CGC / CNPJ
    //'3' = PIS / PASEP
    //'9' = Outros
    $tp_inscricao = "2";
    $nu_inscricao_empresa = "07453543000103";
    $codigo_convenio = "433923";
    $agencia = "1103";
    $dv_agencia = "7";
    $conta_corrente = "95279";
    $dv_conta_corrente = "6";
    $nm_empresa = "MOTTA E ETCHEPARE LTDA ME";

    $codigo_remessa = 1;
    $data_geracao = new DateTime(date("Y-m-d G:i:s"));
    $versao_layout = "085";
    $densidade_arq = "1600"; //TO DO verificar se densidade deve ser 1600 ou 6250

    $nome_arquivo = "0515cnab240" . $data_geracao->format("dmY").$conta_corrente.$dv_conta_corrente;
    //salvar arquivo
    $arquivo->nm_arq = $nome_arquivo;
    $arquivo->dt_arq = $data_geracao->format("Y-m-d G:i:s");
    $arquivo->tp_arq = "REMESSA";
    $arquivo->status = "AGUARDANDO_ENVIO";
    $arquivo->origem = "TED";
    $nsa = $arquivo_id = $conexao_BD_1->insert($arquivo);

    $nome_arquivo .= $nsa . ".txt";
    $arquivo->nm_arq = $nome_arquivo;
    $arquivo->id     = $arquivo_id;
    $conexao_BD_1->update($arquivo);

    $qt_registros_no_arquivo = 0;

    //----------------------------------------------------------------------------------------------------------------------------------------
    // HEADER ARQ
    //----------------------------------------------------------------------------------------------------------------------------------------

    $header_arq = $cod_emp_h_arq = str_pad($cod_banco, 3, "0", STR_PAD_LEFT);
    $header_arq .= $lote_servico_h_arq = str_pad(0000, 4, "0", STR_PAD_LEFT);
    $header_arq .= $tp_registro_h_arq = str_pad(0, 1, "0", STR_PAD_LEFT);
    $header_arq .= $uso_exclusivo_1_h_arq = str_pad("", 9, " ", STR_PAD_RIGHT);
    $header_arq .= $tp_insc_empr_h_arq = str_pad($tp_inscricao, 1, " ", STR_PAD_LEFT);
    $header_arq .= $nu_insc_emp_h_arq = str_pad($nu_inscricao_empresa, 14, "0", STR_PAD_LEFT);
    $header_arq .= $cod_convenio_h_arq = str_pad($codigo_convenio, 20, " ", STR_PAD_RIGHT);
    $header_arq .= $agencia_h_arq = str_pad($agencia, 5, "0", STR_PAD_LEFT);
    $header_arq .= $dv_agencia_h_arq = str_pad($dv_agencia, 1, " ", STR_PAD_RIGHT);
    $header_arq .= $conta_corrente_h_arq = str_pad($conta_corrente, 12, "0", STR_PAD_LEFT);
    $header_arq .= $dv_conta_corrente_h_arq = str_pad($dv_conta_corrente, 1, " ", STR_PAD_RIGHT);
    $header_arq .= $dv_conta_agencia_h_arq = str_pad("", 1, " ", STR_PAD_RIGHT);
    $header_arq .= $nm_empresa_h_arq = str_pad($nm_empresa, 30, " ", STR_PAD_RIGHT);
    $header_arq .= $nm_banco_h_arq = str_pad($nm_banco, 30, " ", STR_PAD_RIGHT);
    $header_arq .= $uso_exclusivo_2_h_arq = str_pad("", 10, " ", STR_PAD_RIGHT);
    $header_arq .= $codigo_remessa_h_arq = str_pad($codigo_remessa, 1, "0", STR_PAD_LEFT);
    $header_arq .= $dt_geracao_arq_h_arq = str_pad($data_geracao->format('dmY'), 8, "0", STR_PAD_LEFT);
    $header_arq .= $hora_geracao_arq_h_arq = str_pad($data_geracao->format("Gis"), 6, "0", STR_PAD_LEFT);
    $header_arq .= $nsa_h_arq = str_pad($nsa, 6, "0", STR_PAD_LEFT);
    $header_arq .= $versao_layout_h_arq = str_pad($versao_layout, 3, "0", STR_PAD_LEFT);
    $header_arq .= $densidade_arq_h_arq = str_pad($densidade_arq, 5, "0", STR_PAD_LEFT);// 21.0 - Densidade de Gravação do Arquivo O que preencher
    $header_arq .= $uso_reserv_banco_h_arq = str_pad("", 20, " ", STR_PAD_RIGHT);
    $header_arq .= $uso_reserv_emp_h_arq = str_pad("", 20, " ", STR_PAD_RIGHT);
    $header_arq .= $uso_exclusivo_3_h_arq = str_pad("", 29, " ", STR_PAD_RIGHT);
    $header_arq .= chr(10);

    $qt_registros_no_arquivo++;

    //----------------------------------------------------------------------------------------------------------------------------------------
    //HEADER DE LOTE
    //----------------------------------------------------------------------------------------------------------------------------------------
    // DADOS ME
    $mensagem = "";
    $logradouro = "RUA TENENTE SILVEIRA";
    $numero = "315";
    $complemento = "";
    $cidade = "FLORIANOPOLIS";
    $cep_prefixo = "88010";
    $cep_sufixo = "301";
    $estado = "SC";

    $contador_lote_servico = 1;
    $qt_registros_lote = 0;
    $qt_lotes_no_arquivo = 0;

    $header_lote = $cod_emp_h_lote = str_pad($cod_banco, 3, "0", STR_PAD_LEFT);
    $header_lote .= $lote_servico_h_lote = str_pad($contador_lote_servico, 4, "0", STR_PAD_LEFT);
    $header_lote .= $tp_registro_h_lote = str_pad(1, 1, "0", STR_PAD_LEFT);
    $header_lote .= $tp_operacao_h_lote = str_pad("C", 1, " ", STR_PAD_RIGHT); // DUVIDA 04.1 - 'C' = Lançamento a Crédito - G028
    $header_lote .= $tp_servico_h_lote = str_pad("20", 2, "0", STR_PAD_LEFT); // DUVIDA 05.1 - '20' = Pagamento Fornecedor - G025
    $header_lote .= $forma_lanc_h_lote = str_pad("41", 2, "0", STR_PAD_LEFT); // '41' = TED – Outra Titularidade (1)
    $header_lote .= $versao_layout_h_lote = str_pad("044", 3, "0", STR_PAD_LEFT);
    $header_lote .= $uso_exclusivo_1_h_lote = str_pad("", 1, " ", STR_PAD_RIGHT);
    $header_lote .= $tp_insc_empr_h_lote = str_pad($tp_inscricao, 1, " ", STR_PAD_LEFT);
    $header_lote .= $nu_insc_emp_h_lote = str_pad($nu_inscricao_empresa, 14, "0", STR_PAD_LEFT);
    $header_lote .= $cod_convenio_h_lote = str_pad($codigo_convenio, 20, " ", STR_PAD_RIGHT);
    $header_lote .= $agencia_h_lote = str_pad($agencia, 5, "0", STR_PAD_LEFT);
    $header_lote .= $dv_agencia_h_lote = str_pad($dv_agencia, 1, " ", STR_PAD_RIGHT);
    $header_lote .= $conta_corrente_h_lote = str_pad($conta_corrente, 12, "0", STR_PAD_LEFT);
    $header_lote .= $dv_conta_corrente_h_lote = str_pad($dv_conta_corrente, 1, " ", STR_PAD_RIGHT);
    $header_lote .= $dv_conta_agencia_h_lote = str_pad("", 1, " ", STR_PAD_RIGHT);
    $header_lote .= $nm_empresa_h_lote = str_pad($nm_empresa, 30, " ", STR_PAD_RIGHT);
    $header_lote .= $mensagem_h_lote = str_pad($mensagem, 40, " ", STR_PAD_RIGHT);
    $header_lote .= $logradouro_h_lote = str_pad($logradouro, 30, " ", STR_PAD_RIGHT);
    $header_lote .= $numero_h_lote = str_pad($numero, 5, "0", STR_PAD_LEFT);
    $header_lote .= $complemento_h_lote = str_pad($complemento, 15, " ", STR_PAD_RIGHT);
    $header_lote .= $cidade_h_lote = str_pad($cidade, 20, " ", STR_PAD_RIGHT);
    $header_lote .= $cep_prefixo_h_lote = str_pad($cep_prefixo, 5, "0", STR_PAD_LEFT);
    $header_lote .= $cep_sufixo_h_lote = str_pad($cep_sufixo, 3, " ", STR_PAD_RIGHT);
    $header_lote .= $estado_h_lote = str_pad($estado, 2, " ", STR_PAD_RIGHT);
    $header_lote .= $forma_pagto_h_lote = str_pad("01", 2, "0", STR_PAD_LEFT); // DUVIDA 26.1 - 01 - Débito em Conta Corrente - P014
    $header_lote .= $uso_exclusivo_2_h_lote = str_pad("", 6, " ", STR_PAD_RIGHT);
    $header_lote .= $ocorrencias_h_lote = str_pad("", 10, " ", STR_PAD_RIGHT);
    $header_lote .= chr(10);

    $qt_registros_lote++;
    $qt_lotes_no_arquivo++;
    $qt_registros_no_arquivo++;

    //----------------------------------------------------------------------------------------------------------------------------------------
    //SEGMENTO A
    //----------------------------------------------------------------------------------------------------------------------------------------
    $nu_seq_registro_lote = 1;
    $sum_valores_lote = 0;

    //Buscar dados do favorecido
    $regTed = $tedsDB->busca_dados_arquivos_remessa($conexao_BD_1, $ted_id);


    //FAVORECIDO
    $cod_camara = "018"; // 08.3 - '018' = TED (STR,CIP) | '700' = DOC (COMPE) - P001
    $cod_banco_fav = $regTed[0]["banco"];
    $agencia_fav = $regTed[0]["agencia"];
    $dv_agencia_fav = $regTed[0]["dv_agencia"];
    $conta_corrente_fav = $regTed[0]["conta"];
    $dv_conta_corrente_fav = $regTed[0]["dv_conta"];
    $nm_fav = $funcoes->remover_acentos($regTed[0]["nome"]);
    //CREDITO
    $nu_documento = $ted_id;//"USAR ID TED";// Número atribuído pela Empresa (Pagador) para identificar o documento de Pagamento (Nota Fiscal, Nota Promissória, etc.).
    $dt_pagto_ted = $regTed[0]["dt_pagto_ted"]; // Utilizar o formato DDMMAAAA
    $tp_moeda = "BRL"; // 'BRL' = Real
    $qt_moeda = "00"; // DUVIDA 19.3A - G041 - Quantidade da Moeda - Número de unidades do tipo de moeda identificada para cálculo do valor do documento.
    $vl_pagto = $funcoes->remove_formatacao($regTed[0]["vl_ted"]);
    $nosso_numero = ""; // DUVIDA 21.3A - G043 - Número do Documento Atribuído pelo Banco (Nosso Número)
    $dt_real = ""; //DDMMAAAA - A ser preenchido quando arquivo for de retorno (Código=2 no Header de Arquivo) e referirse a uma confirmação de lançamento.
    $vl_real = ""; // A ser preenchido quando arquivo for de retorno (Código=2 no Header de Arquivo) e referirse a uma confirmação de lançamento.
    //
    $mensagem2 = "";
    $cod_finalidade_doc = "01"; // DUVIDA 25.3A - '01' = Crédito em Conta - P005
    $cod_finalidade_ted = ""; // DUVIDA 26.3A - P011 - O QUE PREENCHER
    $cod_finalidade_complementar = ""; // 27.3A - P013 - O QUE PREENCHER
    $aviso_favorecido = "0"; //'0' = Não Emite Aviso

    $segmento_a = $cod_emp_seg_a = str_pad($cod_banco, 3, "0", STR_PAD_LEFT);
    $segmento_a .= $lote_servico_seg_a = str_pad($contador_lote_servico, 4, "0", STR_PAD_LEFT);
    $segmento_a .= $tp_registro_seg_a = str_pad(3, 1, "0", STR_PAD_LEFT);
    $segmento_a .= $nu_seq_registro_lote_seg_a = str_pad($nu_seq_registro_lote, 5, "0", STR_PAD_LEFT);
    $segmento_a .= $cod_segmento_seg_a = str_pad("A", 1, " ", STR_PAD_RIGHT); // 05.3A
    $segmento_a .= $tp_movimento_seg_a = str_pad("0", 1, "0", STR_PAD_LEFT);  // DUVIDA 06.3A - '0' = Indica INCLUSÃO - G060
    $segmento_a .= $cod_inst_movimento_seg_a = str_pad("23", 2, "0", STR_PAD_LEFT); // DUVIDA 07.3A - '23' = Pagamento Direto ao Fornecedor - Baixar - G061
    $segmento_a .= $cod_camara_seg_a = str_pad($cod_camara, 3, "0", STR_PAD_LEFT);
    $segmento_a .= $cod_banco_fav_1_seg_a = str_pad($cod_banco_fav, 3, "0", STR_PAD_LEFT);
    $segmento_a .= $agencia_fav_seg_a = str_pad($agencia_fav, 5, "0", STR_PAD_LEFT);
    $segmento_a .= $dv_agencia_fav_seg_a = str_pad($dv_agencia_fav, 1, " ", STR_PAD_RIGHT);
    $segmento_a .= $conta_corrente_fav_seg_a = str_pad($conta_corrente_fav, 12, "0", STR_PAD_LEFT);
    $segmento_a .= $dv_conta_corrente_fav_seg_a = str_pad($dv_conta_corrente_fav, 1, " ", STR_PAD_RIGHT);
    $segmento_a .= $dv_conta_agencia_seg_a = str_pad("", 1, " ", STR_PAD_RIGHT);
    $segmento_a .= $nm_fav_seg_a = str_pad($nm_fav, 30, " ", STR_PAD_RIGHT);
    $segmento_a .= $nu_documento_seg_a = str_pad($nu_documento, 20, " ", STR_PAD_RIGHT);
    $segmento_a .= $dt_pagto_ted_seg_a = str_pad($dt_pagto_ted, 8, "0", STR_PAD_LEFT);
    $segmento_a .= $tp_moeda_seg_a = str_pad($tp_moeda, 3, " ", STR_PAD_RIGHT);
    $segmento_a .= $qt_moeda_seg_a = str_pad($qt_moeda, 15, "0", STR_PAD_LEFT);
    $segmento_a .= $vl_pagto_seg_a = str_pad($vl_pagto, 15, "0", STR_PAD_LEFT);
    $segmento_a .= $nosso_numero_seg_a = str_pad($nosso_numero, 20, " ", STR_PAD_RIGHT);
    $segmento_a .= $dt_real_seg_a = str_pad($dt_real, 8, "0", STR_PAD_LEFT);
    $segmento_a .= $vl_real_seg_a = str_pad($vl_real, 15, "0", STR_PAD_LEFT);
    $segmento_a .= $mensagem2_seg_a = str_pad($mensagem2, 40, " ", STR_PAD_RIGHT);
    $segmento_a .= $cod_finalidade_doc_seg_a = str_pad($cod_finalidade_doc, 2, " ", STR_PAD_RIGHT);
    $segmento_a .= $cod_finalidade_ted_seg_a = str_pad($cod_finalidade_ted, 5, " ", STR_PAD_RIGHT);
    $segmento_a .= $cod_fin_complementar_seg_a = str_pad($cod_finalidade_complementar, 2, " ", STR_PAD_RIGHT);
    $segmento_a .= $uso_excluisivo_1_seg_a = str_pad("", 3, " ", STR_PAD_RIGHT);
    $segmento_a .= $aviso_favorecido_seg_a = str_pad($aviso_favorecido, 1, "0", STR_PAD_LEFT);
    $segmento_a .= $ocorrencias_seg_a = str_pad("", 10, " ", STR_PAD_RIGHT);
    $segmento_a .= chr(10);

    $qt_registros_lote++;
    $sum_valores_lote += $vl_pagto;
    $qt_registros_no_arquivo++;
    $linha_arq = $qt_registros_no_arquivo;

    //----------------------------------------------------------------------------------------------------------------------------------------
    /// SEGMENTO B
    //----------------------------------------------------------------------------------------------------------------------------------------
    $nu_seq_registro_lote++;

    $nu_inscricao_fav = $funcoes->remove_formatacao($regTed[0]["cpf_cnpj"]);

    if (strlen($nu_inscricao_fav) == 11) {
        $tp_inscricao_fav = "1";
    } elseif (strlen($nu_inscricao_fav) == 14) {
        $tp_inscricao_fav = "2";
    } else {
        $tp_inscricao_fav = "9";
    }

    $logradouro_fav = $funcoes->remover_acentos($regTed[0]["rua"]);
    $numero_fav = $regTed[0]["numero"];

    $complemento_fav_bd = "";
    if (isset($regTed[0]["complemento"])){
        $complemento_fav_bd = substr($funcoes->remover_acentos($regTed[0]["complemento"]), 0, 15);
    }
    $complemento_fav = $complemento_fav_bd;
    $bairro_fav = $funcoes->remover_acentos($regTed[0]["bairro"]);
    $cidade_fav = $funcoes->remover_acentos($regTed[0]["cidade"]);
    $cep_prefixo_fav = substr($regTed[0]["cep"], 0, 5);
    $cep_sufixo_fav = substr($regTed[0]["cep"], -3);
    $estado_fav = $regTed[0]["estado"];

    $dt_vencimento = $dt_pagto_ted;
    $vl_documento = $vl_pagto;
    $vl_abatimento = "0";
    $vl_desconto = "0";
    $vl_mora = "0";
    $vl_multa = "0";
    $aviso_favorecido_b = "0"; //'0' = Não Emite Aviso

    $segmento_b = $cod_emp_seg_b = str_pad($cod_banco, 3, "0", STR_PAD_LEFT);
    $segmento_b .= $lote_servico_seg_b = str_pad($contador_lote_servico, 4, "0", STR_PAD_LEFT);
    $segmento_b .= $tp_registro_seg_b = str_pad(3, 1, "0", STR_PAD_LEFT);
    $segmento_b .= $nu_seq_registro_lote_seg_b = str_pad($nu_seq_registro_lote, 5, "0", STR_PAD_LEFT);
    $segmento_b .= $cod_segmento_seg_b = str_pad("B", 1, " ", STR_PAD_RIGHT);
    $segmento_b .= $uso_excluisivo_1_seg_b = str_pad("", 3, " ", STR_PAD_RIGHT);
    $segmento_b .= $tp_insc_fav_seg_b = str_pad($tp_inscricao_fav, 1, " ", STR_PAD_LEFT);
    $segmento_b .= $nu_insc_fav_seg_b = str_pad($nu_inscricao_fav, 14, "0", STR_PAD_LEFT);
    $segmento_b .= $logradouro_fav_seg_b = str_pad($logradouro_fav, 30, " ", STR_PAD_RIGHT);
    $segmento_b .= $numero_fav_seg_b = str_pad($numero_fav, 5, "0", STR_PAD_LEFT);
    $segmento_b .= $complemento_fav_seg_b = str_pad($complemento_fav, 15, " ", STR_PAD_RIGHT);
    $segmento_b .= $bairro_fav_seg_b = str_pad($bairro_fav, 15, " ", STR_PAD_RIGHT);
    $segmento_b .= $cidade_fav_seg_b = str_pad($cidade_fav, 20, " ", STR_PAD_RIGHT);
    $segmento_b .= $cep_prefixo_fav_seg_b = str_pad($cep_prefixo_fav, 5, "0", STR_PAD_LEFT);
    $segmento_b .= $cep_sufixo_fav_seg_b = str_pad($cep_sufixo_fav, 3, " ", STR_PAD_RIGHT);
    $segmento_b .= $estado_fav_seg_b = str_pad($estado_fav, 2, " ", STR_PAD_RIGHT);
    $segmento_b .= $dt_vencimento_seg_b = str_pad($dt_vencimento, 8, "0", STR_PAD_LEFT);
    $segmento_b .= $vl_documento_seg_b = str_pad($vl_documento, 15, "0", STR_PAD_LEFT);
    $segmento_b .= $vl_abatimento_seg_b = str_pad($vl_abatimento, 15, "0", STR_PAD_LEFT);
    $segmento_b .= $vl_desconto_seg_b = str_pad($vl_desconto, 15, "0", STR_PAD_LEFT);
    $segmento_b .= $vl_mora_seg_b = str_pad($vl_mora, 15, "0", STR_PAD_LEFT);
    $segmento_b .= $vl_multa_seg_b = str_pad($vl_multa, 15, "0", STR_PAD_LEFT);
    $segmento_b .= $cod_doc_fav_seg_b = str_pad("", 15, " ", STR_PAD_RIGHT);
    $segmento_b .= $aviso_favorecido_seg_b = str_pad($aviso_favorecido_b, 1, "0", STR_PAD_LEFT);
    $segmento_b .= $uso_excluisivo_siap_seg_b = str_pad("", 6, " ", STR_PAD_RIGHT);
    $segmento_b .= $uso_excluisivo_2_seg_b = str_pad("", 8, " ", STR_PAD_RIGHT);
    $segmento_b .= chr(10);

    $qt_registros_lote++;
    $qt_registros_no_arquivo++;


    //----------------------------------------------------------------------------------------------------------------------------------------
    //TRAILER LOTE
    //----------------------------------------------------------------------------------------------------------------------------------------

    $nu_aviso_debito = ""; //08.5 - G066 Número de Aviso de Débito, O QUE PREENCHER.
    $sum_qt_moedas_lote = 0;
    $qt_registros_lote++;

    $trailer_lote = "";
    $trailer_lote = $cod_emp_t_lote = str_pad($cod_banco, 3, "0", STR_PAD_LEFT);
    $trailer_lote .= $lote_servico_t_lote = str_pad($contador_lote_servico, 4, "0", STR_PAD_LEFT);
    $trailer_lote .= $tp_registro_t_lote = str_pad(5, 1, "0", STR_PAD_LEFT);
    $trailer_lote .= $uso_excluisivo_1_t_lote = str_pad("", 9, " ", STR_PAD_RIGHT);
    $trailer_lote .= $qt_registro_t_lote = str_pad($qt_registros_lote, 6, "0", STR_PAD_LEFT);
    $trailer_lote .= $sum_valores_t_lote = str_pad($sum_valores_lote, 18, "0", STR_PAD_LEFT);
    $trailer_lote .= $sum_qt_moedas_t_lote = str_pad($sum_qt_moedas_lote, 18, "0", STR_PAD_LEFT); // DUVIDA 07.5 - G058 - Somatória de Quantidade de Moedas Valor obtido pela somatória das quantidades de moeda dos registros de detalhe (Registro = '3' / Código de Segmento = {'A' / 'J'}).
    $trailer_lote .= $nu_aviso_debito_t_lote = str_pad($nu_aviso_debito, 6, "0", STR_PAD_LEFT);
    $trailer_lote .= $uso_excluisivo_2_t_lote = str_pad("", 165, " ", STR_PAD_RIGHT);
    $trailer_lote .= $codigo_ocorrencia_t_lote = str_pad("", 10, " ", STR_PAD_RIGHT);
    $trailer_lote .= chr(10);

    $qt_registros_no_arquivo++;

    //----------------------------------------------------------------------------------------------------------------------------------------
    //TRAILER ARQ
    //----------------------------------------------------------------------------------------------------------------------------------------

    $qt_registros_no_arquivo++;

    $trailer_arq = "";
    $trailer_arq = $cod_emp_t_arq = str_pad($cod_banco, 3, "0", STR_PAD_LEFT);
    $trailer_arq .= $lote_servico_t_arq = str_pad(9999, 4, "0", STR_PAD_LEFT);
    $trailer_arq .= $tp_registro_t_arq = str_pad(9, 1, "0", STR_PAD_LEFT);
    $trailer_arq .= $uso_excluisivo_1_t_arq = str_pad("", 9, " ", STR_PAD_RIGHT);
    $trailer_arq .= $qt_lotes_arquivo_t_arq = str_pad($qt_lotes_no_arquivo, 6, "0", STR_PAD_LEFT);
    $trailer_arq .= $qt_registros_no_arquivo_t_arq = str_pad($qt_registros_no_arquivo, 6, "0", STR_PAD_LEFT);
    $trailer_arq .= $qt_contas_concil_t_arq = str_pad(0, 6, "0", STR_PAD_LEFT);
    $trailer_arq .= $uso_excluisivo_2_t_arq = str_pad("", 205, " ", STR_PAD_RIGHT);
    $trailer_arq .= chr(10);


    //241 PORQUE TEM O CARACTER DE QUEBRA DE LINHA
    $conteudo =
        $funcoes->valid_linha($header_arq, 241) .
        $funcoes->valid_linha($header_lote, 241) .
        $funcoes->valid_linha($segmento_a, 241) .
        $funcoes->valid_linha($segmento_b, 241) .
        $funcoes->valid_linha($trailer_lote, 241) .
        $funcoes->valid_linha($trailer_arq, 241);

    // Abre o arquivo para leitura e escrita
    $f = fopen(getenv('CAMINHO_RAIZ') . '/teds/remessa/' . $nome_arquivo, "x");

    // Escreve no arquivo
    fwrite($f, $conteudo);

    // Libera o arquivo
    fclose($f);

    //atualizar TED com ID do arquivo
    $tedsDB->atualiza_ted_arquivo($conexao_BD_1, $ted_id, $arquivo->tp_arq, $arquivo_id, $linha_arq);

}