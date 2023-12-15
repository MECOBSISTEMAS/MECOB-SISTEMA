<?php  
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
include_once($raiz."/inc/combos.php");
include_once $raiz."/valida_acesso.php";


header( "Content-type: application/vnd.ms-excel" );
// Força o download do arquivo
header( "Content-type: application/force-download" );
// Seta o nome do arquivo
header( "Content-Disposition: attachment; filename=lista_protocolos_" . date( 'Y-m-d_H_i_s' ) . ".xls" );
header( "Pragma: no-cache" );

//echo '<pre>';print_r($_REQUEST);echo '</pre>';

$menu_active = "protocolos";
$layout_title = "MECOB - Protocolos";
$sub_menu_active = "contratos";
$tit_pagina = "Protocolos";
$tit_lista = " Protocolos de contratos";

// if(!consultaPermissao($ck_mksist_permissao,"cad_contratos","qualquer")){ 
// 	header("Location: ".$link."/401");
// 	exit;
// }
include_once($raiz."/inc/util.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_setor.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

$protocolosDB     = new protocolosDB();
// $protocolos       = new protocolos();

include($raiz."/partial/html_ini.php");

$class_table="  border:solid 1px #e4e4e4;
				font-size:14px; 
				font-weight:100; 
				color:#999; 
				background-color:#fff ;
				padding:10px 10px 10px 10px !important;  
				";

?> 
<table id="listagem_protocolos"  class="table  table-bordered"  style=" <?php echo $class_table;?>" >
    <thead style=" <?php echo $class_table;?>">
		<tr style=" <?php echo $class_table;?>">
			<th id="th_descricao" style=" width:1000px">Protocolo</th>
			<th id="th_data" >Data</th> 
			<th id="th_prazo" >Prazo</th>
			<th id="th_status" >Status</th>
			<th id="th_valor" >Valor</th>
			<th id="th_evento" >Evento</th>
			<th id="th_vendedor" >Vendedor</th>
			<th id="th_comprador" >Comprador</th>
			<th id="th_comprador" >Data 1ª parcela</th>
			<th id="th_comprador" >Nº 1ª parcela</th>
			<th id="th_comprador" >Data Contrato</th>
			<th id="th_comprador" >Data Digitalização</th>
			<th id="th_comprador" >Contrato ID</th>
			<th id="th_comprador" >Permanência</th>
			<th id="th_comprador" >Finalização</th>
			<th id="th_status" style=" width:1000px"  >Última ocorrência</th>
		</tr>
    </thead>
    <tbody id="tbody_contratos" style=" <?php echo $class_table;?>">
    <?php 
		// Filtros
		$filtros=array();
		if(isset($_REQUEST['filtro_protocolo_id'])) {$filtros['filtro_protocolo_id'] = trim($_REQUEST['filtro_protocolo_id']);}
		if(isset($_REQUEST['filtro_data'])) {$filtros['filtro_data'] = trim($_REQUEST['filtro_data']);}
		if(isset($_REQUEST['filtro_prazo'])) {$filtros['filtro_prazo'] = trim($_REQUEST['filtro_prazo']);}
		if(isset($_REQUEST['filtro_status'])) {$filtros['filtro_status'] = trim($_REQUEST['filtro_status']);}			
		if(isset($_REQUEST['filtro_setor'])) {$filtros['filtro_setor'] = trim($_REQUEST['filtro_setor']);}			
		if(isset($_REQUEST['filtro_vencimento'])) {$filtros['filtro_vencimento'] = trim($_REQUEST['filtro_vencimento']);}			
		if(isset($_REQUEST['filtro_vendedor'])) {$filtros['filtro_vendedor'] = trim($_REQUEST['filtro_vendedor']);}			
		if(isset($_REQUEST['filtro_comprador'])) {$filtros['filtro_comprador'] = trim($_REQUEST['filtro_comprador']);}			
		if(isset($_REQUEST['filtro_evento'])) {$filtros['filtro_evento'] = trim($_REQUEST['filtro_evento']);}			
		if(isset($_REQUEST['filtro_produto'])) {$filtros['filtro_produto'] = trim($_REQUEST['filtro_produto']);}			

		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Contratos total ' . json_encode($filtros));

		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
									$order = "p.id ".$_REQUEST["ordem"].",";			
									break;
					case 'protocolo':		
									$order = "p.protocolo ".$_REQUEST["ordem"].",";			
									break;
					case 'vendedor':		
									$order = "p.vendedor ".$_REQUEST["ordem"].",";			
									break;
					case 'comprador':		
									$order = "p.comprador ".$_REQUEST["ordem"].",";			
									break;
					case 'evento':		
									$order = "p.evento ".$_REQUEST["ordem"].",";			
									break;
					case 'produto':		
									$order = "p.produto ".$_REQUEST["ordem"].",";			
									break;
					case 'prazo':		
									$order = "p.prazo ".$_REQUEST["ordem"].",";			
									break;
					case 'dt_lancamento':		
									$order = "p.dt_lancamento ".$_REQUEST["ordem"].",";			
									break;
					case 'valor':		
									$order = "p.valor ".$_REQUEST["ordem"].",";			
									break;
					case 'status':		
									$order = "p.status ".$_REQUEST["ordem"].",";			
									break;
					case 'setor':		
									$order = "p.setor ".$_REQUEST["ordem"].",";			
									break;
					case 'contrato_id':		
									$order = "p.contrato_id ".$_REQUEST["ordem"].",";			
									break;

					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$protocolos = $protocolosDB->lista_protocolos('', $conexao_BD_1,  $filtros, $order, 0, 'N',1);
		$vl_total   = 0;
		$qtd_total  = 0;
		$data_atual = date('d/m/Y H:i:s');
		// $tt_status  = array();

		foreach($protocolos as $protocolo){ 
			$start_date         = new DateTime($protocolo['dt_registro']);
			$finalizado_date    = new DateTime($protocolo['finalizado'] == null ? date('Y-m-d H:i:s') : $protocolo['finalizado']);
			$dt_ocorrencia      = ($protocolo['dt_ocorrencia'] != null) ? date('d/m/Y H:i:s', strtotime($protocolo['dt_ocorrencia'])) : null;
			// $prazo_date         = new DateTime($protocolo['prazo'] . " 00:00:00");

			$permanencia = format_interval($start_date->diff($finalizado_date));

			$finalizacao = 'Em andamento';
			if ($protocolo['finalizado'] != null ) {
				if ( date('Y-m-d', strtotime($protocolo['finalizado'])) < $protocolo['prazo']) {
					$finalizacao = 'No prazo';
				} else {
					$finalizacao = 'Atrasado';
				}
			} else if ( date('Y-m-d') == $protocolo['prazo']) {
				$finalizacao = 'Vence hoje';
			}
		?>
			<tr style=" <?php echo $class_table;?>">
				<!-- <td style=" <?php echo $class_table;?>"><?php echo $protocolo['id'];?></td> -->
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['protocolo'];?></td>
				<td style=" <?php echo $class_table;?>"><?php echo date('d/m/Y H:i:s', strtotime($protocolo['dt_registro']));?></td>
				<td style=" <?php echo $class_table;?>"><?php echo date('d/m/Y', strtotime($protocolo['prazo']));?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['status'];?></td>
				<td style=" <?php echo $class_table;?>"><?php echo "R$ " . number_format($protocolo['valor'], 2, ',', '.');?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['evento'];?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['vendedor'];?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['comprador'];?></td>
				<td style=" <?php echo $class_table;?>"><?php echo date('d/m/Y', strtotime($protocolo['dt_parcela']));?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['nr_parcela'];?></td>
				<?php
					// if (strtoupper($protocolo['evento']) == 'VENDA DIRETA' && $protocolo['ct_verifica'] == 1) {
						echo "<td style=\"".$class_table. "\">".$protocolo['dt_contrato']."</td>";
						echo "<td style=\"".$class_table. "\">".$protocolo['dt_digitalizado']."</td>";
					// }
				?>				
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['contrato_id'];?></td>
				<td style=" <?php echo $class_table;?>"><?= $permanencia; ?></td>
				<td style=" <?php echo $class_table;?>"><?= $finalizacao; ?></td>
				<td style=" <?php echo $class_table;?>"><?= $dt_ocorrencia; ?></td>

			</tr>
		<?php 
			$vl_total += $protocolo['valor'];
			$qtd_total++;
			if(!isset($tt_status[$protocolo['status']]['qtd'])) {
				$tt_status[$protocolo['status']]['qtd'] = 0;
				$tt_status[$protocolo['status']]['valor'] = 0;
			}
			$tt_status[$protocolo['status']]['qtd']++;
			$tt_status[$protocolo['status']]['valor'] += $protocolo['valor'];

			if(!isset($tt_finalizacao[$finalizacao]['qtd'])) {
				$tt_finalizacao[$finalizacao]['qtd'] = 0;
			}
			$tt_finalizacao[$finalizacao]['qtd']++;
			// $tt_finalizado[$protocolo['finalizado']]['valor'] += $protocolo['valor'];
			
		} ?>

		<!-- Linha em branco antes dos Sub-totais -->
		<tr></tr>

		<?php 
			ksort($tt_status);
			foreach($tt_status as $key => $value) {
		?>
			<tr>
				<td><?= 'Qtd ' .$key; ?></td>
				<td><?= number_format($value['qtd'], 0, ',', '.'); ?></td>
				<td colspan="1"></td>
				<td><?= 'Valor ' .$key; ?></td>
				<td><?= 'R$ ' . number_format($value['valor'], 2, ',', '.'); ?></td>
			</tr>
		<?php }	?>

		<!-- Linha em branco antes do total -->
		<tr></tr>

		<tr>
			<td>Qtd Total</td>
			<td><?= number_format($qtd_total, 0, ',', '.'); ?></td>
			<td colspan="1"></td>
			<td>Valor total:</td>
			<td><?= "R$ " . number_format($vl_total, 2, ',', '.'); ?></td>
		</tr>

		<tr></tr>

		<?php 
			ksort($tt_finalizacao);
			foreach($tt_finalizacao as $key => $value) {
		?>
			<tr>
				<td><?= 'Qtd ' .$key; ?></td>
				<td><?= number_format($value['qtd'], 0, ',', '.'); ?></td>
			</tr>
		<?php }	?>

    </tbody>
</table>

</body>
</html>
