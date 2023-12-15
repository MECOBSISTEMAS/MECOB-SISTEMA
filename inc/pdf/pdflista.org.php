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
	$tamanho_pula_pag = 230;
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
		$lista_atual ='';
		$tipos_lista = array('contrato','parcelas','ocorrencia','ct_evento');
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
					
					if($cont_campo == 0 && in_array($campo_linha,$tipos_lista)){
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
		$linhas_por_linha =1;
		for($i=0;$i<count($first);$i++){
			$ctcelula++;
			//verifica se o nome do campo possui espaco, neste caso recupera os dois
			$campos_linha = explode(' ',$config_body[$i][0]); 
			$trata_texto = explode(' ',$config_body[$i][3]);
			
			$texto="";
			$linhas_na_celula=1;
			$cont_campo_impresso=0;
			$cont_campo = 0;
			$texto_final = '';
			foreach($campos_linha as $campo_linha){
				if($cont_campo == 0  && in_array($campo_linha,$tipos_lista)){
					$cont_campo++;
					$lista_atual = $campo_linha;
					if($lista_atual=='contrato' ||     $lista_atual=='ct_evento')
						$linhas_por_linha =6; 
					elseif($lista_atual=='ocorrencia'  )
						$linhas_por_linha =6; 
					elseif($lista_atual=='parcelas')
						$linhas_por_linha =4;
					continue;
				}
				elseif($cont_campo_impresso){ 
					$cont_campo++;
					$texto .= "\r\n";
					$linhas_na_celula++;
				}
				
				
				if(isset($linha[$campo_linha])){
					$trata_current="";
					if(isset($trata_texto[$cont_campo_impresso])){
						$trata_current = $trata_texto[$cont_campo_impresso];
					}
					$string = $linha[$campo_linha];
					if($lista_atual=='contrato' && $campo_linha == 'descricao' ){
						$string = $linha['id']." - ".$linha[$campo_linha];
					}
					if($lista_atual=='parcelas' && $campo_linha == 'ct_id' ){
						$this->SetFontSize(8);
						$string = $linha[$campo_linha].' - '.$linha['ct_descricao'];
					}
					if($lista_atual=='parcelas' && $campo_linha == 'nome' ){
						$string = $linha[$campo_linha]."\n".$linha['comprador_nome']; 
					}
					
					$texto_final =  trata_texto_current($trata_current,$string,$lista_atual,$cont_campo, $campo_linha); 
					$texto .= $texto_final;
					
					
					#echo "<br>texto imprimir: $texto  ".$config_body[$i][1]."  $linhas_na_celula  = ".
					$texto_imprimir = string_lengt_to_part($texto_final ,$config_body[$i][1],$linhas_na_celula );	
					
					
					
				} 
				$cont_campo_impresso++;
			}
			
			for($linhas_na_celula;$linhas_na_celula<$linhas_por_linha;$linhas_na_celula++){
				#echo "<br> celula $ctcelula $linhas_na_celula - pula linha na celula";
				$texto .= "\r\n  ";
			}
			
						
			$conta_largura+=$colWidth[$i];
			
			$this->MultiCell($colWidth[$i],6,utf8_decode($texto),1,$config_body[$i][2],true); 
			$this->SetFontSize(9);
			$this->SetXY($x + $conta_largura, $y);
			
			
		}
		$this->SetY($y+(6*($linhas_na_celula-1)));

		$this->Ln();
		
		//pula linha
		
		if($lista_atual == 'contrato' || $lista_atual == 'ocorrencia' || $lista_atual == 'ct_evento') $tamanho_pula_pag = 220;
		elseif($lista_atual == 'parcelas') $tamanho_pula_pag = 220;
		
		
		$y_aux = $this->GetY();
		if ( $y >= $tamanho_pula_pag) {  # 
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
			$pdf->Cell(0,5,utf8_decode($it_info ),0,1,'L');
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
	
	$ini_linhas = $total_linhas;
	$tamanho_string = strlen($string);
	$qnt = ceil($tamanho_string/$tamanho); 
	$qnt--;
	$total_linhas = $total_linhas+$qnt;
	
//	echo '<br>ini linhas:'.$ini_linhas;
//	echo ' tamanho:'.$tamanho;
//	echo ' tamanho_string:'.$tamanho_string;
//	echo ' qnt:'.$qnt;
//	echo ' end linhas:'.$total_linhas;
	 
}


function trata_texto_current($trata_current,$string,$lista='', $posicao=0, $nm_campo=''){

	$current_content='';
	$after = '';
	if($lista == 'contrato'){ 
		if($posicao==2) $trata_current = 'data';
		elseif($posicao==3) $trata_current = 'valor'; 
		elseif($posicao==4) $current_content = 'Status: '; 
	}
	elseif($lista == 'ct_evento'){ 
		if($posicao==2) $current_content = 'Vendedor: '; 
		elseif($posicao==3) $current_content = 'Comprador:  '; 
	}
	elseif($lista == 'ocorrencia'){
		if($posicao==1) $trata_current = 'data'; 
		elseif($posicao==3) $trata_current = 'strip_tags_entities'; 
	} 
	elseif($lista == 'parcelas'){
		if($posicao==1 && $nm_campo == 'ct_id' ) $current_content = ' ';
		elseif($posicao==1 && $nm_campo == 'vl_pagto' ){
			$trata_current = 'valor'; 
			if($string==0)
				return 'Pendente';
		}
		elseif($posicao==2 && $nm_campo == 'nu_parcela' ) $current_content = 'Parcela: ';
		elseif($posicao==2 && $nm_campo == 'dt_credito'  ){
			$trata_current = 'data';  
			if(strlen($string) == 10 && $string != '0000-00-00'){
				//$after = " \r\n  ";
			} 
			else{
				return "";
			}
		}
		elseif($posicao==3){ $trata_current = 'data'; $current_content = ' ';}  
	}
	
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
		case '-':		
			$current_content .= $string.' - ';				
			break;
		case 'strip_tags':		
			$current_content .= strip_tags($string);				
			break;
		case 'strip_tags_entities':	
			$array_carcteres = array('/\s\s+/',"\n","\r",'<p>&nbsp;</p>','&nbsp;','&#39;','<p>','</p>','<br>','<br />','
			','    ', '    ','   ','  ');
			$string =  str_replace($array_carcteres,' ',html_entity_decode(trim(strip_tags($string))));	
			$tamanho = 170;
			if(strlen($string)>$tamanho){
				$string =substr($string,0,$tamanho)."...";
			}
			$current_content .= $string;				
			break;
			
		
		
		default:
			$current_content .= $string;			
	}
	
	return $current_content.$after;
}


?>