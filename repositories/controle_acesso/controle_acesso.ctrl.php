<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/controle_acesso/controle_acesso.db.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/crypt.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 			  = array();
$controle_acessoDB  = new controle_acessoDB();



switch ($_REQUEST["acao"]) {
	case 'atualizar':
		
		break;
	case 'inserir':
		
		break;
	case 'inserir_perfil':
		$retorno = $controle_acessoDB->inserir_perfil( $conexao_BD_1,  $_REQUEST["perfil"]);
		break;
	case 'remover_perfil':
		$retorno = $controle_acessoDB->remover_perfil( $conexao_BD_1,  $_REQUEST["id"]);
		break;
		
		
	case 'lista_perfil':
	
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_perfil"])){$filtros['filtro_perfil'] = trim($_REQUEST["filtro_perfil"]);}
		  
		}
		$retorno = $controle_acessoDB->lista_perfil( $conexao_BD_1,  $filtros);
		break;	
		
	case 'lista_modulo':
	
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_modulo"])){$filtros['filtro_modulo'] = trim($_REQUEST["filtro_modulo"]);}
		  
		}
		$retorno = $controle_acessoDB->lista_modulo( $conexao_BD_1,  $filtros);
		break;
		
	case 'lista_permissao':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_perfil"])){$filtros['filtro_perfil'] = trim($_REQUEST["filtro_perfil"]);}
		  if(isset($_REQUEST["filtro_modulo"])){$filtros['filtro_modulo'] = trim($_REQUEST["filtro_modulo"]);}
		  
		}
		$retorno = $controle_acessoDB->lista_permissao( $conexao_BD_1,  $filtros);
		break;
	
	case 'lista_controle':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_perfil"])){$filtros['filtro_perfil'] = trim($_REQUEST["filtro_perfil"]);}
		}
		$perfil    	 = $controle_acessoDB->lista_perfil( $conexao_BD_1, $filtros);
		$modulo    	 = $controle_acessoDB->lista_modulo( $conexao_BD_1, $filtros);
		$permissao      = $controle_acessoDB->lista_permissao( $conexao_BD_1, $filtros);
		
		echo json_encode(array('perfil'=>$perfil,'modulo'=>$modulo,'permissao'=>$permissao));
		exit;
		break;
	case 'seta_permissao':
		$controle_acessoDB->seta_permissao( $conexao_BD_1, $_REQUEST["modulo"],$_REQUEST["perfil"],$_REQUEST["permissao"]);
		exit;
		break;
	case 'remove_permissao':
		$controle_acessoDB->remove_permissao( $conexao_BD_1, $_REQUEST["modulo"],$_REQUEST["perfil"],$_REQUEST["permissao"]);
		exit;
		break;
		
		

		
		
}
echo  json_encode($retorno);
exit(); 
?>