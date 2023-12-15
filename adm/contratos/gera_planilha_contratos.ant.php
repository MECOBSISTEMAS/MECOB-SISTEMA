<?php  
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";


header( "Content-type: application/vnd.ms-excel" );
// Força o download do arquivo
header( "Content-type: application/force-download" );
// Seta o nome do arquivo
header( "Content-Disposition: attachment; filename=lista_contratos_" . date( 'Y-m-d_H_i_s' ) . ".xls" );
header( "Pragma: no-cache" );
$gerar_planilha_pdf = 1;

//echo '<pre>';print_r($_REQUEST);echo '</pre>';

$menu_active="contratos";
$layout_title = "MECOB - Contratos";
$sub_menu_active="contratos";	
$tit_pagina = "Contratos";	
$tit_lista = " Lista de contratos";	

if(!consultaPermissao($ck_mksist_permissao,"cad_contratos","qualquer")){ 
	header("Location: ".$link."/401");
	exit;
}
include_once($raiz."/inc/util.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");
$contratosDB  = new contratosDB();

 
include($raiz."/partial/html_ini.php");

$class_table="  border:solid 1px #e4e4e4;
				font-size:14px; 
				font-weight:100; 
				color:#999; 
				background-color:#fff ;
				padding:10px 10px 10px 10px !important;  
				";

?> 
<table id="listagem_contratos"  class="table  table-bordered"  style=" <?php echo $class_table;?>" >
    <thead style=" <?php echo $class_table;?>">
    <tr style=" <?php echo $class_table;?>">
    <th id="th_id" class="hidden-xs hidden-sm pointer" >Id </th>
    
    <th id="th_descricao">Contrato</th>
    <th id="th_data" >Data</th> 
    <th id="th_valor" >Valor</th>
    <th id="th_evento" >Evento</th>
    <th id="th_vendedor" >Vendedor</th>
    <th id="th_comprador" >Comprador</th>
    <th id="th_status" >Status</th>
    <th id="th_status" style=" width:800px"  >Última ocorrência</th>
    </tr>
    </thead>
    <tbody id="tbody_contratos" style=" <?php echo $class_table;?>">
    <?php 
	$filtros=array(); 
		  if(isset($_REQUEST["filtro_contrato"])){$filtros['filtro_contrato'] = trim($_REQUEST["filtro_contrato"]);}
		  if(isset($_REQUEST["filtro_data"])){$filtros['filtro_data'] = trim($_REQUEST["filtro_data"]);}
		  if(isset($_REQUEST["filtro_data_fim"])){$filtros['filtro_data_fim'] = trim($_REQUEST["filtro_data_fim"]);} 
		  if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
		  if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
		  if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
		  if(isset($_REQUEST["filtro_id"])){$filtros['filtro_id'] = trim($_REQUEST["filtro_id"]);}
		  if(isset($_REQUEST["filtro_pagto"])){$filtros['filtro_pagto'] = trim($_REQUEST["filtro_pagto"]);} 	 
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
									$order = "c.id ".$_REQUEST["ordem"].",";			
									break;
					case 'descricao':		
									$order = "c.descricao ".$_REQUEST["ordem"].",";			
									break;
					case 'valor':		
									$order = "c.vl_contrato ".$_REQUEST["ordem"].",";			
									break;
					case 'data':		
									$order = "c.dt_contrato ".$_REQUEST["ordem"].",";			
									break;
					case 'vendedor':		
									$order = "pv.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'comprador':		
									$order = "pc.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'evento':		
									$order = "e.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'status':		
									$order = "c.status ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$contratos = $contratosDB->lista_contratos('', $conexao_BD_1,  $filtros, $order, 0, 'N',1);
		//echo '<pre>';print_r($contratos);echo '</pre>';
		foreach($contratos as $contrato){ ?>
		<tr style=" <?php echo $class_table;?>">
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['id'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['descricao'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['dt_contrato'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo 'R$ '.Format($contrato['vl_contrato'],'numero');?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['evento_nome'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['vendedor_nome'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['comprador_nome'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['status'];?></td>
        <td style=" <?php echo $class_table;?> width:800px" ><?php echo ConverteData($contrato['data_ocorrencia'])." ";
				  echo $contrato['oc_status']." ";
				  echo strip_tags($contrato['oc_mensagem'])." ";
			?></td> 
        </tr>
		<?php } ?>
    
    </tbody>
    
</table>

 
    
</body>
</html>