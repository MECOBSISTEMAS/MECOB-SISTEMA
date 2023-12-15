<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

set_time_limit(12000);
ini_set('memory_limit', '4024M');

if(isset($_GET['pagina'])){
	$pagina = $_GET['pagina'];
}
else{echo 'Não foi passado nenhuma página para geração do PDF'; exit;}

include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg = array();

//********************************************************************************************************************************
if($pagina == 'contratos'){
	include_once(getenv('CAMINHO_RAIZ')."/inc/pdf/pdflista.php"); 
	$order = $_GET['order'];
	$ordem = $_GET['ordem'];
	
	include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
	include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
	
	$contratosDB  = new contratosDB();
	$contratos    = new contratos();
		
	$file = "Lista Contratos";
	
	$filtros=array();
		  if(isset($_REQUEST["filtro_contrato"])){$filtros['filtro_contrato'] = trim($_REQUEST["filtro_contrato"]);}
		  if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
		  if(isset($_REQUEST["filtro_data_fim"])){$filtros['filtro_data_fim'] = trim($_REQUEST["filtro_data_fim"]);} 
		  if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
		  if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
		  if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
		  if(isset($_REQUEST["filtro_lote"])){$filtros['filtro_lote'] = trim($_REQUEST["filtro_lote"]);}	
		  if(isset($_REQUEST["filtro_id"])){$filtros['filtro_id'] = trim($_REQUEST["filtro_id"]);}
		  if(isset($_REQUEST["filtro_pagto"])){$filtros['filtro_pagto'] = trim($_REQUEST["filtro_pagto"]);} 		  
	
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
									$order = "c.id ".$_REQUEST["ordem"].",";			
									break;
					case 'descricao':		
									$order = "c.descricao ".$_REQUEST["ordem"].",";			
									break;
					case 'valor':		
									$order = "c.vl_contrato ".$_REQUEST["ordem"].",";			
									break;
					case 'data':		
									$order = "c.dt_contrato ".$_REQUEST["ordem"].",";			
									break;
					case 'vendedor':		
									$order = "pv.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'comprador':		
									$order = "pc.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'evento':		
									$order = "e.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'status':		
									$order = "c.status ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}
	
	//Topo da página
	$cabecalho=array();
	$cabecalho[] = "imagem";
	$cabecalho[] = "Lista Contratos ";
	
	//info adicional antes da tabela
	$info=array();
	$totais= $contratosDB->lista_totais_contratos($filtros,$conexao_BD_1);
	$info[] = $totais." contratos";
	#print_r($info);
	

	$tamcolunas=array(70,65,70); //total = 207
	 
	
	$head=array('Contrato','Evento',utf8_decode('Última Ocorrência'));
	
	
	// linhas
	$body = $contratosDB->lista_contratos('', $conexao_BD_1,  $filtros   , $order ,   0,'N',1);
	//echo '<pre>'; print_r($body[0]); echo '</pre>'; 	exit;
	
	$config_body = array();  // nome , limite tam linha (strlen), alinhamento , tipo (default, valor, data, fone)
	$config_body[] = array('contrato descricao dt_contrato vl_contrato status',50,'L', '');  
	$config_body[] = array('ct_evento evento_nome vendedor_nome comprador_nome',50,'L', '');
	$config_body[] = array('ocorrencia data_ocorrencia oc_status oc_mensagem',50,'L', ''); 
	
	//observação adicional para tratamento específico
	$observacao = array(); 
	//extende página
	$observacao[] = 'extende_pagina';

}
elseif($pagina == 'parcelas'){
	include_once(getenv('CAMINHO_RAIZ')."/inc/pdf/pdflista.php"); 
	$order = $_GET['order'];
	$ordem = $_GET['ordem'];
	
	include_once(getenv('CAMINHO_RAIZ')."/repositories/parcelas/parcelas.db.php");
	
	$parcelasDB  = new parcelasDB();
		
	$file = "Lista parcelas";
	
	$filtros=array();
	
	if(isset($_REQUEST["filtro_contrato_id"])){$filtros['filtro_contrato_id'] = trim($_REQUEST["filtro_contrato_id"]);}
	if(isset($_REQUEST["filtro_per_ini"])){$filtros['filtro_per_ini'] = trim($_REQUEST["filtro_per_ini"]);}
	if(isset($_REQUEST["filtro_per_fim"])){$filtros['filtro_per_fim'] = trim($_REQUEST["filtro_per_fim"]);}
	if(isset($_REQUEST["filtro_tpcontrato"])){$filtros['filtro_tpcontrato'] = trim($_REQUEST["filtro_tpcontrato"]);}
	if(isset($_REQUEST["filtro_ted_id"])){$filtros['filtro_ted_id'] = trim($_REQUEST["filtro_ted_id"]);}	
	if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
	if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
	if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
	if(isset($_REQUEST["filtro_status_ct"])){$filtros['filtro_status_ct'] = trim($_REQUEST["filtro_status_ct"]);}
	if(isset($_REQUEST["tipo_operacao"])){$filtros['tipo_operacao'] = trim($_REQUEST["tipo_operacao"]);}
	if ($_SESSION['perfil_id'] == NULL){
		if ($filtros['tipo_operacao'] == 'compra') {
			$filtros['filtro_comprador'] = $_SESSION['id'];
			$filtros['filtro_vendedor'] = '';
		} else {
			$filtros['filtro_vendedor'] = $_SESSION['id'];
			$filtros['filtro_comprador'] = '';
		}
	}	
	
 
	
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'contrato':		
									$order = "ct.id ".$_REQUEST["ordem"].",";			
									break;
					case 'parcela':		
									$order = "p.nu_parcela ".$_REQUEST["ordem"].",";			
									break;
					case 'vencimento':		
									$order = "p.dt_vencimento ".$_REQUEST["ordem"].",";			
									break;
					case 'pagamento':		
									$order = "p.dt_credito ".$_REQUEST["ordem"].",";			
									break;
					case 'valor':		
									$order = "p.vl_parcela ".$_REQUEST["ordem"].",";			
									break;
					case 'correcao':		
									$order = "p.vl_correcao_monetaria ".$_REQUEST["ordem"].",";			
									break;
					case 'juros':		
									$order = "p.vl_juros ".$_REQUEST["ordem"].",";			
									break;
					case 'honor':		
									$order = "p.vl_honorarios ".$_REQUEST["ordem"].",";			
									break;
					case 'vlpago':		
									$order = "p.vl_pagto ".$_REQUEST["ordem"].",";			
									break;
					case 'corrigido':		
									$order = "p.vl_corrigido ".$_REQUEST["ordem"].",";			
									break;					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
	//Topo da página
	$cabecalho=array();
	$cabecalho[] = "imagem";
	$cabecalho[] = "Lista Parcelas ";
	
	//info adicional antes da tabela
	$info=array();
	$totais= $parcelasDB->lista_totais_parcelas($filtros,$conexao_BD_1);
	//print_r($totais); 
	$totais=$totais['totais'][0];
	$info[] = $totais['total_parcelas']." parcelas";
	$info[] = "Valor Parcelas: R$ ".Format($totais['vl_parcela'],'numero');   
	$info[] = "Valor Pagto: R$ ".Format($totais['vl_pagto'],'numero');
	$info[] = "Valor Honorários: R$ ".Format($totais['vl_honorarios'],'numero'); 
	
	//print_r($info); 

	$tamcolunas=array(40,50,30,30,30); //total = 207 ou 180 normal 
	 
	
	$head=array(utf8_decode('Parcela'),'Vendedor/Comprador','Valor', utf8_decode('Honorários'), 'Pagamento'); 
	
	
	// linhas 
	$body = $parcelasDB->lista_parcelas(  $conexao_BD_1,  $filtros   , $order ,   0,'N'); 
	$config_body = array();  // nome , limite tam linha (strlen), alinhamento , tipo (default, valor, data, fone)
	$config_body[] = array('parcelas ct_id nu_parcela dt_vencimento',5000,'L', ''); 
	$config_body[] = array('nome',5000,'L', '');  
	$config_body[] = array('corrigido',150,'C', 'valor');
	$config_body[] = array('vl_honorarios',150,'C', 'valor'); 
	$config_body[] = array('parcelas vl_pagto dt_credito',5000,'C', '');
	

	
	//observação adicional para tratamento específico
	$observacao = array(); 
	//extende página 

}
elseif($pagina == 'teds'){
	include_once(getenv('CAMINHO_RAIZ')."/inc/pdf/pdfted.php");
	
	include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.db.php");
	$tedsDB  = new tedsDB();
	
	//echo '<pre>'; print_r($_REQUEST);echo '</pre>';
	$ted_id = $_GET['id'];
	
	$file = "Detalhe TED ";


	
	//Topo da página
	$cabecalho=array();
	$cabecalho[] = "imagem";
	$cabecalho[] = "  Relatório TED ".$ted_id;
	
	//info adicional antes da tabela
	$info=array();
	
	//tamanho das colunas
	$tamcolunas=array(20,110,25,25); 
	 

	// linhas
	$body = $inicial =  array(); 
	$filtros['filtro_id'] = $ted_id; 
	$info    	= $tedsDB->lista_teds('', $conexao_BD_1, $filtros  ,  "" , $inicial = 0,'N');
	//print_r($info[0]); 
	$parcelas   	= $tedsDB->lista_parcelas_ted($ted_id, $conexao_BD_1);
	$lancamentos = $tedsDB->lista_lancamentos_ted($ted_id, $conexao_BD_1);
	
	pdfted($cabecalho,$info, $tamcolunas, $ted_id,$info[0],$parcelas,$lancamentos,$file,$link);
	exit;
} 

//********************************************************************************************************************************
	
pdflista($cabecalho,$info, $tamcolunas, $head,$body,$config_body,$observacao,$file,$link);

