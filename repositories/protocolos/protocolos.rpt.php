<?php
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

$id = $_GET['protocolo_id'];
$data = date('Ymdhis');

$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');

require_once $raiz . '/vendor/autoload.php';
setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');

include_once($raiz."/inc/util.php");
// require_once($raiz.'/inc/html2pdf/html2pdf.class.php');

include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_setor.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php"); // Dados da Conexao_DB

$msg 		   = array();
$protocolosDB  = new protocolosDB();
$protocolos    = new protocolos();
$reflection    = new ReflectionObject($protocolos);



// Busca os dados do protocolo
$retorno = $protocolosDB->busca_protocolo($conexao_BD_1, $id);

// Monta as variaveis para o relatório
$protocolo  = $retorno[0]['protocolo'];
$data       = date("d/m/Y H:i:s", strtotime( $retorno[0]['dt_registro']));
$vendedor   = $retorno[0]['vendedor'];
$comprador  = $retorno[0]['comprador'];
$cad_pessoa = $retorno[0]['cad_pessoa'];
$prazo      = date("d/m/Y", strtotime( $retorno[0]['prazo']));
$dt_parcela = date("d/m/Y", strtotime( $retorno[0]['dt_parcela']));
$nr_parcela = $retorno[0]['nr_parcela'];
$evento     = $retorno[0]['evento'];
$produto    = $retorno[0]['produto'];
$valor      = 'R$ '.number_format($retorno[0]['valor'], 2, ',', '.');
$status     = $retorno[0]['status'];
$p_cadastro = $retorno[0]['p_cadastro'];
$setor      = $retorno[0]['setor'];
$p_setor    = $retorno[0]['p_setor'];
$dt_setor   = date("d/m/Y H:i:s", strtotime( $retorno[0]['setor_trans']));
$p_finalizado  = $retorno[0]['p_finalizado'];
$dt_finalizado = ($retorno[0]['finalizado'] != null ) ? date("d/m/Y H:i:s", strtotime( $retorno[0]['finalizado'])) : null;
$contrato_id   = $retorno[0]['contrato_id'];

$dt_contrato     = $retorno[0]['dt_contrato'];
$dt_digitalizado = $retorno[0]['dt_digitalizado'];
$ct_verifica     = $retorno[0]['ct_verifica'];

$finalizado_motivo = str_replace("\n", "<br>", $retorno[0]['finalizado_motivo']);

$observacao    = str_replace("\n", "<br>", $retorno[0]['observacao']);
// $observacao    = str_replace("\n", "", $retorno[0]['observacao']);

$start_date         = new DateTime($retorno[0]['dt_registro']);
$first_date         = new DateTime($retorno[0]['dt_registro']);
$finalizado_date    = new DateTime($retorno[0]['finalizado'] == null ? date('Y-m-d H:i:s') : $retorno[0]['finalizado']);
$data_atual         = date('d/m/Y H:i:s');

$permanencia = format_interval($start_date->diff($finalizado_date));


// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Entrou DB ' . $dt_finalizado . ' - ' . json_encode($retorno[0]['finalizado']));

$html_permanencia = '';

if ($dt_finalizado != null ) {
	$html_permanencia = ' 
	<tr>
		<td width="30%" style="text-align: right;">Data da Finalização: </td>
		<td style="text-align: left;">'.$dt_finalizado.'</td>
	</tr>

	<tr>
		<td width="30%" style="text-align: right;">Tempo total: </td>
		<td style="text-align: left;">'.$permanencia.'</td>
	</tr>
	<tr>
		<td width="30%" style="text-align: right;">Finalizador: </td>
		<td style="text-align: left;">'.$p_finalizado.'</td>
	</tr>';
}


// Venda Direta
$html_venda_direta = 'tesete';
if(strtoupper($evento) == 'VENDA DIRETA' and $ct_verifica == 1) {
		$html_venda_direta = ' 
	<tr>
		<td width="30%" style="text-align: right;">Recebimento do contrato: </td>
		<td style="text-align: left;">'.$dt_contrato.'</td>
	</tr>

	<tr>
		<td width="30%" style="text-align: right;">Digitalização do contrato: </td>
		<td style="text-align: left;">'.$dt_digitalizado.'</td>
	</tr>';
}


// Busca e monta tabela com as transferências

$ret_setor = $protocolosDB->busca_trans_setor($conexao_BD_1, $id);

$html_setor = '';

if(count($ret_setor) > 1) {
	$html_setor = '<table id="t_setor" nome="t_setor" style="width:100%;">';

	$html_setor .= '<tr>
					<th>Status</th>
					<th>Data e hora</th>
					<th>Permanência</th>
					<th>Usuário</th>';
					
	foreach ($ret_setor as $key => $row) {
		$html_setor .= '<tr>';
		foreach ($row as $key => $value) {
			if($key == 'data') {
				$first_date_setor = null;
				$first_date_setor = new DateTime(date("Y-m-d H:i:s", strtotime( $value )));
				$html_setor .= '<td> '.date("d/m/Y H:i:s", strtotime( $value )).' </td>';
			} else if($key == 'dataF') {
				$next_date_setor = null;
				if( $value != null and $value != '') {
					$next_date_setor = new DateTime(date("Y-m-d H:i:s", strtotime( $value )));
					$permanencia = format_interval($first_date_setor->diff($next_date_setor));

					$html_setor .= '<td>'.$permanencia.' </td>';
					
				} else {
					if($dt_finalizado != null ) {
						$permanencia = format_interval($first_date_setor->diff($finalizado_date));
						$html_setor .= '<td> '.$permanencia.' </td>';
					} else {
						// $html_setor .= '<td> '.$data_atual.' </td>';
						$html_setor .= '<td> Em andamento </td>';
						// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Entrou DB ' . $data_atual);
					}
				}
			} else {
				$html_setor .= '<td> '.$value.' </td>';
			}

		}
		$html_setor .= '</tr>';

	}
	$html_setor .= '</tr>';
	$html_setor .= '</table>';
}

