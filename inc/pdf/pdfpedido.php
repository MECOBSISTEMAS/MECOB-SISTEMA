<?php

require('tfpdf.php');
setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');

class ttFPDF extends tFPDF
{
  // Tabela dinâmica
  function MyTable($first,$produtos,$colWidth,$link,$desconto)
  {
//  	echo "<pre>";
//	print_r($produtos);
//	echo "</pre>";

	//First
	$this->SetFillColor(	211, 211, 211);
	#$this->SetTextColor(	255, 255, 255);
    for($i=0;$i<count($first);$i++){
      $this->Cell($colWidth[$i],7,$first[$i],0,0,'C',true);
	}
    $this->Ln();
    //Data
	$linhas=0;
	$total_reg = count($produtos);
	$ct = 0 ;
	$sum_quantidade = $sum_subtotal = $sum_total= $sum_desconto = 0;
	
    foreach($produtos as $produto)
	{
		$ct++;
		$colunas = count($first);
		
		if($ct%2==0){
			$this->setFillColor(230,230,230);
		}
		else{
			$this->SetFillColor(250,250,250);
		}
		#$this->SetTextColor(	50, 54, 57); 
		$this->Cell($colWidth[0],6,utf8_decode($produto['codigo']." - ".$produto['nome']),0,0,'L',true); 
		$this->Cell($colWidth[1],6,"R$ ".$produto['valor_unitario'],0,0,'C',true); 
		$this->Cell($colWidth[2],6,$produto['quantidade'],0,0,'C',true); 
		$this->Cell($colWidth[3],6,"R$ ".Format($produto['valor_unitario']*$produto['quantidade'],'numero'),0,0,'C',true); 
		$this->Cell($colWidth[4],6,"R$ ".Format($produto['desconto'],'numero'),0,0,'C',true); 
		$this->Cell($colWidth[5],6,"R$ ".Format($produto['valor_total'],'numero'),0,0,'C',true); 
		
		$sum_quantidade+=$produto['quantidade'];
		$sum_subtotal+=$produto['valor_unitario']*$produto['quantidade'];
		$sum_desconto+=$produto['desconto'];
		$sum_total+=$produto['valor_total'];
		
		
//		for($i=0; $i<$colunas; $i++)
//		  {
//			  if($i==1){$align = "C";}
//			  elseif($i>3){
//				  $align = "R";
//				  if(  substr($registro[$i],-1)=='D'  ){
//				  	#$this->SetTextColor(128,0,0);
//				  }
//			  }
//			  else{$align = "C";}
//			  $this->Cell($colWidth[$i],6,'teste',0,0,$align,true); 
//			  
//		  }
		$this->Ln();
	} 
	$this->SetFillColor(	211, 211, 211);
	#$this->SetTextColor(	255, 255, 255);
	
	if(!empty($desconto) && is_numeric($desconto) && $desconto>0) {
		$final1 = 'Subtotal';}else{$final1 = 'Total';
	}
	$this->Cell($colWidth[0],7,$final1,0,0,'L',true);
	$this->Cell($colWidth[1],7,'',0,0,'C',true);
	$this->Cell($colWidth[2],7,$sum_quantidade,0,0,'C',true);
	$this->Cell($colWidth[3],7,'R$ '.Format($sum_subtotal,'numero'),0,0,'C',true);
	$this->Cell($colWidth[4],7,'R$ '.Format($sum_desconto,'numero'),0,0,'C',true);
	$this->Cell($colWidth[5],7,'R$ '.Format($sum_total,'numero'),0,0,'C',true);
	
	if(!empty($desconto) && is_numeric($desconto) && $desconto>0) {
		$this->Ln();
		$this->Cell($colWidth[0],7,'Desconto final',0,0,'L',true);
		$this->Cell($colWidth[1],7,'',0,0,'C',true);
		$this->Cell($colWidth[2],7,'',0,0,'C',true);
		$this->Cell($colWidth[3],7,'',0,0,'C',true);
		$this->Cell($colWidth[4],7,'',0,0,'C',true);
		$this->Cell($colWidth[5],7,'- R$ '.Format($desconto,'numero'),0,0,'C',true);
		
		$this->Ln();
		$this->Cell($colWidth[0],7,'Valor final do pedido',0,0,'L',true);
		$this->Cell($colWidth[1],7,'',0,0,'C',true);
		$this->Cell($colWidth[2],7,'',0,0,'C',true);
		$this->Cell($colWidth[3],7,'',0,0,'C',true);
		$this->Cell($colWidth[4],7,'',0,0,'C',true);
		$this->Cell($colWidth[5],7,'R$ '.Format($sum_total-$desconto,'numero'),0,0,'C',true);
	}
	

	
  }
}


	
function pedido_pdf($link, $pedido, $produtos,$cliente,$parcelas)
{ 
//	print_r($pedido); echo "<br><br>";
//	print_r($cliente);echo "<br><br>";
//	print_r($produtos);echo "<br><br>";
//	print_r($parcelas);echo "<br><br>";
//	exit;
	
	$pdf=new ttFPDF();
	
	$pdf->AddPage();
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->SetFont('DejaVu','','DejaVuSansCondensed.ttf');
	

	$lt=0;

		if($lt>0){$pdf->AddPage(); }
		####CABECALHO
//		$pdf->SetFont('Arial','B',12);
//		// logo 
//		$pdf->Image($link."/imagens/logo_md.jpg"); 
//			  
//		// fonte do titulo
//		#$pdf->SetTextColor(		211, 211, 211);
//		$pdf->SetFontSize(18);
//		$pdf->Cell(0,-50,utf8_decode('MECOB'),0,1,'C');
//		
//		$pdf->Ln(50);
			
			
		 
		####CLIENTE + VENDEDOR
		$pdf->SetFont('Arial','B',12);
		$pdf->SetFillColor(	255, 255, 255);
		#$pdf->SetTextColor(	255, 255, 255);
		$pedido_info = ConverteData($pedido['data_pedido']).' - Pedido '.$pedido['id'];
		if(!empty($pedido['numero_nota'])) $pedido_info .= ' -  N° Nota: '.$pedido['numero_nota'];
		$pedido_info .= ' - Entrega: '.ConverteData($pedido['data_entrega']);
		$pdf->Cell(0,10,utf8_decode($pedido_info),1,100,'C',1);
		
		$pdf->SetFont('Arial','B',10);
		#$pdf->SetFillColor(250,250,250);
		#$pdf->SetTextColor(0,0,0);
		$nome = "Razão Social: ".$pedido['cliente_nome'];
		$nome2 = "Nome Fantasia: ".$cliente['apelido'];
		$endereco = "Endereço: ".$cliente['rua']." n° ".$cliente['numero'].", ".$cliente['bairro']." - ".$cliente['cidade']."/".$cliente['estado'];
		$cnpj = "CNPJ: ".$pedido['cliente_cpf_cnpj'];
		$ie = "IE:  ".$cliente['rg'];
		
		$email = "E-mail: ".$pedido['cliente_email'];
		$telefone = "Telefone: ".$cliente['celular'];
		
		$contato = "Contato: ".$cliente['contato'];
		$celular = "Telefone2: ".$cliente['telefone'];
	
		$obs = "Obs: ".$pedido['observacao'];
		$vendedor = "Vendedor: ".$pedido['vendedor_nome'];
		
		$pagto = "Pagamento: \n\r";
		foreach($parcelas as $parcela){
			$pagto .= "Parcela ".$parcela['nu_parcela']." - R$ ".Format($parcela['vl_parcela'],'numero')." "." - Vencimento: ".ConverteData($parcela['dt_vencimento']);
			if(!empty($parcela['dt_pagamento']) && $parcela['dt_pagamento'] != '0000-00-00'){
				$pagto .= " - Liquidada em  ".ConverteData($parcela['dt_pagamento']);
			}
			else{
				$pagto .= " - em aberto";
			}
			$pagto .= " \r\n";
		}
		
		
		$x = $pdf->GetX();
		$y = $pdf->GetY();

		$pdf->MultiCell(0,7,utf8_decode($nome),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($nome2),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($endereco),1,1,'L',1);
		$pdf->MultiCell(120,7,utf8_decode($cnpj),1,1,'L',1);
		$pdf->SetXY($x + 110, $y+21);
		$pdf->MultiCell(80,7,utf8_decode($ie),1,1,'L',1);
		$pdf->MultiCell(110,7,utf8_decode($email),1,1,'L',1);
		$pdf->SetXY($x + 110, $y+28);
		$pdf->MultiCell(80,7,utf8_decode($telefone),1,1,'L',1);
		$pdf->MultiCell(110,7,utf8_decode($contato),1,1,'L',1);
		$pdf->SetXY($x + 110, $y+35);
		$pdf->MultiCell(80,7,utf8_decode($celular),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($vendedor),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($obs),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($pagto),1,1,'L',1);
		
		$pdf->Ln(5);
		//// monta a tabela com os produtos
		$first=array(utf8_decode('Descrição'),utf8_decode('Preço Un.'),'Qnt.','Subtotal','Desconto','Total');
		$colWidth=array(70,25,15,30,20,30); // largura das colunas
		// fonte das tabelas
		$pdf->SetFontSize(9);
		$pdf->MyTable($first,$produtos,$colWidth,$link,$pedido['desconto_final']);
		
		
		## DUPLICANDO
		$pdf->Ln(12);
		
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		if ( $y >= 140 || count($produtos)>4) {  # 
			$pdf->AddPage();
			$y = 0; // should be your top margin
		}
		else{
			$pdf->Cell(0,0,'',1,100,'C');
			$pdf->Ln(5);
		}
				
		$pdf->SetFont('Arial','B',12);
		$pdf->SetFillColor(	255, 255, 255);
		#$pdf->SetTextColor(	255, 255, 255);
		$pdf->Cell(0,10,utf8_decode($pedido_info),1,100,'C',1);
		
		$pdf->SetFont('Arial','B',10);
		#$pdf->SetFillColor(250,250,250);
		#$pdf->SetTextColor(0,0,0);
		
		$y = $pdf->GetY();
		
		$pdf->MultiCell(0,7,utf8_decode($nome),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($nome2),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($endereco),1,1,'L',1);
		$pdf->MultiCell(120,7,utf8_decode($cnpj),1,1,'L',1);
		$pdf->SetXY($x + 110, $y+21);
		$pdf->MultiCell(80,7,utf8_decode($ie),1,1,'L',1);
		$pdf->MultiCell(110,7,utf8_decode($email),1,1,'L',1);
		$pdf->SetXY($x + 110, $y+28);
		$pdf->MultiCell(80,7,utf8_decode($telefone),1,1,'L',1);
		$pdf->MultiCell(110,7,utf8_decode($contato),1,1,'L',1);
		$pdf->SetXY($x + 110, $y+35);
		$pdf->MultiCell(80,7,utf8_decode($celular),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($vendedor),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($obs),1,1,'L',1);
		$pdf->MultiCell(0,7,utf8_decode($pagto),1,1,'L',1);
		
		$pdf->Ln(5);
		
		$pdf->SetFontSize(9);
		$pdf->MyTable($first,$produtos,$colWidth,$link,$pedido['desconto_final']);
		  

  //// fim
  $file='pedido'.$pedido['id'].'.pdf';
  $pdf->Output('','',$file);
}

?>