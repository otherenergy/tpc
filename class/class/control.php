<?php
if (session_status() === PHP_SESSION_NONE){session_start();}
// require("./bbdd.php");
require( dirname ( __DIR__ ) . "/config/db_connect.php" );
require( dirname ( __DIR__ ) . "/class/class.carrito.php" );
require( __DIR__ . "./userClass.php");
require( __DIR__ . "./checkoutClass.php");

if( isset( $_REQUEST['accion'] ) && $_REQUEST['accion'] != '' ) {
  $post_keys = array_keys($_REQUEST);
  foreach( $post_keys as $key ) {
	  $$key = $_REQUEST[$key];
	  // echo $key . ' - ' . $$key . '<br>';
  }
}

$_SESSION['id_idioma'] = '1';

// exit;
$db = getDB();

$resp = array();

switch ($accion) {

	case 'set_metodo_pago':
		$_SESSION['smart_user']['metodo_pago'] = $metPago;
		echo "ok";
		break;

	case 'lista_sesion':
		var_dump($_SESSION);
		break;

	case 'borra_sesion':
		$_SESSION = array();
		break;

	case 'login':

		$checkout = new Checkout;

	  $pass = hash('sha256', $input_pass);
		$sql="SELECT * FROM users WHERE email=? AND password=?";
		$arguments = [$input_email, $pass];

		try {
		    $results = $checkout->executeQuery($sql, $arguments);
		} catch (\PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}

		if( count( $results ) > 0 ) {

			$reg = $results[0];

			if( $reg->activo == 0 ) {

				$resp['res'] = "0";
		  	$resp['msg'] = "La cuenta está desactivada.";

			}else {

		    $_SESSION['smart_user']['id']=$reg->uid;
		    $_SESSION['smart_user']['nombre']=$reg->nombre;
		    $_SESSION['smart_user']['email']=$reg->email;
		    $_SESSION['smart_user']['login']=1;

		    if ( $reg->idioma != 0) $_SESSION['smart_user']['lang'] = 1;

		    $resp['res'] = "1";
		    $resp['msg'] = "Hola $reg->nombre bienvenido a Smartcret";
		  }

		}else{
			$resp['res'] = "0";
		  $resp['msg'] = "Los datos no coinciden con ningun usuario";
		}
		echo json_encode($resp);

		break;

	case 'logout':

	  $resp['msg'] = "Gracias por tu visita " . $_SESSION['smart_user']['nombre'] . ". Esperamos que regreses pronto";
		session_destroy();
		$_SESSION = array();
		echo json_encode($resp);

		break;

	case 'recupera_pass':

	  $checkout = new Checkout;

		$sql="SELECT nombre, email , password FROM users WHERE email=?";
		$arguments = [$input_email];

		try {
		    $results = $checkout->executeQuery( $sql, $arguments );

		    if( count( $results ) == 0 ) {
					$resp['res'] = "0";
					$resp['msg'] = "La dirección de correo no coincide con ningun usuario";
				}else{
					$reg = $results[0];
					$nombre = $reg->nombre;
					$email = $reg->email;
					$pass = $reg->password;

					echo "Enviamos email";
					// envia_email_recupera_pass( $email, $pass, $nombre);
					$resp['res'] = "1";
				  $resp['msg'] = "Hemos enviado un email con instrucciones para resetar su contraseña";
				}

		} catch (\PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}

		echo json_encode ( $resp );

		break;


	case 'reset_pass':

	  $checkout = new Checkout;

	  $new_pass = hash('sha256', $input_pass);
	  $old_pass = hash('sha256', $old_pass);

		$sql="UPDATE users SET password=? WHERE email=? AND password=?";
		$arguments = [$new_pass, $input_email, $old_pass];

		try {

				if ( $checkout->executeUpdate( $sql, $arguments ) > 0 ) {

					$resp['res'] = "1";
				  $resp['msg'] = "Tu contraseña se ha cambiado";

				}else {

					$resp['res'] = "0";
				  $resp['msg'] = "Se ha producido un error y no hemos podido cambiar tu contraseña.";

				}

				echo json_encode ( $resp );

		} catch (\PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}

		break;

		case 'aplica_codigo_descuento':

		if ( $nombre_descuento == '') {

			$resp['res'] = "0";
			$resp['msg'] = "Debes introducir un cupón descuento";

		}else {

			$checkout = new Checkout;

			//$nombre_descuento

			$sql="SELECT * FROM descuentos WHERE nombre_descuento =? AND activo=1";
			$arguments = [$nombre_descuento];

			try {
			    $results = $checkout->executeQuery( $sql, $arguments );

					if( count($results) == 0 ) {

						$resp['res'] = "0";
					  $resp['msg'] = "No hay un cupón descuento activo con nombre $nombre_descuento";

					}else {

						if ( !isset( $_SESSION['smart_user']['login'] ) || $_SESSION['smart_user']['login'] !=1 ) {

							$resp['res'] = "2";
					    $resp['msg'] = "Es necesario estar logueado para utilizar el codigo descuento";

						}else {

							if ( isset ( $_SESSION['smart_user']['dir_envio'] ) ) {

								$reg = $results[0];
								$pais_envio = $checkout->obten_dir_envio ( $_SESSION['smart_user']['dir_envio'] )[0]->pais;
								$paises_aplicacion_descuento = explode( '|', $reg->pais_aplicacion );


								if ( in_array( $pais_envio, $paises_aplicacion_descuento, false ) || $reg->pais_aplicacion == 1 ) {

									$fecha_actual = strtotime( date( "d-m-Y H:i:00",time() ) );
					        $fecha_inicio_descuento = strtotime( $reg->fecha_inicio );
					        $fecha_fin_descuento = strtotime( $reg->fecha_fin );

									if ( $fecha_actual > $fecha_inicio_descuento && $fecha_actual < $fecha_fin_descuento ) {

										if ( $checkout->permite_uso_descuento_usuario ( $_SESSION['smart_user']['id'], $reg->id ) ) {

											$_SESSION['codigo_descuento']['activo']=true;
											$_SESSION['codigo_descuento']['id']=$reg->id;
											$_SESSION['codigo_descuento']['nombre']=$reg->nombre_descuento;
											$_SESSION['codigo_descuento']['valor']=$reg->valor;
											$_SESSION['codigo_descuento']['tipo']=$reg->tipo;
											$_SESSION['codigo_descuento']['aplicacion']=$reg->aplicacion_descuento;
											// $_SESSION['codigo_descuento']['p2x1']=$reg->p2x1;
											// $_SESSION['codigo_descuento']['descuento_otro_producto']=$reg->descuento_otro_producto;
											// $_SESSION['codigo_descuento']['cantidad_descuento_otro_producto']=$reg->cantidad_descuento_otro_producto;
											// $_SESSION['codigo_descuento']['regalo']=$reg->regalo;
											// $_SESSION['codigo_descuento']['regalo_condicion']=$reg->regalo_condicion;
											$_SESSION['codigo_descuento']['pais_aplicacion']=$reg->pais_aplicacion;

											$resp['res'] = "1";
										  $resp['msg'] = "El descuento $reg->nombre_descuento se ha aplicado";

										}else {

											$resp['res'] = "0";
										  $resp['msg'] = "Ya has utilizado el descuento $reg->nombre_descuento. Usos permitidos de este descuento: $reg->uso_persona por persona";
										}

									} else {

										$resp['res'] = "1";

										if ( $fecha_actual > $fecha_fin_descuento ) {
											$resp['msg'] = "El descuento $reg->nombre_descuento ha caducado";
										} else {
											$resp['msg'] = "El descuento $reg->nombre_descuento no está activo";
										}
									}

						}else {
							$resp['res'] = "0";
				  		$resp['msg'] = "Descuento no disponible para envios a $pais_envio";
						}

					}else {
						$resp['res'] = "0";
			  		$resp['msg'] = "Es necesario indicar una direccion de envío para aplicar el código descuento";
					}
				}
		  }
		} catch (\PDOException $e) {
	    echo "Error: " . $e->getMessage();
		}
	}

		echo json_encode ( $resp );

		break;


	/************************  GUARDAMOS PEDIDO TEMPORAL *******************/


	case 'guarda_pedido_temporal':

		$es_divisa = false;

		$checkout = new Checkout;

		$carrito = new Carrito();
		$carro = $carrito->get_content();

		$aplicacion_iva = array( '', '21', '0', '0', '21', '', '0');
		$tipo_impuesto = $checkout->obten_tipo_impuesto_envio ( $_SESSION['smart_user']['dir_facturacion'], $_SESSION['smart_user']['dir_envio'] );
		$iva_aplicado = $aplicacion_iva[ $tipo_impuesto ];

		$cupon_aplicado = $checkout->comprueba_cupon_aplicado();

		if ( $cupon_aplicado ) {
			$cupon_id = $cupon_aplicado->id;
			$importe_cupon = $cupon_aplicado->importe;
		}else {
			$cupon_id = 0;
			$importe_cupon = 0;
			$importe_cupon_div = 0;
		}

//		if ( $tipo_impuesto == 1 || $tipo_impuesto == 4 ) {

			$descuento_iva = 1;
			$importe_descuento_iva = 0;
			$total_pagado = $_SESSION['carrito']['precio_total'] + $checkout->calcula_gastos_envio_pedido() - $importe_cupon;

//		}

		if ( $tipo_impuesto == 2 || $tipo_impuesto == 3 || $tipo_impuesto == 6 ) {

			$descuento_iva = 1.21;
			$importe_descuento_iva = ( $tipo_impuesto == 1 ) ? 0 : $checkout->formatea_importe( $total_pagado - ( $total_pagado * 1.21 ) );
			$total_pagado = ( $_SESSION['carrito']['precio_total'] + $checkout->calcula_gastos_envio_pedido() - $importe_cupon ) / $descuento_iva ;

		}

		if ( $tipo_impuesto == 5 ) {

			$descuento_iva = 0;
			$pais = $checkout->obten_dir_envio ( $_SESSION['smart_user']['dir_envio'] )->pais;
			$iva = $checkout->obten_iva_pais( $pais );

			$total_pagado = ( ( $_SESSION['carrito']['precio_total'] + $checkout->calcula_gastos_envio_pedido() - $importe_cupon ) / 1.21 ) * ( 1 + $iva / 100);
		}


		$ref_pedido = $checkout->obten_ref_nuevo_pedido();
		$id_cliente = $_SESSION['smart_user']['id'];

		$id_facturacion = $_SESSION['smart_user']['dir_facturacion'];
		$id_envio = $_SESSION['smart_user']['dir_envio'];
		$tiene_vies = ( $tipo_impuesto == 3 ) ? 1 : 0;
		$total_sinenvio = $checkout->formatea_importe( $_SESSION['carrito']['precio_total'] );
		$gastos_envio = $checkout->calcula_gastos_envio_pedido();
		$peso_pedido = $checkout->calcula_peso_pedido();

		$metodo_pago = $_SESSION['smart_user']['metodo_pago'];
		$estado_pago = 0;
		$fecha_pago = null;

		$estado_envio = 0;
		$fecha_envio = null;
		$fecha_creacion = date('Y-m-d H:i:s');
		$fecha_actualizacion = null;
		$idioma = $_SESSION['id_idioma'];
		$email_seguimiento = 0;
		$fecha_envio_seguimiento = null;
		$cancelado = 0;


		if ( $es_divisa ) {

			$cambio_divisa = get_cambio_divisa ( 'dolar' );

			$total_sinenvio_div = round ( $total_sinenvio, 2 );
			$gastos_envio_div = round ( calcula_gastos_envio_pedido(), 2 );
			$importe_cupon_div = round ( $importe_cupon, 2 );
			$total_pagado_div = round ( $total_pagado, 2 );

			$total_sinenvio = round ( $total_sinenvio * $cambio_divisa, 2 );
			$gastos_envio = round ( $gastos_envio_div * $cambio_divisa, 2 );
			$importe_cupon = round ( $importe_cupon * $cambio_divisa, 2 );
			$total_pagado = round ( $total_pagado * $cambio_divisa, 2 );

		}else {

			$cambio_divisa = 0;

			$total_sinenvio_div = 0;
			$gastos_envio_div = 0;
			$importe_cupon_div = 0;
			$total_pagado_div = 0;

		}

		if (1) {
			$promocion_id = 0;
		}else {
			$promocion_id = 0;
		}

		$arguments = [ $ref_pedido,
									 $id_cliente,
									 $tipo_impuesto,
									 $iva_aplicado,
									 $id_facturacion,
									 $id_envio,
									 $tiene_vies,
									 $total_sinenvio,
									 $total_sinenvio_div,
									 $gastos_envio,
									 $gastos_envio_div,
									 $cupon_id,
									 $importe_cupon,
									 $importe_cupon_div,
									 $promocion_id,
									 $descuento_iva,
									 $metodo_pago,
									 $redsys_num_order,
									 $estado_pago,
									 $fecha_pago,
									 $total_pagado,
									 $total_pagado_div,
									 $peso_pedido,
									 $estado_envio,
									 $fecha_envio,
									 $fecha_creacion,
									 $fecha_actualizacion,
									 $idioma,
									 $email_seguimiento,
									 $fecha_envio_seguimiento,
									 $cancelado,
									 $cambio_divisa
								 ];

								 // print_r( $arguments );exit;
								 // var_dump( $arguments );exit;

		$valores = "";
		$valores .= "ref_pedido=?, ";
		$valores .= "id_cliente=?, ";
		$valores .= "tipo_impuesto=?, ";
		$valores .= "iva_aplicado=?, ";
		$valores .= "id_facturacion=?, ";
		$valores .= "id_envio=?, ";
		$valores .= "tiene_vies=?, ";
		$valores .= "total_sinenvio=?, ";
		$valores .= "total_sinenvio_div=?, ";
		$valores .= "gastos_envio=?, ";
		$valores .= "gastos_envio_div=?, ";
		$valores .= "cupon_id=?, ";
		$valores .= "cupon_importe=?, ";
		$valores .= "cupon_importe_div=?, ";
		$valores .= "promocion_id=?, ";
		$valores .= "descuento_iva=?, ";
		$valores .= "metodo_pago=?, ";
		$valores .= "redsys_num_order=?, ";
		$valores .= "estado_pago=?, ";
		$valores .= "fecha_pago=?, ";
		$valores .= "total_pagado=?, ";
		$valores .= "total_pagado_div=?, ";
		$valores .= "peso_pedido=?, ";
		$valores .= "estado_envio=?, ";
		$valores .= "fecha_envio=?, ";
		$valores .= "fecha_creacion=?, ";
		$valores .= "fecha_actualizacion=?, ";
		$valores .= "idioma=?, ";
		$valores .= "email_seguimiento=?, ";
		$valores .= "fecha_envio_seguimiento=?, ";
		$valores .= "cancelado=?, ";
		$valores .= "cambio_divisa=? ";

		$sql = "INSERT INTO pedidos_temp SET $valores;";

		try {

		    $id_pedido = $checkout->executeInsert($sql, $arguments);

		    $_SESSION['smart_user']['finaliza-pedido'] = 1;
				$_SESSION['smart_user']['ped_temporal'] = $id_pedido;

				if ( $cupon_id > 0 ) {

					$id_prods_descuento = $checkout->obten_aplicacion_descuento ( $cupon_id );
          $array_id_prods_descuento = explode( '|', $id_prods_descuento );

				}

				$ok = true;

				foreach($carro as $producto) {

					$id_prod = $producto['id'];
					$sku = $producto['sku'];
					$cantidad = $producto['cantidad'];

					if ( $cupon_id > 0 ) {

						$cupon = $checkout->obten_descuento( $cupon_id );

						if ( $cupon->tipo == '%' && in_array( $id_prod , $array_id_prods_descuento) ) {

							$cupon_importe = $producto['precio'] * ( $cupon->valor / 100);
							$precio = $producto['precio'] - $cupon_descuento;
							$precio_div = 0;

						}

					}else {
						$precio = $producto['precio'];
						$cupon_importe = 0;
						$precio_div = 0;
					}

					$descuento = $checkout->obten_descuento_producto( $id_prod, $idioma );

					if ( $descuento == 0 ) {

						$descuento_importe = 0;
						$descuento_importe_div = 0;

					}else {

						$descuento_importe_div = round( $precio * ( $descuento / 100 ) );
						$descuento_importe = round( $descuento_importe_div * $cambio_divisa, 2 ) ;

					}

					$fecha_creacion = date('Y-m-d H:i:s');

					if ( $es_divisa ) {
						$precio_div = round( $producto['precio'], 2 );
						$cupon_importe_div = round( $cupon_importe, 2 );

						$precio = round( $producto['precio'] * $cambio_divisa, 2 );
						$cupon_importe = round( $cupon_importe * $cambio_divisa, 2 );
					}


					$valores_linea = "";
					$valores_linea .= "id_pedido = ?, ";
					$valores_linea .= "id_prod = ?, ";
					$valores_linea .= "sku = ?, ";
					$valores_linea .= "cantidad = ?, ";
					$valores_linea .= "precio = ?, ";
					$valores_linea .= "precio_div = ?, ";
					$valores_linea .= "descuento = ?, ";
					$valores_linea .= "descuento_importe = ?, ";
					$valores_linea .= "descuento_importe_div = ?, ";
					$valores_linea .= "fecha_creacion = ?";

					$sql = "INSERT INTO detalles_pedido_temp SET $valores_linea";
					$arguments_linea = [ $id_pedido,
															 $id_prod,
															 $sku,
															 $cantidad,
															 $precio,
															 $precio_div,
															 $descuento,
															 $descuento_importe,
															 $descuento_importe_div,
															 $fecha_creacion ];

					try {

							if ( $checkout->executeInsert( $sql, $arguments_linea ) > 0 ) {

								$resp['id_temp'] = $id_pedido;
								$resp['res'] = "1";
								$resp['metPago'] = $_SESSION['smart_user']['metodo_pago'];

								$ok = true;

							}else {

								$ok = false;

							}

					} catch (\PDOException $e) {
					    echo "Error: " . $e->getMessage();
					}

				}

				if ( $ok ) {

					if ( $_SESSION['smart_user']['metodo_pago']== 2 ) {

						$resp['msg'] = "Redirigiendo a REDSYS pago seguro...";

					}

					if ( $_SESSION['smart_user']['metodo_pago']== 1 ) {

						$resp['msg'] = "Un momento, estamos tramitando tu pedido...";

					}

				}else {

					$resp['res'] = "0";
					$resp['msg'] = "Se ha producido un error";

				}

				echo json_encode ( $resp );

		} catch (\PDOException $e) {

		    echo "Error: " . $e->getMessage();

		}

		break;


}