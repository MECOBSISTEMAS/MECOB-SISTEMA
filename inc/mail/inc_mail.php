<?php

$smtp_host = 'smtp.office365.com'; 
$smtp_mail = 'adm@mecob.com.br';
$smtp_senha = 'm|3H?u2TJ';
$smtp_port = '587';
$secure_mail = "tls";
$is_auth = true;

// enviar via post assunto, $mensagem(html), $email_dest, $nome_dest, $email_reply, $nome_reply

if (empty($email_dest)){ $email_dest = $smtp_mail;}
if (empty ($nome_dest)){$nome_dest = "MECOB";}
if (empty ($email_reply)){$email_reply = $smtp_mail;	}
if (empty ($nome_reply)){$nome_reply = "MECOB";}


//error_reporting(E_ALL);
error_reporting(E_STRICT);
date_default_timezone_set('America/Sao_Paulo');

require_once("mailer/class.phpmailer.php");
//include("mailer/examples/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();
//<img src='$cfg_geral_url_teia/imagens/logo_header.png'>  &nbsp;&nbsp;&nbsp;&nbsp;
//<img src='$cfg_geral_url_teia/imagens/$cfg_geral_logo'>
$body             = "<body style=\"margin: 10px;\">
<div style=\"width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 15px;\">
	".$mensagem."
	<br><br>
	
	<a href='https://sistema.mecob.com.br' target='_blank' style='color:#333333; text-decoration: none; '>
		<strong>Acessar o sistema!</strong>
		
		<br><br>
		Atenciosamente, <br>
		<div>
			<strong>Motta & Etchepare Ltda. </strong> <br /><br />
			<img src='".getenv('CAMINHO_SITE')."/imagens/logo.jpg' width='500px'>
		</div>
	</a>
</div>
<br /><br />
</body>";

#$body             = eregi_replace("[\]",'',$body); ??????????????????????

$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug = false;
$mail->do_debug = 0;
$mail->SMTPDebug  = 0;                    // enables SMTP debug information (for testing)
// 1 = errors and messages
// 2 = messages only									
if( $is_auth){
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = $secure_mail;                  // sets the prefix to the servier
	$mail->Username   = $smtp_mail;           //"thiago@teia.inf.br";  // username
	$mail->Password   = $smtp_senha;          //pass";           //  password
}
else{$mail->SMTPAuth   = false;}

$mail->Host       = $smtp_host;             //"webserver05.floripa.com.br";      // sets GMAIL as the SMTP server
$mail->Port       = $smtp_port;             // 465;     // set the SMTP port for the GMAIL server
$mail->Priority    = 1;

$mail->SetFrom($smtp_mail, 'MECOB');

$mail->AddReplyTo($email_reply, $nome_reply);

$mail->Subject    = $assunto;

$mail->IsHTML(true);
$mail->MsgHTML($body);
$address = $email_dest;
$mail->AddAddress($address, $nome_dest);

if(!empty($destinatarios) && is_array($destinatarios)){
	foreach($destinatarios as $mail_cc){
		$mail->AddBCC($mail_cc);
	}
}

//copias
if($email_dest != $smtp_mail){
	$mail->AddBCC($smtp_mail);
}

if($_SERVER['SERVER_NAME'] == 'localhost'){
	$enviado = 1;
}
else{
	if(!$mail->Send()) {
		$enviado = 0;
	} else {
	  $enviado = 1;
	}
}


?>

