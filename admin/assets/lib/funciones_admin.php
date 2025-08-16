<?php

// Impotar algunas clases de phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Cargar phpmailer via composer
// require  dirname ( __DIR__ ) . '/vendor/autoload.php';


if (session_status() === PHP_SESSION_NONE){@session_start();}

global $mysqli;
$mysqli=$conn;

function cambia_fecha_guion($fecha){
	date_default_timezone_set('Europe/Madrid');
    $date = date_create($fecha);
    return date_format($date, 'd/m/Y H:i A');
}

function cambia_fecha_slash($fecha){
	date_default_timezone_set('Europe/Madrid');
    $date = date_create($fecha);
    return date_format($date, 'd/m/Y');
}

function cambia_fecha_tabla($fecha){
	date_default_timezone_set('Europe/Madrid');
    $date = date_create($fecha);
    return date_format($date, 'Ymd');
}

function obten_clientes() {
	global $mysqli;
	$sql="SELECT * FROM users ORDER BY uid ASC";
	return $mysqli->query($sql);
}

function obten_configuracion( $id ) {
	global $mysqli;
	$sql="SELECT * FROM configuracion WHERE id = $id";
	return $mysqli->query($sql)->fetch_object();
}

function obten_num_pedidos_cliente ( $id_cliente ) {
	global $mysqli;
	$sql = "SELECT COUNT(id_cliente) as num_pedidos FROM pedidos WHERE id_cliente='" . $id_cliente . "'";
	return $mysqli->query($sql)->fetch_object()->num_pedidos;
}

function obten_compras_totales_cliente ( $id_cliente ) {
	global $mysqli;
	$sql = "SELECT SUM(total_pagado) as total_pagado FROM pedidos WHERE id_cliente='" . $id_cliente . "'";
	return $mysqli->query($sql)->fetch_object()->total_pagado;
}

function obten_nombre_categoria ( $id_categoria, $lang='es') {
	global $mysqli;
	$sql = "SELECT categoria FROM categorias WHERE id='" . $id_categoria . "'";
	return $mysqli->query($sql)->fetch_object()->categoria;
}
function obten_aviso_web ( $id_aviso) {
	global $mysqli;
	$mysqli->set_charset("utf8mb4");
	$sql = "SELECT * FROM avisos_web WHERE id='" . $id_aviso . "'";
	return $mysqli->query($sql)->fetch_object();
}

// function obten_texto_aplicacion_descuento ( $id ) {
// 	global $mysqli;
// 	$sql = "SELECT aplicacion_texto FROM `aplicacion_descuento` WHERE id='" . $id . "'";
// 	return $mysqli->query($sql)->fetch_object()->aplicacion_texto;
// }

function obten_num_descuento_utilizado ( $id ) {
	global $mysqli;
	if ( esta_activo( 'mostrar_datos_prueba' ) ) {
		$sql = "SELECT COUNT(p.id) as num_usos FROM pedidos p INNER JOIN pedidos_cupones_aplicados pca ON pca.id = p.cupon_id WHERE pca.temp_id = $id";
	}else {
		$sql = "SELECT COUNT(p.id) as num_usos FROM pedidos p INNER JOIN pedidos_cupones_aplicados pca ON pca.id = p.cupon_id WHERE pca.temp_id = $id AND p.id_cliente NOT IN (SELECT id_user FROM user_test)";
	}
	return $mysqli->query($sql)->fetch_object()->num_usos;
}

function obten_pedidos_descuento ( $id ) {
	global $mysqli;
	$sql = "SELECT p.* FROM pedidos p INNER JOIN pedidos_cupones_aplicados pca ON pca.id = p.cupon_id WHERE pca.temp_id = $id AND p.id_cliente NOT IN (SELECT id_user FROM user_test)";
	return $mysqli->query($sql);
}

function pedidos_usuario( $id_cliente ) {
	global $mysqli;
	$sql = "SELECT * FROM pedidos WHERE id_cliente = $id_cliente";
	return $mysqli->query($sql);
}

function elimina_pedido ( $id_pedido) {
	global $mysqli;
	$sql = "DELETE FROM pedidos WHERE id = $id_pedido";
	if ( $mysqli->query( $sql ) ) {
		$sql2 = "DELETE FROM detalles_pedidos WHERE id_pedido=$id_pedido";
		if ( $mysqli->query( $sql2 ) ) {
			return 1;
		}else{
			return 0;
		}
	}
	return 0;
}

function esta_activo ( $nombre_config ) {
	global $mysqli;
	$sql = "SELECT activado FROM configuracion WHERE nombre_config = '$nombre_config'";
	if ( $mysqli->query($sql)->fetch_object()->activado  == 1) return true;
	else return false;
}

function usuario_tiene_pedidos ( $id ) {
	global $mysqli;
	$dias_atras = 40;
	$sql = 'SELECT count(id) as pedidos FROM pedidos WHERE fecha_creacion BETWEEN CURDATE() - INTERVAL ' . $dias_atras . ' DAY AND CURDATE() and id_cliente = ' . $id . ' AND cancelado=0';

	return $mysqli->query($sql)->fetch_object()->pedidos;
}

function obten_codigo_activo($fecha) {
    global $mysqli;

    $sql = 'SELECT pca.nombre_descuento
            FROM pedidos p
            INNER JOIN pedidos_cupones_aplicados pca ON p.cupon_id = pca.id
            WHERE DATE_FORMAT(p.fecha_creacion, "%Y-%m-%d") = ?';

    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        error_log('Error al preparar la sentencia SQL: ' . $mysqli->error);
        return '';
    }

    $stmt->bind_param('s', $fecha);
    $stmt->execute();
    $result = $stmt->get_result();
    $cupon = '';

    while ($reg = $result->fetch_object()) {
        if (strlen($reg->nombre_descuento) > 5) {
            $cupon = $reg->nombre_descuento;
            break;
        }
    }

    $stmt->close();

    return $cupon;
}


function obten_num_kits_dia ( $fecha ) {

	global $mysqli;
	// $sql = 'SELECT SUM(cantidad) AS num_kits FROM detalles_pedido WHERE DATE_FORMAT(fecha_creacion, "%Y-%m-%d") = "' . $fecha . '" AND id_prod IN ( SELECT id FROM productos WHERE es_variante = 1 OR es_variante = 2)';
	$sql = 'SELECT SUM(cantidad) AS num_kits FROM detalles_pedido WHERE DATE_FORMAT(fecha_creacion, "%Y-%m-%d") = "' . $fecha . '" AND id_prod IN ( SELECT id FROM productos WHERE es_variante IN ( 1,2,812,813,815,816,981,982,1148,1149,1150,1151 ))';

	return $mysqli->query($sql)->fetch_object()->num_kits;

}

function obten_datos_cancelacion( $ref_pedido ) {
	global $mysqli;
	$sql = "SELECT * FROM sc_cancelacion_pedidos WHERE ref_pedido = '$ref_pedido'";
	return $mysqli->query($sql)->fetch_object();
}

