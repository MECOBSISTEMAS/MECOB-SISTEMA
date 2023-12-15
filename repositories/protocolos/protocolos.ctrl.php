<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');

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
		if ($conexao_BD_1->update($protocolos)){
				$retorno = array( 'status' => 1,        'msg'=> "Atualizado com Sucesso!");                     
		} else {
				$retorno = array( 'status' => 0,        'msg'=> "Falha ao suspender o contrato!");
		}

		break;
	
	case 'troca_setor':
		$data_atual               = date('Y-m-d H:i:s' );
		$protocolos->id           = $_REQUEST['protocolo_id'];
		$protocolos->setor        = $_REQUEST['setor'];
		$protocolos->setor_trans  = $data_atual;
		$protocolos->trans_pessoa = $_REQUEST['usuario'];

		if($protocolos->setor == 'Contratos') {
			$protocolos->ct_verifica = 1;
		}
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
		
	case 'mover_contrato':

			// Busca a observação
			$consulta = $protocolosDB->busca_protocolo($conexao_BD_1, $_REQUEST['protocolo_id']);

			$protocolos->id = $_REQUEST['protocolo_id'];
			$protocolos->finalizado = 'NULL';
			$protocolos->finalizado_pessoa = 'NULL';
			$protocolos->status = 'Pendente';
			$protocolos->contrato_id = 'NULL';
			$protocolos->setor = 'Contratos';
			$protocolos->ct_verifica = 1;

			$protocolos->observacao = $consulta[0]['observacao'] . 
										'<br>Contrato ID ' . $_REQUEST['contrato_id'] .
										'<br>Reaberto em ' . date('d/m/Y H:i:s' );
	
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
			if(isset($_REQUEST['filtro_vendedor'])) {$filtros['filtro_vendedor'] = trim($_REQUEST['filtro_vendedor']);}			
			if(isset($_REQUEST['filtro_comprador'])) {$filtros['filtro_comprador'] = trim($_REQUEST['filtro_comprador']);}			
			if(isset($_REQUEST['filtro_evento'])) {$filtros['filtro_evento'] = trim($_REQUEST['filtro_evento']);}			
			if(isset($_REQUEST['filtro_produto'])) {$filtros['filtro_produto'] = trim($_REQUEST['filtro_produto']);}			
		}

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
					case 'dt_contrato':		
									$order = "p.dt_contrato ".$_REQUEST["ordem"].",";			
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
	
		$retorno = $protocolosDB->lista_totais($protocolos, $conexao_BD_1,  $filtros, $order, $inicial);

		break;

}

echo  json_encode($retorno);
