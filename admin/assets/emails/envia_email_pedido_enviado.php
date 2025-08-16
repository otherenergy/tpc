<?php
require_once( dirname ( dirname ( ( dirname ( __DIR__ ) ) ) ) . '/assets/lib/bbdd.php' );
require_once( dirname ( dirname ( ( dirname ( __DIR__ ) ) ) ) . '/assets/lib/funciones.php' );
require_once( dirname ( dirname ( ( dirname ( __DIR__ ) ) ) ) . '/assets/lib/api-inventory.php');


echo "OK";

// $so = getInventorySalesOrders ( 200 );

// var_dump( $so );

$so_item = obtenerOrdenDeVentaPorNombre( 'SC-03639' );

echo $so_item["salesorder"]["salesorder_number"];
echo "<br>";
echo $so_item["salesorder"]["packages"][0]["delivery_method"];
echo "<br>";
echo $so_item["salesorder"]["packages"][0]["tracking_number"];
echo "<br>";
echo $so_item["salesorder"]["packages"][0]["shipment_date"];

// var_dump( $so_item );
// $so_item = getInventorySalesOrder ( '459933000167865652' );
// var_dump( $so_item );



exit;
// $periodo_dias = 15;
// $pedidos = obten_array_ultimos_pedidos( $periodo_dias );
// var_dump($pedidos);

// $so = getInventorySalesOrders ( 200 );

// $pedidos_enviados = array();

// foreach ( $so['salesorders'] as $salesorder ) {
// 	if ( strpos( $salesorder["salesorder_number"], 'SC-' ) !== false ) {
// 		if ( in_array ( $salesorder["salesorder_number"], $pedidos ) ) {

// 			$so_item = getInventorySalesOrder ( $salesorder["salesorder_id"] );

// 			$datos_envio = array(
// 														"referencia" => $so_item["salesorder"]["salesorder_number"],
// 														"transporte" => $so_item["salesorder"]["packages"][0]["delivery_method"],
// 														"num_tracking" => $so_item["salesorder"]["packages"][0]["tracking_number"],
// 														"fecha_envio" => $so_item["salesorder"]["packages"][0]["shipment_date"],
// 													);

// 			array_push ( $pedidos_enviados, $datos_envio );
// 		}
// 	}
// }

// foreach ($pedidos_enviados as $pedido) {

// 	envia_datos_seguimiento_cliente ( $pedido['referencia'], $pedido['transporte'], $pedido['fecha_envio'], $pedido['num_tracking'] );
// 	sleep(3);

// }

function envia_datos_seguimiento_cliente ( $referencia, $transporte, $fecha_envio, $num_tracking ) {

	$datos_pedido = obten_datos_pedido_ref( $referencia );
	$datos_usuario = obten_datos_user ( $datos_pedido->id_cliente );
	$datos_envio = obten_dir_envio ( $datos_pedido->id_envio );
	$detalle_pedido = lista_pedido_ref ( $referencia );

	/* Comprobamos si ya se ha enviado el email con los datos de tracking */
	// if ( !envio_datos_tracking ( $referencia, $datos_pedido->id_cliente ) ) {

	// actualiza_envio_pedido ( $datos_pedido->id, 'Enviado', cambiaFormatoFecha( $fecha_envio ), $transporte . ' - ' . $num_tracking );
	// inserta_datos_tracking ( $datos_pedido->id_cliente, $datos_pedido->id, $referencia, cambiaFormatoFecha( $fecha_envio ), $transporte, $num_tracking );
	// actualiza_estado_pago ( $datos_pedido->id, 'Pagado');

	// $ref_pedido = $referencia;
	// $input_transportista = $transporte;
	// $input_seguimiento = $num_tracking;
	// $input_fecha = $fecha_envio;

	// $lang = $datos_pedido->idioma;
	// include_once( '../../mailings/email_pedido_enviado.php' );

	// echo $body[ $lang ];

	// sleep(5);

	// }else {
	// 	echo "Email ya se ha enviado";
	// }

}

?>