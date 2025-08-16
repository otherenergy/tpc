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
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<div class="sep60"></div>
					<!-- <form action="./assets/return/notifica.php?lang=en" method="POST"> -->
					<!-- <form action="https://www.smartcret.com/assets/return/notifica.php?lang=en" method="POST"> -->
						<h1>Generar SO</h1>
					<form action="notifica_SO.php" method="POST">
						<label>Nº referencia pedido</label>
<!-- 						<input class="form-control" type="text" name="Ds_Order"> -->
						<select class="form-control" name="datos_pedido">
							<option value="-">Seleccionar</option>
							<?php
							  $sql="SELECT * FROM pedidos_temp ORDER by id DESC LIMIT 20";
								$res=consulta($sql, $conn);
								while($reg=$res->fetch_object()) { ?>
									<option value="<?php echo $reg->redsys_num_order . '|' . $reg->ref_pedido ?>"><?php echo  '[ ' . $reg->ref_pedido . ' ] '. '   -  [ ' . $reg->total_pagado . '€ ]  '  . ' [ ' . $reg->fecha_creacion . ' ] [ ' . $reg->idioma . ' ]'; ?></option>
								<?php
							  }
							?>
						</select>
						<div class="sep10"></div>
						<label>Estado de operación</label>
						<select class="form-control" name="Ds_Response">
							<option value="0000">Aceptada</option>
							<option value="999">Denegada</option>
						</select>
						<div class="sep20"></div>
						<button class="btn_smart w-100" type="submit">Confirmar</button>
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
</html>