function pedido_con_descuento ( $id_pedido ) {
	global $mysqli;

	$sql = "SELECT * FROM detalles_pedido WHERE id_pedido = '$id_pedido'";
	$res=$mysqli->query($sql);
	$respuesta = false;

	while( $reg=$res->fetch_object() ) {
		if ( $reg->descuento != 0 ) $respuesta = true;
	}

	return $respuesta;

}

function enviarCorreo( $destinatarios, $asunto, $cuerpo ) {

    if (!is_array($destinatarios) || count($destinatarios) == 0) {
        throw new Exception('La lista de destinatarios no puede estar vacía y debe ser un array.');
    }

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'dedi3172657.eu.tuservidoronline.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@smartcret.com';
        $mail->Password = 'GAtf9s9Mem';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 25;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom('info@smartcret.com', 'Smartcret');

        // Añadir el primer destinatario como destinatario principal
        $mail->addAddress($destinatarios[0]);

        // Añadir el resto de los destinatarios como BCC
        for ($i = 1; $i < count($destinatarios); $i++) {
            $mail->addBCC($destinatarios[$i]);
        }

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;

        // Enviar correo
        $mail->send();
        echo 'El correo ha sido enviado correctamente';
    } catch (Exception $e) {
        echo "El correo no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
    }
}


// function guarda_datos_usuario_web () {

// 	global $lg;
// 	global $mysqli;

// 	$user_id = ( isset( $_SESSION['smart_user']['id'] ) ) ? $_SESSION['smart_user']['id']: '';
// 	$user_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

// 	$valores = " user_ip='" . $_SERVER['REMOTE_ADDR'] . "'";
// 	$valores .= ", user_id='" . $user_id . "'";
// 	$valores .= ", user_idioma='" . $lg . "'";
// 	$valores .= ", user_url='" . $user_url . "'";
// 	//$valores .= ", user_url_previa='" . $_SERVER['HTTP_REFERER'] . "'";
// 	$valores .= ", user_datetime='" .  date( 'Y-m-d H:i:s' ) . "'";

// 	$user_sql = "INSERT INTO sc_analitica_usuarios SET $valores";
//   consulta($user_sql, $mysqli);

// }

function obten_idioma_actual () {
	$url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	$idiomas = array ('/en', '/fr', '/it', '/de', '/en-us');
	$lg = 'es';
	foreach ( $idiomas as $idioma ) {
		if ( strpos ( $url, $idioma ) !== false) $lg = str_replace ( '/', '', $idioma );
	}
	return $lg;
}

function consulta($sql) {
	global $mysqli;
	return $mysqli->query($sql);
}
function res_consulta($sql) {
	global $mysqli;
	$res = $mysqli->query($sql);
	return $res->fetch_object();
}
function numFilas($result) {
	global $mysqli;
	return $result->num_rows;
}

