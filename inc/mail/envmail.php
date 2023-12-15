<?

  $headers = '';

  $headers .= "MIME-Version: 1.1\n";

  $headers .= "Content-type: text/html; charset=iso-8859-1\n";

  $headers .= "From: teia@teia.com.br\n";




  if ( mail( $email, $titulo, $text, $headers ) == TRUE )

  {

    $aviso = "Email enviado com sucesso!<br /><a href=\"javascript:window.close()\">Fechar janela x</a>";

  }

  else

  {

    $aviso = "ERRO ao tentar enviar o email.";

  }

?> 		