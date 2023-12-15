					<?php
                    	include_once(getenv('CAMINHO_RAIZ')."/inc/combos.php");
					?>
                    <form id="form_lotes" action="javascript:salvarFormulario()">
                    <input id="inputId" type="hidden" name="id"  placeholder="Id" class="form-control" />
                    <div class="form-body pal pd-tp-0">
                        <h3 class="mg-tp-0">Informações do Lote</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                		<div class="placeholder">Tipo:</div>
                                        <?php echo combo_tipo_lote('tipo', 'SelectTipo', 'form-control  with-placeholder');?>    
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
                            <div class="col-md-6">
                                <div class="form-group input-icon right">
                               		 <div class="placeholder">Número de registro:</div>
                                    <input id="inputRegistro" name="num_registro" type="text" placeholder="Contato" class="form-control  with-placeholder" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input-icon right">
                                	 <div class="placeholder">Data de Nascimento:</div>
                                    <input id="inputDtNasc" name="dt_nascimento" type="text" placeholder="Telefone" class="form-control  with-placeholder" /></div>
                            </div>
                            
                        </div>
                        
                       
                         
                        
                        
                        
                    </div>
                    <button type="submit" class="hidden"></button>
                    </form>