function cambia_fecha_normal($fecha){
	date_default_timezone_set('Europe/Madrid');
    $date = date_create($fecha);
    return date_format($date, 'd/m/Y');
}
function obten_id() {
	global $mysqli;
	return $mysqli->insert_id;
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

function limpiaCadena($cadena){
	//$cadena = utf8_decode($cadena);

	$cadena = str_replace(("á"),("a"),$cadena);
	$cadena = str_replace(("é"),("e"),$cadena);
	$cadena = str_replace(("í"),("i"),$cadena);
	$cadena = str_replace(("ó"),("o"),$cadena);
	$cadena = str_replace(("ú"),("u"),$cadena);
	$cadena = str_replace(("ü"),("u"),$cadena);
	$cadena = str_replace(("ñ"),("n"),$cadena);

	$cadena = str_replace(("Á"),("A"),$cadena);
	$cadena = str_replace(("É"),("E"),$cadena);
	$cadena = str_replace(("Í"),("I"),$cadena);
	$cadena = str_replace(("Ó"),("O"),$cadena);
	$cadena = str_replace(("Ú"),("U"),$cadena);
	$cadena = str_replace(("Ü"),("U"),$cadena);
	$cadena = str_replace(("Ñ"),("N"),$cadena);

	$cadena = str_replace((" "),("-"),$cadena);
	$cadena = str_replace(("#"),(""),$cadena);
	$cadena = str_replace(("?"),(""),$cadena);
	$cadena = str_replace(("\""),(""),$cadena);
	$cadena = str_replace(("'"),(""),$cadena);
	$cadena = str_replace(("/"),(""),$cadena);
	$cadena = str_replace(("@"),(""),$cadena);
	$cadena = str_replace(("!"),(""),$cadena);
	$cadena = str_replace(("~"),(""),$cadena);
	$cadena = str_replace((";"),(""),$cadena);

	return ($cadena);
}
function mayusculea($cadena){
	$cadena = str_replace(("á"),("a"),$cadena);
	$cadena = str_replace(("é"),("e"),$cadena);
	$cadena = str_replace(("í"),("i"),$cadena);
	$cadena = str_replace(("ó"),("o"),$cadena);
	$cadena = str_replace(("ú"),("u"),$cadena);

	$cadena = str_replace(("Á"),("A"),$cadena);
	$cadena = str_replace(("É"),("E"),$cadena);
	$cadena = str_replace(("Í"),("I"),$cadena);
	$cadena = str_replace(("Ó"),("O"),$cadena);
	$cadena = str_replace(("Ú"),("U"),$cadena);

	$cadena = str_replace(("ü"),("Ü"),$cadena);
	$cadena = str_replace(("ñ"),("Ñ"),$cadena);

	$cadena = str_replace(utf8_encode("á"),"A",$cadena);
	$cadena = str_replace(utf8_encode("é"),"E",$cadena);
	$cadena = str_replace(utf8_encode("í"),"I",$cadena);
	$cadena = str_replace(utf8_encode("ó"),"O",$cadena);
	$cadena = str_replace(utf8_encode("ú"),"U",$cadena);
	$cadena = str_replace(utf8_encode("Á"),"A",$cadena);
	$cadena = str_replace(utf8_encode("É"),"E",$cadena);
	$cadena = str_replace(utf8_encode("Í"),"I",$cadena);
	$cadena = str_replace(utf8_encode("Ó"),"O",$cadena);
	$cadena = str_replace(utf8_encode("Ú"),"U",$cadena);
	$cadena = str_replace(utf8_encode("ü"),utf8_encode("Ü"),$cadena);
	$cadena = str_replace(utf8_encode("ñ"),utf8_encode("Ñ"),$cadena);
	$cadena = str_replace(utf8_encode("Ú"),utf8_encode("Ü"),$cadena);
	$cadena = str_replace(utf8_encode("Ñ"),utf8_encode("Ñ"),$cadena);

	return ($cadena);
}

function cambia_coma($cadena) {
	$cadena = str_replace(",",".",$cadena);
	return $cadena;
}

function normaliza ($cadena){
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    $cadena = strtolower($cadena);
    return utf8_encode($cadena);
}

// function consulta_colores($id){
// 	global $mysqli;
// 	$sql="SELECT distinct A.valor, A.id as color_id, A.agotado, P.sku FROM productos P, atributos A where P.es_variante='$id' and A.id = P.color and activo=1";

// 	return $mysqli->query($sql);
// }
function consulta_colores($es_variante){
    global $mysqli;
    $sql = "
        SELECT
            P.id AS producto_id,
            P.sku,
            P.es_variante,
            P.color AS producto_color,
            P.publicado,
            A.id_atributo AS color_id,
            A.id_idioma,
            A.valor,
            A.activo,
            A.agotado
        FROM
            productos P
        JOIN
            atributos A
        ON
            P.color = A.id_atributo
        WHERE
            P.es_variante = '$es_variante'
		AND A.activo = 1;
    ";

    return $mysqli->query($sql);
}



function consulta_acabados($id){
	global $mysqli;
	$sql="SELECT distinct A.valor, A.id as acabado_id FROM productos P, atributos A where P.es_variante='$id' and A.id = P.acabado";
	return $mysqli->query($sql);
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

function guarda_url() {
	$_SESSION['smart_user']['last_url'] = $_SERVER['REQUEST_URI'];
}

function obten_dir_envio($id) {
	global $mysqli;
	$sql="SELECT * FROM pedidos_dir_envio WHERE id ='$id'";
	return $mysqli->query($sql)->fetch_object();
}



function obten_dir_envio_array($id) {
	global $mysqli;
	$sql="SELECT * FROM pedidos_dir_envio WHERE id ='".$id."'";
	return $mysqli->query($sql)->fetch_array();
}

function obten_dir_facturacion($id) {
	global $mysqli;
	$sql="SELECT * FROM datos_facturacion WHERE id ='".$id."'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_metodos_pago() {
	global $mysqli;
	$sql="SELECT * FROM metodos_pago";
	return $mysqli->query($sql);
}

function obten_metodo_pago($id) {
	global $mysqli;
	$sql="SELECT * FROM metodos_pago WHERE id ='".$id."'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_datos_producto($id_producto) {
	global $mysqli;
	$sql="SELECT * FROM productos WHERE id ='" . $id_producto . "'";
	return $mysqli->query($sql)->fetch_object();
}

function calcula_peso_pedido() {
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

function obten_id_pedido_temp ( $redsys_num_order ) {
	global $mysqli;
	$sql = "SELECT id FROM pedidos_temp WHERE redsys_num_order = '$redsys_num_order'";
	$res = $mysqli->query($sql);
	if( mysqli_num_rows ( $res ) > 0) {
		return $mysqli->query($sql)->fetch_object()->id;
	}
}

function obten_id_user_pedido_temp ( $redsys_num_order ) {
	global $mysqli;
	$sql = "SELECT id_cliente FROM pedidos_temp WHERE redsys_num_order = '$redsys_num_order'";
	$res = $mysqli->query($sql);
	if( mysqli_num_rows ( $res ) > 0) {
		return $mysqli->query($sql)->fetch_object()->id_cliente;
	}
}

function obten_dir_envio_predeterminado ($id_cliente) {
	global $mysqli;
	$sql = "SELECT * FROM pedidos_dir_envio WHERE id_cliente = $id_cliente AND predeterminado = 1 LIMIT 1";
	$res = $mysqli->query($sql);
	if( mysqli_num_rows ( $res ) > 0) {
		return $mysqli->query($sql)->fetch_object()->id;
	}
}

function obten_dir_facturacion_predeterminado ($id_cliente) {
	global $mysqli;
	$sql = "SELECT * FROM datos_facturacion WHERE id_cliente = $id_cliente AND predeterminado = 1 LIMIT 1";
	$res = $mysqli->query($sql);
	if( mysqli_num_rows ( $res ) > 0) {
		return $mysqli->query($sql)->fetch_object()->id;
	}
}

function obten_idioma_predeterminado ($uid) {
	global $mysqli;
	$sql = "SELECT  I.* FROM users U, idioma I WHERE uid = $uid AND U.idioma = I.id";
	return $mysqli->query($sql)->fetch_object()->idioma;
}

function actualiza_idioma_predeterminado ($uid, $idioma) {
	global $mysqli;
	$sql = "UPDATE users SET idioma=$idioma WHERE uid = $uid";
	return $mysqli->query($sql);
}

function existeEmail( $email ) { //SE USA
	global $mysqli;
	$sql="SELECT * FROM users WHERE email = '".trim($email, ' ')."'";
	// echo $sql;
	$res=$mysqli->query($sql);
	if($res->num_rows>0){
		return false;
	}else{
		return true;
	}
}

function obten_precio_sku_es ( $sku ) {
	global $mysqli;
	$sql = "SELECT precio_es FROM productos WHERE sku='" . $sku . "'";
	return number_format( $mysqli->query( $sql )->fetch_object()->precio_es, 2, ".", "," );
}

function obten_descuento( $id ) {
	global $mysqli;
	$sql="SELECT * FROM cupones_descuento WHERE id ='".$id."'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_datos_user( $id ) {
	global $mysqli;
	$sql="SELECT * FROM users WHERE uid ='".$id."'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_datos_pedido( $id_pedido ) {
	global $mysqli;
	$sql="SELECT * FROM pedidos WHERE id ='" . $id_pedido . "'";
	return $mysqli->query($sql);
}

function obten_datos_pedido_temp( $redsys_num_order ) {
	global $mysqli;
	$sql="SELECT * FROM pedidos_temp WHERE redsys_num_order ='" . $redsys_num_order . "'";
	// echo $sql;
	return $mysqli->query($sql)->fetch_object();
}

function obten_metodo_pago_ped ( $id_ped ) {
	global $mysqli;
	$sql = "SELECT metodo_pago FROM pedidos WHERE id='" . $id_ped . "'";
	return $mysqli->query($sql)->fetch_object()->metodo_pago;
}

function obten_dir_envio_user( $id ) {
	global $mysqli;
	$sql="SELECT * FROM pedidos_dir_envio WHERE id ='".$id."'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_dir_fact_user( $id ) {
	global $mysqli;
	$sql="SELECT * FROM pedidos_dir_factura WHERE id ='".$id."'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_provincias( $id_pais ) {
	global $mysqli;
	$sql="SELECT * FROM provincias WHERE pais ='".$id_pais."'";
	return $mysqli->query($sql);
}

function obten_nombre_pais ( $cod_pais) {
	global $mysqli;
	$sql = "SELECT nombre FROM paises WHERE cod_pais='" . $cod_pais . "'";
	return $mysqli->query($sql)->fetch_object()->nombre;
}

function obten_nombre_provincia ( $cod_prov) {
	global $mysqli;
	if ( is_numeric ( $cod_prov ) ) {
		$sql = "SELECT nombre_prov FROM provincias WHERE id_prov='" . $cod_prov . "'";
		return $mysqli->query($sql)->fetch_object()->nombre_prov;
	}else {
		return $cod_prov;
	}
}

function obten_id_ultimo_pedido () {
	global $mysqli;
	$sql = "SELECT * FROM pedidos ORDER BY id DESC LIMIT 1";
	return @$mysqli->query($sql)->fetch_object()->id;
}
function obten_ref_pedido () {
	return  'SC-' . str_pad( obten_id_ultimo_pedido() + 1, 5, "0", STR_PAD_LEFT);
}
function obten_ref_pedido_guardado () {
	return  'SC-' . str_pad( obten_id_ultimo_pedido(), 5, "0", STR_PAD_LEFT);
}

function obten_ref_ultimo_pedido () {
	global $mysqli;
	// $sql = "SELECT * FROM pedidos ORDER BY id DESC LIMIT 1";
	$sql = "SELECT * FROM pedidos ORDER BY ref_pedido DESC LIMIT 1";
	return @$mysqli->query($sql)->fetch_object()->ref_pedido;
}

function obten_ref_nuevo_pedido () {
	$ref_ultimo = obten_ref_ultimo_pedido();
	$ref_num = str_replace ( 'SC-' , '' , $ref_ultimo );
	return  'SC-' . str_pad( $ref_num + 1, 5, "0", STR_PAD_LEFT);
}



function copia_pedido_temporal( $id_ped_temp ) {

	global $mysqli;
	$sql = "INSERT INTO pedidos ( ref_pedido, id_cliente, tipo_impuesto, iva_aplicado, id_envio, id_facturacion, tiene_vies, total_sinenvio, gastos_envio, descuento_aplicado, descuento_id, importe_descuento, descuento_iva, metodo_pago, estado_pago, fecha_pago, total_pagado, estado_envio, fecha_envio, transportista, fecha_entrega, idioma ) SELECT ref_pedido, id_cliente, tipo_impuesto, iva_aplicado, id_envio, id_facturacion, tiene_vies, total_sinenvio, gastos_envio, descuento_aplicado, descuento_id, importe_descuento, descuento_iva, metodo_pago, estado_pago, fecha_pago, total_pagado, estado_envio, fecha_envio, transportista, fecha_entrega, idioma FROM pedidos_temp WHERE id=$id_ped_temp";

	$mysqli->query($sql);

	$id_pedido = mysqli_insert_id( $mysqli );

	$sql="SELECT * FROM detalles_pedido_temp WHERE id_pedido = $id_ped_temp";
	// echo $sql . "\n\n";

	$res=consulta($sql, $mysqli);
	while($reg=$res->fetch_object()) {

		$valores="";
		$valores.="id_pedido='" . $id_pedido . "'";
		$valores.=", id_prod='" . $reg->id_prod ."'";
		$valores.=", sku='" . $reg->sku ."'";
		$valores.=", cantidad='" . $reg->cantidad ."'";
		$valores.=", precio='" . $reg->precio ."'";
		$valores.=", fecha_creacion='" . date('Y-m-d H:i:s') ."'";

		$sql2="INSERT INTO detalles_pedido SET $valores";
		// echo $sql2 . "\n\n";
		consulta($sql2, $mysqli);

	}
	//Actualizamos metodo de pago
	switch ( obten_metodo_pago_ped ( $id_pedido ) ) {
			case '1':

				break;
			case '2':

				$sql3 = "UPDATE pedidos SET estado_pago='Pagado', fecha_pago='" . date('Y-m-d H:i:s') ."' WHERE id=$id_pedido";
		    consulta($sql3, $mysqli);

				break;

			default:

				break;
		}
		// $sql3 = "UPDATE pedidos SET $valores WHERE id=$id_pedido";
		// consulta($sql2, $mysqli);
}

function lista_pedido_ref ( $ref_pedido ) {

	global $mysqli;
	$sql = "SELECT * FROM pedidos WHERE ref_pedido = '$ref_pedido'";
	$id_pedido = $mysqli->query($sql)->fetch_object()->id;
	$res=obten_detalles_pedido( $id_pedido );
	$lista="";
	$lista .= "<div style='padding-left:5%;font-weight:700'>";
	while($reg=$res->fetch_object()) {
		$prod = obten_datos_producto( $reg->id_prod );
		$lista .='<div class="item">' . $reg->cantidad . ' x ' . $prod->nombre_es . '<br></div>';
	}
	$lista .= "</div>";
	return $lista;
}
function detalle_pedido_ref ( $ref_pedido ) {

	global $mysqli;
	$sql = "SELECT * FROM pedidos WHERE ref_pedido = '$ref_pedido'";
	$id_pedido = $mysqli->query($sql)->fetch_object()->id;
	$res=obten_detalles_pedido( $id_pedido );
	$lista="";
	while($reg=$res->fetch_object()) {
		$prod = obten_datos_producto( $reg->id_prod );
		$lista .= $reg->cantidad . " x " . $prod->nombre_es . "\n";
	}
	return $lista;
}
function obten_detalles_pedido( $id_pedido ) {
	global $mysqli;
	$sql="SELECT * FROM detalles_pedido WHERE id_pedido ='" . $id_pedido . "'";
	return $mysqli->query($sql);
}


function comprueba_dir_pred_activa () {
	global $mysqli;

	$valores="";
	$valores.="id_cliente = '" . $_SESSION['smart_user']['id'] . "'";
	$valores.=" AND activo = 1";
	$valores.=" AND predeterminado = 1";

	$sql="SELECT * FROM pedidos_dir_envio WHERE $valores";
	// echo $sql;exit;
	$res=$mysqli->query($sql);
	if($res->num_rows>0){
		return true;
	}else{
		return false;
	}

}

function obten_dir_envio_pedido( $id_pedido ) {
	global $mysqli;
	$sql="SELECT id_envio FROM pedidos WHERE id ='". $id_pedido ."'";
	return $mysqli->query($sql)->fetch_object()->id_envio;
}

function obten_dir_fact_pedido( $id_pedido ) {
	global $mysqli;
	$sql="SELECT id_facturacion FROM pedidos WHERE id ='". $id_pedido ."'";
	return $mysqli->query($sql)->fetch_object()->id_facturacion;
}

function permite_uso_descuento_usuario ( $id_cliente, $descuento_id ) {
	global $mysqli;
	$sql="SELECT COUNT( descuento_id ) as num_usos FROM pedidos WHERE id_cliente= $id_cliente AND descuento_id = $descuento_id";
	$uso_descuento_usuario = $mysqli->query($sql)->fetch_object()->num_usos;
	$uso_descuento_permitido = obten_descuento ( $descuento_id )->uso_persona;
	if ( !$uso_descuento_usuario ) $uso_descuento_usuario = 0;
	return ( $uso_descuento_usuario < $uso_descuento_permitido ) ? true : false;
}

function obten_estrellas_valoracion ( $val ) {
	$class_rate = str_replace ( '.', '-', redondeo_valoracion ( $val ) );
	$class = 'star-' . $class_rate;

	echo '<i class="star_rate ' . $class . '"></i>';

}

function include_valoraciones ( $sku=0, $preguntas=0 ) {

	if ( valoraciones_activo () ) {
		$rate =obten_valoracion ( $sku );
		if ($rate) {
			$num = obten_num_valoraciones ( $sku );
			$class_rate = str_replace ( '.', '-', redondeo_valoracion ( $rate ) );
			$class = 'star-' . $class_rate;
			$num_valoraciones = ( $num == 1 ) ? $num . ' valoracion' : $num . ' valoraciones';
			if ( $preguntas == 1 && obten_listado_preguntas ( $sku )->num_rows > 0 ) {
				echo '<div class="valoracion"  ><span class="click_valora" sku="' . $sku . '"><i class="star_rate ' . $class . '" title="Valoración: ' . $rate . '" ></i><span class="user_review_text"><a href="javascript:void()" title="' . $num_valoraciones . '" >(' . $num_valoraciones . ')</a></span></span>' . include_preguntas( $sku ) . '</div>';
			}else {
				echo '<div class="valoracion" ><span class="click_valora" sku="' . $sku . '"><i class="star_rate ' . $class . '" title="Valoración: ' . $rate . '"></i><span class="user_review_text"><a href="javascript:void()"  title="' . $num_valoraciones . '" >(' . $num_valoraciones . ')</a></span></span></div>';
			}
		}
	}
}

function include_preguntas ( $sku=0 ) {
	return '<span class="ico_preguntas" sku="' . $sku . '" title="Preguntas y respuestas de clientes" ><img class="muestra_preguntas" src="assets/img/preguntas.png"  /> Preguntas</span>';
}

function redondeo_valoracion ( $n ) {
    $ent = floor($n); // Parte entera
    $dec = $n - $ent; // Parte decimal
    $dec = ( $dec >= 0.5 ) ? 0.5: 0;
    return $ent + $dec;
}

function obten_valoracion ( $sku ) {
	global $mysqli;
	$sql="SELECT ROUND ( AVG( valoracion_puntos ), 2 ) as rate FROM valoraciones WHERE sku='$sku'";
	return $mysqli->query($sql)->fetch_object()->rate;
}

function obten_num_valoraciones ( $sku ) {
	global $mysqli;
	$sql="SELECT id FROM valoraciones WHERE sku='$sku'";
	$res = $mysqli->query($sql);
	return mysqli_num_rows ( $res );
}

function obten_listado_valoraciones ( $sku ) {
	global $mysqli;
	$sql="SELECT * FROM valoraciones WHERE sku = '" . $sku . "'";
	return $mysqli->query($sql);
}

function obten_listado_preguntas ( $sku ) {
	global $mysqli;
	$sql="SELECT * FROM preguntas WHERE sku = '" . $sku . "'";
	// echo $sql;
	// return $mysqli->query($sql);
	return $mysqli->query($sql);
}

function obten_listado_respuestas ( $id_pregunta ) {
	global $mysqli;
	$sql="SELECT * FROM respuestas WHERE id_pregunta = '" . $id_pregunta . "'";
	// return $mysqli->query($sql);
	return $mysqli->query($sql);
}

function obten_datos_pedido_ref( $ref_pedido ) {
	global $mysqli;
	$sql="SELECT * FROM pedidos WHERE ref_pedido ='" . $ref_pedido . "'";
	return $mysqli->query($sql)->fetch_object();
}

function actualiza_envio_pedido ( $id_pedido, $estado_envio) {
	global $mysqli;
	$sql = "UPDATE pedidos SET estado_envio='$estado_envio' WHERE id = $id_pedido";
	return $mysqli->query($sql);
}

// actualiza_envio_pedido_tracking ($id_pedido, $fecha_envio, $transportista){
// 	global $mysqli;
// 	$sql = "UPDATE datos_traking SET fecha_envio='$fecha_envio', transportista='$transportista' WHERE id = $id_pedido";
// 	return $mysqli->query($sql);
// }



function valoraciones_activo () {
	global $mysqli;
	$sql = "SELECT activado FROM configuracion WHERE id=1";
	return ( $mysqli->query($sql)->fetch_object()->activado == 1) ? true : false;
}

function envio_datos_tracking ( $ref_pedido, $id_cliente ) {

	global $mysqli;
	$sql="SELECT * FROM datos_tracking WHERE ref_pedido='$ref_pedido' AND id_cliente=$id_cliente";
	$res = $mysqli->query( $sql );
	return ( mysqli_num_rows ( $res ) > 0 ) ? true : false;

}

function inserta_datos_tracking ( $id_cliente, $id_pedido, $ref_pedido, $fecha_envio, $transportista, $num_tracking ) {
	global $mysqli;

	$valores="";
	$valores.="id_cliente = '$id_cliente', ";
	$valores.="id_pedido = $id_pedido, ";
	$valores.="ref_pedido = '$ref_pedido', ";
	$valores.="fecha_envio = '$fecha_envio', ";
	$valores.="transportista = '$transportista', ";
	$valores.="num_tracking = '$num_tracking', ";
	$valores.="fecha = '" . date('Y-m-d H:i:s') . "'";

	$sql = "INSERT INTO datos_tracking SET $valores";

	return $mysqli->query($sql);
}

// function guarda_datos_tracking ( $ref_pedido, $transportista, $fecha_envio, $num_tracking ) {
// 	$pedido['referencia'], $pedido['transporte'], $pedido['fecha_envio'], $pedido['num_tracking']

// 	global $mysqli;
// 	$sql = "INSERT INTO datos_tracking SET ref_pedido=$ref_pedido, transportista=$transportista, fecha_envio=$fecha_envio, num_tracking=$num_tracking WHERE id_pedido = $id_pedido";
// 	return $mysqli->query($sql);
// }

function actualiza_envio_email_tracking ( $id_pedido ) {

	global $mysqli;
	$sql = "UPDATE datos_tracking SET email_enviado=1 WHERE id_pedido = $id_pedido";
	return $mysqli->query($sql);

}
function actualiza_estado_pago ( $id_pedido, $estado ) {

	global $mysqli;
	$sql = "UPDATE pedidos SET estado_pago='$estado' WHERE id = $id_pedido";
	return $mysqli->query($sql);

}

function obten_datos_tracking ( $id_pedido ) {
	global $mysqli;
	$sql = "SELECT  * FROM datos_tracking WHERE id_pedido = $id_pedido";
	return $mysqli->query($sql)->fetch_object();
}

function obten_datos_tracking_ref_pedido ( $ref_pedido) {
	global $mysqli;
	$sql = "SELECT  * FROM datos_tracking WHERE ref_pedido = '$ref_pedido'";
	return $mysqli->query($sql)->fetch_object();
}

function hay_datos_tracking_ref_pedido ( $ref_pedido) {
	global $mysqli;
	$sql = "SELECT  * FROM datos_tracking WHERE ref_pedido = '$ref_pedido'";
	$res=$mysqli->query($sql);
	if($res->num_rows>0){
		return true;
	}else{
		return false;
	}
}

function fn_obtener_nombres_descuentos($descuento_actual){
    global $mysqli;
    $sql = "SELECT nombre_descuento FROM cupones_descuento WHERE nombre_descuento != '$descuento_actual'";
    $result = $mysqli->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}


function print_log( $filename, $txt ) {
	$txt = date( 'd/m/Y | H:i' ) . "\n\n" . $txt;
	$txt .= "\n\n=========================================================\n\n";
	$file = dirname( __DIR__, 2 ) . '/admin/logs/' . $filename . '.txt';
	$fp = fopen( $file, "a" );
	fwrite( $fp, $txt );
	fclose( $fp );
}

function obten_aplicacion_descuento ( $id ) {
	global $mysqli;
	$sql = "SELECT * FROM aplicacion_descuento WHERE id='" . $id . "'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_array_ultimos_pedidos ( $periodo_dias ) {
	global $mysqli;
	// $sql = "SELECT ref_pedido FROM pedidos WHERE fecha_creacion > timestamp(DATE_SUB( NOW() , INTERVAL $periodo_dias DAY ))";
	$sql = "SELECT ref_pedido FROM pedidos WHERE fecha_creacion > timestamp(DATE_SUB( NOW() , INTERVAL $periodo_dias DAY )) AND estado_envio = 'Pendiente'";

	$res = $mysqli->query($sql);
	$pedidos = array ();

	while ( $reg = $res->fetch_object() ) {
		array_push ( $pedidos, $reg->ref_pedido );
	}

	return $pedidos;
}



function obten_productos_envio_gratis () {

	global $mysqli;
	$productos = array();

	$sql = "SELECT * FROM productos_envio_gratis WHERE activo=1";
	$res = $mysqli->query($sql);

	while ( $reg = $res->fetch_object() ) {
		array_push ( $productos, $reg->id_producto );
	}
	return $productos;

}



function comprueba_envio_gratis () {

	$descuento = 1;
	$min_importe_envio_gratis = obten_minimo_portes_gratis();
	$envio_gratis = false;
	$importe_productos_envio_gratis = 0;
	$productos_envio_gratis = obten_productos_envio_gratis ();

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
			$id_prods_descuento = obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['aplicacion'] )->id_prods;
			$array_id_prods_descuento = explode( '|', $id_prods_descuento );

		}
	}


	if( $carrito->articulos_total() > 0 ) {

		foreach($carro as $producto) {

			/* comprobamos si es un articulo con derecho a portes gratis*/
			$producto_carro = obten_datos_producto( $producto['id'] )->es_variante;

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

		$id_prods_descuento = obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['p2x1'] )->id_prods;
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

		$reg = obten_dir_envio ( $_SESSION['smart_user']['dir_envio'] );
		if ( $reg->pais == 'ES' ) {
			return $envio_gratis;
		}else {
			return false;
		}
	}
	return $envio_gratis;
}


