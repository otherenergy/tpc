<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// if ( !$_POST ) exit;

if (session_status() === PHP_SESSION_NONE){session_start();}
require_once( "../../config/db_connect.php" );
require_once( "../../class/class/class.carrito.php" );
require_once( "../../class/userClass.php");
require_once( "../../class/checkoutClass.php");
require_once( "../../class/emailClass.php");
require_once( "../lib/api-books.php");
require_once( '../lib/redsys/include/config.php' );
require_once( '../lib/redsys/include/apiRedsys7.php' );
require_once( '../lib/redsys/include/functions2.php' );

$checkout = new Checkout();

$emailclass = new emailClass();

// $log = "inicio";
// $checkout->print_log ('pedidos', $log);

$response = redsys_process_response();

if ( isset( $_POST['datos_pedido'] ) ) {
	$datos = explode( '|', $_POST['datos_pedido'] );
	$Ds_Order = trim ( $datos[0] );
	$Ds_MerchantData = trim ( $datos[1] );
	$Ds_AuthorisationCode = 11111;
	$Ds_MerchantCode = "";
	$Ds_Terminal = "";
	$Ds_Response = $_POST['Ds_Response'];

	$envio_email = $_POST['envio_email'];
	$ped_temp = $checkout->obten_datos_pedido_temp ( $Ds_Order );

	$datetime = new DateTime( $ped_temp->fecha_creacion );
	$Ds_Date = $datetime->format('d/m/Y');
	$Ds_Hour = $datetime->format('H:i');

	$datetime = $ped_temp->fecha_creacion;
	$Ds_Amount = $ped_temp->total_pagado;
	$idioma = $ped_temp->idioma;

}else {

	$version = $_POST[ 'Ds_SignatureVersion' ];
	$params = $_POST[ 'Ds_MerchantParameters' ];
	$signatureRecibida = $_POST[ 'Ds_Signature' ];
	$envio_email = 1;
	$data_array = json_decode( base64_decode ( $params ), true );

	$Ds_Date = $data_array["Ds_Date"];
	$Ds_Hour = $data_array["Ds_Hour"];
	$datetime = formato_fecha ( $Ds_Date, $Ds_Hour );
	$Ds_Amount = $data_array["Ds_Amount"]/100;
	$Ds_Order = $data_array["Ds_Order"];
	$Ds_MerchantCode = $data_array["Ds_MerchantCode"];
	$Ds_Terminal = $data_array["Ds_Terminal"];
	$Ds_Response = $data_array["Ds_Response"];
	$Ds_AuthorisationCode = $data_array["Ds_AuthorisationCode"];
	$Ds_MerchantData = $data_array["Ds_MerchantData"];

	$ped_temp = $checkout->obten_datos_pedido_temp ( $Ds_Order );
	$idioma = $ped_temp->idioma;

}

// $log = '';
// $log .= 'Ds_Date: ' . $Ds_Date . "\n";
// $log .= 'Ds_Hour: ' . $Ds_Hour . "\n";
// $log .= 'datetime: ' . $datetime . "\n";
// $log .= 'Ds_Amount: ' . $Ds_Amount . "\n";
// $log .= 'Ds_Order: ' . $Ds_Order . "\n";
// $log .= 'Ds_MerchantCode: ' . $Ds_MerchantCode . "\n";
// $log .= 'Ds_Terminal: ' . $Ds_Terminal . "\n";
// $log .= 'Ds_Response: ' . $Ds_Response . "\n";
// $log .= 'Ds_AuthorisationCode: ' . $Ds_AuthorisationCode . "\n";
// $log .= 'Ds_MerchantData: ' . $Ds_MerchantData . "\n";
// $log .= "\n";
// $log .= "obtenemos id del pedido temporal\n\n";

// $checkout->print_log ('pedidos', $log);


/* obtenemos id del pedido temporal*/

$id_pedido_temp = $checkout->obten_id_pedido_temp( $Ds_Order );

//$log .= 'id_pedido_temp: ' . $id_pedido_temp . "\n";


/* obtenemos el id cliente del pedido */
$id_cliente = $checkout->obten_id_user_pedido_temp( $Ds_Order );

//$log .= "id_cliente: " . $id_cliente . "\n\n";


/* obtenemos datos del usuario */
$datos_user = $checkout->obten_datos_user( $id_cliente )[0];

// $checkout->print_log ('pedidos', $log);

$nombre = $datos_user->nombre;
$email = $datos_user->email;
$ref_pedido = $Ds_MerchantData;

