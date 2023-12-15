<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/lotes/lotes.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/lotes/lotes.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$lotesDB  = new lotesDB();
$lotes    = new lotes();
$reflection 	= new ReflectionObject($lotes);

if(isset($_REQUEST["lotes"])){
	$lotes_request = $_REQUEST["lotes"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($lotes_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$lotes->$aux_name = "$value[value]";
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
	if($lotes->dt_nascimento!='') $lotes->dt_nascimento = ConverteData($lotes->dt_nascimento);
}


switch ($_REQUEST["acao"]) {
	case 'inserir':
		#print_r($haras);
		if ($conexao_BD_1->insert($lotes)){
			$retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;
	case 'atualizar':
		#print_r($lotes);
		if ($conexao_BD_1->update($lotes)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	case 'listar':
	
		$retorno = $conexao_BD_1->select($lotes);
		break;
		
	case 'remover':
	
		$lotes->id = $_REQUEST["id"];	
		if ($conexao_BD_1->delete($lotes)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
		
	case 'lista_lotes':
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_lotes"])){$filtros['filtro_lotes'] = trim($_REQUEST["filtro_lotes"]);}
		  if(isset($_REQUEST["filtro_tipo"])){$filtros['filtro_tipo'] = trim($_REQUEST["filtro_tipo"]);}
		  
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'nome':		
									$order = "l.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'registro':		
									$order = "l.num_registro ".$_REQUEST["ordem"].",";			
									break;
					case 'data':		
									$order = "l.dt_nascimento ".$_REQUEST["ordem"].",";			
									break;
					case 'tipo':		
									$order = "l.tipo ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$retorno = $lotesDB->lista_lotes($lotes, $conexao_BD_1,  $filtros, $order, $inicial);
		break;
		
	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_lotes"])){$filtros['filtro_lotes'] = trim($_REQUEST["filtro_lotes"]);}
		  if(isset($_REQUEST["filtro_tipo"])){$filtros['filtro_tipo'] = trim($_REQUEST["filtro_tipo"]);}
		  
		}
		$retorno = $lotesDB->lista_totais_lotes($filtros,$conexao_BD_1);
		break;
		
	case 'remove_lotes':
		$lotes_id  = $_REQUEST["lotes_id"];
		if ($lotesDB->remover_lotes($lotes_id, $conexao_BD_1)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;




		
		
		
}
echo  json_encode($retorno);
exit(); 
?>