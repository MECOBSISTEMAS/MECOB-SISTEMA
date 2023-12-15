          <div class="row mbl pd-15">
          
            <div class=" col-md-12 bk-white "   >
            <br />
            <button id="btn_detalhe_fluxo" class="btn btn-info " onclick="control_detalhe_fluxo();"> Detalhar </button>
            
            
            <div class="detalhe_fluxo " style="display:none;" >
              
              <?php include_once(getenv('CAMINHO_RAIZ')."/repositories/parcelas/parcelas.db.php");
					include_once(getenv('CAMINHO_RAIZ')."/repositories/teds/teds.db.php");
					$parcelasDB  = new parcelasDB();
					$tedsDB  = new tedsDB();
					
			  		$carteira_cliente = $parcelasDB->carteira_cliente( $conexao_BD_1,  $id);  
					$repasse_cliente = $tedsDB->parcelas_para_ted_cliente($conexao_BD_1, $id);  
					$fluxo_cliente = $parcelasDB->fluxo_cliente( $conexao_BD_1,  $id); 
					//echo '<pre>'; print_r($fluxo_cliente); echo '</pre>';
					
					// echo '<pre>';	print_r($repasse_cliente); echo '</pre>';
					$valor_repasse = $valor_transferir = 0;
					if(isset($repasse_cliente[0]['vl_transferir'])){
						$valor_repasse = $valor_transferir = $repasse_cliente[0]['vl_transferir'];
					}
					$valor_receber =  $carteira_cliente[0]['receber']; 

					?>
                    <h3>Carteira </h3>
                    
                    <table class="table table-striped">
                        <thead>
                          <tr>
                          	<th class="visible-xs ">Resumos</th>
                            <th class="hidden-xs ">Repasse</th>
                            <th class="hidden-xs ">A vencer</th>
                            <th class="hidden-xs">A Pagar</th> 
                          </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
							<div class="hidden-xs">
							<?php echo 'R$ '.Format($valor_repasse,'numero');?>
                            </div>
                            <div class="visible-xs">
                            	<?php echo '<strong>Repasse:</strong><br>R$ '.Format($valor_repasse,'numero');?>
                            	<?php echo '<br><strong>Aberto:</strong><br>R$ '.Format($valor_receber,'numero');?> 
                                <?php echo '<br><strong>Pagar:</strong><br>R$ '.Format($carteira_cliente[0]['pagar'],'numero');?>
                            </div>
                            </td>
                            <td class="hidden-xs "><?php echo 'R$ '.Format($valor_receber,'numero');?></td>
                            <td class="hidden-xs"><?php echo 'R$ '.Format($carteira_cliente[0]['pagar'],'numero');?></td> 
                          </tr>
                        </tbody>
                    </table>
                    
                    <h3>Fluxo de caixa </h3>
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Mês</th>
                            <th class="hidden-xs ">A Receber</th>
                            <th class="hidden-xs">A Pagar</th>
                            <th class=" ">Saldo</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php  
                          $soma_cred = $soma_debt = $soma_dif =0;
                          foreach($fluxo_cliente as $fluxo_mes){
                                $soma_cred+= str_replace(',','',$fluxo_mes['receber']); 
                                $soma_debt+= str_replace(',','',$fluxo_mes['pagar']); 
                                $dif = str_replace(',','',$fluxo_mes['receber']) - str_replace(',','',$fluxo_mes['pagar']);
                                $soma_dif+= $dif ; 
                          ?>
                          <tr>
                            <td scope="row"><?php 
								$mes_fluxo_array = explode('-',$fluxo_mes['mes']); 
								echo '<strong>'.date_to_mes($mes_fluxo_array[1])." ".$mes_fluxo_array[0].'</strong>';
								?>
                            	<div  class="hidden ">
                                <?php echo 'Crédito:<br>'.Format($fluxo_mes['receber'],'numero');?>
                                <?php echo '<br>Débito:<br>'.Format($fluxo_mes['pagar'],'numero');?>
                                <?php echo '<br>Total:<br>'.Format($dif,'numero');?>
                                </div>
                            </td>
                            <td class="hidden-xs "><?php echo Format($fluxo_mes['receber'],'numero');?></td>
                            <td class="hidden-xs "><?php echo Format($fluxo_mes['pagar'],'numero');?></td>
                            <td class=" "><?php echo Format($dif,'numero');?></td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <th scope="row">Total
                           		 <div  class="hidden ">
                                <?php echo 'Crédito:<br>'.Format($soma_cred,'numero');?>
                                <?php echo '<br>Débito:<br>'.Format($soma_debt,'numero');?>
                                <?php echo '<br>Total:<br>'.Format($soma_dif,'numero');?>
                                </div>
                            </th>
                            <th class="hidden-xs "><?php echo Format($soma_cred,'numero');?></th>
                            <th class="hidden-xs "><?php echo Format($soma_debt,'numero');?></th>
                            <th class=" "><?php echo Format($soma_dif,'numero');?></th>
                          </tr>
                        </tbody>
                      </table>
                      <?php
					  $total_proximos = $soma_cred+$soma_debt;
					  $percent_receber = $soma_cred*100/$total_proximos;
					  $percent_pagar = $soma_debt*100/$total_proximos;
					  ?>
            	</div>
            </div>
            <?php if( $total_proximos  != 0){ ?>
            <div class="col-md-12 bk-white " >
            <br /> 
            <div class="row">
                    <div class="col-md-12">
                      <div id="grafico_fluxo_4_meses" style="width:100%; "> </div>
                    </div>
                    <div class="col-md-4 hidden">
                        <h4 class="mbm"> Fluxo</h4>
                        <span class="task-item"> A Receber <small class="pull-right text-muted">
                        R$ <?php echo Format($soma_cred,'numero'); ?>                      
                        </small>
                        <div class="progress progress-sm">
                        <div role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent_receber;?>%; background-color:#90ED7D" class="progress-bar "> </div>
                        </div>
                        </span> 
                        <span class="task-item"> A Pagar <small class="pull-right text-muted">
                         R$ <?php echo Format($soma_debt,'numero'); ?>   
                        </small> 
                        <div class="progress progress-sm">
                        <div role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percent_pagar;?>%; background-color:#D9534F" class="progress-bar "> </div>
                        </div>
                        </span>
                     </div>
                  </div>
            </div>
             <?php } ?>
             
             
             
             <div class="col-md-12 bk-white" >
            <div class="row">
                    <div class="col-md-6">
                    
                      <div id="grafico_carteira" style="width:100%; "> </div>
                    </div>
                    
                    <div class="col-md-6"  >
                      <div id="grafico_repasse"> </div> 
                      
                    </div>
                  </div>
            </div>
             
             
             
             
             
          </div>