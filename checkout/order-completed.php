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

$_SESSION['nivel_dir'] = 5;

include('../config/db_connect.php');
include('../class/userClass.php');
include('../class/checkoutClass.php');
$checkout = new Checkout();
$userClass = new userClass();

include_once('../assets/lib/class.carrito.php');
$carrito = new Carrito();

$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma = $id_idioma_global;

$idioma_url = $checkout->obten_idioma($id_idioma)->idioma;
// echo $idioma_url;
$moneda_obj = $userClass->obtener_moneda();
$moneda = $moneda_obj->moneda;

include_once('../includes/nivel_dir.php');
include_once('../includes/vocabulario.php');

if ($carrito->articulos_total() == 0) {
	header("Location: /$vocabulario_url_tienda_microcemento");
}

?>

<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>Pedido finalizado | Smartcret</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="language" content="es-ES">
		<meta name="description" content="Pedido completado">
		<!-- Etiquetas META SEO -->
		<?php //include_once ( './includes/bloque_canonical.php' ) ?>
		<!-- Estilos CSS -->
		<link rel='stylesheet' href='../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel="icon" href="assets/img/favicon.png">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="preload" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" as="style">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
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
		<style>
			#boton-arriba {
				display: none!important;
			}
			table.carrito .datos img {
			    height: 120px;
			    width: auto;
			}
		</style>
	</head>
	<body class="pedido-finalizado">
	<!-- Header - Inicio -->
	<?php include('../includes/header.php');

	$met_pago = obten_metodo_pago ( $_SESSION['smart_user']['metodo_pago'] )->id;

	?>
	<!-- Header - Fin -->

	<section class="sec-fin">
		<div class="txt-finalizado">
			<div class="txt">
				<h1><?php echo $vocabulario_pedido_finalizado ?> <?php if( isset($_SESSION['ref_pedido'] ) ) echo $_SESSION['ref_pedido'] ?></h1>
				<p style="padding-left:3%;text-align:center;"><?php echo $vocabulario_preparando_pedido ?></p>
				<p style="padding-left:3%;text-align:center;"><?php echo $vocabulario_dicen_que_cambiar ?></p>
			</div>
		</div>
