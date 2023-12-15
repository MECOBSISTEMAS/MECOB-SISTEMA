<?php
date_default_timezone_set('America/Sao_Paulo');

function consultaPermissao($array,$modulo,$acao="qualquer"){
	if($array['eh_admin']){return true;}
	elseif($modulo=='eh_admin'){return false;}
	
	
	foreach($array['permissoes'] as $permissao){
		if($permissao['codigo'] == $modulo){
			$permissoes = $permissao;
			$encontrou_permissao = 1;
			break;
		}
	}
	if(empty($encontrou_permissao)){return false;}
	
	#echo "<pre>";print_r($permissoes);echo "</pre>";
	
	if($acao=='qualquer'){
		if($permissoes['visualizar']=='S'){return true;}
		if($permissoes['adicionar']=='S'){return true;}
		if($permissoes['editar']=='S'){return true;}
		//if($permissoes['conceder']=='S'){return true;}
	}
	elseif($acao=='visualizar' && (  $permissoes['visualizar']=='S' || $permissoes['adicionar']=='S' || $permissoes['editar']=='S' )){
		return true;
	}
	elseif($acao=='adicionar' && ( $permissoes['adicionar']=='S' || $permissoes['editar']=='S' )  ){
		return true;
	}
	elseif($acao=='editar' && $permissoes['editar']=='S'){
		return true;
	}
//	elseif($acao=='conceder' && $permissoes['conceder']=='S'){
//		return true;
//	}
	else{
		return false;
	}
}

function url_to_link($url) {
    if(empty($url)) return $url;
	if (substr($url, 0, 4) == "http") {
        return $url;
    } else
        return "http://" . $url;
}

function string_to_int($var) {
    if (!is_numeric($var)) {
        $tam = strlen($var);
		$ret="";
		for($i=0;$i<$tam;$i++){
			if(is_numeric($var[$i])){
			$ret .=  $var[$i];
			}
		}
		return $ret;
    }
	return $var;
}

function ConverteData($Data, $oracle = "") {
	//remove .0000
	$array_remove_zeros = explode('.',$Data);
	$Data = $array_remove_zeros[0];
	
	if(strpos($Data,",")){ $Data = substr($Data,0,strpos($Data,","));}
	
	if (  (strlen($Data) <2) || $Data == "null" || $Data == "00/00/0000" || $Data == "0000-00-00" ) { return ""; } 
		
    if (BD_TIPO_CONNECT == 'mysql' || $oracle != "") {

        $time = explode(" ", $Data);
        if (isset($time[1]) && strlen($time[1]) > 3) {
            $tm = " " . $time[1];
        } else {
            $tm = "";
        }

        $Data = $time[0];
        if (strstr($Data, "/")) {//verifica se tem a barra /
            $d = explode("/", $Data); //tira a barra
            $rstData = "$d[2]-$d[1]-$d[0]"; //separa as datas $d[2] = ano $d[1] = mes etc...
            return $rstData . $tm;
        } elseif (strstr($Data, "-")) {
            $d = explode("-", $Data);
            $rstData = "$d[2]/$d[1]/$d[0]";
            return $rstData . $tm;
        } else {
            return "Data inv&aacute;lida";
        }
    } else {
        if (strstr($Data, "/") || strstr($Data, "-")) {//verifica se tem a barra /
            return data2TO4dig($Data);
        } else {
            return "Data inv&aacute;lida";
        }
    }
}

function ArrayToString($var) {
	//$retorno = implode(",", $_POST);
	$retorno = "";
	foreach( $var  as $item){
		if(is_array ($item)){ $retorno .= ",".ArrayToString($item);}
		else{$retorno .= ",".$item;}
	}
	return $retorno;
}

function tamanho_limite($var, $tam) {
	if(strlen($var)> $tam){
		$var = substr($var,0,$tam);
	}
	return $var;
}



