<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.db.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/crypt.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		= array();
$pessoasDB  = new pessoasDB();
$pessoas    = new pessoas();
$reflection = new ReflectionObject($pessoas);
$crypt		= new crypt();

$dias_entrega = array();
if(isset($_REQUEST["pessoa"])){
	$pessoas_request = $_REQUEST["pessoa"];
	$obj_aux = new stdClass(); //objeto que contem todos os valores passados no formulario
	foreach ($pessoas_request as $key=>$value) {
		if($value["name"] == 'multiple' ){continue;}
		elseif($value["name"] == 'haras_id' ){ 
			$haras_id = "$value[value]";
		}
		else{
			
			if ($reflection->hasProperty($value["name"])){
				$aux_name = $value["name"];
				$pessoas->$aux_name = "$value[value]";
			}
			$aux_name = $value["name"];
			$obj_aux->$aux_name = "$value[value]";
			//echo "$key ... $value[name] - $value[value] <br>";	
		}
     }
}



if(isset($_REQUEST["limit"])){
	$limit = $_REQUEST["limit"];
}
else{
	$limit = "";
}

if(isset($_REQUEST["tipo_pessoa"])){
	
	switch ($_REQUEST["tipo_pessoa"]) {
		case 'leiloeiros':		
			$pessoas->eh_leiloeiro = "S";				
			break;	
		
		case 'vendedores':		
			$pessoas->eh_vendedor = "S";				
			break;	
		
		case 'compradores':		
			$pessoas->eh_comprador= "S";				
			break;	
		
		case 'usuarios':		
			$pessoas->eh_user = "S";				
			break;	
			
	}
}


//print "<pre>";
//print_r($obj_aux);
//exit;
//print "<pre>";
//print_r($_FILES);
////exit;

if ($pessoas->dt_nascimento != ""){
	$pessoas->dt_nascimento = ConverteData($pessoas->dt_nascimento);
}

