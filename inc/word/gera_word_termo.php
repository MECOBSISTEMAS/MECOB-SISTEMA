<?php
session_start();
require getenv('CAMINHO_RAIZ')."/vendor/autoload.php";
$pw = new \PhpOffice\PhpWord\PhpWord();

		/* [THE HTML] */
		$section = $pw->addSection();
		$html = htmlspecialchars_decode($_SESSION['termoword']);
		// var_dump($_SESSION);
		// exit;
		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

		/* [SAVE FILE ON THE SERVER] */
		// $pw->save("html-to-doc.docx", "Word2007");

		/* [OR FORCE DOWNLOAD] */
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment;filename="convert.docx"');
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, 'Word2007');
		$objWriter->save('php://output');
?>

