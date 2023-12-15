<?php



require('tfpdf.php');
setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");

class ttFPDF extends tFPDF
{
  // Tabela dinâmica
  function MyTable($registros,$colWidth,$saldo_inicial)
  {

	$linhas=$ct =0;
	$total_reg = count($registros);
	$vl_soma = $saldo_inicial;
	$cor_aux=1;
	
	$x = $x_ini = $this->GetX();
	$y = $this->GetY();
		
    foreach($registros as $body)
	{
		#echo "<br><br> linha $ct";
		$altura_linha=6;
		$cont_altura_linha=1;
		
		$ct++;
		$colunas = 5;
		
		if($ct%2==0){
			$cor_aux=1;
			$this->SetFillColor(250,250,250);
		}
		else{
			$cor_aux=2;
			$this->setFillColor(230,230,230);
		}
		
		$this->SetXY($x = $x_ini, $y);
		
		$sinal = "";
		if($body['tipo'] == 'DÉBITO'){$sinal='-';}
		
		$categoria = $body['categoria'];
		if(trim($categoria) == 'PEDIDOS - RECEBIMENTO DE PARCELAS'){$categoria='PEDIDOS';}
		elseif(trim($categoria) == 'NOTAS - PAGAMENTO DE FORNECEDORES'){$categoria='FORNECEDORES';}
		

		
		if(!empty($body['dt_pagamento']))
			$this->MultiCell($colWidth[0],6,ConverteData($body['dt_pagamento']),0,'C',true); 
		else
			$this->MultiCell($colWidth[0],6,ConverteData($body['dt_vencimento']),0,'C',true); 
		$this->SetXY($x = $colWidth[0]+10, $y);
		$this->MultiCell($colWidth[1],6,utf8_decode(substr($categoria." - ".$body['descricao'],0,60)),0,'L',true); 
		$this->SetXY($x += $colWidth[1], $y);
		$this->MultiCell($colWidth[2],6,'R$ '.$sinal.Format($body['vl_lancamento'],'numero'),0,'R',true); 
		$this->SetXY($x += $colWidth[2], $y);
		
		
		$vl_soma += $sinal.$body['vl_lancamento'];
		$this->MultiCell($colWidth[3],6,'R$ '.Format($vl_soma,'numero'),0,'R',true); 
		$this->SetXY(0, $y);
		$x=0;
		$this->Ln();
		
		//pula linha
		$y = $this->GetY();
		if ( $y >= 270) {  # 
			$this->AddPage();
			$y = 0; // should be your top margin
			$x = $x_ini;
		}
		
		
	} 
	
	##SALDO FINAL
	if($cor_aux==1){
		$this->setFillColor(230,230,230);
	}
	else{
		$this->SetFillColor(250,250,250);
	}
	$this->Cell($colWidth[0],7,'',0,0,'C',true);
	$this->Cell($colWidth[1],7,'SALDO FINAL',0,0,'L',true);
	$this->Cell($colWidth[2],7,'',0,0,'C',true);
	$this->Cell($colWidth[3],7,'R$ '.Format($vl_soma,'numero'),0,0,'R',true);
	$this->Ln();
	
	  return $vl_soma;
	
  }

  
}


	
function pdfted($cabecalho,$info2, $tamcolunas, $ted_id,$info,$parcelas,$lancamentos,$file,$link)
{ 	
	#print_r($fluxo);
	#print_r($proximos);
	$pdf=new ttFPDF();
	
	$pdf->AddPage();
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->SetFont('DejaVu','','DejaVuSansCondensed.ttf');
	

	$lt=0;

	if($lt>0){$pdf->AddPage(); }
		
		
	####CABECALHO
	if(count($cabecalho)>0){
		$ln_cabecalho=0;
		foreach($cabecalho as $it_cabecalho){
			if($it_cabecalho == 'imagem'){
				$pdf->SetFont('Arial','B',12);
				// logo 
				$pdf->Image($link."/imagens/logo_md.jpg"); 
					  
				// fonte do titulo
				#$pdf->SetTextColor(		211, 211, 211);
				$pdf->SetFontSize(18);	
				$ln_cabecalho=50;				
			}
			else{
				$pdf->Cell(0,-$ln_cabecalho,utf8_decode($it_cabecalho ),0,1,'C');
			}
			
		}
		$pdf->Ln($ln_cabecalho);
	}

	/// Info
	$pdf->SetFont('Arial','',12);
	
	$pdf->Cell(180,7,utf8_decode('TED agendada para '.ConverteData($info['dt_ted'])),0,0,'L',false); $pdf->Ln();
	$pdf->Cell(180,7,utf8_decode('Valor R$'.Format($info['vl_ted'],'numero') ),0,0,'L',false); $pdf->Ln();
	$pdf->Cell(180,7,utf8_decode('Beneficiário:  '.$info['nome']." - ".$info['cpf_cnpj']),0,0,'L',false); $pdf->Ln();
	
	
	$domicilio = 'Banco: '.$info['banco'];
	$domicilio .= '   Agência: '.$info['agencia']."-".$info['dv_agencia'];
	$domicilio .= '   Conta: '.$info['conta']."-".$info['dv_conta'];
	$pdf->Cell(180,7,utf8_decode($domicilio),0,0,'L',false); $pdf->Ln();
	
	$pdf->SetFont('Arial','',10);
	$cadatrada = "Cadastrada em ".ConverteData($info['dt_inclusao'])." por ".$info['nome_incluiu'];
	if(!empty($info['doc_incluiu'])) $cadatrada .= " ( ". $info['doc_incluiu'] ." ) "; 
	$pdf->Cell(180,7,utf8_decode($cadatrada),0,0,'L',false);  $pdf->Ln();
	$pdf->Ln();
	
	$pdf->SetFont('Arial','',12);
	// Parcelas
	$pdf->Cell(180,7,utf8_decode('Parcelas:'),0,0,'L',false); $pdf->Ln();
	$pdf->SetFont('Arial','',10);
	$cont_parc= 0;
	foreach($parcelas as $parcela){ 
		$cont_parc++;
		if($parcela['contratos_id'] == 'adimplencia' && empty($parcela['contratos_id_pai'])){
			$honor = (($parcela['honor_adimp'] / 100) *  $parcela['vl_pagto'] );
		}
		else{
			$honor = $parcela['vl_pagto']-( $parcela['vl_pagto'] / (1+ ($parcela['honor_adimp'] / 100)  ));
		}
				
		$info_parcela  = 'Contrato '.$parcela['contratos_id'];
		$info_parcela .= ' - Parcela: '.$parcela['nu_parcela'];
		$info_parcela .= ' - Valor: R$ '.Format($parcela['vl_pagto']-$honor,'numero');
		$info_parcela .= ' ( R$ '.Format($parcela['vl_pagto'],'numero');
		$info_parcela .= ' - R$ '.Format($honor,'numero').' honorários )'; 
		
		$pdf->Cell(180,7,utf8_decode($info_parcela),0,0,'L',false); 
		$pdf->Ln();
	}
	if(!$cont_parc){
		$pdf->Cell(180,7,utf8_decode('Nenhuma parcela nesta TED'),0,0,'L',false);
		$pdf->Ln();
	}
	
	
	// Lançamentos
	$pdf->Ln();
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(180,7,utf8_decode('Outros Lançamentos:'),0,0,'L',false); $pdf->Ln();
	$pdf->SetFont('Arial','',10);
	$cont_lanc = 0;
	foreach($lancamentos as $lancamento){ 
		$cont_lanc++;
		$info_lancamento  = 'Tipo '.$lancamento['tipo'];
		$info_lancamento .= ' - Valor: R$ '.Format($lancamento['valor'],'numero');
		$info_lancamento .= ' OBS: '.$lancamento['obs']; 
		$pdf->Cell(180,7,utf8_decode($info_lancamento),0,0,'L',false); 
		$pdf->Ln();
	}
	
	if(!$cont_lanc)
		$pdf->Cell(180,7,utf8_decode('Nenhum lançamento nesta TED'),0,0,'L',false);
	

 if(!empty($info['log_zerar'])){
	 	$pdf->Ln(10);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(180,7,utf8_decode('* '.$info['log_zerar']),0,0,'L',false);
 }
	  

  //// fim
  if(substr($file,-4) != '.pdf'){$file.='.pdf';}
  $pdf->Output('','',$file);
}

function string_lengt_to_part($string ,$tamanho , &$total_linhas ){
	#echo "<br> chamou para $string ";
	if(strlen($string)<=$tamanho){  
		#echo "<br> return: $string ";
		return $string;
	}
	$total_linhas++;
	#echo "<br> atual: ".
	$atual = substr($string,0,$tamanho); #."\r\n";
	return $retorno =  $atual.string_lengt_to_part(substr($string,$tamanho) ,$tamanho, $total_linhas);
}

?>