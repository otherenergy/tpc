<?php
	include('./includes/header.php');

echo "Pruebas";
echo "<br><br><br>";


$destinatarios = [
    'jgv1976@gmail.com',
    'javier@topciment.com'
];
$asunto = 'Prueba de correo';
$cuerpo = '<h1>Hola</h1><p>Este es un correo de prueba enviado desde PHPMailer.</p>';
enviarCorreo($destinatarios, $asunto, $cuerpo);


?>

