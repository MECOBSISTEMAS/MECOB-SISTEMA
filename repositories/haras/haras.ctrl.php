<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/haras/haras.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/haras/haras.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$harasDB  = new harasDB();
$haras    = new haras();
$reflection 	= new ReflectionObject($haras);

if(isset($_REQUEST["haras"])){
	$haras_request = $_REQUEST["haras"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($haras_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$haras->$aux_name = "$value[value]";
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
	if(!is_numeric($haras->proprietario_id)){  $haras->proprietario_id = null; }
	if(strlen(trim($haras->proprietario_nome))==0){  $haras->proprietario_nome = ' '; }
	if(strlen(trim($haras->proprietario_doc))==0){  $haras->proprietario_doc = ' '; }
	#print_r($haras);
}



switch ($_REQUEST["acao"]) {
	case 'atualizar':
		#print_r($haras);
		if ($conexao_BD_1->update($haras)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	case 'inserir':
		#print_r($haras);
		if ($conexao_BD_1->insert($haras)){
			$retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;
	case 'listar':
		if(isset($_REQUEST["proprietario_id"]) && is_numeric($_REQUEST["proprietario_id"])){  
			$haras->proprietario_id = $_REQUEST["proprietario_id"]; 
		}
		$retorno = $conexao_BD_1->select($haras);
		break;
		
	case 'remover':
	
		$haras->id = $_REQUEST["id"];	
		if ($conexao_BD_1->delete($haras)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
		
	case 'listar_haras':
		
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_haras"])){$filtros['filtro_haras'] = trim($_REQUEST["filtro_haras"]);}
		  if(isset($_REQUEST["filtro_proprietario"])){$filtros['filtro_proprietario'] = trim($_REQUEST["filtro_proprietario"]);}
		  
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'nome':		
									$order = "h.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'contato':		
									$order = "h.contato ".$_REQUEST["ordem"].",";			
									break;
					case 'telefone':		
									$order = "h.telefone ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$retorno = $harasDB->lista_haras($haras, $conexao_BD_1,  $filtros, $order, $inicial);
		break;
		
	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_haras"])){$filtros['filtro_haras'] = trim($_REQUEST["filtro_haras"]);}
		  if(isset($_REQUEST["filtro_proprietario"])){$filtros['filtro_proprietario'] = trim($_REQUEST["filtro_proprietario"]);}
		}
		$retorno = $harasDB->lista_totais_haras($filtros,$conexao_BD_1);
		break;
	
		
	case 'remove_haras':
		$haras_id  = $_REQUEST["haras_id"];
		if ($harasDB->remover_haras($haras_id, $conexao_BD_1)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
	case 'remove_proprietario':
		$haras_id  = $_REQUEST["haras_id"];
		if ($harasDB->remover_proprietario($haras_id, $conexao_BD_1)){	
			$retorno = 1;	
		}
		else{
			$retorno = 0;	
		}
		break;
		
		
}
echo  json_encode($retorno);
exit(); 
?>