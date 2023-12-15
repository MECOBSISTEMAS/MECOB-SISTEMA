<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;

include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/ted/gerar_arquivo_remessa_ted.php");



$msg 		= array();
$tedsDB  = new tedsDB();
$teds    = new teds();
$reflection 	= new ReflectionObject($teds);

if(isset($_REQUEST["teds"])){
	$teds_request = $_REQUEST["teds"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($teds_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$contratos->$aux_name = "$value[value]";
		}
		$aux_name = $value["name"];
		$obj_aux->$aux_name = "$value[value]";
		//echo "$key ... $value[name] - $value[value] <br>";	
     }
}


switch ($_REQUEST["acao"]) {
	case 'del_domc':
		$domicilio = $_REQUEST['domicilio'];
		$tedsDB->del_domc($domicilio,  $conexao_BD_1);
		break;
	case 'del_ted':
		$ted_id = $_REQUEST['ted_id'];
		if($tedsDB->remove_ted($ted_id,  $conexao_BD_1)){
			$retorno = array( 'status' => 1,	'msg'=> "TED removida com Sucesso!");
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Problema ao remover TED!");
		}
		break;
	case 'zerar_parcelas_ted': 
		 
		 $id_vend= $_REQUEST['id_vend']; 
		 //recupera parcelas
		 $teds_parcs    = new teds();
		 $teds_parcs->pessoas_id_vendedor = $id_vend;
		 $parcelas_teds = $tedsDB->lista_parcelas_teds($teds_parcs, $conexao_BD_1,  $id_vend  ,  "t.id desc," ,  0,"N");
		 foreach($parcelas_teds as $parcela){  
			$ids_parcelas[]=$parcela['id'];
		 }
		
		  
		 $teds->pessoas_id_vendedor = $_REQUEST['id_vend'];
		 $teds->pessoas_id_inclusao = $_SESSION["id"];
		 $teds->dt_inclusao = date("Y-m-d H:i:s");
		 $teds->dt_ted = date('Y-m-d');
		 $teds->vl_ted = 0; 
		 $teds->status_ted = 3;
		 $teds->banco = 1;
		 $teds->agencia =  1;
		 $teds->conta =1; 
		 $teds->dv_agencia =  1;
		 $teds->dv_conta =1; 
		 $teds->del_domc_bancario  = 1;
		 $teds->log_zerar = 'TED zerada - repasse das parcelas controlado por fora.'; 
		
		#print_r($teds);
		if ($teds->id = $conexao_BD_1->insert($teds)){
			$parcelas_ok = $lancamentos_ok =0;
			if( $tedsDB->atualiza_parcelas_teds($teds->id, $ids_parcelas, $conexao_BD_1)){
				//ted OK
				$parcelas_ok=1;
			}
			else{
				$retorno = array( 'status' => 0,	'msg'=> "Problema ao relacionar parcelas com a TED"	);
			}
					
			if($parcelas_ok   ){
				$retorno = array( 'status' => $teds->id,	'msg'=> "Inserido com Sucesso!");
			}
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Problema ao gravar TED"	);
		}
		
		
		break;
	case 'nova_ted':
			
		 $valor_ted= $_REQUEST['valor_ted'];
		 $data_ted= $_REQUEST['data_ted'];
		 $banco_ted= $_REQUEST['banco_ted'];
		 $agencia_ted= $_REQUEST['agencia_ted'];
		 $conta_ted= $_REQUEST['conta_ted'];
		 $id_vend= $_REQUEST['id_vend'];
		 
		 $agencia_dig= $_REQUEST['agencia_dig'];
		 $conta_dig= $_REQUEST['conta_dig'];
		
		 $teds->pessoas_id_vendedor = $_REQUEST['id_vend'];
		 $teds->pessoas_id_inclusao = $_SESSION["id"];
		 $teds->dt_inclusao = date("Y-m-d H:i:s");
		 $teds->dt_ted = ConverteData($data_ted);
		 $teds->vl_ted = trim($valor_ted);
		 $teds->status_ted = 1;
		 if($valor_ted == 0)
		 	$teds->status_ted = 3;
		 $teds->banco = trim($banco_ted);
		 $teds->agencia =  trim($agencia_ted);
		 $teds->conta = trim($conta_ted);
		 
		 $teds->dv_agencia =  trim($agencia_dig);
		 $teds->dv_conta = trim($conta_dig);
		
		#print_r($teds);
		if ($teds->id = $conexao_BD_1->insert($teds)){

            if($valor_ted > 0)
				gerar_arquivo_remessa_ted($conexao_BD_1, $teds->id);
				

            //update parcelas e grava lancamentos
			$cont_lancamentos=0;
			$ids_parcelas = array();
			$arr_lancamentos = array();
			
			$ted_request = $_REQUEST["ted_form"];
			$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
			foreach ($ted_request as $key=>$value) {
					$aux_name = $value["name"];
					if(substr($aux_name,0,3)=='pc_'){
						$ids_parcelas[]=substr($aux_name,3);
					}
					else{
						if($aux_name=='inputLcValor'){
							$arr_lancamentos[$cont_lancamentos]['inputLcValor'] = "$value[value]";
						}
						elseif($aux_name=='inputLcTipo'){
							$arr_lancamentos[$cont_lancamentos]['inputLcTipo'] = "$value[value]";
						}
						elseif($aux_name=='inputLcObs'){
							$arr_lancamentos[$cont_lancamentos]['inputLcObs'] = "$value[value]";
							$cont_lancamentos++;
						}
					}
			 }
			 
			 
			$ted_request_parc = $_REQUEST["form_ted_parcelas"]; 
			if(!is_array($ted_request_parc) && $ted_request_parc == 'todas_parcelas'){
				//recupera ids de todas parcelas em aberto
				$teds_parcs    = new teds();
				$teds_parcs->pessoas_id_vendedor = $id_vend;
				$parcelas_teds = $tedsDB->lista_parcelas_teds($teds_parcs, $conexao_BD_1,  $id_vend  ,  "t.id desc," ,  0,"N");
				foreach($parcelas_teds as $parcela){  
					$ids_parcelas[]=$parcela['id'];
				}
			}
			else{
				//recupera id de cada parcela
				foreach ($ted_request_parc as $key=>$value) {
						$aux_name = $value["name"];
						if(substr($aux_name,0,3)=='pc_'){
							$ids_parcelas[]=substr($aux_name,3);
						} 
				}
			}
			 
//			 //ATUALIZA PARCELAS COM O ID DA TED	 
//			
//						echo '<pre>';
//						print_r($ids_parcelas);
//						echo '</pre>';
//			
//			 //INSERIR LANÇAMENTOS
//				
//						echo '<pre>';
//						print_r($arr_lancamentos);
//						echo '</pre>';  
			
			$parcelas_ok = $lancamentos_ok =0;
			if( $tedsDB->atualiza_parcelas_teds($teds->id, $ids_parcelas, $conexao_BD_1)){
				//ted OK
				$parcelas_ok=1;
			}
			else{
				$retorno = array( 'status' => 0,	'msg'=> "Problema ao relacionar parcelas com a TED"	);
			}
			
			if( $tedsDB->insert_lancamentos_teds($teds->id, $arr_lancamentos, $conexao_BD_1) ){
				//lancamentos OK
				$lancamentos_ok =1;
			}
			else{
				$retorno = array( 'status' => 0,	'msg'=> "Problema ao gravar lancamentos da TED"	);
			}
			
			if($parcelas_ok &&$lancamentos_ok ){
				$retorno = array( 'status' => $teds->id,	'msg'=> "Inserido com Sucesso!");
			}
	 		
	 
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;
	
	case 'atualizar':
		#print_r($teds);
		if ($conexao_BD_1->update($teds)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");	
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	
	case 'listar':
	
		$retorno = $conexao_BD_1->select($teds);
		break;
		
	case 'listar_teds':
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_dt_inclusao"])){$filtros['filtro_dt_inclusao'] = trim($_REQUEST["filtro_dt_inclusao"]);}
		  if(isset($_REQUEST["filtro_per_ini"])){$filtros['filtro_per_ini'] = trim($_REQUEST["filtro_per_ini"]);}
		  if(isset($_REQUEST["filtro_per_fim"])){$filtros['filtro_per_fim'] = trim($_REQUEST["filtro_per_fim"]);}
		  if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
		  if(isset($_REQUEST["filtro_id"])){$filtros['filtro_id'] = trim($_REQUEST["filtro_id"]);}	
		  if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}	  
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
									$order = "t.id ".$_REQUEST["ordem"].",";			
									break;
					case 'agendada':		
									$order = "t.dt_ted ".$_REQUEST["ordem"].",";			
									break;
					case 'inclusao':		
									$order = "t.dt_inclusao ".$_REQUEST["ordem"].",";			
									break;
					case 'valor':		
									$order = "t.vl_ted ".$_REQUEST["ordem"].",";			
									break;
					case 'lancamentos':		
									$order = "tt_lancamentos ".$_REQUEST["ordem"].",";			
									break;
					case 'status':		
									$order = "t.status_ted ".$_REQUEST["ordem"].",";			
									break;
					case 'nome':		
									$order = "pv.nome ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$retorno = $tedsDB->lista_teds($teds, $conexao_BD_1,  $filtros, $order, $inicial);
		break;
		
	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_dt_inclusao"])){$filtros['filtro_dt_inclusao'] = trim($_REQUEST["filtro_dt_inclusao"]);}
		  if(isset($_REQUEST["filtro_per_ini"])){$filtros['filtro_per_ini'] = trim($_REQUEST["filtro_per_ini"]);}
		  if(isset($_REQUEST["filtro_per_fim"])){$filtros['filtro_per_fim'] = trim($_REQUEST["filtro_per_fim"]);}
		  if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
		  if(isset($_REQUEST["filtro_id"])){$filtros['filtro_id'] = trim($_REQUEST["filtro_id"]);}	
		  if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}	  
		}
		
		$retorno = $tedsDB->lista_totais_teds($filtros,$conexao_BD_1);
		
		
		break;
	
	case 'lancamentos_ted':
		$ted_id = trim($_REQUEST["ted_id"]); 
		$retorno = array();
		$retorno['lanc']= $tedsDB->lista_lancamentos_ted($ted_id,$conexao_BD_1);
		$retorno['parc']= $tedsDB->lista_parcelas_ted($ted_id,$conexao_BD_1);
		
		break;
		
		

		
}

echo  json_encode($retorno);