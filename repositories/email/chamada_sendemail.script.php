<?php
#ini_set('display_errors',1);
#ini_set('display_startup_erros',1);
#error_reporting(E_ALL);
//include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/email/email.db.php");
include_once(getenv('CAMINHO_RAIZ')."/inc/crypt.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

$msg 			  = array();
$emailDB  = new emailDB();

$ret_next_send_mail = $emailDB->next_send_mail($conexao_BD_1);

//print_r($ret_next_send_mail);

$next_send_mail = $ret_next_send_mail[0];

if(empty($next_send_mail['id'])){ echo "nenhum email na lista"; exit;}

//echo '<br> id next send_mail: '.
$next_send_mail_id = $next_send_mail['id'];

if(!is_numeric($next_send_mail_id)){echo "email invalido"; exit;}

$assunto = base64_decode($next_send_mail['assunto']);
$mensagem = base64_decode($next_send_mail['mensagem']);
$email_dest = $next_send_mail['email_dest'];
$nome_dest = $next_send_mail['nome_dest'];
$email_reply = 'contato@mecob.com.br';
$nome_reply = 'MECOB';

$envia_emails = $emailDB->lista_send_mail($conexao_BD_1,$next_send_mail_id);

//echo "<pre>";print_r($envia_emails);echo "</pre>";

########## prepara email
$destinatarios = array();
$confirma_envio = '';
foreach($envia_emails as $env_mail){
	if(strlen($confirma_envio)>0){
		$confirma_envio .= ",";
	}
	$confirma_envio .= $env_mail["id"];
	$destinatarios[] = $env_mail["email"];
}
//  REVIEW HOMOLOGAÇÃO
//$destinatarios = array('thiag0_c@hotmail.com', 'mauricio.rosa@gmail.com'); //, 'contato@mecob.com.br');
//  FINAL REVIEW HOMOLOGAÇÃO


//echo "<br> conf: ".$confirma_envio."<br>";
//echo "<pre>";print_r($destinatarios);echo "</pre>";


if(strlen($confirma_envio)==0){echo "nenhum destinatario na lista"; exit;} 

$header = "MIME-Version: 1.0\n"; 
$header .= "From: contato@mecob.com.br";


// 	ENVIO DE EMAIL COMENTADO ENQUANTO EM HOMOLOGAÇÃO

//include(getenv('CAMINHO_RAIZ')."/inc/mail/inc_mail.php");
//if(isset($enviado) && $enviado){
//	$emailDB->confirma_send_mail($conexao_BD_1,$confirma_envio);
//	$retorno = "e-mails enviados com sucesso!"; 
//}
//else{ $retorno = 'Por favor, tente novamente!'; }

// CONFIRMA ENVIO DIRETO ENQUANTO HOMOLOGAÇÃO
$emailDB->confirma_send_mail($conexao_BD_1,$confirma_envio);

echo '<br>'.$retorno;


?>