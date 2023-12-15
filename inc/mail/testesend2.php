<?php
require_once("mailer/class.phpmailer.php");
$mail          = new PHPMailer();

$mail->SMTPDebug = 2; //Alternative to above constant
$mail->isSMTP();  // tell the class to use SMTP
$mail->SMTPAuth   = true;                // enable SMTP authentication
$mail->Port       = 587;                 // set the SMTP port
$mail->Host       = "smtp.office365.com"; // SMTP server
$mail->Username   = "adm@mecob.com.br"; // SMTP account username
$mail->Password   = "m|3H?u2TJ";     // SMTP account password
$mail->SMTPSecure = "tls";

$mail->setFrom('adm@mecob.com.br', 'MECOB');
$mail->addAddress('olavocampos@gmail.com', 'Olavo'); 

    $mail->Body = "teste";

    $mail->send();



