<?php


class Checkout {

function executeQuery($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    // return $stmt->fetchAll();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function executeSelect($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll();
}

public function executeSelectArray($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function executeSelectObj($sql, $args = []) {

    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function executeInsert($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $pdo->lastInsertId();
}

function executeUpdate($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->rowCount();
}

public function comprueba_dir_pred_activa() {
    $id_cliente = $_SESSION['smart_user']['id'];
    $sql = "SELECT * FROM datos_envio WHERE id_cliente = ? AND activo = 1 AND predeterminado = 1";
    $arguments = [$id_cliente];

    $result = $this->executeSelectObj($sql, $arguments);

    if (!empty($result)) {
        return true;
    } else {
        return false;
    }
}


function obten_dir_envios_paises_array($id_idioma) {
    $sql="SELECT * FROM paises WHERE id_idioma= ? and activo=1";
    $arguments = [$id_idioma];

    return $this->executeSelectArray( $sql, $arguments );
}

function obten_estados_us() {
    $sql = "SELECT * FROM sc_estados_us WHERE activo = 1";
    return $this->executeSelectArray($sql);
}

function obten_paises() {
    $sql = "SELECT * FROM paises";
    return $this->executeQuery($sql);
}

function obten_paises_activos_idioma( $id_idioma ) {
    $sql = "SELECT P.cod_pais, PT.nombre FROM paises P JOIN paises_traducciones PT ON P.id = PT.id_pais WHERE activo = 1 AND PT.id_idioma = $id_idioma";
    return $this->executeQuery($sql);
}

function obten_paises_activos() {
    $sql = "SELECT * FROM paises where activo = 1";
    return $this->executeQuery($sql);
}

function obten_idiomas_pais( $cod_pais ) {
    $sql = "SELECT
                PI.cod_pais,
                ID.id as id_idioma,
                ID.nombre_idioma,
                ID.idioma as cod_idioma,
                ID.pais as cod_idioma_pais
            FROM paises_idiomas PI
            JOIN
            idiomas ID ON PI.idioma=ID.id
            WHERE PI.cod_pais=?";

    $arguments = [$cod_pais];
    return $this->executeQuery($sql, $arguments);
}

function obten_idioma_pais( $cod_pais ) {
    $sql = "SELECT idioma FROM paises where cod_pais = ?";
    $arguments = [$cod_pais];
    return $this->executeSelectObj($sql, $arguments)[0];
}

function custom_real_escape_string($string) {
    if ($string === null) {
        return '';
    }

    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");

    return str_replace($search, $replace, $string);
}

public function get_cambio_divisa($divisa) {
    // echo $divisa;
    $sql = "SELECT $divisa as valor FROM sc_cambio_divisas";
    return $this->executeSelectArray($sql);
}

function obten_idiomas() {
    $sql = "SELECT * FROM idiomas";
    return $this->executeQuery($sql);
}

function obten_dir_envios_paises_obj($id_idioma) {
    $sql = "
        SELECT pt.*
        FROM paises_traducciones pt
        INNER JOIN paises p ON p.id = pt.id_pais
        WHERE pt.id_idioma = ? AND p.activo = 1
    ";
    $arguments = [$id_idioma];

    return $this->executeSelectObj($sql, $arguments);
}

function cambia_fecha_normal($fecha){
    date_default_timezone_set('Europe/Madrid');
    $date = date_create($fecha);
    return date_format($date, 'd/m/Y');
}

function cambia_fecha_mysql($fecha){
  date_default_timezone_set('Europe/Madrid');
  $date = date_create($fecha);
  return date_format($date, 'Y/m/d');
}

function cambiaFormatoFecha($fecha) {
    if(strpos($fecha, '/') !== false) {
        $valorPartes = explode("/",$fecha);
        $fecha = $valorPartes[2]."-".$valorPartes[1]."-".$valorPartes[0];

    }else{
        $valorPartes = explode("-",$fecha);
        $fecha = $valorPartes[2]."/".$valorPartes[1]."/".$valorPartes[0];
    }
    return $fecha;
}

function cambia_fecha_hora ( $fecha ) {
    $dat = date_create( $fecha );
  return date_format( $dat, 'd/m/Y | H:i' );
}
function obten_hora( $fecha ) {
    $dt = new DateTime($fecha);
    $time = $dt->format('H:iA');
    echo $time;
}
function obten_fecha( $fecha ) {
    $dt = new DateTime( $fecha );
    $date = $dt->format( 'd/m/Y' );
    echo $date;
}

function cambia_coma($cadena) {
    $cadena = str_replace(",",".",$cadena);
    return $cadena;
}

function formatea_importe($precio){
    $precio_formateado= number_format($precio, 2, ".", "");
    return $precio_formateado;
}

function esta_logueado() {

    if ( session_status() === PHP_SESSION_NONE){@session_start();}
    if ( isset( $_SESSION['smart_user']['login'] ) && $_SESSION['smart_user']['login']=='1' ) {
        return true;
    }
    return false;
}

// function obten_dir_envio($id) {
// 	global $mysqli;
// 	$sql="SELECT * FROM datos_envio WHERE id ='".$id."'";
// 	return $mysqli->query($sql)->fetch_object();
// }

// function obten_dir_envio( $id ) {

//     $sql="SELECT * FROM datos_envio WHERE id =?";
//     $arguments = [$id];
//     return $this->executeSelectArray( $sql, $arguments );

// }

function obten_ped_dir_envio( $id_pedido ) {

    $sql="SELECT * FROM pedidos_dir_envio WHERE id =?";
    $arguments = [$id_pedido];
    return $this->executeQuery( $sql, $arguments );

}

function obten_dir_facturacion( $id ) {

    $sql="SELECT * FROM datos_facturacion WHERE id =?";
    $arguments = [$id];
    return $this->executeQuery( $sql, $arguments );

}
// function obten_dir_facturacion( $id ) {

//     $sql="SELECT * FROM datos_facturacion WHERE id =?";
//     $arguments = [$id];
//     return $this->executeSelectArray( $sql, $arguments );

// }

function obten_ped_dir_facturacion( $id_pedido ) {

    $sql="SELECT * FROM pedidos_dir_factura WHERE id =?";
    $arguments = [$id_pedido];
    return $this->executeQuery( $sql, $arguments );

}

function obten_ped_cupon( $id_pedido ) {

    $sql="SELECT * FROM pedidos_cupones_aplicados WHERE id_pedido =?";
    $arguments = [$id_pedido];
    return $this->executeQuery( $sql, $arguments )[0];

}


function obtener_cupones_descuento_user($id_user){
    $sql="SELECT * FROM cupones_descuento WHERE distribuidor =? AND eliminado = 0";
    $arguments = [$id_user];
    return $this->executeQuery( $sql, $arguments );
}

function obten_ped_cupon_prod( $id ) {

    $sql="SELECT * FROM pedidos_cupones_aplicados WHERE id =?";
    $arguments = [$id];
    return $this->executeQuery( $sql, $arguments );

}

public function calcula_peso_pedido() {

    $carrito = new Carrito();
    $carro = $carrito->get_content();
    $peso = 0;
    if( $carrito->articulos_total() > 0 ) {
        foreach($carro as $producto) {
            $peso += ($producto['cantidad'] * $producto['peso']);
        }
    }
    return $peso;
}

public function obten_ref_nuevo_pedido() {
    $sql = "SELECT ref_pedido FROM pedidos ORDER BY ref_pedido DESC LIMIT 1";
    $arguments = [];
    $result = $this->executeQuery($sql, $arguments);
    // var_dump($result);
    // echo $result[0]->ref_pedido;
    if (!empty($result)) {
        $ref_ultimo = $result[0]->ref_pedido;
        $ref_num = str_replace('SC-', '', $ref_ultimo);
        $ref_num = intval($ref_num); // Convertir a entero
    } else {
        $ref_num = 0; // Si no hay resultados, comenzamos desde 0
    }
    // echo $ref_num;

    return 'SC-' . str_pad($ref_num + 1, 5, "0", STR_PAD_LEFT);
}

function obten_datos_user( $id ) {

    $sql="SELECT * FROM users WHERE uid =?";
    $arguments = [$id];

    return $this->executeQuery( $sql, $arguments );
}

// function obten_datos_user( $id ) {

//     $sql="SELECT * FROM users WHERE uid =?";
//     $arguments = [$id];

//     return $this->executeSelectArray( $sql, $arguments );
// }

public function obten_dir_envio_predeterminado($id_cliente) {
    $sql = "SELECT id FROM datos_envio WHERE id_cliente = ? AND predeterminado = 1 LIMIT 1";
    $arguments = [$id_cliente];
    $result = $this->executeSelectObj($sql, $arguments);
    if (!empty($result)) {
        return $result[0]->id;
    }
    return null;
}


public function obten_dir_facturacion_predeterminado($id_cliente) {
    $sql = "SELECT id FROM datos_facturacion WHERE id_cliente = ? AND predeterminado = 1 LIMIT 1";
    $arguments = [$id_cliente];

    $result = $this->executeSelectObj($sql, $arguments);

    if (!empty($result)) {
        return $result[0]->id;
    }

    return null;
}

// function obten_datos_user( $id ) {

//     $sql="SELECT * FROM users WHERE uid =?";
//     $arguments = [$id];

//     return $this->executeSelectArray( $sql, $arguments );
// }

/*
comprobamos si hay que aplicar el IVA ($tipo_impuesto)
1 - [21%]
2 - [0%]
3 - [0%]
4 - [21%]
5 - IVA según pais destino
6 - [0%]
*/
function obten_tipo_impuesto_envio( $id_facturacion, $id_envio ) {

    $dir_facturacion = $this->obten_dir_facturacion( $id_facturacion )[0];
    $dir_envio = $this->obten_dir_envio( $id_envio )[0];
    // echo "Hola";
    // var_dump($dir_envio);
    // var_dump($dir_envio[0]);
    // echo $dir_envio[0];
    // echo $dir_envio['pais'];

    $provincias_no_iva = array ( 51, 52, 350, 380, 353, 356, 388, 389 );
    $paises_eu = array ( 'DE', 'BE', 'HR', 'DK', 'FR', 'IE', 'LV', 'LU', 'NL', 'SE', 'BG', 'SK', 'EE', 'GR', 'MT', 'PL', 'CZ', 'AT', 'CY', 'SI', 'FI', 'HU', 'IT', 'LT', 'PT', 'RO' );

    if ( $dir_envio['pais'] == 'ES' && !in_array ( $dir_envio['provincia'], $provincias_no_iva ) ) return 1;//nacional peninsula

    else if ( $dir_envio['pais'] == 'ES' && in_array ( $dir_envio['provincia'], $provincias_no_iva ) ) return 2;//canarias

    else if ( $dir_envio['pais'] != 'ES' ) {

        if ( in_array ( $dir_envio['pais'], $paises_eu ) ) {

            if($id_facturacion && $id_envio){
                if ( $this->comprueba_vies ( $dir_facturacion->pais, $dir_facturacion->nif ) && ( $dir_facturacion->tipo_factura == 'Empresa' || $dir_facturacion->tipo_factura == 'Autonomo' ) ) return 3;//UE con VIES

                // else if ( (double) obten_ventas_no_vies() < (double) obten_max_importe_ventas_ue_no_vies() ) return 4;//UE sin VIES no supera limite anual
    
                else return 5;//UE sin VIES supera limite anual
            }
            
        }else {

            return 6;//Resto paises

        }
    }
}

function comprueba_vies( $pais, $nif ) {

    $vies='';

    for ($i = 0; $i < strlen( $nif ); $i++) {
        if ( is_numeric( $nif[$i] ) ) {
            $vies .= $nif[$i];
        }
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ec.europa.eu/taxation_customs/vies/rest-api/ms/' . strtoupper( $pais ) . '/vat/' . $vies,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);

    if ( $response['userError'] == 'VALID' ) {
        return true;
    }else {
        return false;
    }
}

function comprueba_cupon_aplicado () {

    if( isset ( $_SESSION['codigo_descuento']['nombre'] ) && isset ( $_SESSION['codigo_descuento']['valor'] ) )  {

        $carrito = new Carrito();

        $descuento_aplicado = $_SESSION['codigo_descuento']['nombre'];
        $descuento_id = $_SESSION['codigo_descuento']['id'];
        $aplicacion_descuento = $_SESSION['codigo_descuento']['aplicacion'];
        // $importe_descuento;

        if ( $_SESSION['codigo_descuento']['aplicacion'] != 1 ) {

            if ( $_SESSION['codigo_descuento']['tipo'] == '%') {

                /* recorremos los productos del carrito para encontrar productos afectados por el descuento y aplicarlo sobre estos */
                $id_prods_descuento = $this->obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['aplicacion'] )->id_prods;
                $array_id_prods_descuento = explode( '|', $id_prods_descuento );

                $carro = $carrito->get_content();
                $importe_prod_descuento = 0;

                foreach($carro as $producto) {
                    $obten_descuento_producto = $this->obten_descuento_producto($producto['id'], $_SESSION['user_ubicacion']);
                    if ( $obten_descuento_producto == 0 ) {
                        if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
                            $importe_prod_descuento += $producto["cantidad"] * $producto["precio"];
                        }
                    }
                }

                $importe_descuento = $importe_prod_descuento * ( $_SESSION['codigo_descuento']['valor'] / 100 );

            }elseif ( $_SESSION['codigo_descuento']['tipo'] == '€') {
                $importe_descuento = $_SESSION['codigo_descuento']['valor'];
            }

        }else {

            if ( $_SESSION['codigo_descuento']['tipo'] == '%') {
                $aplicacion_descuento = $this->obten_aplicacion_descuento($_SESSION['codigo_descuento']['aplicacion']);
                $carro = $carrito->get_content();
                $importe_prod_descuento = 0;

                foreach ($carro as $producto) {
                    $obten_descuento_producto = $this->obten_descuento_producto($producto['id'], $_SESSION['user_ubicacion']);
                    if ($obten_descuento_producto == 0){
                        // echo "Hola";
                        $importe_prod_descuento += $producto["cantidad"] * $producto["precio"];
                    }

                    // echo $importe_prod_descuento;
                }
                $importe_descuento = $importe_prod_descuento * ($_SESSION['codigo_descuento']['valor'] / 100);
            }elseif ( $_SESSION['codigo_descuento']['tipo'] == '€') {
                $importe_descuento = $_SESSION['codigo_descuento']['valor'];
            }
        }

        $cupon = array(
                        'id' => $descuento_id,
                        'importe' => $importe_descuento,
                        'aplicacion' => $aplicacion_descuento,
                      );

        return json_decode( json_encode( $cupon, JSON_FORCE_OBJECT ) );

        // return $importe_descuento;

    }else {

        return null;

    }

}

public function obten_aplicacion_descuento( $id ) {

    $sql="SELECT * FROM aplicacion_descuento WHERE id=?";
    $arguments = [$id];

    return $this->executeQuery( $sql, $arguments )[0];
}

// function obten_aplicacion_descuento( $id ) {

//     $sql="SELECT * FROM aplicacion_descuento WHERE id=?";
//     echo "SELECT * FROM aplicacion_descuento WHERE id=$id";echo "<br>";
//     $arguments = [$id];

//     return $this->executeSelectArray( $sql, $arguments )[0];
// }

function obten_cambio_divisa( $divisa ) {

    $sql="SELECT $divisa as valor FROM sc_cambio_divisas";
    $arguments = [];

    return $this->executeQuery( $sql, $arguments )[0];

}

// function obten_cambio_divisa( $divisa ) {

//     $sql="SELECT $divisa as valor FROM sc_cambio_divisas";
//     $arguments = [];

//     return $this->executeSelectArray( $sql, $arguments )[0];

// }

public function obten_dir_envio($id) {
    $sql = "SELECT * FROM datos_envio WHERE id =?";
    $arguments = [$id];
    return $this->executeSelectArray($sql, $arguments);
}

// public function obten_nombre_pais($cod_pais) {
//     $sql = "SELECT nombre FROM paises WHERE cod_pais = ?";
//     $arguments = [$cod_pais];

//     $result = $this->executeSelectObj($sql, $arguments);

//     if (empty($result)) {
//         return null; // O algún valor por defecto si no se encuentra el registro
//     }

//     return $result[0]->nombre;
// }



public function calcula_gastos_envio_pedido($dir_envio) {
    if (!isset($dir_envio)) {
        return 0.00;
    } else {
        $reg = $this->obten_dir_envio($dir_envio)[0];

        $peso = $this->calcula_peso_pedido();
        $cod_postal = $reg['cp']; // Asumiendo que executeSelectArray devuelve un array
        $pais = $reg['pais'];

        // echo "yepaa";
        // echo $pais;

        if ($this->programacion_esta_activo('portes_es') && $pais == 'ES') {
            $importe_minimo_portes_gratis_es = $this->obten_minimo_portes_gratis_es();
            $carrito = new Carrito();
            $carro = $carrito->get_content();

            if ($carrito->precio_total() >= $importe_minimo_portes_gratis_es) {
                return 0;
            } else {
                if (substr($reg['provincia'], 0, 2) == '35' || substr($reg['provincia'], 0, 2) == '38') {
                    return calcula_portes('', substr($reg['provincia'], 0, 3), $peso);
                } else {
                    return calcula_portes('', $cod_postal, $peso);
                }
            }
        }

        $paises_envio_kits_gratis = array('GB', 'US');

        if ($this->activado_portes_gratis_pais_producto() && in_array($pais, $this->obten_paises_portes_gratis()) && $this->productos_portes_gratis_en_carrito()) {
            if ($pais != 'ES') {
                return 0.00;
            } else {
                if (substr($reg['provincia'], 0, 2) == '35' || substr($reg['provincia'], 0, 2) == '38') {
                    return calcula_portes('', substr($reg['provincia'], 0, 3), $peso);
                } else {
                    return 0;
                }
            }
        } else {
            if (in_array($pais, $paises_envio_kits_gratis) && $this->portes_por_num_kits_activado()) {
                if (num_kits_en_carrito()) {
                    if ($pais != 'ES') {
                        return 0.00;
                    } else {
                        if (substr($reg['provincia'], 0, 2) == '35' || substr($reg['provincia'], 0, 2) == '38') {
                            return calcula_portes('', substr($reg['provincia'], 0, 3), $peso);
                        } else {
                            return 0;
                        }
                    }
                } else {
                    if ($pais == 'ES') {
                        return calcula_portes('', $cod_postal, $peso);
                    } else {
                        return calcula_portes(strtoupper($this->obten_nombre_pais($pais)), $cod_postal, $peso);
                    }
                }
            } else {
                if ($this->comprueba_envio_gratis()) {
                    if (substr($reg['provincia'], 0, 2) == '35' || substr($reg['provincia'], 0, 2) == '38') {
                        return calcula_portes('', substr($reg['provincia'], 0, 3), $peso);
                    } else {
                        return 0;
                    }
                } else {
                    if ($pais == 'ES') {
                        return calcula_portes('', $cod_postal, $peso);
                    } else {
                        return calcula_portes(strtoupper($this->obten_nombre_pais($pais)), $cod_postal, $peso);
                    }
                }
            }
        }
    }
}

public function portes_por_num_kits_activado() {
    $sql = "SELECT activado FROM configuracion WHERE id = ?";
    $arguments = [7];

    $result = $this->executeSelectObj($sql, $arguments);

    if (empty($result)) {
        return false;
    }

    return $result[0]->activado == 1;
}

public function obten_productos_portes_gratis() {
    $sql = "SELECT valor3 FROM configuracion WHERE id = ?";
    $arguments = [8];

    $result = $this->executeSelectObj($sql, $arguments);

    if (empty($result)) {
        return []; // Devuelve un array vacío si no se encuentra el registro
    }

    return explode('|', $result[0]->valor3);
}


public function productos_portes_gratis_en_carrito() {
    $carrito = new Carrito();
    $carro = $carrito->get_content();

    if ($carro) {
        $array_id_prods_descuento = $this->obten_productos_portes_gratis();
        foreach ($carro as $producto) {
            if (in_array($producto['id'], $array_id_prods_descuento)) {
                return true;
            }
        }
    }
    return false;
}


public function activado_portes_gratis_pais_producto() {
    $sql = "SELECT activado FROM configuracion WHERE id = ?";
    $arguments = [8];

    $result = $this->executeSelectObj($sql, $arguments);

    if (empty($result)) {
        return false;
    }

    return $result[0]->activado == 1;
}

public function obten_minimo_portes_gratis() {
    $sql = "SELECT valor FROM variables_web WHERE nombre_var = ?";
    $arguments = ['minimo_portes_gratis'];

    $result = $this->executeSelectObj($sql, $arguments);

    if (empty($result)) {
        return null; // o algún valor por defecto, dependiendo de tu lógica
    }

    return $result[0]->valor;
}

public function obten_productos_envio_gratis() {
    $sql = "SELECT id_producto FROM productos_envio_gratis WHERE activo = ?";
    $arguments = [1];

    $result = $this->executeSelectObj($sql, $arguments);

    $productos = [];
    foreach ($result as $reg) {
        $productos[] = $reg->id_producto;
    }

    return $productos;
}


public function comprueba_envio_gratis () {

	$descuento = 1;
	$min_importe_envio_gratis = $this->obten_minimo_portes_gratis();
	$envio_gratis = false;
	$importe_productos_envio_gratis = 0;
	$productos_envio_gratis = $this->obten_productos_envio_gratis ();

	$carrito = new Carrito();
	$carro = $carrito->get_content();

	/* si hay un descuento activo obtenemos el */
	if ( isset( $_SESSION['codigo_descuento'] ) ) {
		if ( $_SESSION['codigo_descuento']['tipo'] == '%') {
			$descuento = 1 - $_SESSION['codigo_descuento']['valor'] / 100;
			// $descuento = 1 + $_SESSION['codigo_descuento']['valor'] / 100;
		}
		if ( $_SESSION['codigo_descuento']['aplicacion'] != 1 ) {

			/* recorremos los productos del carrito para encontrar productos afectados por el descuento y aplicarlo sobre estos */
			$id_prods_descuento = $this->obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['aplicacion'] )->id_prods;
			$array_id_prods_descuento = explode( '|', $id_prods_descuento );

		}
	}


	if( $carrito->articulos_total() > 0 ) {

		foreach($carro as $producto) {

			/* comprobamos si es un articulo con derecho a portes gratis*/
			$producto_carro = $this->obten_datos_producto( $producto['id'] )->es_variante;

			if ( in_array( $producto_carro, $productos_envio_gratis ) ) {

				$precio_producto = $producto['precio'];

				/* comprobamos si hay descuento */
				if ( isset( $_SESSION['codigo_descuento'] ) ) {
					/* comprobamos si el descuento es de tipo % */
					if ( $_SESSION['codigo_descuento']['tipo'] == '%') {
						/* comprobamos si es un descuento aplicado en determinados articulos*/
						if ( $_SESSION['codigo_descuento']['aplicacion'] != 1 ) {
							/* comprobamos si el descuento afecta a este articulo y lo aplicamos*/
							if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
								$precio_producto = $precio_producto * $descuento;
							}
						}
					}
				/* si no hay descuento dividimos entre 1 y queda igual */
				}else {
					$precio_producto = $precio_producto / $descuento;
				}
				$importe_productos_envio_gratis += $producto['cantidad'] * $precio_producto;
			}
		}
	}

