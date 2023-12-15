<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.db.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/crypt.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 			  = array();
$acesso_pessoaDB  = new acesso_pessoaDB();
$acesso_pessoa    = new acesso_pessoa();
$reflection 	  = new ReflectionObject($acesso_pessoa);

if(isset($_REQUEST["pessoa"])){
	$acesso_pessoa_request = $_REQUEST["acesso_pessoa"];
	foreach ($acesso_pessoa_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$acesso_pessoa->$aux_name = "$value[value]";
		}
		//echo "$key ... $value[name] - $value[value] <br>";	
     }
}

//print "<pre>";
//print_r(pessoas);
//
//print "<pre>";
//print_r($_FILES);
////exit;

switch ($_REQUEST["acao"]) {
	case 'atualizar':
		
		break;
	case 'inserir':
		
		break;
		
	case 'listar_acessos':
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_nome"])){$filtros['filtro_nome'] = trim($_REQUEST["filtro_nome"]);}
		  if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
		  
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'nome':		
									$order = "p.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'data':		
									$order = "ap.data ".$_REQUEST["ordem"].",";			
									break;
					case 'url':		
									$order = "ap.url ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		
		$retorno = $acesso_pessoaDB->lista_acesso_pessoa($acesso_pessoa, $conexao_BD_1,  $filtros, $order, $inicial);
		break;	
		
		
		
	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_nome"])){$filtros['filtro_nome'] = trim($_REQUEST["filtro_nome"]);}
		  if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
		  
		}
		$retorno = $acesso_pessoaDB->lista_totais_acessos($filtros,$conexao_BD_1);
		break;
		
		
}
echo  json_encode($retorno);
exit(); 
?>