<?php
if ( session_status() === PHP_SESSION_NONE){session_start();}
require( dirname ( dirname ( dirname ( __DIR__ ) ) ) . "/assets/lib/bbdd.php" );
require( dirname ( dirname ( dirname ( __DIR__ ) ) ) . "/assets/lib/funciones.php" );
require( dirname ( __DIR__ ) . "/lib/funciones_admin.php" );


$sql_4m2="UPDATE productos SET descuento_en= 0, descuento_en_us= 0, precio_en=round(  precio_base_en * ( 1 - 0), 2 ), precio_en_us=round(  precio_base_en_us * ( 1 - 0), 2 ) WHERE id IN (815, 816) OR es_variante IN (815, 816)";

$sql_8m2="UPDATE productos SET descuento_en= 10, descuento_en_us= 10, precio_en=round(  precio_base_en * ( 1 - 0.1), 2 ), precio_en_us=round(  precio_base_en_us * ( 1 - 0.1), 2 ) WHERE id IN (1, 2) OR es_variante IN (1, 2)";

$sql_16m2="UPDATE productos SET descuento_en= 15, descuento_en_us= 15, precio_en=round(  precio_base_en * ( 1 - 0.15), 2 ), precio_en_us=round(  precio_base_en_us * ( 1 - 0.15), 2 ) WHERE id IN (812, 813) OR es_variante IN (812, 813)";

$sql_24m2="UPDATE productos SET descuento_en= 20, descuento_en_us= 20, precio_en=round(  precio_base_en * ( 1 - 0.2), 2 ), precio_en_us=round(  precio_base_en_us * ( 1 - 0.2), 2 ) WHERE id IN (981, 982) OR es_variante IN (981, 982)";

if( consulta( $sql_4m2, $conn ) ) { echo "4m2 OK";}else{echo "4m2 ERROR";} echo "<br>";
if( consulta( $sql_8m2, $conn ) ) { echo "8m2 OK";}else{echo "8m2 ERROR";} echo "<br>";
if( consulta( $sql_16m2, $conn ) ) { echo "16m2 OK";}else{echo "16m2 ERROR";} echo "<br>";
if( consulta( $sql_24m2, $conn ) ) { echo "24m2 OK";}else{echo "24m2 ERROR";} echo "<br>";


?>