// recebe uma data e um numero de dias e retorna a diferenca (data - dias) 
function date_less_day_corridos($datetime, $days) {
    $date = date('d/m/Y', strtotime($datetime));
    $sumdate = date('d/m/Y', strtotime($datetime));
    $date = strtotime($datetime . "-" . $days . " days");
    return date('d/m/Y', $date);
}
// recebe uma data e um numero de dias e retorna a soma (data + dias) 
function date_plus_day_corridos($datetime, $days)
{  
  $date = date('Y-m-d', strtotime($datetime));
  $sumdate = date('Y-m-d', strtotime($datetime));
  $date = strtotime($datetime."+".$days." days");      
  return date('d/m/Y',$date); 
}


function tiraAcento($str) {
	$a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');
	$b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
    return str_replace($a, $b, $str);
}

function NomeArquivo($str) {
    return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), tiraAcento($str)));
}

function explodeReverseData($concat, $data) {
    if (BD_TIPO_CONNECT == 'mysql') {
        $dataArray = explode('-', $data);
        $dataAlterada = $dataArray[0] . $concat . $dataArray[1] . $concat . $dataArray[2];
    } else {
        $dataArray = explode('/', $data);
        if (strlen($dataArray[2]) == 4) {
            $dataAlterada = $dataArray[2] . $concat . $dataArray[1] . $concat . $dataArray[0];
        } else {
            $dataAlterada = "20" . $dataArray[2] . $concat . $dataArray[1] . $concat . $dataArray[0];
        }
    }
    return $dataAlterada;
}

function trata_busca_sql_score($busca){
	$busca = str_replace("-"," ",$busca); 
	$busca = strtoupper($busca);	
	$busca = str_replace(" DE ", " ", $busca);
	$busca = str_replace(" P/ ", " ", $busca);
	$busca = str_replace(" PARA ", " ", $busca);
	
	if (strpos($busca, ' ') !== false) {
		$busca = str_replace("@", " ", $busca);
	}
	
	
	$array = explode(" ",$busca);
	$tamanho = count($array);
	
	//remove os acentos da busca
	$busca = tiraAcento($busca);		
	$busca = strtoupper($busca);
	
	if($tamanho > 1){
		$busca = "";
		for($i = 0; $i < $tamanho; $i++)
		{
			$palavra_aux = trim($array[$i]);
			if(empty($palavra_aux) || strlen($palavra_aux)<2){
				#$busca .= $palavra_aux." ";
			}
			else{
				$busca .= $palavra_aux."* ";
			}
		}
		return array('multi'=>$busca);
	}
	else{
		return array('simples'=>$busca);	
	}
	
}

function ConverteDataOracle($Data , $ano2to4dig = "" ) {
	
	if (  (strlen($Data) <2) || $Data == "null" ) { return ""; } 
		
    if (BD_TIPO_CONNECT == 'mysql') {return $Data;  } 
	else {
        if (strstr($Data, "/") || strstr($Data, "-")) {//verifica se tem a barra /
            if($ano2to4dig != ""){return data2TO4dig($Data);}else{return $Data;} 
        } else {
            return "Data inv&aacute;lida";
        }
    }
}



function data2TO4dig($data) { // recebe data dd/mm/yyyy ou dd/mm/yy ouu dd-mm-yyyy ou dd-mm-yy
    
		
		if(empty($data) || strlen($data)==0 || $data == " "){
			return $data;
		}
		
        $time = explode(" ", $data);
        if (isset($time[1]) && strlen($time[1]) > 3) {
            $tm = " " . $time[1];
        } else {
            $tm = "";
        }

        $Data = $time[0];
        if (strstr($Data, "/")) {//verifica se tem a barra /            			
			$d = explode("/", $Data); //tira a barra
            if(strlen($d[2])==2    &&  $d[0]<32  &&  strlen($d[0])==2  ){
				$ano = date('Y');
				$i_ano = substr($ano, 0, 2);
				#$f_ano = substr($ano, 2, 2);
				#if($d[2]<($f_ano-1)){ $i_ano++;}
				$new = $i_ano.$d[2];
				$rstData = "$d[0]/$d[1]/$new";
            	return $rstData . $tm;
				
			}else{return $data;}
		} elseif (strstr($Data, "-")) {
            $d = explode("-", $Data);
            if(strlen($d[2])==2   &&  $d[0]<32  &&  strlen($d[0])==2  ){
				$ano = date('Y');
				$i_ano = substr($ano, 0, 2);
				#$f_ano = substr($ano, 2, 2);
				#if($d[2]<($f_ano-2)){ $i_ano++;}
				$new = $i_ano.$d[2];
				$rstData = "$d[0]-$d[1]-$new";
            	return $rstData . $tm;
				
			}else{return $data;}
        } else {
            return " Data inv&aacute;lida";
        }	
}

