<?php
if (session_status() === PHP_SESSION_NONE){session_start();}
if ( isset( $_SESSION["smart_user_admin"]["login"] ) && $_SESSION["smart_user_admin"]["login"]=='1') {
	header("Location: ./index");
}

?>

<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>Acceso panel administracion | Smartcret</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="language" content="es-ES">
		<meta name="description" content="Página de acceso para usuarios registrados en SmartCret">
		<!-- Etiquetas META SEO -->

		<!-- Estilos CSS -->
		<link rel='stylesheet' href='../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel="icon" href="../assets/img/favicon.png">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<style>
			#boton-arriba {
				display: none!important;
			}
		</style>
	</head>
	<body class="admin">
		<div id="mensaje"><p></p></div>
		<div class="sep30"></div>
	<section class="login">
		<div class="container contenido">
		 	<div class="row">
		 		<div class="col-sm-12 ppal">
		 			<div class="sep30"></div>
		 			<div class="bloque-tit">
		 				<img src="../assets/img/logo-smartcret.png" alt="Logo Smartcret">
		 				<div class="sep10"></div>
		 				<div class="tit">Acceso al panel de administración</div>
		 			</div>
		 			<div class="row login-form form-contacto">
		 				<div class="col-sm-4"></div>
		 				<div class="col-sm-4">
		 					<form class="admin" id="form-login" action="#">
		 						<input type="text" id="input_email" name="input_email" class="form-control" placeholder="Email" value="">
		 						<input type="password" id="input_pass" name="input_pass" class="form-control" placeholder="Contraseña" value="">
		 						<button class="login-button btn_stand_claro">Enviar</button>
		 						<input type="hidden" name="accion" value="login">
		 					</form>
		 					<!-- <p class="txt">No recuerdo mi contraseña. <a href="password-recovery" class="recupera-pass">Recuperar.</a></p> -->
		 				</div>
		 			</div>
		 			<div class="sep40"></div>
		 		</div>
		 	</div>
		</div>

	</section>


	<!-- Footer - Inicio -->
	<?php include('includes/footer.php'); ?>
	<!-- Footer - Fin -->
	<!--<script data-id='xenioo' data-node='app' src="https://static.xenioo.com/webchat/xenioowebchat.js"></script>-->
	<script>
		/*xenioowebchat.Start("1f7d74f5-de9c-4b88-8dd1-6206b99e8c7b");*/
	</script>


	<script>

	</script>

	</body>
</html>
