<?php  
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";


header( "Content-type: application/vnd.ms-excel" );
// Força o download do arquivo
header( "Content-type: application/force-download" );
// Seta o nome do arquivo
header( "Content-Disposition: attachment; filename=lista_parcelas_" . date( 'Ymd_His' ) . ".xls" );
header( "Pragma: no-cache" );

// echo '<pre>';print_r($_REQUEST);echo '</pre>';

$menu_active = "cadastros"; 
$layout_title = "MECOB - Parcelas";
$sub_menu_active="parcelas";	
$tit_pagina = "Parcelas";	
$tit_lista = " Lista de Parcelas";


// if(!consultaPermissao($ck_mksist_permissao,"cad_contratos","qualquer")){ 
// 	header("Location: ".$link."/401");
// 	exit;
// }
include_once($raiz."/inc/util.php");

include_once(getenv('CAMINHO_RAIZ')."/inc/pdf/pdflista.php"); 
$order = $_GET['order'];
$ordem = $_GET['ordem'];

include_once(getenv('CAMINHO_RAIZ')."/repositories/parcelas/parcelas.db.php");

$parcelasDB  = new parcelasDB();	

include($raiz."/partial/html_ini.php");

$class_table="  border:solid 1px #e4e4e4;
				font-size:14px; 
				font-weight:100; 
				color:#000; 
				background-color:#fff ;
				padding:10px 10px 10px 10px !important;  
				";


$filtros=array();

if(isset($_REQUEST["filtro_contrato_id"])){$filtros['filtro_contrato_id'] = trim($_REQUEST["filtro_contrato_id"]);}
if(isset($_REQUEST["filtro_per_ini"])){$filtros['filtro_per_ini'] = trim($_REQUEST["filtro_per_ini"]);}
if(isset($_REQUEST["filtro_per_fim"])){$filtros['filtro_per_fim'] = trim($_REQUEST["filtro_per_fim"]);}
if(isset($_REQUEST["filtro_tpcontrato"])){$filtros['filtro_tpcontrato'] = trim($_REQUEST["filtro_tpcontrato"]);}
if(isset($_REQUEST["filtro_ted_id"])){$filtros['filtro_ted_id'] = trim($_REQUEST["filtro_ted_id"]);}	
if(isset($_REQUEST["filtro_status"])){$filtros['filtro_status'] = trim($_REQUEST["filtro_status"]);}
if(isset($_REQUEST["filtro_vendedor"])){$filtros['filtro_vendedor'] = trim($_REQUEST["filtro_vendedor"]);}
if(isset($_REQUEST["filtro_comprador"])){$filtros['filtro_comprador'] = trim($_REQUEST["filtro_comprador"]);}
if(isset($_REQUEST["filtro_status_ct"])){$filtros['filtro_status_ct'] = trim($_REQUEST["filtro_status_ct"]);}
if(isset($_REQUEST["tipo_operacao"])){$filtros['tipo_operacao'] = trim($_REQUEST["tipo_operacao"]);}
if ($_SESSION['perfil_id'] == NULL){
	if ($filtros['tipo_operacao'] == 'compra') {
		$filtros['filtro_comprador'] = $_SESSION['id'];
		$filtros['filtro_vendedor'] = '';
	} else {
		$filtros['filtro_vendedor'] = $_SESSION['id'];
		$filtros['filtro_comprador'] = '';
	}
}	

if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
	switch ($_REQUEST["order"]) {
			case 'contrato':		
							$order = "ct.id ".$_REQUEST["ordem"].",";			
							break;
			case 'parcela':		
							$order = "p.nu_parcela ".$_REQUEST["ordem"].",";			
							break;
			case 'vencimento':		
							$order = "p.dt_vencimento ".$_REQUEST["ordem"].",";			
							break;
			case 'pagamento':		
							$order = "p.dt_credito ".$_REQUEST["ordem"].",";			
							break;
			case 'valor':		
							$order = "p.vl_parcela ".$_REQUEST["ordem"].",";			
							break;
			case 'correcao':		
							$order = "p.vl_correcao_monetaria ".$_REQUEST["ordem"].",";			
							break;
			case 'juros':		
							$order = "p.vl_juros ".$_REQUEST["ordem"].",";			
							break;
			case 'honor':		
							$order = "p.vl_honorarios ".$_REQUEST["ordem"].",";			
							break;
			case 'vlpago':		
							$order = "p.vl_pagto ".$_REQUEST["ordem"].",";			
							break;
			case 'corrigido':		
							$order = "p.vl_corrigido ".$_REQUEST["ordem"].",";			
							break;					
			default:
					$order = '';	
			break;	
	}
	
} else {
	$order = '';
}	

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Parcelas ' );

