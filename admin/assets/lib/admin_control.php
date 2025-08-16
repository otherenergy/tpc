<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
require(dirname(dirname(dirname(__DIR__))) . "/assets/lib/bbdd.php");
require(dirname(dirname(dirname(__DIR__))) . "/config/db_connect.php");
require(dirname(dirname(dirname(__DIR__))) . "/class/userClass.php");

$userClass = new userClass();

// require(dirname(dirname(dirname(__DIR__))) . "/includes/urls.php");
require(dirname(dirname(dirname(__DIR__))) . "/class/emailClass.php");


// require( dirname ( dirname ( dirname ( __DIR__ ) ) ) . "/assets/lib/funciones.php" );
require(__DIR__ . "/funciones_admin.php");

if (isset($_REQUEST['accion']) && $_REQUEST['accion'] != '') {
	$post_keys = array_keys($_REQUEST);
	foreach ($post_keys as $key) {
		$$key = $_REQUEST[$key];
		// echo $key . ' - ' . $$key . '<br>';
	}
}

$resp = array();

switch ($accion) {

	case 'login':

		$sql = "SELECT * FROM users WHERE email='$input_email' AND password='" . hash('sha256', $input_pass) . "' AND es_admin>0";

		$res = consulta($sql, $conn);
		if (numFilas($res) > 0) {

			$reg = $res->fetch_object();

			if ($reg->activo == 0) {
				$resp['res'] = "0";
				$resp['msg'] = "La cuenta está desactivada.";
			} else {

				$_SESSION['smart_user_admin']['id'] = $reg->uid;
				$_SESSION['smart_user_admin']['nombre'] = $reg->nombre;
				$_SESSION['smart_user_admin']['email'] = $reg->email;
				$_SESSION['smart_user_admin']['login'] = 1;
				$_SESSION['smart_user_admin']['role'] = $reg->es_admin;

				if ($reg->idioma != 0) $_SESSION['smart_user']['lang'] = 1;

				$resp['res'] = "1";
				$resp['msg'] = "Hola $reg->nombre bienvenido al panel de administrador de Smartcret";
			}
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Los datos no coinciden con ningun Administrador ";
		}
		echo json_encode($resp);

		break;

	case 'logout':

		$resp['msg'] = "Hasta pronto " . $_SESSION['smart_user_admin']['nombre'] . ".";
		session_destroy();
		$_SESSION['smart_user_admin'] = array();
		echo json_encode($resp);
		break;

	case 'actualiza_estado_pago':

		if ($estado_pago != 'Pendiente') {

			$fecha_temp = explode('/', $fecha_pago);
			$fecha = $fecha_temp[2] . '-' . $fecha_temp[1] . '-' . $fecha_temp[0] . ' 00:00:01';

			$valores = '';
			$valores .= "estado_pago='$estado_pago', ";
			$valores .= "metodo_pago = $metodo_pago , ";
			$valores .= "fecha_pago = '$fecha'";
		} else {

			$valores = '';
			$valores .= "estado_pago='Pendiente', ";
			$valores .= "metodo_pago = '' , ";
			$valores .= "fecha_pago = null";
		}

		$sql = "UPDATE pedidos SET $valores WHERE ref_pedido='$ref_pedido'";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "El estado de pago del pedido $ref_pedido se ha cambiado a $estado_pago";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible actualizar el estado de pago del pedido $ref_pedido";
		}
		echo json_encode($resp);

		break;

	case 'actualiza_estado_envio':

		$pedido = obten_datos_pedido_ref($ref_pedido);

		if ($input_estado_envio != 'Pendiente') {

			$id_pedido = $pedido->id;
			$id_cliente = $pedido->id_cliente;

			$valores = '';
			$valores .= " id_cliente = $id_cliente ,";
			$valores .= " id_pedido = $id_pedido ,";
			$valores .= (isset($input_fecha)) ? "fecha_envio = '$input_fecha', " : "";
			$valores .= (isset($input_transportista)) ? "transportista = '$input_transportista', " : "";
			$valores .= (isset($input_envio_email)) ? "email_enviado = '$input_envio_email', " : "";
			// $valores .= " fecha = '" . date('Y-m-d H:i:s') . "' ,";			
			$valores .= (isset($input_seguimiento)) ? "num_tracking = '$input_seguimiento'" : "";

			if (hay_datos_tracking_ref_pedido($ref_pedido)) {
				$sql = "UPDATE datos_tracking SET $valores WHERE ref_pedido='$ref_pedido'";
			} else {
				$valores .= ", ref_pedido = '$ref_pedido'";
				$sql = "INSERT INTO datos_tracking SET $valores";
			}

			if (consulta($sql, $conn)) {
				actualiza_envio_pedido($pedido->id, 'Enviado', cambiaFormatoFecha($input_fecha), $input_transportista);
				$resp['res'] = "1";
				$resp['msg'] = "Los datos del envio del pedido $ref_pedido se han actualizado";

				/* si está indicado enviamos email al cliente con los datos*/
				if ($input_envio_email == 1) {

					$input_fecha = cambiaFormatoFecha($input_fecha);
					$datos_pedido = obten_datos_pedido_ref($ref_pedido);
					$datos_usuario = obten_datos_user($datos_pedido->id_cliente);
					$datos_envio = obten_dir_envio($datos_pedido->id_envio);
					$detalle_pedido = lista_pedido_ref($ref_pedido);

					$lang = $datos_pedido->idioma;
					$nombre = $datos_usuario->nombre;
					$apellido = $datos_usuario->apellidos;
					$telefono = $datos_usuario->telefono;
					$email = $datos_usuario->email;

					$direccion = $datos_envio->direccion;
					$localidad = $datos_envio->localidad;
					$cp = $datos_envio->cp;
					$provincia = $datos_envio->provincia;
					$pais = $datos_envio->pais;

					$emailclass = new emailClass();

					$email_pedido_recibido = $emailclass->email_pedido_enviado($lang, $email, $nombre, $apellido, $ref_pedido, $detalle_pedido, $input_fecha, $input_transportista, $input_seguimiento, $telefono, $direccion, $localidad, $cp, $provincia, $pais);

					// include_once(dirname(dirname(dirname(__DIR__))) . "/mailings/email_pedido_enviado.php");

					// function email_pedido_enviado($id_idioma, $email, $nombre, $apellido, $ref_pedido, $detalle_pedido, $fecha_envio, $transportista, $n_seguimiento, $user_telefono, $user_direccion, $user_localidad, $user_cp, $user_provincia, $user_pais) {

				}
			} else {
				$resp['res'] = "0";
				$resp['msg'] = "Error, no ha sido posible actualizar los datos de envio del pedido $ref_pedido";
			}
		} else {

			$sql = "DELETE FROM datos_tracking WHERE ref_pedido='$ref_pedido'";
			if (consulta($sql, $conn)) {
				actualiza_envio_pedido($pedido->id, 'Pendiente', null, '');
				$resp['res'] = "1";
				$resp['msg'] = "Los datos de envío del pedido $ref_pedido se han actualizado como 'Pendiente'";
			}
		}

		echo json_encode($resp);
		break;

	case 'actualiza_descuento':
		$nombres_descuentos = fn_obtener_nombres_descuentos($input_nombre);
		$nombre_existente = false;
		foreach ($nombres_descuentos as $descuento) {
			if ($descuento['nombre_descuento'] == $input_nombre) {
				$nombre_existente = true;
				break;
			};
		};
		
		if ($nombre_existente) {
			$resp['res'] = "0";
			$resp['msg'] = "El nombre del descuento ya existe";
		} else {
			$fecha_inicio = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_ini) . ' ' . $input_hora_ini : null;
			$fecha_fin = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_fin) . ' ' . $input_hora_fin : null;

			$valores = '';
			$valores .= " nombre_descuento = '$input_nombre' ,";
			$valores .= " valor = $input_valor,";
			$valores .= " tipo = '$input_tipo',";
			$valores .= " fecha_inicio = '$fecha_inicio' ,";
			$valores .= " fecha_fin = '$fecha_fin' ,";
			$valores .= " uso_persona = $input_uso_persona ,";
			$valores .= " aplicacion_descuento = $input_aplicacion_descuento ,";
			$valores .= " activo = $input_estado_descuento  ,";
			$valores .= " comentario = '$text_coment'";

			$sql = "UPDATE cupones_descuento SET $valores WHERE id=$id_descuento";

			if (consulta($sql, $conn)) {
				// actualiza_envio_pedido ( $pedido->id, 'Enviado', cambiaFormatoFecha( $input_fecha ), $input_transportista );
				$resp['res'] = "1";
				$resp['msg'] = "La configuración del descuento $input_nombre se ha actualizado";
			} else {
				$resp['res'] = "0";
				$resp['msg'] = "Error, no ha sido posible actualizar la configuración del descuento $input_nombre";
			}
		};
		echo json_encode($resp);
		break;


	case 'nuevo_descuento':
		$nombres_descuentos = fn_obtener_nombres_descuentos($input_nombre);
		$nombre_existente = false;
		foreach ($nombres_descuentos as $descuento) {
			if ($descuento['nombre_descuento'] == $input_nombre) {
				$nombre_existente = true;
				break;
			};
		};
		
		if ($nombre_existente) {
			$resp['res'] = "0";
			$resp['msg'] = "El nombre del descuento ya existe";
		} else {
		
			$fecha_inicio = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_ini) . ' ' . $input_hora_ini : null;
			$fecha_fin = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_fin) . ' ' . $input_hora_fin : null;

			$valores = '';
			$valores .= " nombre_descuento = '$input_nombre' ,";
			$valores .= " valor = $input_valor,";
			$valores .= " tipo = '$input_tipo',";
			$valores .= " fecha_inicio = '$fecha_inicio' ,";
			$valores .= " fecha_fin = '$fecha_fin' ,";
			$valores .= " uso_persona = $input_uso_persona ,";
			$valores .= " aplicacion_descuento = $input_aplicacion_descuento ,";
			$valores .= " activo = $input_estado_descuento  ,";
			$valores .= " fecha_creacion = '" . date('Y-m-d H:i:s') . "' ,";
			if (!empty($input_distribuidor)) {
				$valores .= " distribuidor = '$input_distribuidor' ,";
			}
			$valores .= " comentario = '$text_coment'";

			$sql = "INSERT INTO cupones_descuento SET $valores";

			if (consulta($sql, $conn)) {
				$resp['res'] = "1";
				$resp['msg'] = "El descuento $input_nombre se ha creado";
			} else {
				$resp['res'] = "0";
				$resp['msg'] = "Error, no ha sido posible crear el descuento $input_nombre";
			};

		};
		echo json_encode($resp);
		break;

	case 'añadir_distribuidor':

		$sql_check_email = "SELECT COUNT(*) as count FROM users WHERE email = '$input_distribuidor'";
		$result = consulta($sql_check_email, $conn);
		$row = mysqli_fetch_assoc($result);

		if ($row['count'] > 0) {
			$sql_update = "UPDATE users SET distribuidor = 1 WHERE email = '$input_distribuidor'";
			if (consulta($sql_update, $conn)) {
				$resp['res'] = "1";
				$resp['msg'] = "Se ha añadido el usuario como distribuidor";
			} else {
				$resp['res'] = "0";
				$resp['msg'] = "A ocurrido un error al añadir el usuario como distribuidor";
			}
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "El email no existe";
		}

		echo json_encode($resp);
		break;

	case 'elimina_descuento':

		$sql = "UPDATE cupones_descuento SET eliminado = 1 WHERE id = $id_descuento";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "El descuento se ha eliminado";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible eliminar el descuento";
		}

		echo json_encode($resp);
		break;

	case 'actualiza_aviso':

		$fecha_inicio = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_ini) . ' ' . $input_hora_ini : null;
		$fecha_fin = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_fin) . ' ' . $input_hora_fin : null;

		$valores = '';
		$valores .= 'nombre_aviso = "' . $input_nombre . '" ,';
		$valores .= 'fecha_inicio = "' . $fecha_inicio . '" ,';
		$valores .= 'fecha_fin = "' . $fecha_fin . '" ,';
		$valores .= 'activo = "' . $input_estado_aviso . '" ,';
		$valores .= 'aviso_es = "' . $aviso_es . '", ';
		$valores .= 'aviso_en = "' . $aviso_en . '", ';
		$valores .= 'aviso_en_us = "' . $aviso_en_us . '", ';
		$valores .= 'aviso_fr = "' . $aviso_fr . '", ';
		$valores .= 'aviso_it = "' . $aviso_it . '", ';
		$valores .= 'aviso_de = "' . $aviso_de . '" ';

		$conn->set_charset("utf8mb4");
		$sql = "UPDATE avisos_web SET $valores WHERE id=$id_aviso";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "La configuración del aviso $input_nombre se ha actualizado";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible actualizar la configuración del aviso $input_nombre";
		}

		echo json_encode($resp);
		break;


	case 'nuevo_aviso':

		$fecha_inicio = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_ini) . ' ' . $input_hora_ini : null;
		$fecha_fin = ($input_fecha_ini != '' && $input_fecha_ini != null) ? cambiaFormatoFecha($input_fecha_fin) . ' ' . $input_hora_fin : null;

		$valores = '';
		$valores .= 'nombre_aviso = "' . $input_nombre . '" ,';
		$valores .= 'fecha_inicio = "' . $fecha_inicio . '" ,';
		$valores .= 'fecha_fin = "' . $fecha_fin . '" ,';
		$valores .= 'fecha_creacion = "' . date('Y-m-d H:i:s') . '" ,';
		$valores .= 'activo = "' . $input_estado_aviso . '" ,';
		$valores .= 'aviso_es = "' . $aviso_es . '", ';
		$valores .= 'aviso_en = "' . $aviso_en . '", ';
		$valores .= 'aviso_en_us = "' . $aviso_en_us . '", ';
		$valores .= 'aviso_fr = "' . $aviso_fr . '", ';
		$valores .= 'aviso_it = "' . $aviso_it . '", ';
		$valores .= 'aviso_de = "' . $aviso_de . '" ';

		$conn->set_charset("utf8mb4");
		$sql = "INSERT INTO  avisos_web SET $valores ";

		// echo $sql;
		// exit;

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "El aviso $input_nombre se ha creado";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible crear el aviso $input_nombre";
		}

		echo json_encode($resp);
		break;

	case 'elimina_aviso':

		$sql = "DELETE FROM avisos_web WHERE id = $id_aviso";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "El aviso se ha eliminado";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible eliminar el aviso";
		}

		echo json_encode($resp);
		break;

	case 'elimina_usuario_news':

		$sql = "DELETE FROM newsletter WHERE id = $id_usu";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "El usuario se ha eliminado";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible eliminar el usuario";
		}

		echo json_encode($resp);
		break;

	case 'elimina_form_opinion':

		$sql = "DELETE FROM formulario_opinion WHERE id = $id_form";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "El formulario se ha eliminado";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible eliminar el formulario";
		}

		echo json_encode($resp);
		break;

	case 'cambia_estado_descuento':

		$activo = ($estado == 1) ? 0 : 1;

		$sql = "UPDATE cupones_descuento SET activo = $activo WHERE id = $id";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = ($activo == 1) ? 'El estado del descuento se ha actualizado a ACTIVADO' : 'El estado del descuento se ha actualizado a DESACTIVADO';
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible actualizar el estado del descuento";
		}

		echo json_encode($resp);
		break;

	case 'guarda_max_importe_ventas_ue_no_vies':

		$sql = "UPDATE variables_web SET valor = $importe WHERE nombre_var = 'importe_max_ventas_ue_no_vies'";

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = 'Se ha actualizado el valor de Importe máximo de ventas UE sin VIES';
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible actualizar el estado del importe";
		}

		echo json_encode($resp);
		break;

	case 'cambia_estado_aviso':

		$activo = ($estado == 1) ? 0 : 1;

		$sql = "UPDATE avisos_web SET activo = $activo WHERE id = $id";

		if (consulta($sql, $conn)) {

			// $sql2="UPDATE avisos_web SET activo = 0 WHERE id != $id";
			// consulta( $sql2, $conn );

			$resp['res'] = "1";
			$resp['msg'] = ($activo == 1) ? 'El estado del aviso se ha actualizado a ACTIVADO' : 'El estado del aviso se ha actualizado a DESACTIVADO';
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible actualizar el estado del aviso";
		}

		echo json_encode($resp);
		break;

	case 'elimina_pedido':

		// echo $id_pedido;
		// echo "YEEE";
		// $ref_pedido = obten_datos_pedido($id_pedido)->fetch_object()->ref_pedido;
		// echo $ref_pedido;

		$ref_pedido = obten_datos_pedido($id_pedido)->fetch_object()->ref_pedido;

		$sql = "DELETE FROM pedidos WHERE id = $id_pedido";

		if (consulta($sql, $conn)) {

			$sql2 = "DELETE FROM detalles_pedido WHERE id_pedido = $id_pedido";

			if (consulta($sql2, $conn)) {
				$eliminado = true;
			} else {
				$eliminado = false;
			}
		} else {
			$eliminado = false;
		}

		if ($eliminado) {
			$resp['res'] = "1";
			$resp['msg'] = "El pedido $ref_pedido se ha eliminado";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible eliminar el pedido $ref_pedido";
		}
		echo json_encode($resp);
		break;


	case 'cambia_estado_configuracion':

		$estado = obten_configuracion($id)->activado;
		$activo = ($estado == 1) ? 0 : 1;

		$sql = "UPDATE configuracion SET activado = $activo WHERE id = $id";
		// echo $sql;

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = ($activo == 1) ? 'La configuración ' . obten_configuracion($id)->accion . ' se ha activado' : 'La configuración ' . obten_configuracion($id)->accion . ' se ha desactivado';
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible actualizar el estado de " . obten_configuracion($id)->accion;
		}

		echo json_encode($resp);
		break;

	case 'cancelacion_pedido':

		$sql_cancela = "UPDATE pedidos set cancelado = '$cancelacion' WHERE ref_pedido = '$ref_pedido'";

		if (consulta($sql_cancela, $conn)) {

			$valores = '';
			$valores .= 'ref_pedido = "' . $ref_pedido . '" ,';
			$valores .= 'motivo = "' . $motivo . '" ,';
			$valores .= 'fecha_actualizacion = "' . cambiaFormatoFecha($input_fecha) . '"';


			$sql_elimina = "DELETE FROM sc_cancelacion_pedidos WHERE ref_pedido = '$ref_pedido'";

			if (consulta($sql_elimina, $conn)) {

				if ($cancelacion == 1) {

					$sql_insert = "INSERT INTO sc_cancelacion_pedidos SET $valores";
					consulta($sql_insert, $conn);
				} else {

					$resp['res'] = "0";
					$resp['msg'] = "Error, no ha sido posible actualizar el estado de " . $ref_pedido;
				}

				$resp['res'] = "0";
				$resp['msg'] = "Se ha actualizado el estado de " . $ref_pedido;
			} else {

				$resp['res'] = "0";
				$resp['msg'] = "Error, no ha sido posible actualizar el estado de " . $ref_pedido;
			}
		} else {

			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible actualizar el estado de " . $ref_pedido;
		}

		// echo $sql_cancela;
		// echo "<br>";
		// echo $sql_elimina;
		// echo "<br>";
		// echo $sql_insert;
		// echo "<br>";

		echo json_encode($resp);

		break;

	case 'cambia_estado_color':

		$sql = "SELECT agotado FROM atributos WHERE id = $color_id";
		$res = consulta($sql, $conn);

		if (numFilas($res) > 0) {

			$reg = $res->fetch_object();

			$cambio_estado = ($reg->agotado == 0) ? 1 : 0;

			$sql_cambio = "UPDATE atributos SET agotado = $cambio_estado WHERE id = $color_id";

			if (consulta($sql_cambio, $conn)) {
				$resp['res'] = "1";
				$resp['msg'] = "El estado se ha cambiado a " . $cambio_estado;
			} else {
				$resp['res'] = "0";
				$resp['msg'] = "Se ha producido un error";
			}
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "No se ha encontrado ese color";
		}

		echo json_encode($resp);

		break;


	case 'actualiza_descuentos_promocion':

		$idiomas = array('es' => 'ES', 'en' => 'GB', 'en_us' => 'US', 'fr' => 'FR', 'it' => 'IT', 'de' => 'DE');

		switch ($id_prod) {
			case '1':
				$grupo_productos = '1, 2';
				break;
			case '812':
				$grupo_productos = '812, 813';
				break;
			case '981':
				$grupo_productos = '981, 982';
				break;
			case '815':
				$grupo_productos = '815, 816';
				break;
			default:
				$grupo_productos = $id_prod;
				break;
		}

		// Obtener todos los productos que tienen es_variante igual a los productos en grupo_productos
		$sql_variante = "SELECT id FROM productos WHERE es_variante IN ($grupo_productos)";
		$res_variante = consulta($sql_variante, $conn);

		$productos_variante = array();
		while ($row = $res_variante->fetch_assoc()) {
			$productos_variante[] = $row['id'];
		}

		// Combinar los productos originales con los productos que tienen es_variante
		$todos_productos = array_merge(explode(',', $grupo_productos), $productos_variante);
		$todos_productos_str = implode(',', $todos_productos);

		foreach ($idiomas as $idioma => $cod_pais) {
			$var = 'descuento_' . $idioma;
			if ($$var != '-') {
				$descuento = $$var;
				$precio = 'ROUND(precio_base * (1 - ' . $descuento / 100 . '), 2)';
				$sql = "
						UPDATE productos_precios_new 
						SET descuento = $descuento, 
							precio = $precio
						WHERE id_producto IN ($todos_productos_str) AND cod_pais = '$cod_pais'
					";
				if (!consulta($sql, $conn)) {
					$resp['res'] = "0";
					$resp['msg'] = "Error, no ha sido posible actualizar los descuentos";
					echo json_encode($resp);
					exit;
				}
			}
		}

		$resp['res'] = "1";
		$resp['msg'] = "Los descuentos se han actualizado";

		echo json_encode($resp);
		break;



		case 'actualiza_descuentos_por_color_promocion':

			$idiomas = array('es' => 'ES', 'en' => 'GB', 'en_us' => 'US', 'fr' => 'FR', 'it' => 'IT', 'de' => 'DE');
		
			switch ($id_prod) {
				case '1':
					$grupo_productos = '1, 2';
					break;
				case '812':
					$grupo_productos = '812, 813';
					break;
				case '981':
					$grupo_productos = '981, 982';
					break;
				case '815':
					$grupo_productos = '815, 816';
					break;
				default:
					$grupo_productos = $id_prod;
					break;
			}
		
			// Obtener todos los productos que tienen es_variante igual a los productos en grupo_productos
			$sql_variante = "SELECT id FROM productos WHERE es_variante IN ($grupo_productos)";
			$res_variante = consulta($sql_variante, $conn);
		
			$productos_variante = array();
			while ($row = $res_variante->fetch_assoc()) {
				$productos_variante[] = $row['id'];
			}
		
			// Combinar los productos originales con los productos que tienen es_variante
			$todos_productos = array_merge(explode(',', $grupo_productos), $productos_variante);
			$todos_productos_str = implode(',', $todos_productos);
		
			foreach ($idiomas as $idioma => $cod_pais) {
				$var = 'descuento_' . $idioma;
				if ($$var != '-') {
					$descuento = $$var;
					$precio = 'ROUND(precio_base * (1 - ' . $descuento / 100 . '), 2)';
					$sql = "
						UPDATE productos_precios_new 
						SET descuento = $descuento, 
							precio = $precio
						WHERE id_producto IN ($todos_productos_str) 
						AND cod_pais = '$cod_pais' 
						AND id_producto IN (
							SELECT P.id 
							FROM productos P
							JOIN atributos A ON P.color = A.id_atributo
							WHERE A.id_atributo = '$id_color'
						)
					";
					if (!consulta($sql, $conn)) {
						$resp['res'] = "0";
						$resp['msg'] = "Error, no ha sido posible actualizar los descuentos";
						echo json_encode($resp);
						exit;
					}
				}
			}
		
			$resp['res'] = "1";
			$resp['msg'] = "Los descuentos se han actualizado";
		
			echo json_encode($resp);
			break;
		



	case 'cambia_estado_pedido':


		$sql = "SELECT * FROM sc_estado_pedidos WHERE pedido_id = $pedido_id";
		$res = consulta($sql, $conn);

		$reg = $res->fetch_object();

		if (numFilas($res) > 0) {

			if ($tipo_estado == 'documentacion' && $reg->preparacion == 0) {

				$resp['res'] = "0";
				$resp['msg'] = "No es posible actualizar el estado 'Documentación' hasta que no se actualice 'Preparación'";
			} else if ($tipo_estado == 'envio' && $reg->preparacion == 0) {

				$resp['res'] = "0";
				$resp['msg'] = "No es posible actualizar el estado 'Envío' hasta que no se actualice 'Preparación'";
			} else if ($tipo_estado == 'documentacion' && $reg->envio == 1) {

				$resp['res'] = "0";
				$resp['msg'] = "No es posible deshacer el estado 'Documentación' si no se actualiza 'Envío'";
			} else if ($tipo_estado == 'preparacion' && $reg->documentacion == 1) {

				$resp['res'] = "0";
				$resp['msg'] = "No es posible deshacer el estado 'Preparación' si no se actualiza 'Documentacion'";
			} else {

				if ($tipo_estado == 'envio' && $reg->documentacion == 0) {
					$sql2 = "UPDATE sc_estado_pedidos SET documentacion=1, $tipo_estado = $estado, fecha_" . $tipo_estado . " = '" . date('Y-m-d H:i:s') . "' WHERE pedido_id = $pedido_id";
				} else {
					$sql2 = "UPDATE sc_estado_pedidos SET $tipo_estado = $estado, fecha_" . $tipo_estado . " = '" . date('Y-m-d H:i:s') . "' WHERE pedido_id = $pedido_id";
				}

				if (consulta($sql2, $conn)) {

					if ($tipo_estado == 'documentacion') {
						$actualiza_estado_documentacion = actualiza_estado_documentacion ($pedido_id);
					}

					if ($tipo_estado == 'envio') {

						if ($estado = 1) {
							$sql_enviado = "UPDATE pedidos set estado_envio= 'Enviado' WHERE id=$pedido_id";

							$datos_tracking = obten_datos_tracking($pedido_id);
							$fecha_envio = $datos_tracking->fecha_envio;
							$ref_pedido = $datos_tracking->ref_pedido;
							$transportista = $datos_tracking->transportista;
							$num_tracking = $datos_tracking->num_tracking;
					
							$datos_pedido = obten_datos_pedido_ref($ref_pedido);
							$datos_usuario = obten_datos_user($datos_pedido->id_cliente);
							$datos_envio = obten_dir_envio($datos_pedido->id_envio);
							$detalle_pedido = lista_pedido_ref($ref_pedido);
					
							$lang = $datos_pedido->idioma;
							$nombre = $datos_usuario->nombre;
							$apellido = $datos_usuario->apellidos;
							$telefono = $datos_usuario->telefono;
							$email = $datos_usuario->email;
					
							$direccion = $datos_envio->direccion;
							$localidad = $datos_envio->localidad;
							$cp = $datos_envio->cp;
							$provincia = $datos_envio->provincia;
							$pais = $datos_envio->pais;
					
					
							$emailclass = new emailClass();
					
							$email_pedido_recibido = $emailclass->email_pedido_enviado($lang, $email, $nombre, $apellido, $ref_pedido, $detalle_pedido, $fecha_envio, $transportista, $num_tracking, $telefono, $direccion, $localidad, $cp, $provincia, $pais);
								
							$actualiza_envio_email_tracking = actualiza_envio_email_tracking($pedido_id);

						}

						if ($estado = 0) {
							$sql_enviado = "UPDATE pedidos set estado_envio= 'Pendiente' WHERE id=$pedido_id";

						}

						consulta($sql_enviado, $conn);
					}


					$resp['res'] = "1";
					$resp['msg'] = "El estado de $tipo_estado del pedido se ha modificado";
				} else {

					$resp['res'] = "0";
					$resp['msg'] = "Error, no ha sido posible actualizar el estado de $tipo_estado del pedido";
				}
			}
		} else {

			if ($tipo_estado == 'documentacion' || $tipo_estado == 'envio') {

				$resp['res'] = "0";
				$resp['msg'] = "No es posible actualizar el estado '$tipo_estado' hasta que no se actualice 'Preparación'";
			} else {

				$sql2 = "INSERT INTO sc_estado_pedidos SET pedido_id = $pedido_id, $tipo_estado = $estado, fecha_" . $tipo_estado . " = '" . date('Y-m-d H:i:s') . "'";

				if (consulta($sql2, $conn)) {

					$resp['res'] = "1";
					$resp['msg'] = "El estado de $tipo_estado del pedido se ha modificado";
				} else {

					$resp['res'] = "0";
					$resp['msg'] = "Error, no ha sido posible actualizar el estado de $tipo_estado del pedido";
				}
			}
		}

		echo json_encode($resp);

		break;

	case 'guarda_comentario_preparacion':


		$valores = '';
		$valores .= 'id_pedido = "' . $id_pedido . '" ,';
		$valores .= 'ref_pedido = "' . $ref_pedido . '" ,';
		$valores .= 'comentario = "' . $comentario . '" ,';
		$valores .= 'usuario = "' . $_SESSION['smart_user_admin']['nombre'] . '" ,';
		$valores .= 'id_usuario = "' . $_SESSION['smart_user_admin']['id'] . '" ,';
		$valores .= 'fecha = "' . date('Y-m-d H:i:s') . '"';

		$conn->set_charset("utf8mb4");
		$sql = "INSERT INTO sc_comentarios_preparacion SET $valores";

		// echo $sql;
		// exit;

		if (consulta($sql, $conn)) {
			$resp['res'] = "1";
			$resp['msg'] = "Se ha guardado el comentario";
		} else {
			$resp['res'] = "0";
			$resp['msg'] = "Error, no ha sido posible guardar el comentario";
		}

		echo json_encode($resp);

		break;
}
