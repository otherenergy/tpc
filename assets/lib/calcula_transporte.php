<?php

/*
NACIONAL - tarifa + seguro + tasa carburante + gastos gestion
INTERNACIONAL - tarifa + seguro + tasa carburante + gastos gestion
*/


function calcula_portes ( $pais, $cod_postal, $peso ) {
	$checkout = new Checkout();
	$carrito = new Carrito();
	
	$seguro_nacional = 2.5; //€
	$seguro_internacional = 5; //€
	$gastos_gestion_nacional = 26; // gastos gestion % + IVA 21%
	$gastos_gestion_internacional = 41; // gastos gestion 20% + IVA 21%
	$tasa_carburante = 10;// %

	if ( isset($pais) && (($pais=='ALBANIA') || ($pais=='BOSNIA Y HERZEGOVINA') || ($pais=='CHIPRE') || ($pais=='ISLANDIA') || ($pais=='ISLAS FEROE') || ($pais=='KOSOVO') || ($pais=='MALTA') || ($pais=='REINO UNIDO') || ($pais=='REP. MACEDONIA') || ($pais=='SUIZA') || ($pais=='TURQUIA')) ) {
		$gastos_aduana=45;
	} else {
		$gastos_aduana=0;
	};

	// define('SERVER_TRANS', '151.80.13.213');
	// define('USER_TRANS', 'admin_calculadora_transporte');
	// define('PASS_TRANS', 'uzkdWVEdJQ');
	// define('DB_TRANS', 'admin_calculadora_transporte');

	define('SERVER_TRANS', '146.59.171.104');
	define('USER_TRANS', 'smartcret_calculadora_transporte');
	define('PASS_TRANS', 'uzkdWVEdJQ');
	define('DB_TRANS', 'smartcret_calculadora_transporte');
	$conn_trans = new mysqli(SERVER_TRANS, USER_TRANS, PASS_TRANS, DB_TRANS);
	$conn_trans->set_charset("utf8");



	if ( $pais != '' ) {

		switch ($pais) {

			//envios a UK
			case 'UNITED KINGDOM':

			
			$carro = $carrito->get_content();

			$variantes = array(394, 23, 3, 4);
			$total_portes = 0;

			if( $carrito->precio_total() < 100 ) {
				foreach($carro as $producto) {

						$prod = $checkout->obten_datos_producto( $producto['id'] );

						if ( in_array( $prod->es_variante, $variantes ) ) {

							$id_portes =  $prod->es_variante;

						}else {

							$id_portes = $producto['id'];

						}

				  $sql = "SELECT portes_uk FROM sc_calculo_portes WHERE id_padre=$id_portes";
				  $reg = $conn_trans->query($sql)->fetch_object();
				  $total_portes += $reg->portes_us * $producto['cantidad'];

				}

				return $total_portes*1.21;

			}else {
				return 0;
			}

			break;

			//envios a EEUU
			case 'ESTADOS UNIDOS':

			
			$carro = $carrito->get_content();

			$variantes = array(394, 23, 3, 4);
			$total_portes = 0;

			if( $carrito->precio_total() < 100 ) {
				foreach($carro as $producto) {
					
					$prod = $checkout->obten_datos_producto( $producto['id'] );
					if ( in_array( $prod->es_variante, $variantes ) ) {

						$id_portes =  $prod->es_variante;

					}else {

						$id_portes = $producto['id'];

					}

					$sql = "SELECT portes_us FROM sc_calculo_portes WHERE id_padre=$id_portes";
					$reg = $conn_trans->query($sql)->fetch_object();
					$total_portes += $reg->portes_us * $producto['cantidad'];

				}

				return $total_portes*1.21;

			}else {
				return 0;
			}

			break;

			//envios a FRANCIA
			case 'FRANCIA':
			case 'ITALIA':
			case 'ALEMANIA':

				
				$carro = $carrito->get_content();

				$variantes = array(394, 23, 3, 4);
				$total_portes = 0;

				if( $carrito->precio_total() < 100 ) {
					foreach($carro as $producto) {

							$prod = $checkout->obten_datos_producto( $producto['id'] );

							if ( in_array( $prod->es_variante, $variantes ) ) {

								$id_portes =  $prod->es_variante;

							}else {

								$id_portes = $producto['id'];

							}

					  $sql = "SELECT portes_fr_it_de FROM sc_calculo_portes WHERE id_padre=$id_portes";
					  $reg = $conn_trans->query($sql)->fetch_object();
					  $total_portes += $reg->portes_fr_it_de * $producto['cantidad'];

					}

					return $total_portes*1.21;

				}else {
					return 0;
				}

			break;

			//envios a AUSTRALIA
			case 'AUSTRALIA':

			$tramo_peso = obtener_tramo_peso_us ( $peso );

			if ( $tramo_peso == 'kg_3000') {

				echo '<script>alert("To calculate the price of transportation please  contact us and we will personally assist you with your order:\n\n  Phone/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/en/contact")</script>';
			  return 999999;

			}	else {

				$sql = "SELECT $tramo_peso FROM sc_australia WHERE pais = 'AU'";
				$tarifa = $conn_trans->query($sql)->fetch_object()->$tramo_peso;

				return $tarifa * 1.21;

			}

			break;

			default:

			break;

			exit;

		}

		if ( $pais == 'IRLANDA') {
			$cod_postal = substr( $cod_postal, 0, 5);
		}else {
			$cod_postal = substr( $cod_postal, 0, 2);
		}

		$tramo_peso = obtener_tramo_peso ( $peso );

		$sql = "SELECT $tramo_peso FROM it_dachser WHERE pais='$pais' AND cp = '$cod_postal'
		UNION
		SELECT $tramo_peso FROM it_dbschenker WHERE pais='$pais' AND cp = '$cod_postal'
		-- UNION
		-- SELECT $tramo_peso FROM it_dsv WHERE pais='$pais' AND cp = '$cod_postal'
		UNION
		SELECT $tramo_peso FROM it_decoexsa WHERE pais='$pais' AND cp = '$cod_postal'
		UNION
		SELECT $tramo_peso FROM it_salvat WHERE pais='$pais' AND cp = '$cod_postal'
		UNION
		SELECT $tramo_peso FROM it_tsb WHERE pais='$pais' AND  cp = '$cod_postal'";
		// echo $sql;
		// exit;

		$res = $conn_trans->query($sql);
		// $reg=$res->fetch_array();
		// var_dump( $reg );
		// exit;

		$i = 0;
		$total = 0;
		if ( mysqli_num_rows($res) > 0 ) {

			while( $reg=$res->fetch_object() ) {
				if ( $reg->$tramo_peso > 0 && $reg->$tramo_peso != null && $reg->$tramo_peso != '' ) {
					$total += $reg->$tramo_peso;
					$i++;
			// echo $reg->$tramo_peso;
			// echo "<br>";
				}
			}
			// echo "<br>";
			// echo $total . ' / ' . $i . ' = ' . formatea_importe ( $total/$i );

			$tarifa = $total/$i;

			// echo "Tarifa: " . $tarifa;
			// echo "<br>";
			// echo "Seguro nacional: " . $seguro_internacional;
			// echo "<br>";
			// echo "Tasa carburante: " . $tarifa * $tasa_carburante/100;
			// echo "<br>";
			// echo "Gastos gestion: " . $tarifa * $gastos_gestion_internacional/100;
			// echo "<br>";

			return formatea_importe ( $tarifa + $seguro_internacional + ( $tarifa * $tasa_carburante/100 )  + ( $tarifa * $gastos_gestion_internacional/100 ) + $gastos_aduana ) * 1.21;

		}else if( $pais!='ESTADOS UNIDOS' && $pais!='UNITED KINGDOM' && $pais!='AUSTRALIA' ) {
			echo "<script>alert('The zip code of the selected shipping zone does not match any of our shipping zones. Check that it is correct and if the error persists contact us.')</script>";
			return 999999;
		}

	} else {


		$cod_postal_3 =  substr( $cod_postal, 0, 3);
		$cod_postal_4 =  substr( $cod_postal, 0, 4);
		$cod_postal = substr( $cod_postal, 0, 2);

		//codigos postales canarias
		if ( $cod_postal == 35 || $cod_postal == 38 ) {


			$tramo_peso = obtener_tramo_peso_canarias ( $peso );

			$sql = "SELECT $tramo_peso, importe_minimo FROM sc_canarias WHERE cp = $cod_postal_3";

			$res = $conn_trans->query($sql);
			$reg=$res->fetch_object();

			$importe_despacho_peninsula = 15;
			$importe_recogida = (float) 0.05 * $peso;
			$importe_minimo = $reg->importe_minimo;
			$importe_envio = $reg->$tramo_peso;

			if ( $importe_minimo > $importe_envio ) $importe_envio = $importe_minimo;

			$importe_total = $importe_recogida + $importe_despacho_peninsula + $importe_envio;

			return $importe_total * (1 + $gastos_gestion/100);

		// codigo postal de baleares
		}else if (  $cod_postal == 07 ) {

			if ( $cod_postal_4 == '0780' ) $zona = "IBIZA";
			elseif ( $cod_postal_4 == '0786' ) $zona = "FORMENTERA";
			elseif ( $cod_postal_3 == '070' || $cod_postal_3 == '076' || $cod_postal_3 == '073' ) $zona = "MALLORCA";
			elseif ( $cod_postal_3 == '077' ) $zona = "MENORCA";
			elseif ( $cod_postal_3 == '071' ) $zona = "SOLLER";
			elseif ( $cod_postal_3 == '074' ) $zona = "ALCUDIA";
			else $zona = "MALLORCA";

			$tramo_peso = obtener_tramo_peso ( $peso );

			$sql = "SELECT $tramo_peso FROM b_fornes WHERE localidad = '$zona'
			UNION
			SELECT $tramo_peso FROM b_trias WHERE localidad = '$zona'";

			$res = $conn_trans->query($sql);
			$i = 0;
			$total = 0;
			while( $reg=$res->fetch_object() ) {
				if ( $reg->$tramo_peso > 0 && $reg->$tramo_peso != null && $reg->$tramo_peso != '' ) {
					$total += $reg->$tramo_peso;
					$i++;
				}
			}
			$tarifa = $total/$i;
			return formatea_importe ( $tarifa + $seguro_nacional + ( $tarifa * $tasa_carburante/100 )  + ( $tarifa * $gastos_gestion_nacional/100 ));

	  }else {

	  	if ( $peso <= 207 ) {
	  		// echo $peso;
	  		// echo "<br>";
	  		// echo obtener_portes_es ( $peso );
				return obtener_portes_es ( $peso ) * 1.21;

			}else {

				$tramo_peso = obtener_tramo_peso ( $peso );

				$sql = "SELECT $tramo_peso FROM nt_cbl WHERE cp = $cod_postal
				UNION
				SELECT $tramo_peso FROM nt_dachser WHERE cp = $cod_postal
				UNION
				SELECT $tramo_peso FROM nt_tolosa_pardo WHERE cp = $cod_postal
				UNION
				SELECT $tramo_peso FROM nt_transaher WHERE cp = $cod_postal
				UNION
				SELECT $tramo_peso FROM nt_tsb WHERE  cp = $cod_postal";

				$res = $conn_trans->query($sql);
				$i = 0;
				$total = 0;
				while( $reg=$res->fetch_object() ) {
					if ( $reg->$tramo_peso > 0 && $reg->$tramo_peso != null && $reg->$tramo_peso != '' ) {
						$total += $reg->$tramo_peso;
						// echo $reg->$tramo_peso;
						// echo "<br>";
						$i++;
					}
				}
				// echo "Tarifa: " . $tarifa;
				// echo "<br>";
				// echo "Seguro nacional: " . $seguro_nacional;
				// echo "<br>";
				// echo "Tasa carburante: " . $tarifa * $tasa_carburante/100;
				// echo "<br>";
				// echo "Gastos gestion: " . $tarifa * $gastos_gestion/100;
				// echo "<br>";

				$tarifa = $total/$i;
				return formatea_importe ( $tarifa + $seguro_nacional + ( $tarifa * $tasa_carburante/100 )  + ( $tarifa * $gastos_gestion_nacional/100 ));

			}
		}
	}
}


