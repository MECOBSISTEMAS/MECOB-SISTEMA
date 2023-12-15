<?php

function newpasscontent($email, $filename, $eh)
{

$html = <<<T
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
@charset "utf-8";
body {
	margin-left: 2px;
	margin-right: 2px;
	background-color: white;
	margin-top: 1px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	margin-bottom: 0px;
}
.avisso {
	display:block; 
	color:red;
	margin-top: 40px;
	padding-right: 0px;
	padding-bottom: 10px;
	padding-left: 0px;
	font-size: 14px;
	font-weight: bold;
	text-align:center;
}
.msgs{display:block; margin-top:10px;}
#unique{margin-left:35%;margin-right:35%;}
#unique h2{text-align:center; font-size:18px;margin-top:40px;}
#senha_nova{margin-left: 84px;margin-top:30px;}
#senha_antiga{margin-left:20px; margin-top:30px;}
#altera{margin-left:290px; margin-top:20px;}
#cima {background:url(../images/bg_h3.jpg) black repeat-x;
       text-align:center}
#sprypassword1 .passwordRequiredMsg{margin-top:10px;width:280px;}
#spryconfirm1 .confirmRequiredMsg{margin-top:10px;width:280px;}


</style>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name= "description" content= "MedJet - Painel de Controle do Cliente"/>
<meta name="language" content="portuguese, pt-br">
<meta name="author" content="Ricardo Mira - medjet@medjet.com.br"/>
<title>MedJet - Painel do Usu&aacute;rio</title>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet">
<script type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->

</script>
</head>
<body>
   <div id="cima">
  <img src="../img/medjet.png" /> 
   <?php //include ('../inc_top.php')?>
   <a name="topo" id="topo"></a>
   </div>
   
<div class="row-fluid">   
   <div class="span4 offset4">
<div  style="border:#999 2px solid; padding: 0px 20px 10px 20px; text-align:center;">
   <h2 style=" color:#0044cc; font-size:24px;">Redefinir Senha</h2>
   <div class="msgs">
	  <span class="passwordMinCharsMsg"  style="color:#00F; font-weight:bold;">* No m&iacute;nimo 6 caracteres e no m&aacute;ximo 20 caracteres</span>
	 </div> 
     <br />
<form id="troca_senha" name="troca_senha" method="post" action="$filename?e=$eh">
	
	   <span id="sprypassword1" style=" font-weight:bold">Nova Senha: <input name="senha_nova" type="password"  id="senha_nova"></span>
	   <br>
       <span id="spryconfirm1"  style=" font-weight:bold">Repita a Nova Senha: <input name="senha" type="password"  id="senha_antiga" /></span>
	  	  <?php if (\$aviso) echo '<span class="avisso"><strong>'.\$aviso.'</strong></span>'; 
			?>
      <button class="btn btn-large btn-primary" id="altera" type="submit">Alterar <i class=" icon-chevron-right icon-white"></i></button>
	  <input name="email" type="hidden" value="$email" /> <br>

</form>
</div>
</div>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1", {minChars:6, maxChars:20, validateOn:["blur"]});
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "senha_nova", {validateOn:["blur"]});
</script>
</body>
</html>
T;

$php = <<<S
<?php 
require_once('../func/crypt.php');
require_once('../cnx/bd.php'); 
\$aviso = null; \$ok = null;
if (isset(\$_GET['e'])) {
	\$e = mysql_real_escape_string(\$_GET['e']);
if (\$e == md5('$email')) {
if(isset(\$_POST['senha']) && isset(\$_POST['senha_nova']))
  {
    \$novasenha2 = mysql_real_escape_string(\$_POST['senha']);
    \$novasenha = mysql_real_escape_string(\$_POST['senha_nova']);
    \$email = mysql_real_escape_string(\$_POST['email']);
    if ((strlen(\$novasenha) >= 6) && (strlen(\$novasenha2) >= 6))
    {
    \$secure = new crypt();
    if(\$novasenha == \$novasenha2) // confirmacao da senha digitada no input
      {
	    \$secure->register(\$novasenha); 
	    \$sql_update = "UPDATE clientes SET saltdb = '\$secure->saltdb' , password = '\$secure->password' WHERE email = '\$email'";
	    \$exe_update = mysql_query(\$sql_update, \$bd) or die (mysql_error());
	    ob_start();
		if(!isset(\$_SESSION)) session_start();
	    \$_SESSION['recfile'] = '\$filename'; 
		\$_SESSION['email_usuario'] = '\$email'; 
		\$_SESSION['senha_usuario'] = 'mestrejet@614.'; 
		require_once("../app/login.php");
      } else \$aviso = 'Senhas n&atilde;o conferem.';
    } else \$aviso = 'Digite uma senha com pelo menos 6 caracteres';
  }  
} else header("Location:http://www.medjet.com.br");
} else header("Location:http://www.medjet.com.br");
?>
S;

return $php.$html;

}

function mailmessage ($nome, $filename, $eh)
{
 return <<<S
<p><img src="http://www.medjet.com.br/img/medjet.png" width="112" height="47" /></p>

<p>Ol&aacute; $nome</p>

<p>Estamos respondendo &agrave; requisi&ccedil;&atilde;o de recuperar sua senha perdida no www.medjet.com.br.
Por respeitarmos a privacidade de nossos usu&aacute;rios, n&atilde;o enviamos as senhas de nossos clientes por e-mail. </p>

<p>Para criar uma nova senha, <a href="http://www.medjet.com.br/n/$filename?e=$eh">clique aqui</a>.
</p>

<p>A MedJet segue as recomenda&ccedil;&otilde;es de seguran&ccedil;a da OWASP (The Open Web Application Security Project) 
e por isso as senhas de nossos clientes s&atilde;o armazenadas de forma criptografada.</p>
<p>Para sua seguran&ccedil;a n&atilde;o revele sua senha a ningu&eacute;m.<br /></p>
<p>
<br />Atenciosamente,
<br /><br />MedJet.<br />
<a href="http://medjet.com.br">www.medjet.com.br </a></p>
S;
}
?>
