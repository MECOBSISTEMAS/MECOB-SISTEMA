					<?php
                    	include_once(getenv('CAMINHO_RAIZ')."/inc/combos.php");
					?>
                    <form id="form_boletos_avulso" action="javascript:salvarFormulario()">
                    <input id="inputId" type="hidden" name="id"  placeholder="Id" class="form-control" />
                    <div class="form-body pal pd-tp-0">

                        <h3>Informações do Proprietário</h3>

                        <div id="form-group row_status" class="row">
                                <div id="quadro_proprietario" class="col-md-12 hidden">
                                    <div class="form-group row">
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

                                             <div class=" placeholder">Buscar Proprietário:</div>
                                            <input id="inputProprietario"  name="findproprietario"  type="text" placeholder="Proprietário" class=" form-control  with-placeholder "  autocomplete="off"  onkeyup="busca_proprietario();"  required />
                                            
                                            <div id="autocp_proprietario" class="hidden autocp_div">
                                            <div id="div_loading_autocp" class="row loading_something hidden ">
                                            <img src="<?php echo $link."/imagens/loading_circles.gif";?>" />
                                            </div>
                                            </div>
                                            <input id="inputProprietario_id"  name="proprietario_id"  type="hidden"  class=" form-control" />
                                            
                                    </div>

                                </div>
                        </div>

                        <div class="row mg-tp-10">
                            <div class=" form-group col-xs-12 col-sm-12" >
                                <div class="placeholder">Data Boleto:</div>
                                <input id="inputDtBoleto"  name="dt_boleto"  type="text" placeholder="Data Boleto" class="form-control with-placeholder "  readonly="readonly" value="<?php echo date('d/m/Y');?>" />
                            </div>
                        </div>

                        <div class="row mg-tp-10">
                            <div class="form-group col-xs-12 col-sm-12" >
                                <div class="placeholder">Data Vencimento:</div>
                                <input id="inputDtBoletoVencimento"  name="dt_vencimento"  type="text" placeholder="Data Vencimento" class="form-control with-placeholder "  required />
                            </div>
                        </div>

                        <div class="row mg-tp-10">
                            <div class="form-group col-xs-12 col-sm-12" >
                                <div class="placeholder">Valor Boleto:</div>
                                <input id="inputVlBoleto"  name="vl_boleto"  type="text" placeholder="Valor Boleto" class="form-control with-placeholder vl_mask" required />
                            </div>
                        </div>

                        <div class="row mg-tp-10">
                            <div class="form-group col-xs-12 col-sm-12" >
                                <div class="placeholder">ID Contrato</div>
                                <input id="inputIDcontrato"  name="contratos_id"  type="text" placeholder="ID Contrato" 
									autocomplete="off" class="form-control with-placeholder" />
                            </div>
							<p id="pIDcontrato" style="margin-left: 2em;"></p>
                        </div>

                        <div class="row mg-tp-10">
                            <div class="form-group col-xs-12 col-sm-12" >
                                <div class="placeholder">Descrição</div>
                                <input id="inputDesc"  name="descricao"  type="text" placeholder="Descrição" autocomplete="off" class="form-control with-placeholder" />
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
		$('#quadro_proprietario').removeClass('hidden');
		
	}
	
	function limpa_proprietario(){
		$('#inputProprietario_id').val('');
		$('#inputProprietario').val('');
		$('#inputNomeProp').val('');
		$('#inputDocProp').val('');
		$('#quadro_proprietario').addClass('hidden');	
		$('#select_proprietario').removeClass('hidden');

		$('#inputDesc').val('');

	}
</script>