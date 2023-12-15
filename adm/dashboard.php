<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


if($ehCliente){
	include($raiz."/adm/dashboard_cliente.php");
	exit;
}

$layout_title = "MECOB - Dashboard";
$addcss= '<link rel="stylesheet" href="'.$link.'/css/smoothjquery/smoothness-jquery-ui.css">';
include($raiz."/partial/html_ini.php");
$menu_active = 'dashboard';

include_once(getenv('CAMINHO_RAIZ')."/repositories/dashboard/dashboard.db.php");
$dashboard  = new dashboard();

include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.db.php");
$tedsDB  = new tedsDB();


$filtros=array();
$id_pessoa  = "";
if (!$ehUser  && !$ehAdmin){
	$id_pessoa = $_SESSION["id"];
}

?>

<div>
    <!--BEGIN BACK TO TOP-->
    <a id="totop" href="#"><i class="fa fa-angle-up"></i></a>
    <!--END BACK TO TOP-->
    <!--BEGIN TOPBAR-->
    <?php include($raiz."/partial/header.php");?>
    <!--END TOPBAR-->
    <div id="wrapper">
        <!--BEGIN SIDEBAR MENU-->
        <?php include($raiz."/partial/sidebar_adm.php");?>
        <!--END SIDEBAR MENU-->
        <!--BEGIN PAGE WRAPPER-->
        <div id="page-wrapper">
            <!--BEGIN TITLE & BREADCRUMB PAGE-->
            <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
                <div class="page-header pull-left">
                    <div class="page-title"> Painel </div>
                </div>
                <ol class="breadcrumb page-breadcrumb pull-right">
                    <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/dashboard">Home</a>&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                    <li class="hidden"><a href="#">Painel</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;
                    </li>
                    <li class="active">Painel </li>
                </ol>
                <div class="clearfix"> </div>
            </div>
            <!--END TITLE & BREADCRUMB PAGE-->
            <!--BEGIN CONTENT-->
            <div class="page-content">
                <div id="tab-general">

                    <!-- QUADROS FAIXA 1 -->

                    <div id="sum_box" class="row mbl">
                        <div class="col-sm-6 col-md-4">
                            <div class="panel  db mbm">
                                <div class="panel-body"> <a href="<?php echo $link."/parcelas/vencidos";?>	">
                                        <h4 class="value"> <span>
                                                Boletos vencidos
                                            </span></h4>
                                        <p class="description fs-16">
                                            <?php					 									
                        //adimplencia
                        $boletos_vencidos_adimplencia = $dashboard->total_boletos_vencidos($conexao_BD_1,'adimplencia',false);
                        $boletos_vencidos_adimplencia_inv = $dashboard->total_boletos_vencidos($conexao_BD_1,'adimplencia',true);
                        $qtd_ad = $boletos_vencidos_adimplencia['qtd'];
                        $qtd_ad_inad = $qtd_ad;
                        $qtd_ad_in = $boletos_vencidos_adimplencia_inv['qtd'];
                        $qtd_ad_pc = round((($qtd_ad / ($qtd_ad_in + $qtd_ad))) * 100,2);
                        $qtd_ad_pc_in = round((($qtd_ad / ($qtd_ad_in + $qtd_ad))-1) * -100,2);
                        $qtd_ad_tt = $qtd_ad + $qtd_ad_in;
                        $qtd_ad_inad_tt = $qtd_ad_tt;
                        $qtd_ad_in_custodia = $qtd_ad_in;
                        $qtd_ad_in_custodia_pc = $qtd_ad_pc_in;
                        $qtd_ad = number_format($qtd_ad,0,'','.');
                        $qtd_ad_in = number_format($qtd_ad_in,0,'','.');
                        $qtd_ad_tt = number_format($qtd_ad_tt,0,'','.');

                        $val_ad = $boletos_vencidos_adimplencia['valor'];
                        $val_ad_inad = $val_ad;
                        $val_ad_in = $boletos_vencidos_adimplencia_inv['valor'];
                        $val_ad_pc = round((($val_ad / ($val_ad_in + $val_ad))) * 100,2);
                        $val_ad_pc_in = round((($val_ad / ($val_ad_in + $val_ad))-1) * -100,2);
                        $val_ad_tt = $val_ad + $val_ad_in;
                        $val_ad_inad_tt = $val_ad_tt;
                        $val_ad_in_custodia = $val_ad_in;
                        $val_ad = number_format($val_ad,2,',','.');
                        $val_ad_in = number_format($val_ad_in,2,',','.');
                        $val_ad_tt = number_format($val_ad_tt,2,',','.');

                        //inadimplencia
                        $boletos_vencidos_inadimplencia = $dashboard->total_boletos_vencidos($conexao_BD_1,'inadimplencia',false);
                        $boletos_vencidos_inadimplencia_inv = $dashboard->total_boletos_vencidos($conexao_BD_1,'inadimplencia',true);
                        $qtd_inad = $boletos_vencidos_inadimplencia['qtd'];
                        $qtd_ad_inad += $qtd_inad;
                        $qtd_inad_in = $boletos_vencidos_inadimplencia_inv['qtd'];
                        $qtd_inad_pc = round((($qtd_inad / ($qtd_inad_in + $qtd_inad))) * 100,2);
                        $qtd_inad_pc_in = round((($qtd_inad / ($qtd_inad_in + $qtd_inad))-1) * -100,2);
                        $qtd_inad_tt = $qtd_inad + $qtd_inad_in;
                        $qtd_ad_inad_tt += $qtd_inad_tt;
                        $qtd_ad_in_custodia += $qtd_inad_in;
                        $qtd_ad_in_custodia_pc += $qtd_inad_pc_in;
                        $qtd_inad = number_format($qtd_inad,0,'','.');
                        $qtd_inad_in = number_format($qtd_inad_in,0,'','.');
                        $qtd_inad_tt = number_format($qtd_inad_tt,0,'','.');

                        $val_inad = $boletos_vencidos_inadimplencia['valor'];
                        $val_ad_inad += $val_inad;
                        $val_inad_in = $boletos_vencidos_inadimplencia_inv['valor'];
                        $val_inad_pc = round((($val_inad / ($val_inad_in + $val_inad))) * 100,2);
                        $val_inad_pc_in = round((($val_inad / ($val_inad_in + $val_inad))-1) * -100,2);
                        $val_inad_tt = $val_inad + $val_inad_in;
                        $val_ad_inad_tt += $val_inad_tt;
                        $val_ad_in_custodia += $val_inad_in;
                        $val_inad = number_format($val_inad,2,',','.');
                        $val_inad_in = number_format($val_inad_in,2,',','.');
                        $val_inad_tt = number_format($val_inad_tt,2,',','.');   

                        $qtd_ad_inad = number_format($qtd_ad_inad,0,',','.');
                        $val_ad_inad = number_format($val_ad_inad,2,',','.');
                        $val_ad_in_custodia = number_format($val_ad_in_custodia,2,',','.');
                        $qtd_ad_in_custodia = number_format($qtd_ad_in_custodia,0,',','.');
                        $qtd_ad_in_custodia_pc = number_format($qtd_ad_in_custodia_pc,0,',','.');
                        $val_ad_inad_tt = number_format($val_ad_inad_tt,2,',','.');
                        $qtd_ad_inad_tt = number_format($qtd_ad_inad_tt,0,',','.');

                        echo "
                            <div class='row'>
                                <div class='col-sm-12'>
                                    <div class='row'>
                                        <div class='col-sm-12'>
                                            <strong>Adimplência</strong><br>
                                            $qtd_ad boletos ($qtd_ad_pc% do total)<br>
                                            R$ $val_ad ($val_ad_pc% do total)<br>
                                            <br>
                                        </div>
                                        <div class='col-sm-12'>
                                            <strong>Recuperação de crédito</strong><br>
                                            $qtd_inad boletos ($qtd_inad_pc% do total)<br>
                                            R$ $val_inad ($val_inad_pc% do total)<br>
                                            <br>
                                        </div>
                                        <div class='col-sm-12'>
                                            <strong>Total</strong><br>
                                            $qtd_ad_inad boletos<br>
                                            R$ $val_ad_inad<br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        "
					?>
                                        </p>
                                    </a> </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="panel  db mbm">
                                <div class="panel-body"> <a href="<?php echo $link."/parcelas/custodia";?>	">
                                        <h4 class="value"> <span>
                                                Custódia
                                            </span></h4>
                                        <p class="description fs-16">
                                            <?php					 									
                                            echo "
                                                <div class='row'>
                                                    <div class='col-sm-12'>
                                                        <div class='row'>
                                                            <div class='col-sm-12'>
                                                                <strong>Adimplência</strong><br>
                                                                $qtd_ad_in boletos ($qtd_ad_pc_in% do total)<br>
                                                                R$ $val_ad_in ($val_ad_pc_in% do total)<br>
                                                                <br>
                                                            </div>
                                                            <div class='col-sm-12'>
                                                                <strong>Recuperação de crédito</strong><br>
                                                                $qtd_inad_in boletos ($qtd_inad_pc_in% do total)<br>
                                                                R$ $val_inad_in ($val_inad_pc_in% do total)<br>
                                                                <br>
                                                            </div>
                                                            <div class='col-sm-12'>
                                                                <strong>Total</strong><br>
                                                                $qtd_ad_in_custodia boletos<br>
                                                                R$ $val_ad_in_custodia<br>
                                                                <br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            "
					                        ?>
                                        </p>
                                    </a> </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-4">
                            <div class="panel  db mbm">
                                <div class="panel-body"> <a href="#<?php #echo $link."/pedidos_abertos";?>	"
                                        class="seta">
                                        <h4 class="value"> <span>
                                                Total
                                            </span></h4>
                                        <p class="description fs-16">
                                            <?php					 									
                                            echo "
                                            <div class='row'>
                                                <div class='col-sm-12'>
                                                    <div class='row'>
                                                        <div class='col-sm-12'>
                                                            <strong>Adimplência</strong><br>
                                                            $qtd_ad_tt boletos<br>
                                                            R$ $val_ad_tt <br>
                                                            <br>
                                                        </div>
                                                        <div class='col-sm-12'>
                                                            <strong>Recuperação de crédito</strong><br>
                                                            $qtd_inad_tt boletos<br>
                                                            R$ $val_inad_tt <br>
                                                            <br>
                                                        </div>
                                                        <div class='col-sm-12'>
                                                            <strong>Total</strong><br>
                                                            $qtd_ad_inad_tt boletos<br>
                                                            R$ $val_ad_inad_tt <br>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            "
				                            ?>
                                        </p>
                                    </a> </div>
                            </div>
                        </div>
                    </div>

                    <!-- FIM QUADROS FAIXA 1 -->
                    <!-- QUADROS FAIXA 2 -->

                    <div id="sum_box" class="row mbl">
                        <div class="col-sm-6 col-md-4">
                            <div class="panel  db mbm">
                                <div class="panel-body"> 
                                    <a href="<?php echo $link."/parcelas/vencidos_ontem";?>	">
                                        <p class="icon"> <i class="icon fa fa-usd"></i> </p>
                                        <h4 class="value"> 
                                            <span>
                                                <?php					 									
                                                    //adimplencia
                                                    $boletos_vencidos_ontem_adimplencia = $dashboard->total_boletos_vencidos($conexao_BD_1,'adimplencia',false,true);
                                                    $boletos_vencidos_ontem_adimplencia_inv = $dashboard->total_boletos_vencidos($conexao_BD_1,'adimplencia',true,true);
                                                    $qtd = $boletos_vencidos_ontem_adimplencia['qtd'];
                                                    $qtd_inv = $boletos_vencidos_ontem_adimplencia_inv['qtd'];
                                                    echo "$qtd de $qtd_inv"
                                                ?>
                                            </span>
                                        </h4>
                                        <p class="description"> Boletos atrasados de ontem</p>
                                        <div class="progress progress-sm mbn">
                                            <div role="progressbar" aria-valuenow="<?php echo $qtd?>"
                                            aria-valuemin="0" aria-valuemax="<?php echo $qtd_inv?>"
                                            style="width: <?php echo ($qtd / $qtd_inv * 100 )?>%;"
                                            class="progress-bar progress-bar-warning"> 
                                                <span
                                                    class="sr-only"><?php echo $qtd; ?>% Complete
                                                    (success)
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="panel  db mbm">
                                <div class="panel-body"> 
                                    <a href="<?php echo $link."/contratos/inadimplentes";?>">
                                        <p class="icon"><i class="icon fa fa-user-times"></i></p>
                                        <h5 class="value">
                                            <span>
                                                <?php				
                                                $contratos_inad = $dashboard->qtd_contratos($conexao_BD_1,'inadimplentes', $id_pessoa);
                                                $contratos = $dashboard->qtd_contratos($conexao_BD_1,"", $id_pessoa);
                                                echo number_format($contratos_inad,0,',','.');
                                                echo " de ";
                                                echo number_format($contratos,0,',','.'); 
                                                
                                                
                                                $contratos_porc = 0;
                                                if ($contratos > 0){ 
                                                    $contratos_porc = ($contratos_inad / $contratos) * 100;
                                                }
                                                ?>
                                            </span>
                                        </h4>
                                        <p class="description">Inadimplentes</p>
                                        <div class="progress progress-sm mbn">
                                            <div role="progressbar" aria-valuenow="<?php echo $contratos_porc; ?>"
                                            aria-valuemin="0" aria-valuemax="100"
                                            style="width: <?php echo $contratos_porc; ?>%;"
                                            class="progress-bar progress-bar-danger"> 
                                                <span
                                                class="sr-only">
                                                    <?php echo $contratos_porc; ?>% Complete (success)
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="panel  db mbm">
                                <div class="panel-body"> <a href="<?php echo $link."/arquivos";?>">
                                        <p class="icon"> <i class="icon fa fa-file"></i> </p>
                                        <h4 class="value"> <span>
                                                <?php					 									
						echo $tt_arq_enviar = $dashboard->tt_arquivos($conexao_BD_1,'enviar');
						echo " de ";
						echo $tt_arq = $dashboard->tt_arquivos($conexao_BD_1); 
						
						
						$porcentagem_arquivos = 0;
						if ($tt_arq > 0){ 
							$porcentagem_arquivos = ($tt_arq_enviar / $tt_arq) * 100;
						}
					?>
                                            </span></h4>
                                        <p class="description"> Arquivos Envio Pendente </p>
                                        <div class="progress progress-sm mbn">
                                            <div role="progressbar" aria-valuenow="<?php echo $porcentagem_arquivos; ?>"
                                                aria-valuemin="0" aria-valuemax="100"
                                                style="width: <?php echo $porcentagem_arquivos; ?>%;"
                                                class="progress-bar progress-bar-success"> <span
                                                    class="sr-only"><?php echo $porcentagem_arquivos; ?>% Complete
                                                    (success)</span></div>
                                        </div>
                                    </a> </div>
                            </div>
                        </div>
                    </div>

                    <!-- FIM QUADROS FAIXA 2 -->





                    <div class="row mbl">

                        <div class="col-xs-12 col-sm-6  ">
                            <div class="panel">
                                <div class="panel-body">
                                    <h3>
                                        <a href="#" class="seta">
                                            Boletos vencidos - Adimplência
                                        </a>
                                    </h3>
                                    <?php												
					$atrasados = $dashboard->parcelas_vencidas($conexao_BD_1, $id_pessoa , "adimplencia"); 
				  ?>

                                    <div class="row dashboar-lancamentos-abertos">
                                        <table id="listagem_proximos" class="table table-hover ">
                                            <thead>
                                                <tr>
                                                    <th> Vencimento </th>
                                                    <th>Contrato</th>
                                                    <th>Valor</th>

                                                </tr>
                                            </thead>
                                            <tbody id="tbody_proximos">

                                                <?php 
					  $total_valor =0;
					  foreach($atrasados as $atrasado){ ?>
                                                <tr class="danger pointer">

                                                    <td class="nowrap">
                                                        <a href="<?php echo $link."/contratos/".$atrasado['c_id'];?>">
                                                            <?php echo ConverteData($atrasado['dt_vencimento']);?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $link."/contratos/".$atrasado['c_id'];?>">
                                                            <?php echo "Contrato ".$atrasado['c_id']." - parcela ".$atrasado['nu_parcela'];?>
                                                        </a>
                                                    </td>
                                                    <td class="nowrap">
                                                        <a href="<?php echo $link."/contratos/".$atrasado['c_id'];?>">
                                                            <?php 
					  		$vl_parc = $atrasado['vl_parcela'];
							if(!empty($atrasado['vl_corrigido']) && is_numeric($atrasado['vl_corrigido']) && $atrasado['vl_corrigido'] > $atrasado['vl_parcela']){
								$vl_parc = $atrasado['vl_corrigido'];
							}
							echo 'R$ '.Format($vl_parc, 'numero');
							$total_valor += str_replace(',','',$vl_parc);
					  ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
					  }
					  ?>

                                                <tr>

                                                    <td class="nowrap">
                                                        Total
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td class="nowrap">
                                                        <?php 
							echo 'R$ '.Format($total_valor, 'numero');
					  ?>
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 ">
                            <div class="panel">
                                <div class="panel-body">
                                    <h3>
                                        <a href="#" class="seta">
                                            Boletos vencidos - Recuperação de crédito
                                        </a>
                                    </h3>
                                    <?php												
					$atrasados = $dashboard->parcelas_vencidas($conexao_BD_1, $id_pessoa , "inadimplencia"); 
				  ?>

                                    <div class="row dashboar-lancamentos-abertos">
                                        <table id="listagem_proximos" class="table table-hover ">
                                            <thead>
                                                <tr>
                                                    <th> Vencimento </th>
                                                    <th>Contrato</th>
                                                    <th>Valor</th>

                                                </tr>
                                            </thead>
                                            <tbody id="tbody_proximos">

                                                <?php 
					  $total_valor =0;
					  foreach($atrasados as $atrasado){ ?>
                                                <tr class="danger pointer">

                                                    <td class="nowrap">
                                                        <a href="<?php echo $link."/contratos/".$atrasado['c_id'];?>">
                                                            <?php echo ConverteData($atrasado['dt_vencimento']);?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $link."/contratos/".$atrasado['c_id'];?>">
                                                            <?php echo "Contrato ".$atrasado['c_id']." - parcela ".$atrasado['nu_parcela'];?>
                                                        </a>
                                                    </td>
                                                    <td class="nowrap">
                                                        <a href="<?php echo $link."/contratos/".$atrasado['c_id'];?>">
                                                            <?php 
					  		$vl_parc = $atrasado['vl_parcela'];
							if(!empty($atrasado['vl_corrigido']) && is_numeric($atrasado['vl_corrigido']) && $atrasado['vl_corrigido'] > $atrasado['vl_parcela']){
								$vl_parc = $atrasado['vl_corrigido'];
							}
							echo 'R$ '.Format($vl_parc, 'numero');
							$total_valor += str_replace(',','',$vl_parc);
					  ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
					  }
					  ?>

                                                <tr>

                                                    <td class="nowrap">
                                                        Total
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td class="nowrap">
                                                        <?php 
							echo 'R$ '.Format($total_valor, 'numero');
					  ?>
                                                    </td>
                                                </tr>



                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>



                    <?php 
		  if(consultaPermissao($ck_mksist_permissao,"cad_teds","editar")){ 
		  ?>
                    <div class="row mbl">

                        <div class="col-xs-12 col-sm-12  ">
                            <div class="panel">
                                <div class="panel-body">
                                    <h3>
                                        <a href="#" class="seta">
                                            TEDs - Parcelas liquidadas com transferência pendente
                                        </a>
                                    </h3>
                                    <?php												
					$clientes_ted = $tedsDB->parcelas_para_ted_cliente($conexao_BD_1, $id_pessoa); 
				  ?>

                                    <div class="row dashboar-lancamentos-abertos">
                                        <table id="listagem_proximos" class="table table-hover ">
                                            <thead>
                                                <tr>
                                                    <th> Cliente </th>
                                                    <th> Total parcelas </th>
                                                    <th> Parcela mais antiga</th>
                                                    <th> Total a tranferir</th>
                                                    <th> Consultar Parcelas</th>
                                                    <th> Zerar Parcelas</th>

                                                </tr>
                                            </thead>
                                            <tbody id="tbody_proximos">

                                                <?php 
					  $total_ted =0;
					  foreach($clientes_ted as $cliente_ted){ ?>
                                                <tr class="danger pointer"
                                                    id="cliente_ted_<?php echo $cliente_ted['id'];?>">

                                                    <td <a href="<?php echo $link."/pessoa_ted/".$cliente_ted['id'];?>">
                                                        <?php echo $cliente_ted['nome'];
						  		if(!empty($cliente_ted['cpf_cnpj']))
									echo ' '.$cliente_ted['cpf_cnpj'];
						  ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $link."/pessoa_ted/".$cliente_ted['id'];?>">
                                                            <?php echo $cliente_ted['total_parcelas']." parcelas";?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $link."/pessoa_ted/".$cliente_ted['id'];?>">
                                                            <?php echo ConverteData($cliente_ted['dt_credito']);?>
                                                        </a>
                                                    </td>
                                                    <td class="nowrap">
                                                        <a href="<?php echo $link."/pessoa_ted/".$cliente_ted['id'];?>">
                                                            <?php 
							echo '~ R$ '.Format($cliente_ted['vl_transferir'], 'numero');
							$total_ted += str_replace(',','.',str_replace('.','',Format($cliente_ted['vl_transferir'], 'numero')));
					  ?>
                                                        </a>
                                                    </td>
                                                    <td class="ac">
                                                        <a href="<?php echo $link."/pessoa_ted/".$cliente_ted['id'];?>">
                                                            <i class="fa fa-search fs-18"></i>
                                                        </a>
                                                    </td>


                                                    <td class="ac">
                                                        <a href="#"
                                                            onclick="zerar_parcelas(<?php echo $cliente_ted['id'];?>);">
                                                            <i class="fa fa-times red_light fs-18"></i>
                                                        </a>
                                                    </td>

                                                </tr>
                                                <?php
					  }
					  ?>

                                                <tr>

                                                    <td class="nowrap">
                                                        Total
                                                    </td>
                                                    <td>
                                                    </td>
                                                    <td class="nowrap">
                                                        <?php 
							echo 'R$ '.Format($total_ted, 'numero');
					  ?>
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php } ?>



                    <div class="row mbl">
                        <div class="col-lg-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 class="mbs"> Fluxo deste mês </h4>
                                            <div id="grafico_fluxo_mes_passado" style="width:100%; height:300px"> </div>
                                        </div>
                                        <div class="col-md-4">
                                            <?php
                                            $fluxo_este_mes = $dashboard->fluxo_este_mes($conexao_BD_1, $id_pessoa); 
                                            $total = (is_numeric($fluxo_este_mes['vencido']))?str_replace(',','',$fluxo_este_mes['recebido']) + str_replace(',','',$fluxo_este_mes['aberto'])  + str_replace(',','',$fluxo_este_mes['vencido']) : str_replace(',','',$fluxo_este_mes['recebido']) + str_replace(',','',$fluxo_este_mes['aberto']);
                                            
                                            $percent_recebido =  (str_replace(',','',$fluxo_este_mes['recebido']) / $total) * 100;
                                            $percent_aberto   =  (str_replace(',','',$fluxo_este_mes['aberto']) / $total) * 100;
                                            $percent_vencido  =  (is_numeric($fluxo_este_mes['vencido']))?(str_replace(',','',$fluxo_este_mes['vencido']) / $total) * 100: 0;
                                            
                                            ?>
                                            <h4 class="mbm"> Fluxo</h4>
                                            <span class="task-item"> Recebidos <small class="pull-right text-muted">
                                                    R$ <?php echo Format($fluxo_este_mes['recebido'],'numero');?>
                                                
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        style="width: <?php echo $percent_recebido;?>%; background-color:#90ED7D"
                                                        class="progress-bar "> </div>
                                                </div>
                                            </span>
                                            <span class="task-item"> A vencer <small class="pull-right text-muted">
                                                    R$ <?php echo Format($fluxo_este_mes['aberto'],'numero');?> 
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        style="width: <?php echo $percent_aberto;?>%; background-color:#7CB5EC"
                                                        class="progress-bar "> </div>
                                                </div>
                                            </span>
                                            <span class="task-item"> Vencido <small class="pull-right text-muted">
                                                    R$ <?php echo Format($fluxo_este_mes['vencido'],'numero');?>
                                                
                                                <div class="progress progress-sm">
                                                    <div role="progressbar" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        style="width: <?php echo $percent_vencido;?>%; background-color:#D9534F"
                                                        class="progress-bar "> </div>
                                                </div>
                                            </span>

                                        </div>
                                    </div>
                                </div>
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
<!--CORE JAVASCRIPT-->
<?php include $raiz."/js/corejs.php";?>
<script src="<?php echo $link;?>/js/bootstrap-multiselect.js"></script>
<script src="<?php echo $link;?>/js/jquery.validate.js" />
</script>
<script src="<?php echo $link;?>/js/jquery.form.js"></script>
<!--LOADING SCRIPTS FOR CHARTS-->
<!--CORE JAVASCRIPT-->

