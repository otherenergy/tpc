<?php
// Incluir PHPMailer utilizando Composer autoload
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);
try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host = 'dedi3172657.eu.tuservidoronline.com';  
    $mail->SMTPAuth = true;
    $mail->Username = 'info@smartcret.com'; 
    $mail->Password = 'GAtf9s9Mem';  // Reemplaza con la contraseña correcta
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';
    $mail->SMTPDebug = 3; 

    // Remitente
    $mail->setFrom('info@smartcret.com', 'Smartcret');

    // Receptor
    $mail->addAddress('luis@topciment.com');  

    // Contenido del correo
    $mail->isHTML(true);  // Configurar el correo en formato HTML
    $mail->Subject = 'Asunto del correo';
    $mail->Body    = '<p>Este es un mensaje de prueba desde PHP utilizando PHPMailer.</p>';
    $mail->AltBody = 'Este es un mensaje de prueba desde PHP utilizando PHPMailer en formato texto plano.';

    // Enviar el correo
    $mail->send();
    echo 'El mensaje ha sido enviado exitosamente';
} catch (Exception $e) {
    echo "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
}
?>