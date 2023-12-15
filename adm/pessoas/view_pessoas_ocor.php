
<div class="row mbl pd-15">
  <div  >
    <h3>Últimas Ocorrências </h3><br />
    <?php
					include_once(getenv('CAMINHO_RAIZ')."/repositories/ocorrencias/ocorrencias.db.php");
					$ocorrenciasDB  = new ocorrenciasDB();
					$ocorrencias = $ocorrenciasDB->lista_ocorrencias( $conexao_BD_1,  $id ,0,5);
					//echo '<pre>'; print_r($ocorrencias); echo '</pre>';
					
					foreach($ocorrencias as $ocorrencia){ ?>
                            <h4>
                              <div class="row">
                                <div class="col-sm-8 fs-17">
                                    Contrato: 
                                    <?php 
                                    echo $ocorrencia['c_id'].' - '.$ocorrencia['descricao'].' '.ConverteData($ocorrencia['dt_contrato']).' - ('.$ocorrencia['c_status'].')';?>
                                </div>
                                <div class="col-sm-4 ar fs-12">Usuário responsável: <?php echo $ocorrencia['nome'];?></div>
                              </div>
                            </h4>
                            <div class="ocor_msg"><?php echo ConverteData($ocorrencia['data_ocorrencia']);?><br>
                              Status: <?php echo $ocorrencia['o_status'];?><br>
                              <p><?php echo $ocorrencia['mensagem'];?></p>
                            </div>
                            <hr />
                    <?php
                    } 
					?> 
</div>
</div>
