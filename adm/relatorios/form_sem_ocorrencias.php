<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";

$layout_title = "MECOB - Pessoas";

$menu_active="relatorios";
$cod_modulo = "relatorios";
$lista_usuarios=0;
$layout_title = "MECOB  - Relatórios";
$sub_menu_active="sem_ocorrencias";	
$tit_pagina = "Sem Ocorrências";	
$tit_lista = " IDs sem ocorrências a X dias";		

// if(!consultaPermissao($ck_mksist_permissao,$cod_modulo,"qualquer")){ 
// 	header("Location: ".$link."/401");
// 	exit;
// }
	
$addcss= '<link rel="stylesheet" href="'.$link.'/css/smoothjquery/smoothness-jquery-ui.css">';

include($raiz."/partial/html_ini.php");

include_once($raiz."/inc/util.php");

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
		
		<div id="page-wrapper">
			<!--BEGIN TITLE & BREADCRUMB PAGE-->
			<div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
				<div class="page-header pull-left">
					<div class="page-title">
						<?php echo $tit_pagina; ?></div>
				</div>
				<ol class="breadcrumb page-breadcrumb pull-right">
					<li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/relatorios">Home</a>&nbsp;&nbsp;<i
						class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
					<li class="hidden"><a href="#">Relatórios</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
					<li class="active"><?php echo $tit_pagina;?></li>
				</ol>
				<div class="clearfix">
				</div>
			</div>
			<!--END TITLE & BREADCRUMB PAGE-->
			<!--BEGIN CONTENT-->
			<div class="page-content">
				<div id="tab-general">
					<div class="row mbl">
						<div class="col-lg-12">
							<div class="panel panel-bordo" style="background:#FFF;" >
							<div class="panel-heading"><?php echo $tit_lista;?></div>
							<div class="panel-body">

							<!-- input states -->
							<div class="box-body">
							<form class="form" method="POST" action="sem_ocorrencias/gerar" enctype="multipart/form-data">
								<!-- text input -->
								<div class="row">
									<div class="form-group col-lg-6">
										<label><h4>Dias sem ocorrências</h4></label>
										<input id="dias_sem_oc" 
											name="dias_sem_oc" 
											type="text" 
											class="form-control" 
											required
											value="5">
									</div>
								</div>
								<!-- Select multiple-->
								<div class="row">
									<div class="form-group  col-lg-6">
									<label><h4>Tipo do contrato</h4></label>
									<select id="tipo_sem_oc" name="tipo_sem_oc[]" multiple class="form-control" size="2" required>
										<option value="adimplencia">Adimplência</option>
										<option value="inadimplencia">Inadimplência</option>
									</select>
									</div>
								</div>

								<!-- Select multiple-->
								<div class="row">
									<div class="form-group col-lg-6">
									<label><h4>Status do contrato</h4></label>
									<select id="status_sem_oc" name="status_sem_oc[]" multiple class="form-control" size="7" required>
										<option value="acao_judicial">Ação judicial</option>
										<option value="confirmado">Confirmado</option>
										<option value="em_acordo">Em acordo</option>
										<option value="excluido">Excluído</option>
										<option value="parcialmente_em_acordo">Parcialmente em acordo</option>
										<option value="pendente">Pendente</option>
										<option value="virou_inadimplente">Virou inadimplente</option>
									</select>
									</div>
								</div>

								<div class="row">
									<!-- /.box-body -->
									<div class="box-footer col-lg-6">
										<button type="submit" class="btn btn-brown pull-right">Gerar</button>
									</div>								
								</div>								

							</form>
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


    
    <?php include $raiz."/js/corejs.php";?>
    <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.validate.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
    <script src="<?php echo $link;?>/js/jquery.maskMoney.js"/></script>
	
	<script>
		$('#a_animate_sidebar_relatorios').click();
	</script>    
</body>
</html>