	/* descontamos el valor del IVA y comprobamos si cumple con el minimo para portes gratuitos*/
	if ( $importe_productos_envio_gratis / 1.21 > $min_importe_envio_gratis ) $envio_gratis=true;

	/* si hay oferta 2x1*/
	if ( $_SESSION['codigo_descuento']['p2x1'] != 0 ) {

		$id_prods_descuento = $this->obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['p2x1'] )->id_prods;
		$array_id_prods_descuento = explode( '|', $id_prods_descuento );

		$carro = $carrito->get_content();
		$num_kits = 0;
		$precio_menor = 0;
		$descuento_kits = 0;


		foreach($carro as $producto) {
			if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
				$num_kits += $producto['cantidad'];
				if ( $precio_menor == 0) {
					$precio_menor = $producto['precio'];
				}else {
					$precio_menor = ( $precio_menor < $producto['precio'] ) ? $precio_menor : $producto['precio'];
				}
			}
		}

		switch ( $num_kits ) {

			case '1':
				$descuento_kits = 0;
				break;
			case '2':
				$descuento_kits = 1 * $precio_menor;
				break;
			case '3':
				$descuento_kits = 1 * $precio_menor;
				break;
			case '4':
				$descuento_kits = 2 * $precio_menor;
				break;
			case '5':
				$descuento_kits = 2 * $precio_menor;
				break;
			case '6':
				$descuento_kits = 3 * $precio_menor;
				break;
			case '7':
				$descuento_kits = 3 * $precio_menor;
				break;
			case '8':
				$descuento_kits = 4 * $precio_menor;
				break;
			case '9':
				$descuento_kits = 4 * $precio_menor;
				break;
			case '10':
				$descuento_kits = 5 * $precio_menor;
				break;
			case '11':
				$descuento_kits = 5 * $precio_menor;
				break;
			case '12':
				$descuento_kits = 6 * $precio_menor;
				break;
			case '13':
				$descuento_kits = 6 * $precio_menor;
				break;
			case '14':
				$descuento_kits = 7 * $precio_menor;
				break;
			case '15':
				$descuento_kits = 7 * $precio_menor;
				break;
			case '16':
				$descuento_kits = 8 * $precio_menor;
				break;
			case '17':
				$descuento_kits = 8 * $precio_menor;
				break;
			case '18':
				$descuento_kits = 9 * $precio_menor;
				break;
			case '19':
				$descuento_kits = 9 * $precio_menor;
				break;
			case '20':
				$descuento_kits = 10 * $precio_menor;
				break;
			case '21':
				$descuento_kits = 10 * $precio_menor;
				break;

			default:
				# code...
				break;
		}
		$importe_descuento += $descuento_kits;

		if ( $importe_productos_envio_gratis - $importe_descuento / 1.21 < $min_importe_envio_gratis ) $envio_gratis=false;

	}


	if ( isset ( $_SESSION['smart_user']['dir_envio'] ) ) {

		$reg = $this->obten_dir_envio ( $_SESSION['smart_user']['dir_envio'] );
		if ( $reg->pais == 'ES' ) {
			return $envio_gratis;
		}else {
			return false;
		}
	}
	return $envio_gratis;
}


