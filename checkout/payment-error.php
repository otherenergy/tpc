<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
// error_reporting(E_ALL & ~E_WARNING);
error_reporting(E_ALL);
ini_set('display_errors', '1');
// session_start();
if ($_SESSION['smart_user']['login'] == 0){
	header('Location: ../login');
    exit;
}

// $_SESSION['nivel_dir'] = 5;
// include_once('../includes/nivel_dir.php');

include('../config/db_connect.php');
include('../class/userClass.php');
include('../class/checkoutClass.php');
include ('../class/emailClass.php');

$checkout = new Checkout();
$userClass = new userClass();


include_once('../assets/lib/class.carrito.php');
$carrito = new Carrito();



$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma = $id_idioma_global;

$idioma_url = $checkout->obten_idioma($id_idioma)->idioma;

// $moneda_obj = $userClass->obtener_moneda();
// $moneda = $moneda_obj->moneda;

include_once('../includes/urls.php');
include_once('../includes/vocabulario.php');


$emailclass = new emailClass();

?>

<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>Pedido cancelado | Smartcret</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="language" content="es-ES">
		<meta name="description" content="Página en la que se informa de que el pago por REDSYS ha sido rechazado">
		<!-- Etiquetas META SEO -->
		<!-- Estilos CSS -->
		<link rel='stylesheet' href='../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel="icon" href="assets/img/favicon.png">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-169820052-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', 'UA-169820052-1');
		  gtag('config', 'AW-1001206950');
		</script>
		<script async src="https://www.googletagmanager.com/gtag/js?id=AW-1001206950"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'AW-1001206950');
		</script>
		<script>
		  gtag('event', 'conversion', {
			  'send_to': 'AW-1001206950/NNt5CLbl_o8DEKbptN0D',
			  'transaction_id': ''
		  });
		</script>
		<?php //include_once ( 'includes/seguimiento_hotjar.php' ) ?>
		<style>
			#boton-arriba {
				display: none!important;
			}
		</style>
	</head>
	<body class="pedido-cancelado">
	<!-- Header - Inicio -->
	<?php
	// include('../includes/header.php');



	$input_nombre = $_SESSION['smart_user']['nombre'];
	$input_email = $_SESSION['smart_user']['email'];

	/*********************** 	ENVIAR CORREO  PAGO RECHAZADO  **********************/

	$email_pago_rechazado = $emailclass->email_pago_rechazado($_SESSION['id_idioma'], $input_email, $input_nombre);

	/*********************************************************************************/

	?>
	<!-- Header - Fin -->

	<section class="sec-fin">
		<div class="txt-cancelado">
			<div class="txt">
				<div style="background-color:#9ac31f;color:#fff;height: 100%" >
							<img src="https://www.smartcret.com/assets/img/logo-smartcret-blanco.png" style="width:200px;display:block;margin-left:auto;margin-right:auto;">
							<h1 style="padding-left:3%;text-align:center;color:#fff;"><?php echo $vocabulario_algo_esta_fallando ?> 😢</h1>
							<p style="padding-left:3%;text-align:center;">Oh, Oh, <b><?php echo $input_nombre;?></b> <?php echo $vocabulario_estamos_tristes ?></p>
							<p style="padding-left:3%;text-align:center;"><?php echo $vocabulario_no_sabemos_que ?></p>
							<p style="padding-left:3%;text-align:center;"><?php echo $vocabulario_si_necesitas_cualquier_cosa ?></p>
							<img src="https://www.smartcret.com/assets/img/mailings/pago-rechazado.jpg" width="600" height="" alt="Pedido enviado" border="0" style="width: 100%; max-width: 600px; height: auto; background: #9ac31f; font-family: sans-serif; font-size: 15px; line-height: 15px; color: #555555; margin: auto; display: block;" class="g-img">
							<div class="link_content" style="text-align: center">
                <a href="./" class="volver_link"><?php echo $vocabulario_volver ?></a>
            </div>

						</div>
		</div>
<div class="sep40"></div>
	</section>
<?php
	var_dump($carrito);

?>
	<!-- Footer - Inicio -->
	<?php //include('../includes/footer.php'); ?>
	<!-- Footer - Fin -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<!--<script data-id='xenioo' data-node='app' src="https://static.xenioo.com/webchat/xenioowebchat.js"></script>-->
	<script>
		/*xenioowebchat.Start("1f7d74f5-de9c-4b88-8dd1-6206b99e8c7b");*/
	</script>
  <!-- <?php $carrito->destroy(); ?> -->
	</body>
</html>
