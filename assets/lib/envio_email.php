<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

function enviarCorreo($destinatario, $asunto, $cuerpo) {

    $destinatariosBCC = ['javier@topciment.com'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'dedi3172657.eu.tuservidoronline.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'info@smartcret.com'; 
        $mail->Password = 'GAtf9s9Mem'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Remitente
        $mail->setFrom('info@smartcret.com', 'Smartcret');

        // Destinatario
        $mail->addAddress($destinatario); 

        // // Copia Oculta
        // foreach ($destinatariosBCC as $bcc) {
        //     $mail->addBCC($bcc);
        // }

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;
        $mail->AltBody = strip_tags($cuerpo);

        echo $cuerpo;

        $mail->send();
        return 'El correo fue enviado con éxito a ' . $destinatario;
    } catch (Exception $e) {
        return "Ocurrió un error al enviar el correo: " . $mail->ErrorInfo;
    }
}

?>