function DataInvertida($data,$retornaAnoDig=4) { // recebe data dd/mm/yyyy ou dd/mm/yy ouu dd-mm-yyyy ou dd-mm-yy e retorna yyyymmdd
    	
		$data_aux = ConverteData($data);
		$data_aux = str_replace("-","/",data2TO4dig($data_aux));
		$data_aux = explode("/",$data_aux);
		if (strlen($data_aux[0])==2){
			$data_aux = str_replace("-","/",data2TO4dig($data));
			$data_aux = explode("/",$data_aux);
		}		
		if (BD_TIPO_CONNECT == 'mysql') {
			$retorno =  $data_aux[0].$data_aux[1].$data_aux[2];
		}
		else{
			$retorno =  $data_aux[2].$data_aux[1].$data_aux[0];	
		}
		
		if($retornaAnoDig == 2){
			return substr($retorno, 2); 
		}
		else
			return $retorno;
		
}

function EhDataMaior($data1, $data2){
	
	if (DataInvertida($data1) > DataInvertida($data2)){
		return true;	
	}
	return false;
}

function EhDataIgual($data1, $data2){
	
	if (DataInvertida($data1) == DataInvertida($data2)){
		return true;	
	}
	return false;
}

function EhDataMenor($data1, $data2){
	
	if (DataInvertida($data1) < DataInvertida($data2)){
		return true;	
	}
	return false;
}

//retorna o valor trucado sem arredondamento, valor deve ser separado por . (ponto) na casa decimal
function Truncate($valor, $numero_de_casas_descimais = 2){
	
	$v1 = explode(".", $valor);
	
	if (!isset($v1[0])) {
		$v1[0] = $valor;
		$v1[1] = str_pad(0, $numero_de_casas_descimais, 0, STR_PAD_RIGHT);
	}
	if (!isset($v1[1])) {
		$v1[1] = str_pad(0, $numero_de_casas_descimais, 0, STR_PAD_RIGHT);
	}

	return $v1[0] . "." . substr($v1[1], 0, $numero_de_casas_descimais);
	
}


