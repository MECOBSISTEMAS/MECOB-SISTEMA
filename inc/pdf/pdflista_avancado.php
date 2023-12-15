<?php



require('tfpdf.php');
setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
include_once(getenv('CAMINHO_RAIZ')."/inc/util.php");

class ttFPDF extends tFPDF
{
  // Tabela dinâmica
  function MyTable($first,$body,$config_body,$colWidth,$link, $observacao)
  {


	//First
	$this->SetFillColor(	211, 211, 211);
	#$this->SetTextColor(	255, 255, 255);
	
	if(in_array('extende_pagina',$observacao)){
		$this->Cell(-8,3,'',1,0,'C',true);
	}
	
    for($i=0;$i<count($first);$i++){
      $this->Cell($colWidth[$i],7,$first[$i],1,0,'C',true);
	}
    $this->Ln();
    //Data
	
	$linhas=0;
	$total_reg = count($body);
	$ct = 0 ;
	$sum_quantidade = $sum_total=0;
    foreach($body as $linha)
	{
		#echo "<br><br> linha $ct";
		$altura_linha=6;
		$cont_altura_linha=1;
		
		$ct++;
		$colunas = count($first);
		
		if($ct%2==0){
			$this->setFillColor(230,230,230);
		}
		else{
			$this->SetFillColor(250,250,250);
		}
		
		if(in_array('extende_pagina',$observacao)){
			$this->Cell(-8,3,'',1,0,'C',true);
		}
		
		
		#$this->SetTextColor(	50, 54, 57); 
		$conta_largura=0;
		$x = $this->GetX();
		$y = $this->GetY();
		
		//verifica quantidade de linhas de toda a linha
		$linhas_por_linha=1;
		for($i=0;$i<count($first);$i++){
				$tamanho_da_linha = 0;
				//verifica quantidade de campos === in por linha
				$campos_linha = explode(' ',$config_body[$i][0]);
				$trata_texto = explode(' ',$config_body[$i][3]);
				
				
				
				//verifica tamanho da string e o limit da coluna
				$current_content = "";
				$cont_campo_impresso=0;
				$cont_campo=0;
				foreach($campos_linha as $campo_linha){
					
					if($cont_campo == 0){
						$cont_campo++;
						$lista_atual = $campo_linha;
						continue;
					}
					
					
					$trata_current="";
					if(isset($trata_texto[$cont_campo_impresso])){
						$trata_current = $trata_texto[$cont_campo_impresso];
					}
					$current_content .= trata_texto_current($trata_current,$linha[$campo_linha]);

					
					$cont_campo_impresso++;
				}	
				
				$tamanho_da_linha = $tamanho_da_linha + strlen($current_content);
				
				if( ceil($tamanho_da_linha/$config_body[$i][1]) > $linhas_por_linha  ){
						$linhas_por_linha = ceil($tamanho_da_linha/$config_body[$i][1]);
					}
				
				#echo '<br> '.$current_content.' tam1: '.strlen($current_content).' tam2: '.$tamanho_da_linha.' | limite:'.$config_body[$i][1].'  |   arred: '.ceil($tamanho_da_linha/$config_body[$i][1]);
				
		}
		
		#echo "<br> total linhas: ".$linhas_por_linha ;
		//escreve as linhas
		$ctcelula=0;
		for($i=0;$i<count($first);$i++){
			$ctcelula++;
			//verifica se o nome do campo possui espaco, neste caso recupera os dois
			$campos_linha = explode(' ',$config_body[$i][0]);
			
			
			$trata_texto = explode(' ',$config_body[$i][3]);
			
			$texto="";
			$linhas_na_celula=1;
			$cont_campo_impresso=0;
			$cont_campo = 0;
			foreach($campos_linha as $campo_linha){
				
				$cont_campo++;
				if($cont_campo == 0){
					$lista_atual = $campo_linha;
					if($lista_atual)
						$linhas_por_linha =5;
					continue;
				}
				elseif($cont_campo_impresso){
					$texto .= " \r\n   ";
					$linhas_na_celula++;
				}
				
				
				if(isset($linha[$campo_linha])){
					$trata_current="";
					if(isset($trata_texto[$cont_campo_impresso])){
						$trata_current = $trata_texto[$cont_campo_impresso];
					}					
					$texto .= trata_texto_current($trata_current,$linha[$campo_linha],$lista_atual,$cont_campo-1); 
				}
				$cont_campo_impresso++;
			}
			
			#echo "<br>texto imprimir: $texto  ".$config_body[$i][1]."  $linhas_na_celula  = ".
			$texto_imprimir = string_lengt_to_part($texto ,$config_body[$i][1],$linhas_na_celula );	
			
			for($linhas_na_celula;$linhas_na_celula<$linhas_por_linha;$linhas_na_celula++){
				#echo "<br> celula $ctcelula $linhas_na_celula - pula linha na celula";
				$texto .= " \r\n   ";
			}
			
						
			$conta_largura+=$colWidth[$i];
			
			$this->MultiCell($colWidth[$i],6,utf8_decode($texto),1,$config_body[$i][2],true); 
			$this->SetXY($x + $conta_largura, $y);
			
			
		}
		$this->SetY($y+(6*($linhas_na_celula-1)));

		$this->Ln();
		
		//pula linha
		$y_aux = $this->GetY();
		if ( $y >= 200) {  # 
			$this->AddPage();
			$y = 0; // should be your top margin
		}
		
		
	} 
	#$this->SetFillColor(	211, 211, 211);
	#$this->SetTextColor(	255, 255, 255);
	
//	$this->Cell($colWidth[0],7,'Totais',0,0,'R',true);
//	$this->Cell($colWidth[1],7,$sum_quantidade,0,0,'C',true);
//	$this->Cell($colWidth[2],7,'',0,0,'C',true);
//	$this->Cell($colWidth[3],7,'',0,0,'C',true);
//	$this->Cell($colWidth[4],7,'R$ '.Format($sum_total,'numero'),0,0,'C',true);
	
	
	
  }
}


	
function pdflista($cabecalho,$info, $tamcolunas, $head,$body,$config_body,$observacao,$file,$link)
{ 

	
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
	
	#### Info
	$pdf->SetFont('Arial','',10);
	if(count($info)>0){
		$ln_info=10;
		foreach($info as $it_info){
			$pdf->Cell(0,10,utf8_decode($it_info ),0,1,'L');
			#$pdf->Cell(0,10,utf8_decode($it_info),1,100,'C',1);
		}
		#$pdf->Ln($ln_info);
	}

	### Colunas
	// fonte das tabelas
	$pdf->SetFontSize(9);
	$pdf->MyTable($head,$body,$config_body,$tamcolunas,$link, $observacao);	  

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


function trata_texto_current($trata_current,$string,$lista='', $posicao=0){
	
	if($lista == 'contrato'){
		if($posicao==2) $trata_current = 'data';
		if($posicao==3) $trata_current = 'valor'; 
	}
	
	
	$current_content='';
	switch ($trata_current) {
		case 'valor':		
			$current_content .= "R$ ".Format($string,'numero');				
			break;
		case 'telefone':		
			$current_content .= Format($string,'telefone').' ';					
			break;
		case 'documento':		
			$current_content .= Format($string,'documento');						
			break;	
		case 'data':		
			$current_content .= ConverteData($string);				
			break;	
		case '(':		
			$current_content .= ' ('.$string;				
			break;
		case ')':		
			$current_content .= $string.')';				
			break;
		case '/)':		
			$current_content .= '/'.$string.')';				
			break;
		default:
			$current_content .= $string;			
	}
	
	return $current_content;
}


?>