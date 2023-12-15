<?php
if(isset($_GET['id'])){
	$contrato_id = $_GET['id'];
}
else{echo 'Não foi passado nenhum contrato para geração do PDF'; exit;}


include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
 
$msg = array();


include_once(getenv('CAMINHO_RAIZ')."/inc/pdf/pdftermo.php");
// include_once(getenv('CAMINHO_RAIZ')."/inc/word/gera_word_termo.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/parcelas/parcelas.db.php");

$contratosDB  = new contratosDB();
$parcelasDB  = new parcelasDB();
$contratos    = new contratos();
$contratos->id = $contrato_id;
	
$file = "termo_".$contrato_id;

$sql = "SELECT
* from contrato_parcelas where contratos_id = $contrato_id order by nu_parcela";
$parcelas = $conexao_BD_1->query($sql);

//info adicional antes da tabela
$info=array();
$contrato_info = $contratosDB->lista_contratos($contratos, $conexao_BD_1);
$contrato_info = $contrato_info[0];
	
if (isset($_REQUEST['word']) && $_REQUEST['word'] == 's')
	pdftermo($contrato_info,$file,$link,$parcelas,getenv('CAMINHO_RAIZ'),true);
else
	pdftermo($contrato_info,$file,$link,$parcelas,getenv('CAMINHO_RAIZ'));