$ret = $parcelasDB->lista_parcelas(  $conexao_BD_1,  $filtros   , $order ,   0, 'N'); 

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Parcelas 2 ' . json_encode($ret) );
	
// echo json_encode($ret);
// exit();		



?> 
<table id="listagem_parcelas"  class="table  table-bordered"  style=" <?php echo $class_table;?>" >
    <thead style=" <?php echo $class_table;?>">
		<tr style=" <?php echo $class_table;?>">
			<th id="th_contrato_id" >Contrato ID</th>
			<th id="th_descricao" >Produto</th> 
			<th id="th_descricao" >Evento</th> 
			<th id="th_vendedor" >Vendedor</th> 
			<th id="th_comprador" >Comprador</th> 
			<th id="th_parcela" >Parcela</th> 
			<th id="th_vencimento" >Vencimento</th> 
			<th id="th_vencimento" >Pagamento</th> 
			<th id="th_vencimento" >Crédito</th> 
			<th id="th_valor" >Valor</th> 
			<th id="th_honorarios" >Honorários</th> 
			<th id="th_pgto" >Pagamento</th> 
			<th id="th_repasse" >Repasse</th> 
		</tr>
    </thead>
    <tbody id="tbody_parcelas" style=" <?php echo $class_table;?>">
    <?php 
		$tt_parcelas = 0;

		foreach($ret as $parcela){ 
			$tt_parcelas++; // computa o total de parcelas
			if($parcela['dt_pagto'] == '0000-00-00' || $parcela['dt_pagto'] == null) {
				$dt_pagamento = '';
			} else {
				$dt_pagamento = date('d/m/Y', strtotime($parcela['dt_pagto']));
			}

			if($parcela['dt_credito'] == '0000-00-00' || $parcela['dt_credito'] == null) {
				$dt_credito = '';
			} else {
				$dt_credito = date('d/m/Y', strtotime($parcela['dt_credito']));
			}
		?>
			<tr style=" <?php echo $class_table;?>">
			<td style=" <?php echo $class_table;?>"><?php echo $parcela['contratos_id'];?></td>
			<td style=" <?php echo $class_table;?>"><?php echo rtrim($parcela['ct_descricao']);?></td>
			<td style=" <?php echo $class_table;?>"><?php echo trim($parcela['ev_nome']);?></td>
			<td style=" <?php echo $class_table;?>"><?php echo $parcela['nome'];?></td>
			<td style=" <?php echo $class_table;?>"><?php echo $parcela['comprador_nome'];?></td>
			<td style=" <?php echo $class_table;?>">(<?php echo $parcela['nu_parcela']."/".$parcela['ct_nu_parcelas'];?>)</td>
			<td style=" <?php echo $class_table;?>"><?php echo date('d/m/Y', strtotime($parcela['dt_vencimento']));?></td>
			<td style=" <?php echo $class_table;?>"><?php echo $dt_pagamento;?></td>
			<td style=" <?php echo $class_table;?>"><?php echo $dt_credito;?></td>

			<!-- <td style=" <?php echo $class_table;?>"><?php echo "R$ " . number_format($parcela['vl_parcela'], 2, ',', '.');?></td>
			<td style=" <?php echo $class_table;?>"><?php echo "R$ " . number_format($parcela['vl_honorarios'], 2, ',', '.');?></td>
			<td style=" <?php echo $class_table;?>"><?php echo "R$ " . number_format($parcela['vl_pagto'], 2, ',', '.');?></td> -->

			<td style=" <?php echo $class_table;?>"><?php echo number_format($parcela['vl_parcela'], 2, ',', '.');?></td>
			<td style=" <?php echo $class_table;?>"><?php echo number_format($parcela['vl_honorarios'], 2, ',', '.');?></td>
			<td style=" <?php echo $class_table;?>"><?php echo number_format($parcela['vl_pagto'], 2, ',', '.');?></td>

			<td style=" <?php echo $class_table;?>">
				<?php echo number_format($parcela['vl_pagto'] - $parcela['vl_honorarios'], 2, ',', '.');?>
			</td>
			</tr>

		<?php } ?>

		<tr>
			<td>Total Parcelas</td>
			<td><?= $tt_parcelas;?></td>
			<td colspan=7></td>
			<td><?= "=soma(j2:j". ($tt_parcelas + 1) .")";?></td>
			<td><?= "=soma(k2:k". ($tt_parcelas + 1) .")";?></td>
			<td><?= "=soma(l2:l". ($tt_parcelas + 1) .")";?></td>
			<td><?= "=soma(m2:m". ($tt_parcelas + 1) .")";?></td>
		</tr>
		



    </tbody>
</table>

<!-- <?php echo '<pre>'; print_r($parcela);echo '</pre>'; ?> -->

</body>
</html>
