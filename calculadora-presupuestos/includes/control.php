<?php
if (session_status() === PHP_SESSION_NONE){session_start();}
require("../../assets/lib/bbdd.php");
require('../../assets/lib/class.carrito.php');
require("../../assets/lib/funciones.php");

$carrito = new Carrito();

if( isset( $_REQUEST['accion'] ) && $_REQUEST['accion'] != '' ) {
   $post_keys = array_keys($_REQUEST);
   foreach( $post_keys as $key ) {
	   $$key = $_REQUEST[$key];
	   // echo $key . ' - ' . $$key . '<br>';
   }
}
// exit;
$resp = array();

switch ($accion) {

	case 'presupuesto_a_carrito':

	$cantidad_articulos=0;

	foreach ( $_SESSION['presupuesto'] as $producto_cantidad ) {

		$temp = explode( '|', $producto_cantidad );
		$id_producto = $temp[0];
		$cantidad = $temp[1];
		$prod = obten_datos_producto( $id_producto );

		$articulo = array(
			"id"				=>	$prod->id,
			"nombre"		=>	$prod->nombre_es,
			"nombre_en"	=>	$prod->nombre_en,
			"nombre_fr"	=>	$prod->nombre_fr,
			"nombre_it"	=>	$prod->nombre_it,
			"categoria"	=>	$prod->id_categoria,
			"img"				=>	$prod->miniatura,
			"cantidad"	=>	$cantidad,
			"precio"		=>	cambia_coma($prod->precio_es),
			"sku"				=>	$prod->sku,
			"peso"      =>  $prod->peso,
		);

		$carrito->add($articulo);
		$cantidad_articulos += $cantidad;
	}

	$resp['texto']=( $cantidad_articulos > 1 ) ? 'Se han añadido ' . $cantidad_articulos . ' articulos a tu pedido' : 'Se ha añadido ' . $cantidad_articulos . ' articulo a tu pedido';

	$resp['numProd'] = $carrito->articulos_total();
	echo json_encode($resp);

	break;

}





