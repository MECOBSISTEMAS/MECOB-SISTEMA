<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";

include_once($raiz."/repositories/pessoas/pessoas.class.php");
include_once($raiz."/inc/combos.php");

$id=$_GET["id"];
$menu_active = 'perfil';

$pessoa = file_get_contents($link."/repositories/pessoas/pessoas.ctrl.php?acao=busca_pessoa&id=$id", false, HeaderToFileGetContent($username,$senha));
$pessoa = json_decode($pessoa,true);

$haras = file_get_contents($link."/repositories/haras/haras.ctrl.php?acao=listar&proprietario_id=$id", false, HeaderToFileGetContent($username,$senha));
$haras = json_decode($haras,true);

$layout_title = "MECOB - Perfil";
$addcss= '<link rel="stylesheet" href="'.$link.'/css/smoothjquery/smoothness-jquery-ui.css">';
include($raiz."/partial/html_ini.php");

include_once($raiz."/inc/util.php");

$podeAtualizar=false;
$proprio_user = false;
if($is_admin  || $id==$_SESSION['id'] ){
	$podeAtualizar=true;
	$proprio_user = true;
}
elseif($id!=$user_id){
    if(!consultaPermissao($ck_mksist_permissao,'perfil_usuario',"qualquer")){
        header("Location: ".$link."/401");
        exit;
    }
}

//usuários externos não podem editar nem o próprio cadastro
if($ehCliente){
	$podeAtualizar=false;
}


?>

