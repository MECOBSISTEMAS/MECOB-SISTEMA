					<?php
                    	include_once(getenv('CAMINHO_RAIZ')."/inc/combos.php");
					?>
                    <form id="form_eventos" action="javascript:salvarFormulario()">
                    <input id="inputId" type="hidden" name="id"  placeholder="Id" class="form-control" />
                    <div class="form-body pal pd-tp-0">
                        <h3 class="mg-tp-0">Informações do Evento</h3>
                        <a href="<?php echo $link."/leiloeiros";?>" target="_blank" class="pull-right fs-20 green_light"><i class="fa fa-plus-square fs-20" aria-hidden="true"></i> Cadastrar Leiloeiro</a> 
                        <div id="row_status" class="row">
                                <div id="quadro_leiloeiro" class="col-md-12 hidden">
                                    <div class="row">
                                        <div  class="col-xs-3 col-sm-2 ">
                                        <img id="img_leiloeiro" src="" class="img-responsive img-circle  wd-100p"/>
                                        </div>
                                        <div id="info_leiloeiro" class="col-xs-9 col-sm-10">
                                        </div> 
                                    </div>
                                    <br />
                                </div>
                                <div id="select_leiloeiro" class="col-md-12">
                                    <div class="form-group input-icon right">
                                             
                                             <div class="placeholder">Leiloeiro:</div>
                                          
                                            <input id="inputLeiloeiro"  name="findleiloeiro"  type="text" placeholder="Leiloeiro" class="form-control  with-placeholder"  autocomplete="off"  onkeyup="busca_leiloeiro();"  />
                                            <input id="inputLeiloeiroId" name="leiloeiro_id" type="hidden"  />
                                            <div id="autocp_leiloeiro" class="hidden autocp_div">
                                            <div id="div_loading_autocp" class="row loading_something hidden ">
                                            <img src="<?php echo $link."/imagens/loading_circles.gif";?>" />
                                            </div>
                                            </div>
                                    </div>
                                    
                                </div> 
                          </div>
                          
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                		<div class="placeholder">Tipo:</div>
                                        <?php echo combo_tipo_evento('tipo', 'SelectTipo', 'form-control  with-placeholder');?>    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                		<div class="placeholder">Nome:</div>
                                        <input id="inputNome"  name="nome"  type="text" placeholder="Nome" class="form-control  with-placeholder" required="required" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                	 <div class="placeholder">Data do Evento:</div>
                                    <input id="inputDtEvento" name="dt_evento" type="text" placeholder="Telefone" class="form-control  with-placeholder" /></div>
                            </div>
                            
                        </div>
                        
                        
                    </div>
                    <button type="submit" class="hidden"></button>
                    </form>
                    
  <script>
     function busca_leiloeiro(){
		 
		 palavra =  $('#inputLeiloeiro').val();	
		
		
		tam_palavra = palavra.length;
		if(tam_palavra>=3){
			$('#autocp_leiloeiro').removeClass('hidden');
			$('.loading_something').removeClass('hidden');
			if (delay_busca){
                clearTimeout(delay_busca);
			}
			delay_busca = setTimeout(function(){
						$.get("<?php echo $link."/adm/pessoas/lista_pessoas_autocomplete.ajax.php";?>", {palavra: palavra,tipo_pessoa:'leiloeiros'}, function(result){
											if( result=='0' ){
												//limpa resultados div_loading
												$('.autocp_div').html(div_loading_autocp);
												$('.autocp_div').addClass('hidden');
												click_condominos_control=0;	
											}
											else{
												//exibe resultados
												$('#autocp_leiloeiro').html(result);
												$('#autocp_leiloeiro').removeClass('hidden');
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
		$('#inputLeiloeiroId').val(pessoa.id);
		
		btn_limpa_leil="";
		btn_limpa_leil = '<span class="fa fa-refresh red_light pull-right fs-18 pointer" onclick="limpa_leiloeiro();"></span>';
		leiloeiro = btn_limpa_leil+"<strong>Leiloeiro:</strong><br>"+pessoa.nome+"<br>"+pessoa.email;
		$("#info_leiloeiro").html(leiloeiro);
		$("#img_leiloeiro").attr("src", "<?php echo getenv('CAMINHO_SITE')."/imagens/fotos/nail/";?>"+pessoa.foto);
		
		
		$('#autocp_leiloeiro').addClass('hidden');
		$('#select_leiloeiro').addClass('hidden');
		$('#quadro_leiloeiro').removeClass('hidden');
	}
	
	function limpa_leiloeiro(){
		$('#inputLeiloeiroId').val('');
		$('#quadro_leiloeiro').addClass('hidden');	
		$('#select_leiloeiro').removeClass('hidden');
	}
	

	
                    </script>