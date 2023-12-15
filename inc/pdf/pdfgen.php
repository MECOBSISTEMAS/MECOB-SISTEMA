<?php

require('tfpdf.php');
setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');

class ttFPDF extends tFPDF
{
  // Tabela dinâmica
  function MyTable($first,$data,$w)
  {
    $y=50;
	//First
    for($i=0;$i<count($first);$i++)
      $this->Cell($w[$i],7,$first[$i],1,0,'C');
    $this->Ln();
    //Data
	$linhas=0;
	$total_reg = count($data);
	$ct = 0 ;
	$borda=1;
    foreach($data as $row)
	{
		$ct++;
		if($total_reg==$ct) $borda =0;
		for($i=0; $i<count($first); $i++)
		  {
			  $this->Cell($w[$i],6,$row[$i],$borda,0,'C');
			  
			   $linhas++;; 
				// Eu criei a função "contaLinhas" para contar quantas linhas um campo pode conter se tiver largura = 48
				if($y + $linhas >= 430){      // 230 É O TAMANHO MAXIMO ANTES DO RODAPE
					$this->AddPage();   // SE ULTRAPASSADO, É ADICIONADO UMA PÁGINA
					$y=50;             // E O Y INICIAL É RESETADO
					$linhas=0;
					$this->SetY($y);
				    $this->SetX(10);
				}
		  }
		$this->Ln();
	} 
  }
      function Header(){ // CRIANDO UM RODAPE
	   $this->SetFont('Arial','B',8);
          // logo 1
		  $this->Image('../../../imagens/logo_header.jpg'); 
		  
		  // fonte do titulo
		  $this->SetFontSize(18);
		  $this->Cell(0,-10,utf8_decode('Relatório diferença de Valores'),0,1,'C');
		
		  //  logo 2
		  $this->Image('../../../imagens/logo_header2.jpg', 175,20); 
  
    }
   function Footer(){ // CRIANDO UM RODAPE
	$this->AliasNbPages();
		$this->Ln(10);
        $this->Cell(1,8,date('d/m/Y'),'',0,'L');
		$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
        $this->Cell(70,8,$this->Image('../../../imagens/logo_footer.jpg',160),0,0,'L'); 

    }
}


function relatorio_pdf($nivel, $registros)
{ 
  include_once $nivel."cardi/inc/util.php";
  $pdf=new ttFPDF();

  $pdf->AddPage();
  $pdf->AliasNbPages();
  $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
  $pdf->SetFont('DejaVu','','DejaVuSansCondensed.ttf');
  // fonte do cabecalho
  $pdf->SetFontSize(10);
  $pdf->SetFont('arial','',18);
  $pdf->Ln(20);
  //// monta a tabela com os produtos
  $first=array('ESTAB','DATA','RV','BRUTO', 'TX APLICADA', 'LIQUIDO', 'TX CONTRATO', 'LIQ CALC', utf8_decode('DIFERENÇA'),'QT CV');
  // fonte das tabelas
  $pdf->SetFontSize(8);
  
  $w=array(20,20,20,20,20,20,20,20,20,10); // largura das colunas
  $pdf->MyTable($first,$registros,$w);

  // fecha a tabela principal
  $pdf->Cell(array_sum($w),0,'','T');
  $pdf->Ln(20);
  //// fim
  $pdf->Output();
}

?>