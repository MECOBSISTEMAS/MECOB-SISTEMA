<?php 
$nomearquivo = $_POST['arquivo'];
$arquivo = getenv('CAMINHO_RAIZ').$nomearquivo;
// echo $dir;
unlink($arquivo);
?>