function obtener_tramo_peso_us ( $peso ) {

		$tramos_peso = array (
			'35' => 'kg_35',
			'70' => 'kg_70',
			'105' => 'kg_105',
			'140' => 'kg_140',
			'175' => 'kg_175',
			'210' => 'kg_210',
			'245' => 'kg_245',
			'280' => 'kg_280',
			'315' => 'kg_315',
			'350' => 'kg_350',
			'385' => 'kg_385',
			'420' => 'kg_420',
			'455' => 'kg_455',
			'490' => 'kg_490',
			'525' => 'kg_525',
			'560' => 'kg_560',
			'595' => 'kg_595',
			'630' => 'kg_630',
			'665' => 'kg_665',
			'700' => 'kg_700',
			'735' => 'kg_735',
			'770' => 'kg_770',
			'805' => 'kg_805',
			'840' => 'kg_840',
			'875' => 'kg_875',
			'945' => 'kg_945',
			'980' => 'kg_980',
			'1015' => 'kg_1015',
			'1050' => 'kg_1050',
			'1085' => 'kg_1085',
			'1120' => 'kg_1120',
			'1155' => 'kg_1155',
			'1190' => 'kg_1190',
			'1225' => 'kg_1225',
			'1260' => 'kg_1260',
			'1295' => 'kg_1295',
			'1330' => 'kg_1330',
			'1365' => 'kg_1365',
			'1400' => 'kg_1400',
			'1435' => 'kg_1435',
			'1470' => 'kg_1470',
			'1505' => 'kg_1505',
			'1540' => 'kg_1540',
			'1575' => 'kg_1575',
			'1610' => 'kg_1610',
			'1645' => 'kg_1645',
			'1680' => 'kg_1680',
			'1715' => 'kg_1715',
			'1750' => 'kg_1750',
			'3000' => 'kg_3000',
		);

		$encontrado = 0;
		$anterior = 0;
		$actual = 0;
		$tramo = '';

		foreach ( $tramos_peso as $key => $value ) {
			if ( $key > $anterior && $key <= $peso ) {
				$anterior = $value;
			}elseif ( $key > $anterior && $key > $peso ) {
				$tramo = $value;
				break;
			}elseif ( $peso > $key ) {

				exit;
			}
		}
		return $tramo;
}


