<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active="cadastros";
$layout_title = "MECOB - Haras";
$sub_menu_active="haras";	
$tit_pagina = "Haras";	
$tit_lista = " Lista de Haras";	

if(!consultaPermissao($ck_mksist_permissao,"cad_haras","qualquer")){ 
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
                        <li class="hidden"><a href="#">Haras</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
                            <?php 
							
							if(consultaPermissao($ck_mksist_permissao,"cad_haras","adicionar")){ ?> 
                            	<h3><button type="button" class="btn btn-brown" onClick="alimenta_modal_cad_haras(0,'');">
                            Cadastrar Novo</button></h3>
                            <?php 
							}
							include($raiz."/adm/haras/filtros_haras.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                                <table id="listagem_harass"  class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th class="hidden-xs hidden-sm">#</th>
                                        <th id="th_nome" class="pointer" onclick="ordenar('nome');">Haras <i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i></th>
                                        <th id="th_contato" class="pointer hidden-xs hidden-sm" onclick="ordenar('contato');" >Contato</th>
                                        <th id="th_telefone" class="pointer visible-lg " onclick="ordenar('telefone');">Telefone</th>
                                        <th class=" hidden-xs hidden-sm"  >Proprietário</th>
                                       
                                        
                                        
                                        <th>Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_haras">
                                    <tr><td id="td_carregando" colspan="10">Carregando Haras</td></tr>
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
    
    <!-- modal cadastro de haras-->
<div class="modal fade" id="md_cadastro_haras" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_haras_tt"></h4>
      </div>
      <div class="modal-body" id="md_cadastro_haras_bd">
            <div class="panel panel-bordo">
                <div class="panel-heading">
                    Cadastro de <?php echo $tit_pagina; ?></div>
                <div class="panel-body pan">
                    <?php include($raiz."/adm/haras/form_haras.php");?>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="btn-save-haras" type="button" class="btn btn-brown" onClick="$('#form_haras').submit()">Salvar</button>
      </div>
    </div>
  </div>
</div>
    
    
    
    <!-- fim cadastro de pessoas-->
    <?php include $raiz."/js/corejs.php";?>
    <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.validate.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
	

    <script>
	var filtro_haras="";
	var filtro_proprietario="";
	
	var filtrar = 0;
	
	var order ="nome";
	var ordem ="asc";
	
	var delay_busca;
	
	
	$("#inputTelefone").inputmask({
        mask: ["(99) 9999-9999", "(99) 99999-9999", ]
      });
	  
		$(function () {
		  <?php 
		  		if(isset($ini_filtro) && $ini_filtro){
		  ?> 		filtrar_fields();
		  <?php }
				else{
		  ?>carregar_resultados();<?php }?>		  
		  carregar_totais();
		  $('[data-toggle="tooltip"]').tooltip();
		  $("#inputCep").mask("99999-999");	
		  $('#a_animate_sidebar_pessoas').click();	
		  
		});
		
		$("#form_haras").validate({
			rules: {       
					nome: {required: true},				
					},					
                messages: {
                    nome: "* Preencha o nome",             
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
	
		function alimenta_modal_cad_haras(id,haras){
			limpa_proprietario();
			if(id==0){
				$('#md_cadastro_haras_tt').html('Cadastro de novo Haras');
				$("input#inputId").val(''); 
				$("input#inputNome").val(''); 
				$("input#inputContato").val('');
				$("input#inputTelefone").val('');
				
				$("input#inputProprietario_id").val( '' );
				$("input#inputNomeProp").val( '' );
				$("input#inputDocProp").val( '' );
				
				$("input#inputCep").val('');
				$("#inputEstado").val('' );
				$("input#inputCidade").val( '' );
				$("input#inputBairro").val( '' );
				$("input#inputRua").val( '' );
				$("input#inputNumero").val('');
				$("#inputComplemento").val('');
				limpa_endereco();
				
			}
			else{
				$('#md_cadastro_haras_tt').html('Editar Cadastro do haras '+haras.nome);
				limpa_endereco();
				$("input#inputId").val(id); 
				$("input#inputNome").val(haras.nome);
				$("input#inputContato").val(haras.contato);
				$("input#inputTelefone").val(haras.telefone);
				
				$("input#inputProprietario_id").val(haras.proprietario_id);
				$("input#inputNomeProp").val(haras.proprietario_nome);
				$("input#inputDocProp").val(haras.proprietario_doc);

				$("input#inputCep").val(haras.cep);
				$("#inputEstado").val(haras.estado);
				$("input#inputCidade").val(haras.cidade);
				$("input#inputBairro").val(haras.bairro);
				$("input#inputRua").val(haras.rua);
				$("input#inputNumero").val(haras.numero);
				$("#inputComplemento").val(haras.complemento);
				buscaCep();
				
				if($.isNumeric(haras.proprietario_id)){
					proprietario = {	'id':haras.proprietario_id,
										'nome':haras.prop_nome,
										'email':haras.proprietario_email,
										'foto':haras.proprietario_foto,
										'cpf_cnpj':haras.proprietario_cpf_cnpj
									 };
					escolhe_autocomplete_pessoa(proprietario);
				}
			
				
			}
			$('#md_cadastro_haras').modal('show');
		}
		
		function buscaCep(){
			  $("input#inputCidade").attr('readonly', false);
			  $("input#inputBairro").attr('readonly', false);
			  $("input#inputRua").attr('readonly', false); 
			  $("#inputEstado").attr('readonly', false); 
			  
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
						
						if(rua[0]!=""){
							$("input#inputRua").attr('readonly', true);
						}
						if(result.district!=""){
							$("input#inputBairro").attr('readonly', true);
						}
						if(result.city!=""){
							$("input#inputCidade").attr('readonly', true);
						}
						if(result.state!=""){
							$("#inputEstado").attr('readonly', true);
						}
						
					}
					else{
						//limpa_endereco();
						return;
					}
				 });
		 }
		  
		  function limpa_endereco(){
				//se não alimentou -- limpa dados:
				$("input#inputEstado").val('' );
				$("input#inputCidade").val( '' );
				$("input#inputBairro").val( '' );
				$("input#inputRua").val( '' );
				$("input#inputComplemento").val( '' );
				$("input#inputCidade").attr('readonly', false);
				$("input#inputBairro").attr('readonly', false);
				$("input#inputRua").attr('readonly', false); 
				$("#inputEstado").attr('readonly', false); 
		  }
		  

		  function salvarFormulario(){
			  $('#btn-save-haras').addClass('hidden');
			  	id= $("input#inputId").val(); 
				if(id.length ==0){
					acao = 'inserir';	
				}
				else{
					acao = 'atualizar';
				}
			  	haras = $('#form_haras').serializeArray();
				//alert(JSON.stringify(haras));
				$.getJSON("<?php echo $link."/repositories/haras/haras.ctrl.php?acao=";?>"+acao, {haras: haras }, function(result){
					if( result.status==1 ){	
						//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
						
						document.getElementById("cont_exibidos").value=0;
						carregar_resultados();
						carregar_totais();
						$('#md_cadastro_haras').modal('hide');
						jAlert(result.msg,'Bom trabalho!','ok');
						
					}
					else{
						jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
					}
					$('#btn-save-haras').removeClass('hidden');
				 });
				
		  }
		  
		  
<!--		  ROLAGEM INFINITA + FILTROS + ORDER -->

	function limpa_filtros(){
		$('#filtro_haras').val('');
		$('#filtro_proprietario').val('');
		
		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_haras=$('#filtro_haras').val();
		filtro_proprietario= $('#filtro_proprietario').val();
		
		
		$('#tbody_haras').html('<tr><td colspan="10">Carregando Haras</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
		

	$('#linha_totais').html('');
	$.getJSON('<?php echo $link."/repositories/haras/haras.ctrl.php?acao=listar_totais";?>',{
				filtro_haras: filtro_haras,
				filtro_proprietario:filtro_proprietario,
				filtrar: filtrar,
				ajax: 'true'
		  }, function(j){		
			$('#linha_totais').html('Encontrados '+j+' Haras');
			
		   });


}	

function carregar_resultados(){
		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		$.getJSON('<?php echo $link."/repositories/haras/haras.ctrl.php?acao=listar_haras";?>&inicial='+exibidos,{
				filtro_haras: filtro_haras,
				filtro_proprietario:filtro_proprietario,
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
						
						proprietario='';
						if($.isNumeric(j[i].proprietario_id)){
							if(j[i].prop_nome!=null)
								proprietario +=j[i].prop_nome+' ';
							if(j[i].proprietario_cpf_cnpj!=null)
								proprietario +=j[i].proprietario_cpf_cnpj;
						}
						else{
							if(j[i].proprietario_nome!=null)
								proprietario +=j[i].proprietario_nome+' ';
							if(j[i].proprietario_doc!=null)
								proprietario +=j[i].proprietario_doc;
						}
						
						//open tr
						haras_aux = JSON.stringify(j[i]);
						novos += '<tr id="tr_'+j[i].id+'">';
						
						//td #
						novos += '<td class="hidden-xs hidden-sm">';
						novos += exibidos;
						novos += '</td>';
						
						//td codigo produto
						novos += '<td>';
						novos += j[i].nome;
						
							novos += '<span class="visible-xs visible-sm">';
								if(j[i].contato!=null) novos += j[i].contato+'<br>';
								if(j[i].telefone!=null) novos += j[i].telefone+'<br>';
								novos += proprietario;
								
							novos += '</span>';					
						
						novos += '</td>';
												
						//td documento 
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].contato!=null) novos += j[i].contato;
						novos += '</td>';
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].telefone!=null)novos += j[i].telefone;
						novos += '</td>';
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
							novos += proprietario;
						novos += '</td>';
						
												
						
						
						//td acao
						novos += "<td>";
						
						
						<?php if(consultaPermissao($ck_mksist_permissao,"cad_haras","editar")){ ?>  
						
						novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' onClick='alimenta_modal_cad_haras("+j[i].id+","+haras_aux+" )'; > <i class='fa fa-pencil-square-o fs-19' > </i></span> </a>";
						<?php } ?>  
						
						
						
						novos += "</td>";
						
					
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhum haras cadastrado</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_haras').html(novos);
				}
				else{
					$('#listagem_haras').append(novos);
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
		 
function gerar_pdf_lista(){
	$('#form_filtros_haras').attr('action', '<?php echo $link."/inc/pdf/gera_pdf_listas.php?pagina=haras";?>&order='+order+'&ordem='+ordem); 
	$('#form_filtros_haras').attr('target', '_blank');
	$('#form_filtros_haras').submit();
	$('#form_filtros_haras').attr('action', 'javascript:filtrar_fields();');
	$('#form_filtros_haras').attr('target', '_top');
} 
		  
	
	</script>
    
</body>
</html>