public function programacion_esta_activo($nombre) {
    $sql = "SELECT * FROM sc_programaciones WHERE nombre = ?";
    $arguments = [$nombre];

    $result = $this->executeSelectObj($sql, $arguments);

    if (empty($result)) {
        return false;
    }

    $reg = $result[0];

    $fecha_inicio = strtotime($reg->fecha_inicio);
    $fecha_fin = strtotime($reg->fecha_fin);
    $fecha = strtotime(date("d-m-Y H:i:00", time()));

    return ($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin);
}



public function obten_paises_portes_gratis() {
    $sql = "SELECT * FROM configuracion WHERE id = 8";
    $arguments = [];

    $result = $this->executeSelectObj($sql, $arguments);
    return explode('|', $result[0]->valor1);
}

// public function obten_descuento_producto($id_producto) {
//     global $lg;

//     $descuento = 'descuento_' . $lg;
//     $sql = "SELECT $descuento FROM productos WHERE id = ?";
//     $arguments = [$id_producto];

//     $result = $this->executeSelectObj($sql, $arguments);

//     if (!empty($result)) {
//         return $result[0]->$descuento;
//     }

//     return null; // O algún valor por defecto si no se encuentra el registro
// }


function obten_descuento_producto( $id_producto, $cod_pais ) {

    $sql="SELECT descuento FROM productos_precios_new WHERE id_producto=? AND cod_pais=?";
    $arguments = [ $id_producto, $cod_pais ];

    return $this->executeQuery( $sql, $arguments )[0]->descuento;

}

