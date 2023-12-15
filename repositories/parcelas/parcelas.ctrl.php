<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);

include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/parcelas/parcelas.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$parcelasDB  = new parcelasDB();


switch ($_REQUEST["acao"]) {
	// case 'parcelas_entre_datas_contrato':
	// 	$data_inicial = $_REQUEST["inicio"];
	// 	$data_final = $_REQUEST["fim"];
	// 	$contrato = $_REQUEST["contrato"];
	// 	$parcelasDB->parcelas_entre_datas_contrato($conexao_BD_1, $contrato, $data_inicial, $data_final);
	// break;
	case 'listar_parcelas':
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
			if(isset($_REQUEST["filtro_contrato_id"])){$filtros['filtro_contrato_id'] = trim($_REQUEST["filtro_contrato_id"]);}
			if(isset($_REQUEST["filtro_per_ini"])){$filtros['filtro_per_ini'] = trim($_REQUEST["filtro_per_ini"]);}
			if(isset($_REQUEST["filtro_per_fim"])){$filtros['filtro_per_fim'] = trim($_REQUEST["filtro_per_fim"]);}
			if(isset($_REQUEST["filtro_tpcontrato"])){$filtros['filtro_tpcontrato'] = trim($_REQUEST["filtro_tpcontrato"]);}
			if(isset($_REQUEST["filtro_ted_id"])){$filtros['filtro_ted_id'] = trim($_REQUEST["filtro_ted_id"]);}	
			if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
			if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
			if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
			if(isset($_REQUEST["filtro_status_ct"])){$filtros['filtro_status_ct'] = trim($_REQUEST["filtro_status_ct"]);}
			if(isset($_REQUEST["filtro_dia"])){$filtros['filtro_dia'] = trim($_REQUEST["filtro_dia"]);}
			if(isset($_REQUEST["filtro_descricao"])){$filtros['filtro_descricao'] = trim($_REQUEST["filtro_descricao"]);}
		
		}
		// var_dump($_SESSION['perfil_id']);
		// exit;
		if ($_SESSION['perfil_id'] == NULL){
			if ($filtros['filtro_vendedor'] != $_SESSION['id'] && $filtros['filtro_comprador'] != $_SESSION['id']) {
				$filtros['filtro_vendedor'] = $_SESSION['id'];
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
									$order = "p.dt_pagto ".$_REQUEST["ordem"].",";			
									break;
					case 'credito':		
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
	
		$retorno = $parcelasDB->lista_parcelas( $conexao_BD_1,  $filtros, $order, $inicial);
		break;
		
	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
			if(isset($_REQUEST["filtro_contrato_id"])){$filtros['filtro_contrato_id'] = trim($_REQUEST["filtro_contrato_id"]);}
			if(isset($_REQUEST["filtro_per_ini"])){$filtros['filtro_per_ini'] = trim($_REQUEST["filtro_per_ini"]);}
			if(isset($_REQUEST["filtro_per_fim"])){$filtros['filtro_per_fim'] = trim($_REQUEST["filtro_per_fim"]);}
			if(isset($_REQUEST["filtro_tpcontrato"])){$filtros['filtro_tpcontrato'] = trim($_REQUEST["filtro_tpcontrato"]);}
			if(isset($_REQUEST["filtro_ted_id"])){$filtros['filtro_ted_id'] = trim($_REQUEST["filtro_ted_id"]);}	
			if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
			if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
			if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
			if(isset($_REQUEST["filtro_status_ct"])){$filtros['filtro_status_ct'] = trim($_REQUEST["filtro_status_ct"]);}
			if(isset($_REQUEST["filtro_dia"])){$filtros['filtro_dia'] = trim($_REQUEST["filtro_dia"]);}
			if(isset($_REQUEST["filtro_descricao"])){$filtros['filtro_descricao'] = trim($_REQUEST["filtro_descricao"]);}		    
		}

		if (!$_SESSION['perfil_id']){
			if ($filtros['filtro_vendedor'] != $_SESSION['id'] && $filtros['filtro_comprador'] != $_SESSION['id']) {
				$filtros['filtro_comprador'] = $_SESSION['id'];
			}
		}
		$retorno = $parcelasDB->lista_totais_parcelas($filtros,$conexao_BD_1);
		
		
		break;
	

		

		
}

echo  json_encode($retorno);