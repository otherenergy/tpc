<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
include( './admin_seguridad.php' );
include_once('../../assets/lib/bbdd.php');
include_once('../../assets/lib/funciones.php');
include_once('../assets/lib/funciones_admin.php');

$sql_pedidos = "SELECT COUNT(*) as num_ped FROM pedidos WHERE YEAR(fecha_creacion)= '2023' AND cancelado=0 GROUP BY MONTH(fecha_creacion)";
// $sql_pedidos = "SELECT MONTH(fecha_creacion) as mes, COUNT(*) FROM pedidos WHERE YEAR(fecha_creacion)= '2023' AND cancelado=0 GROUP BY MONTH(fecha_creacion)";

$sql_importes = "SELECT ROUND( SUM(total_pagado), 2) as importe FROM pedidos WHERE YEAR(fecha_creacion)='2023' AND cancelado=0 AND id_cliente NOT IN (SELECT id_user FROM user_test) GROUP BY MONTH(fecha_creacion)";
// $sql_importes = "SELECT MONTH(fecha_creacion) as mes, FORMAT( SUM(total_pagado), 2) FROM pedidos WHERE YEAR(fecha_creacion)='2023' AND cancelado=0 GROUP BY MONTH(fecha_creacion)";

$sql_kits = "SELECT SUM(DP.cantidad) as kits FROM detalles_pedido DP, pedidos P WHERE DP.id_prod IN ( SELECT id FROM productos WHERE es_variante = 1 OR es_variante = 2) AND YEAR(DP.fecha_creacion)='2023' AND DP.id_pedido=P.id GROUP BY MONTH(DP.fecha_creacion)";
// $sql_kits = "SELECT MONTH(DP.fecha_creacion) as mes, SUM(DP.cantidad) FROM detalles_pedido DP, pedidos P WHERE DP.id_prod IN ( SELECT id FROM productos WHERE es_variante = 1 OR es_variante = 2) AND YEAR(DP.fecha_creacion)='2023' AND DP.id_pedido=P.id GROUP BY MONTH(DP.fecha_creacion)";

$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto");
// $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

$res_pedidos=consulta($sql_pedidos, $conn);
$pedidos = array();
while ( $reg=$res_pedidos->fetch_object() ) {
	array_push ( $pedidos, $reg->num_ped );
}

$res_importes=consulta($sql_importes, $conn);
$importes = array();
while ( $reg=$res_importes->fetch_object() ) {
	array_push ( $importes, $reg->importe );
}

$res_kits=consulta($sql_kits, $conn);
$kits = array();
while ( $reg=$res_kits->fetch_object() ) {
	array_push ( $kits, $reg->kits );
}

$label_meses = array();
$mes_actual = date('m');
for ($i=0; $i < $mes_actual; $i++) {
	array_push ( $label_meses, $meses[ $i ] );
}
// var_dump( $pedidos );

// echo "pedidos:";
// echo "<br>";
// echo json_encode( $pedidos );
// echo "<br>";
// echo "<br>";
// echo "importes:";
// echo "<br>";
// echo json_encode( $importes );
// echo "<br>";
// echo "<br>";
// echo "kits:";
// echo "<br>";
// echo json_encode( $kits );
// echo "<br>";


$color_pedidos = "#35471e";
$color_importes = "#92bf23";
$color_kits = "#65696a";

$data = array (
							"labels" => $label_meses,
							"datasets" => array (
															array(
																	  "label" => "Pedidos",
																	  "data"	=> $pedidos,
																	  "backgroundColor" => $color_pedidos
																	),
															array(
																	  "label" => "Kits",
																	  "data"	=> $kits,
																	  "backgroundColor" => $color_kits
																	),
															array(
																	  "label" => "Importe Ventas",
																	  "data"	=> $importes,
																	  "backgroundColor" => $color_importes
																	)
														)
);



echo json_encode ( $data );


?>