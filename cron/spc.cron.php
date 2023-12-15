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


$parcelas = ($parcelasDB->parcelas_colocar_spc($conexao_BD_1,5));
$carol = 4;
foreach ($parcelas as $key => $value) {
    // if ($value['pessoas_id_ocorrencia']) {
    //     $pessoas_id = $value['pessoas_id_ocorrencia'];
    // } else {
    $supervisores = explode(',',$value['supervisores']);
    $qtd_supervisores = count($supervisores);
    if ($supervisores[0] != '' && $qtd_supervisores > 0){
        $rnd = rand(0,$qtd_supervisores-1);
        $pessoas_id = $supervisores[$rnd];
    // } else {
    //     $pessoas_id = $value['pessoas_id_inclusao'];
    // }
        $id_spc = $value['id'];
        $descricao = "Parcela do contrato $id_spc atrasada a mais de 5 dias, por favor, inserir no SPC";
        inserir_alerta($alertas, $conexao_BD_1, $carol,$pessoas_id,$descricao,$link."/contratos/$id_spc");
        echo "Enviado alerta do contrato $id_spc, para o usuário $pessoas_id o colocar no SPC\n";
    // }
    } else{
        echo "Não enviado alerta pois não possui nenhum supervisor cadastrado.\n";
    }
}
$parcelas = ($parcelasDB->parcelas_retirar_spc($conexao_BD_1,5));
$carol = 4;
foreach ($parcelas as $key => $value) {
    // if ($value['pessoas_id_ocorrencia']) {
    //     $pessoas_id = $value['pessoas_id_ocorrencia'];
    // } else {
    $supervisores = explode(',',$value['supervisores']);
    $qtd_supervisores = count($supervisores);
    if ($supervisores[0] != '' && $qtd_supervisores > 0){
        $rnd = rand(0,$qtd_supervisores-1);
        $pessoas_id = $supervisores[$rnd];
    // } else {
    //     $pessoas_id = $value['pessoas_id_inclusao'];
//     }
// }
        $id_spc = $value['id'];
        $descricao = "Parcela paga do contrato $id_spc, por favor, retirar do SPC";
        inserir_alerta($alertas, $conexao_BD_1, $carol,$pessoas_id,$descricao,$link."/contratos/$id_spc");
        echo "Enviado alerta do contrato $id_spc, para o usuário $pessoas_id o retirar do SPC\n";
    } else {
        echo "Não enviado alerta pois não possui nenhum supervisor cadastrado.\n";
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