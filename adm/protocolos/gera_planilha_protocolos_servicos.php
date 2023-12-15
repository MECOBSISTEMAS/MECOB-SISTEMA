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

include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_servicos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_servicos.db.php");
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
			<th id="th_id" >ID</th> 
			<th id="th_nome" style=" width:1000px">Nome</th>
			<th id="th_tipo" >Tipo</th> 
			<th id="th_enviado" >Enviado</th>
			<th id="th_recebido" >Recebido</th>
			<th id="th_digitalizado" >Digitalizado</th>
			<th id="th_fisico" >Físico</th>
			<th id="th_observacao" >Observação</th>
			<th id="th_dt_registro" >Registro</th>
			<th id="th_dt_atualizacao" >Atualização</th>
		</tr>
    </thead>
    <tbody id="tbody_contratos" style=" <?php echo $class_table;?>">
    <?php 
		// Filtros
		$filtros=array();
		if(isset($_REQUEST['filtro_vendedor'])) {$filtros['filtro_vendedor'] = trim($_REQUEST['filtro_vendedor']);}			
		if(isset($_REQUEST['filtro_tipo'])) {$filtros['filtro_tipo'] = trim($_REQUEST['filtro_tipo']);}

		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Contratos total ' . json_encode($filtros));

		if(isset($_REQUEST["order"]) && $_REQUEST["ordem"]){
			switch ($_REQUEST["order"]) {
					case 'id':		
								$order = "ps.id ".$_REQUEST["ordem"].",";			
								break;
					case 'nome':		
								$order = "ps.nome ".$_REQUEST["ordem"].",";			
								break;
					case 'tipo':		
								$order = "ps.tipo ".$_REQUEST["ordem"].",";			
								break;
					case 'enviado':		
								$order = "ps.enviado ".$_REQUEST["ordem"].",";			
								break;
					case 'recebido':		
								$order = "ps.recebido ".$_REQUEST["ordem"].",";			
								break;
					case 'digitalizado':		
								$order = "ps.digitalizado ".$_REQUEST["ordem"].",";			
								break;
					case 'fisico':		
								$order = "ps.fisico ".$_REQUEST["ordem"].",";			
								break;

					default:
							$order = '';	
					break;	
			}
			
		}
		else{$order = '';}	
	
		$protocolos = $protocolosDB->lista_protocolos_servicos('', $conexao_BD_1,  $filtros, $order, 0, 'N',1);

		$data_atual = date('d/m/Y H:i:s');
		// $tt_status  = array();

		foreach($protocolos as $protocolo){ 
		?>
			<tr style=" <?php echo $class_table;?>">
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['id'];?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['nome'];?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['tipo'];?></td>

				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['enviado'] == '0000-00-00' ? '' : date('d/m/Y', strtotime($protocolo['enviado']));?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['recebido'] == '0000-00-00' ? '' : date('d/m/Y', strtotime($protocolo['recebido']));?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['digitalizado'] == '0000-00-00' ? '' : date('d/m/Y', strtotime($protocolo['digitalizado']));?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['fisico'] == '0000-00-00' ? '' : date('d/m/Y', strtotime($protocolo['fisico']));?></td>

				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['observacao'];?></td>

				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['dt_registro'] == '0000-00-00 00:00:00' ? '' : date('d/m/Y H:i:s', strtotime($protocolo['dt_registro']));?></td>
				<td style=" <?php echo $class_table;?>"><?php echo $protocolo['dt_atualizacao'] == '0000-00-00 00:00:00' ? '' : date('d/m/Y H:i:s', strtotime($protocolo['dt_atualizacao']));?></td>

			</tr>
		<?php } ?>

		<tr></tr>

    </tbody>
</table>

</body>
</html>
