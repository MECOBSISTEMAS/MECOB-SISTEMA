<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');

include_once($raiz."/repositories/pessoas/pessoas.class.php");
include_once($raiz."/inc/combos.php");

$layout_title = "MECOB - Segunda via";
$addcss= '<link rel="stylesheet" href="'.$link.'/css/smoothjquery/smoothness-jquery-ui.css">';
include($raiz."/partial/html_ini.php");

include_once($raiz."/inc/util.php");

$menu_active = 'segunda_via';
?>

    <div>
        <!--BEGIN BACK TO TOP-->
        <a id="totop" href="#"><i class="fa fa-angle-up"></i></a>
        <!--END BACK TO TOP-->
        <!--BEGIN TOPBAR-->
        
		
        <?php
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
//verifica quantidade de mensagens novas
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");



?>

<div id="header-topbar-option-demo" class="page-header-topbar">
            <nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" class="navbar navbar-default navbar-static-top">
            <div class="navbar-header">
                <button id="bt_hd_resp" type="button" data-toggle="collapse" data-target=".sidebar-collapse" class="navbar-toggle"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                <a id="logo" href="<?php echo $link;?>/dashboard" class="navbar-brand">
                <span class="fa fa-rocket"></span>
                 <img src="<?php echo $link."/imagens/logo_header.jpg";?>" alt="" class="img-responsive img-circle img-header " width="46px"/>
                <span class="logo-text al fs-32 dourado mg-lf-50">
               
                MECOB
                </span>
                </a>
            </div>
            <div class="topbar-main">
                
                <form id="topbar-search" action="" method="" class="hidden-sm hidden-xs hidden">
                    <div class="input-icon right text-white"><a href="#"><i class="fa fa-search"></i></a><input type="text" placeholder="Busque aqui..." class="form-control text-white"/></div>
                </form>
                <div class="new-update-box hidden hidden-xs hidden-sm ">
                		<!--  PUBLICAR ARRAY DE TEXTOS DO HEADER EM corejs.php-->
                        <div id="header-text"  >
                        </div>
                        <span id="header-cobre-text" style="  position:absolute; min-height:50px;  top:0; right:300px; "></span>
                </div>
                <ul class="nav navbar navbar-top-links navbar-right mbn"> 
					
                    <li class="dropdown topbar-user">
                    <a href="<?php echo $link;?>/acesso.php"><i class="fa fa-key"></i>Fazer Login</a>
                        
                    </li>
                </ul>
            </div>
        </nav>
        
        <!--END TOPBAR-->
       <div id="wrapper">
			
            <!--BEGIN PAGE WRAPPER-->
            <div id="page-wrapper" class="mg-0">
                <!--BEGIN TITLE & BREADCRUMB PAGE-->
                <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                    <div class="page-header pull-left">
                        <div class="page-title">
                        Contratos    
                        </div>
                    </div>
                    
                    <div class="clearfix">
                    </div>
                </div>
                <!--END TITLE & BREADCRUMB PAGE-->
                <!--BEGIN CONTENT-->
                <div class="page-content">
                    <div id="tab-general">
                        <div class="row mbl">

                   <div class="col-lg-12">
                     <div class="row">
                     	<?php
						$solicitar_cpf = 0;
						$msg_cpf = "";
						$val_cpf = "";
						if(!isset($_GET['cpf'])){ 
							$solicitar_cpf = 1;
						}
						elseif(!is_numeric($_GET['cpf']) || !validarCPF($_GET['cpf'])){
							$solicitar_cpf = 1;
							$msg_cpf = '<h4 class="red_light">CPF inválido!</h4>';
							$val_cpf = $_GET['cpf'];
						}
						else{
							include_once($raiz."/repositories/pessoas/pessoas.db.php");
							include_once($raiz."/repositories/pessoas/pessoas.class.php");
							
							$pessoasDB 	   = new pessoasDB();
							$pessoas 		 = new pessoas();
							
							
							
							$pessoasDB->lista_by_cpf($_GET['cpf'],$conexao_BD_1);
							$totalRows_login = $conexao_BD_1->numeroDeRegistros();
							
							if($totalRows_login!=1){
								$solicitar_cpf = 1;
								$msg_cpf = '<h4 class="red_light">Encontrado '.$totalRows_login.' pessoas com este CPF.</h4>';
								$val_cpf = $_GET['cpf'];
							}
							else{
								$pessoa = $conexao_BD_1->leRegistro();
								$id = $pessoa['id'];
								$ehCliente = true;
							}
						}
						
						if($solicitar_cpf){ ?>
							
							<div class="col-md-8 col-md-offset-1">
                        		<div class="row topo_view_pessoa ">
                                    <form action="javascript:consultaCPF()" class="form-horizontal">
                                    		<h3 class="mg-tp-0">Informe o seu cpf para visualizar seus boletos:</h3>
                                        	<input id="inputCPF" type="number" name="cpf"  placeholder="CPF" class="form-control" value="<?php echo $val_cpf;?>"  />
                                            
                                            <?php if($msg_cpf!=""){echo $msg_cpf;}else echo '<br />';?>
                                            <button type="submit" class="btn btn-primary">Consultar</button>
                                    </form>
                                </div>
                            </div>
							
						<?php
						}
						else{	 ?>
                     
                   		<div class="col-md-12">
                        <div class="row topo_view_pessoa ">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="text-center mbl" style="position:relative">
                                    	<div style="position:relative">
                                        <div class="uploading_user_foto hidden">
                                                <h4>Carregando Foto</h4>
                                                <br>
                                                <div><span id="kb_upado"></span></div>
                                                <br>
                                                <div class=" progress progress-striped">
                                                  <div id="bar_up_foto" class="progress-bar progress-bar-success  " role="progressbar" aria-valuenow="00" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                    0%
                                                  </div>
                                                </div>
                                     	 </div>
                                                <img id="foto_usr" src="<?php echo img("/imagens/fotos/thumb/",$pessoa["foto"]);?>" alt="" class="img-responsive wd-100p"/>
                                                
                                            </div>
                                        
                                    </div>
  
                                	</div>
                          		</div>
                                 <div class="col-md-9 ">
                                    <h3 class="mg-tp-0"><?php echo $pessoa["nome"]; ?></h3>
                                        <p><?php echo $pessoa["email"]; ?></p>
                                        
										<?php 
												echo '<p>';
												if ($pessoa["rua"] != ""){
													echo $pessoa["rua"].', '.$pessoa["numero"]." - ";
                                         		}
												if ($pessoa["cidade"] != ""){
												 	echo $pessoa["cidade"].'/'.$pessoa["estado"];
												}
												echo '</p>';
												if ($pessoa["cep"] != ""){
                                                 	echo '<p>CEP: '.$pessoa["cep"].'</p>';
												}
										 ?>
                                         </p>
                                        <?php
										if (strlen($pessoa["dt_ativo"]) >10){
											echo '<p>Membro desde: '.ConverteData($pessoa["dt_ativo"]).'</p>';
										}
										if ($pessoa["status_descricao"] == "ATIVO"){
											echo '<span class="label label-success">Ativo</span>';
										}
										else{
											echo '<p>'.$pessoa['status_descricao'].'</p>';
										}
										?>
                                </div>
                </div>
                
                
                
                		<div class="quadros_view_pessoa row ">
                            <div id="profilecomprador" class="profilebox col-md-12 ">
                                <div id="generalTabContent" class="tab-content">
                                    <div id="tab-edit" class="tab-pane fade in active">
                                        <?php include($raiz.'/adm/pessoas/view_pessoas_comprador.php');?>
                                     </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                              
           </div>
                                
                            
                     
                            
                        </div>
                    </div>
                </div>
                <!--END CONTENT-->
                <!--BEGIN FOOTER-->
                <?php include($raiz."/partial/footer.php");?>
                <!--END FOOTER-->
            </div>
            <!--END PAGE WRAPPER-->
        </div>
    </div>
    <!-- fim cadastro de condominios-->
	<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery-1.10.2.min.js"></script>
	<script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery-migrate-1.2.1.min.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery-ui.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/bootstrap.min.js"></script>
    
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/geral.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/alerts/jquery.alerts.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/bootstrap-hover-dropdown.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/script/jquery.menu.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/responsive.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/css3-animate-it.js"></script>
    <script src="<?php echo getenv('CAMINHO_SITE');?>/js/bootstrap-multiselect.js"></script>
    <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.form.js"></script>
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
    <script src="<?php echo $link;?>/js/highcharts.js"></script>
	<script src="<?php echo $link;?>/js/highcharts_exporting.js"></script>
    
    <script>
		$("#inputCpf").mask("999.999.999-99");
		

		
		function consultaCPF(){
			cpf = $('#inputCPF').val();
			url = '<?php echo $link."/segunda_via/";?>'+cpf;
			window.location = url;
		}
		

	</script>
    
    

    
    
</body>
</html>
