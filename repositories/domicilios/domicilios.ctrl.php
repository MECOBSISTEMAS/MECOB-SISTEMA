<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;

include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/domicilios/domicilios.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php"); 



$msg 		= array();
$domiciliosDB  = new domiciliosDB();  

switch ($_REQUEST["acao"]) {
		
	case 'listar_domicilios':
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}  
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'vendedor':		
									$order = "pv.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'banco':		
									$order = "t.banco ".$_REQUEST["ordem"].",";			
									break;
					case 'agencia':		
									$order = "t.agencia ".$_REQUEST["ordem"].", dv_agencia ".$_REQUEST["ordem"].",";			
									break;
					case 'conta':		
									$order = "t.conta ".$_REQUEST["ordem"].", dv_conta ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$retorno = $domiciliosDB->lista_domicilios(  $conexao_BD_1,  $filtros, $order, $inicial);
		break;
		
	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}	  
		}
		
		$retorno = $domiciliosDB->lista_totais_domicilios($filtros,$conexao_BD_1);
		
		
		break;
		
}

echo  json_encode($retorno);