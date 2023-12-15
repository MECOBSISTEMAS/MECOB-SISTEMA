<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$menu_active="cadastros";
$layout_title = "MECOB - Boletos Avulso";
$sub_menu_active="boletos_avulso";
$tit_pagina = "Boletos Avulso";
$tit_lista = " Lista de Boletos Avulso";

if(!consultaPermissao($ck_mksist_permissao,'cad_boletos',"qualquer")){
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
                        <li class="hidden"><a href="#">Boletos Avulso</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
							
							if(consultaPermissao($ck_mksist_permissao,"cad_boletos","adicionar")){ ?>
                            	<h3><button type="button" class="btn btn-brown" onClick="alimenta_modal_cad_boletos_avulso(0,'');">
                            Cadastrar Novo</button></h3>
                            <?php 
							}
							include($raiz."/adm/boletos_avulso/filtros_boletos_avulso.php");	?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                                <table id="listagem_boletos_avulso"  class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th class="hidden-xs hidden-sm">#</th>
                                        <th class=" hidden-xs hidden-sm"  >Boleto</th>
                                        <th class=" hidden-xs hidden-sm"  >Proprietário</th>
                                        <th id="th_dt_boleto" class="pointer hidden-xs hidden-sm" onclick="ordenar('dt_boleto');" >Data Boleto <i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i></th>
                                        <th id="th_dt_vencimento" class="pointer hidden-xs hidden-sm" onclick="ordenar('dt_vencimento');" >Data Vencimento</th>
                                        <th id="th_valor" class="pointer hidden-xs hidden-sm" onclick="ordenar('valor');" >Valor</th>
                                        <th id="th_dt_pagto" class="pointer hidden-xs hidden-sm" onclick="ordenar('dt_pagto');" >Data Pagamento</th>

                                        
                                        <th>Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_boletos_avulso">
                                    <tr><td id="td_carregando" colspan="10">Carregando Boletos</td></tr>
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
    
    <!-- modal cadastro de boletos avulso-->
<div class="modal fade" id="md_cadastro_boletos_avulso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_boletos_avulso_tt"></h4>
      </div>
      <div class="modal-body" id="md_cadastro_boletos_avulso_bd">
            <div class="panel panel-bordo">
                <div class="panel-heading">
                    Cadastro de <?php echo $tit_pagina; ?></div>
                <div class="panel-body pan">
                    <?php include($raiz."/adm/boletos_avulso/form_boletos_avulso.php");?>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="btn-save-boletos-avulso" type="button" class="btn btn-brown" onClick="$('#form_boletos_avulso').submit()">Salvar</button>
      </div>
    </div>
  </div>
</div>
    
    
    
    <!-- fim cadastro de pessoas-->
    <?php include $raiz."/js/corejs.php";?>
    <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.validate.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
    <script src="<?php echo $link;?>/js/jquery.maskMoney.js"/></script>

	

    <script>
	var filtro_boletos_avulso="";
	var filtro_proprietario=""; 
	var filtro_data ="";
	var filtro_data_fim ="";
	var filtro_status="";
	
	var filtrar = 0;
	
	var order ="nome";
	var ordem ="asc";
	
	var delay_busca;
	
	$('#inputDtBoleto').mask("99/99/9999");
    //$('#inputDtBoleto').datepicker({dateFormat: 'dd/mm/yy'});
	
	$("#filtro_data").mask("99/99/9999");	
	$("#filtro_data").datepicker({dateFormat: 'dd/mm/yy'});
	$("#filtro_data_fim").mask("99/99/9999");	
	$("#filtro_data_fim").datepicker({dateFormat: 'dd/mm/yy'});

    $('#inputDtBoletoVencimento').mask("99/99/9999");
    $('#inputDtBoletoVencimento').datepicker({dateFormat: 'dd/mm/yy',minDate: 1});
    $('#inputVlBoleto').maskMoney();
	  
		$(function () {
		  <?php 
		  		if(isset($ini_filtro) && $ini_filtro){
		  ?> 		filtrar_fields();
		  <?php }
				else{
		  ?>carregar_resultados();<?php }?>		  
		  carregar_totais();
		  $('[data-toggle="tooltip"]').tooltip();
		  
		});
		
		$("#form_boletos_avulso").validate({
			rules: {       
					findproprietario: {required: true},
					proprietario_id: {required: true},	
					dt_vencimento: {required: true},	
					vl_boleto: {required: true},
					descricao: {maxlength: 100}
					},					
                messages: {
                    findproprietario: "",  
					proprietario_id: "<span class='red'>* Selecione o cliente</span>",  
					dt_vencimento: "* Preencha a data de vencimento",  
					vl_boleto: "* Informe o valor do boleto",             
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
	
		function alimenta_modal_cad_boletos_avulso(id,boletos_avulso){
            if(id==0) {
                $("input#inputId").val('');
                $("input#inputProprietario").val('');
                $("input#inputDtBoletoVencimento").val('');
                $("input#inputVlBoleto").val('');
                $('#quadro_proprietario').addClass('hidden');
                $('#select_proprietario').removeClass('hidden');

                $("input#inputProprietario_id").val('');
                $("input#inputNomeProp").val('');
                $("input#inputDocProp").val('');

                $("input#inputDesc").val('');
				$('#inputIDcontrato').val('');
				$('#pIDcontrato').text('');

            }
            $('#md_cadastro_boletos_avulso_tt').html('Cadastro de novo Boleto Avulso');
			$('#md_cadastro_boletos_avulso').modal('show');
		}

		  function salvarFormulario(){
			    $('#btn-save-boletos-avulso').addClass('hidden');
			  	id= $("input#inputId").val(); 
				if(id.length ==0){
					acao = 'inserir';	
				}
				else{
					acao = 'atualizar';
				} 
				
					boletos_avulso = $('#form_boletos_avulso').serializeArray();
					//alert(JSON.stringify(boletos_avulso));
					$.getJSON("<?php echo $link."/repositories/boletos_avulso/boletos_avulso.ctrl.php?acao=";?>"+acao, {boletos_avulso: boletos_avulso }, function(result){
						if( result.status==1 ){	
							//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
							
							document.getElementById("cont_exibidos").value=0;
							carregar_resultados();
							carregar_totais();
							$('#md_cadastro_boletos_avulso').modal('hide');
							jAlert(result.msg,'Bom trabalho!','ok');
							
						}
						else{
							jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
						}
						$('#btn-save-boletos-avulso').removeClass('hidden');
					 }); 
		  }
		  
		  
<!--		  ROLAGEM INFINITA + FILTROS + ORDER -->

	function limpa_filtros(){
		$('#filtro_boletos_avulso').val('');
		$('#filtro_proprietario').val('');
		
		$('#filtro_data').val('');
		$('#filtro_data_fim').val(''); 
		$('#filtro_status').val('');  
	
		<?php if($ehCliente){   ?> 
			$('#filtro_proprietario').val(<?php echo $user_id;?>);
		<?php }  ?>

		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_boletos_avulso=$('#filtro_boletos_avulso').val();
		filtro_proprietario= $('#filtro_proprietario').val(); 
		
		filtro_data=$('#filtro_data').val();
		filtro_data_fim= $('#filtro_data_fim').val(); 
		filtro_status = $('#filtro_status').val(); 
		
		$('#tbody_boletos_avulso').html('<tr><td colspan="10">Carregando Boletos Avulso</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
		
	
	<?php if($ehCliente){   ?>
		filtrar = 1;
		filtro_proprietario = <?php echo $user_id;?>;
	<?php }  ?>
	
	$('#linha_totais').html('');
	$.getJSON('<?php echo $link."/repositories/boletos_avulso/boletos_avulso.ctrl.php?acao=listar_totais";?>',{
				filtro_boletos_avulso: filtro_boletos_avulso,
				filtro_proprietario:filtro_proprietario,
				filtro_data:filtro_data,
				filtro_data_fim:filtro_data_fim,
				filtro_status:filtro_status,
				filtrar: filtrar,
				ajax: 'true'
		  }, function(j){		
			$('#linha_totais').html('Encontrados '+j+' Boletos Avulso');
			
		   });


}	

function carregar_resultados(){
	
		
		<?php if($ehCliente){   ?>
			filtrar = 1;
			filtro_proprietario = <?php echo $user_id;?>;
		<?php }  ?>
		
		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		$.getJSON('<?php echo $link."/repositories/boletos_avulso/boletos_avulso.ctrl.php?acao=listar_boletos_avulso";?>&inicial='+exibidos,{
				filtro_boletos_avulso: filtro_boletos_avulso,
				filtro_proprietario:filtro_proprietario,
				filtro_data:filtro_data,
				filtro_data_fim:filtro_data_fim,
				filtro_status:filtro_status,
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
						boletos_avulso_aux = JSON.stringify(j[i]);
						novos += '<tr id="tr_'+j[i].id+'">';
						
						//td #
						novos += '<td class="hidden-xs hidden-sm">';
						novos += exibidos;
						novos += '</td>';

                        //td boleto
                        novos += '<td>';
                        novos += j[i].id + ' - ' + j[i].nosso_numero;
                        novos += '</td>';

						//td nome
						novos += '<td>';
						novos += j[i].nome + ' - ' + j[i].cpf_cnpj;
						novos += '</td>';

                        //td dt_boleto
                        novos += '<td>';
                        novos += ConverteData(j[i].dt_boleto);
                        novos += '</td>';

                        //td dt_vencimento
                        novos += '<td>';
                        novos += ConverteData(j[i].dt_vencimento);
                        novos += '</td>';

                        //td vl_boleto
                        novos += '<td>';
						if (j[i].vl_corrigido == null)
							novos += 'R$ '+ number_format(j[i].vl_pagto,2,',');
						else
                        	novos += 'R$ '+ number_format(j[i].vl_corrigido,2,',');
                        novos += '</td>';

                        //td dt_credito
                        novos += '<td>';
                        if (j[i].dt_credito != null){
                            novos += ConverteData(j[i].dt_credito);
                        }
                        else{
                            novos += '';
                        }
                        novos += '</td>';

						//td acao
						novos += "<td>";
						
						if(j[i].dt_pagto == null && j[i].dt_credito == null){
								
								if(maior_data(j[i].dt_vencimento , '<?php echo date('d/m/Y');?>')  == 2 && diffDays(j[i].dt_vencimento) > 59){
									novos += '<a href="https://unicred-florianopolis.cobexpress.com.br/default/segunda-via"  target="_blank"> <i class="fa fa-file-o" aria-hidden="true"></i> 2° via Boleto </a>';
								}
								else{
									novos += " <a href='<?php echo $link;?>/inc/boleto/gerar_boleto_avulso.php?boleto="+j[i].id+"'  target='_blank'>"+
										 "<i class='fa fa-print  fs-19 blue_light' ></i></span> "+
										 "</a>";
								}
						}
						
						<?php if(consultaPermissao($ck_mksist_permissao,"cad_boletos","editar")){ ?>
                        if (j[i].dt_credito == null) {
                            novos += "&nbsp;&nbsp; <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Apagar Boleto Avulso ' data-original-title='Apagar Boleto Avulso ' onClick='remover_boleto_avulso(" + j[i].id + " )'; > <i class='fa fa-trash-o  fs-19 red' ></i></span> </a>";
							novos += "&nbsp;&nbsp; <a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Gerar Remessa de cancelamento ' data-original-title='Gerar remessa de cancelamento ' onClick='cancelar_boleto_avulso(" + j[i].id + " )'; > <i class='fa fa-eraser  fs-19 red' ></i></span> </a>";
                        }
						<?php } ?>
						
						
						novos += "</td>";
						
					
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhum boleto avulso cadastrado</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_boletos_avulso').html(novos);
				}
				else{
					$('#listagem_boletos_avulso').append(novos);
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

    function remover_boleto_avulso(boleto){
        jConfirm('Tem certeza que deseja remover este boleto avulso?<br>Esta informação não poderá ser recuperada!', 'Excluir Boleto '+boleto+'?', function(r) {
            if(r){
                $.getJSON("<?php echo $link."/repositories/boletos_avulso/boletos_avulso.ctrl.php?acao=remove_boletos_avulso";?>", {boletos_avulso_id: boleto}, function(result){
                    if( result.status == 1 ){
                        jAlert(result.msg,'Bom trabalho!','ok');
                        document.getElementById("cont_exibidos").value=0;
                        carregar_resultados();
                    }
                    else{
                        jAlert(result.status+' | '+result.msg,'Alerta','alert');
                    }
                });


            }
        });

    }

    function cancelar_boleto_avulso(boleto){
        jConfirm('Tem certeza que deseja cancelar este boleto avulso?<br>Será gerado arquivo de remessa para envio ao banco!', 'Gerar Cancelamento '+boleto+'?', function(r) {
            if(r){
				$.getJSON("<?php echo $link."/inc/boleto/processadores/GARU/gerar_cancelar.php?acao=Z2VyYXJfYXJxdWl2bw==";?>", 
						{contrato_id: boleto,
						boleto_avulso: 'BOLETO_AVULSO'
						}, function(result){
                    if( result == 1 ){
                        jAlert(result.msg,'Bom trabalho!','ok');
                        document.getElementById("cont_exibidos").value=0;
                        carregar_resultados();
                    }
                    else{
                        jAlert(result + ' | '+result,'Alerta','alert');
                    }
                });
            }
        });
	}

	$( "#inputIDcontrato" ).focusout(function() {
		// alert('teste');
		var IDcontrato = parseInt($('#inputIDcontrato').val());
		if(typeof IDcontrato === 'number' && IDcontrato > 0) {

			$.getJSON('<?php echo $link . "/repositories/contratos/contratos.ctrl.php?acao=lista_contratos_boletos_avulsos"; ?>&inicial=' +
				exibidos, {
					filtro_id: IDcontrato,
					filtrar: 1,
					filtro_pagina: 'contratos',
					ajax: 'true'
				},
				function(j) {
					// alert(JSON.stringify(j));
					if( j != '' ) {
						$('#pIDcontrato').css('color', 'black');
						// $('#pIDcontrato').text(j[0].id + ' -> '+ j[0].descricao);
						$('#pIDcontrato').text(j[0].descricao);
					} else {
						$('#pIDcontrato').css('color', 'red');
						$('#pIDcontrato').text('	Não há contrato com o ID '+ IDcontrato +' no sistema!!!');
					}
			});
		} 
	})


	</script>
    
</body>
</html>
