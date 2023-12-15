<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active = "teds"; 
$layout_title = "MECOB - TEDs";
$tit_pagina = "Teds";	
$tit_lista = " Lista de TEDs";	

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
                        <li class="hidden"><a href="#">teds</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
							include($raiz."/adm/teds/filtros_teds.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                            <table id="listagem_teds"  class="table table-hover table-bordered" >
                                <thead>
                                <tr>
                                    <th id="th_id" class="pointer " onclick="ordenar('id');" >
                                    ID
                                    </th>
                                    
                                    <th id="th_agendada" class="pointer " onclick="ordenar('agendada');" >
                                    Agendada p/ 
                                    </th>
                                    
                                    <th id="th_inclusao" class="pointer hidden-xs hidden-sm" onclick="ordenar('inclusao');" >
                                    Data Inclusao  <i class="fa fa-arrow-circle-down fl-rg ico_ordem" ></i>
                                    </th>
                                    
                                     <th  id="th_valor" class="pointer hidden-xs hidden-sm" onclick="ordenar('valor');" >
                                     Valor da TED
                                     </th>
                                     <th id="th_lancamentos" class="pointer hidden-xs hidden-sm" onclick="ordenar('lancamentos');" >
                                     Outros Lançamentos
                                     </th>
                                     <th id="th_status" class="pointer hidden-xs hidden-sm" onclick="ordenar('status');" >
                                     Status
                                     </th>
                                     <th id="th_nome" class="pointer hidden-xs hidden-sm" onclick="ordenar('nome');" >
                                     Cliente
                                     </th>
                                    
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody id="tbody_teds">
                                <tr><td id="td_carregando" colspan="10">Carregando TEDs</td></tr>
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
    
    
    <div class="modal fade" id="md_cadastro_teds" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_teds_tt"></h4>
      </div>
      <div class="modal-body" >
            <div class="panel panel-bordo">
                <div class="panel-heading">
                    Detalhes da TED</div>
                <div id="md_cadastro_teds_bd" class="panel-body pan">
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
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
    <script>
	
	var filtro_teds="";
	var filtro_id="";
	var filtro_dt_inclusao="";
	var filtro_per_ini="";
	var filtro_per_fim="";
	var filtro_status="";
	var filtro_vendedor="";
	var filtrar = 0;
	
	var order ="inclusao";
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
		  $("#filtro_per_ini").mask("99/99/9999");	
		  $("#filtro_per_ini").datepicker({dateFormat: 'dd/mm/yy'});
		  
		  $("#filtro_per_fim").mask("99/99/9999");	
		  $("#filtro_per_fim").datepicker({dateFormat: 'dd/mm/yy'});
		  
		  $("#filtro_dt_inclusao").mask("99/99/9999");	
		  $("#filtro_dt_inclusao").datepicker({dateFormat: 'dd/mm/yy'});
	});
		
	
	function alimenta_modal_cad_teds(id,teds){
		$('#md_cadastro_teds_tt').html('TED ');
		
		//alert(JSON.stringify(teds));
		
		html_ted = "<br><strong>TED agendada para "+ConverteData(teds.dt_ted)+"</strong>";	
		html_ted += "<br>Valor R$ "+number_format(teds.vl_ted,2,',','.');
		html_ted += "<br>Beneficiário: "+teds.nome+" ("+teds.cpf_cnpj+")";
		html_ted += "<br>Banco: "+teds.banco;
		html_ted += "<br>Agência: "+teds.agencia+"-"+teds.dv_agencia;
		html_ted += "<br>Conta: "+teds.conta+"-"+teds.dv_conta;			
		
		html_ted  += "<br>Cadastrada em "+ConverteData(teds.dt_inclusao);
		html_ted  += " por "+teds.nome_incluiu;
			
		if(teds.doc_incluiu != null)
			html_ted  += " ("+teds.doc_incluiu+")";
		
		
		html_ted  += "<br><br> <strong>Total parcelas: "+teds.tt_parcelas+"</strong>";
		
		 $.getJSON('<?php echo $link."/repositories/teds/teds.ctrl.php?acao=lancamentos_ted";?>',{
				ted_id:teds.id,
				ajax: 'true'
		  }, function(retorno){	
		  	//alert(JSON.stringify(j));	 
			j = retorno.parc;
			for (var i = 0; i < j.length; i++) {
				//alert(JSON.stringify(lancamentos));
				
				if(j[i].contratos_id == 'adimplencia' && j[i].contratos_id.contratos_id_pai == null){
					honor = ((j[i].honor_adimp / 100) *  j[i].vl_pagto  ) ;
				}
				else{
					honor = j[i].vl_pagto-( j[i].vl_pagto / (1+ (j[i].honor_adimp / 100)  ));
				}
				
				html_ted += '<br>Contrato '+j[i].contratos_id+' - Parcela: '+j[i].nu_parcela+' - Valor: R$ '+number_format(j[i].vl_pagto-honor,2,',','.')+' ( R$ '+number_format(j[i].vl_pagto,2,',','.')+' - R$ '+number_format(honor,2,',','.')+' honorários )';
				
			}
			
			html_ted  += "<br><br> <strong>Outros Lançamentos:</strong>";
			j = retorno.lanc;
			for (var i = 0; i < j.length; i++) {
				//alert(JSON.stringify(lancamentos));
				html_ted += '<br>Tipo '+j[i].tipo+' - Valor: R$ '+number_format(j[i].valor,2,',','.')+' OBS: '+j[i].obs;
			}
			if(i==0){
				html_ted += "<br> Nenhum lançamento além das parcelas.";
			}
			
			if(teds.log_zerar!=null){
				html_ted += "<br><br><strong>"+teds.log_zerar+"</strong>";
			}
			
			$('#md_cadastro_teds_bd').html(html_ted);
		
			$('#md_cadastro_teds').modal('show');
			
		   });
		
		
		
	}
		

	function limpa_filtros(){
		$('#filtro_id').val('');
		$('#filtro_dt_inclusao').val('');
		$('#filtro_per_ini').val('');
		$('#filtro_per_fim').val('');
		$('#filtro_status').val('');
		$('#filtro_vendedor').val('');
		
		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_id=$('#filtro_id').val();
		filtro_dt_inclusao=$('#filtro_dt_inclusao').val();
		filtro_status=$('#filtro_status').val();
		filtro_vendedor=$('#filtro_vendedor').val();
		filtro_per_ini=$('#filtro_per_ini').val();
		filtro_per_fim=$('#filtro_per_fim').val();
		
		$('#tbody_teds').html('<tr><td colspan="10">Carregando Teds</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
	
	$('#linha_totais').html(''); 
	<?php if($ehCliente){   ?>
	filtrar = 1;
	filtro_vendedor = <?php echo $user_id;?>;
	<?php }  ?>
	$.getJSON('<?php echo $link."/repositories/teds/teds.ctrl.php?acao=listar_totais";?>',{
				filtro_id:filtro_id,
				filtro_dt_inclusao:filtro_dt_inclusao,
				filtro_per_ini:filtro_per_ini,
			    filtro_per_fim:filtro_per_fim,
				filtro_status:filtro_status,
				filtro_vendedor:filtro_vendedor,
				filtrar: filtrar,
				ajax: 'true'
		  }, function(j){	
		  	//alert(JSON.stringify(j));	 
		  	linha_total = 'Encontrados '+j.total_teds+' teds, totalizando '+number_format(j.vl_ted,2,',','.');
			lancamentos = j.lancamentos;
			for (var i = 0; i < lancamentos.length; i++) {
				//alert(JSON.stringify(lancamentos));
				linha_total += '<br>Tipo '+lancamentos[i].tipo+' - Valor: R$ '+number_format(lancamentos[i].valor,2,',','.');
			}
			
			$('#linha_totais').html(linha_total);
			
		   });
}	

function carregar_resultados(){
		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		
		<?php if($ehCliente){   ?>
		filtrar = 1;
		filtro_vendedor = <?php echo $user_id;?>;
		<?php }  ?>
	
		$.getJSON('<?php echo $link."/repositories/teds/teds.ctrl.php?acao=listar_teds";?>&inicial='+exibidos,{
				filtro_id:filtro_id,
				filtro_dt_inclusao:filtro_dt_inclusao,
				filtro_per_ini:filtro_per_ini,
			    filtro_per_fim:filtro_per_fim,
				filtro_status:filtro_status,
				filtro_vendedor:filtro_vendedor,
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
						teds_aux = JSON.stringify(j[i]);
						novos += '<tr id="tr_'+j[i].id+'">';
						
						if(j[i].status_ted == 1){ 
							stt_ted  = 'Aguardando Envio p/ banco ';
							if(j[i].arquivos_id_remessa != null)
								stt_ted  += ' arquivo'+j[i].arquivos_id_remessa;
							if(j[i].pessoas_id_envio != null)
								stt_ted =  'Aguardando Retorno '; 
						
						}
						else if(j[i].status_ted == 2){ stt_ted  = 'Agendada';}
						else if(j[i].status_ted == 3){ stt_ted  = 'Confirmada';}
						else if(j[i].status_ted == 4){ stt_ted  = 'Corrompida';}
						
						//td #
						novos += '<td>';
						novos += j[i].id;
						novos += '</td>';
						
						novos += '<td>';
							novos += ConverteData(j[i].dt_ted);	
							
							novos += '<span class="visible-xs visible-sm">';
									novos += 'Data inclusão:<br>'+ConverteData(j[i].dt_inclusao)+'<br>';
									novos += 'R$ '+number_format(j[i].vl_ted,2,',','.');
									novos += '<br>'+stt_ted;
									novos += '<br>'+j[i].nome;
							novos += '</span>';	
						novos += '</td>';
						
						//td codigo produto
						novos += '<td  class="hidden-xs hidden-sm">';
						novos += ConverteData(j[i].dt_inclusao);
						novos += '</td>';
										
						novos += '<td class="hidden-xs hidden-sm">';
							novos += 'R$ '+number_format(j[i].vl_ted,2,',','.');
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
							novos += 'R$ '+number_format(j[i].tt_lancamentos,2,',','.');
						novos += '</td>';
						
						
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
							novos += stt_ted;
						novos += '</td>';
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
							novos += j[i].nome;
						novos += '</td>';				
						
						
						//td acao
						novos += "<td>";
						
						
						novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' onClick='alimenta_modal_cad_teds("+j[i].id+","+teds_aux+" )'; > <i class='fa fa-search fs-19' > </i></span> </a>";
						
						novos += "<i class='fa fa-file-pdf-o fs-21 pointer' aria-hidden='true'  onclick='gerar_pdf_lista("+j[i].id+")'  rel='tooltip' data-placement='bottom' data-html='true' data-original-title='Gerar PDF'></i>";
						
						<?php if( consultaPermissao($ck_mksist_permissao,"cad_parcelas","qualquer")){ ?>   
						novos += ' <a href="<?php echo $link;?>/parcelas/'+j[i].id+'" target="_blank"  title="Ver Parcelas"><i class="fa fa-bars fs-21"></i></a>';
						
						<?php } ?>  
						
						
						novos += "</td>";
						
					
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhuma TED cadastrada</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_teds').html(novos);
				}
				else{
					$('#listagem_teds').append(novos);
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
		 
function gerar_pdf_lista(id){ 
	$('#form_filtros_teds').attr('action', '<?php echo $link."/inc/pdf/gera_pdf_listas.php?pagina=teds";?>&id='+id); 
	$('#form_filtros_teds').attr('target', '_blank');
	$('#form_filtros_teds').submit();
	$('#form_filtros_teds').attr('action', 'javascript:filtrar_fields();');
	$('#form_filtros_teds').attr('target', '_top');
}  
	
	</script>
    
</body>
</html>
