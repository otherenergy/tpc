<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$_SESSION['nivel_dir'] = 1;

include ('../config/db_connect.php');
include('../includes/nivel_dir.php');
include('../class/userClass.php');
include('../class/checkoutClass.php');
include('../class/portes.php');
include_once('../assets/lib/class.carrito.php');
include_once('../assets/lib/funciones.php');

$rutaServer = $_ENV['RUTA_SERVER'];

$checkout = new Checkout();
$userClass = new userClass();
$portes = new Portes();
$carrito = new Carrito();
// include('../assets/lib/calcula_transporte.php');
$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma = $id_idioma_global;

$idioma_url = $checkout->obten_idioma($id_idioma)->idioma;
$moneda_obj = $userClass->obtener_moneda();
$moneda = $moneda_obj->moneda;

include_once('../includes/vocabulario.php');

if ($_SESSION['smart_user']['login'] == 0){
	header('Location: ../' .$idioma_url. '/login');
    exit;
}

if ($carrito->articulos_total() == 0) {
	header("Location: $rutaServer/$vocabulario_url_tienda_microcemento");
}

$currency = ( $_SESSION['user_ubicacion'] == 'US' ) ? 'USD' : 'EUR';

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title><?php echo $vocabulario_tramitacion_pedido ?> | Smartcret</title>
	<meta name="description" content="<?php echo $vocabulario_tramitacion_pedido ?> | Smartcret">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow">
	<link rel="icon" href="../assets/img/favicon.png">
	<link rel='stylesheet' href='../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
	<link rel='stylesheet' href='./includes/css_checkout.css?<?php echo rand() ?>' type='text/css' />

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="preload" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" as="style">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

	<!-- SweetAlert2 CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

	<!-- SweetAlert2 JS -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script src="https://www.paypal.com/sdk/js?client-id=AQg-_D8yxeiqa5iMcJPsrmoPhAJd3emI53TJaScr1b7aFoLI27lhWfrae-93ZnaUD1VYuu9Th0s9RmE9&disable-funding=card&currency=<?php echo $currency ?>"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-169820052-1"></script>

	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'UA-169820052-1');
		gtag('config', 'AW-1001206950');
	</script>

	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-1001206950"></script>

	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() {
			dataLayer.push(arguments);
		}
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
		.table.carrito tr.datos td {
			min-width: 33px;
		}
	</style>
</head>

