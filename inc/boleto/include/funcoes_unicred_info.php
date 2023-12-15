<?php

$codigobanco = "136";
$codigo_banco_com_dv = geraCodigoBanco($codigobanco);
$nummoeda = "9";
$fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

//valor tem 10 digitos, sem virgula
$valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
//agencia é 4 digitos
$agencia = formata_numero($dadosboleto["agencia"],4,0);
//conta é 6 digitos
$conta = formata_numero($dadosboleto["conta"],9,0);
//dv da conta
$conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
//carteira é 2 caracteres
$carteira = $dadosboleto["carteira"];

//nosso número (sem dv) é 11 digitos
// $nnum = formata_numero($dadosboleto["carteira"],2,0).formata_numero($dadosboleto["nosso_numero"],11,0);
$nnum = formata_numero($dadosboleto["nosso_numero"],10,0);
// $nnum = formata_numero(2,10,0); // Calculo conferido
// $nnum = formata_numero(299621,10,0);

// Adicionado pq carteira não faz mais parte do nosso número (nnum)
$carteira = $dadosboleto["carteira"];

//dv do nosso número
$dv_nosso_numero = digitoVerificador_nossonumero($nnum);

//conta cedente (sem dv) é 7 digitos
$conta_cedente = formata_numero($dadosboleto["conta_cedente"],9,0);
//dv da conta cedente
$conta_cedente_dv = formata_numero($dadosboleto["conta_cedente_dv"],1,0);

//$ag_contacedente = $agencia . $conta_cedente;

// 44 numeros para o calculo do digito verificador do codigo de barras
$monta_dv = $codigobanco.$nummoeda.$fator_vencimento.$valor.$agencia.$conta_cedente.$conta_cedente_dv.$nnum.$dv_nosso_numero;
$dv = digitoVerificador_barra($monta_dv);

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Boletos codigo barras monta ' . $monta_dv);

// Numero para o codigo de barras com 44 digitos
$codigo_barras = "$codigobanco$nummoeda$dv$fator_vencimento$valor$agencia$conta_cedente$conta_cedente_dv$nnum$dv_nosso_numero";

// syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Boletos codigo barras ' . $codigo_barras);

$nossonumero = $nnum.'-'.$dv_nosso_numero;

$agencia_codigo = $agencia."-".$dadosboleto["agencia_dv"]." / ". $conta_cedente ."-". $conta_cedente_dv;


$dadosboleto["codigo_barras"] = $codigo_barras;
$dadosboleto["linha_digitavel"] = monta_linha_digitavel($codigo_barras);
$dadosboleto["agencia_codigo"] = $agencia_codigo;
$dadosboleto["nosso_numero"] = $nossonumero;
$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;