<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$layout_title = "MECOB - Controle de Acesso";

$menu_active="controle";
$layout_title = "MECOB - Controle de Acesso";
$tit_pagina = "Controle de Acesso";	
$tit_lista = " Controle de Acesso";	

if(!consultaPermissao($ck_mksist_permissao,"cad_controle","qualquer")){ 
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
                        <li class="hidden"><a href="#">controle</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
                            <?php if(consultaPermissao($ck_mksist_permissao,"cad_controle","editar")){ ?> 
                            <h3><button type="button" class="btn btn-brown" onClick="$('#md_cadastro_perfil').modal('show');">
                            Gerenciar Perfis</button></h3>
                            <?php } ?> 
                            <?php include($raiz."/adm/controle_acesso/filtros_controle.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                                <table id="listagem_controle"  class="table table-hover table-bordered" >
                                    <thead>
                                    <tr  id="thead_controle">
                                        <th>Módulo </th>
                                        <th>Perfil</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_controle">
                                    <tr><td id="td_carregando" colspan="10">Carregando controle de acesso</td></tr>
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
    
    <!-- modal cadastro de controle-->
<div class="modal fade" id="md_cadastro_perfil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_controle_tt"></h4>
      </div>
      <div class="modal-body" id="md_cadastro_controle_bd">
            <div class="panel panel-bordo">
                <div class="panel-heading">
                    Perfil de usuários</div>
                <div class="panel-body pan">
                    <form id="form_perfil" action="javascript:salvarPerfil()">
                        <div class="row pd-15">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                		<div class="placeholder">Nome:</div>
                                        <input id="inputNovoPerfil"  name="nome"  type="text" placeholder="Novo Perfil" class="form-control  with-placeholder fl-lf mg-rg-10 wd-80p" required="required" />
                                        <button type="submit" class="btn  btn-primary fl-lf">Inserir</button>
                                </div>
                            </div>
                            <div class="col-md-12 pd-tp-10">
                                <div  id="listagem_perf" >
                                    <table id="listagem_perfil"  class="table table-hover table-bordered" >
                                        <thead>
                                        <tr>
                                            <th>Perfil</th>
                                            <th>Ação</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody_perfil">
                                        <tr><td id="td_carregando_perfil" colspan="10">Carregando Perfis</td></tr>
                                        </tbody>
                                        
                                    </table>
                                </div>
                            
                            
                            </div>
                       </div>
                    </form>
                </div>
            </div>
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
	var filtro_perfil="";
	var filtrar = 0;
	
	var order ="nome";
	var ordem ="asc";
	
	  
		$(function () {
			carregar_perfis();
			carregar_resultados();	 
		  $('[data-toggle="tooltip"]').tooltip();
		  
		});
		
		function carregar_perfis(){
		
			$.getJSON('<?php echo $link."/repositories/controle_acesso/controle_acesso.ctrl.php?acao=lista_perfil";?>',{
					ajax: 'true'
			}, function(j){		
					//alert(JSON.stringify(j));
					novos='';
					for (var i = 0; i < j.length; i++) {
							
							//open tr
							perfil_aux = JSON.stringify(j[i]);
							novos += '<tr id="tr_perfil_'+j[i].id+'">';
							
							//td codigo produto
							novos += '<td>';
							novos += j[i].descricao;
							novos += '</td>'; 
							
							novos += '<td>';	
							
							if(j[i].fixo == 'N'){					
								novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Remover' data-original-title='Remover' onClick='remove_perfil("+j[i].id+","+perfil_aux+" )'; > <i class='fa fa-trash fs-19' > </i></span> </a>";
							}
							
							novos += "</td>";
							
							novos += '</tr>';
							
					}
					
					$('#tbody_perfil').html(novos);
				   });
		}
		
		  function salvarPerfil(){
			  	perfil= $("#inputNovoPerfil").val(); 
				//alert(JSON.stringify(controle));
				$.getJSON("<?php echo $link."/repositories/controle_acesso/controle_acesso.ctrl.php?acao=inserir_perfil";?>", {perfil: perfil }, function(result){
					if( result ){	
						//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
						$("#inputNovoPerfil").val(''); 
						carregar_perfis();
						carregar_resultados();
						jAlert(result.msg,'Bom trabalho!','ok');
					}
					else{
						jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
					}
				 });
				
		  }
		  
		  function remove_perfil(id, perfil){
			  
			  	jConfirm('Ao remover todos os usuários deste perfil se tornarão perfil "Padrão".', 'Remover perfil '+perfil.descricao+'?', function(r) {
						if(r){
							$.getJSON("<?php echo $link."/repositories/controle_acesso/controle_acesso.ctrl.php?acao=remover_perfil";?>", {id: id }, function(result){
									if( result ){	
										$("#tr_perfil_"+id).remove(); 
										carregar_perfis();
										carregar_resultados();
										jAlert('Removido com Sucesso.','Bom trabalho!','ok');
									}
									else{
										jAlert('Não foi possível remover!','Oops','alert');
									}
							 });
						}
						else{
							jAlert('As informações atuais estão seguras!','Ação Cancelada','ok');
						}
			});	
				
		  }
		  

	function filtrar_fields(){
		filtro_perfil=$('#filtro_perfil').val();
		
		$('#tbody_controle').html('<tr><td colspan="10">Carregando controle de acesso</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_resultados();
	}
	

	function carregar_resultados(){
		
		$.getJSON('<?php echo $link."/repositories/controle_acesso/controle_acesso.ctrl.php?acao=lista_controle";?>',{
				filtro_perfil: filtro_perfil,
				order:order,
				ordem:ordem,
				filtrar: filtrar,
				ajax: 'true'
		}, function(j){	
		
//				alert(JSON.stringify(j));
//				alert(JSON.stringify(j.perfil));
//				alert(JSON.stringify(j.modulo));
//				alert(JSON.stringify(j.permissao));

				perfil=j.perfil;
				modulo=j.modulo;
				permissao=j.permissao;
				
				thead = '<th>Módulo</th>';
				for (var i = 0; i < perfil.length; i++) {
						if(perfil[i].id==1){
								<?php if(!consultaPermissao($ck_mksist_permissao,"eh_admin","qualquer")){ ?> 
									continue;
								<?php } ?> 
							}
						//alert(JSON.stringify(perfil));
						thead += '<th>'+perfil[i].descricao+'</th>';
						
				}
				
				tbody = "";
				for (var i = 0; i < modulo.length; i++) {
						//alert(JSON.stringify(modulo));
						tbody += '<tr id="tr_'+modulo[i].id+'">';
						
						tbody += '<td>';
						tbody += modulo[i].nome;
						if(modulo[i].descricao!= null )tbody += '<br>'+modulo[i].descricao;
						tbody += '</td>';
						
						for (var j = 0; j < perfil.length; j++) {
							
							if(perfil[j].id==1){
								<?php if(!consultaPermissao($ck_mksist_permissao,"eh_admin","qualquer")){ ?> 
									continue;
								<?php } ?> 
							}
							
							
							visualizar = adicionar = editar = conceder = '';
							//alert(JSON.stringify(permissao));
							for (w = 0; w < permissao.length; w++) {
								if (permissao[w].modulo_id == modulo[i].id && permissao[w].perfil_id == perfil[j].id) {
									if(permissao[w].visualizar=='S'){visualizar = 'checked'; }
									if(permissao[w].adicionar=='S'){adicionar = 'checked'; }
									if(permissao[w].editar=='S'){editar = 'checked'; }
									if(permissao[w].conceder=='S'){conceder = 'checked'; }
									break;
								}
							}
							
							
							
							tbody += '<td>';
                            tbody += '<input id="check_'+modulo[i].id+'_'+perfil[j].id+'_visualizar" type="checkbox" onclick="seta_permissao('+modulo[i].id+','+perfil[j].id+',&#39;visualizar&#39;)" '+visualizar+' > Visualizar<br>';
							
							tbody += '<input id="check_'+modulo[i].id+'_'+perfil[j].id+'_adicionar" type="checkbox" onclick="seta_permissao('+modulo[i].id+','+perfil[j].id+',&#39;adicionar&#39;)" '+adicionar+' > Adicionar<br>';
							
							tbody += '<input id="check_'+modulo[i].id+'_'+perfil[j].id+'_editar" type="checkbox" onclick="seta_permissao('+modulo[i].id+','+perfil[j].id+',&#39;editar&#39;)" '+editar+' > Editar<br>';
							
							//tbody += '<input id="check_'+modulo[i].id+'_'+perfil[j].id+'_conceder" type="checkbox" onclick="seta_permissao('+modulo[i].id+','+perfil[j].id+',&#39;conceder&#39;)" '+conceder+' > Conceder<br>';
							
							tbody += '</td>';
						}
						tbody += '</tr>';
				}
				
				$('#thead_controle').html(thead);
				$('#tbody_controle').html(tbody);
					
			   });
			 }
			 
			 function seta_permissao(modulo, perfil, permissao){
				 
				 <?php if(consultaPermissao($ck_mksist_permissao,"cad_controle","editar")){ ?> 
				 
					if($("#check_"+modulo+"_"+perfil+"_"+permissao).is(':checked')){
						acao = 'seta_permissao';
					}
					else{
						acao = 'remove_permissao';
					}
					
					$.getJSON("<?php echo $link."/repositories/controle_acesso/controle_acesso.ctrl.php?acao=";?>"+acao, {modulo: modulo,perfil: perfil,permissao: permissao }, function(result){});
				
				<?php }else{ ?>  
							if($("#check_"+modulo+"_"+perfil+"_"+permissao).is(':checked')){
								$("#check_"+modulo+"_"+perfil+"_"+permissao).prop('checked', false);

							}
							else{
								$("#check_"+modulo+"_"+perfil+"_"+permissao).prop('checked', true);
							}
							jAlert('Você não possui permissão para editar os acessos!'); 
					<?php } ?> 
			 }
			 	
		 
	  
	
	</script>
    
</body>
</html>
