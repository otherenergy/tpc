<?php

session_start();
$_SESSION['nivel_dir'] = 2;

include('../../includes/nivel_dir.php');
include('../../class/userClass.php');
include('../../config/db_connect.php');

$userClass = new userClass();
$url_data = $userClass->obtener_informacion_url($id_url, $id_idioma);
$contenido_email = $userClass->contenido_email($id_url, $id_idioma);

include('../../includes/vocabulario.php');
include('../../includes/urls.php');

$urlsDinamicas = [
	"{url_reformas_diy}" => $link_reformas_diy,
];
$datosPrueba = [
	"{{nombre}}" => 'Luisito',
	"{{ref_pedido}}" => 'SC-00123',
	"{{detalle_pedido}}" => 'Muchos productos de Smartcret :)'
];

$content = $contenido_email->cuerpo;

// Reemplaza URLs dinámicas en el contenido
foreach ($urlsDinamicas as $marcador => $valor) {
	$content = str_replace($marcador, $valor, $content);
}

// Datos prueba
foreach ($datosPrueba as $marcador => $valor) {
	$content = str_replace($marcador, $valor, $content);
}

?>

<!DOCTYPE html>
<html lang="es-ES">

<body>
	<?php echo $contenido_email->asunto ?>
	<section style="max-width:600px;margin:0 auto;">

		<?php echo $content ?>

	</section>

</body>

</html>

<?php
exit;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


$subject = [];
$body = [];

$subject[$idioma_url] = $contenido_email->asunto;
$body[$idioma_url] = $contenido_email->cuerpo;

$lang = $idioma_url;
$email = $_GET['email'] ?? '';

// Usar una dirección de correo de prueba si no se proporciona una en $_GET
if (empty($email)) {
	$email = 'ismael@topciment.com';
}

$datos_usuario = (object) ['email' => 'ismael@topciment.com'];

$mail = new PHPMailer(true);

try {
	// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->isSMTP();
	$mail->Host = 'dedi3172657.eu.tuservidoronline.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'info@smartcret.com';
	$mail->Password = 'GAtf9s9Mem';
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = 587;
	$mail->CharSet = 'UTF-8';

	$mail->setFrom('info@smartcret.com', 'Smartcret');

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		throw new Exception("Dirección de correo no válida proporcionada: $email");
	}

	// Asignar destinatarios según la URL
	$url = "email_procesando_pedido";
	switch ($url) {
		case 'email_procesando_pedido':
			$mail->addAddress($email);
			$mail->addBCC('teddy@topciment.com');

			break;

		case 'email_pedido_recibido':
			$mail->addAddress($email);
			$mail->addAddress('javier@topciment.com');
			break;

		case 'email_pedido_enviado':
			$mail->addAddress($datos_usuario->email);
			$mail->addBCC('javier@topciment.com');
			break;

		case 'email_bienvenido_newsletter':
		case 'email_cambio_pass':
		case 'email_recupera_pass':
		case 'email_pago_rechazado':
		case 'email_registro':
			$mail->addAddress($email);
			break;

		case 'email_seguimiento':
		case 'email_suscripcion_news_regalo':
		case 'recordatorio_descuento_newsletter':
			$mail->addAddress($email);
			$mail->addBCC('javier@topciment.com');
			break;

		default:
			throw new Exception("URL no válida: $url");
	}

	$mail->isHTML(true);
	$mail->Subject = $subject[$lang];
	$mail->Body = $body[$lang];
	$mail->AltBody = strip_tags($body[$lang]);

	$mail->send();
	echo 'El correo fue enviado';
} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
?>
