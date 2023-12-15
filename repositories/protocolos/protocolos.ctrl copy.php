<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Protocolos entrou ...' . $_REQUEST["acao"] );

include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_setor.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php"); // Dados da Conexao_DB

include_once($raiz . "/inc/util.php");


$msg 		      = array();
$protocolosDB     = new protocolosDB();
$protocolos       = new protocolos();
$reflection       = new ReflectionObject($protocolos);
$protocolos_setor = new protocolos_setor();

if(isset($_REQUEST["protocolos"])){
	$protocolos_request = $_REQUEST["protocolos"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($protocolos_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$protocolos->$aux_name = "$value[value]";
		}
		$aux_name = $value["name"];
		$obj_aux->$aux_name = "$value[value]";
		//echo "$key ... $value[name] - $value[value] <br>";
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Form nomes ' . $value["name"] . " " . $value["value"]);
	}
}

switch ($_REQUEST["acao"]) {

	case 'inserir':

		if ($protocolos->id = $conexao_BD_1->insert($protocolos)){

			$protocolos_setor->setor         = $protocolos->setor;
			$protocolos_setor->data          = $protocolos->dt_registro;
			$protocolos_setor->pessoas_id    = $protocolos->cad_pessoa;
			$protocolos_setor->protocolos_id = $protocolos->id;
			
			$ret = $conexao_BD_1->insert($protocolos_setor);

			$retorno = array( 'status' => $protocolos->id,	'msg'=> "Inserido com Sucesso!");				
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}					

		break;
	
	case 'atualizar':
		break;
	
	case 'troca_setor':
		$data_atual               = date('Y-m-d H:i:s' );
		$protocolos->id           = $_REQUEST['protocolo_id'];
		$protocolos->setor        = $_REQUEST['setor'];
		$protocolos->setor_trans  = $data_atual;
		$protocolos->trans_pessoa = $_REQUEST['usuario'];
		
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Protocolos setor ' . json_encode($_REQUEST));
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Protocolos setor ' . $protocolos->id);
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Protocolos setor ' . $protocolos->setor);

		if ($conexao_BD_1->update($protocolos)){
			// Grava na tabela protocolos_setor para registro
			$protocolos_setor->setor         = $protocolos->setor;
			$protocolos_setor->data          = $data_atual;
			$protocolos_setor->pessoas_id    = $protocolos->trans_pessoa;
			$protocolos_setor->protocolos_id = $protocolos->id;
			
			$ret = $conexao_BD_1->insert($protocolos_setor);
			$retorno = array( 'status' => 1, 'msg'=> "");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao trocar de setor!");
		}

		break;
		
	case 'finalizar':
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Protocolos setor ' . $_SESSION['id'] ." -- ". $_GET["id"]);

		$protocolos->id = $_REQUEST['protocolo_id'];
		$protocolos->finalizado = date('Y-m-d H:i:s' );
		$protocolos->finalizado_pessoa = $_REQUEST['usuario'];
		$protocolos->contrato_id = $_REQUEST['contrato_id'];
		$protocolos->status = 'Finalizado';

		$ret = $conexao_BD_1->update($protocolos);
		if ($ret){
			$retorno = array( 'status' => 1,	'msg'=> "");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha! Contrato ID duplicado! " );
		}

		break;
		
	case 'remover':

		$protocolos->id = $_REQUEST['protocolo_id'];
		$protocolos->enable = '0';

		$ret = $conexao_BD_1->update($protocolos);

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

		syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Protocolo-> ' . $_REQUEST['protocolo_id'] . " -> ". $protocolos->id);

		$ret = $conexao_BD_1->update($protocolos);
		
		if ($ret){
			$retorno = array( 'status' => 1,	'msg'=> "");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao cancelar o protocolo!" );
		}

		break;
		
	case 'listar':
	
		$retorno = $conexao_BD_1->select($protocolos);
		break;
		
	case 'lista_protocolos':

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
		}

		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Filtros ' . json_encode($filtros));

		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
									$order = "p.id ".$_REQUEST["ordem"].",";			
									break;
					case 'protocolo':		
									$order = "p.protocolo ".$_REQUEST["ordem"].",";			
									break;
					case 'vendedor':		
									$order = "p.vendedor ".$_REQUEST["ordem"].",";			
									break;
					case 'comprador':		
									$order = "p.comprador ".$_REQUEST["ordem"].",";			
									break;
					case 'evento':		
									$order = "p.evento ".$_REQUEST["ordem"].",";			
									break;
					case 'produto':		
									$order = "p.produto ".$_REQUEST["ordem"].",";			
									break;
					case 'prazo':		
									$order = "p.prazo ".$_REQUEST["ordem"].",";			
									break;
					case 'dt_lancamento':		
									$order = "p.dt_lancamento ".$_REQUEST["ordem"].",";			
									break;
					case 'valor':		
									$order = "p.valor ".$_REQUEST["ordem"].",";			
									break;
					case 'status':		
									$order = "p.status ".$_REQUEST["ordem"].",";			
									break;
					case 'setor':		
									$order = "p.setor ".$_REQUEST["ordem"].",";			
									break;
					case 'contrato_id':		
									$order = "p.contrato_id ".$_REQUEST["ordem"].",";			
									break;

					default:
							$order = '';	
					break;	
			}
			
		} else { 
			$order = '';
		}	
	
		$retorno = $protocolosDB->lista_protocolos($protocolos, $conexao_BD_1,  $filtros, $order, $inicial);

		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Proto CTRL ' . json_encode($filtros));

		break;

}

echo  json_encode($retorno);