function hay_base_en_pedido ( $id_pedido ) {

	$productos_envio_gratis = obten_aplicacion_descuento ( 2 )->id_prods;
  $array_id_prods_base = explode( '|', $productos_envio_gratis );

  array_push( $array_id_prods_base, "3");

  $hay_base = false;
  $res=obten_detalles_pedido( $id_pedido );
  while($reg=$res->fetch_object()) {
		if ( in_array ( $reg->id_prod, $array_id_prods_base ) ) {
			$hay_base = true;
		}
	}
	return $hay_base;
}

function popup_newsletter_activo () {
	global $mysqli;
	$sql = "SELECT activado FROM configuracion WHERE id=3";
	return ( $mysqli->query($sql)->fetch_object()->activado == 1) ? true : false;
}

function obten_ip () {
	return $_SERVER['REMOTE_ADDR'];
}

function actualiza_envio_email_seguimiento ( $ref_pedido, $estado ) {
	global $mysqli;
	$sql = "UPDATE pedidos SET email_seguimiento='$estado', fecha_envio_seguimiento = '" . date('Y-m-d H:i:s') . "' WHERE ref_pedido = '$ref_pedido'";
	return $mysqli->query($sql);
}

function actualiza_envio_recuerda_codigo_newsletter ( $id, $estado ) {
	global $mysqli;
	$sql="UPDATE newsletter SET envio_recordatorio_codigo_news=1, fecha_envio_recordatorio='" . date('Y-m-d H:i:s') . "' WHERE id = '$id'";
	return $mysqli->query($sql);
}

