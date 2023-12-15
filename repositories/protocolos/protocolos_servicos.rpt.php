<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$id = $_GET['protocolo_id'];
$data = date('Ymdhis');

$raiz = getenv('CAMINHO_RAIZ');
$link = getenv('CAMINHO_SITE');

require_once $raiz . '/vendor/autoload.php';
setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');

include_once($raiz."/inc/util.php");
// require_once($raiz.'/inc/html2pdf/html2pdf.class.php');

include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_servicos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/protocolos/protocolos_servicos.db.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php"); // Dados da Conexao_DB

$msg 		   = array();
$protocolosDB  = new protocolosDB();
$protocolos    = new protocolos_servicos();
$reflection    = new ReflectionObject($protocolos);



// Busca os dados do protocolo
$retorno = $protocolosDB->busca_protocolo($conexao_BD_1, $id);

// Monta as variaveis para o relatório
$protocolo   = $retorno[0]['id'];

$registro    = date("d/m/Y H:i:s", strtotime( $retorno[0]['dt_registro']));
$atualizacao = date("d/m/Y H:i:s", strtotime( $retorno[0]['dt_atualizacao']));

$nome        = $retorno[0]['nome'];
$tipo        = $retorno[0]['tipo'];

if($retorno[0]['enviado'] !=  '0000-00-00') {
	$enviado = date("d/m/Y", strtotime( $retorno[0]['enviado']));
} else {
	$enviado = '';
}

if($retorno[0]['recebido'] !=  '0000-00-00') {
	$recebido = date("d/m/Y", strtotime( $retorno[0]['recebido']));
} else {
	$recebido = '';
}

if($retorno[0]['digitalizado'] !=  '0000-00-00') {
	$digitalizado = date("d/m/Y", strtotime( $retorno[0]['digitalizado']));
} else {
	$digitalizado = '';
}

if($retorno[0]['fisico'] !=  '0000-00-00') {
	$fisico = date("d/m/Y", strtotime( $retorno[0]['fisico']));
} else {
	$fisico = '';
}

// $recebido     = date("d/m/Y", strtotime( $retorno[0]['recebido']));
// $digitalizado = date("d/m/Y", strtotime( $retorno[0]['digitalizado']));
// $fisico       = date("d/m/Y", strtotime( $retorno[0]['fisico']));

$observacao = str_replace("\n", "<br>", $retorno[0]['observacao']);

$data_atual = date('d/m/Y H:i:s');

syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Entrou DB ' . json_encode($retorno[0]));

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

		#rodape {
			position: absolute;
			bottom: 30;
			}

	</style>
</head>
	<body>
		<table id="cabecalho" style="width:100%; text-align: center;">
			<tr>
				<td width="200px;"><img src="'.$raiz.'/imagens/logo_md.jpg" /></td>
				<td width="70px;" style="text-align: left;">PROTOCOLO DE SERVIÇOS:</td>
				<td style="text-align: center;">
				<strong><h3>'.$protocolo.'</h3></strong>
				</td>
			</tr>
		</table>
		</br>
		<table id="dados" style="width:100%;">

			<tr>
				<td width="30%" style="text-align: right;">Cliente: </td>
				<td style="text-align: left;">'.$nome.'</td>
			</tr>

			<tr>
				<td width="30%" style="text-align: right;">Tipo do Contrato: </td>
				<td style="text-align: left;">'.$tipo.'</td>
			</tr>

		</table>

		<table class="t_separador" id="conteudo" style="width:100%;">
			<tr>
				<th width="20%" style="text-align: center;">Enviado: </td>
				<th width="20%" style="text-align: center;">Recebido: </td>
				<th width="20%" style="text-align: center;">Digitalizado: </td>
				<th width="20%" style="text-align: center;">Físico: </td>
			</tr>

			<tr>
				<td style="text-align: center;">'.$enviado.'</td>
				<td style="text-align: center;">'.$recebido.'</td>
				<td style="text-align: center;">'.$digitalizado.'</td>
				<td style="text-align: center;">'.$fisico.'</td>
			</tr>


		</table>


		'.$html_observacao.'

		<div id="rodape">
			<table id="rodape" style="width:100%;">

				<tr>
					<td width="75%" style="text-align: right;">Data/hora do registro: </td>
					<td style="text-align: left;">'.$registro.'</td>
				</tr>

				<tr>
					<td width="75%" style="text-align: right;">Data/hora última atualização: </td>
					<td style="text-align: left;">'.$atualizacao.'</td>
				</tr>

			</table>
		</div>

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
$mpdf->Output('Protocolo_Servicos_'.$protocolo.'_'.$data.'.pdf',\Mpdf\Output\Destination::INLINE);

exit();

?>