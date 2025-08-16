<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
// error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

session_start();
if (isset($_SESSION['nivel_dir']) && $_SESSION['nivel_dir'] == 2) {
    $ruta_link1 = "../../";
    $ruta_link2 = "../";
} else {
    $ruta_link1 = "../";
    $ruta_link2 = "./";
}

$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma=$id_idioma_global;

include ('../../config/db_connect.php');
include ('../../class/userClass.php');
include ('./class.carrito.php');
include("./funciones.php");

$resp= array();
$carrito = new Carrito();
date_default_timezone_set("Europe/Madrid");

global $mysqli;
$mysqli=$conn;


if(isset($_REQUEST['accion']) && $_REQUEST['accion']!='') {
	$res=array();
	$post_keys = array_keys($_REQUEST);
	foreach( $post_keys as $key ) {
		$$key = $_REQUEST[$key];
		// echo $key . ' - ' . $$key . "<br>";
	}
}

include('../../includes/vocabulario.php');

if ( isset( $variante ) && $variante != 0) {
	$where = "WHERE p.es_variante='$variante'";
	if( isset( $color ) && $color != '' ) {
		$where .= " AND p.color='$color'";
	}
	if( isset ( $acabado ) && $acabado != '' ) {
		$where .= " AND p.acabado='$acabado'";
	}
	if( isset ( $juntas ) && $juntas != '' ) {
		$where .= " AND p.juntas='$juntas'";
	}
	if( isset ( $formato ) && $formato != '' ) {
		$where .= " AND p.formato='$formato'";
	}
    $where .= " AND pn.id_idioma=$id_idioma_global AND pp.cod_pais='" . $_SESSION['user_ubicacion'] . "'";
	// $where .= " AND pn.id_idioma=$id_idioma_global AND pp.id_idioma=$id_idioma_global";

	$sql = "SELECT p.*, pn.nombre, pp.precio FROM productos p INNER JOIN productos_nombres pn ON p.id = pn.id_producto INNER JOIN productos_precios_new pp ON p.id = pp.id_producto $where";

	$res=consulta($sql, $conn);
	$reg=$res->fetch_object();
}else if ( isset( $idProd ) && $idProd != 0 ) {
    $sql = "SELECT p.*, pi.nombre, pp.precio FROM productos p
    INNER JOIN productos_info pi ON p.id = pi.id_producto
    INNER JOIN productos_precios_new pp ON p.id = pp.id_producto WHERE p.id ='$idProd' AND pi.id_idioma=$id_idioma_global AND pp.cod_pais='" . $_SESSION['user_ubicacion'] . "'";
	// $sql = "SELECT p.*, pi.nombre, pp.precio FROM productos p
	// INNER JOIN productos_info pi ON p.id = pi.id_producto
	// INNER JOIN productos_precios pp ON p.id = pp.id_producto WHERE p.id ='$idProd' AND pi.id_idioma=$id_idioma_global AND pp.id_idioma=$id_idioma_global";

	$res=consulta($sql, $conn);
	$reg=$res->fetch_object();
}

// echo $sql;
// echo "<br>";
// var_dump( $reg );exit;


// $vocabulario_añadidos_a_pedido = "artículos añadidos a tu pedido";
// echo $se_ha_eliminado_articulo;
// $vocabulario_añadid = $vocabulario_añadidos_a_pedido;
// $vocabulario_añadidos_a = $vocabulario_añadid;


function procesarCase1($carrito, $reg, $cantidad, $vocabulario_añadido_a_pedido, $vocabulario_añadidos_a_pedido) {
    if (isset($reg) && isset($cantidad)) {
        $articulo = array(
            "id"        => $reg->id,
            "books_id"  => $reg->books_id,
            "nombre"    => $reg->nombre,
            "categoria" => $reg->id_categoria,
            "img"       => $reg->miniatura,
            "cantidad"  => $cantidad,
            "precio"    => cambia_coma($reg->precio),
            "sku"       => $reg->sku,
            "peso"      => $reg->peso,
        );

        // var_dump($articulo);
        // exit;

        $carrito->add($articulo);

        $resp['texto'] = ($cantidad > 1) ? $cantidad . ' x ' . $reg->nombre . ' ' . $vocabulario_añadidos_a_pedido : $cantidad . ' x ' . $reg->nombre . ' ' . $vocabulario_añadido_a_pedido;
        $resp['numProd'] = $carrito->articulos_total();
        $resp['importe'] = formatea_importe($carrito->precio_total());

        echo json_encode($resp);
    } else {
        echo json_encode(['error' => 'Variables $reg o $cantidad no están definidos.']);
    }
}

if ($_REQUEST['accion'] == '0') {
    $carrito->destroy();
} elseif ($_REQUEST['accion'] == '1') {
    procesarCase1($carrito, $reg, $cantidad, $vocabulario_añadido_a_pedido, $vocabulario_añadidos_a_pedido);
} elseif ($_REQUEST['accion'] == '2') {
    if ($carrito->articulos_total() == 0) {
        echo $vocabulario_el_carrito_esta_vacio;
    } else {
        $carro = $carrito->get_content();
        foreach ($carro as $producto) {
            echo "id - " . $producto["id"];
            echo "<br />";
            echo "sku - " . $producto["sku"];
            echo "<br />";
            echo "unico id - " . $producto["unique_id"];
            echo "<br />";
            echo "nombre - " . $producto["nombre"];
            echo "<br />";
            echo "cantidad uds - " . $producto["cantidad"];
            echo "<br />";
            echo "precio ud - " . $producto["precio"] . EURO;
            echo "<br />";
            echo "------------------------------------------";
            echo "<br /><br />";
        }
    }
} elseif ($_REQUEST['accion'] == '3') {
    echo $carrito->precio_total() . ' ' . EURO;
} elseif ($_REQUEST['accion'] == '4') {
    echo $carrito->articulos_total();
} elseif ($_REQUEST['accion'] == '5') {
    $carrito->remove_producto($uid);
    $resp['texto'] = $vocabulario_se_ha_eliminado_articulo;
    $resp['numProd'] = $carrito->articulos_total();
    $resp['importe'] = formatea_importe($carrito->precio_total());


    echo json_encode($resp);
} elseif ($_REQUEST['accion'] == '6') {
    if($carrito->articulos_total() > 0) {
        $carrito->destroy();
        $resp['texto']=$vocabulario_su_pedido_ha_sido_eliminado;
        $resp['numProd']=$carrito->articulos_total();

        $_SESSION['codigo_descuento']=array();
        unset ( $_SESSION['codigo_descuento'] );

        echo json_encode($resp);
    }

} elseif ($_REQUEST['accion'] == '8') {
	$carrito->actualiza_cantidad($uid, $cantidad);

}

?>