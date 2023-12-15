<?php 
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";

$tipo_url=explode("/", $_SERVER["REDIRECT_URL"]);
$tipo_pessoa = end($tipo_url);


$layout_title = "MECOB - Pessoas";

$menu_active="cadastros";
$cod_modulo = "cad_pessoas";
$lista_usuarios=0;
switch ($tipo_pessoa) {
	
	case 'leiloeiros':	
		$layout_title = "MECOB  - Leiloeiros";
		$sub_menu_active="leiloeiros";	
		$tit_pagina = "Leiloeiros";	
		$tit_lista = " Lista de Leiloeiros";		
		break;	
	
	case 'compradores':		
		$layout_title = "MECOB - Compradores";
		$sub_menu_active="compradores";	
		$tit_pagina = "Compradores";	
		$tit_lista = " Lista de Compradores";					
		break;	
	
	case 'vendedores':		
		$layout_title = "MECOB - Vendedores";
		$sub_menu_active="vendedores";	
		$tit_pagina = "Vendedores";	
		$tit_lista = " Lista de Vendedores";					
		break;	
	
	case 'usuarios':	
		$cod_modulo = "cad_usuarios";	
		$layout_title = "MECOB - Usuários";
		$sub_menu_active="usuarios";	
		$tit_pagina = "Usuários";	
		$tit_lista = " Lista de Usuários";	
		$lista_usuarios=1;				
		break;	
	
}
if(!consultaPermissao($ck_mksist_permissao,$cod_modulo,"qualquer")){ 
	header("Location: ".$link."/401");
	exit;
}
	
$addcss= '<link rel="stylesheet" href="'.$link.'/css/smoothjquery/smoothness-jquery-ui.css">';

include($raiz."/partial/html_ini.php");

//$pessoas = file_get_contents($link."/repositories/pessoas/pessoas.ctrl.php?acao=listar&tipo_pessoa=".$tipo_pessoa."", false, HeaderToFileGetContent($username,$senha));
//$pessoas = json_decode($pessoas,true);
//#print_r($pessoas);


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
                        <li class="hidden"><a href="#">Pessoas</a>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;</li>
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
							
							if(consultaPermissao($ck_mksist_permissao,$cod_modulo,"adicionar")){ ?>  
                            <h3><button type="button" class="btn btn-brown" onClick="alimenta_modal_cad_pessoa(0,'<?php echo $tipo_pessoa?>');"> Cadastrar Novo</button></h3>
                            <?php 
							} 
							include($raiz."/adm/pessoas/filtros_pessoas.php");	
							?>
                            <div id="linha_totais"></div><br />
                            <div  id="listagem" >
                                <table id="listagem_pessoas"  class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th class="hidden-xs hidden-sm">#</th>
                                        <th id="th_pessoa" class="pointer" onclick="ordenar('pessoa');">Usuário <i class="fa fa-arrow-circle-up fl-rg ico_ordem" ></i></th>
                                        <th  class="visible-lg ">Telefone</th>
                                        <th id="th_documento" class="pointer hidden-xs hidden-sm" onclick="ordenar('documento');" >Documento</th>
                                        <th id="th_mail" class="pointer hidden-xs hidden-sm" onclick="ordenar('mail');" >e-mail</th>
                                        <?php if($lista_usuarios){ ?>
                                        	<th id="th_perfil" class="pointer hidden-xs hidden-sm " onclick="ordenar('perfil');">Perfil</th>
                                        <?php } ?>
                                        <th id="th_status" class="pointer hidden-xs hidden-sm " onclick="ordenar('status');">Status</th>
                                        
                                        
                                        <th>Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_pessoas">
                                    <tr><td id="td_carregando" colspan="10">Carregando Pessoas</td></tr>
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
    
    <!-- modal cadastro de pessoas-->
<div class="modal fade" id="md_cadastro_pessoas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_cadastro_pessoas_tt"></h4>
      </div>
      <div class="modal-body" id="md_cadastro_pessoas_bd">
            <div class="panel panel-bordo">
                <div class="panel-heading">
                    Cadastro de <?php echo $tit_pagina; ?></div>
                <div class="panel-body pan">
                    <?php include($raiz."/adm/pessoas/form_pessoas.php");?>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="btn-save-pessoa" type="button" class="btn btn-brown" onClick="$('#form_pessoas').submit()">Salvar</button>
      </div>
    </div>
  </div>
