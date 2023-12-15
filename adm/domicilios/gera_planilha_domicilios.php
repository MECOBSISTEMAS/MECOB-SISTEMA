<?php  
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";


header( "Content-type: application/vnd.ms-excel" );
// Força o download do arquivo
header( "Content-type: application/force-download" );
// Seta o nome do arquivo
header( "Content-Disposition: attachment; filename=lista_domicilios_" . date( 'Y-m-d_H_i_s' ) . ".xls" );
header( "Pragma: no-cache" );
$gerar_planilha_pdf = 1;

$class_table="  border:solid 1px #e4e4e4;
				font-size:14px; 
				font-weight:100; 
				color:#999; 
				background-color:#fff ;
				padding:10px 10px 10px 10px !important; 
				";

//echo '<pre>';print_r($_REQUEST);echo '</pre>';

$menu_active="domicilios";
$layout_title = "MECOB - domicilios";
$sub_menu_active="domicilios";	
$tit_pagina = "domicilios";	
$tit_lista = " Lista de domicilios";	

if(!consultaPermissao($ck_mksist_permissao,"cad_domicilios","qualquer")){ 
	header("Location: ".$link."/401");
	exit;
}
include_once($raiz."/inc/util.php"); 
include_once(getenv('CAMINHO_RAIZ')."/repositories/domicilios/domicilios.db.php");
$domiciliosDB  = new domiciliosDB();

 
include($raiz."/partial/html_ini.php");
?> 
<table id="listagem_contratos"  class="table  table-bordered"  style=" <?php echo $class_table;?>">
    <thead style=" <?php echo $class_table;?>">
    <tr  style=" <?php echo $class_table;?>">
    <th id="th_descricao" class="pointer" >Vendedor</th>
    <th id="th_data" class="pointer hidden-xs hidden-sm "> Banco</th>
    
    <th id="th_valor" class="pointer " onclick="ordenar('valor');" >Agencia</th>
    <th id="th_evento" class="pointer hidden-xs hidden-sm"  >Conta</th> 
    </tr>
    </thead>
    <tbody id="tbody_contratos" style=" <?php echo $class_table;?>">
    <?php 
	$filtros=array();
		if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}  
		
		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'vendedor':		
									$order = "pv.nome ".$_REQUEST["ordem"].",";			
									break;
					case 'banco':		
									$order = "t.banco ".$_REQUEST["ordem"].",";			
									break;
					case 'agencia':		
									$order = "t.agencia ".$_REQUEST["ordem"].", dv_agencia ".$_REQUEST["ordem"].",";			
									break;
					case 'conta':		
									$order = "t.conta ".$_REQUEST["ordem"].", dv_conta ".$_REQUEST["ordem"].",";			
									break;
					
					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	 
	
		$domicilios = $domiciliosDB->lista_domicilios(  $conexao_BD_1,  $filtros, $order, 0, 'N');
		//echo '<pre>';print_r($contratos);echo '</pre>';
		foreach($domicilios as $domicilio){ ?>
		<tr style=" <?php echo $class_table;?>">
        <td style=" <?php echo $class_table;?>"><?php echo $domicilio['vendedor_nome'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $domicilio['banco'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $domicilio['agencia'];
				  if(!empty($domicilio['dv_agencia']))
				  		echo '-'.$domicilio['dv_agencia'] ;?>
		</td>
        <td style=" <?php echo $class_table;?>"><?php echo $domicilio['conta'];
				  if(!empty($domicilio['dv_conta']))
				  		echo '-'.$domicilio['dv_conta'] ;?>
		</td>
		<?php } ?>
    
    </tbody>
    
</table>

 
    
</body>
</html>