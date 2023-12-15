<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.db.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/crypt.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 			  = array();
$acesso_pessoaDB  = new acesso_pessoaDB();
$acesso_pessoa    = new acesso_pessoa();
$reflection 	  = new ReflectionObject($acesso_pessoa);

if(isset($_REQUEST["pessoa"])){
	$acesso_pessoa_request = $_REQUEST["acesso_pessoa"];
	foreach ($acesso_pessoa_request as $key=>$value) {
		if ($reflection->hasProperty($value["name"])){
			$aux_name = $value["name"];
			$acesso_pessoa->$aux_name = "$value[value]";
		}
		//echo "$key ... $value[name] - $value[value] <br>";	
     }
}

//print "<pre>";
//print_r(pessoas);
//
//print "<pre>";
//print_r($_FILES);
////exit;

switch ($_REQUEST["acao"]) {
	case 'atualizar':
		
		break;
	case 'inserir':
		
		break;
		
	case 'listar':
		if(isset($_REQUEST["data"])){
			$acesso_pessoa->data = ConverteData($_REQUEST["data"]);	
		}
		$retorno = $acesso_pessoaDB->lista_acesso_pessoa($acesso_pessoa, $conexao_BD_1);
		break;	
		
}
echo  json_encode($retorno);
exit(); 
?>