</div>

<!-- modal lista haras--> 
<div class="modal fade" id="md_haras" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_haras_tt"></h4>
      </div>
      <div class="modal-body" id="md_haras_bd">
            <div class="panel panel-grey">
                <div class="panel-heading">
                    Lista Haras</div>
                <div class="panel-body pan">
                    <div class="form-body pal">  
                        <table  id="listagem_haras"  class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th >Haras</th> 
                                <th class="hidden-xs">Telefone</th>                                     
                                <th class="hidden-xs">Contato</th>                             
                                <th>Desfazer Relação</th>
                            </tr>
                            </thead>
                             <tbody id="tbody_listagem_haras">
                            <tr><td id="td_carregando_haras" colspan="10">Carregando Haras</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        
      </div>

    </div>
  </div>
</div> 
    
    
<!-- modal lista alertas--> 
<div class="modal fade" id="md_alertas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="md_alertas_tt">Lista Alertas</h4>
      </div>
      <div class="modal-body" id="md_alertas_bd">
            <div class="panel panel-grey">
                <div class="panel-body pan">
                    <div class="form-body pal">  
                        <form id="form_alerta" action="javascript:salvarAlerta()">
                    	<input  type="hidden" name="pessoas_id_cadastro" value="<?php echo $_SESSION['id'];?>" />
                        <input  type="hidden" id="pessoas_id_destino" name="pessoas_id_destino"  />
                        <div class="form-body">                            
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="form-group input-icon right">
                                            <div class="placeholder">Alerta:</div>
                                            	<input id="inputAlertaDesc"  name="descricao"  type="text" 
                                                placeholder="Descrição" class="form-control  with-placeholder" required="required" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="form-group input-icon right">
                                            <div class="placeholder">Link:</div>
                                            	<input id="inputAlertaLink"  name="link"  type="text" placeholder="Link" class="form-control  with-placeholder"  />
                                            </div>
                                        </div>
									</div>
									<div class="row">
									<div class="col-md-4">
                                        <div class="form-group input-icon right">
                                            <div class="placeholder">Prazo Máximo:</div>
                                            	<input id="dt_prazo"  name="dt_prazo"  type="data" placeholder="Data" class="form-control  with-placeholder"  />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="form-group ">
                                            	<button type="submit" class="btn btn-primary">Salvar Alerta</button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </form>
                        <hr />
                        <table  id="listagem_alertas"  class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th >#</th> 
                                <th class="hidden-xs">Data</th>                                     
                                <th class="hidden-xs">Descrição</th>                             
                                <th class="hidden-xs">Link</th>                                     
                                <th class="hidden-xs">Visualizado</th> 
                                <th class="hidden-xs">Concluído</th> 
                                <th class="hidden-xs">Prazo</th>                                     
                            </tr>
                            </thead>
                             <tbody id="tbody_listagem_alertas">
                            <tr><td id="td_carregando_alertas" colspan="10">Carregando Alertas</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        
      </div>

    </div>
  </div>