/* si la respuesta del tpv es OK */
if ( (int)$Ds_Response <= 99 ) {

	$ref_pedido = $checkout->obten_ref_nuevo_pedido();

	/* operación ACEPTADA */
	//$log .= "operación ACEPTADA \n";

	/* insertamos el pedido copiando del pedido temporal con el numero generado por redsys */
	//$log .= "insertamos el pedido copiando del pedido temporal con el numero generado por redsys\n\n";

	$ref_pedido = $ped_temp->ref_pedido;

	$ped_temp = $checkout->obten_datos_pedido_temp ( $Ds_Order );
	$idioma = $ped_temp->idioma;

	$arguments = [
									$ref_pedido,
									$ped_temp->id_cliente,
									$ped_temp->tipo_impuesto,
									$ped_temp->iva_aplicado,
									$ped_temp->id_facturacion,
									$ped_temp->id_envio,
									$ped_temp->tiene_vies,
									$ped_temp->total_sinenvio,
									$ped_temp->total_sinenvio_div,
									$ped_temp->gastos_envio,
									$ped_temp->gastos_envio_div,
									$ped_temp->cupon_id,
									$ped_temp->cupon_importe,
									$ped_temp->cupon_importe_div,
									$ped_temp->promocion_id,
									$ped_temp->descuento_iva,
									$ped_temp->metodo_pago,
									$ped_temp->redsys_num_order,
									'Pagado',
									date('Y-m-d H:i:s') ,
									$ped_temp->total_pagado,
									$ped_temp->total_pagado_div,
									$ped_temp->peso_pedido,
									0,
									$ped_temp->estado_envio,
									date('Y-m-d H:i:s') ,
									null,
									$ped_temp->idioma,
									0,
									null,
									0,
									$ped_temp->cambio_divisa,
									$ped_temp->tipo_factura
	];

	$valores ="";
	$valores .="ref_pedido=?";
	$valores .=", id_cliente=?";
	$valores .=", tipo_impuesto=?";
	$valores .=", iva_aplicado=?";
	$valores .=", id_facturacion=?";
	$valores .=", id_envio=?";
	$valores .=", tiene_vies=?";
	$valores .=", total_sinenvio=?";
	$valores .=", total_sinenvio_div=?";
	$valores .=", gastos_envio=?";
	$valores .=", gastos_envio_div=?";
	$valores .=", cupon_id=?";
	$valores .=", cupon_importe=?";
	$valores .=", cupon_importe_div=?";
	$valores .=", promocion_id=?";
	$valores .=", descuento_iva=?";
	$valores .=", metodo_pago=?";
	$valores .=", redsys_num_order=?";
	$valores .=", estado_pago=?";
	$valores .=", fecha_pago=?";
	$valores .=", total_pagado=?";
	$valores .=", total_pagado_div=?";
	$valores .=", peso_pedido=?";
	$valores .=", tracking_id=?";
	$valores .=", estado_envio=?";
	$valores .=", fecha_creacion=?";
	$valores .=", fecha_actualizacion=?";
	$valores .=", idioma=?";
	$valores .=", email_seguimiento=?";
	$valores .=", fecha_envio_seguimiento=?";
	$valores .=", cancelado=?";
	$valores .=", cambio_divisa=?";
	$valores .=", tipo_factura=?";

	$sql="INSERT INTO pedidos SET $valores";

	$id_pedido = $checkout->executeInsert($sql, $arguments);

	if ( $id_pedido > 0 ) {

		$checkout->copia_pedido_dir_envio( $id_pedido, $ped_temp->id_envio );
		$checkout->copia_pedido_dir_factura( $id_pedido, $ped_temp->id_facturacion );

		if ( $ped_temp->cupon_id > 0 ) {

			$checkout->copia_pedidos_cupones_aplicados( $id_pedido, $ped_temp->cupon_id );
			$checkout->actualiza_cupon_detalle ( $id_pedido );

		}

	}

	/* actualizamos pedido temporal por si tuviera diferente id */
	$sql_id_ped_temporal = "UPDATE pedidos_temp SET ref_pedido = ? WHERE redsys_num_order = ? ";
	$arguments=[ $ref_pedido, $Ds_Order ];
	$checkout->executeQuery($sql_id_ped_temporal, $arguments);

	/* obtenemos las lineas de pedido temporal y las insertamos al pedido final */
	$sql="SELECT * FROM detalles_pedido_temp WHERE id_pedido = ?";
	$arguments=[$id_pedido_temp];
	$result = $checkout->executeQuery($sql, $arguments);

	//$log .= "insertamos lineas de pedido\n";

	foreach ( $result as $reg ) {

		$arguments = [
			$id_pedido,
			$reg->id_prod,
			$reg->sku,
			$reg->cantidad,
			$reg->precio,
			$reg->precio_div,
			$reg->descuento,
			$reg->descuento_importe,
			$reg->descuento_importe_div,
			$reg->cupon_id,
			$reg->cupon_importe,
			$reg->cupon_importe_div,
			$reg->promocion_id,
			$reg->promocion_descuento,
			$reg->promocion_descuento_div,
			date('Y-m-d H:i:s')
		];

		$valores = "id_pedido=?";
		$valores .= ", id_prod=?";
		$valores .= ", sku=?";
		$valores .= ", cantidad=?";
		$valores .= ", precio=?";
		$valores .= ", precio_div=?";
		$valores .= ", descuento=?";
		$valores .= ", descuento_importe=?";
		$valores .= ", descuento_importe_div=?";
		$valores .= ", cupon_id=?";
		$valores .= ", cupon_importe=?";
		$valores .= ", cupon_importe_div=?";
		$valores .= ", promocion_id=?";
		$valores .= ", promocion_descuento=?";
		$valores .= ", promocion_descuento_div=?";
		$valores .= ", fecha_creacion=?";

		// Verificar que la cantidad de elementos en $arguments coincida con los ? en $valores
		$num_placeholders = substr_count($valores, '?');
		if ($num_placeholders !== count($arguments)) {
			throw new Exception("Número de parámetros no coincide: $num_placeholders placeholders, " . count($arguments) . " argumentos.");
		}

		$sql_linea_pedido = "INSERT INTO detalles_pedido SET $valores";

		// Registrar la consulta y los argumentos para depuración
		error_log("SQL: $sql_linea_pedido");
		error_log("Arguments: " . print_r($arguments, true));

		$id_linea_pedido = $checkout->executeInsert($sql_linea_pedido, $arguments);

		// $log .= "$sql_linea_pedido \n";

	}

	// $log .= "\n\nDetalle pedido\n" . $checkout->detalle_pedido_ref ( $ref_pedido ) . "\n\n";

	/* insertamos datos operación TPV */
	$arguments = [
		$Ds_Order,
		$Ds_MerchantData,
		$Ds_Amount,
		$Ds_Response,
		$Ds_AuthorisationCode,
		$datetime,
		1
	];

	$valores="";
	$valores.="tpv_order=?";
	$valores.=", ref_pedido=?";
	$valores.=", tpv_amount=?";
	$valores.=", tpv_response=?";
	$valores.=", tpv_authorisation_code=?";
	$valores.=", tpv_datetime=?";
	$valores.=", tpv_estado=?";

	$sql_datos_tpv ="INSERT INTO datos_tpv SET $valores";
	$id_datos_tpv = $checkout->executeInsert($sql_datos_tpv, $arguments);

	// $log .= "\ninsertamos datos de operación TPV\n";
	// $log .= "$sql_tpv\n\n";

/******* AQUI ENVIAMOS EL EMAIL DE PEDIDO RECIBIDO *************/

	$detalle_pedido = $checkout->lista_pedido_ref ( $ref_pedido );

	if ( $envio_email == 1 ) {
		$email_pedido_recibido = $emailclass->email_procesando_pedido($idioma, $email, $nombre, $ref_pedido, $detalle_pedido);
	}


/*** CREAMOS LA SO EN ZOHO ***/

/* Escribimos el proceso en el archivo log */
//$checkout->print_log ('pedidos', $log);


$checkout->crea_orden_venta( $id_pedido );


}else {
	/* operacion DENEGADA */

	//$log .= "\n\noperacion DENEGADA\n";
	//$log .= "\ninsertamos datos operación denegada TPV\n";

	/* insertamos datos operación TPV */
	$valores_tpv ="";
	$valores_tpv .="tpv_order='" . $Ds_Order  . "'";
	$valores_tpv .=", ref_pedido='" . $Ds_MerchantData  . "'";
	$valores_tpv .=", tpv_amount='" . $Ds_Amount . "'";
	$valores_tpv .=", tpv_response='" . $Ds_Response . "'";
	$valores_tpv .=", tpv_authorisation_code='" . $Ds_AuthorisationCode . "'";
	$valores_tpv .=", tpv_datetime='" . $datetime . "'";
	$valores_tpv .=", tpv_estado=0";

	$sql_tpv = "INSERT INTO datos_tpv SET $valores_tpv";
	$checkout->executeInsert($sql_tpv);

	//$log .= "$sql_tpv";

	$email_pago_rechazado = $emailclass->email_pago_rechazado($idioma, $email, $nombre);

}

function formato_fecha ( $Ds_Date, $Ds_Hour) {

	$fecha_temp = explode( '%2F', $Ds_Date );
	$dia = $fecha_temp[0];
	$mes = $fecha_temp[1];
	$anio = $fecha_temp[2];

	$hora_temp = explode( '%3A', $Ds_Hour );
	$hora = $hora_temp[0];
	$min = $hora_temp[1];

	return $anio . '-' . $mes . '-' . $dia . ' ' . $hora . ':' . $min;

}