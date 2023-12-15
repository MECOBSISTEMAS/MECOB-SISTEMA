<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/eventos/eventos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/eventos/eventos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$eventosDB  = new eventosDB();
$eventos    = new eventos();
$reflection 	= new ReflectionObject($eventos);

if(isset($_REQUEST["eventos"])){
	$eventos_request = $_REQUEST["eventos"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($eventos_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$eventos->$aux_name = "$value[value]";
		}
		$aux_name = $value["name"];
		$obj_aux->$aux_name = "$value[value]";
		//echo "$key ... $value[name] - $value[value] <br>";	
     }
}

//print "<pre>";
//print_r($obj_aux);
//exit;

#print_r($_REQUEST);

if($_REQUEST["acao"] == 'atualizar' || $_REQUEST["acao"] == 'inserir'){
	if($eventos->dt_evento!='') $eventos->dt_evento = ConverteData($eventos->dt_evento);
}


switch ($_REQUEST["acao"]) {
	case 'inserir':
		#print_r($haras);
		if ($conexao_BD_1->insert($eventos)){
			$retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;	
	case 'atualizar':
		#print_r($eventos);
		if ($conexao_BD_1->update($eventos)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	case 'listar':
	
		$retorno = $conexao_BD_1->select($eventos);
		break;
		
	case 'remover':
	
		$eventos->id = $_REQUEST["id"];	
		if ($conexao_BD_1->delete($eventos)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
		
	case 'listar_eventos':
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_eventos"])){$filtros['filtro_eventos'] = trim($_REQUEST["filtro_eventos"]);}
		  if(isset($_REQUEST["filtro_tipo"])){$filtros['filtro_tipo'] = trim($_REQUEST["filtro_tipo"]);}
		  if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);} 
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'nome':		
									$order = "e.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'data':		
									$order = "e.dt_evento ".$_REQUEST["ordem"].",";			
									break;
					case 'tipo':		
									$order = "e.tipo ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$retorno = $eventosDB->lista_eventos($eventos, $conexao_BD_1,  $filtros, $order, $inicial);
		break;

	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_eventos"])){$filtros['filtro_eventos'] = trim($_REQUEST["filtro_eventos"]);}
		  if(isset($_REQUEST["filtro_tipo"])){$filtros['filtro_tipo'] = trim($_REQUEST["filtro_tipo"]);}
		  if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
		}
		$retorno = $eventosDB->lista_totais_eventos($filtros,$conexao_BD_1);
		break;
				
	case 'remove_eventos':
		$evento_id  = $_REQUEST["evento_id"];
		if ($eventosDB->remover_eventos($evento_id, $conexao_BD_1)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removida com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;




		
		
		
}
echo  json_encode($retorno);
exit(); 
?>