			<form id="form_arquivo" action="<?php echo $link."/repositories/arquivos/arquivos.ctrl.php";?>" enctype="multipart/form-data" method="post">
                    <input id="inputId" type="hidden" name="id"  placeholder="Id" class="form-control"  />
                    <input type="file" id="arqFile" name="arquivo" style="display:none" onchange="troca_img()"/>
                    <div class="form-body pal">
                    
                    <div class="row">
                    	<div class="col-md-3">
                    	<div class="text-center mbl" style="position:relative;">
                            <div style="position:relative">
                            <div class="uploading_user_arq hidden">
                                    <h4>Carregando Arquivo</h4>
                                    <br>
                                    <div><span id="kb_upado"></span></div>
                                    <br>
                                    <div class=" progress progress-striped">
                                      <div id="bar_up_arq" class="progress-bar progress-bar-success  " role="progressbar" aria-valuenow="00" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                        0%
                                      </div>
                                    </div>
                             </div>
                                    <a href="#" onClick="javascript:openImputFile();">
                                    <img id="arq_arquivo" src="" alt="" class="img-responsive wd-100p"/>
                                    <div id="call_nm_arq" class="ac">
                                    </div>
                                    </a>
                                </div>
                              
                            
                        </div>
                    </div>
                    <div class="col-md-9">
                    	 
                        
                        <div id="row_status" class="row">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                		<div class="placeholder">Categoria:</div>
                                        <?php combo_tipo_arquivo("tp_arq", "tp_arq", "form-control  with-placeholder"); ?>  
                                </div>
                            </div>                            
                        </div>
                        
                        <div class="row hidden">
                            <div class="col-md-12">
                                <div class="form-group input-icon right">
                                		<div class="placeholder">Descrição:</div>
                                        <textarea id="descricao"  name="descricao"  type="text" placeholder="Descrição" class="form-control with-placeholder"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        
                      </div>
                      </div>
                    </div>
                    <button type="submit" class="hidden"></button>
                    </form>