function slugify($text)
{

    $text = tiraAcento($text);
	
	
	// replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    // trim
    $text = trim($text, '-');
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // lowercase
    $text = strtolower($text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    return empty($text) ? 'n-a' : $text;
}

function Format($valor,$tipo){
	switch ($tipo) {
		case 'numero':
			$valor = str_replace(',','.',$valor);
			if($valor==""){$valor=0;}
			$valor = number_format($valor, 2, ',', '.');
			break;
		case 'calculo':
			$valor = str_replace('.','',$valor);
			$valor = str_replace(',','.',$valor);
			break;
		case 'telefone':
			if(!is_numeric($valor)){return $valor;}
			$valor = ltrim($valor,0);
			$tam=strlen($valor);
			if($tam==8)
				$valor = substr($valor,0,4)."-".substr($valor,4,4);
			elseif($tam==9)
				$valor = substr($valor,0,5)."-".substr($valor,5,4);
			elseif($tam==10)
				$valor = "(".substr($valor,0,2).")"." ".substr($valor,2,4)."-".substr($valor,6,4);
			elseif($tam==11)
				$valor = "(".substr($valor,0,2).")"." ".substr($valor,2,5)."-".substr($valor,7,4);
			break;
		case 'documento':
			if(!is_numeric($valor)){return $valor;}
			$tam=strlen($valor);
			if($tam==11)
				$valor = substr($valor,0,3).".".substr($valor,3,3).".".substr($valor,6,3)."-".substr($valor,9,2);
			elseif($tam==14)
				$valor = substr($valor,0,2).".".substr($valor,2,3).".".substr($valor,5,3)."/".substr($valor,8,4)."-".substr($valor,12,2);
			break;
	}
	return $valor;
}


function editImage( $pathToImage, $pathToNewImage, $thumbWidth, $thumbHeight, $ext ){
  
	if($ext == "png" || $ext == "PNG"){
	
		$im =  imagecreatefrompng("$pathToImage");
		$width = imagesx( $im );
		$height = imagesy( $im );
		
		//plano de fundo da imagem
		$img_fundo = imagecreatetruecolor($thumbWidth, $thumbHeight);
		//imageantialias($img_fundo,false);
		$white = imagecolorallocate($img_fundo, 240, 242, 245);
		imagefill($img_fundo, 0, 0, $white);
		
		// calculate size
		$new_width = $thumbWidth;
		$new_height = floor( $height * ( $thumbWidth / $width ) );
		if($new_height > $thumbHeight){
			$new_width = floor( $width * ( $thumbHeight / $height ) );
			$new_height = $thumbHeight;
		}
		
		
		$im_dest = imagecreatetruecolor( $new_width, $new_height );
		imagealphablending($im_dest, false);
		
		//echo "$new_width, $new_height, $width, $height ";
		imagecopyresampled($im_dest, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
		
		imagecopy($img_fundo, $im_dest, ($thumbWidth-$new_width)/2, ($thumbHeight-$new_height)/2, 0, 0, $new_width, $new_height);
		
		#imagesavealpha($im_dest, true);
		imagepng($img_fundo, "$pathToNewImage");
	
	}
	else{
	
	
	// load image and get image size
	$img = imagecreatefromjpeg("$pathToImage"); 
	
	$width = imagesx( $img );
	$height = imagesy( $img );
	
	//plano de fundo da imagem
	$img_fundo = imagecreatetruecolor($thumbWidth, $thumbHeight);
	//imageantialias($img_fundo,false);
	$white = imagecolorallocate($img_fundo, 240, 242, 245);
	imagefill($img_fundo, 0, 0, $white);
	
	// calculate size
	$new_width = $thumbWidth;
	$new_height = floor( $height * ( $thumbWidth / $width ) );
	if($new_height > $thumbHeight){
		$new_width = floor( $width * ( $thumbHeight / $height ) );
		$new_height = $thumbHeight;
	}
	
	// create a new temporary image
	$tmp_img = imagecreatetruecolor( $new_width, $new_height );
	// copy and resize old image into new image
	imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	//imagesavealpha($tmp_img, true);
	imagecopy($img_fundo, $tmp_img, ($thumbWidth-$new_width)/2, ($thumbHeight-$new_height)/2, 0, 0, $new_width, $new_height);
	
	// save thumbnail into a file JPEG
	imagejpeg( $img_fundo, "$pathToNewImage", 100);
	//echo "jpg: $pathToNewImage";
	
	}
}

function HeaderToFileGetContent($username="",$senha=""){
	if(!empty($username) && !empty($senha)){
		$opts = array(
		'http' => array(
			'method' => 'GET',
			'header' => 'Cookie: Ath='.base64_encode(base64_encode($username).":@:".base64_encode($senha))
		)
		);                      
		return $context  = stream_context_create($opts);
	}
	return null;
}

// retorna uma string aleatoria de tamanho n
function random_str($n)
{
  $rs = null;
  for($i=0; $i<$n; $i++) 
    {
      $range = rand(0,2);
      switch($range)
	{
	case(0): $rs .= chr(rand(48,57)); break;
	case(1): $rs .= chr(rand(65,90)); break;
	case(2): $rs .= chr(rand(97,122)); break;
	}
    }
  return $rs;
}


function img($caminho,$file){
	if(!empty($file) && file_exists(getenv('CAMINHO_RAIZ').$caminho.$file)){
		return getenv('CAMINHO_SITE').$caminho.$file;
	}
	else{
		return getenv('CAMINHO_SITE').$caminho.'default.png';
	}
}

function date_to_mes($numero_mes,$abreviado=0){
	switch($numero_mes){ 
		case 1: if($abreviado)return 'Jan';else return 'Janeiro';
		case 2: if($abreviado)return 'Fev';else return 'Fevereiro';
		case 3: if($abreviado)return 'Mar';else return 'Março';
		case 4: if($abreviado)return 'Abr';else return 'Abril';
		case 5: if($abreviado)return 'Mai';else return 'Maio';
		case 6: if($abreviado)return 'Jun';else return 'Junho';
		case 7: if($abreviado)return 'Jul';else return 'Julho';
		case 8: if($abreviado)return 'Ago';else return 'Agosto';
		case 9: if($abreviado)return 'Set';else return 'Setembro';
		case 10: if($abreviado)return 'Out';else return 'Outubro';
		case 11: if($abreviado)return 'Nov';else return 'Novembro';
		case 12: if($abreviado)return 'Dez';else return 'Dezembro';
	}
	return '';
	
}

function test_responsive(){
	return $retorno = "
			<div class='visible-xs'>visible-xs</div>
			<div class='visible-sm'>visible-sm</div>
			<div class='visible-md'>visible-md</div>
			<div class='visible-lg'>visible-lg</div>
			";
}

function validarCPF($cpf = null) {
 	
	if(strlen($cpf) != 11){
		return false;
	}
	
    $cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
	if ( strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
		return FALSE;
	} else { // Calcula os números para verificar se o CPF é verdadeiro
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf{$c} * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf{$c} != $d) {
				return FALSE;
			}
		}
		return true;
	}	
}

