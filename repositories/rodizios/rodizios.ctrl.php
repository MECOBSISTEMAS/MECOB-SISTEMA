<?php
$is_pagina_perfil=1;

include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/rodizios/rodizios.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$msg 		 = array();
$rodiziosDB  = new rodiziosDB();

switch ($_REQUEST["acao"]) {
	case 'gerarRodizio':
		if ($rodiziosDB->gerarRodizio($conexao_BD_1)){
			$retorno = array( 'status' => 1,	'msg'=> "Rodízio Gerado!");			
		} else {
			$retorno = array( 'status' => 0,	'msg'=> "Falha ao gerar rodízio!");
		};
	break;
	case 'carregarRodizios':
		$retorno = $rodiziosDB->carregarRodizios($conexao_BD_1);
	break;
	case 'carregarRodizioId':
		$id = $_REQUEST['id'];
		$retorno = $rodiziosDB->carregarRodizioId($conexao_BD_1,$id);
	break;
	case 'carregarRodizioPessoa':
		$id = $_REQUEST['id'];
		$pessoa = $_REQUEST['pessoa'];
		$retorno = $rodiziosDB->carregarRodizioPessoa($conexao_BD_1,$id,$pessoa);
	break;
	case 'ativarRodizio':
		$id = $_REQUEST['id'];
		// $pessoa = $_REQUEST['pessoa'];
		$retorno = $rodiziosDB->ativarRodizio($conexao_BD_1,$id);
	break;
	case 'carregarListaOperador':
		if (isset($_REQUEST['id'])){ 
			$id = $_REQUEST['id'];
			$limit = 5;
		} else {
			$id = $_SESSION['id'];
			$limit = 0;
		}
		if (isset($_REQUEST['ultimosFeitos']) && $_REQUEST['ultimosFeitos'] == 'N'){
			$ultimosFeitos = false;
		} else {
			$ultimosFeitos = true;
		}
			
		$retorno = $rodiziosDB->carregarListaOperador($conexao_BD_1,$id,$limit,$ultimosFeitos);
	break;
	case 'listarSituacaoAtual':
		$retorno = $rodiziosDB->listarSituacaoAtual($conexao_BD_1);
	break;
	case 'alterarOperador':
		$retorno = $rodiziosDB->alterarOperador($conexao_BD_1,$_POST['operador'],$_POST['vendedor'],$_POST['rodizio']);
	break;
	case 'excluirCarteira':
		$retorno = $rodiziosDB->excluirCarteira($conexao_BD_1,$_POST['vendedor'],$_POST['rodizio']);
	break;
}
echo  json_encode($retorno);