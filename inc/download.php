<?php
$raiz= getenv('CAMINHO_RAIZ');
$link= getenv('CAMINHO_SITE');

if(!empty($_GET['file'])){
	$caminho=base64_decode($_GET['file']);
	$nome = basename($caminho);
	$file = $raiz."/".$caminho;
}
else{
	echo "Arquivo não encontrado";
	exit;
}

//echo $file;
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$nome);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    ob_clean();
    flush();
    readfile($file);
    exit;
}else{
	echo "<br> Arquivo não Encontrado: $file";
}
?>