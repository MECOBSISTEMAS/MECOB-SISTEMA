<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/alertas/alertas.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/alertas/alertas.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$alertasDB  = new alertasDB();
$alertas    = new alertas();
$reflection 	= new ReflectionObject($alertas);

if(isset($_REQUEST["alertas"])){
	$alertas_request = $_REQUEST["alertas"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($alertas_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$alertas->$aux_name = "$value[value]";
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
	$alertas->data_alerta = date('Y-m-d H:i:s');
	$dt_prazo = '';
	foreach ($alertas_request as $key => $value) {
		if ($value['name'] == 'dt_prazo') {
			$dt_prazo = $value['value'];
		}
	}
	if ($dt_prazo){
		$dt_prazo = explode('/',$dt_prazo);
		$dt_prazo = $dt_prazo[2].'-'.$dt_prazo[1].'-'.$dt_prazo[0];
		$alertas->dt_prazo = $dt_prazo;
	} else {
		$alertas->dt_prazo = NULL;
	}
	$alertas->visualizado = 'N';
	if(strlen($alertas->link)){
		$alertas->link = url_to_link($alertas->link);
	}
}


switch ($_REQUEST["acao"]) {
	case 'inserir':
		#print_r($haras);
		if ($conexao_BD_1->insert($alertas)){
			$retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;
	case 'atualizar':
		#print_r($alertas);
		if ($conexao_BD_1->update($alertas)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	case 'listar':
		if(!empty($_REQUEST['pessoa_id_alerta']) && is_numeric($_REQUEST['pessoa_id_alerta'])){
			$alertas->pessoas_id_destino = $_REQUEST['pessoa_id_alerta'];
		}
		$retorno = $conexao_BD_1->select($alertas);
		break;
		
	case 'remover':
	
		$alertas->id = $_REQUEST["id"];	
		if ($conexao_BD_1->delete($alertas)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
		
	case 'lista_alertas':
		$inicial = 0;
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
			if(isset($_REQUEST["filtro_criador"])){$filtros['filtro_criador'] = trim($_REQUEST["filtro_criador"]);}
		  	if(isset($_REQUEST["filtro_destino"])){$filtros['filtro_destino'] = trim($_REQUEST["filtro_destino"]);}		  
		}
		else{$order = '';}	
	
		$retorno = $alertasDB->lista_alertas($alertas, $conexao_BD_1,  $filtros);
		break;
	case 'lista_alertas_usuario_ativo':
		$inicial = 0;
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
			if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
			if(isset($_REQUEST["filtro_per_ini"])){$filtros['filtro_per_ini'] = trim($_REQUEST["filtro_per_ini"]);}
			if(isset($_REQUEST["filtro_per_fim"])){$filtros['filtro_per_fim'] = trim($_REQUEST["filtro_per_fim"]);}
		}
		else{$order = '';}	

		// var_dump($filtros);
		// exit;
	
		$retorno = $alertasDB->lista_alertas_usuario_ativo($alertas, $conexao_BD_1,  $filtros);
		break;
	case 'load_more':
		$alertas->pessoas_id_destino =  $_REQUEST['pessoas_id'];
		$retorno = $alertasDB->lista_alertas($alertas, $conexao_BD_1,  '' , $_REQUEST['ja_carregados']);
		break;
	case 'see':		
		$alertasDB->visualizado($_REQUEST["pessoas_id"],  $conexao_BD_1);
		return 1;
		exit;
	case 'alerta_concluido':		
		$retorno = $alertasDB->concluido($_REQUEST["id_alerta"],  $conexao_BD_1); 
		break;
	case 'check_alertas':
		$alertas->pessoas_id_destino = $_SESSION['id'];
		$retorno = $alertasDB->lista_total_alertas($alertas,  $conexao_BD_1,''); 
		//$lista_alertas = $alertasDB->lista_alertas($alertas,  $conexao_BD_1,'',0);
		break;
	case 'check_alertas_atrasados':
		$alertas->pessoas_id_destino = $_SESSION['id'];
		$retorno = $alertasDB->lista_total_alertas_atrasados($alertas,  $conexao_BD_1,''); 
		//$lista_alertas = $alertasDB->lista_alertas($alertas,  $conexao_BD_1,'',0);
		break;
	case 'inserir_alerta':	
		$pessoas_id = $_SESSION['id'];
		$pessoas_id_destino = $_REQUEST['pessoas_id'];
		$contrato = $_REQUEST['contrato'];
		$motivoSuspensao = 'Motivo não informado';
		if (isset($_REQUEST['motivoSuspensao']))
			$motivoSuspensao = $_REQUEST['motivoSuspensao'];
		$alertas->pessoas_id_cadastro = $pessoas_id;
		$alertas->pessoas_id_destino = $pessoas_id_destino;
		$alertas->visualizado = 'N';
		$alertas->data_alerta = date('Y-m-d H:i:s');
		if (isset($_REQUEST['link'])) {
			$alertas->link = $_REQUEST['link'];
		} else {
			$alertas->link = "$link/adm/contratos/lista_contratos.php?id=$contrato&solicita_suspensao=S&motivo=$motivoSuspensao&pessoas_id=$pessoas_id";
		}
		$supervisor = $_SESSION['nome'];
		if (isset($_REQUEST['descricao'])) {
			$alertas->descricao = $_REQUEST['descricao'];
		} else {
			$alertas->descricao = "O supervisor $supervisor, solicitou a suspensão
			do contrato $contrato, informando o seguinte motivo: \n
			$motivoSuspensao \n
			Para aceitar ou recusar esta solicitação, clique nesta mensagem";			
		}
		if ($conexao_BD_1->insert($alertas)) {
			$retorno = array ('status' => 1);
		} else {
			$retorno = array ('status' => 0);
		}

		break;	
}
echo  json_encode($retorno);
exit(); 
?>