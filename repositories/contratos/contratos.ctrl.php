<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
$is_pagina_perfil=1;

if($_REQUEST["acao"] != 'atualiza_parcelas_2_via' &&  $_REQUEST["acao"] != 'lista_parcelas'){
	include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
}
// include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARB/gerar_arquivo_remessa.php");
$boleto_unicred = true;

if(!empty($_REQUEST["contratos_id"])) {
	if($_REQUEST["contratos_id"] <= 12460) {
		$boleto_unicred = false;
	}
}

if($boleto_unicred) {
	include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARU/gerar_arquivo_remessa.php");
} else {
	include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARB/gerar_arquivo_remessa.php");
}
//include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARU/gerar_arquivo_remessa.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
// include_once(getenv('CAMINHO_RAIZ')."/repositories/parcelas/parcelas.db.php");
include_once(getenv('CAMINHO_RAIZ') . "/repositories/arquivos/arquivos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		  = array();
$contratosDB  = new contratosDB();
// $parcelasDB  = new parcelasDB();
$contratos    = new contratos();
$arquivos     = new arquivos();
$reflection   = new ReflectionObject($contratos);

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

//print "<pre>";
//print_r($obj_aux);
//exit;

#print_r($_REQUEST);
if($_REQUEST["acao"] == 'atualizar' || $_REQUEST["acao"] == 'inserir'){
	if($contratos->dt_contrato!='') $contratos->dt_contrato = ConverteData($contratos->dt_contrato);


    if($contratos->gerar_boleto == ''){
        $contratos->gerar_boleto = "N";
    }
}

$envia_email = 0;


switch ($_REQUEST["acao"]) {
	case 'desfazerAcordo':
		if ($contratosDB->desfazer_acordo($conexao_BD_1,$_REQUEST['contrato'],$_REQUEST['contrato_pai']) == 'Ok'){
			$retorno = array( 'status' => 1,	'msg'=> "Acordo desfeito!");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao desfazer o acordo!");
		};
		// $retorno = 
	break;
	case 'contratoRepasse':	
		$contrato = new contratos();

		$contrato->id = $_REQUEST['contrato'];
		$contrato->repasse = "S";

	
		if ($conexao_BD_1->update($contrato)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao suspender o contrato!");
		}

	break;
	case 'removerContratoRepasse':	
		$contrato = new contratos();

		$contrato->id = $_REQUEST['contrato'];
		$contrato->repasse = "N";

	
		if ($conexao_BD_1->update($contrato)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao suspender o contrato!");
		}

	break;
	case 'suspenderContrato':	
		$contrato = new contratos();
		$contrato->id = $_REQUEST['contrato'];
		$contrato->suspenso = "S";
		$contrato->dt_suspensao = date('Y-m-d');
		$contrato->dt_retorno_suspensao = 'NULL';

	
		if ($conexao_BD_1->update($contrato)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao suspender o contrato!");
		}

	break;
	case 'suspenderContrato':	
		$contrato = new contratos();

		$contrato->id = $_REQUEST['contrato'];
		$contrato->suspenso = "S";
		$contrato->dt_suspensao = date('Y-m-d');
		$contrato->dt_retorno_suspensao = 'NULL';

	
		if ($conexao_BD_1->update($contrato)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao suspender o contrato!");
		}

	break;

	case 'removerSuspensaoContrato':	
		$contrato = new contratos();

		$contrato->id = $_REQUEST['contrato'];
		$contrato->suspenso = "N";
		$contrato->dt_retorno_suspensao = date('Y-m-d');
		// if ($_REQUEST["cancelar"] == "sim") {
			// $contrato->status = 'excluido';
		// }

	
		if ($conexao_BD_1->update($contrato)){
			// echo var_dump($contrato);
			// exit;

			$regContrato = $contratosDB->lista_contratos($contrato, $conexao_BD_1);
			$gerar_boleto = $regContrato[0]["gerar_boleto"];
		
			if ($_REQUEST["realocar"] == "sim") {
				$contratosDB->altera_parcelas_contrato_retirado_suspensao($conexao_BD_1, $contrato->id, $regContrato[0]["dt_suspensao"], $contrato->dt_retorno_suspensao);
				gerar_arquivo_remessa($contrato->id, $conexao_BD_1);
			}
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao suspender o contrato!");
		}

	break;
	
	case 'inserir':
		
		$contratos->dt_inclusao = date("Y-m-d H:i:s");
		$contratos->pessoas_id_inclusao = $_SESSION["id"];	
		$contratos->juros = 2.00;
		$contratos->tp_contrato_boleto = $contratos->tp_contrato;
		
		if($contratos->status == ''){
        	$contratos->status = "pendente";
    	}
		
		#print_r($contratos);
		if ($contratos->id = $conexao_BD_1->insert($contratos)){
			$retorno = array( 'status' => $contratos->id,	'msg'=> "Inserido com Sucesso!");	
			
			//gerar parcelas contrato
			$contratosDB->gera_parcelas($conexao_BD_1, $contratos);
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
		}			
		break;
	
	case 'atualizarTermo':
		if ($contratos->fiador == ''){
			$contratos->fiador = 'NULL';
		}
		if ($contratos->animal == ''){
			$contratos->animal = 'NULL';
		}
		if ($conexao_BD_1->update($contratos)){
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");	
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
	
	case 'edit_descricao': 
		$contratos_id = $_REQUEST['contratos_id'];
		$descricao = $_REQUEST['descricao'];
		$contratos    = new contratos();
		$contratos->id = $contratos_id;
		$contratos->descricao = $descricao;
		if ($conexao_BD_1->update($contratos)){
			
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");	
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
		
	case 'atualizar':
		#print_r($contratos);
        $arquivos->contratos_id = $contratos->id;
        $conexao_BD_1->select($arquivos);
        $qtArquivos = $conexao_BD_1->numeroDeRegistros();

        if ($qtArquivos > 0){
            $retorno = array('status' => 9, 'msg' => "Contrato já confirmado!");
        }
        else {

            if (($contratos->status == "pendente") && ($contratos->contratos_id_pai == "")) {
                $contratos->tp_contrato_boleto = $contratos->tp_contrato;
            }


            if ($conexao_BD_1->update($contratos)) {

                $contratosDB->delete_parcelas($conexao_BD_1, $contratos->id);
                $contratosDB->gera_parcelas($conexao_BD_1, $contratos);

                $retorno = array('status' => 1, 'msg' => "Atualizado com Sucesso!");
            } else {
                $retorno = array('status' => 0, 'msg' => "Não foi possível atualizar!");
            }
        }
		break;
	
	case 'atualiza_simulacao':
		if ($conexao_BD_1->update($contratos)){
			
			$parcelas_a_atualizar = $_REQUEST['parcelas_a_atualizar'];
			$contratosDB->atualiza_parcelas_simulacao($conexao_BD_1, $contratos, $parcelas_a_atualizar);
			
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");	

		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;	
	case 'calcula_correcao':
		$dt_vencimento_antiga 	= $_POST['vencimento_antigo'];
		$dt_vencimento_nova		= $_POST['vencimento_novo'];
		$vl_parcela 			= $_POST['valor'];
		$retorno = $contratosDB->calcula_correcao_monetaria($vl_parcela, $dt_vencimento_antiga, $dt_vencimento_nova, $conexao_BD_1);
		break;
	
	case 'confirmar_contrato':

		$contratos->id = $_REQUEST["id_contrato"];
        $contratos->status = $_REQUEST['status_contrato'];
        $conexao_BD_1->select($contratos);
        $qtRegistros = $conexao_BD_1->numeroDeRegistros();

        $arquivos->contratos_id = $contratos->id;
        $conexao_BD_1->select($arquivos);
        $qtArquivos = $conexao_BD_1->numeroDeRegistros();

        if (($qtArquivos > 0)||($qtRegistros == 0)){
            $retorno = array('status' => 9, 'msg' => "Contrato já confirmado!");
        }
        else {

            $contratos->status = "confirmado";
            if ($conexao_BD_1->update($contratos)) {
                $retorno = array('status' => 1, 'msg' => "Atualizado com Sucesso!");

                $regContrato = $contratosDB->lista_contratos($contratos, $conexao_BD_1);
                $gerar_boleto = $regContrato[0]["gerar_boleto"];

                if ($gerar_boleto == "S") {
                    gerar_arquivo_remessa($contratos->id, $conexao_BD_1);
                }

                $contratosDB->parcelas_liquidadas_no_cadastro($conexao_BD_1, $contratos->id);

                //enviando email confirmação contrato
                $envia_email = 1;
                $emailComprador = $regContrato[0]["comprador_email"];
                $emailVendedor = $regContrato[0]["vendedor_email"];
                $destinatarios = array($emailComprador, $emailVendedor);
                $assunto = "MECOB - Novo contrato cadastrado.";
                $mensagem = "Foi cadastrado um novo contrato seu em nosso sistema.";
            } else {
                $retorno = array('status' => 0, 'msg' => "Não foi possível atualizar!");
            }
        }
		break;	
		
	case 'gerar_acordo':
		
		$contratos->id = $_REQUEST["id_contrato"];
		
		$contrato_acordo = new contratos();
		
		$contrato_acordo->contratos_id_pai 	  = $contratos->id;
		$contrato_acordo->descricao		 	  = "ACORDO DO CONTRATO >> ".$contratos->id;
		$contrato_acordo->tp_contrato		  = "adimplencia";
		$contrato_acordo->status		   	  = "pendente";
		$contrato_acordo->dt_contrato	   	  = date("Y-m-d");
		$contrato_acordo->dt_inclusao 	   	  = date("Y-m-d H:i:s");
		$contrato_acordo->pessoas_id_inclusao = $_SESSION["id"];	
		$contrato_acordo->nu_parcelas 		  = $_REQUEST["qt_parcelas_acordo"];
		$contrato_acordo->dt_primeira_parcela = ConverteData($_REQUEST["dt_primeira_parcela"]);
        $contrato_acordo->desconto_total      = $_REQUEST["desconto_acordo"];
        $contrato_acordo->vl_contrato	 	  = $_REQUEST["vl_acordo"] - $contrato_acordo->desconto_total;


        $info_contrato = $contratosDB->lista_contratos($contratos, $conexao_BD_1);
		
		$emailComprador = $info_contrato[0]["comprador_email"];
		$emailVendedor  = $info_contrato[0]["vendedor_email"];

		$contrato_acordo->juros			 = $info_contrato[0]["juros"];
		$contrato_acordo->honor_adimp	 = $info_contrato[0]["honor_inadimp"];
		$contrato_acordo->honor_inadimp	 = $info_contrato[0]["honor_inadimp"];
		$contrato_acordo->vendedor_id	 = $info_contrato[0]["vendedor_id"];
		$contrato_acordo->comprador_id	 = $info_contrato[0]["comprador_id"];
		$contrato_acordo->eventos_id	 = $info_contrato[0]["eventos_id"];
		$contrato_acordo->parcela_primeiro_pagto = 1;
        $contrato_acordo->tp_contrato_boleto  = $info_contrato[0]["tp_contrato_boleto"];
        $contrato_acordo->gerar_boleto   = $info_contrato[0]["gerar_boleto"];
			
		$contratos->status_antes_acordo = $info_contrato[0]["status"];
		if ($contratosDB->parcelas_tem_em_aberto($conexao_BD_1, $contratos)){
			$contratos->status = "parcialmente_em_acordo";
		}else{
			$contratos->status = "em_acordo";
		}
		
		if ($contrato_acordo->id = $conexao_BD_1->insert($contrato_acordo)){
			
			$conexao_BD_1->update($contratos);			
			$contratosDB->atualiza_dt_pagto_parcelas_acordo($conexao_BD_1, $contratos->id, $contrato_acordo->dt_contrato);
			
			if ($contratos->status == "parcialmente_em_acordo" && $info_contrato[0]["gerar_boleto"] == "S"){
                gerar_arquivo_remessa($contratos->id, $conexao_BD_1);
            }
			
			
			//gerar parcelas contrato
			$contratosDB->gera_parcelas($conexao_BD_1, $contrato_acordo);
			
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");	
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}		
		
		//enviando email confirmação contrato
		$envia_email=1; 
		$destinatarios =array($emailComprador ,$emailVendedor  ); 
		$assunto = "MECOB - Novo contrato cadastrado.";
		$mensagem = "Foi cadastrado um novo contrato seu em nosso sistema.  ";
		
		break;
		
	case 'listar':
	
		$retorno = $conexao_BD_1->select($contratos);
		break;
		
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
			if(isset($_REQUEST["filtro_evento"])){$filtros['filtro_evento'] = trim($_REQUEST["filtro_evento"]);}
			if(isset($_REQUEST["filtro_pagina"])){$filtros['filtro_pagina'] = trim($_REQUEST["filtro_pagina"]);}
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
		
	case 'listar_totais':
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		if(isset($_REQUEST["filtro_contrato"])){$filtros['filtro_contrato'] = trim($_REQUEST["filtro_contrato"]);}
		if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
		if(isset($_REQUEST["filtro_data_fim"])){$filtros['filtro_data_fim'] = trim($_REQUEST["filtro_data_fim"]);} 
		if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
		if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
		if(isset($_REQUEST["filtro_id"])){$filtros['filtro_id'] = trim($_REQUEST["filtro_id"]);}	
		if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
		if(isset($_REQUEST["filtro_pagto"])){$filtros['filtro_pagto'] = trim($_REQUEST["filtro_pagto"]);}
		if(isset($_REQUEST["filtro_zerado"])){$filtros['filtro_zerado'] = trim($_REQUEST["filtro_zerado"]);}
		if(isset($_REQUEST["filtro_evento"])){$filtros['filtro_evento'] = trim($_REQUEST["filtro_evento"]);}
		if(isset($_REQUEST["filtro_pagina"])){$filtros['filtro_pagina'] = trim($_REQUEST["filtro_pagina"]);}
		}
		$retorno = $contratosDB->lista_totais_contratos($filtros,$conexao_BD_1);
		break;
		
	case 'remove_contratos':
		$contratos_id  = $_REQUEST["contratos_id"];
		$ret_del = $contratosDB->remover_contratos($conexao_BD_1, $contratos_id);
		if ($ret_del==1){	
			$retorno = array( 'status' => 1, 'msg'=>  "Contrato removido com sucesso!"	);	
		}
		elseif($ret_del==3){
			$retorno  = array( 'status' => 0,	'msg'=> "Já existe parcelas deste contrato ou de seus originais que estão relacionadas com TEDs. Para esta ação contate o suporte."	); 
		}
		else{
			$retorno  = array( 'status' => $ret_del,	'msg'=> "Não foi possível remover."	); 	
		}
		break;
	case 'lista_documentos':
		$contrato_id  = $_REQUEST["contrato_id"];
		$retorno = $contratosDB->lista_documentos($contrato_id, $conexao_BD_1);
		break;
	
	case 'lista_parcelas':
		$contrato_id  = $_REQUEST["contrato_id"];
		$retorno = $contratosDB->lista_parcelas_contratos($contrato_id, $conexao_BD_1);
		break;
		
	case 'remove_documento':
		$documento_id  = $_REQUEST["id"];
		if($retorno = $contratosDB->remover_documento($documento_id, $conexao_BD_1)){
			unlink(getenv('CAMINHO_RAIZ')."/documentos/".$_REQUEST['file']);
		}
		break;
	case 'remove_boletos':
		$contrato_id  = $_REQUEST["contrato_id"]; 
		$retorno = $contratosDB->remover_boletos_contrato($contrato_id, $conexao_BD_1);
		break;
	case 'desfazer_pg_parcela':
		$id_parcela  = $_REQUEST["id_parcela"]; 
		$ret = $contratosDB->desfazer_pg_parcela($id_parcela, $conexao_BD_1); 
		if ($ret == 'OK'){	
			$retorno = array( 'status' => 1, 'msg'=>  "Pagamento da parcela removido com sucesso!"	);	
		}
		elseif(is_numeric($ret)){
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível desfazer o pagamento desta parcela pois ela está relacionada a uma TED: TED ".$ret); 
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível desfazer o pagamento desta parcela."); 
		}
		
		break;
	
	case 'upload_file':
		
		$upload_dir  = getenv('CAMINHO_RAIZ')."/documentos/";
		
		if (isset($_FILES["arquivo"])) {
			
			if ($_FILES["arquivo"]["error"] > 0) {				
				echo "ERRO NO ARQUIVO: ".$_FILES["arquivo"]["error"];
			} else {	
				$_UP['extensoes'] = array('jpg', 'png', 'gif', 'jpeg','pdf','xls','doc','docx', 'txt');
			}
				
			$value = explode('.', $_FILES["arquivo"]['name']);
			
			if(strlen($value[0])<1){
				
				echo "Problema ao carregar nome do arquivo!";
				exit();
			}
			$nome = slugify($_REQUEST['descricao']).date('_dmY').date('_his');
			$extensao = strtolower(array_pop($value)); 
			
			if (array_search($extensao, $_UP['extensoes']) === false) {
				echo "Por favor, envie arquivos com as seguintes extensões: 'jpg', 'png', 'gif', 'jpeg','pdf','xls','doc','docx', 'txt'. ext atual: ".$extensao;
			}				
			else{
				$nome .= ".".$extensao;
				
				if(file_exists($upload_dir.$nome)){
					unlink($upload_dir.$nome);					
				}
				move_uploaded_file($_FILES["arquivo"]["tmp_name"], $upload_dir.$nome);
				
				$retorno = $contratosDB->inserir_documento($_REQUEST['contratos_id'],  $_REQUEST['descricao'],$nome,  $conexao_BD_1);
				echo "Atualizado";
		  	}
		}else{
			echo "Arquivo não encontrado!";
		}
		exit;
		break;	
		
	case 'edit_parcelas':

        $contratos->id = $_REQUEST['id_contrato'];
        $contratos->status = $_REQUEST['status_contrato'];

        $pessoas_id_atualizacao = null;
        if(isset($_SESSION["id"])){
            $pessoas_id_atualizacao = $_SESSION["id"];
        }

        $conexao_BD_1->select($contratos);

        $qtRegistros = $conexao_BD_1->numeroDeRegistros();

        if ($qtRegistros > 0){
            if(empty($_REQUEST['parcelas'])){
                $retorno = 1;
            }
            else{
                $parcelas = $_REQUEST['parcelas'];
                //print_r($parcelas);
                //exit;
                $retorno = $contratosDB->atualiza_parcelas($parcelas, $conexao_BD_1, $pessoas_id_atualizacao);
            }
        }
        else{
            $retorno = 9;
        }


		break;
	case 'save_instrucao':
		if(empty($_REQUEST['ctId']) || !is_numeric($_REQUEST['ctId'])){
			$retorno = array( 'status' => 0,	'msg'=> "Contrato inválido"	);	
		}
		else{
			if($_REQUEST['opcoes']==1){
				//instruções padrao
				$instrucoes = "null";
			}
			elseif($_REQUEST['opcoes']==2){
				//instruções em branco
				$instrucoes = "-";
			}
			elseif($_REQUEST['opcoes']==3){
				//instruções customizadas
				$instrucoes = "";
				if(!empty($_REQUEST['inst1'])){$instrucoes .= $_REQUEST['inst1']."<br>";}
				if(!empty($_REQUEST['inst2'])){$instrucoes .= $_REQUEST['inst2']."<br>";}
				if(!empty($_REQUEST['inst3'])){$instrucoes .= $_REQUEST['inst3']."<br>";}
			}
			$retorno =  $contratosDB->atualiza_instrucao($_REQUEST['ctId'], $instrucoes, $conexao_BD_1);
			
		}
		break;
		
	case 'copy_contrato_adimp_para_inadimplente':
		$contratos->id = $_REQUEST['id_contrato'];
		
		$info_contrato = $contratosDB->lista_contratos($contratos, $conexao_BD_1);
		
		$novo_contrato = new contratos();
		
		$novo_contrato->descricao           = $info_contrato[0]["descricao"];
		$novo_contrato->dt_contrato		 	= $info_contrato[0]["dt_contrato"];
		$novo_contrato->vl_contrato		 	= $info_contrato[0]["vl_contrato"];
		$novo_contrato->eventos_id	   	 	= $info_contrato[0]["eventos_id"];
		$novo_contrato->vendedor_id	   	 	= $info_contrato[0]["vendedor_id"];
		$novo_contrato->comprador_id	 	= $info_contrato[0]["comprador_id"];
		$novo_contrato->vl_entrada	 	  	= $info_contrato[0]["vl_entrada"];
		$novo_contrato->tp_contrato		 	= "inadimplencia";
		$novo_contrato->nu_parcelas		 	= $info_contrato[0]["nu_parcelas"];
		$novo_contrato->dt_inclusao 		= date("Y-m-d H:i:s");
		$novo_contrato->pessoas_id_inclusao = $_SESSION["id"];	
		$novo_contrato->honor_adimp		 	= $info_contrato[0]["honor_adimp"];
		$novo_contrato->honor_inadimp       = $info_contrato[0]["honor_inadimp"];
        $novo_contrato->status              = "pendente";
		$novo_contrato->parcela_primeiro_pagto = $info_contrato[0]["parcela_primeiro_pagto"];
		$novo_contrato->juros				= $info_contrato[0]["juros"];
		$novo_contrato->contratos_id_pai	= $info_contrato[0]["id"];
        $novo_contrato->tp_contrato_boleto  = $info_contrato[0]["tp_contrato_boleto"];
        $novo_contrato->gerar_boleto        = $info_contrato[0]["gerar_boleto"];
		
		if ($novo_contrato->id = $conexao_BD_1->insert($novo_contrato)){
		
			//copiar as parcelas
			$parcelas = $contratosDB->lista_parcelas_contratos($contratos->id, $conexao_BD_1);

			//o que fazer com o contrato antigo
			$contratos->status = "virou_inadimplente";
			$conexao_BD_1->update($contratos);	
			$dt_pagto = $novo_contrato->dt_inclusao;


			foreach($parcelas as $parcela){ 

				$insert_parcelas = "insert into contrato_parcelas 
										( contratos_id, nu_parcela, dt_vencimento, dt_pagto, vl_parcela, vl_juros, vl_correcao_monetaria, vl_honorarios, vl_corrigido, liquidada_no_cadastro, dt_processo_pagto  )
								values  ( ".$novo_contrato->id." ,".$parcela["nu_parcela"].", '".$parcela["dt_vencimento"]."', '".$parcela["dt_pagto"]."', ".$parcela["vl_parcela"].", ".$parcela["vl_juros"].", ".$parcela["vl_correcao_monetaria"].", ".$parcela["vl_honorarios"].", ".$parcela["vl_corrigido"].", '".$parcela["liquidada_no_cadastro"]."', '".$parcela["dt_processo_pagto"]."'  )";
				$conexao_BD_1->query_inserir($insert_parcelas);

				//atualiza parcelas do contrato antigo
				$update = " update contrato_parcelas 
							set dt_pagto = '".$dt_pagto."',
							    dt_processo_pagto='".date('Y-m-d G:i:s')."'
							where id = ".$parcela["id"]." and (dt_pagto is null or dt_pagto = '0000-00-00') ";
				$conexao_BD_1->query($update);
			}
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");	
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	
		}
	break;
		
	case 'atualiza_parcelas_2_via':
		$parcela_id 	= $_REQUEST['parcela_id'];
		$dt_atualizacao = $_REQUEST['dt_atualizacao'];
		$retorno = $contratosDB->calcula_juros_parcela($conexao_BD_1, $parcela_id, $dt_atualizacao);		
	break;

    case 'verifica_informacoes':
        $contratos_id  = $_REQUEST["id_contrato"];
        $regInformacoes = $contratosDB->verifica_informacoes($conexao_BD_1, $contratos_id);
        $dados_invalidos = "";

        if (trim($regInformacoes[0]["doc_comprador"]) == ""){
            $dados_invalidos .= "Documento Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["nome_comprador"]) == ""){
            $dados_invalidos .= "Nome Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["rua_comprador"]) == ""){
            $dados_invalidos .= "Rua Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["numero_comprador"]) == ""){
            $dados_invalidos .= "Número Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["bairro_comprador"]) == ""){
            $dados_invalidos .= "Bairro Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["cidade_comprador"]) == ""){
            $dados_invalidos .= "Cidade Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["estado_comprador"]) == ""){
            $dados_invalidos .= "Estado Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["cep_comprador"]) == ""){
            $dados_invalidos .= "CEP Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["email_comprador"]) == ""){
            $dados_invalidos .= "E-mail Comprador deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["doc_vendedor"]) == ""){
            $dados_invalidos .= "Documento Vendedor deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["nome_vendedor"]) == ""){
            $dados_invalidos .= "Nome Vendedor deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["rua_vendedor"]) == ""){
            $dados_invalidos .= "Rua Vendedor deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["numero_vendedor"]) == ""){
            $dados_invalidos .= "Número Vendedor deve ser preenchido.<br>";
        }
        //if (trim($regInformacoes[0]["complemento_vendedor"]) == ""){
        //    $dados_invalidos .= "Complemento Vendedor deve ser preenchido.<br>";
        //}
        if (trim($regInformacoes[0]["bairro_vendedor"]) == ""){
            $dados_invalidos .= "Bairro Vendedor deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["cidade_vendedor"]) == ""){
            $dados_invalidos .= "Cidade Vendedor deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["estado_vendedor"]) == ""){
            $dados_invalidos .= "Estado Vendedor deve ser preenchido.<br>";
        }
        if (trim($regInformacoes[0]["cep_vendedor"]) == ""){
            $dados_invalidos .= "CEP Vendedor deve ser preenchido.<br>";
        }

        if (strlen($dados_invalidos) > 0){
            $retorno = array( 'status' => 1, 'msg'=>  $dados_invalidos	);
        }
        else{
            $retorno  = array( 'status' => 0,	'msg'=> "Ok!"	);
        }
        break;
    case 'zerar_parcelas':

        $contrato_a_zerar = new contratos();

        $contrato_a_zerar->id = $_REQUEST['id_contrato'];
        $contrato_a_zerar->fl_parcelas_zerado = "S";
        $contrato_a_zerar->dt_parcelas_zerado = date('Y-m-d G:i:s');
        $contrato_a_zerar->motivo_zerado = $_REQUEST['motivo_zerado'];
        $contrato_a_zerar->observacao_zerado = $_REQUEST['observacao_zerado'];

        $contratosDB->zerar_parcelas($conexao_BD_1, $contrato_a_zerar, $_SESSION["id"]);
        $conexao_BD_1->update($contrato_a_zerar);

        $retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");
        break;
    case 'zerar_parcela_unica':

        $contrato_a_zerar = new contratos();

        $contrato_a_zerar->id = $_REQUEST['id_contrato'];
        $contrato_a_zerar->dt_parcelas_zerado = date('Y-m-d G:i:s');
        $parcela_id = $_REQUEST['id_parcela'];
        $motivo_zerado = $_REQUEST['motivo_zerado'];
        $observacao_zerado = $_REQUEST['observacao_zerado'];

        $contratosDB->zerar_parcelas($conexao_BD_1, $contrato_a_zerar, $_SESSION["id"], null, $parcela_id, $motivo_zerado, $observacao_zerado);

        $retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");
        break;
    case 'virar_acao_judicial':

        $contrato_acao = new contratos();

        $contrato_acao->id = $_REQUEST['id_contrato'];
        $contrato_acao->fl_parcelas_zerado = "S";
        $contrato_acao->dt_parcelas_zerado = date('Y-m-d G:i:s');

        $fl_acao_judicial = "S";
        $contratosDB->zerar_parcelas($conexao_BD_1, $contrato_acao, $_SESSION["id"], $fl_acao_judicial);

        $contrato_acao->fl_parcelas_zerado = null;
        $contrato_acao->dt_parcelas_zerado = null;
        $contrato_acao->status = "acao_judicial";
        $contrato_acao->dt_acao_judicial = date('Y-m-d G:i:s');

        $conexao_BD_1->update($contrato_acao);

        $retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!");
        break;

    case 'desnegativar_parcela':

        $parcela_id    = $_REQUEST['parcela_id'];
        $fl_negativada = $_REQUEST['negativa'];

        if ($contratosDB->atualiza_fl_negativada_parcela($conexao_BD_1, $parcela_id, $fl_negativada)) {
            $retorno = array('status' => 1, 'msg' => "Atualizado com Sucesso!");
        }
        else{
            $retorno = array('status' => 0, 'msg' => "Não foi possivel atualizar!");
        }
		break;
	case 'get_parcela':

        $parcela_id    = $_POST['parcela_id'];

        $retorno = $contratosDB->get_parcela($conexao_BD_1, $parcela_id);
		break;
	case 'gera_remessa_parcela':
		try {
			$contrato_id    = $_POST['contratos_id'];
			$parcela_id    = $_POST['parcelas_id'];
			$correcao    = $_POST['correcao'];
			$juros    = $_POST['juros'];
			$multa    = $_POST['multa'];
			$taxas    = $_POST['taxas'];
			$honorarios    = $_POST['honorarios'];
			$valor_corrigido = $_POST['valor_corrigido'];
			$vencimento = $_POST['vencimento'];
			gerar_arquivo_remessa($contrato_id, $conexao_BD_1, 'PARCELA', $parcela_id, [
				'correcao' => $correcao,
				'juros' => $juros,
				'multa' => $multa,
				'taxas' => $taxas,
				'honorarios' => $honorarios,
				'valor_corrigido' => $valor_corrigido,
				'vencimento' => $vencimento
			]);

			$retorno = 'ok';
		} catch (\Throwable $th) {
			$retorno = 'erro';
		}
		break;
	case 'listaContratosComprador':
		$ct    = new contratos();
		$ct->comprador_id = $_REQUEST['pessoas_id'];
		$filtros = [];
		if ( isset($_REQUEST['filtro_contrato'])){
			$filtros['filtro_contrato'] = $_REQUEST['filtro_contrato'];
		}
		if (isset($_REQUEST['filtro_data'])){
			$filtros['filtro_data'] = $_REQUEST['filtro_data'];
		}
		if (isset($_REQUEST['filtro_evento'])){
			$filtros['filtro_evento'] = $_REQUEST['filtro_evento'];			
		}
		if (isset($_REQUEST['filtro_vendedor'])){
			$filtros['filtro_vendedor'] = $_REQUEST['filtro_vendedor'];			
		}
		$ordem = $_REQUEST['ordem'];
		$retorno = $contratosDB->lista_contratos($ct, $conexao_BD_1,  $filtros ,  $ordem ,  0,"N");
	break;
	case 'listaContratosVendedor':
		$ct    = new contratos();
		$ct->vendedor_id = $_REQUEST['pessoas_id'];
		$filtros = [];
		if ( isset($_REQUEST['filtro_id']))
			$filtros['filtro_id'] = $_REQUEST['filtro_id'];
		if ( isset($_REQUEST['filtro_contrato']))
			$filtros['filtro_contrato'] = $_REQUEST['filtro_contrato'];
		if ( isset($_REQUEST['filtro_data']))
			$filtros['filtro_data'] = $_REQUEST['filtro_data'];
		if ( isset($_REQUEST['filtro_evento']))
			$filtros['filtro_evento'] = $_REQUEST['filtro_evento'];
		if ( isset($_REQUEST['filtro_comprador']))
			$filtros['filtro_comprador'] = $_REQUEST['filtro_comprador'];
		$ordem = $_REQUEST['ordem'];
		$retorno = $contratosDB->lista_contratos($ct, $conexao_BD_1,  $filtros ,  $ordem ,  0,"N");
	break;
	case 'listaTotaisContratosVendedor':
		$ct    = new contratos();
		$ct->vendedor_id = $_REQUEST['pessoas_id'];
		$filtros = [];
		if ( isset($_REQUEST['filtro_id']))
			$filtros['filtro_id'] = $_REQUEST['filtro_id'];
		if ( isset($_REQUEST['filtro_contrato']))
			$filtros['filtro_contrato'] = $_REQUEST['filtro_contrato'];
		if ( isset($_REQUEST['filtro_data']))
			$filtros['filtro_data'] = $_REQUEST['filtro_data'];
		if ( isset($_REQUEST['filtro_evento']))
			$filtros['filtro_evento'] = $_REQUEST['filtro_evento'];
		if ( isset($_REQUEST['filtro_comprador']))
			$filtros['filtro_comprador'] = $_REQUEST['filtro_comprador'];
		$ordem = $_REQUEST['ordem'];
		$retorno = $contratosDB->lista_contratos($ct, $conexao_BD_1,  $filtros ,  $ordem ,  0,"N");
		$retorno = $contratosDB->lista_totais_contratos($filtros,$conexao_BD_1,$ct);
	break;
	case 'listaTotaisContratosComprador':
		$ct    = new contratos();
		$ct->comprador_id = $_REQUEST['pessoas_id'];
		$filtros = [];
		if ( isset($_REQUEST['filtro_contrato']))
			$filtros['filtro_contrato'] = $_REQUEST['filtro_contrato'];
		if ( isset($_REQUEST['filtro_data']))
			$filtros['filtro_data'] = $_REQUEST['filtro_data'];
		if ( isset($_REQUEST['filtro_evento']))
			$filtros['filtro_evento'] = $_REQUEST['filtro_evento'];
		if ( isset($_REQUEST['filtro_comprador']))
			$filtros['filtro_comprador'] = $_REQUEST['filtro_comprador'];
		$ordem = $_REQUEST['ordem'];
		$retorno = $contratosDB->lista_contratos($ct, $conexao_BD_1,  $filtros ,  $ordem ,  0,"N");
		$retorno = $contratosDB->lista_totais_contratos($filtros,$conexao_BD_1,$ct);
	break;
	// Busca o histórico dos contratos
	case 'listaHistorico':
		$contrato_id  = $_REQUEST["contrato_id"];
		$retorno = $contratosDB->lista_historico($contrato_id, $conexao_BD_1);
	break;

	case 'lista_contratos_boletos_avulsos':
		if ( isset($_REQUEST['filtro_id']))
			$filtros['filtro_id'] = $_REQUEST['filtro_id'];

		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Contratos  ' . json_encode($filtros));

		$retorno = $contratosDB->lista_contratos_boletos_avulsos($conexao_BD_1, $filtros);
	break;

}

if($envia_email){
	include_once(getenv('CAMINHO_RAIZ')."/repositories/email/email.db.php");
	$emailDB  = new emailDB();
	$emailDB->insert_send_mail($conexao_BD_1, $destinatarios, $assunto, $mensagem, "", "", "contato@mecob.com.br", "MECOB",9);
}
echo  json_encode($retorno);
