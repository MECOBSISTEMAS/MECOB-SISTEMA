<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$is_pagina_perfil=1;
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Protocolos entrou ...' . $_REQUEST["acao"] );
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_ocorrencias.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php"); // Dados da Conexao_DB

include_once($raiz . "/inc/util.php");

$msg 		      = array();
$protocolosDB     = new protocolosDB();
$ocorrencias      = new protocolos_eventos();
$reflection       = new ReflectionObject($ocorrencias);

if(isset($_REQUEST["ocorrencias"])){
	$ocorrencias_request = $_REQUEST["ocorrencias"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($ocorrencias_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$ocorrencias->$aux_name = "$value[value]";
		}
		$aux_name = $value["name"];
		$obj_aux->$aux_name = "$value[value]";
		//echo "$key ... $value[name] - $value[value] <br>";
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Form nomes: ' . $value["name"] . " -> " . $value["value"]);
	}
}

switch ($_REQUEST["acao"]) {

	case 'inserir':
		// Registra a data e hora atual.
		$ocorrencias->data = date('Y-m-d H:i:s' );
		
		if ($ocorrencias->id = $conexao_BD_1->insert($ocorrencias)){
			$retorno = array( 'status' => $ocorrencias->id,	'msg'=> "Inserido com Sucesso!");				
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}					

		break;
	
	case 'atualizar':
		break;

	case 'listar':
		break;
		
	case 'lista_ocorrencias':

		$protocolos_id = $_REQUEST['protocolos_id'];

		$ocorrencias  = $protocolosDB->lista_ocorrencias($conexao_BD_1 , $protocolos_id );

		$retorno = array( 	'status' => '1',	'msg'=> "Sucesso!",
							'ocorrencias' => $ocorrencias,
		);	

		break;

}

echo  json_encode($retorno);
