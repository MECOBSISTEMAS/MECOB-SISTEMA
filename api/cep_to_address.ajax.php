<?php

$cep = $_REQUEST['cep'];
//$ctx = stream_context_create(array(
//        'http' => array(
//            'timeout' => 15,
//            'ignore_errors' => true
//        )
//    )
//);

#echo $correios = file_get_contents("http://www.buscacep.correios.com.br/servicos/dnec/consultaEnderecoAction.do?relaxation=" . $cep . "&TipoCep=ALL&semelhante=N&pesquisar=pesquisar&cfm=1&Metodo=listaLogradouro&TipoConsulta=relaxation&StartRow=1&EndRow=10", false, $ctx);
echo $busca = "https://apps.widenet.com.br/busca-cep/api/cep/".$cep.".json";
#echo $correios = file_get_contents($busca); 
exit;


if ($correios != false) {

    if ($xmlstr) {
		
        $endereco = array("rua" => $rua, "bairro" => $bairro, "cidade" => $cidade, "uf" => $uf, "status" => "1");
    } else {
        $endereco = array("error" => 0, 'message' => 'nenhum endereco encontrado para o cep');
    }
} else {
    $endereco = array("error" => 1, 'message' => 'nenhum resposta dos correios');
}
echo json_encode($endereco);
#Response::json($endereco);
?>