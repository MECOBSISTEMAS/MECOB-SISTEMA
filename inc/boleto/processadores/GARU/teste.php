<?php
//REALIZANDO TESTES
include 'src/Arquivo.php';

//configurando o arquivo de remessa
$config['codigo_empresa'] = '433923';
$config['razao_social'] = 'UNICRED FLORIANÓPOLIS';
$config['numero_remessa'] = '0001';
$config['data_gravacao'] = '250417';

$arquivo = new Arquivo();
//configurando remessa
$arquivo->config($config);

for ($i = 0; $i < 20; $i++) {
	//adicionando boleto
	$boleto['agencia'] 						= '7506';
	$boleto['agencia_dv'] 					= '0';
	$boleto['razao_conta_corrente']			= '0000';
	$boleto['conta'] 						= '952796';
	$boleto['conta_dv'] 					= '6';
	$boleto['carteira'] 					= '009';
	$boleto['numero_controle'] 				= '5219';
	$boleto['habilitar_debito_compensacao'] = true;
	$boleto['habilitar_multa'] 				= true;
	$boleto['percentual_multa'] 			= '2';
	$boleto['nosso_numero'] 				= '61551964';
	$boleto['nosso_numero_dv'] 				= 'P';
	$boleto['desconto_dia']	 				= '0';
	$boleto['rateio'] 						= false;
	$boleto['numero_documento'] 			= '56541654';
	$boleto['vencimento'] 					= '201115';
	$boleto['valor'] 						= '1200';
	$boleto['data_emissao_titulo'] 			= '161115';
	$boleto['valor_dia_atraso'] 			= '0';
	$boleto['data_limite_desconto'] 		= '201115';
	$boleto['valor_desconto'] 				= '0';
	$boleto['valor_iof'] 					= '0';
	$boleto['valor_abatimento_concedido'] 	= '0';
	$boleto['tipo_inscricao_pagador'] 		= 'CPF';
	$boleto['numero_inscricao'] 			= '09191332400';
	$boleto['nome_pagador'] 				= 'Maurício Rosa';
	$boleto['endereco_pagador'] 			= 'Rua Rodrigo Campos Bastos, Universitário, Santa Catarina';
	$boleto['primeira_mensagem'] 			= '';
	$boleto['cep_pagador'] 					= '54100';
	$boleto['sufixo_cep_pagador'] 			= '230';
	$boleto['sacador_segunda_mensagem'] 	= '';
	
	//adicionando boleto
	$arquivo->add_boleto($boleto);
}

$arquivo->setFilename('/Applications/XAMPP/xamppfiles/htdocs/sites/bradesco/GARB/src/CB171101');

$arquivo->save();
