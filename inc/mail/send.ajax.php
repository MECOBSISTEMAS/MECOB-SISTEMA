<?php

$retorno = "Por favor, tente novamente!";
	
	if(empty($_REQUEST['msg_nome'])){
		$retorno = 'Preencha o seu Nome!';
	}
	elseif(empty($_REQUEST['msg_mail'])){
		$retorno = 'Preencha o seu e-mail!';
	}
	elseif (!filter_var($_REQUEST['msg_mail'], FILTER_VALIDATE_EMAIL)){
		$retorno = 'Preencha o seu e-mail corretamente!';
	}
	elseif(empty($_REQUEST['msg_msg'])){
		$retorno = 'Preencha a mensagem!';
	}
	else{
		$mensagem = "<table style=\"margin-left:7; margin-right:7; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">
		<tr><td><h3><strong>MECOB - Contato via formulário do site!</h3></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td><strong>Dados Enviados:</strong></td></tr>
		<tr><td></td></tr>
		<tr><td>Nome: ".$_REQUEST['msg_nome']."</td></tr>
		<tr><td>Email: ".$_REQUEST['msg_mail']."</td></tr>
		<tr><td>Link: ".$_REQUEST['msg_link']."</td></tr>
		<tr><td>Mensagem: ".$_REQUEST['msg_msg']."</td></tr>
		<tr><td></td></tr>
		
		
		<tr><td></td></tr>
		</table>";
		$email_dest =  'contato@mecob.com.br';  
		$nome_dest ='MECOB Contato';
		$assunto = "MECOB - Contato via MECOB";
		$header = "MIME-Version: 1.0\n"; 
		$header .= "From: ".$_REQUEST['msg_mail']."\n";

		include(getenv('CAMINHO_RAIZ')."/inc/mail/inc_mail.php");
		if(isset($enviado) && $enviado){$retorno = "ok"; }
		else{ $retorno = 'Por favor, tente novamente!'; }
	}
		


echo json_encode($retorno);