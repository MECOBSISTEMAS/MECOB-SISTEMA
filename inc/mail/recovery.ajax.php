<?php
$aviso ="";
$email = $_GET['email'];
#$email = 'email@email.com.br';
$enviado ="";
if(strlen($email)==0){
	$aviso = "Preencha o seu e-mail!";
}
else{
	include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
	include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.db.php");
	include_once(getenv('CAMINHO_RAIZ')."/repositories/pessoas/pessoas.class.php");
	
	$pessoasDB  = new pessoasDB();
	$pessoas    = new pessoas();
	$pessoas->email=$email;
	$retorno = $pessoasDB->lista_pessoas($pessoas, 0,"","","", $conexao_BD_1);
	
	$totalRows_login = count($retorno);
	#print_r($retorno);

	//confere senha e usuario
	if($totalRows_login==0){
		$aviso = "E-mail não cadastrado - contate-nos!";
	}
	elseif($totalRows_login==1)
	{	
		$res = $retorno[0];
		
		
		if($res["status_id"]!="1"){
				$aviso = "Conta desativada - contate-nos!";
		}else{
			 
			$senha="";
			$caracteres = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789';
			$len = strlen($caracteres);
			
			for ($n = 1; $n <= 7; $n++) {
				$rand = mt_rand(1, $len);
				$senha .= $caracteres[$rand-1];
			}
			include_once(getenv('CAMINHO_RAIZ')."/inc/crypt.php");
			$secure = new crypt();
			$secure->register($senha);
			
			$pessoas->id = $res["id"];
			$pessoas->password = $secure->password;
			$pessoas->saltdb = $secure->saltdb;
			
			#print_r($pessoas);
			
			if($conexao_BD_1->update($pessoas)){
				
				$mensagem = "<table style=\"margin-left:7; margin-right:7; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px\">
				<tr><td><h3><strong>Recuperação de senha - MECOB!</h3></td></tr>
				<tr><td></td></tr>
				<tr><td><strong>Olá ".$res["nome"].",</strong> recebemos uma solicitação para recuperação de senha</td></tr>
				<tr><td></td></tr>
				<tr><td>Seu Login é o seu e-mail, e sua nova senha  é: ".$senha."</td></tr>
				<tr><td><a href='http://sistema.mecob.com.br'>Acessar o sistema!</a></td></tr>
				
				
				<tr><td></td></tr>
				</table>";
				
				
				
				$email_dest =  $res["email"]; 
				$nome_dest = $res["nome"];
				$assunto = "MECOB - Recuperação de Senha!";
				$header = "MIME-Version: 1.0\n"; 
				$header .= "From: MECOB\n";
				
				include(getenv('CAMINHO_RAIZ')."/inc/mail/inc_mail.php");
				if(isset($enviado) && $enviado){$aviso = "ok"; }
				else{$aviso = "Falha no envio do e-mail - contate-nos!"; }
			}
			else{
				$aviso = "Falha ao gerar nova senha - contate-nos!";
			}
		}
		
	}else{ $aviso = "Falha na identificação do seu e-mail - contate-nos!"; }
}
	


echo json_encode($aviso);