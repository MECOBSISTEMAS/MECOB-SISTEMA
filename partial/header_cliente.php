<?php
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
//verifica quantidade de mensagens novas
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/dashboard/dashboard.db.php");
$dashboard  = new dashboard();

?>

<div id="header-topbar-option-demo" class="page-header-topbar">
            <nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" class="navbar navbar-default navbar-static-top">
            <div class="navbar-header">
                <button id="bt_hd_resp" type="button" data-toggle="collapse" data-target=".sidebar-collapse" class="navbar-toggle"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                <a id="logo" href="<?php echo $link;?>/dashboard" class="navbar-brand">
                <span class="fa fa-rocket"></span>
                 <img src="<?php echo $link."/imagens/logo_header.jpg";?>" alt="" class="img-responsive img-circle img-header "/>
                <span class="logo-text al fs-32 dourado mg-lf-50">
               
                MECOB
                </span>
                </a>
            </div>
            <div class="topbar-main"><a id="menu-toggle" href="#" class="hidden-xs"><i class="fa fa-bars"></i></a>
                
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
                <li class="animatedClick pointer" data-target="animate_notificacoes"><a id="a_animate_notificacoes"
                        onclick="ver_notificacoes();"><i class="fa fa-bell fa-fw"></i>
                        <span id="badge-notificacoes" class="badge badge-green"></span>
                    </a>

                </li>


                <li class="dropdown hidden"><a data-hover="dropdown" href="#" class="dropdown-toggle"
                        onclick="mostra_alertas();"><i class="fa fa-bell fa-fw"></i><span
                            class="badge badge-green">3</span></a>

                </li>
                    <li class="dropdown topbar-user"><a data-hover="dropdown" href="#" class="dropdown-toggle">
                    
					<img src="<?php echo img("/imagens/fotos/nail/",$_SESSION['foto']); ?>" alt="" class="img-responsive img-circle "/>
									
                    
                    &nbsp;<span class="hidden-xs">
					<?php if(!empty($_SESSION['apelido'])) 
							echo $_SESSION['apelido'];
						  else{
							  $nome = explode(" ", $_SESSION['nome']);
							  echo $apelido = $nome[0];
						  }
					?></span>&nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-user pull-left">
                            <li><a href="<?php echo $link."/pessoa/".$user_id;?>"><i class="fa fa-user"></i>Perfil</a></li>

                            <li class="divider"></li>
                            
                            <li><a href="<?php echo $link;?>/sair"><i class="fa fa-key"></i>Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        
        
        
        
        
           
        
        <div id="div_notificacoes" class="animated animate_notificacoes  bounceInDown  fadeOutDown " style="display:none;">
        <!-- <button class="btn btn-link" style="float:right;margin-right:10px;" onclick="ver_notificacoes();"><i class="fa fa-times"></i></button> -->
        <div id="insert_notific" class="row">
            <?php
                        $ct_notf=0;
                        $lista_alertas = array();
                        foreach($lista_alertas as $notifc){ 
                            break;
                            #if($ct_notf>0){echo '<hr>';}
                            ?>
            <div class="col-md-12 ">
                <?php 
                            if(!empty($notifc['link'])){
                                echo '<a href="'.$notifc['link'].'"> ';
                            }
                            ?>
                <div class="item_notificacoes <?php if($notifc['visualizado']=='N')echo 'bk_blue_light_notifc';?>">

                    <div class="col-xs-11 pd-tp-3">
                        <?php echo ConverteData($notifc['data_alerta']);?>
                        <br />
                        <?php echo $notifc['descricao'];?>
                    </div>
                    <div class="col-xs-1 pd-tp-3">
                        <?php   if(!empty($notifc['descricao']) && $notifc['descricao'] == 'S'){ ?>
                        <i class="fa fa-thumbs-up fs-19 green_light "> </i>
                        <?php	 }
                                else{ ?>
                        <i class="fa fa-exclamation-triangle red-light"></i>
                        <?php
                                }
                                ?>
                    </div>

                </div>
                <?php
                                if(!empty($notifc['link'])){
                                    echo '</a> ';
                                }
                                ?>
            </div>
            <?php
                                $ct_notf++;
                            }
                            ?>

        </div>


        <div class="row">
            <div class="col-md-12 ">
                <div id="item_notificacoes_load" class="item_notificacoes pointer" onclick="load_more_notificacoes();">
                    <div class="col-xs-12 ac pd-tp-12">
                        Carregar mais notificações
                        <input type="hidden" id="ct_notifc_exibidos" value="<?php echo $ct_notf;?>" />
                    </div>
                </div>
                <div id="item_notificacoes_loading" class="item_notificacoes hidden">
                    <div class="col-xs-12 pd-tp-12 ac">
                        <img src="<?php echo getenv('CAMINHO_SITE')."/imagens/loading_circles.gif";?>" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    document.ready = function() {
        check_alerts();
        setInterval(() => {
            check_alerts();
        }, 60000);
    }

    function check_alerts() {
        $.ajax({
            method: "GET",
            url: "<?php echo $link?>/repositories/alertas/alertas.ctrl.php",
            data: {
                acao: "check_alertas"
            }
        }).done(function(data) {
            data = parseInt(data.replace('"', ''));
            if (parseInt(data) > 0) {
                $('#badge-notificacoes').html(data);
                // toastr.options.timeOut = 40000;
                // toastr.warning('Você tem notificações não visualizadas!')
                // $('#md_alerts').modal('show');
            }
        });

        $.ajax({
            method: "GET",
            url: "<?php echo $link?>/repositories/alertas/alertas.ctrl.php",
            data: {
                acao: "check_alertas_atrasados"
            }
        }).done(function(data) {
            data = parseInt(data.replace('"', ''));
            if (parseInt(data) > 0) {
                $('#badge-notificacoes').html(data);
                // toastr.options.timeOut = 40000;
                // toastr.error('Você tem notificações com prazo de conclusão atrasados!')
                // $('#md_alerts').modal('show');
            }
        });
    }
    </script>