function obtener_portes_es ( $peso ) {

	if ( $peso == 38.4 ) {
		return 10;
	}else if ( $peso == 70.3 ) {
		return 15;
	}else if ( $peso == 102.2 ) {
		return 22;
	}else if ( $peso == 134.1 ) {
		return 26;
	}else if ( $peso == 166 ) {
		return 28;
	}else if ( $peso == 197.9 ) {
		return 32;
	}else if ( $peso <= 3 ) {
		return 10;
	}else if ( $peso <= 6 ) {
		return 8;
	}else if ( $peso <= 9 ) {
		return 8;
	}else if ( $peso <= 12 ) {
		return 8;
	}else if ( $peso <= 15 ) {
		return 10;
	}else if ( $peso <= 18 ) {
		return 10;
	}else if ( $peso <= 21 ) {
		return 10;
	}else if ( $peso <= 24 ) {
		return 30;
	}else if ( $peso <= 27 ) {
		return 36;
	}else if ( $peso <= 30 ) {
		return 42;
	}else if ( $peso <= 32 ) {
		return 10;
	}else if ( $peso <= 35 ) {
		return 18;
	}else if ( $peso <= 38 ) {
		return 18;
	}else if ( $peso <= 41 ) {
		return 18;
	}else if ( $peso <= 44 ) {
		return 20;
	}else if ( $peso <= 47 ) {
		return 20;
	}else if ( $peso <= 50 ) {
		return 20;
	}else if ( $peso <= 64 ) {
		return 15;
	}else if ( $peso <= 67 ) {
		return 23;
	}else if ( $peso <= 70 ) {
		return 23;
	}else if ( $peso <= 73 ) {
		return 23;
	}else if ( $peso <= 76 ) {
		return 25;
	}else if ( $peso <= 79 ) {
		return 25;
	}else if ( $peso <= 82 ) {
		return 25;
	}else if ( $peso <= 96 ) {
		return 27;
	}else if ( $peso <= 99 ) {
		return 27;
	}else if ( $peso <= 102 ) {
		return 27;
	}else if ( $peso <= 102.2 ) {
		return 22;
	}else if ( $peso <= 105 ) {
		return 29;
	}else if ( $peso <= 109 ) {
		return 29;
	}else if ( $peso <= 111 ) {
		return 29;
	}else if ( $peso <= 128 ) {
		return 23;
	}else if ( $peso <= 131 ) {
		return 31;
	}else if ( $peso <= 134 ) {
		return 31;
	}else if ( $peso <= 137 ) {
		return 31;
	}else if ( $peso <= 140 ) {
		return 32;
	}else if ( $peso <= 143 ) {
		return 32;
	}else if ( $peso <= 160 ) {
		return 28;
	}else if ( $peso <= 163 ) {
		return 36;
	}else if ( $peso <= 166 ) {
		return 36;
	}else if ( $peso <= 169 ) {
		return 36;
	}else if ( $peso <= 172 ) {
		return 38;
	}else if ( $peso <= 175 ) {
		return 38;
	}else if ( $peso <= 192 ) {
		return 32;
	}else if ( $peso <= 195 ) {
		return 40;
	}else if ( $peso <= 198 ) {
		return 40;
	}else if ( $peso <= 201 ) {
		return 40;
	}else if ( $peso <= 204 ) {
		return 42;
	}else if ( $peso <= 207 ) {
		return 42;
	}else {
		return 999999;
	}
}
function obtener_portes_uk ( $peso ) {

	if ( $peso <= 3 ) {
		return 10;
	}else if ( $peso <= 6 ) {
		return 12;
	}else if ( $peso <= 9 ) {
		return 14;
	}else if ( $peso <= 12 ) {
		return 16;
	}else if ( $peso <= 15 ) {
		return 18;
	}else if ( $peso <= 18 ) {
		return 21;
	}else if ( $peso <= 21 ) {
		return 24;
	}else if ( $peso <= 24 ) {
		return 30;
	}else if ( $peso <= 27 ) {
		return 36;
	}else if ( $peso <= 30 ) {
		return 42;
	}else if ( $peso <= 32 ) {
		return 50;
	}else if ( $peso <= 35 ) {
		return 60;
	}else if ( $peso <= 38 ) {
		return 62;
	}else if ( $peso <= 41 ) {
		return 64;
	}else if ( $peso <= 44 ) {
		return 66;
	}else if ( $peso <= 47 ) {
		return 68;
	}else if ( $peso <= 64 ) {
		return 84;
	}else if ( $peso <= 67 ) {
		return 94;
	}else if ( $peso <= 70 ) {
		return 96;
	}else if ( $peso <= 73 ) {
		return 98;
	}else if ( $peso <= 76 ) {
		return 100;
	}else if ( $peso <= 79 ) {
		return 102;
	}else if ( $peso <= 96 ) {
		return 150;
	}else if ( $peso <= 99 ) {
		return 160;
	}else if ( $peso <= 102 ) {
		return 162;
	}else if ( $peso <= 105 ) {
		return 164;
	}else if ( $peso <= 109 ) {
		return 166;
	}else if ( $peso <= 111 ) {
		return 168;
	}else if ( $peso <= 128 ) {
		return 130;
	}else if ( $peso <= 131 ) {
		return 140;
	}else if ( $peso <= 134 ) {
		return 142;
	}else if ( $peso <= 137 ) {
		return 144;
	}else if ( $peso <= 140 ) {
		return 146;
	}else if ( $peso <= 143 ) {
		return 148;
	}else if ( $peso <= 160 ) {
		return 154;
	}else if ( $peso <= 163 ) {
		return 164;
	}else if ( $peso <= 166 ) {
		return 166;
	}else if ( $peso <= 169 ) {
		return 168;
	}else if ( $peso <= 172 ) {
		return 170;
	}else if ( $peso <= 175 ) {
		return 172;
	}else {
		return 999999;
	}
}