public function obten_minimo_portes_gratis_es() {
    $sql = "SELECT valor FROM variables_web WHERE nombre_var = ?";
    $arguments = ['minimo_portes_gratis_es'];

    $result = $this->executeSelectObj($sql, $arguments);

    if (empty($result)) {
        return null; // O algún valor por defecto si no se encuentra el registro
    }

    return $result[0]->valor;
}

// public function obten_minimo_portes_gratis_es () {
// 	global $mysqli;
// 	$sql="SELECT valor FROM variables_web WHERE nombre_var = 'minimo_portes_gratis_es'";
// 	return $mysqli->query($sql)->fetch_object()->valor;
// }

// function obten_descuento_producto( $id_producto, $idioma ) {

//     $sql="SELECT descuento FROM productos_precios_new WHERE id_producto=? AND id_idioma=?";
//     $arguments = [ $id_producto, $idioma ];

//     return $this->executeSelectArray( $sql, $arguments )[0]->descuento;

// }

function obten_descuento( $id ) {

    $sql="SELECT * FROM cupones_descuento WHERE id =?";
    $arguments = [$id];

    return $this->executeQuery( $sql, $arguments )[0];

}

// function obten_descuento( $id ) {

//     $sql="SELECT * FROM descuentos WHERE id =?";
//     $arguments = [$id];

