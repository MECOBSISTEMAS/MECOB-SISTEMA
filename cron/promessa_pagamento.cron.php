<?php 
include_once("cron_config.php");
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');
$cron = true;
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");

include_once(getenv('CAMINHO_RAIZ')."/repositories/alertas/alertas.class.php");
$alertas  = new alertas();
include_once(getenv('CAMINHO_RAIZ')."/repositories/alertas/alertas.db.php");
$alertasDB  = new alertasDB();

include_once(getenv('CAMINHO_RAIZ')."/repositories/parcelas/parcelas.db.php");
$parcelasDB  = new parcelasDB();


$promessas = ($parcelasDB->promessas_pagamento_nao_cumpridas_ontem($conexao_BD_1));
$sistema = 3870;
foreach ($promessas as $key => $value) {
    $contrato = $value['contratos_id'];
    $operador = $value['pessoas_id'];
    $parcelas_pagas = $value['parcelas_pagas'];
    if ($parcelas_pagas == 0){
        $descricao = "ID $contrato. Promessa de pagamento não cumprida. Retomar cobrança.";
        inserir_alerta($alertas, $conexao_BD_1, $sistema,$operador,$descricao,$link."/contratos/$contrato");
        echo "Enviado alerta do contrato $contrato, para o usuário $operador\n";
    }
}
function inserir_alerta($alertas,  &$conexao_BD_1, $from, $to, $descricao, $link){
    $alertas->data_alerta = date('Y-m-d H:i:s');
    $alertas->visualizado = 'N';
    $alertas->pessoas_id_cadastro = $from;
    $alertas->pessoas_id_destino = $to;
    $alertas->descricao = $descricao;
    $alertas->link = $link;

    if ($conexao_BD_1->insert($alertas)){
        $retorno = array( 'status' => 1,	'msg'=> "Inserido com Sucesso!"	);							
    }
    else{
        $retorno = array( 'status' => 0,	'msg'=> "Não foi possível inserir!"	);	 	
    }			
    return $retorno;
}

?>