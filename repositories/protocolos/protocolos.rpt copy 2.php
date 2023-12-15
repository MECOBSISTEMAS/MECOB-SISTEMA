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
require_once($raiz.'/inc/html2pdf/html2pdf.class.php');

include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php"); // Dados da Conexao_DB

$msg 		   = array();
$protocolosDB  = new protocolosDB();
$protocolos    = new protocolos();
$reflection    = new ReflectionObject($protocolos);



// Busaca os dados do protocolo
$retorno = $protocolosDB->busca_protocolo($conexao_BD_1, $id);

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - PDF chamou id '.json_encode($retorno));

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - PDF chamou id '.json_encode($retorno[0]['id']));
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
$p_cadastro = $retorno[0]['p_cadastro'];
$setor      = $retorno[0]['setor'];
$p_setor    = $retorno[0]['p_setor'];
$dt_setor   = date("d/m/Y H:i:s", strtotime( $retorno[0]['setor_trans']));
$p_finalizado  = $retorno[0]['p_finalizado'];
$dt_finalizado = date("d/m/Y H:i:s", strtotime( $retorno[0]['finalizado']));
$contrato_id   = $retorno[0]['contrato_id'];

$first_date         = new DateTime($retorno[0]['dt_registro']);
$finalizado_date    = new DateTime($retorno[0]['finalizado'] == null ? date('Y-m-d H:i:s') : $retorno[0]['finalizado']);

$permanencia = format_interval($first_date->diff($finalizado_date));

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


// Busca e monta tabela com as transferências



// Monta o HTML para o PDF
$html = '
<html>
<head>
	<!-- <title>Protocolo_contratos.pdf</title> -->
	<style>
		table {
			padding: 20px;
		}

		th, td {
			border: 0px solid black;
			padding: 2px;
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
				<td width="30%" style="text-align: right;">Data de registro: </td>
				<td style="text-align: left;">'.$data.'</td>
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
				<td width="30%" style="text-align: right;"># 1ª parcela: </td>
				<td style="text-align: left;">'.$nr_parcela.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Prazo: </td>
				<td style="text-align: left;">'.$prazo.'</td>
			</tr>

			'.$html_permanencia.'

			</table>
	</body>
</html>';





// Gerador do PDF
$file = 'teste.pdf';

$font_termo = 'helvetica';
// $mpdf = new \Mpdf\Mpdf(['tempDir' => $raiz . '/documentos/temp','default_font' => $font_termo,'setAutoTopMargin' => 'pad']);

// $pdf->WriteHTML("<h3>".substr($_POST['nomeOperador'],0,strpos($_POST['nomeOperador'],'<'))."</h3>".$_POST['contentPDF']);
// $pdf->Output('','',$file);

$mpdf = new \Mpdf\Mpdf([
	'format' => 'A4',
	'margin_left' => 15,
	'margin_right' => 15,
	'margin_top' => 15,
	'margin_bottom' => 15,
	'margin_header' => 7,
	'margin_footer' => 3,
	'tempDir' => $raiz . '/documentos/temp'
]);

// $mpdf->SetHTMLHeader("<img src='$link/imagens/logo_sm.jpg' />");

$mpdf->WriteHTML($html);

// $mpdf->Output('Protocolo_'.$protocolo.'_'.$data.'.pdf',\Mpdf\Output\Destination::DOWNLOAD);
$mpdf->Output('Protocolo_'.$protocolo.'_'.$data.'.pdf',\Mpdf\Output\Destination::INLINE);

exit();

?>