//     return $this->executeSelectArray( $sql, $arguments )[0];

// }

function obten_codigo_idioma( $id ) {

    $sql="SELECT * FROM idiomas WHERE id =?";
    $arguments = [$id];

    $idioma = $this->executeQuery( $sql, $arguments )[0];

    return ( $idioma->pais != null) ? $idioma->idioma . '-' . $idioma->pais : $idioma->idioma;

}

function obten_datos_pedido_temp( $redsys_num_order ) {

    $sql="SELECT * FROM pedidos_temp WHERE redsys_num_order =? ORDER BY id DESC LIMIT 1";
    $arguments = [$redsys_num_order];

    return $this->executeQuery( $sql, $arguments )[0];

}

// function obten_datos_pedido_temp( $redsys_num_order ) {

//     $sql="SELECT * FROM pedidos_temp WHERE redsys_num_order =? ORDER BY id DESC LIMIT 1";
//     $arguments = [$redsys_num_order];

//     return $this->executeSelectArray( $sql, $arguments )[0];

// }

function permite_uso_descuento_usuario ( $id_cliente, $cupon_id ) {

    $sql="SELECT COUNT( cupon_id ) as num_usos FROM pedidos WHERE id_cliente= ? AND cupon_id = ?";
    $arguments = [ $id_cliente, $cupon_id ];

    $uso_descuento_usuario = $this->executeQuery( $sql, $arguments )[0]->num_usos;

    $uso_descuento_permitido = $this->obten_descuento ( $cupon_id )->uso_persona;
    if ( !$uso_descuento_usuario ) $uso_descuento_usuario = 0;
    return ( $uso_descuento_usuario < $uso_descuento_permitido ) ? true : false;

}

// function permite_uso_descuento_usuario ( $id_cliente, $cupon_id ) {

//     $sql="SELECT COUNT( cupon_id ) as num_usos FROM pedidos WHERE id_cliente= ? AND cupon_id = ?";
//     $arguments = [ $id_cliente, $cupon_id ];

//     $uso_descuento_usuario = $this->executeSelectArray( $sql, $arguments )[0]->num_usos;

//     $uso_descuento_permitido = $this->obten_descuento ( $cupon_id )->uso_persona;
//     if ( !$uso_descuento_usuario ) $uso_descuento_usuario = 0;
//     return ( $uso_descuento_usuario < $uso_descuento_permitido ) ? true : false;

// }

function obten_id_pedido_temp( $redsys_num_order ) {

    $sql = "SELECT id FROM pedidos_temp WHERE redsys_num_order = ?";
    $arguments = [ $redsys_num_order ];

    return $this->executeQuery( $sql, $arguments )[0]->id;

}

function obten_id_user_pedido_temp( $redsys_num_order ) {

    $sql = "SELECT id_cliente FROM pedidos_temp WHERE redsys_num_order = ?";
    $arguments = [ $redsys_num_order ];

    return $this->executeQuery( $sql, $arguments )[0]->id_cliente;

}

function obten_datos_pedido( $id_pedido ) {

    $sql = "SELECT * FROM pedidos WHERE id = ?";
    $arguments = [$id_pedido];

    return $this->executeQuery( $sql, $arguments )[0];
}

function obten_datos_pedido_ref( $ref_pedido ) {
    $sql = "SELECT * FROM pedidos WHERE ref_pedido = ?";
    $arguments = [$ref_pedido];

    return $this->executeQuery( $sql, $arguments )[0];
}

function obten_detalles_pedido( $id_pedido ) {
    $sql = "SELECT * FROM detalles_pedido WHERE id_pedido = ?";
    $arguments = [$id_pedido];

    return $this->executeQuery( $sql, $arguments );
}

public function obten_datos_producto($id_producto) {
    $sql = "SELECT * FROM productos WHERE id = ?";
    $arguments = [$id_producto];

    return $this->executeQuery($sql, $arguments)[0];
}



function obten_datos_producto_new($id_producto) {
    $sql = "SELECT * FROM productos WHERE id = ?";
    $arguments = [$id_producto];

    return $this->executeQuery($sql, $arguments)[0];
}

function lista_pedido_ref( $ref_pedido ) {

    $sql = "SELECT * FROM pedidos WHERE ref_pedido = '$ref_pedido'";
    $id_pedido = $this->obten_datos_pedido_ref( $ref_pedido )->id;

    $result = $this->obten_detalles_pedido( $id_pedido );

    $lista="";
    $lista .= "<div style='padding-left:5%;font-weight:700'>";

    foreach ( $result as $reg ) {

        $prod = $this->obten_datos_producto( $reg->id_prod );
        $lista .='<div class="item">' . $reg->cantidad . ' x ' . $prod->nombre_es . '<br></div>';
    }

    $lista .= "</div>";
    return $lista;

}


function detalle_pedido_ref( $ref_pedido ) {

    $id_pedido = $this->obten_datos_pedido_ref( $ref_pedido )->id;

    $result = $this->obten_detalles_pedido( $id_pedido );

    $lista="";

    foreach ( $result as $reg ) {

        $prod = $this->obten_datos_producto( $reg->id_prod );
        $lista .= $reg->cantidad . ' x ' . $prod->nombre_es . "\n";
    }

    return $lista;

}

function copia_pedido_dir_envio( $id_pedido, $id_envio ) {

    $sql = "INSERT INTO pedidos_dir_envio (id_cliente, id_pedido, nombre, apellidos, email, telefono, direccion, cp, localidad, provincia, pais, eori, observaciones, predeterminado, activo, fecha_creacion, fecha_actualizacion)
        SELECT id_cliente, ?, nombre, apellidos, email, telefono, direccion, cp, localidad, provincia, pais, eori, observaciones, predeterminado, activo, fecha_creacion, fecha_actualizacion
        FROM datos_envio
        WHERE id = ?";

    $arguments = [ $id_pedido, $id_envio ];

    $id_pedido_envio = $this->executeInsert( $sql, $arguments );

    $sql_actualiza_id_envio = "UPDATE pedidos SET id_envio = ? WHERE id= ?";
    $arguments = [ $id_pedido_envio, $id_pedido ];

    return $this->executeUpdate( $sql_actualiza_id_envio, $arguments );

}

function copia_pedido_dir_factura( $id_pedido, $id_facturacion ) {

    $sql = "INSERT INTO pedidos_dir_factura (id_cliente, id_pedido, nombre, apellidos, empresa, nif, email, telefono, direccion, cp, localidad, provincia, pais, predeterminado, tipo_factura, vies, activo, fecha_creacion, fecha_actualizacion)
        SELECT id_cliente, ?, nombre, apellidos, empresa, nif, email, telefono, direccion, cp, localidad, provincia, pais, predeterminado, tipo_factura, vies, activo, fecha_creacion, fecha_actualizacion
        FROM datos_facturacion
        WHERE id = ?";

    $arguments = [ $id_pedido, $id_facturacion ];

    $id_pedido_facturacion = $this->executeInsert( $sql, $arguments );

    $sql_actualiza_id_factura = "UPDATE pedidos SET id_facturacion = ? WHERE id= ?";
    $arguments = [ $id_pedido_facturacion, $id_pedido ];

    return $this->executeUpdate( $sql_actualiza_id_factura, $arguments );

}

