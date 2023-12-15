					<?php
                    	include_once(getenv('CAMINHO_RAIZ')."/inc/combos.php");
					?>
                    <form id="form_haras" action="javascript:salvarFormulario()">
                    <input id="inputId" type="hidden" name="id"  placeholder="Id" class="form-control" />
                    <div class="form-body pal pd-tp-0">
                        <h3 class="mg-tp-0">Informações do Haras</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                		<div class="placeholder">Nome:</div>
                                        <input id="inputNome"  name="nome"  type="text" placeholder="Nome" class="form-control  with-placeholder" required="required" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group input-icon right">
                               		 <div class="placeholder">Contato:</div>
                                    <input id="inputContato" name="contato" type="text" placeholder="Contato" class="form-control  with-placeholder" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input-icon right">
                                	 <div class="placeholder">Telefone:</div>
                                    <input id="inputTelefone" name="telefone" type="text" placeholder="Telefone" class="form-control  with-placeholder" /></div>
                            </div>
                            
                        </div>
                        
                        <hr />
                        
                        <h3>Endereço do Haras</h3>
                        
                          <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                	<div class="placeholder">CEP:</div>
                                    <input id="inputCep"   name="cep"  type="text" placeholder="CEP" class="form-control with-placeholder"  onBlur="buscaCep();" /> <span id="btn-cnpj" class="input-group-btn ">
                    <a target="_blank" href="http://www.buscacep.correios.com.br/"> ? </a>
            </span>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                	<div class="placeholder">Rua:</div>
                                    <input id="inputRua" type="text"  name="rua"  placeholder="Rua" class="form-control with-placeholder" /></div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                	<div class="placeholder">Número:</div>
                                    <input id="inputNumero" type="text"  name="numero"  placeholder="Número" class="form-control with-placeholder" /></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                	<div class="placeholder">Bairro:</div>
                                    <input id="inputBairro" type="text"  name="bairro"  placeholder="Bairro" class="form-control with-placeholder" /></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                	<div class="placeholder">Estado:</div>
                                    <select "form-control" id="inputEstado" name="estado" class="form-control with-placeholder"/>
                                        <option value="">Selecione</option>
                                         <option value="AC">Acre</option>
                                         <option value="AL">Alagoas</option>
                                         <option value="AP">Amapá</option>
                                         <option value="AM">Amazonas</option>
                                         <option value="BA">Bahia</option>
                                         <option value="CE">Ceará</option>
                                         <option value="DF">Distrito Federal</option>
                                         <option value="GO">Goiás</option>
                                         <option value="ES">Espírito Santo</option>
                                         <option value="MA">Maranhão</option>
                                         <option value="MT">Mato Grosso</option>
                                         <option value="MS">Mato Grosso do Sul</option>
                                         <option value="MG">Minas Gerais</option>
                                         <option value="PA">Pará</option>
                                         <option value="PB">Paraiba</option>
                                         <option value="PR">Paraná</option>
                                         <option value="PE">Pernambuco</option>
                                         <option value="PI">Piauí­</option>
                                         <option value="RJ">Rio de Janeiro</option>
                                         <option value="RN">Rio Grande do Norte</option>
                                         <option value="RS">Rio Grande do Sul</option>
                                         <option value="RO">Rondônia</option>
                                         <option value="RR">Roraima</option>
                                         <option value="SP">São Paulo</option>
                                         <option value="SC">Santa Catarina</option>
                                         <option value="SE">Sergipe</option>
                                         <option value="TO">Tocantins</option>
                                       </select>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                	<div class="placeholder">Cidade:</div>
                                    <input id="inputCidade" name="cidade" type="text" placeholder="Cidade" class="form-control with-placeholder" /></div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                        	<div class="placeholder">Complemento:</div>
                            <textarea id="inputComplemento" name="complemento" rows="5" placeholder="Complemento" class="form-control with-placeholder"></textarea></div>
                            
                            <hr />
                        
                        <h3>Informações do Proprietário</h3>
                        
                        <div id="row_status" class="row">
                                <div id="quadro_proprietario" class="col-md-12 hidden">
                                    <div class="row">
                                        <div  class="col-xs-3 col-sm-2  ">
                                        <img id="img_prop" src="" class="img-responsive img-circle  wd-100p"/>
                                        </div>
                                        <div id="info_prop" class="col-xs-9 col-sm-10">
                                        </div> 
                                    </div>
                                    <br />
                                </div>
                                <div id="select_proprietario" class="col-md-12">
                                    <div class="form-group input-icon right">
                                             
                                             <div class="placeholder">Buscar Proprietário:</div>
                                          
                                            <input id="inputProprietario"  name="findproprietario"  type="text" placeholder="Proprietário" class="form-control  with-placeholder"  autocomplete="off"  onkeyup="busca_proprietario();"  />
                                            <input id="inputProprietario_id"  name="proprietario_id"  type="hidden"  />
                                            <div id="autocp_proprietario" class="hidden autocp_div">
                                            <div id="div_loading_autocp" class="row loading_something hidden ">
                                            <img src="<?php echo $link."/imagens/loading_circles.gif";?>" />
                                            </div>
                                            </div>
                                    </div>
                                    
                                </div> 
                        </div>
                        
                       <div id="inputs_proprietario">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                	<div class="placeholder">Nome do Proprierátio:</div>
                                   <input id="inputNomeProp"  name="proprietario_nome"  type="text" placeholder="Nome do proprietário" class="form-control  with-placeholder" /></div>
                            </div>
                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                	<div class="placeholder">Documento do Proprierátio:</div>
                                   <input id="inputDocProp"  name="proprietario_doc"  type="text" placeholder="CPF/CNPJ" class="form-control  with-placeholder color-placeholder" /></div>
                            </div>
                            
                        </div>
                         
                    </div>    
                        
                        
                    </div>
                    <button type="submit" class="hidden"></button>
                    </form>
                    
                    
   <script>
     function busca_proprietario(){
		 
		 palavra =  $('#inputProprietario').val();	
		
		
		tam_palavra = palavra.length;
		if(tam_palavra>=3){
			$('#autocp_proprietario').removeClass('hidden');
			$('.loading_something').removeClass('hidden');
			if (delay_busca){
                clearTimeout(delay_busca);
			}
			delay_busca = setTimeout(function(){
						$.get("<?php echo $link."/adm/pessoas/lista_pessoas_autocomplete.ajax.php";?>", {palavra: palavra,tipo_pessoa:'all'}, function(result){
											if( result=='0' ){
												//limpa resultados div_loading
												$('.autocp_div').html(div_loading_autocp);
												$('.autocp_div').addClass('hidden');
												click_condominos_control=0;	
											}
											else{
												//exibe resultados
												$('#autocp_proprietario').html(result);
												$('#autocp_proprietario').removeClass('hidden');
												click_condominos_control=1;	
											}
						 });
			 }, 500);
		}
		else{
			//limpa resultados
			$('.autocp_div').html(div_loading_autocp);
			$('.autocp_div').addClass('hidden');
			click_condominos_control=0;	
		}
		
	}
	
	function escolhe_autocomplete_pessoa(pessoa){
		$('#inputProprietario_id').val(pessoa.id);
		
		btn_limpa_vend="";
		btn_limpa_prop= '<span class="fa fa-refresh red_light pull-right fs-18 pointer" onclick="limpa_proprietario();"></span>';
		proprietario = btn_limpa_prop+"<strong>Proprietário:</strong><br>"+pessoa.nome+"<br>"+pessoa.email;
		$("#info_prop").html(proprietario);
		$("#img_prop").attr("src", "<?php echo getenv('CAMINHO_SITE')."/imagens/fotos/nail/";?>"+pessoa.foto);
		
		
		$('#inputNomeProp').val(pessoa.nome);
		$('#inputDocProp').val(pessoa.cpf_cnpj);
		
		$('#autocp_proprietario').addClass('hidden');
		$('#select_proprietario').addClass('hidden');
		$('#inputs_proprietario').addClass('hidden');
		$('#quadro_proprietario').removeClass('hidden');
		
	}
	
	function limpa_proprietario(){
		$('#inputProprietario_id').val('');
		$('#inputProprietario').val('');
		$('#inputNomeProp').val('');
		$('#inputDocProp').val('');
		$('#quadro_proprietario').addClass('hidden');	
		$('#select_proprietario').removeClass('hidden');
		$('#inputs_proprietario').removeClass('hidden');
		
	}
	

	
                    </script>