					 <?php
                     $total_haras = count($haras);
					 if($total_haras){ 
					 ?>
                      <h3 class="mg-tp-0">Haras deste Usuário</h3>
                      <?php foreach($haras as $haras_item){ ?>
                      		<h4 style="text-transform:uppercase"> &nbsp;&nbsp;&nbsp;
                             <?php echo $haras_item['nome'];?> </h4>
                      <?php } ?>
                      <br />
                      <?php } ?>  
                        
                      <?php if($podeAtualizar){ ?>
                      <form id="form_pessoas" action="javascript:salvarFormulario()" class="form-horizontal">
                        <h3 class="mg-tp-0">Configuração da conta</h3>
                        <input id="inputId" type="hidden" name="id"  placeholder="Id" class="form-control" value="<?php echo $pessoa[0]["id"]; ?>" />
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Email</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <input name="email" type="email" class="form-control" value="<?php echo $pessoa[0]["email"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Apelido / Nome Fantasia:</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <input name="apelido" type="text" placeholder="Apelido / Nome Fantasia:" class="form-control" value="<?php echo $pessoa[0]["apelido"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Senha</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-md-4 col-xs-8">
                                <input name="password" type="password" placeholder="password" class="form-control"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Confirmar senha</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-md-4 col-xs-8">
                                <input name="password_confirma" type="password" placeholder="password" class="form-control"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr/>
                        <h3>Dados pessoais</h3>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Nome</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <input name="nome" type="text" placeholder="Nome" class="form-control" value="<?php echo $pessoa[0]["nome"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">CPF</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <input id="inputCpf" name="cpf_cnpj" type="text" placeholder="CPF" class="form-control" value="<?php echo $pessoa[0]["cpf_cnpj"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">RG / IE</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <input id="inputRg" name="rg" type="text" placeholder="RG / IE" class="form-control" value="<?php echo $pessoa[0]["rg"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group hidden">
                          <label class="col-sm-3 control-label">Sexo</label>
                          <div class="col-sm-9 controls hidden">
                            <div class="row">
                              <div class="col-xs-9">
                                <div class="radio">
                                  <label class="radio-inline">
                                    <input type="radio" value="0" name="gender" checked="checked" class="fl-none mg-0"/>
                                    &nbsp; <span class="float-left">Masculino</span> </label>
                                  <br />
                                  <label class="radio-inline">
                                    <input type="radio" value="1" name="gender" class="fl-none mg-0"/>
                                    &nbsp; <span class="float-left">Feminino</span></label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Data de Nascimento</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-md-4 col-xs-8">
                                <input name="dt_nascimento" id="dt_nascimento" type="text" class="form-control" value="<?php echo ConverteData($pessoa[0]["dt_nascimento"]); ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Sobre</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <textarea name="sobre" rows="3" class="form-control"><?php echo $pessoa[0]["sobre"]; ?></textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr/>
                        <h3>Contato</h3>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Celular</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input id="celular" name="celular" type="text" placeholder="(00) 0000-0000" class="form-control" value="<?php echo Format($pessoa[0]["celular"],'telefone'); ?>"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Site</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input name="site" type="text" placeholder="Site" class="form-control" value="<?php echo $pessoa[0]["site"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Facebook</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input name="facebook" type="text" placeholder="Facebook" class="form-control" value="<?php echo $pessoa[0]["facebook"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Twitter</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input name="twitter" type="text" placeholder="Twitter" class="form-control" value="<?php echo $pessoa[0]["twitter"]; ?>"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-green btn-block">Salvar</button>
                      </form>
                      <?php }
					  elseif($proprio_user ){ ?>
                      
                      
                      
                      <form id="form_pessoas" action="javascript:salvarFormulario()" class="form-horizontal">
                        <h3>Dados pessoais</h3>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Nome</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <?php echo $pessoa[0]["nome"]; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">CPF</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <?php echo $pessoa[0]["cpf_cnpj"]; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">RG / IE</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <?php echo $pessoa[0]["rg"]; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Data de Nascimento</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-md-4 col-xs-8">
                                <?php echo ConverteData($pessoa[0]["dt_nascimento"]); ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Sobre</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <?php echo $pessoa[0]["sobre"]; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr/>
                        <h3 class="mg-tp-0">Configuração da conta</h3>
                        <input id="inputId" type="hidden" name="id"  placeholder="Id" class="form-control" value="<?php echo $pessoa[0]["id"]; ?>" />
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Email</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <?php echo $pessoa[0]["email"]; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Apelido / Nome Fantasia:</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-9">
                                <?php echo $pessoa[0]["apelido"]; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Senha</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-md-4 col-xs-8">
                                <input name="password" type="password" placeholder="password" class="form-control"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Confirmar senha</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-md-4 col-xs-8">
                                <input name="password_confirma" type="password" placeholder="password" class="form-control"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr/>
                        
                        <h3>Contato</h3>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Celular</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input id="celular" name="celular" type="text" placeholder="(00) 0000-0000" class="form-control" value="<?php echo Format($pessoa[0]["celular"],'telefone'); ?>"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Site</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input name="site" type="text" placeholder="Site" class="form-control" value="<?php echo $pessoa[0]["site"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Facebook</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input name="facebook" type="text" placeholder="Facebook" class="form-control" value="<?php echo $pessoa[0]["facebook"]; ?>" />
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Twitter</label>
                          <div class="col-sm-9 controls">
                            <div class="row">
                              <div class="col-xs-10">
                                <input name="twitter" type="text" placeholder="Twitter" class="form-control" value="<?php echo $pessoa[0]["twitter"]; ?>"/>
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr/>
                        <button type="submit" class="btn btn-green btn-block">Salvar</button>
                      </form>
                      <?php }
					  else{ ?>
                      <style>
										 .control-label{ text-align:right;}
										 </style>
                      <h3 class="mg-tp-0">Conta</h3>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-9"> <?php echo $pessoa[0]["email"]; ?> </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Apelido / Nome Fantasia:</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-9"> <?php echo $pessoa[0]["apelido"]; ?> </div>
                          </div>
                        </div>
                      </div>
                      <hr/>
                      <h3>Dados pessoais</h3>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Nome</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-9"> <?php echo $pessoa[0]["nome"]; ?> </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">CPF</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-9"><?php echo $pessoa[0]["cpf_cnpj"]; ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">RG / IE</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-9"><?php echo $pessoa[0]["rg"]; ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row hidden">
                        <label class="col-sm-3 control-label">Sexo</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-9">
                              <div class="radio"> Sexo </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Data de Nascimento</label>
                        <div class="col-sm-9 controls ">
                          <div class="row">
                            <div class="col-md-4 col-xs-8"> <?php echo ConverteData($pessoa[0]["dt_nascimento"]); ?> </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Sobre</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-9"> <?php echo $pessoa[0]["sobre"]; ?> </div>
                          </div>
                        </div>
                      </div>
                      <hr/>
                      <h3>Contato</h3>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Celular</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-10">
                              <?php 
														if(empty($pessoa[0]["celular"]))
															echo '--';
														else
															echo Format($pessoa[0]["celular"],'telefone'); ?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Site</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-10">
                              <?php
														$site = $pessoa[0]["site"];
														if(empty($site))
															echo '--';
														else{
															if(substr($site,0,4)!='http')
																$site = 'http://'.$site; 
															?>
                              <a href="<?php echo $site;?>" target="_blank"> <?php echo $pessoa[0]["site"]; ?> </a>
                              <?php
														}
														?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Facebook</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-10">
                              <?php
														$site = $pessoa[0]["facebook"];
														if(empty($site))
															echo '--';
														else{
															if(substr($site,0,4)!='http')
																$site = 'http://'.$site; 
															?>
                              <a href="<?php echo $site;?>" target="_blank"> <?php echo $pessoa[0]["facebook"]; ?> </a>
                              <?php
														}
														?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-sm-3 control-label">Twitter</label>
                        <div class="col-sm-9 controls">
                          <div class="row">
                            <div class="col-xs-10">
                              <?php
														$site = $pessoa[0]["twitter"];
														if(empty($site))
															echo '--';
														else{
															if(substr($site,0,4)!='http')
																$site = 'http://'.$site; 
															?>
                              <a href="<?php echo $site;?>" target="_blank"> <?php echo $pessoa[0]["twitter"]; ?> </a>
                              <?php
														}
														?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php } ?>