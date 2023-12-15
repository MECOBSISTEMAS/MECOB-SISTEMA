<?php
/**
 * Created by PhpStorm.
 * User: mauriciorosa
 * Date: 17/05/17
 * Time: 13:10
 */

include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARB/gerar_arquivo_remessa.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");

//http://sistema.mecob.com.br/inc/boleto/processadores/GARB/gerar_arquivo.php?acao=Z2VyYXJfYXJxdWl2bw==&contrato_id=886

if ((base64_decode($_GET['acao']) == "gerar_arquivo")&&($_GET['contrato_id'] != "")) {

    $contratos    = new contratos();
    $contratos->id = $_GET['contrato_id'];
    $contratos->gerar_boleto = "S";
    $conexao_BD_1->update($contratos);

    gerar_arquivo_remessa($_GET['contrato_id'], $conexao_BD_1);

    echo  json_encode(1);
}