function obtener_portes_uk2 ( $peso ) {

	if ( $peso <= 5 ) {
		return 30;
	}else if ( $peso <= 8 ) {
		return 50;
	}else if ( $peso <= 10 ) {
		return 100;
	}else if ( $peso <= 13 ) {
		return 120;
	}else if ( $peso <= 40 ) {
		return 125;
	}else if ( $peso <= 80 ) {
		return 235;
	}else if ( $peso <= 120 ) {
		return 350;
	}else if ( $peso <= 160 ) {
		return 272;
	}else if ( $peso <= 200 ) {
		return 254;
	}else if ( $peso <= 240 ) {
		return 354;
	}else if ( $peso <= 280 ) {
		return 454;
	}else if ( $peso <= 320 ) {
		return 554;
	}else if ( $peso <= 360 ) {
		return 654;
	}else if ( $peso <= 400 ) {
		return 754;
	}else {
		return 999999;
	}
}

function obtener_portes_fr ( $peso ) {

	if ( $peso <= 3 ) {
		return 9;
	}else if ( $peso <= 6 ) {
		return 10;
	}else if ( $peso <= 9 ) {
		return 12;
	}else if ( $peso <= 12 ) {
		return 14;
	}else if ( $peso <= 15 ) {
		return 15;
	}else if ( $peso <= 18 ) {
		return 24;
	}else if ( $peso <= 21 ) {
		return 25;
	}else if ( $peso <= 24 ) {
		return 28;
	}else if ( $peso <= 27 ) {
		return 29;
	}else if ( $peso <= 30 ) {
		return 30;
	}else if ( $peso <= 32 ) {
		return 45;
	}else if ( $peso <= 35 ) {
		return 54;
	}else if ( $peso <= 38.5 ) {
		return 48;
	}else if ( $peso <= 41 ) {
		return 57;
	}else if ( $peso <= 44 ) {
		return 59;
	}else if ( $peso <= 47 ) {
		return 60;
	}else if ( $peso <= 64 ) {
		return 58;
	}else if ( $peso <= 67 ) {
		return 67;
	}else if ( $peso <= 70 ) {
		return 68;
	}else if ( $peso <= 73 ) {
		return 70;
	}else if ( $peso <= 96 ) {
		return 69;
	}else if ( $peso <= 99 ) {
		return 78;
	}else if ( $peso <= 96 ) {
		return 69;
	}else if ( $peso <= 102 ) {
		return 79;
	}else if ( $peso <= 105 ) {
		return 81;
	}else if ( $peso <= 128 ) {
		return 82;
	}else if ( $peso <= 131 ) {
		return 91;
	}else if ( $peso <= 134 ) {
		return 92;
	}else if ( $peso <= 137 ) {
		return 94;
	}else if ( $peso <= 140 ) {
		return 96;
	}else if ( $peso <= 160 ) {
		return 97;
	}else if ( $peso <= 163 ) {
		return 106;
	}else if ( $peso <= 166 ) {
		return 107;
	}else if ( $peso <= 169 ) {
		return 109;
	}else if ( $peso <= 172 ) {
		return 111;
	}else {
		return 999999;
	}
}