<div class="container listado">
	<div class="row">
		<div class="col-md-10 offset-1">
			<div class=" table-responsive">
				<table class="table carrito finalizado">
					<thead>
						<tr>
							<th colspan="2" class="b-top0 "><?php echo $vocabulario_articulo ?></th>
							<th class="b-top0 der"><?php echo $vocabulario_unidades ?></th>
							<th class="b-top0 der"><?php echo $vocabulario_precio ?></th>
							<th class="b-top0 der" style="min-width: 108px"><?php echo $vocabulario_total ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if($carrito->articulos_total() > 0) {
						  $carro = $carrito->get_content();
							foreach($carro as $producto) { ?>

								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $producto["img"] ?>" alt="<?php echo $producto["nombre"] ?>"></td>
									<td><div class="desc"><?php echo $producto["nombre"] ?></div></td>
									<td class="td-unidades">
										<?php echo $producto["cantidad"] ?>
									</td>
									<td><?php echo formatea_importe($producto["precio"])?> <?php echo $moneda ?></td>
									<td><?php echo formatea_importe($producto["cantidad"] * $producto["precio"]) ?> <?php echo $moneda ?></td>
								</tr>

						<?php }
							} else { ?>


							<?php } ?>

							<?php $gastos_envio = $checkout->calcula_gastos_envio_pedido( $_SESSION['smart_user']["dir_envio"] ); ?>

							<tr class="total envios">
								<td colspan="4" class="der"><?php echo $vocabulario_subtotal_productos ?></td>
								<td><?php echo formatea_importe( $carrito->precio_total() ) ?> <?php echo $moneda ?></td>
							</tr>

							<?php if( isset ( $_SESSION['codigo_descuento']['nombre'] ) && isset ( $_SESSION['codigo_descuento']['valor'] ) )  {

								include ( './includes/bloque_codigo_descuento_finalizado.php' );

							} ?>

							<tr class="total envios">
								<td colspan="4" class="der"><?php echo  $vocabulario_gastos_envio ?>:</td>
								<td><?php echo formatea_importe( $gastos_envio ) ?> <?php echo $moneda ?></td>
							</tr>
							<tr class="total">
								<td colspan="4" class="der total"><?php echo $vocabulario_total ?>: <?php if( $_SESSION['user_ubicacion'] != 'US') { ?><span class="peque">(<?php echo $vocabulario_IVA ?>)</span>:<?php } ?></td>
								<td><b><?php echo formatea_importe( $carrito->precio_total() + $gastos_envio - $importe_descuento ) ?> <?php echo $moneda ?></b></td>
							</tr>

							<?php

							/****** INICIO - comprobacion del tipo de impuesto del pedido ******/

							$importe_final = formatea_importe( $carrito->precio_total() + $gastos_envio - $importe_descuento );
							$tipo_impuesto = obten_tipo_impuesto_envio ( $_SESSION['smart_user']['dir_facturacion'] ,  $_SESSION['smart_user']['dir_envio'] );
							$pais_envio = obten_dir_envio( $_SESSION['smart_user']["dir_envio"] )->pais;

							//****** INICIO - bloque descuento de iva *****//

							// Tipo impuesto 2, 3, 6 - Exportaciones de bienes [0%] (Canarias, Unión Europea con VIES y resto de paises)
							if ( $_SESSION['smart_user']['dir_envio'] && ( $tipo_impuesto == 2 || $tipo_impuesto == 3 || $tipo_impuesto == 6 ) && ( $_SESSION['user_ubicacion'] != 'US') ) {

								$iva_es = obten_iva_books ( "ES" );
								$importe_descuento_iva = $importe_final - ( $importe_final / ( 1 + ( $iva_es->iva / 100 ) ) );

								?>

								<tr class="descuento_iva total">
									<td colspan="3"></td>
									<td class="izq iva"><?php echo $vocabulario_descuento_iva ?><span class="peque"> (-21%)</span>:</td>
									<td class="der">- <?php echo formatea_importe( $importe_descuento_iva ) ?> <?php echo $moneda ?></td>
								</tr>
								<tr class="descuento_iva total">
									<td colspan="3"></td>
									<td class="izq"><b><?php echo $vocabulario_total_a_pagar ?></b><span class="peque"></span>:</td>
									<?php  if( $carrito->articulos_total() > 0 ) {
										$importe_final = $carrito->precio_total() + $gastos_envio - $importe_descuento - $importe_descuento_iva;
										?>
										<td class="der"><b><?php echo formatea_importe( $importe_final ) ?> <?php echo $moneda ?></b></td>
									<?php }else { ?>
										<td class="der"><b>0.00 <?php echo $moneda ?></b></td>
									<?php } ?>
								</tr>
							<?php }

							//VIES - Se supera el importe max anual - Aplicar IVA de país de envío
							else if ( $_SESSION['smart_user']['dir_envio'] && $tipo_impuesto ==5 ) {

								$iva_es = obten_iva_books ( "ES" );
								$importe_descuento_iva = $importe_final - ( $importe_final / ( 1 + ( $iva_es->iva / 100 ) ) );
								$nombre_pais = obten_nombre_pais ( $pais_envio );
								$importe_final_sin_iva_es = $importe_final - $importe_descuento_iva;
								$impuesto_iva_pais = obten_iva_pais ( $pais_envio );

								?>

								<tr class="descuento_iva total">
									<td colspan="3"></td>
									<td class="izq iva"><b><?php echo $vocabulario_impuesto_sobre_iva_espana?><span class="peque"> (-21%)</b></span>:</td>
									<td class="der">- <?php echo formatea_importe( $importe_descuento_iva ) ?> <?php echo $moneda ?></td>
								</tr>
								<tr class="descuento_iva total">
									<td colspan="3"></td>
									<td class="izq iva"><b><?php echo $vocabulario_impuesto_sobre_iva?> <?php echo $nombre_pais ?><span class="peque"> (+<?php echo $impuesto_iva_pais ?>%)</b></span>:</td>
									<td class="der">+ <?php echo formatea_importe( $importe_final_sin_iva_es * (1+$impuesto_iva_pais/100) -$importe_final_sin_iva_es ) ?> <?php echo $moneda ?></td>
								</tr>
								<tr class="descuento_iva total">
									<td colspan="3"></td>
									<td class="izq"><b><?php echo $vocabulario_total_a_pagar ?></b><span class="peque"></span>:</td>
									<?php  if( $carrito->articulos_total() > 0 ) { ?>
										<td class="der"><b><?php echo formatea_importe( $importe_final_sin_iva_es * (1+$impuesto_iva_pais/100) ) ?> <?php echo $moneda ?></b></td>
									<?php }else { ?>
										<td class="der"><b>0.00 <?php echo $moneda ?></b></td>
									<?php } ?>
								</tr>

							<?php }
							?>

					</tbody>
				</table>

				<div class="sep20"></div>

				<div class="">
				<table class="table">

					<tbody>
						<?php

							$res = obten_datos_user($_SESSION['smart_user']['id']);
							$res2 = obten_dir_envio_user ( $_SESSION['smart_user']['dir_envio'] );
							$res3 = obten_dir_fact_user ( $_SESSION['smart_user']['dir_facturacion'] );
							$res4 = obten_metodo_pago ( $_SESSION['smart_user']['metodo_pago'] );

							$prov_envio = obten_nombre_provincia ( $res2->provincia );
							$pais_envio = obten_nombre_pais ( $res2->pais ) . ' (' . $res2->pais  . ')';

							$prov_factura = obten_nombre_provincia ( $res3->provincia );
							$pais_factura = obten_nombre_pais ( $res3->pais ) . ' (' . $res3->pais  . ')';

						?>
						<tr class="datos izq dir">
							<td rowspan="1" colspan="2"><?php echo $vocabulario_metodo_pago_solo ?>:</td>
							<td colspan="1" class="izq dat">
								<i class="fas fa-euro-sign"></i>
								<?php echo $res4->nombre  ?><br>
								<?php if( $res4->id == 1) { ?>
									<i class="fas fa-question"></i>
									<span class="txt-transferencia">Has seleccionado pago por transferencia. Debes realizar la transferencia en los próximos 3 dias y enviarnos el justificante donde aparezcan los datos, indicando Nombre y Apellidos, DNI y Nº de pedido. <br>Nº de cuenta: <strong>ES42 2100 4145 9122 00063362</strong></span>
								<?php  } ?>

								</td>
						</tr>
						<tr class="datos izq dir">
							<td rowspan="1" colspan="2"><?php echo $vocabulario_datos_envio ?>:</td>
							<td colspan="1" class="izq dat">
								<i class="fa fa-user"></i>
								<?php echo $res->nombre . ', ' . $res->apellidos ?><br>
								<i class="fa fa-phone"></i>
								<?php echo $res->telefono ?><br>
									<i class="fa fa-map-marker-alt"></i>
									<?php echo $res2->direccion . ', ' . $res2->cp . ', ' . $res2->localidad . ', ' . $res2->provincia . ', ' . $res2->pais ?>
								</td>
						</tr>
						<tr class="datos izq dir">
							<td rowspan="1" colspan="2"><?php echo $vocabulario_datos_facturacion ?>:</td>
							<td colspan="1" class="izq dat">
								<i class="fa fa-user"></i>
								<?php echo $res3->nombre . ', ' . $res3->apellidos ?><br>
								<i class="fa fa-phone"></i>
								<?php echo $res3->telefono ?><br>
									<i class="fa fa-map-marker-alt"></i>
									<?php echo $res3->direccion . ', ' . $res3->cp . ', ' . $res3->localidad . ', ' . $res3->provincia . ', ' . $res3->pais ?>
								</td>
						</tr>
					</tbody>
				</table>
				<div class="btn acepta_fin">
					<button type="button" class="continuar cart_btn verde_claro" onclick="window.location.href='./'">Aceptar</button>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
</section>

<section style="max-width: 900px;margin: 0 auto;">


</section>

	<!-- Footer - Inicio -->
	<?php include('../includes/footer.php'); ?>
	<!-- Footer - Fin -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

  <?php
		$carrito->destroy();
		$_SESSION['codigo_descuento']=array();
		unset($_SESSION['codigo_descuento']);
	?>
	</body>
</html>
