<?php 

/* Este arquivo conta com funções de uso geral */

//valida endereço de e-mail


// função para validar cep...
function validaCep($cep){
	$cep = trim($cep);
	if(preg_match("/^[0-9]{5}-[0-9]{3}$/", $cep))
	{
		return true;
	}else{
		return false;
	}
} // fim da função



 // diferentemente do isset, essa função força que uma variavel alem de existir, tenha algum conteudo
function notnull($var) {
  if (isset($var))
    return ($var != null);
  else return 0;
}

function isnull($var) {
  return !notnull($var);
}

  // recebe um array e verifica se todos os valores são diferentes de nulo/0
function arraynotnull($arr) {
  $r = 1;
  foreach ($arr as $a) $r *= notnull($a);
  return $r;
}

// recebe o cep e retorna o endereco em um array associativo
function cep_to_address($cep)
 {
  $correios = file_get_contents("http://www.buscacep.correios.com.br/servicos/dnec/consultaEnderecoAction.do?relaxation=".$cep."&TipoCep=ALL&semelhante=N&pesquisar=pesquisar&cfm=1&Metodo=listaLogradouro&TipoConsulta=relaxation&StartRow=1&EndRow=10");
  $xmlstr = strstr($correios, "<?xml");
  $axmlstr = explode('<table width="645">', $xmlstr); // isso teve que ser feito pq o php no server nao suporta strstr integralmente
  $xmlstr = $axmlstr[0];
  if (notnull($xmlstr)) {
    $xml = new SimpleXMLElement($xmlstr);  
    $rua = utf8_decode($xml->tr->td[0])."";
    $rua = explode(' - ', $rua);
    $rua = $rua[0];
    $bairro = utf8_decode($xml->tr->td[1])."";
    $cidade = utf8_decode($xml->tr->td[2])."";
    $uf = utf8_decode($xml->tr->td[3])."";
    $cep = utf8_decode($xml->tr->td[4])."";
    $end = array("rua" => $rua, "bairro" => $bairro, "cidade" => $cidade, "uf" => $uf);
    return $end;		
  }
}
      
// recebe uma data e um numero de dias e retorna a soma (data + dias uteis) eliminando finais de semana
// versão não orientada a objetos:
function date_plus_day($datetime, $days)
{  
  $date = date('Y-m-d', strtotime($datetime));
  $sumdate = date('Y-m-d', strtotime($datetime));
  // isso é necessário para lidar com os dias de final de semana
  // o delay é o tempo de atraso caso tenha dias de final de semana 
  // até o prazo de entrega..
  $delay = 0;
   for ($d = 1; $d <= $days; $d++) 
    {
      $sumdate = date('Y-m-d', strtotime($sumdate." + 1 day"));
      $a = aDate($sumdate);
      $weekday = date("l", mktime(0,0,0,$a['m'],$a['d'],$a['y']));
      if (($weekday == 'Saturday') || ($weekday == 'Sunday')) {
	$delay++;
	// caso a contagem termine no sábado mais um dia de atraso (do domingo):
	if (($d == $days) && ($weekday == 'Saturday'))  
	  $delay++;
      }
    }
  // pronto, agora temos o valor de delay e podemos somar com o numero de dias:
  $days += $delay;
  $date = strtotime($datetime."+".$days." days");      
  return date('d/m/Y',$date); 
}

function date_plus_day2($datetime, $days)//iqual a de cima porem retorna Y-m-d
{  
  $date = date('Y-m-d', strtotime($datetime));
  $sumdate = date('Y-m-d', strtotime($datetime));
  // isso é necessário para lidar com os dias de final de semana
  // o delay é o tempo de atraso caso tenha dias de final de semana 
  // até o prazo de entrega..
  $delay = 0;
   for ($d = 1; $d <= $days; $d++) 
    {
      $sumdate = date('Y-m-d', strtotime($sumdate." + 1 day"));
      $a = aDate($sumdate);
      $weekday = date("l", mktime(0,0,0,$a['m'],$a['d'],$a['y']));
      if (($weekday == 'Saturday') || ($weekday == 'Sunday')) {
	$delay++;
	// caso a contagem termine no sábado mais um dia de atraso (do domingo):
	if (($d == $days) && ($weekday == 'Saturday'))  
	  $delay++;
      }
    }
  // pronto, agora temos o valor de delay e podemos somar com o numero de dias:
  $days += $delay;
  $date = strtotime($datetime."+".$days." days");      
  return date('Y-m-d',$date); 
}



// recebe uma data e um numero de dias e retorna a soma (data + dias)  em formato Y-m-d
function date_plus_day_corridos_bd($datetime, $days)
{  
  $date = date('Y-m-d', strtotime($datetime));
  $sumdate = date('Y-m-d', strtotime($datetime));
  $date = strtotime($datetime."+".$days." days");      
  return date('Y-m-d',$date);
}






function curPageURL() {
 $pageURL = 'http';
 //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>