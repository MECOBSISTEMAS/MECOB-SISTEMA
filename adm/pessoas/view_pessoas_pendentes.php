<?php

//include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

$contratosDB  = new contratosDB();
$ct    = new contratos();
$ct->vendedor_id = $id;

$filtros="";
if($ehCliente){
	//$filtros = array('filtro_ativo'=>1);
}
$cfg_filtros = 'pendentes'; 
$filtros = array('filtro_ativo'=>'pendentes');
include("filtros_contrato_ini.php"); 
$contratos_vendedor = $contratosDB->lista_contratos($ct, $conexao_BD_1,  $filtros  ,  "c.id desc," ,  0,"N");
//echo '<pre>'; print_r($contratos); echo '</pre>';

?>

<h3 class="mg-tp-0">Contratos de Venda Pendentes </h3>
<?php 
include("filtros_contrato.php"); 
?>

<div class="panel-group" id="accordionVend" role="tablist" aria-multiselectable="true">
<?php
foreach($contratos_vendedor as $contrato){ 
	$cor_stt_contrato = "#5F9EA0";
	if($contrato['pc_atrasada']>0){$cor_stt_contrato = "#DE5145";}
	else if($contrato['pc_total'] == $contrato['pc_liqd']){$cor_stt_contrato = "#1BA261";}
?>
<div class="panel panel-default">
    <div class="panel-heading pessoa_collapse_title" role="tab" id="heading<?php echo $contrato['id'];?>" 
    style=" background-color:<?php echo $cor_stt_contrato;?>" >
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordionVend" href="#collapse<?php echo $contrato['id'];?>" aria-expanded="true" aria-controls="collapse<?php echo $contrato['id'];?>" class="pessoa_collapse_title_a" onClick="carrega_parcelas_vendedor(<?php echo $contrato['id'];?>);">
          <i class="fa fa-plus "></i>  
          <?php echo 'Evento: '.$contrato['evento_nome'].'<br>';?>
           Contrato <?php echo $contrato['id']." - ".$contrato['descricao']." - ".ConverteData($contrato['dt_contrato']);?>
          <?php echo ' - Valor: R$ '.Format($contrato['vl_contrato'],'numero');?>
          <?php echo ' ( Status: '.$contrato['status'].')';?>
          <?php echo ' - Pagto: '.$contrato['pc_liqd'].'/'.$contrato['pc_total'];?> 
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo $contrato['id'];?>" class="panel-collapse collapse pessoa_collapse_body" role="tabpanel" aria-labelledby="heading<?php echo $contrato['id'];?>">
      <div class="panel-body">
      	<div class="pessoa_collapse_info">
        <h4 class="mg-tp-0">Evento</h4>
        <h5><?php echo $contrato['evento_nome'];?></h5>
        <h4 class="mg-tp-0">Comprador</h4>
        <h5>Nome: <?php echo $contrato['comprador_nome'];?></h5>
        <h5>E-mail: <?php echo $contrato['comprador_email'];?></h5>
        <h5>Documento: <?php echo Format($contrato['comprador_cpf_cnpj'],'documento');?></h5>
        <?php if(!empty($contrato['comprador_telefone']) || !empty($contrato['comprador_celular'])) { 
				$comprador_telefone = "";
				
			  if(!empty($contrato['comprador_telefone'])) 
						$comprador_telefone = Format($contrato['comprador_telefone'],'telefone'); 
			
			  if(!empty($contrato['comprador_celular'])) {
						if($comprador_telefone != "")
							$comprador_telefone .= " <span class='hidden-xs'>/</span> <div class='visible-xs'><br></div> ";
						$comprador_telefone .= Format($contrato['comprador_celular'],'telefone'); 
			  } 
			  
			  echo   "<h5>Telefone:  ".$comprador_telefone." </h5>";
			} ?>

        </div>
        <div id="documentos_contrato_<?php echo $contrato['id'];?>" class="bk-white" >
        Carregando Documentos...
        </div>
        <div id="parcelas_contrato_<?php echo $contrato['id'];?>" class="pessoa_collapse_parcelas" >
        Carregando Parcelas...
        </div>
      </div>
    </div>
  </div>
<?php } ?> 
</div>