<div> 
  <!--BEGIN BACK TO TOP--> 
  <a id="totop" href="#"><i class="fa fa-angle-up"></i></a> 
  <!--END BACK TO TOP--> 
  <!--BEGIN TOPBAR-->
  <?php 
			include($raiz."/partial/header.php");
		?>
  <!--END TOPBAR-->
  <div id="wrapper"> 
    <!--BEGIN SIDEBAR MENU-->
    <?php 
			include($raiz."/partial/sidebar_adm.php");
			?>
    <!--END SIDEBAR MENU--> 
    
    <!--BEGIN PAGE WRAPPER-->
    <div id="page-wrapper"> 
      <!--BEGIN TITLE & BREADCRUMB PAGE-->
      <div id="title-breadcrumb-option-demo" class="page-title-breadcrumb">
        <div class="page-header pull-left">
          <div class="page-title"> Informações pessoais</div>
        </div>
        <ol class="breadcrumb page-breadcrumb pull-right">
          <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/dashboard">Home</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
          <li class="active"><?php echo $pessoa[0]["nome"]; ?></li>
        </ol>
        <div class="clearfix"> </div>
      </div>
      <!--END TITLE & BREADCRUMB PAGE--> 
      <!--BEGIN CONTENT-->
      <div class="page-content">
        <div id="tab-general">
          <div class="row mbl">
            <div class="col-lg-12">
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
                            <div id="bar_up_foto" class="progress-bar progress-bar-success  " role="progressbar" aria-valuenow="00" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"> 0% </div>
                          </div>
                        </div>
                        <a href="#" onClick="javascript:openImputFile();"> <img id="foto_usr" src="<?php echo img("/imagens/fotos/thumb/",$pessoa[0]["foto"]);?>" alt="" class="img-responsive wd-100p"/> </a> </div>
                    </div>
                    <form id="form_imagem" name="form_imagem" action="<?php echo $link."/repositories/pessoas/pessoas.ctrl.php"; ?>" method="post"  enctype="multipart/form-data">
                      <input type="file" id="imageFile" name="arquivo" style="display:none" onChange='submitForm(this)'/>
                      <input type="hidden" id="acao" name="acao" value="update_imagem" />
                      <input type="hidden" id="id_usr_img" name="id_usr_img" value="<?php echo $pessoa[0]["id"]; ?>" />
                      <input type="submit" id="linkid" value="SUBMIT" style="display:none" />
                    </form>
                  </div>
                </div>
                <div class="col-md-9 ">
                  <h3 class="mg-tp-0"><?php echo $pessoa[0]["nome"]; ?></h3>
                  <p><?php echo $pessoa[0]["email"]; ?></p>
                  <?php 
												echo '<p>';
												if ($pessoa[0]["rua"] != ""){
													echo $pessoa[0]["rua"].', '.$pessoa[0]["numero"]." - ";
                                         		}
												if ($pessoa[0]["cidade"] != ""){
												 	echo $pessoa[0]["cidade"].'/'.$pessoa[0]["estado"];
												}
												echo '</p>';
												if ($pessoa[0]["cep"] != ""){
                                                 	echo '<p>CEP: '.$pessoa[0]["cep"].'</p>';
												}
										 ?>
                  </p>
                  <?php
										if (strlen($pessoa[0]["dt_ativo"]) >10){
											echo '<p>Membro desde: '.ConverteData($pessoa[0]["dt_ativo"]).'</p>';
										}
										if ($pessoa[0]["status_descricao"] == "ATIVO"){
											echo '<span class="label label-success">Ativo</span>';
										}
										else{
											echo '<p>'.$pessoa[0]['status_descricao'].'</p>';
										}
										?>
                </div>
              </div>
            </div>
          </div>

          
          <div class="row">
            <div class="col-md-12">
              <div class=" menu_view_pessoa ">
                <ul class="nav nav-tabs bd-0">
                  <li  class="profilepainel profile_menutab active "><a onclick="active_menutab('profilepainel');" >Painel</a></li>
                  <li  class="profiledados profile_menutab  "><a onclick="active_menutab('profiledados');" >Dados pessoais</a></li> 
                  <li  class="profilecomprador profile_menutab"><a onclick="active_menutab('profilecomprador');">Contratos de Compra</a></li>
				  <li  class="profilevendedor profile_menutab"><a onclick="active_menutab('profilevendedor');" >Contratos de Venda</a></li>
				  <?php
				  if ($_SESSION['perfil_id'] == 1 || $_SESSION['perfil_id'] == 3){ 
					  ?>
						  <li  class="profileted profile_menutab"><a href="<?php echo $link.'/teds';?>" >TED</a></li>
					<?php
				  }
				  ?>
                  
                </ul>
              </div>
              <div class="quadros_view_pessoa row ">
              	<div id="profilepainel" class="profilebox col-md-12 ">
                  <div id="generalTabContent" class="tab-content">
                    <div id="tab-edit" class="tab-pane fade in active">
                    	<?php include($raiz.'/adm/pessoas/view_pessoas_painel.php');?>
                    </div>
                  </div>
                </div>
                <div id="profiledados" class="profilebox col-md-12 hidden">
                  <div id="generalTabContent" class="tab-content">
                    <div id="tab-edit" class="tab-pane fade in active">
                    	<?php include($raiz.'/adm/pessoas/view_pessoas_dados.php');?>
                    </div>
                  </div>
                </div> 
                <div id="profilecomprador" class="profilebox col-md-12 hidden">
                  <div id="generalTabContent" class="tab-content">
                    <div id="tab-edit" class="tab-pane fade in active">
                      <?php include($raiz.'/adm/pessoas/view_pessoas_comprador.php');?>
                    </div>
                  </div>
                </div>
                <div id="profilevendedor" class="profilebox col-md-12 hidden">
                  <div id="generalTabContent" class="tab-content">
                    <div id="tab-edit" class="tab-pane fade in active">
                      <?php include($raiz.'/adm/pessoas/view_pessoas_vendedor.php');?>
                    </div>
                  </div>
                </div> 
                <?php if( consultaPermissao($ck_mksist_permissao,"cad_teds","qualquer") || $proprio_user){ ?>
                <div id="profileted" class="profilebox col-md-12 hidden">
                  <div id="generalTabContent" class="tab-content">
                    <div id="tab-edit" class="tab-pane fade in active">
                      <?php include($raiz.'/adm/pessoas/view_pessoas_ted.php');?>
                    </div>
                  </div>
                </div>
                <?php } ?>
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
<!-- fim cadastro de condominios-->
<?php include $raiz."/js/corejs.php";?>
<script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script> 
<script src="<?php echo $link;?>/js/jquery.form.js"></script> 
<script src="<?php echo $link;?>/js/jquery.maskMoney.js"/></script> 
<script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>  
<script src="<?php echo $link;?>/js/jquery.validate.js"/></script> 

<!--<script src="<?php //echo $link;?>/js/highcharts.js"></script> 
<script src="<?php //echo $link;?>/js/highcharts_exporting.js"></script> 
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script> -->


<!--<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>

 -->

<script src="<?php echo $link;?>/js/highcharts.js?2"/></script>
<script src="<?php echo $link;?>/js/highcharts-more.js"/></script> 
<script src="<?php echo $link;?>/js/solid-gauge.js"/></script> 


<script>