// function obtener_portes_uk ( $peso ) {

// 	if ( $peso <= 5 ) {
// 		return 60;
// 	}else if ( $peso <= 8 ) {
// 		return 80;
// 	}else if ( $peso <= 10 ) {
// 		return 100;
// 	}else if ( $peso <= 13 ) {
// 		return 120;
// 	}else if ( $peso <= 40 ) {
// 		return 125;
// 	}else if ( $peso <= 80 ) {
// 		return 235;
// 	}else if ( $peso <= 120 ) {
// 		return 350;
// 	}else if ( $peso <= 160 ) {
// 		return 475;
// 	}else if ( $peso <= 200 ) {
// 		return 600;
// 	}else if ( $peso <= 240 ) {
// 		return 725;
// 	}else if ( $peso <= 280 ) {
// 		return 850;
// 	}else if ( $peso <= 320 ) {
// 		return 975;
// 	}else if ( $peso <= 360 ) {
// 		return 1100;
// 	}else if ( $peso <= 400 ) {
// 		return 1225;
// 	}else {
// 		return 999999;
// 	}
// }

function obtener_portes_au ( $peso ) {

	if ( $peso <= 5 ) {
		return 60;
	}else if ( $peso <= 8 ) {
		return 80;
	}else if ( $peso <= 10 ) {
		return 100;
	}else if ( $peso <= 13 ) {
		return 120;
	}else if ( $peso <= 40 ) {
		return 125;
	}else if ( $peso <= 80 ) {
		return 235;
	}else if ( $peso <= 120 ) {
		return 350;
	}else if ( $peso <= 160 ) {
		return 475;
	}else if ( $peso <= 200 ) {
		return 600;
	}else if ( $peso <= 240 ) {
		return 725;
	}else if ( $peso <= 280 ) {
		return 850;
	}else if ( $peso <= 320 ) {
		return 975;
	}else if ( $peso <= 360 ) {
		return 1100;
	}else if ( $peso <= 400 ) {
		return 1225;
	}else {
		return 999999;
	}
}



