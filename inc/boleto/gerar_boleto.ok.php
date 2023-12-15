<?php 
error_reporting(0);

date_default_timezone_set( 'America/Sao_Paulo' );
if(isset($_GET['id'])){
	$contrato_id = $_GET['id']; 
	if(!is_numeric($contrato_id)){
		echo 'Não foi passado nenhum contrato válido'; exit;
	}
}
else{echo 'Não foi passado nenhum contrato para geração dos boletos'; exit;}

$parcela_id="";
if(isset($_GET['p'])){
	$parcela_id = $_GET['p'];
}


$control_files=1;

$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');

$layout_title = "MECOB - Gerar Boletos";
$tit_pagina   = "MECOB - Gerar Boletos";	
$tit_lista    = "MECOB - Gerar Boletos";

include_once($raiz."/inc/util.php");
require_once($raiz.'/inc/html2pdf/html2pdf.class.php');

if(!isset($_GET['segvia'])){
	include_once(getenv('CAMINHO_RAIZ')."/valida_acesso.php");
}

include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.db.php");

include_once($raiz."/inc/util.php");

$contratosDB  = new contratosDB();
$contratos    = new contratos();
$contratos->id = $contrato_id;
	
$file = "boleto_contrato_".$contrato_id;
$filtros=array();
$filtros['filtro_id'] = $contrato_id;

//info adicional antes da tabela
$contrato_info = $contratosDB->lista_contratos($contratos, $conexao_BD_1);
$contrato_info = $contrato_info[0];

$instrucoes_boleto = $contrato_info['instrucao'];

//echo '<pre>'; print_r($contrato_info); echo '</pre>'; exit;

$info=array();
$info[] = "Id Contrato: ".$contrato_info['id'];
$info[] = "Data contrato: ".ConverteData($contrato_info['dt_contrato']);
$info[] = "Valor Original: R$ ".Format( $contrato_info['vl_contrato'],'numero');
$info[] = "Vendedor: ".$contrato_info['vendedor_nome']." - ".$contrato_info['vendedor_email']." - ".$contrato_info['vendedor_cpf_cnpj'];
$info[] = "Comprador: ".$contrato_info['comprador_nome']." - ".$contrato_info['comprador_email']." - ".$contrato_info['comprador_cpf_cnpj'];
#print_r($info);

$nome_cliente 	  = utf8_decode($contrato_info['comprador_nome']." ".$contrato_info['comprador_cpf_cnpj']) ;
if(!empty($contrato_info['comprador_rua']))
	$endereco_cliente1 =utf8_decode( $contrato_info['comprador_rua']." ".$contrato_info['comprador_numero']);

if(!empty($contrato_info['comprador_bairro']))
	$endereco_cliente2 = utf8_decode($contrato_info['comprador_bairro'].",");

if(!empty($contrato_info['comprador_cidade']))	
	$endereco_cliente2 .= utf8_decode($contrato_info['comprador_cidade']);

if(!empty($contrato_info['comprador_estado']))	
	$endereco_cliente2 .= utf8_decode("/".$contrato_info['comprador_estado']) ;
	
if(!empty($contrato_info['comprador_cep']))		
	$endereco_cliente3 = $contrato_info['comprador_cep'];


//nome das colunas
$head=array('Parcela',utf8_decode('Correção'),'Juros',utf8_decode('Honorários'),'Corrigido', 'Vencimento', 'Pagto');

// linhas
$parcelas = $contratosDB->lista_parcelas_contratos($contrato_id, $conexao_BD_1,$parcela_id );
//echo '<pre>'; print_r($parcelas); echo '</pre>'; exit;

$content_all = "";
$cont_boletos=0;
foreach($parcelas as $parcela){
	if( (!empty($parcela['dt_pagto']) && $parcela['dt_pagto'] != '0000-00-00') || (!empty($parcela['vl_pagto']) &&  $parcela['vl_pagto'] > 0)){
		continue;
	}
	$cont_boletos++;
	ob_start();
	//echo 'Gera boleto - parcela  '.
	$nu_parcela = $parcela['nu_parcela'];
	$nosso_numero = $parcela['id'];
	$dt_vencimento = $parcela['dt_vencimento'];
	$vl_parcela = $parcela['vl_parcela'];
	$vl_corrigido = $parcela['vl_corrigido'];
	$contratos_id = $parcela['contratos_id'];
	$descricao_boleto = "Pagamento da Parcela ".$nu_parcela." do Contrato ".$contratos_id." - ".utf8_decode( $contrato_info['descricao']);
	
	$valor_cobrado = $vl_parcela;
	if(!empty($vl_corrigido) && $vl_corrigido>0){
		$valor_cobrado = $vl_corrigido;
	}
	
	$dias_de_prazo_para_pagamento = 0;
	$taxa_boleto = 0;
	$data_venc = ConverteData($dt_vencimento);  
	$valor_cobrado = str_replace(",", ".",$valor_cobrado);
	$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
	
	// include($raiz."/inc/boleto/boleto_bradesco.php");
	if($contrato_id <= 12460) {
		// Gera boleto Bradesco conforme o arquivo de remessa
		include($raiz."/inc/boleto/boleto_bradesco.php");
	} else {
		// Gera boleto UNICRED 
		include($raiz."/inc/boleto/boleto_unicred.php");
	}

	$content = ob_get_clean();
	$content_all .= $content;
    // echo $content_all;
	// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Boletos ' . json_encode($content));
	
	$novo_arquivo = $raiz.'/boletos/bol_'.$contrato_id.'_p'.$nu_parcela.'.pdf'; 
}

if(empty($cont_boletos)){
	echo '<h4>Não foi possível baixar este boleto - verifique com a administração.</h4>';
	exit;
}

if(empty($parcela_id)){
	$novo_arquivo = $raiz.'/boletos/bol_'.$contrato_id.'_all.pdf'; 
}

// prepara construcao do pdf

try
{
	$html2pdf = new HTML2PDF('P','A4','fr', array(0, 0, 0, 0));
	/* Abre a tela de impressão */
	//$html2pdf->pdf->IncludeJS("print(true);");
	
	$html2pdf->pdf->SetDisplayMode('real');
	
	/* Parametro vuehtml = true desabilita o pdf para desenvolvimento do layout */
	$html2pdf->writeHTML($content_all, isset($_GET['vuehtml']));
	
	/* Abrir no navegador */
	$html2pdf->Output($novo_arquivo);
	

}
catch(HTML2PDF_exception $e) {
	echo '<br> HTML2PDF_exception: '.$e;
	exit;
}
