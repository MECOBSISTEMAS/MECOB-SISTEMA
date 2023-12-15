<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;

if($_REQUEST["acao"] != 'atualiza_parcelas_2_via' &&  $_REQUEST["acao"] != 'lista_parcelas'){
	include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
}
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos_analitico/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos_analitico/contratos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		  = array();
$contratosDB  = new contratosDB();
// $parcelasDB  = new parcelasDB();
$contratos    = new contratos();

if(isset($_REQUEST["contratos"])){
	$contratos_request = $_REQUEST["contratos"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($contratos_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$contratos->$aux_name = "$value[value]";
		}
		$aux_name = $value["name"];
		$obj_aux->$aux_name = "$value[value]";
		//echo "$key ... $value[name] - $value[value] <br>";	
     }
}

$envia_email = 0;


switch ($_REQUEST["acao"]) {
	case 'lista_contratos':
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_contrato"])){$filtros['filtro_contrato'] = trim($_REQUEST["filtro_contrato"]);}
		  if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
		  if(isset($_REQUEST["filtro_data_fim"])){$filtros['filtro_data_fim'] = trim($_REQUEST["filtro_data_fim"]);} 
		  if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
		  if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
		  if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
		  if(isset($_REQUEST["filtro_id"])){$filtros['filtro_id'] = trim($_REQUEST["filtro_id"]);}
		  if(isset($_REQUEST["filtro_pagto"])){$filtros['filtro_pagto'] = trim($_REQUEST["filtro_pagto"]);}
		  if(isset($_REQUEST["filtro_zerado"])){$filtros['filtro_zerado'] = trim($_REQUEST["filtro_zerado"]);}
		  if(isset($_REQUEST["filtro_dia"])){$filtros['filtro_dia'] = trim($_REQUEST["filtro_dia"]);}
		  if(isset($_REQUEST["filtro_tipo"])){$filtros['filtro_tipo'] = trim($_REQUEST["filtro_tipo"]);}
		}
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
	
		$retorno = $contratosDB->lista_contratos($contratos, $conexao_BD_1,  $filtros, $order, $inicial);
		break;

}

if($envia_email){
	include_once(getenv('CAMINHO_RAIZ')."/repositories/email/email.db.php");
	$emailDB  = new emailDB();
	$emailDB->insert_send_mail($conexao_BD_1, $destinatarios, $assunto, $mensagem, "", "", "contato@mecob.com.br", "MECOB",9);
}
echo  json_encode($retorno);