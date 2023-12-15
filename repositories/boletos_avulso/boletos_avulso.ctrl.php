<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
// include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARB/gerar_arquivo_remessa.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARU/gerar_arquivo_remessa.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/boletos_avulso/boletos_avulso.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/boletos_avulso/boletos_avulso.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$boletos_avulsoDB  = new boletos_avulsoDB();
$boletos_avulso    = new boletos_avulso();
$reflection 	    = new ReflectionObject($boletos_avulso);
$obj_aux            = new stdClass(); //objeto que contem todos os valores passados no formulario

if(isset($_REQUEST["boletos_avulso"])){
	$boletos_avulso_request = $_REQUEST["boletos_avulso"];
	foreach ($boletos_avulso_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$boletos_avulso->$aux_name = "$value[value]";
		}
		$aux_name = $value["name"];
		$obj_aux->$aux_name = "$value[value]";
		//echo "$key ... $value[name] - $value[value] <br>";	
     }
   $boletos_avulso->pessoas_id = $obj_aux->proprietario_id;
}

if ($boletos_avulso->dt_boleto != ""){
    $boletos_avulso->dt_boleto = ConverteData($boletos_avulso->dt_boleto);
}

if (isset($obj_aux->dt_vencimento) != ""){
    $obj_aux->dt_vencimento = ConverteData($obj_aux->dt_vencimento);
}
//print "<pre> request";
//print_r($_REQUEST);
//print "</pre></pre>";
//print_r($boletos_avulso);
//print "</pre><pre> ";
//print_r($obj_aux);
//print "</pre>";
//exit;

switch ($_REQUEST["acao"]) {
	case 'inserir':
        $boletos_avulso->pessoas_id_inclusao = $_SESSION["id"];
		if ($conexao_BD_1->insert($boletos_avulso)){
		    $boletos_avulsoDB->insere_valores_boleto($conexao_BD_1, $boletos_avulso->id, $obj_aux->dt_vencimento, $obj_aux->vl_boleto);
            gerar_arquivo_remessa($boletos_avulso->id, $conexao_BD_1, "BOLETO_AVULSO");

            $retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;
	case 'listar':
		if(isset($_REQUEST["pessoas_id"]) && is_numeric($_REQUEST["pessoas_id"])){
			$boletos_avulso->pessoas_id = $_REQUEST["pessoas_id"];
		}
		$retorno = $conexao_BD_1->select($boletos_avulso);
		break;

	case 'remover':
	
		$boletos_avulso->id = $_REQUEST["id"];
		if ($conexao_BD_1->delete($boletos_avulso)){
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
    case 'listar_boletos_avulso':

        $inicial = 0;
        if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];

        $filtros=array();
        if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
            if(isset($_REQUEST["filtro_proprietario"])){$filtros['filtro_proprietario'] = trim($_REQUEST["filtro_proprietario"]);}
			if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
			if(isset($_REQUEST["filtro_data_fim"])){$filtros['filtro_data_fim'] = trim($_REQUEST["filtro_data_fim"]);}
			if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}			
        }
        if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
            switch ($_REQUEST["order"]) {
                case 'nome':
                    $order = "p.nome ".$_REQUEST["ordem"].",";
                    break;
                case 'dt_boleto':
                    $order = "b.dt_boleto ".$_REQUEST["ordem"].",";
                    break;
                case 'dt_vencimento':
                    $order = "cp.dt_vencimento ".$_REQUEST["ordem"].",";
                    break;
                case 'valor':
                    $order = "cp.vl_corrigido ".$_REQUEST["ordem"].",";
                    break;

                default:
                    $order = '';
                    break;
            }

        }
        else{$order = '';}

        $retorno = $boletos_avulsoDB->lista_boletos_avulso($conexao_BD_1, $boletos_avulso,  $filtros, $order, $inicial);
        break;

    case 'listar_totais':
        $filtros=array();
        if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
            if(isset($_REQUEST["filtro_proprietario"])){$filtros['filtro_proprietario'] = trim($_REQUEST["filtro_proprietario"]);}
			if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
			if(isset($_REQUEST["filtro_data_fim"])){$filtros['filtro_data_fim'] = trim($_REQUEST["filtro_data_fim"]);}
			if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}			
        }
        $retorno = $boletos_avulsoDB->lista_totais_boletos_avulso($conexao_BD_1, $filtros);
        break;
    case 'remove_boletos_avulso':
        $boletos_avulso_id  = $_REQUEST["boletos_avulso_id"];
        $boletos_avulso->id = $boletos_avulso_id;
        $ret_del = $boletos_avulsoDB->remover_boletos_avulso($conexao_BD_1, $boletos_avulso);
        if ($ret_del){
            $retorno = array( 'status' => 1, 'msg'=>  "Boleto avulso removido com sucesso!"	);
        }
        else{
            $retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover o boleto."	);
        }
        break;

}
echo  json_encode($retorno);
exit(); 
?>