function obten_articulos_microcemento () {
	global $mysqli;
	$sql = "SELECT * FROM productos WHERE id IN (1,2,3,4,5,6,7,8,9)";
	return $mysqli->query($sql);
}

function guarda_books_id ( $books_id, $email ) {
	global $mysqli;
	$sql="UPDATE users SET books_id='$books_id' WHERE email = '$email'";
	return $mysqli->query($sql);
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

//1) Si es cliente nacional - Operac.Internas Sujetas 21% (REP) [21%]
//2) Canarias, Ceuta y Melilla, exportación - Exportaciones de bienes [0%]
//3) Unión Europea, con VIES - Entregas Intracomunitarias Exentas [0%]
//4) Unión Europea, sin VIES (no superado max anual) - Operac.Internas Sujetas 21% (REP) [21%]
//5) Unión Europea, sin VIES (superado max anual) - IVA según pais destino
//6) Resto de países - Exportaciones de bienes [0%]


function obten_tipo_impuesto_envio ( $id_facturacion, $id_envio ) {

	$dir_facturacion = obten_dir_facturacion ( $id_facturacion );
	$dir_envio = obten_dir_envio ( $id_envio );

	$provincias_no_iva = array ( 51, 52, 350, 380, 353, 356, 388, 389 );
	$paises_eu = array ( 'DE', 'BE', 'HR', 'DK', 'FR', 'IE', 'LV', 'LU', 'NL', 'SE', 'BG', 'SK', 'EE', 'GR', 'MT', 'PL', 'CZ', 'AT', 'CY', 'SI', 'FI', 'HU', 'IT', 'LT', 'PT', 'RO' );

	if ( $dir_envio->pais == 'ES' && !in_array ( $dir_envio->provincia, $provincias_no_iva ) ) return 1;//nacional peninsula

	else if ( $dir_envio->pais == 'ES' && in_array ( $dir_envio->provincia, $provincias_no_iva ) ) return 2;//canarias

	else if ( $dir_envio->pais != 'ES' ) {

		if ( in_array ( $dir_envio->pais, $paises_eu ) ) {

			if ( comprueba_vies ( $dir_facturacion->pais, $dir_facturacion->nif ) && ( $dir_facturacion->tipo_factura == 'Empresa' || $dir_facturacion->tipo_factura == 'Autonomo' ) ) return 3;//UE con VIES

			else if ( (double) obten_ventas_no_vies() < (double) obten_max_importe_ventas_ue_no_vies() ) return 4;//UE sin VIES no supera limite anual

			else return 5;//UE sin VIES supera limite anual

		}else {

			return 6;//Resto paises

		}
	}
}


function guarda_llamada_api ( $tipo, $ref_pedido, $identificador, $contenido ) {

	global $mysqli;

	$valores="";
	$valores.="tipo='" . $tipo . "'";
	$valores.=", ref_pedido='" . $ref_pedido . "'";
	$valores.=", identificador='" . $identificador ."'";
	$valores.=", contenido='" . $contenido ."'";
	$valores.=", fecha_creacion='" . date('Y-m-d H:i:s') ."'";

	$sql="INSERT INTO llamadas_api SET $valores";
	consulta($sql, $mysqli);

}

function obten_iva_pais ( $cod_pais ) {
	global $mysqli;
	$sql="SELECT iva FROM paises_ue WHERE codigo ='" . $cod_pais . "'";
	return $mysqli->query($sql)->fetch_object()->iva;
}

function sin_iva ( $importe ) {
	return $importe / 1.21;
}

function obten_ventas_no_vies () {
	global $mysqli;
	$sql="SELECT importe_total FROM ventas_intracomunitarias_sin_vies_2023";
	return $mysqli->query($sql)->fetch_object()->importe_total;
}

function obten_max_importe_ventas_ue_no_vies () {
	global $mysqli;
	$sql="SELECT valor FROM variables_web WHERE nombre_var = 'importe_max_ventas_ue_no_vies'";
	return $mysqli->query($sql)->fetch_object()->valor;
}

function es_pais_ue ( $cod_pais ) {

	global $mysqli;

	$sql="SELECT * FROM paises_ue WHERE codigo = '" .  $cod_pais . "'";
	// echo $sql;
	$res=$mysqli->query($sql);
	if($res->num_rows > 0){
		return 1;
	}else{
		return 0;
	}
}

function obten_iva_books ( $cod_pais ) {
	global $mysqli;
	$sql="SELECT * FROM books_iva WHERE codigo = '" . $cod_pais . "'";
	return $mysqli->query($sql)->fetch_object();
}

function obten_books_id ( $id_prod ) {
	global $mysqli;
	$sql="SELECT books_id FROM productos WHERE id = '" . $id_prod . "'";
	return $mysqli->query($sql)->fetch_object()->books_id;
}

function obten_minimo_portes_gratis () {
	global $mysqli;
	$sql="SELECT valor FROM variables_web WHERE nombre_var = 'minimo_portes_gratis'";
	return $mysqli->query($sql)->fetch_object()->valor;
}

// function actualiza_estado_pedido ($pedido_id) {
// 	global $mysqli;
// 	$sql2 = "UPDATE sc_estado_pedidos SET documentacion=1 WHERE pedido_id = $pedido_id";
// 	return $mysqli->query($sql)->fetch_object();

// }

function actualiza_estado_pedido ($pedido_id) {
	global $mysqli;
	$sql = "UPDATE sc_estado_pedidos SET documentacion=1 WHERE pedido_id = $pedido_id";
	return $mysqli->query($sql);
}

function actualiza_estado_documentacion ($pedido_id) {
	global $mysqli;
	$sql = "UPDATE pedidos SET estado_documentacion=1 WHERE id = $pedido_id";
	return $mysqli->query($sql);
}

function programacion_esta_activo ( $nombre ) {

global $mysqli;
$sql = "SELECT * FROM sc_programaciones WHERE nombre = '$nombre'";
$reg = $mysqli->query($sql)->fetch_object();

$fecha_inicio = strtotime( $reg->fecha_inicio );
$fecha_fin = strtotime( $reg->fecha_fin );
$fecha = strtotime(date("d-m-Y H:i:00",time()));

// echo 'fecha_inicio: ' . $fecha_inicio;
// echo "<br>";
// echo 'fecha_fin: ' . $fecha_fin;
// echo "<br>";
// echo 'fecha: ' . $fecha;
// echo "<br>";

if ( ( $fecha >= $fecha_inicio ) && ( $fecha <= $fecha_fin ) ) return true;
else return false;

}

function calcula_gastos_envio_pedido () {


	if ( !isset( $_SESSION['smart_user']['dir_envio'] ) ) {
		return 0.00;
	}else {

		$reg = obten_dir_envio ( $_SESSION['smart_user']['dir_envio'] );

		$peso = calcula_peso_pedido();
		$cod_postal = $reg->cp;
		$pais = $reg->pais;

		// echo $pais;
		// echo num_kits_en_carrito();
		// echo num_kits_portes_gratis();

		$paises_envio_kits_gratis = array('GB','US');
		// $paises_envio_kits_gratis = array('ES','GB','IT');

		if ( activado_portes_gratis_pais_producto () && in_array( $pais, obten_paises_portes_gratis ()) && productos_portes_gratis_en_carrito ()) {

			if ( $pais != 'ES' ) {
				return 0.00;
			}else {
				if ( substr( $reg->provincia, 0, 2 ) == '35' || substr( $reg->provincia, 0, 2 ) == '38' ) return calcula_portes ( '', substr( $reg->provincia, 0, 3 ), $peso );
				else return 0;
			}

		} else {

			if ( in_array( $pais, $paises_envio_kits_gratis ) && portes_por_num_kits_activado () ) {

				if ( num_kits_en_carrito () ) {

					if ( $pais != 'ES' ) {
						return 0.00;
					}else {
						if ( substr( $reg->provincia, 0, 2 ) == '35' || substr( $reg->provincia, 0, 2 ) == '38' ) return calcula_portes ( '', substr( $reg->provincia, 0, 3 ), $peso );
					  else return 0;
					}

				}else {
					if ( $pais == 'ES') {
						return calcula_portes ( '',  $cod_postal, $peso );
					}else {
						return  calcula_portes ( strtoupper ( obten_nombre_pais ( $pais ) ), $cod_postal, $peso );
					}
				}

			}else {

				if ( comprueba_envio_gratis() ) {
					if ( substr( $reg->provincia, 0, 2 ) == '35' || substr( $reg->provincia, 0, 2 ) == '38' ) return calcula_portes ( '', substr( $reg->provincia, 0, 3 ), $peso );
					else return 0;
				}else {
					if ( $pais == 'ES') {
						return calcula_portes ( '',  $cod_postal, $peso );
					}else {
						return  calcula_portes ( strtoupper ( obten_nombre_pais ( $pais ) ), $cod_postal, $peso );
					}
				}
		 	}
	 }
	}
}

function num_kits_en_pedido ( $id_pedido ) {
	global $mysqli;
	$sql = "SELECT sum( cantidad ) AS total_kits FROM detalles_pedido WHERE id_pedido = $id_pedido AND id_prod IN ( SELECT id FROM productos WHERE es_variante IN (1,2));";
	return $mysqli->query($sql)->fetch_object()->total_kits;
}

function num_kits_en_carrito () {

	$num_kits = 0;
	$carrito = new Carrito();
	$carro = $carrito->get_content();

	if ( $carro ) {

		$id_prods_descuento = obten_aplicacion_descuento ( 2 )->id_prods;
		$array_id_prods_descuento = explode( '|', $id_prods_descuento );

			foreach($carro as $producto) {
				if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
					$num_kits += $producto['cantidad'];
				}
			}

		return ( $num_kits >= num_kits_portes_gratis() ) ? true : false;
	}
}