function copia_pedidos_cupones_aplicados( $id_pedido, $cupon_id ) {

    $sql = "INSERT INTO pedidos_cupones_aplicados (
                                                    nombre_descuento,
                                                    temp_id,
                                                    id_pedido,
                                                    valor,
                                                    comentario,
                                                    tipo,
                                                    fecha_inicio,
                                                    fecha_fin,
                                                    uso_persona,
                                                    fecha_creacion,
                                                    aplicacion_descuento,
                                                    pais_aplicacion
                                                    )
        SELECT
            nombre_descuento,
            ?,
            ?,
            valor,
            comentario,
            tipo,
            fecha_inicio,
            fecha_fin,
            uso_persona,
            fecha_creacion,
            aplicacion_descuento,
            pais_aplicacion
        FROM cupones_descuento
        WHERE id = ?";

    $arguments = [ $cupon_id, $id_pedido, $cupon_id ];

    $id_pedido_cupon = $this->executeInsert( $sql, $arguments );

    $sql_actualiza_id_cupon = "UPDATE pedidos SET cupon_id = ? WHERE id= ?";
    $arguments = [ $id_pedido_cupon, $id_pedido ];

    return $this->executeUpdate( $sql_actualiza_id_cupon, $arguments );

}

function actualiza_cupon_detalle ( $id_pedido ) {

    $dat_detalle_pedido = $this->obten_detalles_pedido( $id_pedido );
    $cont = 0;

    // echo json_encode( $dat_detalle_pedido ); exit;

    foreach ( $dat_detalle_pedido as $linea ) {

        if ( $linea->cupon_id > 0 ) {

            // echo $linea->id . ' ---> ' . $linea->cupon_id;
            // echo "<br>";

            $sql = "SELECT id FROM pedidos_cupones_aplicados WHERE temp_id=? AND id_pedido=?";
            // echo "SELECT id FROM pedidos_cupones_aplicados WHERE temp_id=$linea->cupon_id AND id_pedido=$id_pedido";
            // echo "<br>";
            $arguments = [ $linea->cupon_id, $id_pedido ];
            $cupon_id = $this->executeQuery( $sql, $arguments )[0]->id;
            // echo "cupon_id --> " . $cupon_id;
            // echo "<br>";

            $sql_actualiza_id_cupon_detalle = "UPDATE detalles_pedido SET cupon_id = ? WHERE id= ?";
            // echo "UPDATE detalles_pedido SET cupon_id = $cupon_id WHERE id= $linea->id";
            // echo "<br><br><br>";
            $arguments = [ $cupon_id, $linea->id ];
            $this->executeUpdate( $sql_actualiza_id_cupon_detalle, $arguments );

            $cont++;

        }

    }

    return $cont;

}

function tipo_impuesto_books( $tipo_impuesto ) {

    $sql = "SELECT * FROM sc_tipo_impuesto_books WHERE tipo_impuesto = ?";
    $arguments = [$tipo_impuesto];

    return $this->executeQuery($sql, $arguments)[0];

}

function obten_iva_books( $cod_pais ) {
    $sql = "SELECT * FROM books_iva WHERE codigo = ?";
    $arguments = [$cod_pais];

    return $this->executeQuery($sql, $arguments)[0];
}

function es_factura_simplificada( $id_pedido ) {

    $dat_pedido = $this->obten_datos_pedido( $id_pedido );
    if ( ( $dat_pedido->tipo_impuesto == 1 || $dat_pedido->tipo_impuesto == 3 || $dat_pedido->tipo_impuesto == 4 || $dat_pedido->tipo_impuesto == 5 || $dat_pedido->tipo_impuesto == 6 )  &&  $dat_pedido->total_pagado < 3000 && ( $dat_pedido->tipo_factura == 1 || $dat_pedido->tipo_factura == 0 ) ) {
        return true;
    }
    return false;
}

public function obten_nombre_pais($cod_pais) {
    $sql = "SELECT nombre FROM paises WHERE cod_pais = ?";
    $arguments = [$cod_pais];

    $result = $this->executeQuery($sql, $arguments);
    if (!empty($result)) {
        return $result[0]->nombre;
    }
    return null;
}

function obten_nombre_provincia( $cod_prov ) {
    if (is_numeric($cod_prov)) {
        $sql = "SELECT nombre_prov FROM provincias WHERE id_prov = ?";
        $arguments = [$cod_prov];

        return $this->executeQuery($sql, $arguments)[0]->nombre_prov;
    } else {
        return $cod_prov;
    }
}

function guarda_books_id( $books_id, $email ) {
    $sql = "UPDATE users SET books_id = ? WHERE email = ?";
    $arguments = [$books_id, $email];

    return $this->executeUpdate($sql, $arguments);
}

function guarda_llamada_api ($tipo, $ref_pedido, $identificador, $contenido ) {
    $sql = "INSERT INTO llamadas_api (tipo, ref_pedido, identificador, contenido, fecha_creacion) VALUES (?, ?, ?, ?, ?)";
    $arguments = [$tipo, $ref_pedido, $identificador, $contenido, date('Y-m-d H:i:s')];

    return $this->executeInsert($sql, $arguments);
}

function obten_metodo_pago( $id ) {
    $sql = "SELECT * FROM metodos_pago WHERE id = ?";
    $arguments = [$id];

    return $this->executeQuery($sql, $arguments)[0];
}

function obten_books_id($id_prod) {
    $sql = "SELECT books_id FROM productos WHERE id = ?";
    $arguments = [$id_prod];

    return $this->executeQuery($sql, $arguments)[0]->books_id;
}

function obten_idioma( $id ) {
    $sql = "SELECT * FROM idiomas WHERE id = ?";
    $arguments = [$id];

    return $this->executeQuery($sql, $arguments)[0];
}

public function print_log($filename, $txt) {
    $txt = date('d/m/Y | H:i') . "\n\n" . $txt;
    $txt .= "\n\n=========================================================\n\n";
    // $file = dirname(__DIR__, 2) . '/admin/logs/' . $filename . '.txt';
    $file = $_SERVER['DOCUMENT_ROOT'] . '/smartcret_new/admin/logs/' . $filename . '.txt';

    $fp = fopen($file, "a");

    if ($fp) {
        fwrite($fp, $txt);
        fclose($fp);
    } else {
        error_log("No se pudo abrir el archivo para escribir: $file");
    }

    return "TODO OK";
}

function obten_dir_envio_user( $id ) {

    $sql="SELECT * FROM datos_envio WHERE id =?";
    $arguments = [$id];

    return $this->executeQuery($sql, $arguments)[0];
}

function obten_dir_fact_user( $id ) {

    $sql="SELECT * FROM datos_facturacion WHERE id =?";
    $arguments = [$id];

    return $this->executeQuery($sql, $arguments)[0];
}

public function consulta($sql, $args = []) {
    $pdo = getDB();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}


