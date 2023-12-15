<?php 
require_once('../cnx/bd.php');
require_once('../func/crypt.php');

if(isset($_POST["email"])){
  $email = mysql_real_escape_string($_POST["email"]); 
  if($email != "")
    {
      $sql_mail = "SELECT email FROM clientes WHERE email = '$email'";
      $exe_mail = mysql_query ($sql_mail, $bd) or die (mysql_error());
      $num_mail = mysql_num_rows ($exe_mail);
      if ($num_mail == 1) 
	{
	  $sql_nome = "SELECT nome FROM clientes WHERE email = '$email'";
	  $exe_nome = mysql_query ($sql_nome, $bd) or die (mysql_error());
	  $anome = mysql_fetch_assoc($exe_nome); $nome = $anome['nome'];
	  $titulo = "Atendimento MedJet";
	  
	  include "newpasscontent.php"; 
	  $email_dest = $email ;	
	  $nome_dest = $nome;
	  $filename = random_str(64).".php";
	  $eh = md5($email);
	  $assunto = "Recuperar Senha MEDJET";
	  $mensagem = '

<p>Ol&aacute; '.$nome.'</p>

<p>Estamos respondendo &agrave; requisi&ccedil;&atilde;o de recuperar sua senha perdida no www.medjet.com.br .</p>
<p>Por respeitarmos a privacidade de nossos usu&aacute;rios, n&atilde;o enviamos as senhas de nossos clientes por e-mail. </p>

<p>Para criar uma nova senha, <a href="http://www.medjet.com.br/n/'.$filename.'?e='.$eh.'">clique aqui</a>.
</p>

<p>A MedJet segue as recomenda&ccedil;&otilde;es de seguran&ccedil;a da OWASP (The Open Web Application Security Project) 
e por isso as senhas de nossos clientes s&atilde;o armazenadas de forma criptografada, não sendo possível recuperá-la.</p>
<p>Para sua seguran&ccedil;a n&atilde;o revele sua senha a ningu&eacute;m.<br /></p>
<p>
<br />Atenciosamente,
<br /><br />MedJet.<br />
<a href="http://medjet.com.br">www.medjet.com.br </a></p>';

	  include "inc_mail.php";
	  $file = fopen("../n/".$filename, 'w') or die("can't open file");
	  $content = newpasscontent($email, $filename, $eh);
	  fwrite($file,$content);
	  fclose($file);

	}
      else 
	{
	  $aviso = $email."<br />Este e-mail n&atilde;o est&aacute; cadastrado";
	}
    }
  else
    {
      $aviso = $email."<br />Este e-mail n&atilde;o est&aacute; cadastrado";
    }
}
?>


<!DOCTYPE html>

<html lang="en-us">

  <head>

    <meta charset="iso-8859-1">

    <title>Medjet - Recuperar senha</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Produtos para Saúde">

    <meta name="author" content="Viano">

    <!-- Le styles -->

    <link href="../css/bootstrap.css" rel="stylesheet">

    <style type="text/css">

      body {

        padding-top: 60px;

      }

    </style>

    <link href="../css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->

    <!--[if lt IE 9]>

     <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

    <![endif]-->

    <!-- Le fav and touch icons 

    <link rel="shortcut icon" href="ico/favicon.ico">-->

  </head>

  <body>

 <?php include ('../inc/inc_top_limpo.php')?>

  <div class="container-fluid">

      <div class="row-fluid">


        <!--/span coluna esquerda -->



        <!-- coluna cetral -->



        <div class="span12">
				<div id="cont" style="margin-top:-50px;">



                

                       <form action="envsenha.php?enviado" method="post" class="form-horizontal login " id="cadastrado">
					<?php 
					
					
					 if($aviso == "E-mail Enviado!"){ ?>
                    <h4 class="color_red">Você receberá um e-mail para redefinir sua senha!<br>(Conferir também a caixa de spam)</h4>
                    <?php }elseif(strlen($aviso) > 10 ){?><h4 class="color_red">E-mail não cadastrado!</h4> <?php }  ?>
					
                    <h2>Redefinir Senha</h2>

                    <div class="control-group">
					<input name="email" type="text" class="ctext" id="email" placeholder="Digite o e-mail"/>


                    </div>


                     <button class="btn btn-large btn-primary" type="submit">Continuar <i class=" icon-chevron-right icon-white"></i></button>

                   
                    </form>
                    <div class="avisso" style="margin-top:-80px; margin-left:70px; font-weight:bold; color:#0044cc; font-size:14px;"><? echo $aviso;?></div>
               <tr>
                
                <td>

                    <input name="tipofrete" type="hidden" value="<?php echo $tipofrete; ?>">

                    <input name="cep" type="hidden" value="<?php echo $cep; ?>">

                    <input name="vlfrete" type="hidden" value="<?php echo $vlfrete; ?>">
                </td>
              </tr>
</div>


        </div><!--/span-->

		<!--Coluna direita-->


      </div><!--/row-->


    </div><!--/.fluid-container-->

    <!-- Le javascript

    ================================================== -->

    <!-- Placed at the end of the document so the pages load faster -->

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>

    <!-- mascara para formulário -->    

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-5227413-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body></html>
<?php
	mysql_close();
?>




