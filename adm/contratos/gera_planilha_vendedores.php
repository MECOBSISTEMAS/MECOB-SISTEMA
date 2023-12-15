<?php  
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";


header( "Content-type: application/vnd.ms-excel" );
// Força o download do arquivo
header( "Content-type: application/force-download" );
// Seta o nome do arquivo
header( "Content-Disposition: attachment; filename=vendedore_contratos_" . date( 'Y-m-d_H_i_s' ) . ".xls" );
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
<table id="listagem_contratos"  class="table  table-bordered" style=" <?php echo $class_table;?>"  >
    <thead style=" <?php echo $class_table;?>" >
    <tr style=" <?php echo $class_table;?>" >
    <th id="th_id" class="hidden-xs hidden-sm pointer" > </th>
    
    <th id="th_descricao"   >Vendedor</th>
    <th id="th_data"   >Total Contratos vigentes</th>
    <th id="th_valor"   >em dia </th>
    <th id="th_valor"   >Total Contratos inadimplentes</th>
    <th id="th_evento"    >Usuário responsável</th>
    </tr>
    </thead>
    <tbody id="tbody_contratos" style=" <?php echo $class_table;?>" >
		<?php
		$contratos = $contratosDB->lista_vendedores_contratos($conexao_BD_1);
		//echo '<pre>';print_r($contratos);echo '</pre>';
		$cont=0;
		foreach($contratos as $contrato){$cont++; ?>
		<tr style=" <?php echo $class_table;?>" >
        <td style=" <?php echo $class_table;?>"><?php echo $cont;?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['vendedor_nome'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['total_ct'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['total_ct']-$contrato['total_inadp'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['total_inadp'];?></td>
        <td style=" <?php echo $class_table;?>"><?php echo $contrato['user_id'].' - '.$contrato['user_nome'];?></td>
        </tr>
		<?php } ?>
    
    </tbody>
    
</table>

 
    
</body>
</html>