function validarCNPJ($cnpj)
{
	if(strlen($cnpj) != 14){
		return false;
	}
	
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;
	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
		return false;
	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj{$i} * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
}

function retornaFeriados($data=''){
	$data = (empty($data))?$data=date('Y-m-d'):$data=$data;
	$data = explode('-',$data);
	// token
	// cGVkcm9AbWVjb2IuY29tLmJyJmhhc2g9NTc1OTgzNzc

	// Conecta na API
    $curl = curl_init('https://api.calendario.com.br/?json=true&token=cGVkcm9AbWVjb2IuY29tLmJyJmhhc2g9NTc1OTgzNzc&ano='.$data[0].'&ibge=4205407');
	
    // Permite retorno
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
    // Obtem dados da API
	$dados = json_decode(curl_exec($curl), true);

	// Fecha a conexão Curl
	curl_close($curl); 
	$data = $data[2].'/'.$data[1].'/'.$data[0];

	$feriado = false;
	foreach ($dados as $key => $value) {
		if ($value['date'] == $data) {
			if (strpos($value['type'],'Feriado') >= 0){
				$feriado = true;
				break;
			}
		}
	}
	

	return $feriado;
}

function format_interval(DateInterval $interval) {
	$result = "";
	if ($interval->y) { $result .= $interval->format("%y anos "); }
	if ($interval->m) { $result .= $interval->format("%m meses "); }
	if ($interval->d) { $result .= ($interval->d > 1) ? $interval->format("%d dias ") : $interval->format("%d dia "); }
	if ($interval->h) { $result .= ($interval->h > 1) ? $interval->format("%h horas ") : $interval->format("%h hora "); }
	if ($interval->i) { $result .= $interval->format("%i min. "); }
	if ($interval->s) { $result .= $interval->format("%s seg."); }

	return $result;
}

function retornaFeriadosItajai($data=''){
	$data     = (empty($data)) ? $data=date('Y-m-d') : $data;
	$ano      = date('Y', strtotime($data));
	$ano_next = date('Y', strtotime($data . ' +366 day'));

	// token
	// cGVkcm9AbWVjb2IuY29tLmJyJmhhc2g9NTc1OTgzNzc

	// Conecta na API
    $curl = curl_init('https://api.calendario.com.br/?json=true&token=cGVkcm9AbWVjb2IuY29tLmJyJmhhc2g9NTc1OTgzNzc&ano='.$ano.'&ibge=4208203');
	
    // Permite retorno
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
    // Obtem dados da API
	$dados = json_decode(curl_exec($curl), true);

	$ret = array();

	// Fecha a conexão Curl
	curl_close($curl); 

	// coloca as datas de feriados no array ret
	if (is_array($dados)) {
		foreach ($dados as $key => $value) {
			if (strpos($value['type'],'Feriado') !== false ) {
				array_push($ret, $value['date']);
			}
		}
	} else {
		array_push($ret, '01/01/' . $ano);
	}

	// Marca 01/01 do próximo ano como feriado
	array_push($ret, '01/01/' . $ano_next);
	// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Dados ' . json_encode($ret));

	return $ret;
}

