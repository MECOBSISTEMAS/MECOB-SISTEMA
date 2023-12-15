<?php
if(isset($_GET['id'])){
	$contrato_id = $_GET['id'];
}
else{echo 'Não foi passado nenhum contrato para geração do PDF'; exit;}

include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg = array();


	include_once(getenv('CAMINHO_RAIZ')."/inc/pdf/pdflista.php");
	
	include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
	include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
	
	$contratosDB  = new contratosDB();
	$contratos    = new contratos();
	$contratos->id = $contrato_id;
		
	$file = "simulacao_contrato_".$contrato_id;
	$filtros=array();
    $filtros['filtro_id'] = $contrato_id;
	
	//Topo da página
	$cabecalho=array();
	$cabecalho[] = "imagem";
	$cabecalho[] = "Simulação de acordo";
	
	//info adicional antes da tabela
	$info=array();
	$contrato_info = $contratosDB->lista_contratos($contratos, $conexao_BD_1);
	$contrato_info = $contrato_info[0];

	$info[] = "Id Contrato: ".$contrato_info['id'];
	$info[] = "Data contrato: ".ConverteData($contrato_info['dt_contrato']);
	$info[] = "Valor Original: R$ ".Format( $contrato_info['vl_contrato'],'numero');
	$info[] = "Vendedor: ".$contrato_info['vendedor_nome']." - ".$contrato_info['vendedor_email']." - ".$contrato_info['vendedor_cpf_cnpj'];
	$info[] = "Comprador: ".$contrato_info['comprador_nome']." - ".$contrato_info['comprador_email']." - ".$contrato_info['comprador_cpf_cnpj'];
	
	$parcelas = $contratosDB->lista_parcelas_contratos($contrato_id, $conexao_BD_1);
	//echo '<pre>'; print_r($parcelas); echo '</pre>';
	
	$tt_original = $tt_correcao = $tt_juros = $tt_hono = $tt_atual = 0;
	$parcelas_imprimir = array();
	foreach($parcelas as $parcela){
		if($parcela['liquidada_no_cadastro'] == 'S' ||  $parcela['simulada'] == 'N' ||  
				(   !empty($parcela['dt_pagto']) &&  $parcela['dt_pagto'] != '0000-00-00'  )  ){
			continue;
		}
		$tt_original += str_replace(',','.',$parcela['vl_parcela']);
		$tt_correcao += str_replace(',','.',$parcela['vl_correcao_monetaria']);
		$tt_juros 	+= str_replace(',','.',$parcela['vl_juros']);
		$tt_hono 	 += str_replace(',','.',$parcela['vl_honorarios']);
		$tt_atual 	+= str_replace(',','.',$parcela['vl_corrigido']);
		
		$parcelas_imprimir[] = $parcela;
	}
	
	$info[] = "";
	$info[] = "Dívida original: R$ ".Format($tt_original,'numero');
	// $info[] = "Correção : R$ ".Format($tt_correcao,'numero');
	// $info[] = "Juros : R$ ".Format($tt_juros,'numero');
	// $info[] = "Honorários : R$ ".Format($tt_hono,'numero');
	$info[] = "Dívida atualizada : R$ ".Format($tt_atual,'numero');
	
	
	
	#print_r($info);
	
	//tamanho das colunas
	// $tamcolunas=array(10,25,25,25,25,25,25,25); //total = 207
	$tamcolunas=array(10,45,45,45,45,25,25,25); //total = 207
	
	//nome das colunas
	// $head=array('#','Parcela',utf8_decode('Correção'),'Juros',utf8_decode('Honorários'),'Corrigido', 'Vencimento', 'Pagto');
	$head=array('#','Parcela','Corrigido', 'Vencimento', 'Pagto');
	
	// linhas
	$config_body = array();  // nome , limite tam linha (strlen), alinhamento , tipo (default, valor, data, fone)
	$config_body[] = array('nu_parcela',27,'C', '');
	$config_body[] = array('vl_parcela',27,'R', 'valor');
	// $config_body[] = array('vl_correcao_monetaria',15,'C', 'valor');
	// $config_body[] = array('vl_juros',20,'C', 'valor');
	// $config_body[] = array('vl_honorarios',100,'R', 'valor');
	$config_body[] = array('vl_corrigido',16,'R', 'valor');
	$config_body[] = array('dt_vencimento',100,'R', 'data');
	$config_body[] = array('dt_pagto',16,'R', 'data');
	
	//observação adicional para tratamento específico
	$observacao = array();


	
pdflista($cabecalho,$info, $tamcolunas, $head,$parcelas_imprimir,$config_body,$observacao,$file,$link);

