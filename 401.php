<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');

if(isset($_SESSION['condominio_id']))
unset($_SESSION['condominio_id']);
if(isset($_SESSION['condominio_slug']))
unset($_SESSION['condominio_slug']);
			
include_once $raiz."/valida_acesso.php";

$layout_title = "Condominio Fácil - 401";
$menu_active="condiminios";

include($raiz."/partial/html_ini.php");

include_once($raiz."/inc/util.php");

#print_r($condominios);
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
                            401 - Acesso restrito!</div>
                    </div>
                    <ol class="breadcrumb page-breadcrumb pull-right">
                        <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/dashboard">Home</a>&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                        <li class="hidden"><a href="#">Condomínios</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                        <li class="active">401</li>
                    </ol>
                    <div class="clearfix">
                    </div>
                </div>
                <!--END TITLE & BREADCRUMB PAGE-->
                <!--BEGIN CONTENT-->
                <div class="page-content">
                    <div id="tab-general">
                        <div class="row mbl">
                            <div class="col-lg-12 red_light" style="text-align:center;">
                            <div style="font-size:100px; font-weight:bold">401</div>
                            <h1>Você não tem permissão para acessar a página solicitada.</h1>
                            
                            
                            <div>
                            <i  style="font-size:100px; font-weight:bold; line-height:180px" class="fa fa-exclamation-triangle"></i> </div>
                            
                            <h1><button class="btn btn-danger"  onclick="history.go(-1);">
                            <i class="fa fa-reply mg-rg-3"></i> 
                            Voltar</button></h1>
                            
                            
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
    
    <!-- modal cadastro de condominios-->
<div class="modal fade" id="md_cadastro_condominios" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_condominios_tt"></h4>
      </div>
      <div class="modal-body" id="md_cadastro_condominios_bd">
            <div class="panel panel-grey">
                <div class="panel-heading">
                    Cadastro de Condomínio</div>
                <div class="panel-body pan">
                    <?php include($raiz."/adm/condominio/form_condominios.php");?>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" onClick="salvarFormulario()">Salvar</button>
      </div>
    </div>
  </div>
</div>
    <!-- fim cadastro de condominios-->
	<?php include $raiz."/js/corejs.php";?>
     <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
     
     
    <script>
		$("#inputCnpj").mask("99.999.999/9999-99");
		$("#inputCep").mask("99999-999");
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip();
		})
	
		function alimenta_modal_cad_condominio(id,condominio){
			if(id==0){
				$('#md_cadastro_condominios_tt').html('Cadastro de novo Condomínio');
				$("input#inputId").val(''); 
				$("input#inputNome").val(''); 
				$("input#inputCnpj").val('');
				$("input#inputCep").val('');
				$("#inputEstado").val('' );
				$("input#inputCidade").val( '' );
				$("input#inputBairro").val( '' );
				$("input#inputRua").val( '' );
				$("input#inputNumero").val('');
				$("input#adicionalInfo").val('');
				$("select#selectStatus").val('');
				
			}
			else{
				$('#md_cadastro_condominios_tt').html('Editar Condomínio '+condominio.nome);
				$("input#inputId").val(id); 
				$("input#inputNome").val(condominio.nome);
				$("input#inputCnpj").val(condominio.cnpj);
				$("input#inputCep").val(condominio.cep);
				$("#inputEstado").val(condominio.estado);
				$("input#inputCidade").val(condominio.cidade);
				$("input#inputBairro").val(condominio.bairro);
				$("input#inputRua").val(condominio.rua);
				$("input#inputNumero").val(condominio.numero);
				$("input#adicionalInfo").val(condominio.complemento);
				$("select#selectStatus").val(condominio.status_id);
				buscaCep();
			}
			$('#md_cadastro_condominios').modal('show');
		}

	
		function buscaCep(){
			  var cep_code = $('#inputCep').val();
			  if( cep_code.length < 7 ){limpa_endereco(); return;}
			  $.get("https://apps.widenet.com.br/busca-cep/api/cep.json", { code: cep_code },
				 function(result){
					if( result.status==1 ){
						rua = result.address.split('-');
						$("input#inputRua").val( rua[0] );
						$("#inputEstado").val( result.state );
						$("input#inputCidade").val( result.city );
						$("input#inputBairro").val( result.district );
						$("input#inputRua").attr('readonly', true);
						$("input#inputCidade").attr('readonly', true);
						$("#inputEstado").attr('readonly', true);
						$("input#inputBairro").attr('readonly', true);
					}
					else{limpa_endereco(); return;}
				 });
		 }
		  
		  function limpa_endereco(){
				//se não alimentou -- limpa dados:
				$("input#inputEstado").val('' );
				$("input#inputCidade").val( '' );
				$("input#inputBairro").val( '' );
				$("input#inputRua").val( '' );
				$("input#inputCidade").attr('readonly', false);
				$("input#inputBairro").attr('readonly', false);
				$("input#inputRua").attr('readonly', false); 
				$("#inputEstado").attr('readonly', false); 
		  }
		  
		  function salvarFormulario(){
			  	id= $("input#inputId").val(); 
				if(id.length ==0){
					acao = 'inserir';	
				}
				else{
					acao = 'atualizar';
				}
			  	condominio = $('#form_condominios').serializeArray();
				//alert(JSON.stringify(condominio));
				$.getJSON("<?php echo $link."/repositories/condominio/condominios.ctrl.php?acao=";?>"+acao, {condominio: condominio }, function(result){
					if( result.status==1 ){	
						//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")					
						jAlert(result.msg,'Bom trabalho!','ok');
						
						$('#md_cadastro_condominios').modal('hide');
						if (acao=="atualizar"){
							$("#td_nm_"+id).html($("input#inputNome").val());	
							$("#td_cnpj_"+id).html($("input#inputCnpj").val());	
							
							endereco = $("input#inputRua").val() + ',' + $("input#inputNumero").val() ;
							
							if ($("input#adicionalInfo").length>0){
								
								endereco = endereco + '<br>' +  $("input#adicionalInfo").val() ;	
							}
							endereco = endereco + '<br>' + $("input#inputBairro").val() + ',' + $("input#inputCidade").val() + '/' + $("select#inputEstado :selected").val() + ' - ' + $("input#inputCep").val()
							$("#td_end_"+id).html(endereco);	
							$("#td_stt_"+id).html($("select#selectStatus :selected").text());
							$("#tr_"+id).addClass("success");
							$("#td_span_"+id).html("Atualizado");
								
						}
						else{
							$('#popup_ok').on( "click", function() {
						 		 location.reload();
							});
						}
						
					}
					else{
						jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
					}
				 });

		  }
	
	</script>
    
</body>
</html>
