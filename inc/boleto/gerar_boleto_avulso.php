<?php 
error_reporting(0);

date_default_timezone_set( 'America/Sao_Paulo' );
if(isset($_GET['boleto'])){
	$boleto_id = $_GET['boleto'];
	if(!is_numeric($boleto_id)){
		echo 'Não foi passado nenhum boleto válido'; exit;
	}
}
else{echo 'Não foi passado nenhum boleto para geração.'; exit;}

$control_files=1;

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');

$layout_title = "MECOB - Gerar Boleto Avulso";
$tit_pagina   = "MECOB - Gerar Boleto Avulso";	
$tit_lista    = "MECOB - Gerar Boleto Avulso";

include_once($raiz."/inc/util.php");
require_once($raiz.'/inc/html2pdf/html2pdf.class.php');

if(!isset($_GET['segvia'])){
	include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
}

include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/boletos_avulso/boletos_avulso.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/boletos_avulso/boletos_avulso.db.php");

include_once($raiz."/inc/util.php");

$boletos_avulsoDB  = new boletos_avulsoDB();
$boletos_avulso    = new boletos_avulso();
$boletos_avulso->id = $boleto_id;
	
$file = "boleto_avulso_".$boleto_id;
$filtros=array();
$filtros['filtro_id'] = $boleto_id;

//info adicional antes da tabela
$boleto_info = $boletos_avulsoDB->lista_boletos_avulso( $conexao_BD_1, $boletos_avulso);
$boleto_info = $boleto_info[0];

$instrucoes_boleto = ""; //$boleto_info['instrucao'];

//echo '<pre>'; print_r($boleto_info); echo '</pre>'; 

if( (!empty($boleto_info['dt_credito']) && $boleto_info['dt_credito'] != '0000-00-00')){
	echo 'Boleto já liquidado';
	exit;
}

$info=array();
$info[] = "Id Boleto: ".$boleto_info['id'];
$info[] = "Data Vencimento: ".ConverteData($boleto_info['dt_vencimento']);
$info[] = "Valor: R$ ".Format( $boleto_info['vl_corrigido'],'numero');
$info[] = $boleto_info['nome']." - ".$boleto_info['cpf_cnpj'];

#print_r($info);
// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Boleto Comprador dados  ' . json_encode($boleto_info));

$nome_cliente 	  = utf8_decode($boleto_info['nome']." ".$boleto_info['cpf_cnpj']) ;
if(!empty($boleto_info['comprador_rua']))
	$endereco_cliente1 =utf8_decode( $boleto_info['comprador_rua']." ".$boleto_info['comprador_numero']);

if(!empty($boleto_info['comprador_bairro']))
	$endereco_cliente2 = utf8_decode($boleto_info['comprador_bairro'].",");

if(!empty($boleto_info['comprador_cidade']))	
	$endereco_cliente2 .= utf8_decode($boleto_info['comprador_cidade']);

if(!empty($boleto_info['comprador_estado']))	
	$endereco_cliente2 .= utf8_decode("/".$boleto_info['comprador_estado']) ;
	
if(!empty($boleto_info['comprador_cep']))		
	$endereco_cliente3 = $boleto_info['comprador_cep'];


//nome das colunas
$head=array('Parcela',utf8_decode('Correção'),'Juros',utf8_decode('Honorários'),'Corrigido', 'Vencimento', 'Pagto');

 
$content_all = "";
$cont_boletos=0; 
	
	$cont_boletos++;
	ob_start();
	//echo 'Gera boleto - parcela  '.
	$nu_parcela = 1;
	$nosso_numero = $boleto_info['nosso_numero'];
	$dt_vencimento = $boleto_info['dt_vencimento'];
	$vl_parcela = $boleto_info['vl_corrigido'];
	$vl_corrigido = $boleto_info['vl_corrigido'];
	//$contratos_id = $boleto_info['contratos_id'];
	// $descricao_boleto = "Pagamento de boleto avulso - ".$nosso_numero; //Pagamento da Parcela ".$nu_parcela." do Contrato ".$contratos_id." - ".utf8_decode( $boleto_info['descricao']);
	$descricao_boleto = "Pagamento de boleto avulso - ".$nosso_numero;
	if($boleto_info['contratos_id']) {
		$descricao_boleto .= " do Contrato ID ".$boleto_info['contratos_id'];
	}
	$descricao_boleto .= "<br>".utf8_decode( $boleto_info['descricao']);
	
	$valor_cobrado = $vl_corrigido;
	
	$dias_de_prazo_para_pagamento = 0;
	$taxa_boleto = 0;
	$data_venc = ConverteData($dt_vencimento);  
	$valor_cobrado = str_replace(",", ".",$valor_cobrado);
	$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
	
	// include($raiz."/inc/boleto/boleto_bradesco.php");
	include($raiz."/inc/boleto/boleto_unicred.php");

	$content = ob_get_clean();
	
	$content_all .= $content;
	
	// echo  $content_all;
	// exit();
	
	$novo_arquivo = $raiz.'/boletos/boleto_'.$boleto_info['id'].'pdf';  

   
$novo_arquivo = $raiz.'/boletos/boleto_'.$boleto_id.'.pdf';  

// prepara construcao do pdf

try
{
	$html2pdf = new HTML2PDF('P','A4','fr', array(0, 0, 0, 0));
	/* Abre a tela de impressão */
	//$html2pdf->pdf->IncludeJS("print(true);");
	
	$html2pdf->pdf->SetDisplayMode('real');

	// $html2pdf->setTestTdInOnePage(false); // My - Força mostrar dados em mais de uma pagina
	// $html2pdf->setModeDebug(); // My 

	/* Parametro vuehtml = true desabilita o pdf para desenvolvimento do layout */
	$html2pdf->writeHTML($content_all, isset($_GET['vuehtml']));
	
	/* Abrir no navegador */
	$html2pdf->Output($novo_arquivo);
	

}
catch(HTML2PDF_exception $e) {
	echo '<br> HTML2PDF_exception: '.$e;
	exit;
}