function obtener_tramo_peso_uk ( $peso ) {

		$tramos_peso = array (
			'5' => 'kg_5',
			'10' => 'kg_10',
			'20' => 'kg_20',
			'30' => 'kg_30',
			'40' => 'kg_40',
			'50' => 'kg_50',
			'60' => 'kg_60',
			'70' => 'kg_70',
			'80' => 'kg_80',
			'90' => 'kg_90',
			'100' => 'kg_100',
			'120' => 'kg_120',
			'140' => 'kg_140',
			'160' => 'kg_160',
			'180' => 'kg_180',
			'200' => 'kg_200'
			);

		$encontrado = 0;
		$anterior = 0;
		$actual = 0;
		$tramo = '';

		foreach ( $tramos_peso as $key => $value ) {
			if ( $key > $anterior && $key <= $peso ) {
				$anterior = $value;
			}elseif ( $key > $anterior && $key > $peso ) {
				$tramo = $value;
				break;
			}elseif ( $peso > $key ) {

				exit;
			}
		}
		return $tramo;
}

function obtener_tramo_peso ( $peso ) {

	$tramos_peso = array (
			'10' => 'kg_10',
			'20' => 'kg_20',
			'30' => 'kg_30',
			'40' => 'kg_40',
			'50' => 'kg_50',
			'60' => 'kg_60',
			'70' => 'kg_70',
			'80' => 'kg_80',
			'90' => 'kg_90',
			'100' => 'kg_100',
			'125' => 'kg_125',
			'150' => 'kg_150',
			'175' => 'kg_175',
			'200' => 'kg_200',
			'250' => 'kg_250',
			'300' => 'kg_300',
			'350' => 'kg_350',
			'400' => 'kg_400',
			'450' => 'kg_450',
			'500' => 'kg_500',
			'600' => 'kg_600',
			'700' => 'kg_700',
			'800' => 'kg_800',
			'900' => 'kg_900',
			'1000' => 'kg_1000',
			'1250' => 'kg_1250',
			'1500' => 'kg_1500',
			'1750' => 'kg_1750',
			'2000' => 'kg_2000',
			'2250' => 'kg_2250',
			'2500' => 'kg_2500',
			'3000' => 'kg_3000'
		);

	$encontrado = 0;
	$anterior = 0;
	$actual = 0;
	$tramo = '';

	foreach ( $tramos_peso as $key => $value ) {
		if ( $key > $anterior && $key <= $peso ) {
			$anterior = $value;
		}elseif ( $key > $anterior && $key > $peso ) {
			$tramo = $value;
			break;
		}elseif ( $peso > $key ) {

			exit;
		}
	}
	return $tramo;
}

