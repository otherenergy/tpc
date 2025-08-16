<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// echo $_REQUEST['paypal_id'];
// echo "<br>";
// echo $_REQUEST['num_order'];
// exit;

if ( !$_REQUEST ) exit;

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

$paypal_id = $_REQUEST['paypal_id'];
$redsys_num_order = $_REQUEST['num_order']; $redsys_num_order;

$ped_temp = $checkout->obten_datos_pedido_temp ( $redsys_num_order );

// $datetime = new DateTime( $ped_temp->fecha_creacion );
// $Ds_Date = $datetime->format('d/m/Y');
// $Ds_Hour = $datetime->format('H:i');

$datetime = $ped_temp->fecha_creacion;
$Ds_Amount = $ped_temp->total_pagado;
$idioma = $ped_temp->idioma;


/* obtenemos id del pedido temporal*/

$id_pedido_temp = $checkout->obten_id_pedido_temp( $redsys_num_order );

/* obtenemos el id cliente del pedido */
$id_cliente = $checkout->obten_id_user_pedido_temp( $redsys_num_order );

/* obtenemos datos del usuario */
$datos_user = $checkout->obten_datos_user( $id_cliente )[0];

$nombre = $datos_user->nombre;
$email = $datos_user->email;
$ref_pedido = $Ds_MerchantData;

$ref_pedido = $checkout->obten_ref_nuevo_pedido();

$ref_pedido = $ped_temp->ref_pedido;

$ped_temp = $checkout->obten_datos_pedido_temp ( $redsys_num_order );
$idioma = $ped_temp->idioma;
/*
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
	$ped_temp->paypal_id,
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

// Construir la consulta SQL con placeholders
$valores ="
ref_pedido='%s',
id_cliente='%s',
tipo_impuesto='%s',
iva_aplicado='%s',
id_facturacion='%s',
id_envio='%s',
tiene_vies='%s',
total_sinenvio='%s',
total_sinenvio_div='%s',
gastos_envio='%s',
gastos_envio_div='%s',
cupon_id='%s',
cupon_importe='%s',
cupon_importe_div='%s',
promocion_id='%s',
descuento_iva='%s',
metodo_pago='%s',
redsys_num_order='%s',
paypal_id='%s',
estado_pago='%s',
fecha_pago='%s',
total_pagado='%s',
total_pagado_div='%s',
peso_pedido='%s',
tracking_id='%s',
estado_envio='%s',
fecha_creacion='%s',
fecha_actualizacion='%s',
idioma='%s',
email_seguimiento='%s',
fecha_envio_seguimiento='%s',
cancelado='%s',
cambio_divisa='%s',
tipo_factura='%s'";

$sql = "INSERT INTO pedidos SET $valores";

// Usar vsprintf para reemplazar los placeholders por los valores reales
$sql_final = vsprintf($sql, $arguments);

// Imprimir la consulta SQL final
echo $sql_final;

exit;
*/

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
	$ped_temp->paypal_id,
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
$valores .=", paypal_id=?";
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
$arguments=[ $ref_pedido, $redsys_num_order ];
$checkout->executeQuery($sql_id_ped_temporal, $arguments);

$_SESSION['ref_pedido'] = $ref_pedido;

/* obtenemos las lineas de pedido temporal y las insertamos al pedido final */
$sql="SELECT * FROM detalles_pedido_temp WHERE id_pedido = ?";
$arguments=[$id_pedido_temp];
$result = $checkout->executeQuery($sql, $arguments);

foreach ( $result as $reg ) {

	$arguments = [
		$id_pedido,
		$reg->id_prod,
		$reg->sku,
		$reg->cantidad,
		$reg->precio,
		$reg->precio_div,
		$reg->descuento,
		date('Y-m-d H:i:s')
	];

	$valores = "id_pedido=?";
	$valores .= ", id_prod=?";
	$valores .= ", sku=?";
	$valores .= ", cantidad=?";
	$valores .= ", precio=?";
	$valores .= ", precio_div=?";
	$valores .= ", descuento=?";
	$valores .= ", fecha_creacion=?";

		// Verificar que la cantidad de elementos en $arguments coincida con los ? en $valores
	$num_placeholders = substr_count($valores, '?');
	if ($num_placeholders !== count($arguments)) {
		throw new Exception("Número de parámetros no coincide: $num_placeholders placeholders, " . count($arguments) . " argumentos.");
	}

	$sql_linea_pedido = "INSERT INTO detalles_pedido SET $valores";
	$id_linea_pedido = $checkout->executeInsert($sql_linea_pedido, $arguments);

}


/******* AQUI ENVIAMOS EL EMAIL DE PEDIDO RECIBIDO *************/

$detalle_pedido = $checkout->lista_pedido_ref ( $ref_pedido );

// echo "Envia email pedido recibido:";
// echo "<br>";
// echo $detalle_pedido;
// exit;

$email_pedido_recibido = $emailclass->email_procesando_pedido($idioma, $email, $nombre, $ref_pedido, $detalle_pedido);

/*** CREAMOS LA SO EN ZOHO ***/

$checkout->crea_orden_venta( $id_pedido );


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