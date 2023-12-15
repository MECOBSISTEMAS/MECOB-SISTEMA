<?php 
$nomearquivo = $_POST['nomearquivo'];
$uploaddir = getenv('CAMINHO_RAIZ')."/documentos/pessoas/$nomearquivo/";
if (!is_dir($uploaddir))
    mkdir($uploaddir);
// $extensao  = pathinfo($_FILES[ "arquivo" ][ 'name' ],PATHINFO_EXTENSION);
$uploadfile = $uploaddir.$_FILES[ "arquivo" ][ 'name' ];
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
    echo "ok";
}
?>