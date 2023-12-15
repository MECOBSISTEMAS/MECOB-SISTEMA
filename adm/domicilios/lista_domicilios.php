<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active = "domicilios"; 
$layout_title = "MECOB - Domicílios bancários";
$tit_pagina = "Domicílios bancários";	
$tit_lista = " Lista de Domicílios bancários";	

if(!consultaPermissao($ck_mksist_permissao,"cad_domicilios","qualquer")){ 
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
							include($raiz."/adm/domicilios/filtros_domicilios.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                            <table id="listagem_domicilios"  class="table table-hover table-bordered" >
                                <thead>
                                <tr>
                                    <th id="th_vendedor" class="pointer " onclick="ordenar('vendedor');" >
                                    Vendedor <i class="fa fa-arrow-circle-down fl-rg ico_ordem" ></i>
                                    </th>
                                    
                                    <th id="th_banco" class="pointer " onclick="ordenar('banco');" >
                                    Banco
                                    </th>
                                    
                                    <th id="th_agencia" class="pointer hidden-xs hidden-sm" onclick="ordenar('agencia');" >
                                    Agência  
                                    </th>
                                     <th  id="th_conta" class="pointer hidden-xs hidden-sm" onclick="ordenar('conta');" >
                                     Conta
                                     </th>
                                </tr>
                                </thead>
                                <tbody id="tbody_domicilios">
                                <tr><td id="td_carregando" colspan="10">Carregando Domicílios</td></tr>
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
	

	var filtro_vendedor="";
	var filtrar = 0;
	
	var order ="vendedor";
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
	});
		
	


	function limpa_filtros(){
		$('#filtro_vendedor').val('');
		
		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_vendedor=$('#filtro_vendedor').val();
		
		$('#tbody_teds').html('<tr><td colspan="10">Carregando Domicílios</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
	
	$('#linha_totais').html('');
	$.getJSON('<?php echo $link."/repositories/domicilios/domicilios.ctrl.php?acao=listar_totais";?>',{
				filtro_vendedor:filtro_vendedor,
				filtrar: filtrar,
				ajax: 'true'
		  }, function(j){	
		  	//alert(JSON.stringify(j));	  
		  	linha_total = 'Encontrados '+j[0].total_domc+' domicílios bancários';
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
		$.getJSON('<?php echo $link."/repositories/domicilios/domicilios.ctrl.php?acao=listar_domicilios";?>&inicial='+exibidos,{
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
						
						 
						novos += '<td class="hidden-xs hidden-sm">';
							novos += j[i].vendedor_nome;
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
							novos += j[i].banco;
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
							novos += j[i].agencia;
							if(j[i].dv_agencia != null )
								novos += '-'+j[i].dv_agencia;
						novos += '</td>';
						
						novos += '<td class="hidden-xs hidden-sm">';
							novos += j[i].conta;
							if(j[i].dv_conta != null )
								novos += '-'+j[i].dv_conta;
						novos += '</td>';
						
						
					
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhum Domicílio encontrado</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_domicilios').html(novos);
				}
				else{
					$('#listagem_domicilios').append(novos);
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
		 
function gerar_planilha_lista(){
	direct = '<?php echo $link."/adm/domicilios/gera_planilha_domicilios.php";?>?order='+order+'&ordem='+ordem; 
	$('#form_filtros_domicilios').attr('action', direct); 
	$('#form_filtros_domicilios').attr('target', '_blank');
	$('#form_filtros_domicilios').submit();
	$('#form_filtros_domicilios').attr('action', 'javascript:filtrar_fields();');
	$('#form_filtros_domicilios').attr('target', '_top');
}  
	
	</script>
    
</body>
</html>
