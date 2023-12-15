<?php

//este arquivo possui os dados para conexao com o banco de dados
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/cls_bd.php");;

//define('BD_HOST'   , "127.0.0.1"); //192.168.0.10
//define('BD_USUARIO', "root");//f117358_teiausr //root
//define('BD_SENHA'  , "");//305uq3L2bKDN
//define('BD_BANCO'  , "mesistema");//f117358_teiacard  //teiacard

## LOCAL REMOTO
$db_cron = getenv('MYSQL_DB');
$server = getenv('SERVER_NAME');
if (!isset($cron)) $cron = false;

if (($server == 'mecob.how') || ($cron == True && $db_cron == 'dev')){
	$mysql_desc= "<span style='color:#00F'>banco de dados conectado como DESENVOLVIMENTO</span>";
	$mysql_srv = "localhost";
	$mysql_usr = "pedro";
	$mysql_pas = "123";
	$mysql_dat = "u779313693_mecob";
	
} elseif (($server == 'homologacao.mecob.com.br') || ($cron == True && $db_cron == 'hom')) {
	$mysql_desc= "<span style='color:#990'>CUIDADO! Banco de dados conectado como HOMOLOGAÇÃO</span>";
	$mysql_srv = "localhost";
	$mysql_usr = "mecob";
	$mysql_pas = "]qWncL%?A6tq4;Fqaj,3w@ss4@2t#U-_";
	$mysql_dat = "homologacao";
} elseif (($server == 'sistema.mecob.com.br') || ($cron == True && $db_cron == 'pro')) {
	$mysql_desc= "<span style='color:#F00'>banco de dados conectado como PRODUÇÃO</span>";
	$mysql_srv = "localhost";
	$mysql_usr = "mecob";
	$mysql_pas = "]qWncL%?A6tq4;Fqaj,3w@ss4@2t#U-_";
	$mysql_dat = "sistema";
}

define('BD_HOST'   , $mysql_srv);
define('BD_USUARIO', $mysql_usr);
define('BD_SENHA'  , $mysql_pas);
define('BD_BANCO'  , $mysql_dat);

######HOSTINGER

//define('BD_HOST'   , "mysql.hostinger.com.br"); 
//define('BD_USUARIO', "u779313693_mecob");
//define('BD_SENHA'  , "mesistema@2016!");
//define('BD_BANCO'  , "u779313693_mecob");

if(!isset($define_before)){
	define('BD_TIPO_CONNECT'  , "mysql");
}

$conexao_BD_1 = new bancoDeDados(BD_HOST, BD_USUARIO, BD_SENHA, BD_BANCO);

?>
