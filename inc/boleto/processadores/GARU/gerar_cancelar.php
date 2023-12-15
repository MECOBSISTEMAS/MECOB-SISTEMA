<?php

include_once(getenv('CAMINHO_RAIZ')."/inc/boleto/processadores/GARU/gerar_cancelar_remessa.php");
include_once(getenv('CAMINHO_RAIZ')."/_configuracao/config.php");
include_once(getenv('CAMINHO_RAIZ')."/repositories/contratos/contratos.class.php");

if ((base64_decode($_GET['acao']) == "gerar_arquivo")&&($_GET['contrato_id'] != "")) {

    $contratos               = new contratos();
    $contratos->id           = $_GET['contrato_id'];
    // $contratos->gerar_boleto = "S";
    // $conexao_BD_1->update($contratos);
    
    syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Boletos cancelar GET ' . json_encode($_GET));

    if(isset($_GET['boleto_avulso'])) { 
        gerar_arquivo_remessa($_GET['contrato_id'], $conexao_BD_1, $_GET['boleto_avulso']); 
    } else {
        gerar_arquivo_remessa($_GET['contrato_id'], $conexao_BD_1);
    }
    
    // syslog( 158, 'MECOB - ' . date('H:i:s') . ' - Boletos avulso GET ' . json_encode($_GET));

    echo  json_encode(1);
}