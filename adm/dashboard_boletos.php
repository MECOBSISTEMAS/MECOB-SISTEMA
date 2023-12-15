<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");


$layout_title = "MECOB - Boletos";
$addcss= '<link rel="stylesheet" href="'.$link.'/css/smoothjquery/smoothness-jquery-ui.css">';
include($raiz."/partial/html_ini.php");
$menu_active = 'dashboard';

include_once(getenv('CAMINHO_RAIZ')."/repositories/dashboard/dashboard.db.php");
$dashboard  = new dashboard();

$filtros=array();
$id_pessoa = $_SESSION["id"];

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
          <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/dashboard">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
          <li class="hidden"><a href="#">Painel</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
          <li class="active">Painel </li>
        </ol>
        <div class="clearfix"> </div>
      </div>
      <!--END TITLE & BREADCRUMB PAGE--> 
      <!--BEGIN CONTENT-->
      <div class="page-content">
        <div id="tab-general">
          <div class="row mbl">
            <div class=" col-sm-12 ">
              <div class="panel">
                <div class="panel-body">
                  <h3>
                  <a href="#" class="seta">
                  Boletos
                  </a>
                  </h3>
                  <?php												
					$atrasados = $dashboard->parcelas_aberto($conexao_BD_1, $id_pessoa); 
				  ?>
                  
                  <div class="row pd-15">
                    <table id="listagem_proximos" class="table table-hover ">
                      <thead>
                        <tr>
                          <th> Vencimento </th>
                          <th class="hidden-xs">Contrato</th>
                          <th class="hidden-xs">Valor</th>
                          <th ></th>
                          
                        </tr>
                      </thead>
                      <tbody id="tbody_proximos">
                      
                      <?php
					  foreach($atrasados as $atrasado){ 
					  $stt_parcela  = "";
            $acao_parcela = "";
            $dias_diferenca = round((strtotime(date('Y-m-d')) - strtotime($atrasado['dt_vencimento'])) / 86400);

            if( EhDataMaior( date("Y-m-d") , $atrasado['dt_vencimento'] )){
              if( $dias_diferenca > 60 ){
                $stt_parcela  = "danger";
                //$acao_parcela = "<span class='pointer'  onClick='gera_segunda_via_parcela(".$atrasado['p_id']." , ".$atrasado['c_id'].");'> Gerar 2° via </span>";
                $acao_parcela='<a href="https://unicred-florianopolis.cobexpress.com.br/default/segunda-via"  target="_blank"> <i class="fa fa-file-o" aria-hidden="true"></i> 2° via Boleto </a>';							
              } else {
                $stt_parcela = "danger";
                $acao_parcela='<a href="'.$link.'/inc/boleto/gerar_boleto.php?id='.$atrasado['c_id'].'&p='.$atrasado['p_id'].'"  target="_blank"> <i class="fa fa-file-o" aria-hidden="true"></i> Boleto </a>';	  
              }
						}
						else if($atrasado['gerar_boleto'] == 'S'){
							$stt_parcela = "info";
							$acao_parcela='<a href="'.$link.'/inc/boleto/gerar_boleto.php?id='.$atrasado['c_id'].'&p='.$atrasado['p_id'].'"  target="_blank"> <i class="fa fa-file-o" aria-hidden="true"></i> Boleto </a>';	
						}
						else{
							$acao_parcela = "Sem boleto";
						}
						
						$vl_parc = $atrasado['vl_parcela'];
							if(!empty($atrasado['vl_corrigido']) && is_numeric($atrasado['vl_corrigido']) && $atrasado['vl_corrigido'] > $atrasado['vl_parcela']){
								$vl_parc = $atrasado['vl_corrigido'];
							}
						
					  
					  ?>
					  <tr class="<?php echo $stt_parcela;?> ">
                      
                      <td class="nowrap ">
                          <?php echo ConverteData($atrasado['dt_vencimento']);?> 
                          <div class="visible-xs">
                          		<?php 
								echo "Contrato ".$atrasado['c_id']." - parcela ".$atrasado['nu_parcela'];
                                echo '<br>R$ '.Format($vl_parc, 'numero');
								?> 
                          </div>
                          
                          
                          
                          
                      </td>
                      <td class="hidden-xs">
						<?php echo "Contrato ".$atrasado['c_id']." - parcela ".$atrasado['nu_parcela'];?> 
                      </td>
                      <td class="nowrap hidden-xs">
					  <?php  echo 'R$ '.Format($vl_parc, 'numero');  ?>
                      </td>
                      <td class="nowrap ">
                       <div class="visible-xs">
                       <br />
                       </div>
                      <?php echo $acao_parcela; ?>
                      
                      </td>
                      </tr>
					  <?php
					  }
					  ?>
                      
					                        
					   </tbody>
                    </table>
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
<script src="<?php echo $link;?>/js/jquery.validate.js"/></script> 
<script src="<?php echo $link;?>/js/jquery.form.js"></script> 
<!--LOADING SCRIPTS FOR CHARTS--> 
<!--CORE JAVASCRIPT--> 

<script src="<?php echo $link;?>/js/highcharts.js"></script> 
<script src="<?php echo $link;?>/js/highcharts_exporting.js"></script> 

<script>
function gera_segunda_via_parcela(id_parcela, contrato_id){
	jAlert('Segunda via apenas pelo ambiente do banco!','Oops');
	return 0;
	<?php
        /*
	$dt_venc = new DateTime(date('Y-m-d'));
	$dt_venc->add(new DateInterval("P2D"));
	?>
	
	$.post("<?php //echo $link."/repositories/contratos/contratos.ctrl.php?acao=atualiza_parcelas_2_via";?>", {
		parcela_id: id_parcela,
		dt_atualizacao: ConverteData('<?php //echo $dt_venc->format("d/m/Y");?>'),
	}, function(result){
	if( result==1 ){
		jAlert('As informações serão recarregadas!','Atualizado!','ok');
		$('#popup_ok').on( "click", function() {
			 location.reload();
		});
	}
	else{
		jAlert('Não foi possível gerar a segunda via desta parcela - contate a nossa equipe.','Oops','alert');
	}
	});
    <?php
    */
    ?>
}

</script>



</body></html>