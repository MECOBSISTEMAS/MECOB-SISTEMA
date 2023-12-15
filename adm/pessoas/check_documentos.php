<?php 
$nomearquivo = $_POST['pasta'];
$dir = getenv('CAMINHO_RAIZ')."/documentos/pessoas/$nomearquivo/";
// echo $dir;
if (is_dir($dir)){
    echo json_encode(array('status'=>'1','arquivos'=>scandir($dir)));
} else {
    echo json_encode(array('status'=>'0'));
}
?>