// Observação
$html_observacao = '';
if ($observacao != null && $observacao != ' ') {
	$html_observacao  = '<table class="t_separador" style="width:100%;">';
	$html_observacao .= '<tr>';
	$html_observacao .= '<th>Observação</th>';
	$html_observacao .= '</tr>';
	$html_observacao .= '<tr>';
	$html_observacao .= '<td>';
	$html_observacao .= $observacao;
	$html_observacao .= '</td>';
	$html_observacao .= '</tr>';
	$html_observacao .= '</table>';
}
// Cancelados
$html_cancelado = '';

if($finalizado_motivo != null) {
	$html_cancelado  = '<table class="t_separador" style="width:100%;">';
	$html_cancelado .= '<tr>';
	$html_cancelado .= '<th>Motivo do cancelamento</th>';
	$html_cancelado .= '</tr>';
	$html_cancelado .= '<tr>';
	$html_cancelado .= '<td>';
	$html_cancelado .= $finalizado_motivo;
	$html_cancelado .= '</td>';
	$html_cancelado .= '</tr>';

	$html_cancelado .= '</table>';
}



// Monta o HTML para o PDF
$html = '
<html>
<head>
	<!-- <title>Protocolo_contratos.pdf</title> -->
	<style>
		table {
			padding: 12px;
		}

		table#cabecalho th, table#cabecalho td {
			border: 0px solid black;
			padding: 2px;
		}

		// table#t_setor td, th {
		// 	border: 1px solid black;
		// 	padding: 2px;
		// }
		
		table#t_setor td 
		{
			border-style:solid;
			border-top:thick #cccccc;
			padding: 3px;
		}

		table.t_separador td
		{
			border-style:solid;
			border-top:thick #cccccc;
			padding: 3px;
		}

	</style>
</head>
	<body>
		<table id="cabecalho" style="width:100%; text-align: center;">
			<tr>
				<td width="200px;"><img src="'.$raiz.'/imagens/logo_md.jpg" /></td>
				<td width="70px;" style="text-align: left;">PROTOCOLO: </td>
				<td style="text-align: center;">
				<strong><h3>'.$protocolo.'</h3></strong>
				</td>
			</tr>
		</table>
		</br>
		<table id="dados" style="width:100%;">
			<tr>
				<td width="30%" style="text-align: right;">Data e hora do registro: </td>
				<td style="text-align: left;">'.$data.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Status: </td>
				<td style="text-align: left;">'.$status.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Vendedor: </td>
				<td style="text-align: left;">'.$vendedor.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Comprador: </td>
				<td style="text-align: left;">'.$comprador.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Evento: </td>
				<td style="text-align: left;">'.$evento.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Produto: </td>
				<td style="text-align: left;">'.$produto.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Valor: </td>
				<td style="text-align: left;">'.$valor.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Data da 1ª parcela: </td>
				<td style="text-align: left;">'.$dt_parcela.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Nº da 1ª parcela: </td>
				<td style="text-align: left;">'.$nr_parcela.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Prazo para conclusão: </td>
				<td style="text-align: left;">'.$prazo.'</td>
			</tr>


			'.$html_permanencia.'

			'.$html_venda_direta.'

			</table>

			'.$html_setor.'

			'.$html_cancelado.'

			'.$html_observacao.'


			</body>
</html>';


// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Entrou DB ' . $html);



// Gerador do PDF
$file = 'teste.pdf';

$font_termo = 'helvetica';
// $mpdf = new \Mpdf\Mpdf(['tempDir' => $raiz . '/documentos/temp','default_font' => $font_termo,'setAutoTopMargin' => 'pad']);

// $pdf->WriteHTML("<h3>".substr($_POST['nomeOperador'],0,strpos($_POST['nomeOperador'],'<'))."</h3>".$_POST['contentPDF']);
// $pdf->Output('','',$file);

$mpdf = new \Mpdf\Mpdf([
	'format'        => 'A4',
	'margin_left'   => 15,
	'margin_right'  => 15,
	'margin_top'    => 7,
	'margin_bottom' => 7,
	'margin_header' => 7,
	'margin_footer' => 4,
	'tempDir'       => $raiz . '/documentos/temp'
]);

// Set a simple Footer including the page number
$mpdf->setFooter('página {PAGENO}/{nb}');

// $mpdf->SetHTMLHeader("<img src='$link/imagens/logo_sm.jpg' />");

$mpdf->WriteHTML($html);

// $mpdf->Output('Protocolo_'.$protocolo.'_'.$data.'.pdf',\Mpdf\Output\Destination::DOWNLOAD);
$mpdf->Output('Protocolo_'.$protocolo.'_'.$data.'.pdf',\Mpdf\Output\Destination::INLINE);

exit();

?>