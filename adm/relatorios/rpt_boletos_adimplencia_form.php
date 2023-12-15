<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";

$layout_title = "MECOB - Relatórios";

$menu_active="relatorios";
$cod_modulo = "relatorios";
$lista_usuarios=0;
$layout_title = "MECOB  - Relatórios";
$sub_menu_active="rpt_boletos_adimplencia";	
$tit_pagina = "Boletos - Adimplencia";	
$tit_lista = " Relatório de adimplencia dos boletos por dia";		

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
							<form class="form" method="POST" action="rpt_boletos_adimplencia/gerar" enctype="multipart/form-data" 
									onsubmit="this.submit(); this.reset(); return false;">
								<div class="row">
									<label><h4>Informe uma data para o mês de referência</h4></label>
									<div class="form-group col-lg-12">
										<div class=" input-group  input-group-sm fl-lf  col-lg-2 pd-lf-5 col-sm-11 col-md-5 col-xs-11   mg-bt-3">
											<input type="text" name="filtro_data" id="filtro_data" class="form-control" placeholder="Data" >
										</div> 

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

		$("#filtro_data").mask("99/99/9999");
		// $("#filtro_data").datepicker({
		// 	dateFormat: 'dd/mm/yy'
		// });
		$("#filtro_data_fim").mask("99/99/9999");
		// $("#filtro_data_fim").datepicker({
		// 	dateFormat: 'dd/mm/yy'
		// });

    $("#filtro_data").datepicker({
		numberOfMonths: 1,
		format: 'dd/mm/yy',
		dateFormat: 'dd/mm/yy',
		maxDate: '0',
        onSelect: function (selected) {
			var dateOffset = (24*60*60*1000) * 30; //31 days
            var dt_f     = new Date();
			var dt_atual = new Date();

			dt0 = $( "#filtro_data" ).datepicker( "getDate" );
			dt_f.setTime(dt0.getTime() + dateOffset);

        }
    });

	</script>    
</body>
</html>
