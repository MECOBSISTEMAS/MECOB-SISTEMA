<?php 

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

// if (!$_SESSION['perfil_id'] == 'NULL') {
// 	echo "Sistema de parcelas em manutenção, previsão máxima de retorno às 17h<br> <a href='javascript:history.back()'>Voltar</a>";
// 	exit;
// }

$menu_active = "cadastros"; 
$layout_title = "MECOB - Parcelas";
$sub_menu_active="parcelas";	
$tit_pagina = "Parcelas";	
$tit_lista = " Lista de Parcelas";	

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
                        <li class="hidden"><a href="#">parcelas</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
							include($raiz."/adm/parcelas/filtros_parcelas.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                            <table id="listagem_parcelas"  class="table table-hover table-bordered" >
                                <thead>
                                <tr>
                                    <th id="th_contrato" class="pointer " onclick="ordenar('contrato');" >
                                    Contrato <i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i>
                                    </th>
                                    <th   class="   hidden-xs hidden-sm"   >
                                    Vendedor/Comprador
                                    </th>
                                    
                                    <th id="th_parcela" class="pointer  hidden-xs hidden-sm" onclick="ordenar('parcela');" >
                                    Parcela
                                    </th>
                                    
                                    <th id="th_vencimento" class="pointer hidden-xs hidden-sm" onclick="ordenar('vencimento');" >
                                    Vencimento
                                    </th>
                                    
									<th  id="th_pagamento" class="pointer hidden-xs hidden-sm" onclick="ordenar('pagamento');" >
									Pagamento
									</th>
									<th  id="th_credito" class="pointer hidden-xs hidden-sm" onclick="ordenar('credito');" >
									Crédito
									</th>
									<th id="th_valor" class="pointer hidden-xs hidden-sm" onclick="ordenar('valor');" >
									Valor Parcela
									</th>
									<th id="th_vlpago" class="pointer hidden-xs hidden-sm" onclick="ordenar('vlpago');" >
									Valor Pago
									</th>
									<?php if(!$ehCliente){   ?>
										<th id="th_honor" class=" hidden-xs hidden-sm"  >
										Honorários
										</th>
									<?php } ?>

                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody id="tbody_parcelas">
                                <tr><td id="td_carregando" colspan="15">Carregando Parcelas</td></tr>
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
    
    <!-- fim cadastro de pessoas-->
    <?php include $raiz."/js/corejs.php";?>
    <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.validate.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
    <script>
	
	var filtro_contrato_id="";
	var filtro_vendedor="";
	var filtro_comprador="";
	var filtro_per_ini="";
	var filtro_per_fim="";
	var filtro_status="";
	var filtro_tpcontrato="";
	var filtro_ted_id="";
	var filtro_status_ct="";
	var filtro_dia="";
	var filtro_descricao="";
	
	var total_results =0;
	
	
	var filtrar = 0;
	
	var order ="agendada";
	var ordem ="asc";
	
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
	});
		

	function limpa_filtros(){
		$('#filtro_contrato_id').val('');
		$('#filtro_tpcontrato').val('');
		$('#filtro_per_ini').val('');
		$('#filtro_per_fim').val('');
		$('#filtro_status').val('');
		$('#filtro_ted_id').val('');
		$('#filtro_status_ct').val('');
		$('#filtro_descricao').val('');		
		
		$('#filtro_vendedor').val('');
		$('#filtro_comprador').val(''); 
		
		<?php if($ehCliente){   ?> 
			$('#filtro_vendedor').val(<?php echo $user_id;?>);
		<?php }  ?>
		
		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_contrato_id=$('#filtro_contrato_id').val();
		filtro_tpcontrato=$('#filtro_tpcontrato').val();
		filtro_status=$('#filtro_status').val();
		filtro_ted_id=$('#filtro_ted_id').val();
		filtro_per_ini=$('#filtro_per_ini').val();
		filtro_per_fim=$('#filtro_per_fim').val();
		filtro_status_ct =$('#filtro_status_ct').val();
		filtro_vendedor=$('#filtro_vendedor').val();
		filtro_comprador=$('#filtro_comprador').val(); 
		filtro_dia=$('#filtro_dia').val(); 
		filtro_descricao=$('#filtro_descricao').val();
		
		$('#tbody_teds').html('<tr><td colspan="10">Carregando Parcelas</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
	tipo_operacao=$('#tipo_operacao').children('option:selected').val(); 	
	<?php if($ehCliente){   ?>
		filtrar = 1;
		if (tipo_operacao == 'venda') {
			filtro_vendedor = <?php echo $user_id;?>;
			filtro_comprador = null;
		} else {
			filtro_vendedor = null;
			filtro_comprador = <?php echo $user_id;?>;
		}
		<?php }  ?>
	
	$('#linha_totais').html('');
	$.getJSON('<?php echo $link."/repositories/parcelas/parcelas.ctrl.php?acao=listar_totais";?>',{
				filtro_contrato_id:filtro_contrato_id,
				filtro_tpcontrato:filtro_tpcontrato,
				filtro_status:filtro_status,
				filtro_ted_id:filtro_ted_id,
				filtro_per_ini:filtro_per_ini,
			    filtro_per_fim:filtro_per_fim,
				filtro_vendedor:filtro_vendedor,
			    filtro_comprador:filtro_comprador, 
				filtro_status_ct:filtro_status_ct,
				filtro_dia:filtro_dia,
				filtro_descricao:filtro_descricao,

				filtrar:filtrar,
				ajax: 'true'
		  }, function(j){
			//alert(JSON.stringify(j));
			totais = j.totais[0];
		  	linha_total = 'Encontradas '+totais.total_parcelas+' parcelas';
			linha_total += '<br>Valor Parcelas: R$ '+number_format(totais.vl_parcela,2,',','.');
			
			<?php if(!$ehCliente){   ?>
			
			//linha_total += '<br>Valor Juros/Correção: R$ '+number_format(totais.vl_juros,2,',','.');
			linha_total += '<br>Valor Pagto: R$ '+number_format(totais.vl_pagto,2,',','.');
			linha_total += '<br>Valor Honorários: R$ '+number_format(totais.vl_honorarios,2,',','.');
			<?php }  ?>
			
			$('#linha_totais').html(linha_total);
			total_results = totais.total_parcelas;
		   });
}	

	function carregar_resultados(){
		tipo_operacao=$('#tipo_operacao').children('option:selected').val(); 	

		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		
		<?php if($ehCliente){   ?>
		filtrar = 1;
		if (tipo_operacao == 'venda') {
			filtro_vendedor = <?php echo $user_id;?>;
			filtro_comprador = null;
		} else {
			filtro_vendedor = null;
			filtro_comprador = <?php echo $user_id;?>;
		}
		<?php }  ?>
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		$.getJSON('<?php echo $link."/repositories/parcelas/parcelas.ctrl.php?acao=listar_parcelas";?>&inicial='+exibidos,{
				filtro_contrato_id:filtro_contrato_id,
				filtro_tpcontrato:filtro_tpcontrato,
				filtro_status:filtro_status,
				filtro_ted_id:filtro_ted_id,
				filtro_per_ini:filtro_per_ini,
			    filtro_per_fim:filtro_per_fim,
				filtro_vendedor:filtro_vendedor,
			    filtro_comprador:filtro_comprador, 
				filtro_status_ct:filtro_status_ct,
				filtro_dia:filtro_dia,
				filtro_descricao:filtro_descricao,
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
						
						//td #
						novos += '<td>';
						novos += j[i].ct_id+' - '+j[i].ct_descricao;
						if (j[i].repasse == 'S'){
							novos += '<br>Status: Repasse ('+j[i].ct_status+')';  
						} else {
							novos += '<br>Status: '+j[i].ct_status;  
						}
						novos += '<div class="visible-xs visible-sm">';
						novos += 'Parcela '+j[i].nu_parcela;
						novos += '<br>Venc. '+ConverteData(j[i].dt_vencimento);
						if(j[i].dt_credito!= null && j[i].dt_credito!= '0000-00-00')
							novos += '<br>Pagto '+ConverteData(j[i].dt_credito);
						else if(j[i].dt_pagto!= null && j[i].dt_pagto!= '0000-00-00')
							novos += '<br>Pagto '+ConverteData(j[i].dt_pagto);
							
						novos += '<br> Valor Parcela<br>R$ '+number_format(j[i].corrigido,2,',','.');
						novos += '<br> Valor Pago<br>R$ '+number_format(j[i].vl_pagto,2,',','.');
						<?php if(!$ehCliente){   ?>
							novos += '<br> Honorários<br>R$ '+number_format(j[i].vl_honorarios,2,',','.');
						<?php } ?>
						novos += '</div>';
						
						
						novos += '</td>';
						
						
						
						novos += '<td class="hidden-xs hidden-sm">';
						novos += j[i].nome;
						novos += '<br>'+j[i].comprador_nome; 
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
						novos += j[i].nu_parcela;
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
						novos += ConverteData(j[i].dt_vencimento);
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
							 if(j[i].dt_pagto!= null && j[i].dt_pagto!= '0000-00-00')
								novos += ConverteData(j[i].dt_pagto);
							
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].dt_credito!= null && j[i].dt_credito!= '0000-00-00')
								novos += ConverteData(j[i].dt_credito); 
							
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
						novos += 'R$ '+number_format(j[i].corrigido,2,',','.');
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
						novos += 'R$ '+number_format(j[i].vl_pagto,2,',','.');
						novos += '</td>';
						
										
						<?php if(!$ehCliente){   ?>
							novos += '<td class="hidden-xs hidden-sm">';
							novos += 'R$ '+number_format(j[i].vl_honorarios,2,',','.');
							novos += '</td>';
						<?php } ?>

						//td acao
						novos += "<td>";
						
						
						
						<?php if(consultaPermissao($ck_mksist_permissao,"cad_contratos","qualquer")){ ?>  
							novos += ' <a href="<?php echo $link;?>/contratos/'+j[i].ct_id+'" target="_blank"  title="Ver Contrato"><i class="fa fa-file fs-21"></i></a>';
						<?php } ?>  
						if(j[i].teds_id != null){
								novos += ' <a href="<?php echo $link;?>/teds/'+j[i].teds_id+'" target="_blank"  title="Ver TED"><i class="fa fa-usd fs-21"></i></a>';
							}
						
						novos += "</td>";
						
					
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhuma parcela</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_parcelas').html(novos);
				}
				else{
					$('#listagem_parcelas').append(novos);
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
		limite = 5000;
		if(total_results > limite && <?php echo $user_id;?> != '_' ){
			jAlert('Você está tentando gerar um pdf de '+total_results+' registros.<br>Para não prejudicar seu processo limite a sua consulta a até '+limite+' registros.','Oops');	
		}
		else{ 
			$('#form_filtros_parcelas').attr('action', '<?php echo $link."/inc/pdf/gera_pdf_listas.php?pagina=parcelas";?>&order='+order+'&ordem='+ordem); 
			$('#form_filtros_parcelas').attr('target', '_blank');
			$('#form_filtros_parcelas').submit();
			$('#form_filtros_parcelas').attr('action', 'javascript:filtrar_fields();');
			$('#form_filtros_parcelas').attr('target', '_top');
		}
	}   

	function gerar_planilha_parcelas() {
		// var total_results = 1;
		if (total_results > 5000) {
			jAlert('Você está tentando gerar uma planilha com ' + total_results +
				' registros.<br>Para não prejudicar seu processo limite a sua consulta a até 5000 registros.', 'Oops');
		} else {
			direct = '<?php echo $link . "/adm/parcelas/gera_planilha_parcelas.php"; ?>?order=' + order + '&ordem=' +
				ordem;
			$('#form_filtros_parcelas').attr('action', direct);
			$('#form_filtros_parcelas').attr('target', '_blank');
			$('#form_filtros_parcelas').submit();
			$('#form_filtros_parcelas').attr('action', 'javascript:filtrar_fields();');
			$('#form_filtros_parcelas').attr('target', '_top');
		}
	}

	</script>
    
</body>
</html>
