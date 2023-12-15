<?php
if(!isset($sub_menu_active)){$sub_menu_active='';}
?>
<nav id="sidebar" role="navigation" data-step="2" data-intro="Template has &lt;b&gt;many navigation styles&lt;/b&gt;"
    data-position="right" class="navbar-default navbar-static-side">
    <div id="sidebar_resp" class="sidebar_resp_adm sidebar-collapse menu-scroll ">
        <ul id="side-menu" class="nav">
            <div class="clearfix"></div>
            <?php if($ehCliente){ ?>
            <li class="menu_cliente menu_cliente_perfil <?php if($menu_active == 'perfil'){echo 'active';}?>">
                <a href="<?php echo $link."/pessoa/".$user_id;?>">
                    <i class="fa fa-user fa-fw">
                    </i>
                    <span class="menu-title">Perfil</span>
                </a>
            </li>

            <li class="menu_cliente menu_cliente_venda">
                <a href="<?php echo $link."/pessoa_venda/".$user_id;?>">
                    <i class="fa fa-usd fa-fw">
                    </i>
                    <span class="menu-title">Contratos de Venda</span>
                </a>
            </li>

            <li class="menu_cliente menu_cliente_compra">
                <a href="<?php echo $link."/pessoa_compra/".$user_id;?>">
                    <i class="fa fa-shopping-cart fa-fw">
                    </i>
                    <span class="menu-title">Contratos de Compra</span>
                </a>
            </li>

            <!-- <li class="menu_cliente">
                <a href="<?php //echo $link."/boletos_avulso";?>">
                    <i class="fa fa-folder-open-o fa-fw">
                    </i>
                    <span class="menu-title">Boletos Avulsos</span>
                </a>
            </li> -->

            <li class="menu_cliente  <?php if($menu_active == 'dashboard'){echo 'active';}?>">
                <a href="<?php echo $link."/dashboard_boletos";?>">
                    <i class="fa fa-exclamation-triangle fa-fw">
                    </i>
                    <span class="menu-title">Boletos a vencer</span>
                </a>
            </li>

            <li class=" <?php if($menu_active == 'financeiro'){echo 'active';}?>">
                <a href="<?php echo $link."/parcelas";?>">
                    <i class="fa fa-bars fa-fw">
                    </i>
                    <span class="menu-title">Parcelas</span>
                </a>
            </li>

            <!-- Libera o protocolos o Carlos no login de cliente dele -->
            <?php if(in_array($user_id , array('31')) ) { ?>
                        <li class="menu_cliente">
                            <a href="<?php echo $link."/protocolos/lista_protocolos_servicos";?>">
                                <i class="fa fa-file-text-o fa-fw"></i>
                                <span class="menu-title">Protocolos de Serviços</span>
                            </a>
                        </li>
            <?php }  ?>

            <?php } 
						  else{
					?>

            <li class="<?php if($menu_active == 'dashboard'){echo 'active';}?>">
                <a href="<?php echo $link."/dashboard";?>">
                    <i class="fa fa-area-chart fa-fw">
                    </i>
                    <span class="menu-title">Painel</span>
                </a>
            </li>

            <li class="animatedClick " data-target="animate_sidebar_pessoas"><a id="a_animate_sidebar_pessoas"
                    class="pointer" onclick="showSubmenu('submenu_pessoas');">
                    <i class="fa fa-edit fa-fw"></i>
                    <span class="menu-title">Cadastros</span>
                    <i id="icon_submenu_pessoas" class="fa fa-chevron-circle-down fa-fw pull-right icon_submenu"></i>
                </a>
            </li>

            <?php if(consultaPermissao($ck_mksist_permissao,"cad_pessoas","qualquer")){ ?>
            <li class="submenu-item submenu_pessoas  animated animate_sidebar_pessoas  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'leiloeiros'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/leiloeiros";?>">
                    <i class="fa fa-black-tie fa-fw">
                    </i>
                    <span class="menu-title">Leiloeiros</span>
                </a>
            </li>
            <li class="submenu-item submenu_pessoas  animated animate_sidebar_pessoas  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'compradores'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/compradores";?>">
                    <i class="fa fa-shopping-basket fa-fw">
                    </i>
                    <span class="menu-title">Compradores</span>
                </a>
            </li>
            <li class="submenu-item submenu_pessoas  animated animate_sidebar_pessoas  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'vendedores'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/vendedores";?>">
                    <i class="fa fa-credit-card fa-fw">
                    </i>
                    <span class="menu-title">Vendedores</span>
                </a>
            </li>
            <?php } ?>

            <?php if(consultaPermissao($ck_mksist_permissao,"cad_haras","qualquer")){ ?>
            <li class="submenu-item submenu_pessoas  animated animate_sidebar_pessoas  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'haras'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/haras";?>">
                    <i class="fa fa-diamond fa-fw">
                    </i>
                    <span class="menu-title">Haras</span>
                </a>
            </li>
            <?php } ?>

            <!-- <?php if(consultaPermissao($ck_mksist_permissao,"cad_lotes","qualquer")){ ?>
            <li class="submenu-item submenu_pessoas  animated animate_sidebar_pessoas  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'lotes'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/lotes";?>">
                    <i class="fa fa-shopping-cart fa-fw">
                    </i>
                    <span class="menu-title">Lotes</span>
                </a>
            </li>
            <?php } ?> -->
            <?php if(consultaPermissao($ck_mksist_permissao,"cad_eventos","qualquer")){ ?>
            <li class="submenu-item submenu_pessoas  animated animate_sidebar_pessoas  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'eventos'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/eventos";?>">
                    <i class="fa fa-calendar fa-fw">
                    </i>
                    <span class="menu-title">Eventos</span>
                </a>
            </li>
            <?php } ?>
            <?php if(consultaPermissao($ck_mksist_permissao,"cad_contratos","qualquer")){ ?>

            <li class="animatedClick " data-target="animate_sidebar_carteiras"><a id="a_animate_sidebar_carteiras"
                    class="pointer" onclick="showSubmenu('submenu_carteiras');">
                    <i class="fa fa-edit fa-fw"></i>
                    <span class="menu-title">Carteiras</span>
                    <i id="icon_submenu_carteiras" class="fa fa-chevron-circle-down fa-fw pull-right icon_submenu"></i>
                </a>
            </li>
            <li class="submenu-item submenu_carteiras  animated animate_sidebar_carteiras  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'rodizios'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/carteiras/rodizios";?>">
                    <i class="fa fa-black-tie fa-fw">
                    </i>
                    <span class="menu-title">Rodízios</span>
                </a>
            </li>
            <?php
            if ($_SESSION['operador'] == 'S'){
            ?>
            <li class="submenu-item submenu_carteiras  animated animate_sidebar_carteiras  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'operadores'){echo 'active';}?>"
                style="display:none;">
                <a href="<?php echo $link."/carteiras/operadores";?>">
                    <i class="fa fa-black-tie fa-fw">
                    </i>
                    <span class="menu-title">Operadores</span>
                </a>
            </li>
            <?php
            } elseif ($_SESSION['supervisor'] || $_SESSION['perfil_id'] = 1 || $_SESSION['perfil_id'] = 3) {?>
                <li class="submenu-item submenu_carteiras  animated animate_sidebar_carteiras  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'gerentes'){echo 'active';}?>"
                    style="display:none;">
                    <a href="<?php echo $link."/carteiras/gerentes";?>">
                        <i class="fa fa-black-tie fa-fw">
                        </i>
                        <span class="menu-title">Gerentes</span>
                    </a>
                </li>
            <?php
            }
            ?>
            <?php } ?>
            <?php if(consultaPermissao($ck_mksist_permissao,"cad_contratos","qualquer")){ ?>
            <li class="<?php if($menu_active == 'contratos'){echo 'active';}?>">
                <a href="<?php echo $link."/contratos";?>">
                    <i class="fa fa-folder-open-o fa-fw">
                    </i>
                    <span class="menu-title">Contratos</span>
                </a>
            </li>
            <li class="<?php if($menu_active == 'contratos_analitico'){echo 'active';}?>">
                <a href="<?php echo $link."/contratos_analitico";?>">
                    <i class="fa fa-folder-open-o fa-fw">
                    </i>
                    <span class="menu-title">Contratos - Analítico</span>
                </a>
            </li>
            <?php } ?>
            <?php if(consultaPermissao($ck_mksist_permissao,"cad_boletos","qualquer")){ ?>
            <li class="<?php if($menu_active == 'boletos_avulso'){echo 'active';}?>">
                <a href="<?php echo $link."/boletos_avulso";?>">
                    <i class="fa fa-folder-open-o fa-fw">
                    </i>
                    <span class="menu-title">Boletos Avulsos</span>
                </a>
            </li>
            <?php } ?>
            <?php if( consultaPermissao($ck_mksist_permissao,"cad_parcelas","qualquer")){ ?>
            <li class=" <?php if($menu_active == 'financeiro'){echo 'active';}?>">
                <a href="<?php echo $link."/parcelas";?>">
                    <i class="fa fa-bars fa-fw">
                    </i>
                    <span class="menu-title">Parcelas</span>
                </a>
            </li>
            <?php } ?>

            <?php if(consultaPermissao($ck_mksist_permissao,"cad_domicilios","qualquer")){ ?>
            <li class=" <?php if($menu_active == 'domicilios'){echo 'active';}?>">
                <a href="<?php echo $link."/domicilios";?>">
                    <i class="fa fa-credit-card fa-fw">
                    </i>
                    <span class="menu-title">Domicílios bancários</span>
                </a>
            </li>
            <?php } ?>
            <li class=" <?php if($menu_active == 'teds'){echo 'active';}?>">
                <a href="<?php echo $link."/teds";?>">
                    <i class="fa fa-usd fa-fw">
                    </i>
                    <span class="menu-title">TEDS</span>
                </a>
            </li>

            <?php if(consultaPermissao($ck_mksist_permissao,"cad_arquivos","qualquer")){ ?>
            <li class="<?php if($menu_active == 'arquivos'){echo 'active';}?>">
                <a href="<?php echo $link."/arquivos";?>">
                    <i class="fa fa-file-text fa-fw">
                    </i>
                    <span class="menu-title">Arquivos Banco</span>
                </a>
            </li>
            <?php } ?>

            <?php
                 if ($_SESSION['supervisor'] == 'S' || in_array($_SESSION['perfil_id'], array('1', '2', '3', '5'))) {?>


                    <li class="animatedClick " data-target="animate_sidebar_relatorios">
                        <a id="a_animate_sidebar_relatorios"
                            class="pointer" onclick="showSubmenu('submenu_relatorios');">
                            <i class="fa fa-file-o fa-fw"></i>
                            <span class="menu-title">Relatórios</span>
                            <i id="icon_submenu_relatorios" class="fa fa-chevron-circle-down fa-fw pull-right icon_submenu"></i>
                        </a>
                    </li>

                    <?php if (in_array($_SESSION['perfil_id'], array('1', '3', '5')) || $user_id == '4093') {?>
                        <li class="submenu-item submenu_relatorios  animated animate_sidebar_relatorios  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'rpt_baixas'){echo 'active';}?>"
                            style="display:none;">
                            <a href="<?php echo $link."/relatorios/rpt_baixas";?>">
                                <i class="fa fa-file-excel-o fa-fw"></i>
                                <span class="menu-title">Baixas de boletos</span>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if (in_array($_SESSION['perfil_id'], array('1', '3', '5'))) {?>
                        <li class="submenu-item submenu_relatorios  animated animate_sidebar_relatorios  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'rpt_extrato'){echo 'active';}?>"
                            style="display:none;">
                            <a href="<?php echo $link."/relatorios/rpt_extrato";?>">
                                <i class="fa fa-file-excel-o fa-fw"></i>
                                <span class="menu-title">Extrato retorno</span>
                            </a>
                        </li>
                    <?php } ?>

                    <li class="submenu-item submenu_relatorios  animated animate_sidebar_relatorios  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'sem_ocorrencias'){echo 'active';}?>"
                        style="display:none;">
                        <a href="<?php echo $link."/relatorios/sem_ocorrencias";?>">
                            <i class="fa fa-file-excel-o fa-fw"></i>
                            <span class="menu-title">IDs Sem Ocorrências</span>
                        </a>
                    </li>

                    <li class="submenu-item submenu_relatorios  animated animate_sidebar_relatorios  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'qtd_adimplencia'){echo 'active';}?>"
                        style="display:none;">
                        <a href="<?php echo $link."/relatorios/qtd_adimplencia";?>">
                            <i class="fa fa-file-excel-o fa-fw"></i>
                            <span class="menu-title">Adimplência - totais</span>
                        </a>
                    </li>

                    <li class="submenu-item submenu_relatorios  animated animate_sidebar_relatorios  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'saldo_cliente'){echo 'active';}?>"
                        style="display:none;">
                        <a href="<?php echo $link."/relatorios/saldo_cliente";?>">
                            <i class="fa fa-file-excel-o fa-fw"></i>
                            <span class="menu-title">Saldo Cliente</span>
                        </a>
                    </li>

                    <li class="submenu-item submenu_relatorios  animated animate_sidebar_relatorios  bounceInDown  fadeOutDown <?php if($sub_menu_active == 'rpt_boletos_adimplencia'){echo 'active';}?>"
                        style="display:none;">
                        <a href="<?php echo $link."/relatorios/rpt_boletos_adimplencia";?>">
                            <i class="fa fa-file-excel-o fa-fw"></i>
                            <span class="menu-title">Boletos - Adimplência</span>
                        </a>
                    </li>

                <?php }  ?>

                <?php if ($_SESSION['supervisor'] == 'S' || in_array($_SESSION['perfil_id'], array('1', '2', '3', '5'))) {?>


                    <li class="animatedClick " data-target="animate_sidebar_protocolos">
                        <a id="a_animate_sidebar_protocolos"
                            class="pointer" onclick="showSubmenu('submenu_protocolos');">
                            <i class="fa fa-file-o fa-fw"></i>
                            <span class="menu-title">Protocolos</span>
                            <i id="icon_submenu_protocolos" class="fa fa-chevron-circle-down fa-fw pull-right icon_submenu"></i>
                        </a>
                    </li>

                    <li class="submenu-item submenu_protocolos animated animate_sidebar_protocolos bounceInDown fadeOutDown <?php if($sub_menu_active == 'contratos'){echo 'active';}?>"
                        style="display:none;">
                        <a href="<?php echo $link."/protocolos/lista_protocolos";?>">
                            <i class="fa fa-file-text-o fa-fw"></i>
                            <span class="menu-title">Contratos</span>
                        </a>
                    </li>

                    <?php if(in_array($_SESSION['perfil_id'], array('1')) || in_array($user_id , array('1874', '2695', '6775', '5564')) ) { ?>
                        <li class="submenu-item submenu_protocolos animated animate_sidebar_protocolos bounceInDown fadeOutDown <?php if($sub_menu_active == 'servicos'){echo 'active';}?>"
                            style="display:none;">
                            <a href="<?php echo $link."/protocolos/lista_protocolos_servicos";?>">
                                <i class="fa fa-file-text-o fa-fw"></i>
                                <span class="menu-title">Serviços</span>
                            </a>
                        </li>
                    <?php }  ?>

                <?php }  ?>




            <?php }  ?>


        </ul>

    </div>
</nav>



<script>
function showSubmenu(submenu) {
    if ($('#icon_' + submenu).hasClass("fa-chevron-circle-down")) {
        $('.' + submenu).fadeIn();
        $('#icon_' + submenu).removeClass('fa-chevron-circle-down').addClass('fa-minus-circle');
    } else {
        $('.' + submenu).fadeOut();
        $('#icon_' + submenu).removeClass('fa-minus-circle').addClass('fa-chevron-circle-down');
    }
}
</script>