function protocolos_feriados_range($startDate=null, $endDate=null) {
	$dias_uteis = 5;
	if($startDate == null ) {
		$startDate = date('Y-m-d');
		// $startDate = date('Y-m-d', strtotime('2019-11-12'));
		$endDate = date('Y-m-d', strtotime($startDate . ' +'.$dias_uteis.' day'));
	}
	$begin = new DateTime($startDate);
	$end   = new DateTime($endDate);
	
	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($begin, $interval, $end);

	$holidays = retornaFeriadosItajai();
	$i = 0;

	foreach ($period as $dt) {
		if (in_array($dt->format("d/m/Y"), $holidays) || $dt->format("w") == 0 || $dt->format("w") == 6) {
			$dias_uteis++;
		}
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Count ' . ++$i .' '. json_encode($dt->format("d/m/Y")) .' '. $dias_uteis);
	}

	$swap_date = date('Y-m-d', strtotime($startDate . ' +'.$dias_uteis.' day'));

	// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Fora ' . json_encode($swap_date) .' '. $dias_uteis);

	if(date('N', strtotime($endDate)) == 7) {
		$swap_date = date('Y-m-d', strtotime($swap_date . ' +1 day'));
	}

	// Retorna a data final somados os feriados na semana e os finais de semana.
	$swap_date = getDateUtil($swap_date, $holidays);

	// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Entrou ' . json_encode($swap_date));

	return $swap_date;
}

function getDateUtil($asuumed_date, $holidays) {
if (date('N', strtotime($asuumed_date)) == 6) {
        $tomorrow = date('Y-m-d', strtotime($asuumed_date . '+2 Day'));

		// Não conta feriados aos finais de semana
		// if (in_array(date('d/m/Y', strtotime($tomorrow)), $holidays)) {
        //     $tomorrow = date('Y-m-d', strtotime($tomorrow . '+1 Day'));
        //     $tomorrow = getDateUtil($tomorrow, $holidays);
        // }
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Sábado ' . json_encode($tomorrow));

	} else if (date('N', strtotime($asuumed_date)) == 7) {
        $tomorrow = date('Y-m-d', strtotime($asuumed_date . '+3 Day'));
        // if (in_array(date('d/m/Y', strtotime($tomorrow)), $holidays)) {
        //     $tomorrow = date('Y-m-d', strtotime($tomorrow . '+1 Day'));
        //     $tomorrow = getDateUtil($tomorrow, $holidays);
		// }
		// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Domingo ' . json_encode($tomorrow));
    } else if (in_array(date('d/m/Y', strtotime($asuumed_date)), $holidays)) {
        $tomorrow = date('Y-m-d', strtotime($asuumed_date . '+1 Day'));
        $tomorrow = getDateUtil($tomorrow, $holidays);

    } else {
        $tomorrow = $asuumed_date;
    }

    return $tomorrow;
}

function getDateUtilOrg($asuumed_date, $holidays) {
    if (in_array($asuumed_date, $holidays)) {
        $tomorrow = date('Y-m-d', strtotime($asuumed_date . '+1 Day'));
        $tomorrow = getDateUtil($tomorrow, $holidays);

    } else if (date('N', strtotime($asuumed_date)) == 6) {
        $tomorrow = date('Y-m-d', strtotime($asuumed_date . '+3 Day'));
        if (in_array($tomorrow, $holidays)) {
            $tomorrow = date('Y-m-d', strtotime($tomorrow . '+1 Day'));
            $tomorrow = getDateUtil($tomorrow, $holidays);
        }
    } else if (date('N', strtotime($asuumed_date)) == 7) {
        $tomorrow = date('Y-m-d', strtotime($asuumed_date . '+2 Day'));
        if (in_array($tomorrow, $holidays)) {
            $tomorrow = date('Y-m-d', strtotime($tomorrow . '+1 Day'));
            $tomorrow = getDateUtil($tomorrow, $holidays);
        }
    } else {
        $tomorrow = $asuumed_date;
    }

    return $tomorrow;
}

?>