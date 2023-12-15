<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/arquivos/arquivos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$arquivosDB  = new arquivosDB();
$arquivos    = new arquivos();
$reflection 	= new ReflectionObject($arquivos);

if (isset($_POST["id"])){
	$arquivos->id 	   			   	 = $_POST["id"]; 
	$arquivos->tp_arq 			 	 = $_POST["tp_arq"];	
	$arquivos->status 			 	 = "CAPTURADO";	
}

if (isset($_REQUEST["acao"])){
	$acao = $_REQUEST["acao"];
}
elseif(isset($_POST["id"]) && $_POST["id"] != ""){
	$acao = "atualizar";
}
elseif(isset($_POST["id"]) && $_POST["id"] == ""){
	$acao = "inserir";
}

//print "<pre>";
//print_r($obj_aux);
//exit;

#print_r($_REQUEST);

switch ($acao) {
	case 'confirma_envio_banco':
		$user_id = $_REQUEST["u"];
		$id_arq = $_REQUEST["id_arq"];
		
		if(!is_numeric($user_id)  || !is_numeric($id_arq )){
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível confirmar envio!"	);
		}
		else{
			$arquivos->id 	   			   	 = $id_arq; 
			$arquivos->dt_envio_banco 		 = date('Y-m-d H:i:s');	
			$arquivos->pessoas_id_envio 		= $user_id;	
			if ($conexao_BD_1->update($arquivos)){
				$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);							
			}
			else{
				$retorno = array( 'status' => 0,	'msg'=> "Não foi possível confirmar envio ao banco!"	);
			}
			
			
		}
	case 'atualizar':
		#print_r($haras);
		if ($conexao_BD_1->update($arquivos)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);							
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	case 'inserir':
		$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);
		
		if($_POST["tp_arq"]=="RETORNO_TED") {
			$upload_dir_arq = getenv('CAMINHO_RAIZ')."/teds/retorno/a_importar/";
		}
		elseif($_POST["tp_arq"]=="RETORNO_BOLETO") {
			$upload_dir_arq = getenv('CAMINHO_RAIZ')."/boletos/retorno/a_importar/";
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Tipo de arquivo inválido"); 	
		}
		
		if (isset($_FILES["arquivo"])) {
			
			if ($_FILES["arquivo"]["error"] > 0) {				
				echo "ERRO NO ARQUIVO: ".$_FILES["arquivo"]["error"];
			} else {				
				$_UP['extensoes'] = array('txt', 'ret', 'TXT', 'RET');
			}
				
			$value = explode('.', $_FILES["arquivo"]['name']);
			
			if(strlen($value[0])<1){
				
				echo "Problema ao carregar nome do arquivo!";
				exit();
			}
				
			$nome  = pathinfo($_FILES[ "arquivo" ][ 'name' ],PATHINFO_FILENAME);
			$extensao  = pathinfo($_FILES[ "arquivo" ][ 'name' ],PATHINFO_EXTENSION);
			
			if (array_search($extensao, $_UP['extensoes']) === false) {
				$retorno = array( 'status' => 0,	'msg'=> "Por favor, envie arquivos com extensão: txt. extensão atual: ".$extensao	); 	
			}				
			else{
				$nome = $nome.".".$extensao;
				
				if(file_exists($upload_dir_arq.$nome)){
					$retorno = array( 'status' => 0,	'msg'=> "Arquivo já enviado com este nome."	); 		
				}
				else{
					move_uploaded_file($_FILES["arquivo"]["tmp_name"], $upload_dir_arq.$nome);
					if($_POST["tp_arq"]=="RETORNO_TED") {
						include(getenv('CAMINHO_RAIZ')."/inc/ted/importador_retorno_ted.php");
					}
					elseif($_POST["tp_arq"]=="RETORNO_BOLETO") {
						include(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/importador/importador_retorno_boleto.php");
					}
                    $retorno = array( 'status' => 1, 'msg'=> "Arquivo processado com sucesso" );
				}
				
				
				//echo "Atualizado";
			}
		}else{
			//echo "Arquivo não encontrado!";
		}
				
		
		
				
		break;
	case 'listar':
		if(isset($_REQUEST["proprietario_id"]) && is_numeric($_REQUEST["proprietario_id"])){  
			$arquivos->proprietario_id = $_REQUEST["proprietario_id"];
		}
		$retorno = $conexao_BD_1->select($arquivos);
		break;
		
	case 'remover':
	
		$arquivos->id = $_REQUEST["id"];
		if ($conexao_BD_1->delete($arquivos)){
			$retorno = array( 'status' => 1, 'msg'=>  "Removido com sucesso!"	);	
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
		
	case 'listar_arquivos':
		
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_arquivos"])){$filtros['filtro_arquivos'] = trim($_REQUEST["filtro_arquivos"]);}
		  if(isset($_REQUEST["filtro_dt_arquivo"])){$filtros['filtro_dt_arquivo'] = trim($_REQUEST["filtro_dt_arquivo"]);}
		  if(isset($_REQUEST["filtro_tp_arquivo"])){$filtros['filtro_tp_arquivo'] = trim($_REQUEST["filtro_tp_arquivo"]);}
		  if(isset($_REQUEST["filtro_origem"])){$filtros['filtro_origem'] = trim($_REQUEST["filtro_origem"]);}
		  
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
                case 'processo':
                    $order = "a.id ".$_REQUEST["ordem"].",";
                    break;

                case 'nome':
                    $order = "a.nm_arq ".$_REQUEST["ordem"].",";
                    break;
                case 'data':
                    $order = "a.dt_arq ".$_REQUEST["ordem"].",";
                    break;
                case 'tipo':
                    $order = "a.tp_arq ".$_REQUEST["ordem"].",";
                    break;
                case 'status':
                    $order = "a.status ".$_REQUEST["ordem"].",";
                    break;
                case 'origem':
                    $order = "a.origem ".$_REQUEST["ordem"].",";
                    break;
				case 'contrato':
                    $order = "a.contratos_id ".$_REQUEST["ordem"].",";
                    break;
                default:
                    $order = '';
                break;
			}
			
		}
		else{$order = '';}	
	
		$retorno = $arquivosDB->lista_arquivos($arquivos, $conexao_BD_1,  $filtros, $order, $inicial);
		break;
		
	case 'listar_totais':
		$filtros=array();
        if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
            if(isset($_REQUEST["filtro_arquivos"])){$filtros['filtro_arquivos'] = trim($_REQUEST["filtro_arquivos"]);}
            if(isset($_REQUEST["filtro_dt_arquivo"])){$filtros['filtro_dt_arquivo'] = trim($_REQUEST["filtro_dt_arquivo"]);}
            if(isset($_REQUEST["filtro_tp_arquivo"])){$filtros['filtro_tp_arquivo'] = trim($_REQUEST["filtro_tp_arquivo"]);}
            if(isset($_REQUEST["filtro_origem"])){$filtros['filtro_origem'] = trim($_REQUEST["filtro_origem"]);}

        }
		$retorno = $arquivosDB->lista_totais_arquivos($filtros,$conexao_BD_1);
		break;
		
}
echo  json_encode($retorno);
exit(); 
?>