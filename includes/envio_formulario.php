<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include("../assets/lib/phpmailer/PHPMailerAutoload.php");

$email_smartcret = "info@smartcret.com";

if( isset( $_POST['g-recaptcha-response'] ) && $_POST['g-recaptcha-response'] != '' ){
   $captcha=$_POST['g-recaptcha-response'];
   $secretKey = "6LdZAWsfAAAAAM-bgNbVYTWHuCMJpwKB2nqYoajZ";
   $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
   $response = file_get_contents($url);
   $responseKeys = json_decode($response,true);

   if($responseKeys["success"]) {

   	$nom = $_POST['form_nom'];
		$email = $_POST['form_email'];
		$telf = $_POST['form_telf'];
		$loc = $_POST['form_loc'];
		$pais = $_POST['form_pais'];
		$mensa = $_POST['form_mensa'];
		$priv = ( isset( $_POST['form_priv'] ) ) ? 'ok' : '';
		$comercial = ( isset( $_POST['form_comercial'] ) ) ? 'ok' : '';
		$dat = ( isset( $_POST['form_dat'] ) ) ? 'ok' : '';
		$desde = $_POST['form_desde'];

		$asunto = "Formulario distribuidor SMARTCRET - $nom";

		$mensaje = '
		<style>
			@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap");
		</style>
		<div class="contenido" style="width: 600px;margin: 20px;">
			<div class="mensaje">
				<a href="https://www.smartcret.com" target="_blank"><img src="https://www.smartcret.com/assets/img/logo-smartcret.png" alt="Smartcret" style="width: 180px;"></a>';

		$mensaje .= '
				<br><br>
				<h4>Enviado desde ' . $desde . '</h4>
				<table border="1" style="margin-top: 20px;">
					<tr>
						<td style="padding:4px 10px;">Nombre empresa: </td>
						<td class="dat" style="padding:4px 10px;padding-left: 20px;font-weight: 500;color: #000;">' . $nom . '</td>
					</tr>
					<tr>
						<td style="padding:4px 10px;">Email: </td>
						<td class="dat" style="padding:4px 10px;padding-left: 20px;font-weight: 500;color: #000;">' . $email . '</td>
					</tr>
					<tr>
						<td style="padding:4px 10px;">Teléfono: </td>
						<td class="dat" style="padding:4px 10px;padding-left: 20px;font-weight: 500;color: #000;">' . $telf . '</td>
					</tr>
					<tr>
						<td style="padding:4px 10px;">Localización:</td>
						<td class="dat" style="padding:4px 10px;padding-left: 20px;font-weight: 500;color: #000;">' . $loc . '</td>
					</tr>
					<tr>
						<td style="padding:4px 10px;">Pais:</td>
						<td class="dat" style="padding:4px 10px;padding-left: 20px;font-weight: 500;color: #000;">' . $pais . '</td>
					</tr>
					<tr>
						<td style="padding:4px 10px;">Mensaje:</td>
						<td class="dat" style="padding:4px 10px;padding-left: 20px;font-weight: 500;color: #000;">' . $mensa . '</td>
					</tr>
				</table>
				<br>
				<p>Acepto política de privacidad: <span class="datos"><b> ' . $priv . '</b></span></p>
				<p>Acepto recibir comunicaciones comperciales: <span class="datos"><b> ' . $comercial . '</b></span></p>
				<p>Acepto cesión de datos: <span class="datos"><b> ' . $dat . '</b></span></p>
			</div>
		</div>';

	  $mail = new phpmailer ();
	  $mail -> From = "info@smartcret.com";
	  $mail -> FromName = "Smartcret";
	  $mail -> Host     = "dedi3172657.eu.tuservidoronline.com";
	  $mail -> Mailer   = "smtp";
	  $mail -> SMTPAuth = true;
	  $mail -> SMTPSecure = "ssl";
	  $mail -> Port = 465;
	  $mail -> Sender   = "info@smartcret.com";
	  $mail -> IsSMTP();
		$mail -> Username = "info@smartcret.com";
		$mail -> Password =  base64_decode('R0F0ZjlzOU1lbQ==');

		$mail -> AddAddress( $email_smartcret );

		$mail -> Subject = $asunto;
		$mail -> CharSet  =  "utf-8";
		$mail -> IsHTML (true);
		$mail -> Body = $mensaje;

		// echo $mensaje;
		// exit;

		if ($mail -> Send ()){
			echo "<p style='color:green;border: 1px solid;padding: 5px 20px;background-color: #e2efe2;'>Hemos recibido tu mensaje, nos pondremos en contacto contigo.</p>";
		}else{
			echo "<p style='color:red;padding:5px 20px;border:1px solid;background-color:#fbf0f0;'>No se ha podido realizar el envio. Se ha producido un error.</p>";
		}
   } else {
      echo "<p style='color:red;padding:5px 20px;border:1px solid;background-color:#fbf0f0;'>El Captcha no es válido. Vuelve a enviar el formulario</p>";
   }
} else {
	echo "<p style='color:red;padding:5px 20px;border:1px solid;background-color:#fbf0f0;'>Pare realizar el envio tienes que hacer click en el captcha</p>";
}


?>