<?php
require_once( '../lib/bbdd.php' );
require_once( '../lib/funciones.php' );

?>

 <!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>Confirmación de pedido TPV</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="language" content="es-ES">
		<!-- Etiquetas META SEO -->
		<meta name="robots" content="noindex, nofollow">
		<meta name="description" content="Confirmación de pedido TPV">

		<!-- Estilos CSS -->
		<link rel='stylesheet' href='../../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel="icon" href="assets/img/favicon.png">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

	</head>
	<body>

		<div class="container">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<div class="sep60"></div>
					<h1>Confirmar pedido y generar SO</h1>
					<!-- <form action="notifica2.php?lang=es" method="POST"> -->
						<form action="https://www.smartcret.com/assets/return/notifica.php?lang=es" method="POST">
						<!-- <form action="notifica.php" method="POST"> -->
						<input class="form-control" type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1">
						<input class="form-control" type="hidden" name="Ds_Signature" value="9MLlyGJUUZj8B0txUMT251aXNCUju2AHSyIrMrLl7Wk=">
						<label>Ds_MerchantParameters</label>
						<textarea class="form-control" name="Ds_MerchantParameters" id="params" rows="10"></textarea>
						<div class="sep20"></div>
						<label>Decoded</label>
						<textarea class="form-control" name="" id="decoded" rows="8"></textarea>
						<div class="sep20"></div>
						<button class="btn_smart w-100" type="submit">Enviar</button>
					</form>
				</div>
			</div>
		</div>
		<style>
			label {
			    font-weight: 500;
			    font-size: 14px;
			    margin-bottom: 2px;
			}
			h1{
				font-size: 22px;
    		margin-bottom: 25px;
			}
		</style>
</body>
<script>
	$(document).ready(function() {
		$('#params').change(function(event) {
			var text=$(this).val();
			$('#decoded').text(atob(text));
		});
	});
</script>

</html>