function num_kits_portes_gratis () {
	global $mysqli;
	$sql="SELECT valor1 as valor FROM configuracion WHERE id=7";
	return $mysqli->query($sql)->fetch_object()->valor;
}


if( !function_exists('portes_por_num_kits_activado') ) {

    global $mysqli;
    $mysqli=$conn;

    function portes_por_num_kits_activado () {
        global $mysqli;
        $sql = "SELECT activado FROM configuracion WHERE id=7";
        return ( $mysqli->query($sql)->fetch_object()->activado == 1) ? true : false;
    }
}

function get_divisa() {
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://www.expansion.com/app/bolsa/datos/cambio_divisas.html?opcion=1&cantidad=1&de_divisa=2&a_divisa=3&llave=',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
		),
	));

	$response = json_decode(curl_exec($curl), true);

	curl_close($curl);

	return (float) str_replace( ',', '.', $response['resultado'] );
}


function get_precio ( $sku, $idioma )  {

	if ( $idioma == 'en-us' ) $idioma = 'en_us';

	$precio = 'precio_' . $idioma;

	global $mysqli;
	$sql="SELECT * FROM productos WHERE sku ='" . $sku . "'";
	return $mysqli->query($sql)->fetch_object()->$precio;

}

function get_precio_base ( $id_producto, $id_idioma )  {

	#if ( $idioma == 'en-us' ) $idioma = 'en_us';

	#$precio_base = 'precio_base_' . $idioma;
	#$precio = 'precio_' . $idioma;

	global $mysqli;
	$sql="SELECT * FROM productos_precios_new WHERE id_producto ='" . $id_producto . "' AND id_idioma ='" . $id_idioma . "'";

	$pb = $mysqli->query($sql)->fetch_object()->precio_base;
	$descuento = $mysqli->query($sql)->fetch_object()->descuento;

	#$p_formateado= number_format($p, 2, ".", "");
	$pb_formateado= number_format($pb, 2, ".", "");

	if ($descuento == '0') {
		return 	$pb_formateado . '€ ';
	}else{
		$p=$pb*$descuento/100;
		$p_formateado= number_format($pb, 2, ".", "");
		return 	'<strong class="descuento">' . $pb_formateado . '€<br></strong>' . $p_formateado . '€ ';
	}
}

