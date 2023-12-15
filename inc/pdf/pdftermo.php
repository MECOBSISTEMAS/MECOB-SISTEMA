<?php
// require_once('fpdf_html.php');
require_once $raiz . '/vendor/autoload.php';

setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");




	
function pdftermo($contrato,$file,$link,$parcelas=[],$raiz = '',$word = false)
{ 
//echo '<pre>';
//print_r($contrato);
//echo '</pre>';

// puxar do contrato ************** ###review
$vendedor_nome = trim(str_replace(' ','   ',mb_strtoupper($contrato['vendedor_nome'],mb_internal_encoding())));
$vendedor_nacionalidade = strtolower($contrato['vendedor_nacionalidade']);
$vendedor_cpf = $contrato['vendedor_cpf_cnpj'];
$vendedor_rua = $contrato['vendedor_rua'];
$vendedor_numero = $contrato['vendedor_numero'];
$vendedor_complemento = $contrato['vendedor_complemento'];
$vendedor_bairro = $contrato['vendedor_bairro'];
$vendedor_cidade = $contrato['vendedor_cidade'];
$vendedor_uf = $contrato['vendedor_estado'];
$vendedor_cep = $contrato['vendedor_cep'];

$comprador_nome = trim(str_replace(' ','   ',mb_strtoupper($contrato['comprador_nome'],mb_internal_encoding())));
$comprador_nacionalidade = strtolower($contrato['comprador_nacionalidade']);
$comprador_cpf = $contrato['comprador_cpf_cnpj'];
$comprador_rua = $contrato['comprador_rua'];
$comprador_numero = $contrato['comprador_numero'];
$comprador_complemento = $contrato['comprador_complemento'];
$comprador_bairro = $contrato['comprador_bairro'];
$comprador_cidade = $contrato['comprador_cidade'];;
$comprador_uf = $contrato['comprador_estado'];
$comprador_cep = $contrato['comprador_cep'];

$fiador = $contrato['fiador'];
$animal = $contrato['animal'];

$lote = $animal;

$valor_contrato = Format($contrato['vl_contrato'],'numero');

$percentual_contrato = $contrato['termo_percentual_contrato'];

$loste_desc = $contrato['termo_descricao_lote'];

$desc_pagto =  $contrato['termo_descricao_pagto'];

$local_data = $contrato['termo_local_data'];

$tabelaParcelas = "
	<table style='width:100%;' border='1' cellpadding='5' cellspacing='0'>
		
			<tr>
				<td>Parcela</td>
				<td>Valor</td>
				<td>Vencimento</td>
			</tr>
	";

foreach ($parcelas as $key => $value) {
	$parcela = $value['nu_parcela'];
	$valor = $value['vl_parcela'];
	$vencimento = ConverteData($value['dt_vencimento']);
	$tabelaParcelas .= "
		<tr>
			<td>$parcela</td>
			<td>$valor</td>
			<td>$vencimento</td>
		</tr>
	";
}

$tabelaParcelas .= "
	</table>";

// puxar do contrato **************
	
	// $pdf=new PDF_HTML();
	
	$font_termo = 'helvetica';
	$pdf = new \Mpdf\Mpdf(['tempDir' => $raiz . '/documentos/temp','default_font' => $font_termo,'setAutoTopMargin' => 'pad']);
	// $pdf->AddPage();

	$pdf->SetHTMLHeader("<img src='$link/imagens/me_topo_pdf.jpg' />");
	
	$lt=0;
	

	if($lt>0){$pdf->AddPage(); }
		
	//CABECALHO
	// $pdf->Image($link."/imagens/me_topo_pdf.jpg"); 
	// $pdf->Ln(15);	
	
	// Info
	// $pdf->SetTextColor( 0, 0, 0);
	// $pdf->SetFont($font_termo,'BU',13);
	// $pdf->SetLeftMargin(40);	
	// $pdf->Cell(125,7,utf8_decode('INSTRUMENTO PARTICULAR DE CONFISSÃO DE DÍVIDA'),0,1,'C');
	// $pdf->SetLeftMargin(10);
	// $pdf->Ln(10);
	
	// Paragrafos
	
	$paragrafos = array();
	
	$lote = str_replace(' ', '   ',$lote);
	
	// $paragrafos[] = "<img src='$link/imagens/me_topo_pdf.jpg' />";

	$paragrafos[] = "<h3 style='text-decoration:underline;text-align:center'>INSTRUMENTO PARTICULAR DE CONFISSÃO DE DÍVIDA</h3>";

	// Credor
	$paragrafos[] = "<strong>CREDOR  - <u>".$vendedor_nome."</u>:</strong> ".$vendedor_nacionalidade.", inscrito no CPF sob o n° ".$vendedor_cpf.", residente e domiciliado na ".$vendedor_rua.", n° ".$vendedor_numero." ".$vendedor_complemento.", Bairro ".$vendedor_bairro.", ".$vendedor_cidade."/".$vendedor_uf." - CEP ".$vendedor_cep;
	
	// Devedor
	$paragrafos[] = "<strong>DEVEDOR  - <u>".$comprador_nome."</u>:</strong> ".$comprador_nacionalidade.", inscrito no CPF sob o n° ".$comprador_cpf.", residente e domiciliado na ".$comprador_rua.", n° ".$comprador_numero." ".$comprador_complemento.", Bairro ".$comprador_bairro.", ".$comprador_cidade."/".$comprador_uf." - CEP ".$comprador_cep;

	//FIADOR
	if (strlen($fiador) > 0){ 
		$paragrafos[]  = "<strong>FIADOR(A): </strong> $fiador";
	}
	
	//inicio
	$paragrafos[] =  "Têm entre si, como justo e acertado, o presente Instrumento Particular de Confissão de Dívida, mediante as seguintes cláusulas e condições:";
	
	//animal
	if (strlen($animal) > 0){ 
		//clausula 1
		$paragrafos[] =  "<strong>CLÁUSULA  PRIMEIRA - <u>DO   OBJETO:</u></strong> o DEVEDOR reconhece, confessa e declara, por este Instrumento Particular de Confissão de Dívida, dever ao CREDOR a quantia de <strong>R$  ".$valor_contrato."</strong>.";
	}
	
	//clausula 1 - paragrafo unico
	$paragrafos[] =  "<strong>PARÁGRAFO  ÚNICO - <u>DA  ORIGEM  DA  DÍVIDA:</u></strong> a dívida ora confessada é oriunda do inadimplemento da compra realizada pelo DEVEDOR frente ao CREDOR, a qual teve como objeto contratual ".$percentual_contrato." das cotas do animal <strong>'".$lote."'</strong>, ".$loste_desc;
	
	//clausula 2
	$paragrafos[] =  "<strong>CLÁUSULA  SEGUNDA - <u>DA  FORMA  DE  PAGAMENTO:</u></strong> o valor de R$  ".$valor_contrato.", reconhecido e confessado na Cláusula Primeira, será pago conforme tabela abaixo";

	$paragrafos[] = $tabelaParcelas;

	//clausula 2  - PARÁGRAFO PRIMEIRO
	$paragrafos[] = "<strong>PARÁGRAFO  PRIMEIRO - <u>DO  LOCAL  DE  PAGAMENTO:</u></strong> os pagamentos descritos na Cláusula Segunda deverão ser efetuados por meio de boleto bancário, servindo cada qual como comprovante de pagamento do DEVEDOR, os quais serão compensados na conta da empresa Motta & Etchepare Ltda, transação já devidamente autorizada pelo CREDOR. ";
	
	//clausulas seguintes
	$paragrafos[] = "<strong>PARÁFRAFO  SEGUNDO - <u>DA  EXISTÊNCIA  DE  PROTESTO:</u></strong> somente ao ser efetuado o pagamento da primeira parcela, o CREDOR procederá à retirada de protesto, caso este tenha sido anteriormente efetuado.";
	
	$paragrafos[] = "<strong>CLÁUSULA  TERCEIRA - <u>DAS   PENALIDADES:</u></strong> qualquer quantia devida por força deste Instrumento, vencida e não paga (mesmo que parcialmente) será considerada em mora, sendo o débito sujeito a juros de mora de 1% (um por cento) ao mês ou fração, juros remuneratórios calculados pela variação positiva do Índice Geral de Preços - Mercado (IGP-M), calculado até a data do efetivo pagamento e multa de 2% (dois por cento) sobre o montante apurado.";
	
	$paragrafos[] = "<strong>PARÁGRAFO  PRIMEIRO - <u>DA  BUSCA  E  APREENSÃO  DO  ANIMAL:</u></strong> caso o DEVEDOR não arque com o pagamento de qualquer das parcelas por mais de 30 (trinta) dias, e não optando o CREDOR pelo recebimento dos valores vencidos e não pagos com acréscimo dos encargos, este efetuará a imediata busca e apreensão do animal <strong>'".$lote."'</strong>, devendo o DEVEDOR colocar o equino a disposição para retirada e realizar todos os exames de praxe, sob pena de multa diária no importe de 01 (um) salário mínimo vigente. ";
	
	$paragrafos[] = "<strong>PARÁGRAFO  SEGUNDO - <u>DA  QUITAÇÃO  DAS  PARCELAS  INADIMPLENTES:</u></strong> somente quitar-se-á parcela em atraso, com o depósito do valor original da prestação acrescida das correções dispostas na Cláusula Terceira, ficando enquanto inadimplente, sempre a mercê da correção descrita no caput desta Cláusula.";
	
	$paragrafos[] = "<strong>PARÁGRAFO  TERCEIRO - <u>DA MULTA E VENCIMENTO ANTECIPADO:</u></strong> o descumprimento por mais de 30 (trinta) dias na data de qualquer das parcelas acordadas, acarretará no vencimento antecipado da dívida em sua totalidade, apregoando-se multa penal não compensatória de <strong>20% (vinte por cento)</strong>, em face da violação obrigatória contratual sobre o total do débito, independentemente de qualquer aviso ou notificação.";
	
	$paragrafos[] = "<strong>PARÁGRAFO  QUARTO - <u>DO   PROTESTO:</u></strong> por força deste Instrumento, os boletos vencidos e não pagos poderão ser levados a protesto automático 05 (cinco) dias após o vencimento dos títulos.";
	
	$paragrafos[] = "<strong>CLÁUSULA  QUARTA - <u>DA  TRANSFERÊNCIA  DEFINITIVA:</u></strong> somente após o pagamento integral do valor descrito na Cláusula Primeira o CREDOR efetuará a transferência definitiva do animal ao DEVEDOR. ";
	
	$paragrafos[] = "<strong>CLÁUSULA  QUINTA - <u>DO  PAGAMENTO  POR  TERCEIROS:</u></strong> qualquer terceiro que pagar o débito (totalidade do contrato) do DEVEDOR, em seu nome ou por conta deste, ficará automaticamente sub-rogado em todos os direitos do CREDOR.";
	
	$paragrafos[] = "<strong>CLÁUSULA  SEXTA - <u>DA   EXECUÇÃO   JUDICIAL:</u></strong> ocorrendo qualquer infração ao disposto neste Instrumento e que se expressarem em moeda corrente, serão exigíveis por meio de Processo de Execução, cujo título executivo será o presente contrato, consoante o que orienta o Código de Processo Civil.";
	
	$paragrafos[] = "<strong>CLÁUSULA  SÉTIMA - <u>DOS  HONORÁRIOS  ADVOCATÍCIOS:</u></strong> havendo necessidade de se recorrer a meios judiciais para dirimir qualquer dúvida ou questão relacionada com este contrato, a parte vencida responderá pelas despesas do processo e pelos honorários advocatícios no montante  de  20%.";
	
	$paragrafos[] = "<strong>CLÁUSULA  OITAVA - <u>DA  TOLERÂNCIA  OU  NOVAÇÃO:</u></strong> eventual tolerância pelas partes do presente Instrumento, não implicará em hipótese alguma, qualquer modificação ou novação das obrigações aqui contidas, nem tampouco em precedentes para novas concessões.";
	
	$paragrafos[] = "<strong>CLÁSULA  NONA - <u>DA  CESSÃO  DESTE  CONTRATO:</u></strong> as partes contratantes não poderão ceder os seus direitos e obrigações sob este Instrumento sem a prévia e expressa autorização escrita da outra parte.";
	
	$paragrafos[] = "<strong>CLÁUSULA  DÉCIMA - <u>FORO  DE  ELEIÇÃO:</u></strong> para dirimir toda e qualquer controvérsia oriunda da interpretação ou execução do presente contrato, as partes elegem o foro da Comarca do CREDOR, renunciando a qualquer outro por mais privilegiado que seja, contudo, reserva-se exclusivamente ao CREDOR o direito de escolher demandar no foro do DEVEDOR, caso seja de seu interesse processual.";
	
	// $paragrafos = array_map("utf8_decode", $paragrafos );
	
	
	
	// $pdf->SetLeftMargin(20);
	// $pdf->SetFont($font_termo,'',11);
	foreach($paragrafos as $paragrafo){
		// $y = $pdf->GetY();
		// if($y>240){
		// 	$pdf->AddPage();   // SE ULTRAPASSADO, É ADICIONADO UMA PÁGINA
		// 	// $pdf->Image($link."/imagens/me_topo_pdf.jpg"); 
		// 	$pdf->Ln(15);
		// 	$y = $pdf->GetY();
		// }
		
		//$pdf->MultiCell(0,7,$paragrafo);
		$parsed = 1;
		//$pdf->WriteHTML($paragrafo,$parsed );
		//$pdf->Multicell(0,7, $parsed );
		
		// $pdf->WriteHtmlCell(170, $paragrafo );
		
		// $pdf->Ln(13);
	}
	
	//ultima página
	// $pdf->Image($link."/imagens/me_topo_pdf.jpg"); 
	// $pdf->Ln(5);
	
	// $pdf->MultiCell(0,7,utf8_decode($final));
	// $pdf->Ln(5);
	// $pdf->MultiCell(160,7,utf8_decode($local_data) ,0,'C');
	// $pdf->Ln(20);
	
	// //assinaturas
	// $pdf->SetFont($font_termo,'B',12);
	// $pdf->SetLeftMargin(50);
	// $pdf->Cell(100,7,'  ','B',10,'C');
	// $pdf->SetLeftMargin(10);
	// $pdf->Cell(100,7,utf8_decode($vendedor_nome),0,10,'C');
	// $pdf->SetFont($font_termo,'',12);
	// $pdf->Cell(100,7,'CREDOR',0,10,'C');
	// $pdf->Ln(15);
	
	
	// $pdf->SetFont($font_termo,'B',12);
	// $pdf->SetLeftMargin(50);
	// $pdf->Cell(100,7,'  ','B',10,'C');
	// $pdf->SetLeftMargin(10);
	// $pdf->Cell(100,7,utf8_decode($comprador_nome),0,10,'C');
	// $pdf->SetFont($font_termo,'',12);
	// $pdf->Cell(100,7,'DEVEDOR',0,10,'C');
	// $pdf->Ln(15);
	
	// // testemunhas 
	// $pdf->SetLeftMargin(20);
	// $pdf->Cell(0,10,'Testemunhas:',0,10,'L');
	// $pdf->Ln(10);
	// $pdf->Cell(0,10,'Nome:',0,10,'L');
	// $pdf->Cell(120,-10,'Nome:',0,10,'R');
	// $pdf->Ln(20);
	// $pdf->Cell(0,10,'CPF:',0,10,'L');
	// $pdf->Cell(117,-10,'CPF:',0,10,'R');
	

  	//// fim
	if(substr($file,-4) != '.pdf'){$file.='.pdf';}
	$paragrafos_imploded = (implode('<br/><br/>',$paragrafos));
	$assinaturas = "Estando justas e contratadas, as partes firmam o presente Instrumento em 03 (três) vias de igual teor e forma na presença de duas testemunhas, para que surta seus jurídicos e legais efeitos. (1ª via Credor, 2ª via Devedor, 3ª Via Escritório).
	<br/>
	<br/>
	$local_data
	<br/>
	<h4 style='margin-top:150px;text-align:center'>
		_______________________________________________________________
		<br/>
		$vendedor_nome
		<br/>
		CREDOR
		<br/>
		<br/>
		<br/>
		_______________________________________________________________
		<br/>
		$comprador_nome
		<br/>
		DEVEDOR
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		
	</h4>	
	<table style='width:100%'>
		<tr>
			<td style='height:50px' colspan='2'>Testemunhas:</td>
		</tr>
		<tr>				
			<td style='height:50px'>Nome:</td>
			<td style='height:50px'>Nome:</td>
		</tr>
		<tr>
			<td style='height:50px'>CPF:</td>
			<td style='height:50px'>CPF</td>
		</tr>
	</table>
	";
	if ($word) {
		$_SESSION['termoword'] = $paragrafos_imploded.$assinaturas;
		header("Location:".getenv('CAMINHO_SITE')."/inc/word/gera_word_termo.php");
	} else {
		$pdf->WriteHTML($paragrafos_imploded);
		$pdf->AddPage();
		$pdf->WriteHTML($assinaturas);
	  	$pdf->Output('','',$file);
	}
}



?>