<script src="<?php echo $link;?>/js/highcharts.js"></script>
<script src="<?php echo $link;?>/js/highcharts_exporting.js"></script>

<script>
function zerar_parcelas(id_vendedor) {
    jConfirm(
        'Ao confirmar esta ação será gerado um registro de TED, no valor de R$0,00 atrelado a todas as parcelas com o pagamento recebido e sem TED para que o repasse dessa quantia não fique mais como pendente.',
        'Cancelar TEDs das parcelas?',
        function(r) {
            if (r) {
                $.getJSON("<?php echo $link."/repositories/teds/teds.ctrl.php?acao=zerar_parcelas_ted";?>", {
                    id_vend: id_vendedor
                }, function(result) {
                    if (result.status > 0) {
                        //alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
                        jAlert(result.msg, 'Bom trabalho! TED id ' + result.status, 'ok');
                        $('#cliente_ted_' + id_vendedor).remove();

                    } else {
                        jAlert(result.msg, 'Não foi possível salvar as informações!', 'alert');
                    }
                });
            } else {
                jAlert('As informações estão seguras.', 'Ação Cancelada', 'ok');
            }
        });

}


$(function() {

    $(document).ready(function() {

        $('#grafico_fluxo_mes_passado').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '<?php echo  date_to_mes(date('m'))." ".date('Y');?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },

            colors: ['#90ED7D', '#7CB5EC', '#D9534F'],

            series: [{
                name: 'Quantidade',
                colorByPoint: true,
                data: [{
                    name: 'Recebido',
                    y: <?php echo $percent_recebido ;?>,
                    sliced: true,
                    selected: true
                }, {
                    name: 'A vencer',
                    y: <?php echo $percent_aberto ;?>
                }, {
                    name: 'Atrasado',
                    y: <?php echo $percent_vencido ;?>
                }]

            }]
        });
    });
});
</script>

</body>

</html>
