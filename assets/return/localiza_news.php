<?php
include ( '../lib/bbdd.php' );
include ( '../lib/funciones.php' );


$sql = "SELECT * FROM newsletter WHERE user_ip ='95.127.181.128'";
// $sql = "SELECT * FROM newsletter WHERE user_ip !=''";
$res = consulta( $sql );
// $users = $res->fetch_object();

// var_dump( $res );

// exit;
$i = 1;
while ( $user=$res->fetch_object() ) {

		$ip = $user->user_ip;
		$ch = curl_init('http://ipwho.is/'.$ip);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$ipwhois = json_decode(curl_exec($ch), true);
		curl_close($ch);
		// echo $user->email . ' - ' . $ipwhois['country'] . ' - <img src="' . $ipwhois['flag']['img'] . '" width="25" />';
		// echo "<br>";

		$valores = "continente = '". $ipwhois['continent'] . "', ";
		$valores .= "cod_continente = '". $ipwhois['continent_code'] . "', ";
		$valores .= "pais = '". $ipwhois['country'] . "', ";
		$valores .= "cod_pais = '". $ipwhois['country'] . "', ";
		$valores .= "region = '". $ipwhois['region'] . "', ";
		$valores .= "cod_region = '". $ipwhois['region_code'] . "', ";
		$valores .= "ciudad = '". $ipwhois['city'] . "', ";
		$valores .= "cod_postal = '". $ipwhois['postal'] . "', ";
		$valores .= "bandera = '" . $ipwhois['flag']['img'] . "'";


		$sql2 = "UPDATE newsletter SET $valores WHERE id =" . $user->id;
		if ( consulta( $sql2 ) ) echo $i . ' - OK -> ';
		else echo $i . " - ERROR -> ";
		echo $sql2;
		echo "<br>";
		$i++;

}

?>