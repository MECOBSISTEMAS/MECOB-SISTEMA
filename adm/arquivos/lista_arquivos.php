<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active="arquivos";
$layout_title = "MECOB - Arquivos";
$sub_menu_active="arquivos";
$tit_pagina = "Arquivos";
$tit_lista = " Lista de Arquivos";

if(!consultaPermissao($ck_mksist_permissao,"cad_arquivos","qualquer")){
	header("Location: ".$link."/401");
	exit;
}

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
                        <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/dashboard">Home</a>&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                        <li class="hidden"><a href="#">Arquivos</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
                            <?php if(consultaPermissao($ck_mksist_permissao,"cad_arquivos","adicionar")){?>  
                            <h3><button type="button" class="btn btn-brown" onClick="alimenta_modal_cad_arquivo(0,0);" />
                            Upload de arquivo</button></h3>
                            <?php } ?>  
							<?php
							    include($raiz."/adm/arquivos/filtros_arquivos.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                                <table id="listagem_arquivos"  class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th id="th_processo" class="pointer hidden-xs hidden-sm" onclick="ordenar('processo');">Processo</th>
                                        <th id="th_origem" class="pointer" onclick="ordenar('origem');">Origem </th>
                                        <th id="th_contrato" class="pointer" onclick="ordenar('contrato');">Contrato </th>
                                        <th id="th_nome" class="pointer" onclick="ordenar('nome');">Arquivo </th>
                                        <th id="th_data" class="pointer hidden-xs hidden-sm" onclick="ordenar('data');" >Data<i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i></th>
                                        <th id="th_tipo" class="pointer visible-lg " onclick="ordenar('tipo');">Tipo</th>
                                        <th id="th_status" class="pointer visible-lg " onclick="ordenar('status');">Status</th>
                                        <th>Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_arquivos">
                                    <tr><td id="td_carregando" colspan="10">Carregando Arquivos</td></tr>
                                    </tbody>
                                    
                                </table>
                                <div id="mais_resultados" ></div>
                                 <div id="loading_resultados" style="display:none; text-align:center; color:#667;"> 
                                    <h4> <img src="<?php echo $link."/imagens/loading_circles.gif";?>"  width="18px;" /> &nbsp;Carregando</h4>
                                </div>
                                <input id="cont_exibidos" type="hidden" value="0">
                                <input id="permite_carregar"  type="hidden" value="1">
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
    
    <div class="modal fade" id="md_cadastro_arquivo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_arquivo_tt"></h4>
      </div>
      <div class="modal-body" id="md_cadastro_arquivo_bd">
            <div class="panel panel-bordo">
                <div class="panel-heading">
                    Upload de Arquivo</div>
                <div class="panel-body pan">
                    <?php include($raiz."/adm/arquivos/form_arquivos.php");?>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <!-- <button id="arq_salvar" type="button" class="btn btn-brown" onClick="$('#form_arquivo').submit()">Salvar</button> -->
        <button id="arq_salvar" type="button" class="btn btn-brown" onClick="desabilitarSalvar()">Salvar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="md_arq_log" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_arq_log_tt"></h4>
      </div>
      <div class="modal-body" >
            <div class="panel panel-bordo">
                
                <div class="panel-body pan" id="md_arq_log_bd">
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
    
    <!-- fim cadastro de pessoas-->
    <?php include $raiz."/js/corejs.php";?>
    <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.validate.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.form.js"></script>
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
	

    <script>
	var filtro_arquivos="";
	var filtro_dt_arquivo="";
	var filtro_tp_arquivo="";
    var filtro_origem="";
	
	var filtrar = 0;
	
	var order ="data";
	var ordem ="desc";
	
	var delay_busca;
	
	$(function () {
        <?php
        if(isset($ini_filtro) && $ini_filtro){
        ?> 		filtrar_fields();
        <?php }
              else{
        ?>carregar_resultados();<?php }?>
        carregar_totais();
        $('[data-toggle="tooltip"]').tooltip();
        $("#filtro_dt_arquivo").mask("99/99/9999");
        $("#filtro_dt_arquivo").datepicker({dateFormat: 'dd/mm/yy'});
		$("#dt_arq").mask("99/99/9999");	
		$("#dt_arq").datepicker({dateFormat: 'dd/mm/yy'});

    });
		
		<!-- INICIO FORM E UPLOAD ARQUIVO -->
	
	$("#form_arquivo").validate({
		rules: {       
			arquivo :  {required: true},
			tp_arq: {required: true},					
			},
		messages: {
			arquivo: "* Selecione o arquivo.",  
			tp_arq: "* Preencha tipo do arquivo.",                 
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
	
	
	function alimenta_modal_cad_arquivo(id,arquivo){
			if(id==0){
				$('#md_cadastro_arquivo_tt').html('Upload de Arquivo');
				$("input#inputId").val(''); 
							
				$("#tp_arq").val(''); 
				
				$("#arq_arquivo").attr("src", "<?php echo $link."/"; ?>imagens/upload.jpg");
				$('#call_nm_arq').html('Selecione um arquivo');
				
			}
			else{
				$('#md_cadastro_produto_tt').html('Editar arquivo '+arquivo.nm_arq);
				
				$("#inputId").val(id); 

				$("#tp_arq").val(arquivo.tp_arq);
				$("#arq_arquivo").attr("src", "<?php echo $link."/"; ?>imagens/arquivo.jpg"); 
				$('#call_nm_arq').html(arquivo.nm_arq);

			}
			$("#arq_salvar").attr("disabled", false); //Habilita o botão Salvar
			$('#md_cadastro_arquivo').modal('show');
		}

		function openImputFile(){			
			$('#arqFile').click();
		}
		
		  (function() {
				var current_upload = current_percent = current_total = 0;
				$("#form_arquivo").ajaxForm({
						beforeSend: function(){ 
						},
						uploadProgress: function(event, position, total, percentComplete) {
							
							mb_pos = (position / 1048576).toFixed(2);
							mb_tot = (total / 1048576).toFixed(2);
							if(percentComplete>current_percent){current_percent=percentComplete;}
							if(mb_pos>current_upload){current_upload=mb_pos;}
							if(mb_tot>current_total){current_total=mb_tot;}
							$("#bar_up_arq").html(current_percent+"%");
							$("#bar_up_arq").css( "width", percentComplete+"%" );
							$("#kb_upado").html(current_upload+" / "+current_total+" MB");
						},
						success: function() {
								
						},
						complete: function(xhr) {
							var result = $.parseJSON(xhr.responseText);
							if( result.status==1 ){
								document.getElementById("cont_exibidos").value=0;
								carregar_resultados();
								$('#md_cadastro_arquivo').modal('hide');
								jAlert(result.msg,'Bom trabalho!','ok');
							}
							else{
								jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
							}
								
						}
            });
        })();
		
		function troca_img() {
														
			var fileInput =  document.getElementById("arqFile");
			var re = /(?:\.([^.]+))?$/;
			nm_arq =  document.getElementById("arqFile").value;
			var ext = re.exec( nm_arq)[1];
			ext = ext.toLowerCase();
			if((ext!='txt')&&(ext!='ret')){
				jAlert("Favor enviar arquivo no formato: txt, ret ");
			}
			else{
				current_height = $('.uploading_user_arq').height();
				padTop = (current_height/2).toFixed(0);
				$(".uploading_user_arq").css("padding-top", padTop+"px");
				$('.uploading_user_arq').removeClass('hidden');
				
				
				var file = $("#arqFile")[0].files[0];

				if (file) {
					var reader = new FileReader();
					reader.readAsDataURL(file);
					reader.onload = function(e) {
						//new_img = e.target.result;
						document.getElementById("arq_arquivo").src= '<?php echo $link."/"; ?>imagens/arquivo.jpg';		
						$('.uploading_user_arq').addClass('hidden');
						nm_arq = nm_arq.match(/\\([^\\]+)$/)[1];
						$('#call_nm_arq').html(nm_arq);
					};
				}					
			}
		}
		
		
		<!-- FINAL UPLOAD ARQUIVO -->

		  
<!--		  ROLAGEM INFINITA + FILTROS + ORDER -->

	function limpa_filtros(){
		$('#filtro_arquivos').val('');
		$('#filtro_dt_arquivo').val('');
        $('#filtro_tp_arquivo').val('');
        $('#filtro_origem').val('');


		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_arquivos=$('#filtro_arquivos').val();
		filtro_dt_arquivo= $('#filtro_dt_arquivo').val();
        filtro_tp_arquivo= $('#filtro_tp_arquivo').val();
        filtro_origem= $('#filtro_origem').val();
		
		
		$('#tbody_arquivos').html('<tr><td colspan="10">Carregando Arquivos</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
		

	$('#linha_totais').html('');
	$.getJSON('<?php echo $link."/repositories/arquivos/arquivos.ctrl.php?acao=listar_totais";?>',{
				filtro_arquivos: filtro_arquivos,
                filtro_dt_arquivo:filtro_dt_arquivo,
                filtro_tp_arquivo:filtro_tp_arquivo,
                filtro_origem:filtro_origem,
				filtrar: filtrar,
				ajax: 'true'
		  }, function(j){		
			$('#linha_totais').html('Encontrados '+j+' Arquivos');
			
		   });


}	

function carregar_resultados(){
		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		$.getJSON('<?php echo $link."/repositories/arquivos/arquivos.ctrl.php?acao=listar_arquivos";?>&inicial='+exibidos,{
                filtro_arquivos: filtro_arquivos,
                filtro_dt_arquivo:filtro_dt_arquivo,
                filtro_tp_arquivo:filtro_tp_arquivo,
                filtro_origem:filtro_origem,
				order:order,
				ordem:ordem,
				filtrar: filtrar,
				ajax: 'true'
		}, function(j){		
				cont_novos = 0;
				novos = "";
				//alert(JSON.stringify(j));
				for (var i = 0; i < j.length; i++) {
						exibidos++;
						cont_novos++;

						//open tr
						arquivos_aux = JSON.stringify(j[i]);
						if(j[i].status == 'CORROMPIDO' || j[i].status == 'INVALIDO' ){
							novos += '<tr id="tr_'+j[i].id+'" class="danger danger_row">';
						} else {
							novos += '<tr id="tr_'+j[i].id+'"  class="success success_row">';
						}

						
						//td processo
						novos += '<td class="hidden-xs hidden-sm">';
						novos += j[i].id;
						novos += '</td>';

                        //td origem
                        novos += '<td class="hidden-xs hidden-sm">';
                        novos += j[i].origem;
                        novos += '</td>';
						
						 //td origem
                        novos += '<td class="hidden-xs hidden-sm">';
                        if(j[i].contratos_id != null)
							novos += j[i].contratos_id;
                        novos += '</td>';

                    //td arquivo
						novos += '<td>';
						novos += j[i].nm_arq;
						
							novos += '<span class="visible-xs visible-sm">';
                            novos += ConverteData(j[i].dt_arq)+"<br>";; // TO DO
                            novos += j[i].tp_arq+"<br>";; // TO DO
							novos += '</span>';
						
						novos += '</td>';
												
						//td data
						novos += '<td class="hidden-xs hidden-sm">';
						novos += j[i].dt_arq;
						novos += '</td>';
						
						//td tipo
						novos += '<td class="hidden-xs hidden-sm">';
                        novos += j[i].tp_arq;
						novos += '</td>';

                        //td status
                        novos += '<td class="hidden-xs hidden-sm">';
						
						if(j[i].status == 'CORROMPIDO'){
								novos += j[i].status;
						}
						else if(j[i].dt_envio_banco != null && j[i].dt_envio_banco != '0000-00-00' && j[i].dt_envio_banco != ''   && j[i].pessoas_id_envio != null && j[i].pessoas_id_envio != '' && j[i].pessoas_id_envio > 0){
								novos += "ENVIADO P/ BANCO";
						}
						else{
								novos += j[i].status;
						}
						
                        
                        novos += '</td>';

						
						//td acao
						novos += "<td class='nowrap'>";
						
						
						<?php if(consultaPermissao($ck_mksist_permissao,"cad_arquivos","editar")){ ?>
						
						//novos += "<a><span class='pointer hidden' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' onClick='alimenta_modal_cad_arquivo("+j[i].id+","+arquivos_aux+" )'; > <i class='fa fa-pencil-square-o fs-19' > </i></span> </a>";
						
						if(j[i].tp_arq == 'REMESSA'  ){
							
							novos += "<a  title='Download' href='<?php echo $link."/inc/download.php?file="?>"+btoa(j[i].origem.toLowerCase()+'s/remessa/'+j[i].nm_arq)+"'  target='_blank'> <i class='fa fa-download fs-19' > </i> </a>";
						
							if(j[i].dt_envio_banco == null || j[i].dt_envio_banco == '0000-00-00' || j[i].dt_envio_banco == ''   || j[i].pessoas_id_envio == null || j[i].pessoas_id_envio == '' || j[i].pessoas_id_envio == 0){
								novos += "<a><span class='pointer ' data-toggle='tooltip' data-placement='left' title='Confirma Envio p/ Banco'  onClick='confirma_envio_banco("+j[i].id+","+arquivos_aux+" )'; > <i class='fa fa-share-square-o fs-19' > </i></span> </a>";
							}
							else{
								novos += "<a><span data-toggle='tooltip' data-placement='left' title='Arquivo enviado pro banco em "+ConverteData(j[i].dt_envio_banco)+"'   > <i class='fa fa-university green_light fs-19' > </i></span> </a>";
								
							}
						
						}
						else{
							<?php if(consultaPermissao($ck_mksist_permissao,"eh_admin","qualquer")){ ?>
							novos += "<a  title='Download' href='<?php echo $link."/inc/download.php?file="?>"+btoa(j[i].origem.toLowerCase()+'s/retorno/importados/'+j[i].nm_arq)+"'  target='_blank'> <i class='fa fa-download fs-19' > </i> </a>";
							<?php }?>
						}
						
						if(j[i].log != '' && j[i].log != null){
						
							novos += "<a><span class='pointer ' data-toggle='tooltip' data-placement='left' title='Ver Log'  onClick='show_log("+j[i].id+","+arquivos_aux+" )'; > <i class='fa fa-exclamation-triangle blue_light fs-19' > </i></span> </a>";
						}
						
						
						<?php } ?>  
						
						
						
						novos += "</td>";
						
					
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhum arquivo cadastrado</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_arquivos').html(novos);
				}
				else{
					$('#listagem_arquivos').append(novos);
				}
				document.getElementById("loading_resultados").style.display='none';
				document.getElementById("cont_exibidos").value = exibidos;
				document.getElementById("permite_carregar").value=libera_carregamento;
			   });
			 }
			 
		function ordenar(campo){
			 order = campo;
			 if(ordem == 'desc'){
				 ordem='asc';
				 icone = '<i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i>';
			 }
			 else{
				 ordem = 'desc';
				 icone = '<i class="fa fa-arrow-circle-down fl-rg ico_ordem" ></i>';
			 }
			 $('.ico_ordem').remove();
			 $('#th_'+campo).append(icone);
			 $('#cont_exibidos').val('0');
			 carregar_resultados();
			
		 }		
		 
		 function confirma_envio_banco(id, arquivo){
				jConfirm('Você só deve confirmar o envio quando o arquivo seja realmente enviado ao banco.', 'Confirma Envio do arquivo para o banco? Arquivo id: '+id, function(r) {
				if(r){
					$.getJSON("<?php echo $link."/repositories/arquivos/arquivos.ctrl.php?acao=confirma_envio_banco";?>", {
						id_arq: id, 
						u:<?php echo $_SESSION['id'];?>
						}, function(result){
							if( result.status==1 ){
								$('#cont_exibidos').val('0');
								$('#permite_carregar').val('1');
								filtrar=1;
								carregar_totais();
								carregar_resultados();
								jAlert(result.msg,'Bom trabalho!','ok');
		
							}
							else{
								jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
							}
					});
				}
				else{
					jAlert('As informações estão salvas. O envio do arquivo continua pendente.','Ação cancelada','ok');
				}
			});
		 }
		 
		 function show_log(id, arquivo){
			 $('#md_arq_log_tt').html('Log do arquivo '+id);
			 $('#md_arq_log_bd').html(arquivo.log);
			 $('#md_arq_log').modal('show');
		 }
		function desabilitarSalvar(){
			$("#arq_processando").attr("hidden", false);
			$("#arq_salvar").attr("disabled", true);
			$('#form_arquivo').submit();
		}
	</script>
    
</body>
</html>
