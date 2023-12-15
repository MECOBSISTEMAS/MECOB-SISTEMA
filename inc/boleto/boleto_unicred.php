<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Versï¿½o Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo estï¿½ disponï¿½vel sob a Licenï¿½a GPL disponï¿½vel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Vocï¿½ deve ter recebido uma cï¿½pia da GNU Public License junto com     |
// | esse pacote; se nï¿½o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaboraï¿½ï¿½es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de Joï¿½o Prado Maia e Pablo Martins F. Costa			       	  |
// | 																	                                    |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenaï¿½ï¿½o Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto Bradesco: Ramon Soares						            |
// +----------------------------------------------------------------------+


// ------------------------- DADOS DINï¿½MICOS DO SEU CLIENTE PARA A GERAï¿½ï¿½O DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulï¿½rio c/ POST, GET ou de BD (MySql,Postgre,etc)	//

// DADOS DO BOLETO PARA O SEU CLIENTE
if(empty($dias_de_prazo_para_pagamento))$dias_de_prazo_para_pagamento = 5;
if(!empty($taxa_boleto))$taxa_boleto = 0;
if(empty($data_venc))$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
if(empty($valor_cobrado))$valor_cobrado = "2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
if(empty($valor_cobrado))$valor_cobrado = str_replace(",", ".",$valor_cobrado);
if(empty($valor_boleto))$valor_boleto  = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

if(empty($nosso_numero)){$nosso_numero = date('mdHis');}

$dadosboleto["nosso_numero"] = $nosso_numero;  // Nosso numero sem o DV - REGRA: Mï¿½ximo de 11 caracteres!
$dadosboleto["numero_documento"] = $dadosboleto["nosso_numero"];	// Num do pedido ou do documento = Nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissï¿½o do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vï¿½rgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
if(empty($nome_cliente))$nome_cliente ='Cliente ME';
$dadosboleto["sacado"] = $nome_cliente;

$dadosboleto["endereco1"] = $dadosboleto["endereco2"] = $dadosboleto["endereco3"] = "";

if(!empty($endereco_cliente1)){
	$dadosboleto["endereco1"] = $endereco_cliente1;
	if(!empty($endereco_cliente2))	$dadosboleto["endereco2"] = '<br>'.$endereco_cliente2;
	if(!empty($endereco_cliente3))	$dadosboleto["endereco3"] = ' '.$endereco_cliente3;	
}



// INFORMACOES PARA O CLIENTE
if(empty($descricao_boleto))$descricao_boleto = "Parcela de Contrato";
$dadosboleto["demonstrativo1"] = $descricao_boleto;

if(!empty($demonstrativo_boleto)){
	$dadosboleto["demonstrativo2"] = $demonstrativo_boleto;
}

if(!empty($instrucoes_boleto)){
	if($instrucoes_boleto!='-')
		$dadosboleto["instrucoes1"] = utf8_decode($instrucoes_boleto);
}
else{
	if ($nosso_numero >= '472228' ) {
		$dadosboleto["instrucoes1"] = "Após vencimento, mora de 1% ao mês";
	} else {
		$dadosboleto["instrucoes1"] = "Após vencimento, mora dia de R$ 0,54";
	}
	$dadosboleto["instrucoes2"] = "Após vencimento, multa de 2%";
	// $dadosboleto["instrucoes3"] = "Tï¿½tulo sujeito a negativaï¿½ï¿½o e protesto 03 dias Apï¿½s o vencimento.";
	$dadosboleto["instrucoes3"] = "";
}
$dadosboleto["instrucoes4"] = "";
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "Não";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURAï¿½ï¿½O DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - UNICRED (Bradesco) 1103-7 / 095279-6
$dadosboleto["agencia"] = "1103"; // Num da agencia, sem digito
$dadosboleto["agencia_dv"] = "7"; // Digito do Num da agencia
$dadosboleto["conta"] = "095279"; 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = "6"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - UNICRED (Bradesco) 1103-7 / 095279-6
$dadosboleto["conta_cedente"] = "095279"; // ContaCedente do Cliente, sem digito (Somente Nï¿½meros)
$dadosboleto["conta_cedente_dv"] = "6"; // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = "21";  // Cï¿½digo da Carteira: pode ser 06 ou 03

// SEUS DADOS ME
//$dadosboleto["identificacao"] = "Motta & Etchepare Ltda ";
//$dadosboleto["cpf_cnpj"] = "074.064.502/0001-12";
//$dadosboleto["endereco"]  = "Rua Uruguai, 299 ";
//$dadosboleto["cidade_uf"] = "Centro - Itajaï¿½/SC, 88.302-201";
//$dadosboleto["cedente"]   = "Motta & Etchepare Ltda";

// SEUS DADOS UNICRED
$dadosboleto["identificacao"] = "Motta & Etchepare Ltda";
$dadosboleto["cpf_cnpj"] = "007.453.543/0001-03 ";
$dadosboleto["endereco"]  = "Av. Trompowsky, n. 172 ";
$dadosboleto["cidade_uf"] = "Bairro Centro, Florianópolis/SC - 88015-300.";

$cedente = "MOTTA E ETCHEPARE LTDA ME - CNPJ: 007.453.543/0001-03";
$cedente .= "<br>Av Trompowsky,172 Centro - Florianópolis/SC - 88015-300";

$dadosboleto["cedente"]   = $cedente;


// $avalista = "<br>Sacador/Avalista  MOTTA E ETCHEPARE LTDA ME - CNPJ 007453543000103<br>AV Trompowsky 172  Centro - Florianï¿½polis / SC - 88015-300";
$avalista = "<br>Sacador/Avalista";
$dadosboleto["avalista"] = $avalista;


$dadosboleto = array_map("utf8_encode", $dadosboleto );

// Nï¿½O ALTERAR!
include_once("include/funcoes_unicred.php");
include("include/funcoes_unicred_info.php");
include("include/layout_unicred.php");
?>