function crea_orden_venta( $id_pedido ) {

    $dat_pedido = $this->obten_datos_pedido( $id_pedido );
    $dat_detalle_pedido = $this->obten_detalles_pedido( $id_pedido );
    $dat_user = $this->obten_datos_user( $dat_pedido->id_cliente );
    $dat_dir_envio = $this->obten_ped_dir_envio ( $dat_pedido->id_envio );
    $dat_dir_factura = $this->obten_ped_dir_facturacion ( $dat_pedido->id_facturacion );
    $dat_metodo_pago = $this->obten_metodo_pago( $dat_pedido->metodo_pago );

    if ( $dat_pedido->cupon_id > 0 ) {

        $dat_cupon = $this->obten_ped_cupon( $dat_pedido->id_envio );

    }

    if ( $dat_pedido->tipo_impuesto == 5) {

        $iva = $this->obten_iva_books( $dat_dir_envio[0]->pais );

        $tax_id = $iva->books_id_iva;
        $txt_impuesto = $iva->nombre_iva;

    }else {

        $tax = $this->tipo_impuesto_books( $dat_pedido->tipo_impuesto );
        $tax_id = $tax->books_id;
        $txt_impuesto = $tax->txt;

    }

    $factura_simplificada = $this->es_factura_simplificada( $id_pedido );

    //si es factura simplificada cambiamos los datos del usuario VENTAS SMARTCRET SIMPLIFICADA
    if ( $factura_simplificada ) {

        $datos_simplificada = array(
            "billing_address" => array (
            "attention" =>  $dat_dir_factura[0]->nombre . ' ' . $dat_dir_factura[0]->apellidos,
            "address" => $dat_dir_factura[0]->direccion,
            "city" => $dat_dir_factura[0]->localidad,
            "state" => $this->obten_nombre_provincia( $dat_dir_factura[0]->provincia ),
            "zip" => $dat_dir_factura[0]->cp,
            "country" => $this->obten_nombre_pais( $dat_dir_factura[0]->pais ) . ' (' . $dat_dir_factura[0]->pais . ')',
            "phone" => $dat_dir_factura[0]->telefono
            ),

            "shipping_address" => array (
            "attention" => $dat_user[0]->nombre . ' ' . $dat_user[0]->apellidos,
            "address" => $dat_dir_envio[0]->direccion,
            "city" => $dat_dir_envio[0]->localidad,
            "state" => $this->obten_nombre_provincia( $dat_dir_envio[0]->provincia ),
            "zip" => $dat_dir_envio[0]->cp,
            "country" => $this->obten_nombre_pais( $dat_dir_envio[0]->pais ) . ' (' . $dat_dir_envio[0]->pais . ')',
            "phone" => $dat_dir_envio[0]->telefono
            )
        );

        $contact_factura_simple = "459933000158317400";
        $reg = updateContact( $contact_factura_simple, json_encode ( $datos_simplificada ) );

    }


    $valor_iva = 1 + ( $dat_pedido->iva_aplicado / 100 );

    if ( $dat_user[0]->books_id != null &&  $dat_user[0]->books_id != '' ) {

        $contact_id = $dat_user[0]->books_id;

    }else {

        $reg = obtenIdEmail ( trim (  $dat_user[0]->email ) );

        if ( $reg != '' || $reg != null ) {

            $contact_id = $reg;

        }else {

            $contactData = array(

                "contact_name" => $dat_user[0]->nombre . ' ' . $dat_user[0]->apellidos,
                "company_name" => $dat_user[0]->empresa,
                "billing_address" => array (
                                            "attention" =>  $dat_dir_factura[0]->nombre . ' ' . $res3->apellidos,
                                            "address" => $dat_dir_factura[0]->direccion,
                                            "city" => $dat_dir_factura[0]->localidad,
                                            "state" => $this->obten_nombre_provincia( $dat_dir_factura[0]->provincia ),
                                            "zip" => $dat_dir_factura[0]->cp,
                                            "country" => $this->obten_nombre_pais( $dat_dir_factura[0]->pais ) . ' (' . $dat_dir_factura[0]->pais . ')',
                                            "phone" => $dat_dir_factura[0]->telefono
                ),

                "shipping_address" => array (
                                            "attention" => $dat_user[0]->nombre . ' ' . $dat_user[0]->apellidos,
                                            "address" => $dat_dir_envio[0]->direccion,
                                            "city" => $dat_dir_envio[0]->localidad,
                                            "state" => $this->obten_nombre_provincia( $dat_dir_envio[0]->provincia ),
                                            "zip" => $dat_dir_envio[0]->cp,
                                            "country" => $this->obten_nombre_pais( $dat_dir_envio[0]->pais ) . ' (' . $dat_dir_envio[0]->pais . ')',
                                            "phone" => $dat_dir_envio[0]->telefono
                ),

                "custom_fields" => [
                    array (
                        "label" => "NIF/CIF",
                        "value" => $dat_user[0]->nif_cif
                    ),
                    array (
                        "label" => "Comisionista",
                        "value" => "smartcret"
                    ),
                    array (
                        "label" => "Empresa grupo",
                        "value" => "Smartcret"
                    ),
                    array (
                        "label" => "NIF CIF",
                        "value" => $dat_user[0]->nif_cif
                    ),
                    array (
                        "label" => "Tipo Impuesto",
                        "value" => $txt_impuesto
                    ),
                    array (
                        "label" => "Forma de pago",
                        "value" => "Tarjeta TPV"
                    )
                ],
            );

            $contactData = json_encode ( $contactData );
            $reg = createNewContact( $contactData ) ;
            var_dump($reg);
            $contact_id = $reg['contact']['contact_id'];

        }

        //guardamos el id de books asociado al cliente
        $this->guarda_books_id ( $contact_id, $dat_user[0]->email );

        //guardamos la llamada a la API en base de datos
        // $this->guarda_llamada_api ( 'cliente', $dat_pedido->ref_pedido, $contact_id, $contactData );

    }



    // echo "<br><br>";
    // var_dump($contact_id);
    // echo 'user id: ' . $contact_id . "<br><br>";
    // $log .= "\nIdentificador usuario: $contact_id\n";

    /* si se hace factura simplificada cambiamos id de cliente*/
    if ( $factura_simplificada ) {
        $contact_id = "459933000158317400";
    }

    if ( $dat_pedido->cupon_id > 0 ) {
        $notas_descuento = " | Código descuento: " . $dat_cupon->nombre_descuento . " (" . $dat_cupon->valor . $dat_cupon->tipo . " - " . $dat_cupon->comentario . ")";
    }

    $notes = "Forma de pago: " . $dat_metodo_pago->nombre . " | Idioma: " . $this->obten_idioma( $dat_pedido->idioma )->nombre_idioma . $notas_descuento;

    $salesOrderData = array(
        "customer_id" => $contact_id,
        "date" => date("Y-m-d"),
        "is_inclusive_tax" => false,
        "line_items" => array (),
        "notes" => $notes,
        // "discount_amount" => 0,
        // "discount" => $importe_descuento,
        "discount_applied_on_amount" => 0,
        "is_discount_before_tax" => true,
        "discount_type" => "item_level",
        // "discount_type" => "entity_level",
        // "shipping_charge" => $gastos_envio,
        "salesorder_number" => $dat_pedido->ref_pedido,
        "tax_percentage" => '21',
        "reference_number" => "Tienda Online Smartcret",
        "salesperson_id" => "459933000119333749",
        "template_id" => "459933000037068755",
        "custom_fields" =>[
                            array (
                                "customfield_id" => "459933000093914053",
                                "value" => true
                            ),
                            array (
                                "customfield_id" => "459933000048957187",
                                "value" => "Por determinar"
                            ),
                            array (
                                "customfield_id" => "459933000088385214",
                                "value" => "Venta"
                            ),
                            array (
                                "customfield_id" => "459933000074198397",
                                "value" => "No"
                            )
                        ]
    );


    /* añadimos lineas de pedido */

    $i = 1;

    foreach ($dat_detalle_pedido as $linea) {

        if ( $linea->cupon_id == 0 ) {

            $descuento_aplicado_articulo = 0;

        }else {

            $linea_cupon = $this->obten_ped_cupon_prod( $linea->cupon_id )[0];
            $descuento_aplicado_articulo = $linea_cupon->valor . $linea_cupon->tipo;

        }

        array_push (
        $salesOrderData['line_items'] ,
        array (
            "item_order" => $i,
            // "name" => $prod->nombre_es,
            "item_id" => $this->obten_books_id( $linea->id_prod ),
            "quantity" => $linea->cantidad,
            "discount" => $descuento_aplicado_articulo,
            "rate" => $linea->precio / $valor_iva,
            "tax_id" => $tax_id,
            "warehouse_id" => "459933000007839005",
            "warehouse_name" => "Grupo Negocios PO SLU",
            "unit" => "ud",
            )
        );

        $i++;

    }

    /* añadimos linea gastos de envio */

    $i++;
    array_push (
        $salesOrderData['line_items'] ,
        array (
            "item_order" => $i,
            "name" => "Portes",
            "item_id" => "459933000000278064",
            "quantity" => 1,
            "rate" => ( $dat_pedido->gastos_envio / $valor_iva ),
            "tax_id" => $tax_id,
            "warehouse_id" => "459933000007839005",
            "warehouse_name" => "Grupo Negocios PO SLU",
            "unit" => "ud",
        )
    );

    /* crear orden de venta */

    $data = json_encode( $salesOrderData );

    // echo $data;
    // echo "<br><br>";
    // exit;

    echo 'Orden de venta -><br>' . $data;
    echo "<br><br>";
    $reg_so = createSalesOrder( $data );

    echo "<br><br>";
    var_dump ( $reg_so );
    echo "<br><br>";

    $res_so = ( (array) ( $reg_so ) );
    $saleorder_id = $res_so['salesorder']->salesorder_id;

    // $this->guarda_llamada_api( 'orden_venta', $dat_pedido->ref_pedido, $saleorder_id, $data );

    /* actualizamos direccion de envio */
    echo "<br>DATOS ENVIO:<br>";
    var_dump($dat_dir_envio);
    echo "<br><br>";

    $shippingAddress = array(
        "address" => $dat_dir_envio[0]->direccion,
        "city" => $dat_dir_envio[0]->localidad,
        "state" => $this->obten_nombre_provincia( $dat_dir_envio[0]->provincia ),
        "zip" => $dat_dir_envio[0]->cp,
        "country" => $this->obten_nombre_pais( $dat_dir_envio[0]->pais ) . ' (' . $dat_dir_envio[0]->pais . ')',
        "phone" => $dat_dir_envio[0]->telefono,
        "attention" =>  $dat_user[0]->nombre . ' ' . $dat_user[0]->apellidos,
    );

    $shippingAddress = json_encode ( $shippingAddress );

    var_dump( $shippingAddress );
    echo "<br><br>";

    $reg = updateSaleOrderShippingAddress( $saleorder_id, $shippingAddress );

    // guardamos la llamada a la API en base de datos
    // $this->guarda_llamada_api( 'direccion_envio', $dat_pedido->ref_pedido, '', $shippingAddress );

    /* actualizamos direccion de facturación */

    echo "<br>DATOS FACTURA:<br>";
    var_dump($dat_dir_factura);
    echo "<br><br>";

    $billingAddress = array(
        "address" => $dat_dir_factura[0]->direccion,
        "city" => $dat_dir_factura[0]->localidad,
        "state" => $this->obten_nombre_provincia( $dat_dir_factura[0]->provincia ),
        "zip" => $dat_dir_factura[0]->cp,
        "country" => $this->obten_nombre_pais( $dat_dir_factura[0]->pais ) . ' (' . $dat_dir_factura[0]->pais . ')',
        "phone" =>  $dat_dir_factura[0]->telefono,
        "attention" => $dat_dir_factura[0]->nombre . ' ' . $dat_dir_factura[0]->apellidos
    );

    $billingAddress = json_encode ( $billingAddress );

    var_dump( $billingAddress );
    echo "<br><br>";
    $reg = updateSaleOrderBillingAddress( $saleorder_id, $billingAddress );

    // guardamos la llamada a la API en base de datos
    // $this->guarda_llamada_api ( 'direccion_facturacion', $ref_pedido, '', $billingAddress );

}


