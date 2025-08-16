<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

class emailClass
{


function enviarCorreo($email, $asunto, $cuerpo, $tipo=null) {

    // $destinatariosBCC = ['ismael@topciment.com', 'javier@topciment.com', 'info@smartcret.com'];

    if ( $tipo == 'pedido_recibido' ) {
        $destinatariosBCC = ['info@smartcret.com', 'javier@topciment.com', 'pierre@topciment.com'];
    }else {
        $destinatariosBCC = ['info@smartcret.com', 'javier@topciment.com'];
    };
    
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

        $mail->setFrom('info@smartcret.com', 'Smartcret');
        $mail->addAddress($email); 

        if ($archivos && is_array($archivos['name'])) {
            for ($i = 0; $i < count($archivos['name']); $i++) {
                if ($archivos['error'][$i] === UPLOAD_ERR_OK) {
                    $mail->addAttachment($archivos['tmp_name'][$i], $archivos['name'][$i]);
                }
            }
        };

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;
        $mail->AltBody = strip_tags($cuerpo);

        $mail->send();
        return 'El correo fue enviado con éxito a ' . $email;
    } catch (Exception $e) {
        return "Ocurrió un error al enviar el correo: " . $mail->ErrorInfo;
    }
}

    function contenido_mails($id_idioma, $tipo) {
        // global $pdo;
        $pdo = getDB();
        
        $sql = "SELECT asunto, cuerpo FROM emails WHERE id_idioma = :id_idioma AND tipo = :tipo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id_idioma' => $id_idioma, 'tipo' => $tipo]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result;
    }

    function email_procesando_pedido($id_idioma, $email, $nombre, $ref_pedido, $detalle_pedido) {

        $emailContent = $this->contenido_mails($id_idioma, 'procesando_pedido');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{ref_pedido}}' => $ref_pedido,
            '{{detalle_pedido}}' => $detalle_pedido
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo, 'pedido_recibido');
    }

    function email_contacto_distribuidores($email, $contenido, $archivos=null) {
        $nombre_distribuidor = $_SESSION['smart_user']['nombre'];
        $email_distribuidor = $_SESSION['smart_user']['email'];
        $idioma_distribuidor = $_SESSION['smart_user']['idioma'];

        $asunto = 'MENSAJE DISTRIBUIDOR';
        $cuerpo = "Datos distribuidor:<br>
        Nombre:" .$nombre_distribuidor. "<br>
        Email:" .$email_distribuidor. "<br>
        Idioma:" .$idioma_distribuidor. "<br><br>       
        Mensaje distribuidor:<br>
        " .$contenido;

        $destinatariosBCC = ['info@smartcret.com'];
        
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
    
            $mail->setFrom('info@smartcret.com', 'Smartcret');
            $mail->addAddress($email); 
    
            foreach ($destinatariosBCC as $bcc) {
                $mail->addBCC($bcc);
            };
    
            if ($archivos != null){
                for ($i = 0; $i < count($archivos['name']); $i++) {
                    if ($archivos['error'][$i] === UPLOAD_ERR_OK) {
                        $mail->addAttachment($archivos['tmp_name'][$i], $archivos['name'][$i]);
                    }
                }    
            };

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;
            $mail->AltBody = strip_tags($cuerpo);
        
            $mail->send();
            return 'El correo fue enviado con éxito a ' . $email;
        } catch (Exception $e) {
            return "Ocurrió un error al enviar el correo: " . $mail->ErrorInfo;
        }
    }
    
    // $enviar = email_procesando_pedido(1, 92, 'JUAN', 'SC-0000', 'oookkkkkkk');

    // echo "ENVIADO";

    function email_pedido_enviado($id_idioma, $email, $nombre, $apellido, $ref_pedido, $detalle_pedido, $fecha_envio, $transportista, $n_seguimiento, $user_telefono, $user_direccion, $user_localidad, $user_cp, $user_provincia, $user_pais) {

    
        $emailContent = $this->contenido_mails($id_idioma, 'pedido_enviado');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        // echo 'YEEEE';
        $userClass = new userClass();
        $link_paso_a_paso= $userClass->paso_paso(1, $id_idioma)->valor;

        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{apellido}}' => $apellido,
            '{{ref_pedido}}' => $ref_pedido,
            '{{detalle_pedido}}' => $detalle_pedido,
            '{{fecha_envio}}' => $fecha_envio,
            '{{transportista}}' => $transportista,
            '{{n_seguimiento}}' => $n_seguimiento,
            '{{user_email}}' => $email,
            '{{user_telefono}}' => $user_telefono,
            '{{user_direccion}}' => $user_direccion,
            '{{user_localidad}}' => $user_localidad,
            '{{user_cp}}' => $user_cp,
            '{{user_provincia}}' => $user_provincia,
            '{{user_pais}}' => $user_pais,
            '{{enlace}}' =>  'www.smartcret.com/assets/downloads/' . $link_paso_a_paso
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_pedido_enviado($id_idioma, $email, $nombre, $apellido, $ref_pedido, $detalle_pedido, $fecha_envio, $transportista, $n_seguimiento, $user_email, $user_telefono, $user_direccion, $user_localidad, $user_cp, $user_provincia, $user_pais);


    function email_pago_rechazado($id_idioma, $email, $nombre) {
        $emailContent = $this->contenido_mails($id_idioma, 'pago_rechazado');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_pago_rechazado($id_idioma, $email, $nombre);

    function email_pedido_recibido($id_idioma, $email, $nombre) {
        $emailContent = $this->contenido_mails($id_idioma, 'pedido_recibido');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_pedido_recibido($id_idioma, $email, $nombre);


    function email_opinion_cliente($id_idioma, $email, $nombre, $ref_pedido, $formulario) {
        $emailContent = $this->contenido_mails($id_idioma, 'opinion_cliente');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{ref_pedido}}' => $ref_pedido,
            '{{formulario}}' => $formulario
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_opinion_cliente($id_idioma, $email, $nombre, $ref_pedido, $formulario);


    function email_bienvenido_newsletter($id_idioma, $email) {


        $emailContent = $this->contenido_mails($id_idioma, 'bienvenido_newsletter');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_bienvenido_newsletter($id_idioma, $email);

    function email_regalo_newsletter($id_idioma, $email, $nombre) {

        include '../includes/urls.php';

        $emailContent = $this->contenido_mails($id_idioma, 'nuevo_suscriptor');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{tienda}}' => $link_tienda
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_regalo_newsletter($id_idioma, $email, $nombre);

    function email_recordatorio_descuento($id_idioma, $email, $nombre, $descuento, $img_descuento) {

        include '../includes/urls.php';

        $emailContent = $this->contenido_mails($id_idioma, 'recordatorio_descuento');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{tienda}}' => $link_tienda,
            '{{descuento}}' => $descuento,
            '{{img_descuento}}' => $img_descuento
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_recordatorio_descuento($id_idioma, $email, $nombre, $descuento, $img_descuento);


    function email_registro($id_idioma, $email, $nombre, $pass) {
        $emailContent = $this->contenido_mails($id_idioma, 'nuevo_registro');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $url = $_ENV['RUTA_SERVER'].'/includes/activar_cuenta?email='.$email.'&token='.$pass;
        $site = $_ENV['RUTA_SERVER'];

        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{url}}' => $url,
            '{{site}}' => $site
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_registro($id_idioma, $email, $nombre, $pass);


    function email_cambio_pass($id_idioma, $email, $nombre, $pass) {
        $emailContent = $this->contenido_mails($id_idioma, 'cambio_pass');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{user_email}}' => $email,
            '{{user_pass}}' => $pass
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_cambio_pass($id_idioma, $email, $nombre, $email, $pass);


    function email_recuperar_pass($id_idioma, $email, $nombre, $pass) {
        $emailContent = $this->contenido_mails($id_idioma, 'recuperar_pass');
    
        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{user_email}}' => $email,
            '{{user_pass}}' => $pass
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $this->enviarCorreo($email, $asunto, $cuerpo);
    }
    // email_recuperar_pass($id_idioma, $email, $nombre, $pass);

    // Envía email al usuario para que confirme su baja en smartcret
    function email_confirmar_baja($id_idioma, $email, $nombre, $pass) {
   
        $emailContent = $this->contenido_mails($id_idioma, 'confirmar_baja');
    
        $entorno = 'https://www.smartcret.com/';
        if($_SERVER['SERVER_NAME'] == 'localhost') {
            $entorno = 'http://localhost/smartcret_new/';
        }

        $asunto = $emailContent['asunto'];
        $cuerpo = $emailContent['cuerpo'];
        $reemplazos = [
            '{{nombre}}' => $nombre,
            '{{user_email}}' => $email,
            '{{user_pass}}' => $pass,
            '{{direccion}}' => $entorno
        ];
    
        foreach ($reemplazos as $placeholder => $valor) {
            $asunto = str_replace($placeholder, $valor, $asunto);
            $cuerpo = str_replace($placeholder, $valor, $cuerpo);
        }
    
        $envio = $this->enviarCorreo($email, $asunto, $cuerpo);
    }

}
 ?>