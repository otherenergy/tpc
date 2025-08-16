<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once( '../lib/bbdd.php' );
require_once( '../lib/funciones.php' );

/*recogemos las variables enviadas */
$post_keys = array_keys($_REQUEST);
foreach( $post_keys as $key ) {
	$$key = $_REQUEST[$key];
    // echo $key . ' - ' . $$key . '<br>';
}
// exit;
$datos_pedido = obten_datos_pedido_ref( $salesorder_number );
$datos_usuario = obten_datos_user ( $datos_pedido->id_cliente );
$datos_envio = obten_dir_envio ( $datos_pedido->id_envio );
$detalle_pedido = lista_pedido_ref ( $salesorder_number );

/* Comprobamos si ya se ha enviado el email con los datos de tracking */
if ( !envio_datos_tracking ( $salesorder_number, $datos_pedido->id_cliente ) ) {

actualiza_envio_pedido ( $datos_pedido->id, 'Enviado', cambiaFormatoFecha( $shipment_date ), $delivery_method . ' - ' . $tracking_number );
inserta_datos_tracking ( $datos_pedido->id_cliente, $datos_pedido->id, $salesorder_number, cambiaFormatoFecha( $shipment_date ), $delivery_method, $tracking_number );
actualiza_estado_pago ( $datos_pedido->id, 'Pagado');

$ref_pedido = $salesorder_number;
$input_transportista = $delivery_method;
$input_seguimiento = $tracking_number;
$input_fecha = $shipment_date;

$lang = $datos_pedido->idioma;
include_once( '../../mailings/email_pedido_enviado.php' );

echo $body[ $lang ];

}else {
	echo "Email ya se ha enviado";
}

exit;
?>