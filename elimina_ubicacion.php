<?php
if (session_status() === PHP_SESSION_NONE){session_start();}

require( "config/db_connect.php" );
require( "assets/lib/class.carrito.php" );
require( "class/userClass.php");
require( "class/checkoutClass.php");
require( "assets/lib/api-books.php");

$checkout= new Checkout;
$userClass= new UserClass;

unset( $_SESSION['user_ubicacion'] );
unset( $_SESSION['user_idioma'] );

echo "<br>";
echo "<br>";
echo 'Ubicacion: ' . $_SESSION['user_ubicacion'];
echo "<br>";
echo 'Idioma: ' . $_SESSION['user_idioma'];


?>