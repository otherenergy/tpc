<?php

$pais = "ALEMANIA";
$empresa = "it_dachser_media_15";


function calcula_portes ( $pais, $empresa) {

define("SERVER_TRANS", "localhost");
define("USER_TRANS", "root");
define("PASS_TRANS", "");
define("DB_TRANS", "calculadora_portes");

$conn_trans = new mysqli(SERVER_TRANS, USER_TRANS, PASS_TRANS, DB_TRANS);
$conn_trans->set_charset("utf8");


$sql = "SELECT * FROM $empresa WHERE pais='$pais'";
$res = $conn_trans->query($sql);

// $col = array ( '10kg', '20kg',	'30kg',	'40kg',	'50kg',	'60kg',	'70kg',	'80kg', '90kg',	'100kg', '125kg', '150kg', '175kg', '200kg', '250kg', '300kg', '350kg', '400kg', '450kg', '500kg', '600kg', '700kg', '800kg',	'900kg', '1000kg', '1250kg', '1500kg', '1750kg', '2000kg', '2250kg', '2500kg', '3000kg', '3500kg', '4000kg' );

$i=0;

while( $reg=$res->fetch_object() ) {
		// echo "document.getElementById('rangeEnd$i').value = " . $reg->$col[$i] . ";";
		// echo "<br>";
	  $reg->kg_10;
		$i++;
	}


}

?>