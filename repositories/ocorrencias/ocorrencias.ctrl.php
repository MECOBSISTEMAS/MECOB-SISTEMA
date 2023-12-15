<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/ocorrencias/ocorrencias.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/ocorrencias/ocorrencias.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/alertas/alertas.class.php");
$alertas  = new alertas();
include_once(getenv('CAMINHO_RAIZ')."/repositories/alertas/alertas.db.php");
$alertasDB  = new alertasDB();

$msg 		= array();
$ocorrenciasDB  = new ocorrenciasDB();
$ocorrencias    = new ocorrencias();
$reflection 	= new ReflectionObject($ocorrencias);

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
     }
}

//print "<pre>";
//print_r($obj_aux);
//exit;

#print_r($_REQUEST);
if($_REQUEST["acao"] == 'atualizar' || $_REQUEST["acao"] == 'inserir'){
	$ocorrencias->data_ocorrencia = date('Y-m-d H:i:s');
	$ocorrencias->pessoas_id = $_SESSION['id'];
}


switch ($_REQUEST["acao"]) {
	case 'inserir':
		#print_r($haras);
		if ($conexao_BD_1->insert($ocorrencias)){
			$retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;
	case 'atualizar':
		#print_r($ocorrencias);
		if ($conexao_BD_1->update($ocorrencias)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	case 'listar':
	
		$retorno = $conexao_BD_1->select($ocorrencias);
		break;
	case 'listar_ultima_ocorrencia_contrato':
		
		$retorno = $ocorrenciasDB->select_last_ocor_contrato($_REQUEST['contrato_id'], $conexao_BD_1);
		break;
		
	case 'remover':
	
		$ocorrencias->id = $_REQUEST["id"];	
		if ($conexao_BD_1->delete($ocorrencias)){	
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
		
	case 'lista_ocorrencias':
	
		$contrato_id = $_REQUEST['contrato_id'];
		
		$comprador    	= $ocorrenciasDB->comprador_do_contrato( $contrato_id , $conexao_BD_1);
		$vendedor    	= $ocorrenciasDB->vendedor_do_contrato( $contrato_id , $conexao_BD_1);
		
		$ocorrencias 	= $ocorrenciasDB->lista_ocorrencias($conexao_BD_1 , $comprador['id'], $vendedor['id']  );
		$dividas		 = $ocorrenciasDB->dividas_comprador( $conexao_BD_1,  $contrato_id, $comprador['id']);
				
		echo json_encode(	array(  'comprador'=>$comprador,
									'vendedor'=>$vendedor,
									'ocorrencias'=>$ocorrencias, 
									'dividas'=>$dividas
									)
						);
		exit;
		break;	

		case 'lista_ocorrencias_id':
	
			$contrato_id = $_REQUEST['contrato_id'];
			
			$comprador    	= $ocorrenciasDB->comprador_do_contrato( $contrato_id , $conexao_BD_1);
			$vendedor    	= $ocorrenciasDB->vendedor_do_contrato( $contrato_id , $conexao_BD_1);
			
			// $ocorrencias 	= $ocorrenciasDB->lista_ocorrencias($conexao_BD_1 , $comprador['id'], $vendedor['id']  );
			$ocorrencias 	= $ocorrenciasDB->lista_ocorrencias_id($conexao_BD_1 , $contrato_id, $comprador['id'], $vendedor['id']  );
			$dividas		 = $ocorrenciasDB->dividas_comprador( $conexao_BD_1,  $contrato_id, $comprador['id']);
					
			echo json_encode(	array(  'comprador'=>$comprador,
										'vendedor'=>$vendedor,
										'ocorrencias'=>$ocorrencias, 
										'dividas'=>$dividas
										)
							);
			exit;
			break;	
	
		case 'insere_ocorrencia':
		$contratos_id = $_POST['contratos_id'];
		$msg = $_POST['msg'];
		
		if ($ocorrenciasDB->insere_ocorrencia_cliente($conexao_BD_1, $contratos_id, $msg)) {
			$select = "
				SELECT p.id as id FROM ocorrencias o inner join pessoas p on p.id = o.pessoas_id where contratos_id = $contratos_id 
				and p.status_id = 1 and p.perfil_id is not null
				order by o.id desc limit 1
				";
			$ret = $conexao_BD_1->query($select);
			if (count($ret) == 0) {
				$select = "SELECT id FROM pessoas WHERE supervisor = 'S'";
				$ret = $conexao_BD_1->query($select);
			}

			foreach ($ret as $key => $value) {
				inserir_alerta($alertas,$conexao_BD_1,$_SESSION['id'],$value['id'],"Olá, incluí uma ocorrência no contrato $contratos_id, por favor, me responda assim que possível","$link/contratos/$contratos_id");
			}

			$retorno = array( 'status' => 1,	'msg'=> "Ocorrência inserida com sucesso!");	
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Erro ao inserir a ocorrência!");	
		}
		break;
		
		
}

function inserir_alerta($alertas,  &$conexao_BD_1, $from, $to, $descricao, $link){
    $alertas->data_alerta = date('Y-m-d H:i:s');
    $alertas->visualizado = 'N';
    $alertas->pessoas_id_cadastro = $from;
    $alertas->pessoas_id_destino = $to;
    $alertas->descricao = $descricao;
    $alertas->link = $link;

    if ($conexao_BD_1->insert($alertas)){
        $retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);							
    }
    else{
        $retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
    }			
    return $retorno;
}
echo  json_encode($retorno);
exit(); 
?>