function descuento_activado ($id_producto, $id_idioma)  {

	#if ( $idioma == 'en-us' ) $idioma = 'en_us';

	#$descuento = 'descuento_' . $idioma;

	global $mysqli;
	$sql="SELECT * FROM productos_precios_new WHERE id_producto ='" . $id_producto . "' AND id_idioma ='" . $id_idioma . "'";

	$descuento_db = $mysqli->query($sql)->fetch_object()->descuento;

	if ($descuento_db != 0){
		return 	"<div style='background-color: #C93285 !important;' class='rebaja'>-". $descuento_db ."%</div>";
	}

}

function productos_destacados ($tipo, $idioma, $sku_el)  {

	global $mysqli;
	$sql = "SELECT * FROM sc_productos_destacados WHERE tipo='" . $tipo . "' and idioma='" . $idioma . "'";
	$resultado = $mysqli->query($sql);

	$productos_destacados = array();
	while ($fila = $resultado->fetch_object()) {
		$productos_destacados[] = $fila;
	}

	$sku = $mysqli->query($sql)->fetch_object()->$sku_el;
	$sql = "SELECT * FROM productos WHERE sku ='" . $sku . "'";
	$resultado = $mysqli->query($sql);

	$productos_destacados = array();
	while ($fila = $resultado->fetch_object()) {
		$productos_destacados[] = $fila;
	}

	return $productos_destacados;
}