function obtener_tramo_peso_canarias ( $peso ) {

	$tramos_peso = array (
			'10' => 'kg_10',
			'15' => 'kg_15',
			'20' => 'kg_20',
			'25' => 'kg_25',
			'30' => 'kg_30',
			'35' => 'kg_35',
			'40' => 'kg_40',
			'45' => 'kg_45',
			'50' => 'kg_50',
			'55' => 'kg_55',
			'60' => 'kg_60',
			'65' => 'kg_65',
			'70' => 'kg_70',
			'75' => 'kg_75',
			'80' => 'kg_80',
			'85' => 'kg_85',
			'90' => 'kg_90',
			'95' => 'kg_95',
			'100' => 'kg_100',
			'110' => 'kg_110',
			'120' => 'kg_120',
			'130' => 'kg_130',
			'140' => 'kg_140',
			'150' => 'kg_150',
			'160' => 'kg_160',
			'170' => 'kg_170',
			'180' => 'kg_180',
			'190' => 'kg_190',
			'200' => 'kg_200',
			'210' => 'kg_210',
			'220' => 'kg_220',
			'230' => 'kg_230',
			'240' => 'kg_240',
			'250' => 'kg_250',
			'260' => 'kg_260',
			'270' => 'kg_270',
			'280' => 'kg_280',
			'290' => 'kg_290',
			'300' => 'kg_300',
			'310' => 'kg_310',
			'320' => 'kg_320',
			'330' => 'kg_330',
			'340' => 'kg_340',
			'350' => 'kg_350',
			'360' => 'kg_360',
			'370' => 'kg_370',
			'380' => 'kg_380',
			'390' => 'kg_390',
			'400' => 'kg_400',
			'410' => 'kg_410',
			'420' => 'kg_420',
			'430' => 'kg_430',
			'440' => 'kg_440',
			'450' => 'kg_450',
			'460' => 'kg_460',
			'470' => 'kg_470',
			'480' => 'kg_480',
			'490' => 'kg_490',
			'500' => 'kg_500',
		);

	$encontrado = 0;
	$anterior = 0;
	$actual = 0;
	$tramo = '';

	if ( $peso > 500 ) {
		return 'kg_500';
	}else {

		foreach ( $tramos_peso as $key => $value ) {

			if ( $key > $anterior && $key <= $peso ) {
				$anterior = $value;
			}elseif ( $key > $anterior && $key > $peso ) {
				$tramo = $value;
				break;
			}elseif ( $peso > $key ) {

				exit;
			}

		}

	return $tramo;
	}


}

?>