if (($_REQUEST["acao"] == "atualizar") || ($_REQUEST["acao"] == "inserir")){
	
	if(!is_numeric($pessoas->honor_adimp)) $pessoas->honor_adimp=3;
	if(!is_numeric($pessoas->honor_inadimp )) $pessoas->honor_inadimp =20; 
	
	if(!isset($_REQUEST["trava_perfil"]) && empty($_REQUEST["trava_perfil"])){
			
			$setou_tipo = false;
			
			if ($pessoas->eh_user == ""){
				$pessoas->eh_user = "N";
				$pessoas->perfil_id = "";	
				
			}
			else{
				$setou_tipo = true;
				if(!is_numeric($pessoas->perfil_id)){
					$pessoas->perfil_id = 2;
				}
			}
			
			if ($pessoas->eh_leiloeiro == ""){
				$pessoas->eh_leiloeiro = "N";
			}
			else{
				$setou_tipo = true;
			} 
			
			if ($pessoas->eh_vendedor == ""){
				$pessoas->eh_vendedor = "N";
			}
			else{
				$setou_tipo = true;
			}
			
			if ($pessoas->eh_comprador == ""){
				$pessoas->eh_comprador = "N";
			}
			else{
				$setou_tipo = true;
			}
			
			if ($pessoas->operador == ""){
				$pessoas->operador = "N";
			}

			if ($pessoas->supervisor == ""){
				$pessoas->supervisor = "N";
			}
			
			//se não setar nenhum tipo a pessoa é cadastrada como vendedor
			if(!$setou_tipo){
				$pessoas->eh_comprador = "S";	
			}
	}
	

	
}
$id_pessoa_inserida=0;
switch ($_REQUEST["acao"]) {
	case 'atualizar':
	
		if ($pessoas->password != ""){
			$crypt->register($pessoas->password);
			$senha_nao_crypt = $pessoas->password;
			$pessoas->password = $crypt->password;
			$pessoas->saltdb   = $crypt->saltdb;	
		}
		
		if ($conexao_BD_1->update($pessoas)){
			$id_pessoa_inserida=$pessoas->id ;
			if($pessoas->id == $_SESSION["id"]){
				
				$_SESSION["usuario"] = $pessoas->email;
				
				if ($pessoas->password != ""){				
					$_SESSION["senha"] = $senha_nao_crypt;
				}
			}
																		
			$retorno = array( 'status' => 1,	'msg'=> "Atualizado com Sucesso!"	);

			if(!empty($haras_id) && is_numeric($haras_id)){
				//seta haras para este usuário
				$pessoasDB->atribuir_haras($pessoas->id,$haras_id, $conexao_BD_1);
			}						
		}
		else{
			$retorno = array( 'status' => 0,	'msg'=> "Não foi possível atualizar!"	);	 	
		}			
		break;
		
	case 'inserir':
	
		$envia_email=1;
		if(strlen($pessoas->email) <2){
			$envia_email=0;
			$pessoas->email = "sem_email@a".random_str(5).".com";
		}
	
		$pessoas->foto 		  = "default.png";
		$pessoas->status_id	  = "1";
		$pessoas->dt_ativo 	  = date("Y-m-d H:i:s");
		$pessoas->dt_inclusao = date("Y-m-d H:i:s");
		
		if ($pessoas->password == ""){
			$pessoas->password = "temp123@";
		}
		$crypt->register($pessoas->password);
		$senha_nao_crypt = $pessoas->password;
		$pessoas->password = $crypt->password;
		$pessoas->saltdb   = $crypt->saltdb;		
	
		if ($id_pessoa_inserida = $conexao_BD_1->insert($pessoas)){											
			$retorno = array( 'status' => 1,	'msg'=>  "Inserido com sucesso!"	);						
			
			if(!empty($haras_id) && is_numeric($haras_id)){
				//seta haras para este usuário
				$pessoasDB->atribuir_haras($pessoas->id,$haras_id, $conexao_BD_1);
			}
			
			
			//enviando email novo usuário
			if($envia_email){
				$destinatarios =array();
				$destinatarios[] =$pessoas->email;
				$assunto = "Bem vindo ao MECOB";
				$mensagem = "Você foi inserido em nosso sistema.  
							 <br> Seu login é seu email e sua senha  é: <strong>".$senha_nao_crypt."</strong>
							 ";
				
				include_once(getenv('CAMINHO_RAIZ')."/repositories/email/email.db.php");
				$emailDB  = new emailDB();
				$emailDB->insert_send_mail($conexao_BD_1, $destinatarios, $assunto, $mensagem, "", "", "contato@emecob.com.br", "MECOB",9);
			}
			
			
			
			
		}
		else{
			$retorno  = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	); 	
		}
		break;
		
	
	case 'listar':
	
		$inicial = 0;
		if(!empty($_GET["inicial"]) ) $inicial = $_GET["inicial"];
		
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_pessoa"])){$filtros['filtro_pessoa'] = trim($_REQUEST["filtro_pessoa"]);}
		  if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
		  if(isset($_REQUEST["filtro_perfil"])){$filtros['filtro_perfil'] = trim($_REQUEST["filtro_perfil"]);}
		  
		}
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'pessoa':		
									$order = "p.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'data':		
									$order = "p.dt_nascimento ".$_REQUEST["ordem"].",";			
									break;
					case 'documento':		
									$order = "p.cpf_cnpj ".$_REQUEST["ordem"].",";			
									break;
					case 'celular':		
									$order = "p.celular ".$_REQUEST["ordem"].",";			
									break;
					case 'mail':		
									$order = "p.email ".$_REQUEST["ordem"].",";			
									break;
					case 'status':		
									$order = "s.descricao ".$_REQUEST["ordem"].",";			
									break;
					case 'perfil':		
									$order = "pf.descricao ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$retorno = $pessoasDB->lista_pessoas($pessoas, $inicial, $limit, $filtros, $order, $conexao_BD_1);
		break;
		
	case 'listar_totais':
	
		$filtros=array();
		if(isset($_REQUEST["filtrar"]) && $_REQUEST["filtrar"]==1){
		  if(isset($_REQUEST["filtro_pessoa"])){$filtros['filtro_pessoa'] = trim($_REQUEST["filtro_pessoa"]);}
		  if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
		  if(isset($_REQUEST["filtro_perfil"])){$filtros['filtro_perfil'] = trim($_REQUEST["filtro_perfil"]);}
		}

	
		$retorno = $pessoasDB->lista_totais_pessoas($conexao_BD_1, $filtros, $pessoas);
		break;
			
	case 'busca_pessoa':
		
		$pessoas->id = $_REQUEST["id"];
		$retorno = $pessoasDB->lista_pessoas($pessoas, 0, $limit, "", "", $conexao_BD_1);
		break;	
		
	case 'listar_tipo_acesso':
	
		$retorno = $pessoasDB->lista_tipo_acesso($pessoas, $conexao_BD_1);
		break;	
		
	case 'listar_status':
	
		$retorno = $pessoasDB->lista_status($pessoas, $conexao_BD_1);
		break;	
	case 'verifica_email_doc_existente':
	
		if($_REQUEST["campo"]=='doc'){
			
			if( !validarCPF($_REQUEST["valor"]) &&  !validarCNPJ($_REQUEST["valor"])){
				$retorno = 'doc_invalido';
				echo  json_encode($retorno);
				exit(); 
			}
			
			
			$pessoas->cpf_cnpj = $_REQUEST["valor"];
		}
		elseif($_REQUEST["campo"]=='email'){
			$pessoas->email = $_REQUEST["valor"];
		}
		$filtros = array('id_dif'=>$_REQUEST["id"]);
		$ret = $pessoasDB->lista_totais_pessoas($conexao_BD_1, $filtros, $pessoas);
		$retorno = $ret[0]['qt_pessoas'];
		break;
		
			
	case 'update_imagem':
		
		$upload_dir_nail  = getenv('CAMINHO_RAIZ')."/imagens/fotos/nail/";
		$upload_dir_thumb = getenv('CAMINHO_RAIZ')."/imagens/fotos/thumb/";
		$upload_dir_temp  = getenv('CAMINHO_RAIZ')."/imagens/fotos/temp/";
		
		if (isset($_FILES["arquivo"])) {
			
			if ($_FILES["arquivo"]["error"] > 0) {				
				echo "ERRO NO ARQUIVO: ".$_FILES["arquivo"]["error"];
			} else {				
				$id_usr_img = $_POST['id_usr_img'];
				$_UP['extensoes'] = array('jpg', 'png', 'gif', 'jpeg','JPG', 'PNG', 'GIF', 'JPEG');
			}
				
			$value = explode('.', $_FILES["arquivo"]['name']);
			
			if(strlen($value[0])<1){
				
				echo "Problema ao carregar nome do arquivo!";
				exit();
			}
				
			$extensao = strtolower(array_pop($value)); 
			
			if (array_search($extensao, $_UP['extensoes']) === false) {
				echo "Por favor, envie arquivos com as seguintes extensões: jpg, png ou gif. ext atual: ".$extensao;
			}				
			else{
				$nome = $id_usr_img.".".$extensao;
				
				if(file_exists($upload_dir_nail.$nome)){
					unlink($upload_dir_nail.$nome);
					unlink($upload_dir_thumb.$nome);					
				}
				move_uploaded_file($_FILES["arquivo"]["tmp_name"], $upload_dir_temp.$nome);
				editImage($upload_dir_temp.$nome, $upload_dir_nail.$nome, 150, 150, $extensao );
				editImage($upload_dir_temp.$nome, $upload_dir_thumb.$nome, 500, 500, $extensao );
				unlink($upload_dir_temp.$nome);
				
				$pessoas->id   = $id_usr_img;
				$pessoas->foto = $nome;
				$conexao_BD_1->update($pessoas);
				echo "Atualizado";
		  	}
		}else{
			echo "Arquivo não encontrado!";
		}
		exit;
	break;
	
		
}

echo  json_encode($retorno);
exit(); 
?>