function obten_condiciones_envio () {
	global $mysqli;
	$mysqli->set_charset("utf8mb4");
	$sql = "SELECT * FROM sc_condiciones_envios WHERE  activo = 1 ";
	if ( $mysqli->query($sql) ) return $mysqli->query($sql)->fetch_array();
}

function ids_variantes_producto ( $id_producto ) {

	global $mysqli;
	$sql="SELECT id FROM productos WHERE es_variante= $id_producto";
	$res = $mysqli->query($sql);

	$ids = '';

	while ( $reg = $res->fetch_object() ) {
		// $ids .= $reg->id . '|';
		echo $reg->id . '|';
	}
}

if( !function_exists('activado_portes_gratis_pais_producto') ) {

    global $mysqli;
    $mysqli=$conn;

    function activado_portes_gratis_pais_producto () {
        global $mysqli;
        $sql = "SELECT activado FROM configuracion WHERE id=8";
        return ( $mysqli->query($sql)->fetch_object()->activado == 1) ? true : false;
    }
}

function obten_paises_portes_gratis () {
	global $mysqli;
	$sql="SELECT * FROM configuracion WHERE id =8";
	return explode( '|', $mysqli->query($sql)->fetch_object()->valor1);
}

function obten_productos_portes_gratis () {
	global $mysqli;
	$sql="SELECT * FROM configuracion WHERE id =8";
	return explode( '|', $mysqli->query($sql)->fetch_object()->valor3);
}


function productos_portes_gratis_en_carrito () {

	$carrito = new Carrito();
	$carro = $carrito->get_content();

	if ( $carro ) {
		$array_id_prods_descuento = obten_productos_portes_gratis ();
			foreach($carro as $producto) {
				if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
					return true;
				}
			}
		return false;
	}
}

function obten_descuento_producto ( $id_producto ) {

	global $mysqli;
	global $lg;

	$descuento = 'descuento_' . $lg;
	$sql="SELECT $descuento FROM productos WHERE id = $id_producto";

	return $mysqli->query($sql)->fetch_object()->$descuento;

}

function obten_estado_pago ( $id ) {
	global $mysqli;
	$sql="SELECT nombre_estado FROM estados_pago WHERE id ='" . $id . "'";
	return $mysqli->query($sql)->fetch_object()->nombre_estado;
}

function obten_cupon_aplicado_pedido( $id ) {
	global $mysqli;
	$sql="SELECT * FROM pedidos_cupones_aplicados WHERE id ='".$id."'";
	return $mysqli->query($sql)->fetch_object();
}



?>
