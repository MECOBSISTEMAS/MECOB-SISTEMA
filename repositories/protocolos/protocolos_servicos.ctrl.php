<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');

include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_servicos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_servicos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php"); // Dados da Conexao_DB

include_once($raiz . "/inc/util.php");

$msg 		         = array();
$protocolosDB        = new protocolosDB();
$protocolos_servicos = new protocolos_servicos();
$reflection          = new ReflectionObject($protocolos_servicos);

if(isset($_REQUEST["protocolos_servicos"])){
	$protocolos_request = $_REQUEST["protocolos_servicos"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($protocolos_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$protocolos_servicos->$aux_name = "$value[value]";
		}
		$aux_name = $value["name"];
		$obj_aux->$aux_name = "$value[value]";
	}
}

switch ($_REQUEST["acao"]) {

	case 'inserir':

		if ($protocolos_servicos->id = $conexao_BD_1->insert($protocolos_servicos)){

			$retorno = array( 'status' => $protocolos_servicos->id,	'msg'=> "Inserido com Sucesso!");				
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}					

		break;
	
	case 'atualizar':
		if ($conexao_BD_1->update($protocolos_servicos)){
				$retorno = array( 'status' => 1,        'msg'=> "Atualizado com Sucesso!");                     
		} else {
				$retorno = array( 'status' => 0,        'msg'=> "Falha ao suspender o contrato!");
		}

		break;
	
	case 'remover':

		$protocolos_servicos->id        = $_REQUEST['id'];
		$protocolos_servicos->enable    = '0';
		$protocolos_servicos->pessoa_id = $_REQUEST['usuario'];

		$ret = $conexao_BD_1->update($protocolos_servicos);

		if ($ret){
			$retorno = array( 'status' => 1,	'msg'=> "");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao remover o protocolo!" );
		}

		break;
		
	case 'cancelar':

		$protocolos->id = $_REQUEST['protocolo_id'];
		$protocolos->finalizado = date('Y-m-d H:i:s');
		$protocolos->finalizado_pessoa = $_REQUEST['usuario'];
		$protocolos->finalizado_motivo = $_REQUEST['motivo'];
		$protocolos->status = 'Cancelado';

		$ret = $conexao_BD_1->update($protocolos);
		
		if ($ret){
			$retorno = array( 'status' => 1,	'msg'=> "");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao cancelar o protocolo!" );
		}

		break;
		
	case 'listar':
	
		$retorno = $conexao_BD_1->select($protocolos_servicos);
		break;
		
	case 'lista_protocolos_servicos':

		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];

		// Filtros
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1) {
			if(isset($_REQUEST['filtro_vendedor'])) {$filtros['filtro_vendedor'] = trim($_REQUEST['filtro_vendedor']);}			
			if(isset($_REQUEST['filtro_tipo'])) {$filtros['filtro_tipo'] = trim($_REQUEST['filtro_tipo']);}
		}

		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' -CTRL Filtros' . json_encode($_REQUEST['filtro_tipo']));

		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
									$order = "ps.id ".$_REQUEST["ordem"].",";			
									break;
					case 'nome':		
									$order = "ps.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'tipo':		
									$order = "ps.tipo ".$_REQUEST["ordem"].",";			
									break;
					case 'enviado':		
									$order = "ps.enviado ".$_REQUEST["ordem"].",";			
									break;
					case 'recebido':		
									$order = "ps.recebido ".$_REQUEST["ordem"].",";			
									break;
					case 'digitalizado':		
									$order = "ps.digitalizado ".$_REQUEST["ordem"].",";			
									break;
					case 'fisico':		
									$order = "ps.fisico ".$_REQUEST["ordem"].",";			
									break;

					default:
							$order = '';	
					break;	
			}
			
		} else { 
			$order = '';
		}	
	
		$retorno = $protocolosDB->lista_protocolos_servicos($protocolos_servicos, $conexao_BD_1,  $filtros, $order, $inicial);

		break;

	case 'lista_totais':

		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];

		// Filtros
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1) {
			if(isset($_REQUEST['filtro_protocolo_id'])) {$filtros['filtro_protocolo_id'] = trim($_REQUEST['filtro_protocolo_id']);}
			if(isset($_REQUEST['filtro_data'])) {$filtros['filtro_data'] = trim($_REQUEST['filtro_data']);}
			if(isset($_REQUEST['filtro_prazo'])) {$filtros['filtro_prazo'] = trim($_REQUEST['filtro_prazo']);}
			if(isset($_REQUEST['filtro_status'])) {$filtros['filtro_status'] = trim($_REQUEST['filtro_status']);}			
			if(isset($_REQUEST['filtro_setor'])) {$filtros['filtro_setor'] = trim($_REQUEST['filtro_setor']);}			
			if(isset($_REQUEST['filtro_vencimento'])) {$filtros['filtro_vencimento'] = trim($_REQUEST['filtro_vencimento']);}			
			if(isset($_REQUEST['filtro_vendedor'])) {$filtros['filtro_vendedor'] = trim($_REQUEST['filtro_vendedor']);}			
			if(isset($_REQUEST['filtro_comprador'])) {$filtros['filtro_comprador'] = trim($_REQUEST['filtro_comprador']);}			
			if(isset($_REQUEST['filtro_evento'])) {$filtros['filtro_evento'] = trim($_REQUEST['filtro_evento']);}			
			if(isset($_REQUEST['filtro_produto'])) {$filtros['filtro_produto'] = trim($_REQUEST['filtro_produto']);}			
		}

		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
									$order = "ps.id ".$_REQUEST["ordem"].",";			
									break;
					case 'protocolo':		
									$order = "ps.protocolo ".$_REQUEST["ordem"].",";			
									break;
					case 'vendedor':		
									$order = "ps.vendedor ".$_REQUEST["ordem"].",";			
									break;
					case 'comprador':		
									$order = "ps.comprador ".$_REQUEST["ordem"].",";			
									break;
					case 'evento':		
									$order = "ps.evento ".$_REQUEST["ordem"].",";			
									break;
					case 'produto':		
									$order = "ps.produto ".$_REQUEST["ordem"].",";			
									break;
					case 'prazo':		
									$order = "ps.prazo ".$_REQUEST["ordem"].",";			
									break;
					case 'dt_registro':		
									$order = "ps.dt_registro ".$_REQUEST["ordem"].",";			
									break;
					case 'valor':		
									$order = "ps.valor ".$_REQUEST["ordem"].",";			
									break;
					case 'status':		
									$order = "ps.status ".$_REQUEST["ordem"].",";			
									break;
					case 'setor':		
									$order = "ps.setor ".$_REQUEST["ordem"].",";			
									break;
					case 'contrato_id':		
									$order = "ps.contrato_id ".$_REQUEST["ordem"].",";			
									break;

					default:
							$order = '';	
					break;	
			}
			
		} else { 
			$order = '';
		}	
	
		$retorno = $protocolosDB->lista_totais($protocolos, $conexao_BD_1,  $filtros, $order, $inicial);

		break;

}

echo  json_encode($retorno);