</div> 
    
    
    <?php include $raiz."/js/corejs.php";?>
    <script src="<?php echo $link;?>/js/jquery.maskedinput-1.1.4.pack.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.validate.js"/></script>
    <script src="<?php echo $link;?>/js/jquery.inputmask.bundle.js"></script>
    <script src="<?php echo $link;?>/js/jquery.maskMoney.js"/></script>
	

    <script>
	
	function controla_formSelectPerfil(acao){
		if(acao == 'show'){
			$('#formSelectPerfil').removeClass('hidden');
		}
		else{
			$('#formSelectPerfil').addClass('hidden');
			$('#SelectPerfil').val('');
		}
	}
	
	function checkboxUserSelectPerfil(){
		if($("#inputEhUsuario").is(':checked')){
			$('#formSelectPerfil').removeClass('hidden');
		}
		else{
			$('#formSelectPerfil').addClass('hidden');
		}
	}
	
	var filtro_pessoa="";
	var filtro_status="";
	var filtro_perfil="";
	var filtrar = 0;
	
	var order ="pessoa";
	var ordem ="asc";
	var delay_busca;
	
	
	$("#inputTelefone").inputmask({
        mask: ["(99) 9999-9999", "(99) 99999-9999", ]
      });
	$("#inputCelular").inputmask({
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
			$( "#inputDtNasc" ).datepicker({dateFormat: 'dd/mm/yy'});
			$("#inputCep").mask("99999-999");	
			$('#inputHonorAdimp').maskMoney({allowZero:true});
			$('#inputHonorInadimp').maskMoney({allowZero:true});	
		  	$('#a_animate_sidebar_pessoas').click();	

		  	$("#dt_prazo").mask("99/99/9999");
			$("#dt_prazo").datepicker({
				dateFormat: 'dd/mm/yy'
			});
		  
		});
		
		$("#form_pessoas").validate({
			rules: {       
					nome: {required: true},	
					apelido: {required: true},
					nacionalidade: {required: true},	
					email: {
						email: true
					},
					password: {minlength: 6},
					password_confirma: {
						equalTo: "#inputSenha"},									
					},					
                messages: {
                    nome: "* Preencha o nome",
					nacionalidade: "* Preencha a nacionalidade",
					email:{
						email:  "* Email inválido"
					},
					password: "* Ao menos 6 caracteres",
					password_confirma:"* Senha diferente do campo senha",                 
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
	
		function alimenta_modal_cad_pessoa(id,pessoa){
			limpa_haras();
			if(id==0){
				$('#md_cadastro_pessoas_tt').html('Cadastro de novo <?php echo $tit_pagina; ?>');
				$("input#inputId").val(''); 
				$("input#inputNome").val(''); 
				$("input#inputApelido").val('');
				$("input#inputDtNasc").val('');
				$("input#inputCpfCnpj").val( '' );
				$("input#inputRg").val( '' );
				$("input#inputEmail").val( '' );
				$("input#inputTelefone").val('');
				$("input#inputSite").val('');
				$("select#selectStatus").val('');
				$("select#selectPerfil").val('');
				$("#adicionalInfo").val('');
				$("input#inputNacionalidade").val('Brasileiro');
				
				
				$("#inputContato").val('');
				$("#inputCelular").val('');
				
				
				$("input#inputEhComprador").attr('checked',false);
				if (pessoa == "compradores"){
					$("input#inputEhComprador").attr('checked',true);
				}
				$("input#inputEhVendedor").attr('checked',false);
				if (pessoa == "vendedores"){
					$("input#inputEhVendedor").attr('checked',true);
				}
				$("input#inputEhLeiloeiro").attr('checked',false);
				if (pessoa == "leiloeiros"){
					$("input#inputEhLeiloeiro").attr('checked',true);
				}
				
				controla_formSelectPerfil('hide');
				$("input#inputEhUsuario").attr('checked',false);
				if (pessoa == "usuarios"){
					controla_formSelectPerfil('show');
					$("input#inputEhUsuario").attr('checked',true);
				}
				
				$("#inputSupervisor").attr('checked',false);
				$("#inputOperador").attr('checked',false);
				
				$("#row_status").hide();
				$("input#inputCep").val('');
				$("#inputEstado").val('' );
				$("input#inputCidade").val( '' );
				$("input#inputBairro").val( '' );
				$("input#inputRua").val( '' );
				$("input#inputNumero").val('');
				limpa_endereco();
				
				$("input#inputHonorAdimp").val( '' );
				$("input#inputHonorInadimp").val('');
								
			}
			else{
				$('#md_cadastro_pessoas_tt').html('Editar Cadastro de '+pessoa.nome);
				limpa_endereco();
				$("input#inputId").val(id); 
				$("input#inputNome").val(pessoa.nome);
				$("input#inputApelido").val(pessoa.apelido);
				$("input#inputDtNasc").val(ConverteData(pessoa.dt_nascimento));
				$("input#inputCpfCnpj").val(pessoa.cpf_cnpj);
				$("input#inputRg").val(pessoa.rg);
				$("input#inputTelefone").val(pessoa.telefone);
				$("input#inputSite").val(pessoa.site);
				$("input#inputEmail").val(pessoa.email);
				$("select#selectStatus").val(pessoa.status_id);
				$("input#inputNacionalidade").val(pessoa.nacionalidade);
				$("#adicionalInfo").val(pessoa.complemento);
				
				$("#inputContato").val(pessoa.contato);
				$("#inputCelular").val(pessoa.celular);
				$("#inputLimiteDias").val(pessoa.pedidos_abertos);
				
				$("input#inputEhComprador").attr('checked',false);
				if (pessoa.eh_comprador == "S"){
					$("input#inputEhComprador").attr('checked',true);
				}
				$("input#inputEhVendedor").attr('checked',false);
				if (pessoa.eh_vendedor == "S"){
					$("input#inputEhVendedor").attr('checked',true);
				}
				$("input#inputEhLeiloeiro").attr('checked',false);
				if (pessoa.eh_leiloeiro == "S"){
					$("input#inputEhLeiloeiro").attr('checked',true);
				}
				
				controla_formSelectPerfil('hide');
				$("input#inputEhUsuario").attr('checked',false);
				if (pessoa.eh_user == "S"){
					controla_formSelectPerfil('show');
					$("input#inputEhUsuario").attr('checked',true);
					if(pessoa.perfil_id != null){
						$("#SelectPerfil").val(pessoa.perfil_id);
					}
					else{
						$("#SelectPerfil").val(2);
					}
				}

				if (pessoa.supervisor == 'S') {
					$("#inputSupervisor").attr('checked',true);	
				} else {
					$("#inputSupervisor").attr('checked',false);
				}

				if (pessoa.operador == 'S') {
					$("#inputOperador").attr('checked',true);	
				} else {
					$("#inputOperador").attr('checked',false);
				}
				
				$("#row_status").show();
				$("input#inputCep").val(pessoa.cep);
				$("#inputEstado").val(pessoa.estado);
				$("input#inputCidade").val(pessoa.cidade);
				$("input#inputBairro").val(pessoa.bairro);
				$("input#inputRua").val(pessoa.rua);
				$("input#inputNumero").val(pessoa.numero);
				buscaCep();
				
				$("input#inputHonorAdimp").val(pessoa.honor_adimp);
				$("input#inputHonorInadimp").val(pessoa.honor_inadimp);
				
//				if($.isNumeric(pessoa.haras_id)){
//					haras = {	   'id':pessoa.haras_id,
//									'nome':pessoa.haras_nome,
//									'telefone':pessoa.haras_telefone
//								 };
//					escolhe_autocomplete_haras(haras);
//				}
			
				
			}
			$('#md_cadastro_pessoas').modal('show');
			ver_arquivos();
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
						$("input#inputRua").val( rua[0].replace("'",' ') );
						$("#inputEstado").val( result.state );
						$("input#inputCidade").val( result.city.replace("'",' ') );
						$("input#inputBairro").val( result.district.replace("'",' ') );
						
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
				$("input#inputCidade").attr('readonly', false);
				$("input#inputBairro").attr('readonly', false);
				$("input#inputRua").attr('readonly', false); 
				$("#inputEstado").attr('readonly', false); 
		  }
		  
		 
		  function salvarFormulario(validou){
				
				$('#btn-save-pessoa').addClass('hidden');
				if(validou ==0){
					verifica_email_doc_existente('doc');
				}
				else if(validou ==1){
					verifica_email_doc_existente('email');
				}
				else if(validou ==2){
					$('#btn-save-pessoa').addClass('hidden');
					id= $("input#inputId").val(); 
					if(id.length ==0){
						acao = 'inserir';	
					}
					else{
						acao = 'atualizar';
					}
					pessoa = $('#form_pessoas').serializeArray();
					//alert(JSON.stringify(pessoa));
					$.getJSON("<?php echo $link."/repositories/pessoas/pessoas.ctrl.php?acao=";?>"+acao, {pessoa: pessoa }, function(result){
						if( result.status==1 ){	
							//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
							
							document.getElementById("cont_exibidos").value=0;
							carregar_resultados();
							carregar_totais();
							$('#md_cadastro_pessoas').modal('hide');
							jAlert(result.msg,'Bom trabalho!','ok');
							
						}
						else{
							jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
						}
						$('#btn-save-pessoa').removeClass('hidden');
					 });
				}
				else{
					jAlert('Não foi possível validar o formulário!','Oops','alert');
				}
				$('#btn-save-pessoa').removeClass('hidden');
				
		  }
		  
		  
<!--		  ROLAGEM INFINITA + FILTROS + ORDER -->

	function limpa_filtros(){
		$('#filtro_pessoa').val('');
		$('#filtro_status').val('');
		$('#filtro_perfil').val('');
		
		filtrar=0;
		filtrar_fields();
	}
	
	function filtrar_fields(){
		filtro_pessoa=$('#filtro_pessoa').val();
		filtro_status=$('#filtro_status').val();
		<?php if($lista_usuarios){ ?>
			filtro_perfil=$('#filtro_perfil').val();
		<?php } ?>
		
		
		$('#tbody_pessoas').html('<tr><td colspan="10">Carregando Pessoas</td></tr>');
		
		$('#cont_exibidos').val('0');
		$('#permite_carregar').val('1');
		filtrar=1;
		
		carregar_totais();
		carregar_resultados();
	}
	
function carregar_totais(){
		

	$('#linha_totais').html('');
	$.getJSON('<?php echo $link."/repositories/pessoas/pessoas.ctrl.php?acao=listar_totais&tipo_pessoa=".$tipo_pessoa."";?>',{
				filtro_pessoa: filtro_pessoa,
				filtro_status:filtro_status,
				filtro_perfil:filtro_perfil,
				filtrar: filtrar,
				ajax: 'true'
		  }, function(j){		
			$('#linha_totais').html('Encontrados '+j[0].qt_pessoas+' <?php echo $tipo_pessoa?>');
			
		   });


}	

function carregar_resultados(){
		//quantos já foram exibidos e descartar ids exibidos na cidade principal
		exibidos = document.getElementById("cont_exibidos").value;
		if(exibidos==0){nova_listagem = 1;}
		else{nova_listagem = 0;}
		
		document.getElementById("loading_resultados").style.display = 'block';
		libera_carregamento = 0;
		$.getJSON('<?php echo $link."/repositories/pessoas/pessoas.ctrl.php?acao=listar&tipo_pessoa=".$tipo_pessoa."";?>&inicial='+exibidos,{
				filtro_pessoa: filtro_pessoa,
				filtro_status:filtro_status,
				filtro_perfil:filtro_perfil,
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
						pessoas_aux = JSON.stringify(j[i]);
						novos += '<tr id="tr_'+j[i].id+'">';
						
						//td #
						novos += '<td class="hidden-xs hidden-sm">';
						novos += exibidos;
						novos += '</td>';
						
						//td codigo produto
						novos += '<td>';
						novos += j[i].nome;
						
							novos += '<div class="visible-xs visible-sm">';
								novos += j[i].email;
								if(j[i].cpf_cnpj!=null) novos += '<br>'+j[i].cpf_cnpj;
								if(j[i].celular!=null) novos += '<br>'+j[i].celular;
								if(j[i].telefone!=null) novos += '<br>'+j[i].telefone;
								if(j[i].status_descricao=='ATIVO'){desc_aux='success';}
								else if(j[i].status_descricao=='PENDENTE'){desc_aux='info';}
								else if(j[i].status_descricao=='SUSPENSO'){desc_aux='warning';}
								else if(j[i].status_descricao=='BLOQUEADO'){desc_aux='danger';} 
								novos += '<br><span class="label label-sm label-'+desc_aux+'">'+j[i].status_descricao+'</span>';
								

								
							novos += '</div>';					
						
						novos += '</td>';
						
						//td celular e telefone
						novos += '<td class="visible-lg">';
						if(j[i].celular!=null) novos += j[i].celular+'<br>';
						if(j[i].telefone!=null) novos += j[i].telefone;
						novos += '</td>';
						
						//td documento 
						novos += '<td class="hidden-xs hidden-sm">';
						if(j[i].cpf_cnpj!=null) novos += j[i].cpf_cnpj;
						novos += '</td>';
						
						//td email 
						novos += '<td class="hidden-xs hidden-sm">';
						novos += j[i].email;
						novos += '</td>';
						
						<?php if($lista_usuarios){ ?>
							novos += '<td class="hidden-xs hidden-sm">';
								if(j[i].perfil_descricao!=null)
									novos += j[i].perfil_descricao;
								else
									novos += 'Padrão';
							novos += '</td>';
						<?php } ?>
						
						//td status 
						novos += '<td class="hidden-xs hidden-sm">';
						novos += '<span class="label label-sm label-'+desc_aux+'">'+j[i].status_descricao+'</span>';
						novos += '</td>';
												
						
						
						//td acao
						novos += "<td>";
						
						novos += "<a href='<?php echo $link.'/pessoa/';?>"+j[i].id+"'><span data-toggle='tooltip' data-placement='left' title='Visualizar' data-original-title='Visualizar'> <i class='fa fa-search fs-19' > </i></span> </a>";
						<?php if(consultaPermissao($ck_mksist_permissao,$cod_modulo,"editar")){ ?>  
						
						novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Editar' data-original-title='Editar' onClick='alimenta_modal_cad_pessoa("+j[i].id+","+pessoas_aux+" )'; > <i class='fa fa-pencil-square-o fs-19' > </i></span> </a>";
						
						<?php if($lista_usuarios){ ?>
							novos += "<a><span class='pointer' data-toggle='tooltip' data-placement='left' title='Alertar' data-original-title='Alertas' onClick='cadastrar_alerta("+j[i].id+")'; > <i class='fa fa-bell fs-19' > </i></span> </a>";
						<?php } ?>
						
						
						<?php } ?>  
						
						novos += "<a class='pointer' data-toggle='tooltip' data-placement='left' title='Ver Haras' data-original-title='Ver Haras' onClick='lista_haras("+j[i].id+")';> <i class='fa fa-diamond fa-fw fs-18' ></i> <span class='badge mg-lf--10 mg-tp--5'>"+j[i].total_haras+"</span></span> </a>"; 
						
						
						
						novos += "</td>";
						
						//novos_aux = '<td><a href="<?php ##echo $link.'/pessoa/';?>'+j[i].id+'"><span data-toggle="tooltip" data-placement="left" title="Visualizar" data-original-title="Visualizar"  > <i class="fa fa-search fs-19" > </i></span> </a> <a> <span  class="pointer" data-toggle="tooltip" data-placement="left" title="Editar" data-original-title="Editar" onClick="alimenta_modal_cad_pessoa('+j[i].id+','+pessoas_aux+' )"; > <i class="fa fa-pencil-square-o fs-19" > </i></span> </a>  </td>';
						
						novos += '</tr>';
						
						
				}
				if(exibidos==0){novos= "<tr><td colspan='10'>Nenhuma pessoa cadastrada cadastrada</td></tr>";}
				//Se a quantidade de resultados for igual ao total esperado, libera para carregar mais
				if(cont_novos==30){ libera_carregamento = 1; }
				
				if(nova_listagem==1){
					$('#tbody_pessoas').html(novos);
				}
				else{
					$('#listagem_pessoas').append(novos);
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

function lista_haras(proprietario_id){
			$('#tbody_listagem_haras').html('<tr><td id="td_carregando_haras" colspan="10">Carregando Haras</td></tr>'); 
			$.getJSON('<?php echo $link."/repositories/haras/haras.ctrl.php?acao=listar";?>',{
							proprietario_id: proprietario_id,
							ajax: 'true'
					}, function(j){
									table_haras = "";
									for (var i = 0; i < j.length; i++) {
										//open tr
										table_haras += "<tr id='tr_lista_haras_"+j[i].id+"'>";
										table_haras += "<td>";
										table_haras += j[i].nome;
											table_haras += '<span class="visible-xs">';
												if(j[i].telefone!=null)
													table_haras += j[i].telefone+'<br>';
												if(j[i].contato!=null)
													table_haras += j[i].contato+'<br>';
											table_haras += '</span>';		
										table_haras += "</td>";
										table_haras += "<td class='hidden-xs'>";
											if(j[i].telefone!=null) 
												table_haras += j[i].telefone;
										table_haras += "</td>";
										table_haras += "<td class='hidden-xs'>";
											if(j[i].contato!=null) 
												table_haras += j[i].contato;
										table_haras += "</td>";
										table_haras += "<td>";
										
										table_haras += "<a> <span  class='pointer' data-toggle='tooltip ' data-placement='left' title='Remover' data-original-title='Remover ' onclick='remover_relacao_haras("+proprietario_id+","+j[i].id+")'> <i class='fa fa-user-times fs-16 '> </i></span> </a>";
										
										table_haras += "</td>";
										table_haras += "</tr>";
									}
									if(table_haras==""){
										table_haras='<tr><td id="td_carregando_haras" colspan="10">Nenhum Haras</td></tr>';
									}
									
									$('#tbody_listagem_haras').html(table_haras); 
									$('#md_haras').modal('show');
					});
}

function remover_relacao_haras(id_pessoa, id_haras){
			  
			  	jConfirm('Este Haras não estará mais atribuido a este usuário e ficará sem nenhum proprietário até que preenchido em seu cadastro.', 'Remover Relação?', function(r) {
						if(r){
							$.getJSON("<?php echo $link."/repositories/haras/haras.ctrl.php?acao=remove_proprietario";?>", {haras_id: id_haras }, function(result){
									if( result ){	
										$("#tr_lista_haras_"+id_haras).remove(); 
										jAlert('Removido com Sucesso.','Bom trabalho!','ok');
										document.getElementById("cont_exibidos").value = 0;
										document.getElementById("permite_carregar").value=1;
										lista_haras(id_pessoa);
										carregar_resultados();	  
		 								carregar_totais();
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
			 	
		 
function gerar_pdf_lista(){
	$('#form_filtros_produtos').attr('action', '<?php echo $link."/inc/pdf/gera_pdf_listas.php?pagina=pessoas&tipo_pessoa=".$tipo_pessoa."";?>&order='+order+'&ordem='+ordem); 
	$('#form_filtros_produtos').attr('target', '_blank');
	$('#form_filtros_produtos').submit();
	$('#form_filtros_produtos').attr('action', 'javascript:filtrar_fields();');
	$('#form_filtros_produtos').attr('target', '_top');
} 
		
		
		
		<!---->  
		
		function salvarAlerta(){
			acao = 'inserir';
			alerta = $('#form_alerta').serializeArray();
			//alert(JSON.stringify(pessoa));
			$.getJSON("<?php echo $link."/repositories/alertas/alertas.ctrl.php?acao=";?>"+acao, {alertas: alerta }, function(result){
				if( result.status==1 ){	
					//alert("MENSAGEM DE SUCESSO! \n\n APOS ATUALIZAR, CLICAR PARA ATUALIZAR NOVAMENTE.")
					
					cadastrar_alerta($('#pessoas_id_destino').val());
					jAlert(result.msg,'Bom trabalho!','ok');
					
				}
				else{
					jAlert(result.msg,'Não foi possível salvar as alterações!','alert');
				}
			 });
			
		}
		
		function cadastrar_alerta(id){
			
			$('#pessoas_id_destino').val(id);
			
			$('#tbody_listagem_alertas').html('<tr><td id="td_carregando_alertas" colspan="10">Carregando Alertas</td></tr>'); 
			$.getJSON('<?php echo $link."/repositories/alertas/alertas.ctrl.php?acao=listar";?>',{
							pessoa_id_alerta: id,
							ajax: 'true'
					}, function(j){
							table_alertas = "";
							cont_alerta=0;
							for (var i = 0; i < j.length; i++) {
								//open tr
								cont_alerta++;
								table_alertas += "<tr id='tr_lista_alertas_"+j[i].id+"'>";
									table_alertas += "<td>";
									table_alertas += cont_alerta;
									table_alertas += "</td>";
									table_alertas += "<td>";
									table_alertas += ConverteData(j[i].data_alerta);
									table_alertas += "</td>";
									table_alertas += "<td>";
									table_alertas += j[i].descricao;
									table_alertas += "</td>";
									table_alertas += "<td>";
									if(j[i].link != null)
										table_alertas += j[i].link;
									table_alertas += "</td>";
									table_alertas += "<td>";
										if(j[i].visualizado == 'S'){
											table_alertas += 'Sim';
										}
										else if(j[i].visualizado == 'N'){
											table_alertas += 'Não';
										}
										else{
											table_alertas += j[i].visualizado;
										}
									table_alertas += "</td>";
									table_alertas += "<td>";
										if(j[i].concluido == 'S' && j[i].dt_concluido != null){
											table_alertas += 'Em '+ConverteData(j[i].dt_concluido);
										}
										else if(j[i].concluido == 'S'){
											table_alertas += 'Sim';
										}
										else if(j[i].concluido == 'N' || j[i].concluido == 'null' || j[i].concluido == null){
											table_alertas += 'Não';
										}
										else{
											table_alertas += j[i].concluido;
										}
									table_alertas += "</td>";
									table_alertas += "<td>";
									table_alertas += ConverteData(j[i].dt_prazo);
									table_alertas += "</td>";
								table_alertas += "</tr>";
							}
							if(table_alertas==""){
								table_alertas='<tr><td id="td_carregando_alertas" colspan="10">Nenhum Alerta</td></tr>';
							}
							
							$('#tbody_listagem_alertas').html(table_alertas); 
							$('#md_alertas').modal('show');
					});
			
		}
	
	</script>
    
</body>
</html>