// $('#tt_contratos_confirmados').html('Total Contratos:  R$ <?php //echo Format($tt_confirmados,'numero');?>');
// $('#tt_contratos_acao').html('Total Contratos:  R$ <?php //echo Format($tt_acao,'numero');?>');
// $('#tt_contratos_virou').html('Total Contratos:  R$ <?php //echo Format($tt_virou,'numero');?>');
// $('#tt_contratos_inadp').html('Total Contratos:  R$ <?php //echo Format($tt_inadp,'numero');?>');

		$("#celular").inputmask({
        mask: ["(99) 9999-9999", "(99) 99999-9999", ]
      });
	  
		$("#inputCpf").mask("999.999.999-99");
		$('.inputLcValor').maskMoney({allowZero:true, allowNegative:true});
		
		$( "#dt_nascimento" ).datepicker({dateFormat: 'dd/mm/yy'});
		$( "#ted_dt" ).datepicker({dateFormat: 'dd/mm/yy'});
		$("#ted_dt").mask("99/99/9999");
		
		
		function openImputFile(){			
			$('#imageFile').click();
		}
		function salvarFormulario(){
			  	id= $("input#inputId").val(); 
				
				acao = 'atualizar';
				
			  	pessoa = $('#form_pessoas').serializeArray();
				//alert(JSON.stringify(pessoa));
				$.getJSON("<?php echo $link."/repositories/pessoas/pessoas.ctrl.php?acao=";?>"+acao, {pessoa: pessoa, trava_perfil : 1 }, function(result){
					if( result.status==1 ){	
						jAlert(result.msg,'Bom trabalho!','ok');
					}
					else{
						jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
					}
				 });
				
		  }
		  
		  (function() {
			  
			  
			  $("#form_ted").validate({
			rules: {       
					ted_dt: {required: true,dateBR:true},
					ted_bc: {required: true},
					ted_ag: {required: true},
					ted_cc: {required: true}		
					},					
                messages: {
                    ted_dt: "* Informe a data da transferencia", 
					ted_bc: "* Preencha o código do banco",  
					ted_ag: "* Preencha a agencia",  
					ted_cc: "* Preencha a conta"         
						},
                errorClass: "validate-msg",
                errorElement: "div",
                highlight: function(element, errorClass, validClass) {
                    $(element).parents('.form-group').addClass('red');
                },
                unhighlight: function(element, errorClass, validClass) {
					$(element).parents('.form-group').removeClass('red');
                }
		});
			  
			  
			  
		var current_upload = current_percent = current_total = 0;
		$("#form_imagem").ajaxForm({
               	beforeSend: function() {
					var fileInput =  document.getElementById("imageFile");
						if(fileInput.files[0].size > 2097152){
							atual = (fileInput.files[0].size/1048576).toFixed(2);
							jAlert("Arquivos de no máximo 2 MB!\nTamanho da imagem: " + atual + " MB", 'Alerta');
					}	
					else{
						var re = /(?:\.([^.]+))?$/;
						var ext = re.exec( document.getElementById("imageFile").value)[1];
						ext = ext.toLowerCase();
						if(ext!='jpg' && ext!='jpeg' && ext!='png'){ 
							jAlert("Favor enviar a imagem no formato: JPG ou PNG");
						}
						else{
							current_height = $('.uploading_user_foto').height();
							padTop = (current_height/2).toFixed(0);
							$(".uploading_user_foto").css("padding-top", padTop+"px");
							$('.uploading_user_foto').removeClass('hidden');
							
						}
					}	
				},
                uploadProgress: function(event, position, total, percentComplete) {
					
					mb_pos = (position / 1048576).toFixed(2);
					mb_tot = (total / 1048576).toFixed(2);
					if(percentComplete>current_percent){current_percent=percentComplete;}
					if(mb_pos>current_upload){current_upload=mb_pos;}
					if(mb_tot>current_total){current_total=mb_tot;}
					$("#bar_up_foto").html(current_percent+"%");
					$("#bar_up_foto").css( "width", percentComplete+"%" );
					$("#kb_upado").html(current_upload+" / "+current_total+" MB");
                },
                success: function() {
						
                },
                complete: function(xhr) {
						var re = /(?:\.([^.]+))?$/;
						var ext = re.exec( document.getElementById("imageFile").value)[1];
						ext = ext.toLowerCase();
							
						new_img = "<?php echo $link; ?>/imagens/fotos/thumb/"+document.getElementById("id_usr_img").value+"."+ext;
						d = new Date();
						document.getElementById("foto_usr").src= new_img+"?"+d.getTime();		
						$('.uploading_user_foto').addClass('hidden');	
                }
            });
        })();
		
		function submitForm(input){
				document.getElementById('linkid').click();
		}
		
		function active_menutab(box){
			$('.profile_menutab').removeClass('active');
			$('.'+box).addClass('active');
			
			$('.profilebox').addClass('hidden');
			$('#'+box).removeClass('hidden');
			
			
			<?php
			if($ehCliente){
			?>
			$('.menu_cliente').removeClass('active');
			if(box=='profiledados'){
				$('.menu_cliente_perfil').addClass('active');
			}
			else if(box=='profilevendedor'){
				$('.menu_cliente_venda').addClass('active');
			}
			else if(box=='profilecomprador'){
				$('.menu_cliente_compra').addClass('active');
			}
			<?php
			}
			?>
			
			
		}
		
		
		<?php if( isset($_GET['compra'])){ ?>
					active_menutab('profilecomprador');
					
					<?php if( isset($_GET['contrato']) && is_numeric($_GET['contrato'])){ ?>
							$('#openContrato<?php echo $_GET['contrato'];?>').click();
					<?php } ?>
		<?php }
			  elseif( isset($_GET['venda'])){ ?>
					active_menutab('profilevendedor');
		<?php  }
		elseif( isset($_GET['pendente'])){ ?>
					active_menutab('profilependentes');
		<?php  }elseif( !$ehCliente && isset($_GET['ted'])){ ?>
					active_menutab('profileted');
		<?php  } ?>
		
		var control_show_detalhe_fluxo =0;
		function control_detalhe_fluxo(){
				if(control_show_detalhe_fluxo==0){
					$('#btn_detalhe_fluxo').html('Esconder');
					control_show_detalhe_fluxo=1;
					$('.detalhe_fluxo').fadeIn(900);
				}
				else{
					$('#btn_detalhe_fluxo').html('Detalhar');
					$('.detalhe_fluxo').fadeOut(500);
					control_show_detalhe_fluxo=0;
					
				} 
		} 
		

	</script>
   <?php if( $total_proximos  != 0){ ?> 
    <script>
	
	

$(function () {

    $(document).ready(function () {
		
		
		$(".filtro_ct_data").mask("99/99/9999");	
		  $(".filtro_ct_data").datepicker({dateFormat: 'dd/mm/yy'}); 
		
	Highcharts.chart('grafico_fluxo_4_meses', {
		title: {
			text: 'Fluxo de caixa'
		},
		colors: ['#5CB85C',  '#D9534F'],
		xAxis: {
			categories: [<?php $ct=0;
							   foreach($fluxo_cliente as $fluxo_mes){
								   if($ct) echo ',';
								   echo "'".$fluxo_mes['mes']."'";
								   $ct++;
							   }
						 ?>]
		},
		labels: {
			items: [{ 
				style: {
					left: '50px',
					top: '18px',
					color: '#5CB85C'
				}
			}]
		},
		series: [{
			type: 'column',
			name: 'Crédito',
			data: [<?php $ct=0;
						 foreach($fluxo_cliente as $fluxo_mes){ 
							if($ct) echo ',';
							echo $fluxo_mes['receber'];
							$ct++;
									
						} ?>]
		}, {
			type: 'column',
			name: 'Débito',
			data: [<?php $ct=0;
						 foreach($fluxo_cliente as $fluxo_mes){ 
							if($ct) echo ',';
							echo $fluxo_mes['pagar'];
							$ct++;
									
						} ?>]
		},  {
			type: 'spline',
			name: 'Saldo',
			data: [<?php $ct=0;
						 foreach($fluxo_cliente as $fluxo_mes){ 
							if($ct) echo ',';
							echo $fluxo_mes['receber']-$fluxo_mes['pagar'];
							$ct++;
									
						} ?>],
			marker: {
				lineWidth: 3,
				lineColor: '#00A8E6',
				fillColor: 'white'
			}
		}]
	});
	
		
	$('#grafico_carteira').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
			type: 'pie'
		},
		title: {
			text: 'Carteira'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true
				},
				showInLegend: true
			}
		},
			
			colors: [ '#5CB85C', '#00ADED', '#D9534F'],

			series: [{
				name: 'Percent',
				colorByPoint: true, 
				data: [
				{  name: '<?php  echo 'Repasse<br>R$ '.Format($valor_transferir,'numero') ;?>', y: <?php  echo $valor_transferir ;?> , sliced: true, selected: true},
				{  name: '<?php  echo 'A vencer<br>R$ '.Format($valor_receber,'numero') ;?>', y: <?php  echo $valor_receber ;?> },
				{  name: '<?php  echo 'A pagar<br>R$ '.Format($carteira_cliente[0]['pagar'],'numero') ;?>', y: <?php  echo $carteira_cliente[0]['pagar'] ;?>  }]
				
			}]
		}); 
		   

 

var gaugeOptions = {

    chart: {
        type: 'solidgauge'
    },
	title: {
			text: 'Repasse'
		}, 

    pane: {
        center: ['50%', '70%'],
        size: '100%',
        startAngle: -92,
        endAngle: 92,
        background: {
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#5CB85C'], // green
            [0.5, '#5CB85C'], // yellow 
            [0.9, '#5CB85C'] // red
        ]
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

// The speed gauge
var chartSpeed = Highcharts.chart('grafico_repasse', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: <?php echo round($valor_receber + $valor_transferir);?>
    }, 

    series: [{
        data: [<?php echo $valor_repasse;?>],
        dataLabels: {
            format: '<div style="text-align:center">Repasse<br><span style="font-size:20px;color:' + 
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">'+'R$ <?php echo Format($valor_repasse,'numero');?> </div>'+'</span><br/>' 
                   
        } 
    }]

}));

 
			
	//final graficos 
			
		});	
		
	}); 

	
	
</script>



<?php }  ?>
</body></html>