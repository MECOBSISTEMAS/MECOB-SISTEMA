<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active="cadastros";
$layout_title = "MECOB - Lotes";
$sub_menu_active="lotes";	
$tit_pagina = "Lotes";	
$tit_lista = " Lista de lotes";	

if(!consultaPermissao($ck_mksist_permissao,"cad_lotes","qualquer")){ 
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
                        <li class="hidden"><a href="#">Lotes</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
                            if(consultaPermissao($ck_mksist_permissao,"cad_lotes","adicionar")){ ?> 
                            	<h3><button type="button" class="btn btn-brown" onClick="alimenta_modal_cad_lotes(0,'');">
                           		 Cadastrar Novo</button></h3>
                            <?php 
							}
							include($raiz."/adm/lotes/filtros_lotes.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                                <table id="listagem_lotes"  class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th class="hidden-xs hidden-sm">#</th>
                                        
                                        <th id="th_nome" class="pointer" onclick="ordenar('nome');">Lote <i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i></th>
                                        <th id="th_registro" class="pointer visible-lg " onclick="ordenar('registro');">Registro</th>
                                        
                                        <th id="th_data" class="pointer hidden-xs hidden-sm" onclick="ordenar('data');" >Data Nasc.</th>
                                        <th id="th_tipo" class="pointer hidden-xs hidden-sm" onclick="ordenar('tipo');" >Tipo</th>
                                       
                                        
                                        
                                        <th>Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_lotes">
                                    <tr><td id="td_carregando" colspan="10">Carregando lotes</td></tr>
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
    
    <!-- modal cadastro de lotes-->
<div class="modal fade" id="md_cadastro_lotes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_lotes_tt"></h4>
      </div>
      <div class="modal-body" id="md_cadastro_lotes_bd">
            <div class="panel panel-bordo">
                <div class="panel-heading">
                    Cadastro de <?php echo $tit_pagina; ?></div>
                <div class="panel-body pan">
                    <?php include($raiz."/adm/lotes/form_lotes.php");?>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="btn-save-lote" type="button" class="btn btn-brown" onClick="$('#form_lotes').submit()">Salvar</button>
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
	var filtro_lotes="";
	var filtro_tipo="";
	var filtrar = 0;
	
	var order ="nome";
	var ordem ="asc";
	
	  
		$(function () {
		  <?php 
		  		if(isset($ini_filtro) && $ini_filtro){
		  ?> 		filtrar_fields();
		  <?php }
				else{
		  ?>carregar_resultados();<?php }?>		  
		  carregar_totais();
		  $('[data-toggle="tooltip"]').tooltip();
		  $("#inputDtNasc").mask("99/99/9999");	
		  $("#inputDtNasc").datepicker({dateFormat: 'dd/mm/yy'});
		  $('#a_animate_sidebar_pessoas').click();	
		  
		});
		
		$("#form_lotes").validate({
			rules: {       
					nome: {required: true},
					tipo: {required: true},				
					},					
                messages: {
                    nome: "* Preencha o nome",  
					tipo: "* Informe o tipo",             
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
	
		function alimenta_modal_cad_lotes(id,lotes){
			if(id==0){
				$('#md_cadastro_lotes_tt').html('Cadastro de novo lote');
				$("input#inputId").val(''); 
				$("input#inputNome").val(''); 
				$("input#inputRegistro").val('');
				$("input#inputDtNasc").val('');
				$("#SelectTipo").val( '' );
			}
			else{
				$('#md_cadastro_lotes_tt').html('Editar Cadastro do lote '+lotes.nome);
				$("input#inputId").val(id); 
				$("input#inputNome").val(lotes.nome);
				$("input#inputRegistro").val(lotes.num_registro);
				$("input#inputDtNasc").val(ConverteData(lotes.dt_nascimento));
				$("#SelectTipo").val(lotes.tipo);	
			}
			$('#md_cadastro_lotes').modal('show');
		}


		  function salvarFormulario(){
			  	$('#btn-save-lote').addClass('hidden');
				
				
			  	id= $("input#inputId").val(); 
				if(id.length ==0){
					acao = 'inserir';	
				}
				else{
					acao = 'atualizar';
				}
			  	lotes = $('#form_lotes').serializeArray();
				//alert(JSON.stringify(lotes));
				$.getJSON("<?php echo $link."/repositories/lotes/lotes.ctrl.php?acao=";?>"+acao, {lotes: lotes }, function(result){
					if( result.status==1 ){	
						//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
						
						document.getElementById("cont_exibidos").value=0;
						carregar_resultados();
						carregar_totais();
						$('#md_cadastro_lotes').modal('hide');
						jAlert(result.msg,'Bom trabalho!','ok');
						
					}
					else{
						jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
					}
					$('#btn-save-lote').removeClass('hidden');
				 });
				
		  }
		  
		  
<!--		  ROLAGEM INFINITA + FILTROS + ORDER -->

	function limpa_filtros(){
		$('#filtro_lotes').val('');
		$('#filtro_tipo').val('');
		
		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_lotes=$('#filtro_lotes').val();
		filtro_tipo=$('#filtro_tipo').val();
		
		
		$('#tbody_lotes').html('<tr><td colspan="10">Carregando lotes</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
		

	$('#linha_totais').html('');
	$.getJSON('<?php echo $link."/repositories/lotes/lotes.ctrl.php?acao=listar_totais";?>',{
				filtro_lotes: filtro_lotes,
				filtro_tipo:filtro_tipo,
				filtrar: filtrar,
				ajax: 'true'
		  }, function(j){		
			$('#linha_totais').html('Encontrados '+j+' lotes');
			
		   });


}	

function carregar_resultados(){
		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		$.getJSON('<?php echo $link."/repositories/lotes/lotes.ctrl.php?acao=lista_lotes";?>&inicial='+exibidos,{
				filtro_lotes: filtro_lotes,
				filtro_tipo:filtro_tipo,
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
						lotes_aux = JSON.stringify(j[i]);
						novos += '<tr id="tr_'+j[i].id+'">';
						
						//td #
						novos += '<td class="hidden-xs hidden-sm">';
						novos += exibidos;
						novos += '</td>';
						
						//td codigo produto
						novos += '<td>';
						novos += j[i].nome;
						
							novos += '<span class="visible-xs visible-sm">';
								if(j[i].num_registro!=null) novos += j[i].num_registro+'<br>';
								if(j[i].dt_nascimento!=null && j[i].dt_nascimento!='0000-00-00') novos += ConverteData(j[i].dt_nascimento)+'<br>';
								if(j[i].tipo!=null) novos += j[i].tipo;
								
							novos += '</span>';					
						
						novos += '</td>';
												
						//td documento 
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].num_registro!=null) novos += j[i].num_registro;
						novos += '</td>';
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].dt_nascimento!=null) novos += ConverteData(j[i].dt_nascimento);
						novos += '</td>';
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].tipo!=null) novos += j[i].tipo;
						novos += '</td>';				
						
						
						//td acao
						novos += "<td>";
						
						
						<?php if(consultaPermissao($ck_mksist_permissao,"cad_lotes","editar")){ ?>  
						
						novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' onClick='alimenta_modal_cad_lotes("+j[i].id+","+lotes_aux+" )'; > <i class='fa fa-pencil-square-o fs-19' > </i></span> </a>";
						<?php } ?>  
						
						
						
						novos += "</td>";
						
					
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhum lote cadastrado</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_lotes').html(novos);
				}
				else{
					$('#listagem_lotes').append(novos);
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
	$('#form_filtros_lotes').attr('action', '<?php echo $link."/inc/pdf/gera_pdf_listas.php?pagina=lotes";?>&order='+order+'&ordem='+ordem); 
	$('#form_filtros_lotes').attr('target', '_blank');
	$('#form_filtros_lotes').submit();
	$('#form_filtros_lotes').attr('action', 'javascript:filtrar_fields();');
	$('#form_filtros_lotes').attr('target', '_top');
} 
		  
	
	</script>
    
</body>
</html>
