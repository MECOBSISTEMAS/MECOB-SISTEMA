<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once $raiz."/valida_acesso.php";
$layout_title = "MECOB - Auditoria";
$menu_active="acessos";

if(!consultaPermissao($ck_mksist_permissao,"auditoria","qualquer")){ 
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
                            Auditoria</div>
                    </div>
                    <ol class="breadcrumb page-breadcrumb pull-right">
                        <li><i class="fa fa-home"></i>&nbsp;<a href="<?php echo $link;?>/dashboard">Home</a>&nbsp;&nbsp;<i
                            class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                        <li class="hidden"><a href="#">Acesso</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
                        <li class="active">Lista Acessos</li>
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
                            <div class="panel-heading">Lista acessos</div>
                            <div class="panel-body">
                            
                            <?php include($raiz."/adm/acesso_pessoas/filtros_acessos.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >	
                                <table  id="listagem_acessos"   class="table table-hover table-bordered " >
                                    <thead>
                                    <tr>
                                        <th id="th_nome" class="pointer" onclick="ordenar('nome');">Usuário</th>
                                        <th id="th_data" class="pointer hidden-xs hidden-sm" onclick="ordenar('data');" >Data</th>
                                        <th id="th_url" class="pointer hidden-xs hidden-sm" onclick="ordenar('url');">Url / Arquivo</th>
                                        <th >Dados acesso</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_acessos">
                                    <tr><td id="td_carregando" colspan="10">Carregando Acessos</td></tr>
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

    <script>
	var filtro_nome="";
	var filtro_data="";
	var filtrar = 0;
	
	var order ="nome";
	var ordem ="asc";
	
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip();
		 $("#filtro_data").mask("99/99/9999");	
		 $("#filtro_data").datepicker({dateFormat: 'dd/mm/yy'});
		 filtrar_fields();
		 carregar_totais();
		})

		function  alimenta_modal_dados_acesso(acesso){
			body_prepend = "";
			
			body_prepend += "<h3>Dados do usuário</h3><h5>";
			
			if(acesso.nome!=null) body_prepend += acesso.nome+'<br>';
			if(acesso.cpf_cnpj!=null) body_prepend += acesso.cpf_cnpj+'<br>';
			if(acesso.email!=null) body_prepend += acesso.email+'<br>';
			if(acesso.status_descricao!=null) body_prepend += acesso.status_descricao+'<br>';
			if(acesso.ip!=null) body_prepend += 'IP:'+acesso.ip+'<br>';
			
		    body_prepend += "</h5><h3>Dados do acesso</h3><h5>";
			
			if(acesso.data!=null) body_prepend += ConverteData(acesso.data)+'<br>';
			if(acesso.url!=null){  
				url = acesso.url;
				url_split = url.split('&');
				body_prepend += url_split[0]+'<br>'; 
			}
			if(acesso.caminho_arquivo!=null) body_prepend += acesso.caminho_arquivo+'<br>';
			
			body_prepend += "</h5><h3>Dados do trafegados</h3>";
			
			body_prepend += "Post: "+acesso.post+"<br>Get: "+acesso.texto_get+"<br>Cookie: "+acesso.cookie+"<br>Request: "+acesso.request;
			$('#md_geral_tt').html("Visualizar dados do acesso");
			$('#md_geral_bd').html(body_prepend);
			$('#md_geral').modal('show');
		}
		
	function limpa_filtros(){
		$('#filtro_nome').val('');
		$('#filtro_data').val('<?php echo date('d/m/Y'); ?>');
		
		filtrar=1;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_nome=$('#filtro_nome').val();
		filtro_data=$('#filtro_data').val();
		
		
		$('#tbody_acessos').html('<tr><td colspan="10">Carregando acessos</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
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
	
	function carregar_totais(){
		$('#linha_totais').html('');
		$.getJSON('<?php echo $link."/repositories/acesso_pessoa/acesso_pessoa.ctrl.php?acao=listar_totais";?>',{
					filtro_nome: filtro_nome,
					filtro_data:filtro_data,
					filtrar: filtrar,
					ajax: 'true'
			  }, function(j){		
				$('#linha_totais').html('Encontrados '+j+' acessos');
				
			   });
	}	
	
	function carregar_resultados(){
		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		$.getJSON('<?php echo $link."/repositories/acesso_pessoa/acesso_pessoa.ctrl.php?acao=listar_acessos";?>&inicial='+exibidos,{
				filtro_nome: filtro_nome,
				filtro_data:filtro_data,
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
						acessos_aux = JSON.stringify(j[i]);
						novos += '<tr id="tr_'+j[i].id+'">';
						
						url = j[i].url;
						if(url.substring(0, 26) == 'http://localhost/mesistema'){
							url = url.substring(26);
						}
						else if(url.substring(0, 27) == 'http://sistema.mecob.com.br'){
							url = url.substring(27);
						}
						
						url_split = url.split('&');
						url = url_split[0]; 
						
						tam_url = url.length;
						if(tam_url>80){
							url = url.substring(0, 50)+'... '+url.substring((tam_url-30), tam_url);
						}
						
						//td codigo produto
						novos += '<td>';
						novos += j[i].nome;						
						
							novos += '<span class="visible-xs visible-sm">';
								if(j[i].data!=null) novos += ConverteData(j[i].data)+'<br>';
								if(j[i].url!=null) novos += url+'<br>';
								if(j[i].caminho_arquivo!=null) novos += j[i].caminho_arquivo;
								
							novos += '</span>';					
						
						novos += '</td>';
						
						//td data 
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].data!=null) novos += ConverteData(j[i].data);
						novos += '</td>';
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
							if(j[i].url!=null) novos += url+'<br>';
							if(j[i].caminho_arquivo!=null) novos += j[i].caminho_arquivo;
						novos += '</td>';				
						
						
						//td acao
						novos += "<td>";
						novos += "<a> <span  class='pointer' data-toggle='tooltip' data-placement='left' title='Consultar dados'  onClick='alimenta_modal_dados_acesso("+acessos_aux+")'; > <i class='fa fa-search fs-19' > </i> </span> </a>";
						
						
						novos += "</td>";
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhum acesso recuperado</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_acessos').html(novos);
				}
				else{
					$('#listagem_acessos').append(novos);
				}
				document.getElementById("loading_resultados").style.display='none';
				document.getElementById("cont_exibidos").value = exibidos;
				document.getElementById("permite_carregar").value=libera_carregamento;
			   });
	}
                                          
	</script>
    
</body>
</html>