<body class="carrito">

	<?php
    $reg = obten_datos_user($_SESSION['smart_user']['id']);
    // var_dump($reg);
    if ($reg->distribuidor == 1){
        if ($reg->apellidos == '' || $reg->nombre == '' || $reg->nif_cif == '' || $reg->telefono == '') {?>
        <script>
            muestraMensajeLn('Por favor, completa tus datos personales');
            setTimeout(function() {
                $('.perso-dat button').click();
            }, 2000);
        </script>
    <?php
        }
    }else{
        if ($reg->apellidos == '' || $reg->nombre == '') { ?>
        <script>
            muestraMensajeLn('Por favor, completa tus datos personales');
            setTimeout(function() {
                $('.perso-dat button').click();
            }, 2000);
        </script>
    <?php
        }
    }?>


	<header>
		<div id="mensaje">
			<p></p>
		</div>
		<div class="nav-header-principal">
			<div class="div-header-principal">
				<a class="enlace-logo-smartcret" href="../">
					<img style="margin-left: 10px;" width="120px" height="65px" src="../assets/img/logo-smartcret.png" alt="Smartcret" title="Smartcret">
				</a>
			</div>
		</div>

	</header>
	<?php //include_once('../includes/geolocation.php'); ?>

	<?php
	// if ( $carrito->articulos_total() == 0 ) header("Location: ../");
	if (!isset($_SESSION['smart_user']['dir_envio'])) $_SESSION['smart_user']['dir_envio'] = $checkout->obten_dir_envio_predeterminado($_SESSION['smart_user']['id']);

	// echo  $_SESSION['smart_user']['dir_envio'];
	if (!isset($_SESSION['smart_user']['dir_facturacion'])) $_SESSION['smart_user']['dir_facturacion'] = $checkout->obten_dir_facturacion_predeterminado($_SESSION['smart_user']['id']);
	$_SESSION['smart_user']['idioma'] = $_SESSION['idioma_url'];
	?>

	<div class="container listado">
		<div class="row">
			<div class="contenedor_tramitacion_pedido">

				<h1 style="margin-top: 0px; margin-bottom:25px; font-size: 35px;"><?php echo $vocabulario_tramitacion_pedido ?></h1>

				<div class="table-responsive">
					<h2><?php echo $vocabulario_datos_personales ?></h2>
					<tbody class="perso-dat">
						<tr class="datos">
							<?php
							$reg = $checkout->obten_datos_user($_SESSION['smart_user']['id'])[0];

							if ($reg->apellidos == '') { ?>
								<td colspan="2" class="izq">
									<p style="color: red;"><?php echo $vocabulario_debe_introducir_datos ?></p>
								</td>
								<td>
									<button style="margin-top: 10px;" class="cart_btn btn-tabla" onclick="openModal( 'form_dat_pers', <?php echo $reg->uid ?>, 'edit' ) "><?php echo $vocabulario_introducir_datos ?></button>
								</td>
							<?php } else { ?>
								<td colspan="2" class="izq">
									<?php echo '<i class="fa fa-user"></i>' . $reg->nombre . ' ' . $reg->apellidos . '<br><i class="fa fa-phone"></i>' . $reg->telefono . '<br><i class="fa fa-id-card"></i>' . $reg->nif_cif  ?></td>
									<br>
									<td>
										<button style="margin-top: 10px;" class="cart_btn btn-tabla" onclick="openModal( 'form_dat_pers', <?php echo $reg->uid ?>, 'edit' ) "><i class="fa fa-edit"></i><?php echo $vocabulario_editar ?></button>
									</td>
								<?php } ?>
							</tr>
						</tbody>
					</div>

				<br>

				<div class="table-responsive">

					<h2><?php echo $vocabulario_mi_pedido ?></h2>
					<br>

					<div class="carrito">
						<?php
						if ($carrito->articulos_total() > 0) {
							$carro = $carrito->get_content();

							foreach ($carro as $producto) { ?>

								<div class="datos producto-item" uid="<?php echo $producto["unique_id"] ?>">
									<div class="producto-imagen">
										<img src="../assets/img/productos/<?php echo $producto["img"] ?>" alt="<?php echo $producto["nombre"] ?>">
									</div>
									<div class="contenedor-prod-desc-cant">
										<div class="producto-descripcion">
											<div class="desc"><?php echo $producto["nombre"] ?></div>

										</div>
										<div class="producto-precio">
											<?php echo $checkout->formatea_importe($producto["precio"]) ?><?php echo $moneda ?>
										</div>
										<div class="td-unidades producto-cantidad">
											<div class="contadores input-group no-select">
												<div class="input-group-btn btn-menos">
													<div class="menos"><i class="fa fa-minus"></i></div>
												</div>
												<input type="text" id="cant" name="cant" class="inputcajas form-control input-number" value="<?php echo $producto["cantidad"] ?>" min="1" max="999" readonly="true" />
												<div class="input-group-btn btn-mas">
													<div class="mas"><i class="fa fa-plus"></i></div>
												</div>
											</div>
										</div>
										<div class="producto-eliminar">
											<p class="p-producto-eliminar" onclick="confirmarEliminacionArticulo('<?php echo $producto["unique_id"] ?>')" title="<?php echo $vocabulario_eliminar_articulo ?>"><?php echo $vocabulario_eliminar_articulo ?></p>
										</div>

									</div>
								</div>
			  <?php }

						} else { ?>

						<?php } ?>

					</div>

					<p class="num-articulos p-btn-articulos-carrito">

						<?php	$num_articulos = $carrito->articulos_total();	?>

						<a class="seguir cart_btn out btn-volver-tienda" href="../<?php echo $idioma_url ?>/<?php echo $vocabulario_enlace_tienda ?>"><?php echo $vocabulario_continuar_comprando ?></a>
						<p class="p_num_articulos"><?php echo $vocabulario_total_articulos_2puntos ?> <?php echo $num_articulos ?></p>
					</p>

				</div>

				<br>
				<div class="table-responsive">
					<h2><?php echo $vocabulario_direccion_envio_checkout ?></h2>
					<br>
					<table class="table carrito">
						<tbody>
							<?php
							$sql = "SELECT
											DE.*,
											PV.nombre_prov as nombre_prov,
											PA.nombre as nombre_pais,
											PA.idioma
										FROM
											datos_envio DE
										LEFT JOIN
											provincias PV ON DE.provincia = PV.id_prov
										JOIN
										   paises PA ON PA.cod_pais = DE.pais
										WHERE
											DE.id_cliente =? AND DE.activo=1";


							$arguments = [$_SESSION['smart_user']['id']];
							$result = $checkout->executeQuery($sql, $arguments);

							if ($result) {

								foreach ($result as $reg) {
									$provincia = ($reg->pais == 'ES') ? $reg->nombre_prov . ', ' : '';
								?>

								<tr class="datos">
									<td colspan="2" class="izq">
										<input type="checkbox" class="dir env" name="dir-env" value="<?php echo $reg->id ?>" <?php if ($reg->predeterminado == 1) echo 'checked' ?> checked_idioma="<?php echo $reg->pais ?>">
										<i class="fa fa-map-marker-alt"></i><?php echo $reg->direccion . ', ' . $reg->cp . ', ' . $reg->localidad . ', ' . $provincia . $reg->nombre_pais ?>
									</td>
									<td class="der">
										<button class="cart_btn btn-tabla" onclick="openModal('form_dir_envio', <?php echo $reg->id ?>, 'edit')">
											<i class="fa fa-edit"></i><?php echo $vocabulario_editar; ?>
										</button>
									</td>
									<td class="td_cruz">
										<i class="fa fa-times transform" onclick="eliminaDireccion('<?php echo $reg->id ?>', 'datos_envio', '../')" title="<?php echo $vocabulario_eliminar_direccion ?>"></i>
									</td>
								</tr>

								<?php }

							} else { ?>

								<td text-align="center" colspan="3" class="no_item"><?php echo $vocabulario_todavia_no_hay_direccion ?></td>

							<?php } ?>

						</tbody>
					</table>

					<div class="btn-new">
						<button id="new-dir-env" class="cart_btn btn-tabla" onclick="openModal( 'form_dir_envio', <?php echo $_SESSION['smart_user']['id'] ?>, 'new' )"><i class="fa fa-plus"></i><?php echo $vocabulario_nueva_direccion ?></button>
					</div>

				</div>

				<br>
				<div class=" table-responsive">
					<h2><?php echo $vocabulario_direccion_facturacion ?></h2>

					<table class="table carrito">

						<tbody>

								<?php if (!isset($_SESSION['smart_user']['tipo_factura'])) $_SESSION['smart_user']['tipo_factura'] = 1; ?>

								<?php
								// echo $carrito->precio_total() ;
								if ($carrito->precio_total() < 3000) { ?>

									<tr class="sel_tipo_factura">
										<th colspan="3" class="izq"><?php echo $vocabulario_tipo_factura ?><br>
										<?php if ($_SESSION['smart_user']['tipo_factura'] == 2){ ?>
											<small class="txt-min"><?php echo $vocabulario_asegurese_dni_factura_nominal ?></small><br>
										<?php } ?>

											<div class="tipo_factura">
												<input type="radio" value="1" name="tipo_factura" <?php echo ($_SESSION['smart_user']['tipo_factura'] == 1) ? 'checked' : ''; ?>> <?php echo $vocabulario_factura_simplificada ?>
												<br>
												<input type="radio" value="2" name="tipo_factura" <?php echo ($_SESSION['smart_user']['tipo_factura'] == 2) ? 'checked' : ''; ?>> <?php echo $vocabulario_factura_nominal ?>
											</div>
										</th>
										<th class="izq"></th>
									</tr>

								<?php }

								?>

							<?php
								$pais_envio = $checkout->obten_dir_envio( $_SESSION['smart_user']["dir_envio"])[0]['pais'];

								if ($pais_envio == 'ES'){

							?>

							<tr>
								<td colspan="3">
									<small style="color:black;" class="txt-min"><?php echo $vocabulario_mensaje_compras_superiores_3000_1 ?></small><br>
									<small style="color:black;" class="txt-min"><?php echo $vocabulario_mensaje_compras_superiores_3000_2 ?></small>
								</td>
							</tr>

							<?php
								}

							$sql = "SELECT
											DF.*,
											PV.nombre_prov as nombre_prov,
											PA.nombre as nombre_pais
										FROM
											datos_facturacion DF
										LEFT JOIN
											provincias PV ON DF.provincia = PV.id_prov
										JOIN
										   paises PA ON PA.cod_pais = DF.pais
										WHERE
											DF.id_cliente =? AND DF.activo=1";

							$arguments = [$_SESSION['smart_user']['id']];
							$result = $checkout->executeQuery($sql, $arguments);

							if ($result) {

								foreach ($result as $reg) {

								?>

								<tr class="datos">
									<td colspan="2" class="izq"><input type="checkbox" class="dir fact" value="<?php echo $reg->id ?>" <?php if ($reg->predeterminado == 1) echo 'checked' ?> checked_idioma="<?php echo $reg->pais ?>"><i class="fa fa-map-marker-alt"></i><?php echo $reg->direccion . ', ' . $reg->cp . ', ' . $reg->localidad . ', ' . $reg->nombre_prov . ', ' . $reg->nombre_pais ?><br>
										<?php echo '<div class="perso-dat"><i class="fa fa-user"></i>' . $reg->nombre . ' ' . $reg->apellidos . ' (' . $reg->tipo_factura . ') ' . '<br><i class="fa fa-phone"></i>' . $reg->telefono . '<br><i class="fa fa-id-card"></i>' . $reg->nif . '</div>' ?>
									</td>
									<td class="der"><button class="cart_btn btn-tabla" onclick="openModal( 'form_dir_fac', <?php echo $reg->id ?>, 'edit' )"><i class="fa fa-edit"></i><?php echo $vocabulario_editar; ?></button>
									</td>
									<td class="td_cruz"><i class="fa fa-times transform" onclick="eliminaDireccion('<?php echo $reg->id ?>', 'datos_facturacion', '../')" title="<?php echo $vocabulario_eliminar_direccion ?>"></i>
									</td>
								</tr>
								<?php }

							} else { ?>

								<td text-align="center" colspan="3" class="no_item"><?php echo $vocabulario_nueva_direccion ?></td>

							<?php } ?>

						</tbody>

					</table>

					<div class="btn-new">
						<button id="new-dir-fac" class="cart_btn btn-tabla" onclick="openModal( 'form_dir_fac', <?php echo $_SESSION['smart_user']['id'] ?>, 'new' )"><i class="fa fa-plus"></i><?php echo $vocabulario_nueva_direccion ?></button>

						<button id="copy-dir-fac" class="cart_btn btn-tabla" onclick="igualDirEnvio()"><?php echo $vocabulario_igual_direccion_envio ?></button>

					</div>

					<script>
						function igualDirEnvio() {
							$.ajax({
									url: '../class/control.php',
									type: 'post',
									dataType: 'text',
									data: {
										accion: 'igual-dir-envio'
									}
								})
								.done(function(result) {
									// alert(result)
									var result = $.parseJSON(result);
									muestraMensajeLn(result.msg);
									setTimeout(function() {
										location.reload();
									}, 2000);
								})
								.fail(function() {
									alert("error");
								});
						}
					</script>
				</div>
				<br>


				<div class=" table-responsive">
					<h2><?php echo $vocabulario_metodo_pago ?></h2>
					<br>
					<table class="table carrito">
						<tbody>

							<?php

							$sql = "SELECT
										  MP.id,
										  MP.tipo,
										  MP.logo,
										  MPI.idioma,
										  MPI.nombre AS nombre_idioma,
										  MP.activo
										FROM
										  metodos_pago MP
										JOIN
										  metodos_pago_idioma MPI ON MP.tipo = MPI.tipo
										WHERE
										  MP.activo=1 AND MP.id=2 AND MPI.idioma = 1";


							$arguments = [];
							$result = $checkout->executeQuery($sql, $arguments);

							if ($result) {
								foreach ($result as $reg) {

							?>
									<tr class="datos">
										<td class="izq"><input type="checkbox" class="metodo_pago" name="metodo_pago" value="<?php echo $reg->id ?>" <?php if (isset($_SESSION['smart_user']['metodo_pago']) && $reg->id == $_SESSION['smart_user']['metodo_pago']) echo 'checked' ?>>
										<img class="logo_pago" src="../assets/<?php echo $reg->logo ?>" alt="Redssys, visa, mastercard"></td>
										<td><?php echo $vocabulario_tarjeta_credito ?></td>
									</tr>

							<?php }
							}
							?>
						</tbody>
					</table>

				</div>
				<br><br>

			</div>

			<div class="contenedor_separacion_checkout">
				<div class="sepv"></div>
			</div>

			<div class="contenedor_resumen_checkout">

				<div class="resume_box">

					<table class="table resumen ">
						<thead>
							<tr>
								<th colspan="2" style="border-bottom: 2px solid black !important;"><?php echo $vocabulario_resumen ?></th>

							</tr>
						</thead>


						<?php if ($carrito->articulos_total() > 0 && $pais_envio) {

							$gastos_envio = $portes->calcula_portes( $_SESSION['smart_user']['dir_envio'], $carrito );
							$importe_descuento = 0;

						?>
							<tbody>

								<tr>
									<td class="izq"><?php echo $vocabulario_total_articulos ?><span class="peque"></span>:</td>
									<td class="der"><?php echo $checkout->formatea_importe($carrito->precio_total()) ?> <?php echo $moneda ?></td>
								</tr>

								<tr>
									<td class="izq"><?php echo $vocabulario_gastos_envio ?>: <?php echo ($gastos_envio == 0) ? '<span class="envio_gratis">' . $vocabulario_envio_gratis . '</span>' : '' ?></td>
									<td class="der"> <?php echo $checkout->formatea_importe($gastos_envio) ?> <?php echo $moneda ?></td>
								</tr>

								<?php if (!isset($_SESSION['codigo_descuento'])) { ?>

									<form class="desc" action="">
										<tr class="descuento">
											<td class="izq">
												<input id="nombre_descuento" type="text" name="cupon" placeholder="<?php echo $vocabulario_cupon_descuento ?>">
											</td>
											<td class="der">
												<button type="submit" id="aplica_descuento" class="aplica cart_btn out"><?php echo $vocabulario_aplicar ?></button>
											</td>
										</tr>
									</form>

								<?php } else {

									include('./includes/bloque_codigo_descuento.php');

								} ?>

							</tbody>
						<?php }

						// $carro = $carrito->get_content();

						// foreach ($carro as $producto) {
						// 	echo $producto['id'];
						// 	$obten_descuento_producto = $checkout->obten_descuento_producto($producto['id'], $_SESSION['user_ubicacion']);
						// 	echo $obten_descuento_producto;
						// }
						?>

						<tfooter>
							<tr class="total">
								<td class="izq"><b><?php echo $vocabulario_total ?></b> <span class="peque"><?php echo $vocabulario_iva_incluido ?></span>:</td>
								<?php if ($carrito->articulos_total() > 0) {
									$importe_final = $carrito->precio_total() + $gastos_envio - $importe_descuento;
								?>
									<td class="der"><b><?php echo $checkout->formatea_importe($importe_final) ?> <?php echo $moneda ?></b></td>
								<?php } else { ?>
									<td class="der"><b>0.00 <?php echo $moneda ?></b></td>
								<?php } ?>
							</tr>

							<?php

							/******* bloque descuento de iva  ******/
							include('./includes/bloque_descuento_iva.php');

							?>

						</tfooter>
					</table>

					<div id="paypal-button-container"></div>

					<?php
						if ($checkout->obten_dir_fact_user($_SESSION['smart_user']['dir_facturacion']) != ''){
							$array_facturacion = $checkout->obten_dir_fact_user($_SESSION['smart_user']['dir_facturacion']);
							$nif_user_fac = $array_facturacion->nif;
							$nif_user_tel = $array_facturacion->telefono;
							$id_user_fac = $array_facturacion->id;
						}else{
							$nif_user_fac = 'NULL';
							$id_user_fac = 'NULL';
							$nif_user_tel = 'NULL';
						};

					?>

					<?php if ($carrito->articulos_total() > 0) { ?>
						<button type="button" class="continuar cart_btn"><?php echo $vocabulario_pagar_finalizar ?></button>
					<?php } else { ?>
						<button class="continuar cart_btn disabled" onclick="void(0)"><?php echo $vocabulario_pagar_finalizar ?></button>
					<?php } ?>
					<div class="txt_gastos_envio">

					</div>
					<div class="txt_gastos_envio">
						<?php echo $vocabulario_plazos_envio ?>
					</div>
					<div class="metodos-pago">
						<img src="../assets/img/metodos-pago.jpg" alt="Redssys, visa, mastercard">
					</div>


					<div>
						<?php
						$idioma = $_SESSION['id_idioma'];
						$user_ubicacion = $_SESSION['user_ubicacion'];

						require_once('../assets/lib/redsys/include/functions.php');

						$orderAmmount = $checkout->formatea_importe($importe_final);
						$orderNumber = redsys_ramdom_order();
						$orderDescription = 'Pedido Smartcret';

						echo "<div class = 'boton_redsys'>";
						echo redsys_button(
							$orderAmmount,
							$orderNumber,
							$orderDescription,
							$checkout->obten_ref_nuevo_pedido()
						);
						echo "</div>";
						?>
					</div>
				</div>
			</div>

			<?php

			$usuario = $checkout->obten_datos_user( $_SESSION['smart_user']['id'] )[0];
			$envio = $checkout->obten_dir_envio( $_SESSION['smart_user']['dir_envio'] )[0];
			$factura = $checkout-> obten_dir_facturacion( $_SESSION['smart_user']['dir_facturacion'] )[0];

			?>

			<script>
				paypal.Buttons({
					createOrder: function(data, actions) {
					return actions.order.create({
				      purchase_units: [{
				        amount: {
				          value: '<?php echo $orderAmmount ?>'
				        },
				        shipping: {
				          name: {
				            full_name: '<?php echo $usuario->nombre . ' ' . $usuario->apellidos ?>'
				          },
				          address: {
				            address_line_1: '<?php echo $envio['direccion'] ?>',
				            address_line_2: '',
				            admin_area_2: '<?php echo $envio['localidad'] ?>',
				            admin_area_1: '<?php echo $envio['provincia'] ?>',
				            postal_code: '<?php echo $envio['cp'] ?>',
				            country_code: '<?php echo $envio['pais'] ?>'
				          }
				        }
				      }],
				      payer: {
				        name: {
				          given_name: '<?php echo $factura->nombre ?>',
				          surname: '<?php echo $factura->apellidos ?>'
				        },
				        email_address: '<?php echo $factura->email ?>',
				        address: {
				          address_line_1: '<?php echo $factura->direccion ?>',
				          address_line_2: '',
				          admin_area_2: '<?php echo $factura->localidad ?>',
				          admin_area_1: '<?php echo $factura->provincia ?>',
				          postal_code: '<?php echo $factura->cp ?>',
				          country_code: '<?php echo $factura->pais ?>'
				        }
				      },
				      application_context: {
				        brand_name: 'Smartcret',
				        shipping_preference: 'SET_PROVIDED_ADDRESS'
				      }
				    }).then(function(orderId) {
				      console.log('Order ID:', orderId);
				      pedidoTemporalPaypal( orderId );
				      return orderId;
				    });
				  },
				  onClick: function(data, actions) {

				  	if ($('.dir.fact').is(':checked') && $('.dir.env').is(':checked')) {

							var checkedIdiomaEnv = $('.dir.env:checked').attr('checked_idioma');
							var checkedIdiomaFact = $('.dir.fact:checked').attr('checked_idioma');
							var user_ubicacion = '<?php echo $_SESSION['user_ubicacion'] ?>';

							if (user_ubicacion != checkedIdiomaEnv){

								var checkedIdiomaEnv = $('.dir.env:checked').attr('checked_idioma');
								console.log(checkedIdiomaEnv);
								muestraMensajeLn(`<?php echo $vocabulario_direccion_de_envio_no_coincide_con_geolocalizacion ?>`);

								setTimeout( function() {
									$.ajax({
										url: '../class/control.php',
										type: 'post',
										dataType: 'text',
										data: {accion: 'modifica_ubicacion', input_pais: checkedIdiomaEnv}
									})
									.done(function(result) {
										var result = $.parseJSON(result);
										muestraMensajeLn(result.msg);
										setTimeout(function() {
											location.reload();
										},1000);

									})
									.fail(function() {
										alert("error");
									});
								},500);

								return actions.reject();
							}

						} else {
							muestraMensajeLn(`<?php echo $vocabulario_debe_seleccionar_direccion_entrega_facturacion ?>`);
						}


					},
					onApprove: function(data, actions) {
						return actions.order.capture().then(function(details) {

							muestraMensajeMl( '<?php echo $vocabulario_pago_exito ?>' );

							guardaOperacionPaypal( details );
							guardaPedidoPaypal( details.id );

					  });
					},
					onCancel: function(data) {
					  alert('<?php echo $vocabulario_pago_cancelado ?>');
					  console.log('Pago cancelado', data);
					},
					onError: function(err) {
					  alert('<?php echo $vocabulario_error_pago ?>');
					  console.error('Error en el proceso de pago', err);
					}

				}).render('#paypal-button-container');

			</script>
		</div>

		<div class="modal fade" id="datos">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>