function obten_nombre_prod_idioma( $id_producto, $idioma ) {

    $sql="SELECT nombre FROM productos_nombres WHERE id_producto=? AND id_idioma=?";
    $arguments = [ $id_producto, $idioma ];

    return $this->executeQuery( $sql, $arguments )[0]->nombre;

}

function obten_iva_pais ( $cod_pais ) {

    $sql="SELECT iva FROM paises_ue WHERE codigo =?";
    $arguments = [ $cod_pais ];

    return $this->executeQuery( $sql, $arguments )[0]->iva;
}

// function existeEmail( $email ) {
//     global $mysqli;
//     $sql="SELECT * FROM users WHERE email = '".trim($email, ' ')."'";
//     // echo $sql;
//     $res=$mysqli->query($sql);
//     if($res->num_rows>0){
//         return false;
//     }else{
//         return true;
//     }
// }

function existeEmail($email) {

    $sql = "SELECT * FROM users WHERE email = ?";
    $arguments = [trim($email, ' ')];

    $res = $this->executeQuery($sql, $arguments);

    if (count($res) > 0) {
        // return false;
        return 'existe';
    } else {
        // return true;
        return'NO existe';
    }
}

function getCurrentUrl() {

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $url = $protocol . $host . $uri;

    return $url;
}

function actualiza_carrito_pais() {

    $carrito = new Carrito();
    $carro = $carrito->get_content();

    $productos_carrito = array();

    foreach($carro as $producto) {

        $id_idioma = 1;

        $id_producto = $producto['id'];
        $cantidad = $producto['cantidad'];

        $array_prod = array(
            'id'             => $id_producto,
            'cantidad' => $cantidad
        );

        array_push( $productos_carrito, $array_prod );

    }

    $carrito->destroy();

      // var_dump( $productos_carrito );
      // echo "<br><br><br>";

    foreach ( $productos_carrito as $prod ) {

        $sql = "SELECT p.id, p.books_id, pn.nombre, p.id_categoria, p.miniatura, pp.precio, p.sku, p.peso, pp.precio
        FROM productos p
        INNER JOIN productos_nombres pn ON p.id = pn.id_producto
        INNER JOIN productos_precios_new pp ON p.id = pp.id_producto
        WHERE p.id = ? AND pn.id_idioma= ? AND pp.cod_pais= ?";

        $arguments = [ $prod['id'], $id_idioma_global, $_SESSION['user_ubicacion'] ];
        $reg = $checkout->executeQuery($sql, $arguments)[0];


        $articulo = array(
            "id"        => $reg->id,
            "books_id"  => $reg->books_id,
            "nombre"    => $reg->nombre,
            "categoria" => $reg->id_categoria,
            "img"       => $reg->miniatura,
            "cantidad"  => $prod['cantidad'],
            "precio"    => cambia_coma($reg->precio),
            "sku"       => $reg->sku,
            "peso"      => $reg->peso,
        );

          // var_dump($articulo);
          // echo "<br><br><br>";
          // exit;

        $carrito->add($articulo);

    }

}

}

?>