<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

require_once $raiz . '/vendor/autoload.php';
setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");

$font_termo = 'helvetica';
$pdf = new \Mpdf\Mpdf(['tempDir' => $raiz . '/documentos/temp','default_font' => $font_termo,'setAutoTopMargin' => 'pad']);
// $pdf->AddPage();

$pdf->SetHTMLHeader("<img src='$link/imagens/me_topo_pdf.jpg' />");
$pdf->WriteHTML("<h3>".substr($_POST['nomeOperador'],0,strpos($_POST['nomeOperador'],'<'))."</h3>".$_POST['contentPDF']);
$pdf->Output('','',$file);

?>