<script>

function confirmarEliminacionArticulo(productId) {
    Swal.fire({
        title: `<?php echo $vocabulario_estas_seguro_eliminar_carrito ?>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `<?php echo $vocabulario_si_eliminar ?>`,
        cancelButtonText: `<?php echo $vocabulario_cancelar ?>`
    }).then((result) => {
        if (result.isConfirmed) {
            eliminaArticuloListado(productId);
        }
    });
}

</script>
		<style>
.custom-modal-content {
    background-color: #f0f7e6;
    border-radius: 20px;
    padding: 18px 16px 14px;
    width: 80%;
    max-width: 500px;
    margin: auto;
    position: relative;
}

.custom-modal {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.custom-modal-header {
    font-size: 24px;
    margin-bottom: 15px;
}

.custom-modal-body {
    font-size: 18px;
    margin-bottom: 15px;
	text-align: center;
}

.custom-modal-content{
	max-width: 440px;
}

.custom-modal-body .nav-link{
	background: white;
	border-radius: 20px;
	display: flex;
    justify-content: center;
    align-items: center;
}

.custom-modal-body .fa-globe{
	font-size: 18px;
}

.custom-modal-body .form-control{
	border: none !important;
}


		</style>

		<div class="sep50"></div>
		<!-- Footer - Fin -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
		<script src="../assets/js/custom-js.js"></script>
		<!-- <script src="../assets/js/pay.js"></script> -->

		<script>
			function openModal(url, id, type) {
				// alert(url+id+type);
				var myModal = new bootstrap.Modal(document.getElementById('datos'), {
					keyboard: false
				})
				$.ajax({
						url: './includes/' + url + '.php',
						type: 'POST',
						datatype: 'html',
						data: {
							id: id,
							type: type
						}
					})
					.done(function(result) {
						$('#datos .modal-body').html(result);
						myModal.show();
					})
					.fail(function() {
						alert(`<?php echo $vocabulario_se_ha_producido_un_error ?>`);
					})
			}

		</script>

		<script>

			$('.continuar.cart_btn').click(function(e) {
				e.preventDefault();

				if ($('.dir.fact').is(':checked') && $('.dir.env').is(':checked')) {

					var checkedIdiomaEnv = $('.dir.env:checked').attr('checked_idioma');
					var checkedIdiomaFact = $('.dir.fact:checked').attr('checked_idioma');

					var user_ubicacion = '<?php echo $_SESSION['user_ubicacion'] ?>';

					if (user_ubicacion != checkedIdiomaEnv){
						var checkedIdiomaEnv = $('.dir.env:checked').attr('checked_idioma');
						console.log(checkedIdiomaEnv);
						muestraMensajeLn(`<?php echo $vocabulario_direccion_de_envio_no_coincide_con_geolocalizacion ?>`);
						setTimeout( function() {
							$.ajax({
								url: '../class/control.php',
								type: 'post',
								dataType: 'text',
								data: {accion: 'modifica_ubicacion', input_pais: checkedIdiomaEnv}
							})
							.done(function(result) {
								var result = $.parseJSON(result);
								muestraMensajeLn(result.msg);
								setTimeout(function() {
									location.reload();
								},1000);

							})
							.fail(function() {
								alert("error");
							});
						},1000);
					}
					else{

						if ('<?php echo $nif_user_fac ?>' == '' && '<?php echo $_SESSION['smart_user']['tipo_factura'] ?>' == '2' && '<?php echo $nif_user_tel ?>' == ''){

							Swal.fire({
								icon: 'warning',
								title: `<?php echo $vocabulario_informacion_requerida ?>`,
								text: `<?php echo $vocabulario_debes_introducir_dni_telefono_en_direccion ?>`,
								confirmButtonText: `<?php echo $vocabulario_aceptar?>`
							}).then((result) => {
								if (result.isConfirmed) {
									openModal('form_dir_fac', <?php echo $id_user_fac ?>, 'edit');
								}
							});

						}else if ('<?php echo $nif_user_fac ?>' == '' && '<?php echo $_SESSION['smart_user']['tipo_factura'] ?>' == '2'){

							Swal.fire({
								icon: 'warning',
								title: `<?php echo $vocabulario_informacion_requerida ?>`,
								text: `<?php echo $vocabulario_debes_introducir_dni_en_direccion ?>`,
								confirmButtonText: `<?php echo $vocabulario_aceptar?>`
							}).then((result) => {
								if (result.isConfirmed) {
									openModal('form_dir_fac', <?php echo $id_user_fac ?>, 'edit');
								}
							});

						}else if ('<?php echo $nif_user_tel ?>' == ''){

							Swal.fire({
								icon: 'warning',
								title: `<?php echo $vocabulario_informacion_requerida ?>`,
								text: `<?php echo $vocabulario_debes_introducir_telefono_en_direccion ?>`,
								confirmButtonText: `<?php echo $vocabulario_aceptar?>`
							}).then((result) => {
								if (result.isConfirmed) {
									openModal('form_dir_fac', <?php echo $id_user_fac ?>, 'edit');
								}
							});

						}else{
							finalizarPedido();
						}
					}

				} else {
					muestraMensajeLn(`<?php echo $vocabulario_debe_seleccionar_direccion_entrega_facturacion ?>`);
				}
			});

			$('input.dir.env').click(function(e) {
				$(this).prop('checked', true);
				$('input.dir.env').not(this).prop('checked', false);
				$.ajax({
					url: '../class/control.php',
					type: 'POST',
					datatype: 'html',
					data: {
						accion: 'set_dir_envio',
						idEnvio: $(this).val()
					}
				})
				.done(function(result) {
					var checkedIdiomaEnv = $('.dir.env:checked').attr('checked_idioma');
					console.log(checkedIdiomaEnv);

					$.ajax({
						url: '../class/control.php',
						type: 'post',
						dataType: 'text',
						data: {accion: 'modifica_ubicacion', input_pais: checkedIdiomaEnv}
					})
					.done(function(result) {
						var result = $.parseJSON(result);
						muestraMensajeLn(result.msg);
						setTimeout(function() {
							location.reload();
						},1000);

					})
					.fail(function() {
						alert("error");
					});


				})
				.fail(function() {
					alert(`<?php echo $vocabulario_se_ha_producido_un_error ?>`);
				})
			})

			$('input.dir.fact').click(function(e) {
				$(this).prop('checked', true);
				$('input.dir.fact').not(this).prop('checked', false);
				console.log($(this).val());

				$.ajax({
					url: '../class/control.php',
					type: 'POST',
					datatype: 'html',
					data: {
						accion: 'set_dir_facturacion',
						idEnvio: $(this).val()
					}
				})
				.done(function(result) {
					location.reload();
				})
				.fail(function() {
					alert(`<?php echo $vocabulario_se_ha_producido_un_error ?>`);
				})
			});

			$('.tipo_factura input').change(function(e) {
				$.ajax({
					url: '../class/control.php',
					type: 'POST',
						datatype: 'html',
						data: {
							accion: 'config_tipo_factura',
							tipo_factura: $(this).val()
						}
					})
					.done(function(result) {
						location.reload();
					})
					.fail(function() {
						alert('Se ha producido un error');
				})
			});

		</script>

		<script>
			setTimeout(function() {
				$('.metodo_pago').click();
			}, 1000);

			$('input.metodo_pago').click(function(e) {
				$(this).prop('checked', true);
				$('input.metodo_pago').not(this).prop('checked', false);
				$.ajax({
						url: '../class/control.php',
						type: 'POST',
						datatype: 'html',
						data: {
							accion: 'set_metodo_pago',
							metPago: $(this).val()
						}
					})
					.done(function(result) {
						// alert(result);
						// location.reload();
					})
					.fail(function() {
						alert(`<?php echo $vocabulario_se_ha_producido_un_error ?>`);
					})
			})
		</script>

		<script>
			$(document).on('click', '.del_cupon', eliminaCupon);

			function finalizarPedido( paypal_id = 0 ) {
				muestraMensajeMl(`<?php echo $vocabulario_procesando_pedido ?>`);
				$.ajax({
						url: '../class/control.php',
						type: 'POST',
						datatype: 'html',
						data: {
							accion: 'guarda_pedido_temporal',
							redsys_num_order: `<?php echo $orderNumber ?>`,
							paypal_id: paypal_id
						}
					})
					.done(function(result) {
						var result = $.parseJSON(result);
						if (result.res == 1) {
							if (result.metPago == 1) {
								muestraMensajeMl(result.msg);
								setTimeout(function() {
									$('#from').submit();
									alert(`<?php echo $vocabulario_vamos_al_tpv ?>`);
								}, 3000);
							} else if (result.metPago == 2) {
								muestraMensajeMl(result.msg);
								setTimeout(function() {
									$('#from').submit();
								}, 3000);
							}
						} else if (result.res == 0) {
							muestraMensajeLn(result.msg);
						}
					})
					.fail(function() {
						alert(`<?php echo $vocabulario_se_ha_producido_un_error ?>`);
					})
			}


			function pedidoTemporalPaypal( paypal_id ) {

				$.ajax({
						url: '../class/control.php',
						type: 'POST',
						datatype: 'html',
						data: {
							accion: 'guarda_pedido_temporal',
							redsys_num_order: `<?php echo $orderNumber ?>`,
							paypal_id: paypal_id
						}
					})
					.done(function(result) {

					})
					.fail(function() {
						alert('<?php echo $vocabulario_se_ha_producido_un_error ?>');
					})
			}

			function guardaPedidoPaypal( paypal_id ) {

				$.ajax({
						url: '../assets/return/pedido_paypal.php',
						type: 'POST',
						datatype: 'html',
						data: {
							num_order: '<?php echo $orderNumber ?>',
							paypal_id: paypal_id
						}
					})
					.done(function(result) {
						window.location.href="order-completed?from=paypal&pay_id=<?php echo $orderNumber ?>";
					})
					.fail(function() {

						alert('<?php echo $vocabulario_se_ha_producido_un_error ?>');

					})

			}

			function guardaOperacionPaypal( paypal_data ) {

				$.ajax({
						url: '../class/control.php',
						type: 'POST',
						datatype: 'html',
						data: {
							accion: 'guarda_operacion_paypal',
							paypal_data: paypal_data
						}
					})
					.done(function(result) {
					})
					.fail(function() {
						alert('<?php echo $vocabulario_se_ha_producido_un_error ?>');
					})

			}

		</script>

</body>

</html>