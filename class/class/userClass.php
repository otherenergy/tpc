<?php
class userClass {
	/* Idiomas */
	public function ver_idiomas(){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM idiomas");
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* URLS */
	public function urls($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM urls WHERE id_url=:id_url AND id_idioma=:id_idioma");
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* URL Metas */
	public function url_metas($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM url_metas WHERE id_url=:id_url AND id_idioma=:id_idioma ");
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	// /* Menú Productos */
	// public function menu_productos($id_idioma){
	// 	try{
	// 		$db = getDB();
	// 		$stmt = $db->prepare("SELECT * FROM productos_categorias WHERE menu = 1 and id_idioma = :id_idioma ORDER BY orden ASC");
	// 		$stmt->bindParam(':id_idioma', $id_idioma, PDO::PARAM_INT);
	// 		$stmt->execute();
	// 		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
	// 		return $data;
	// 	} catch(PDOException $e) {
	// 		echo '{"error":{"text":'. $e->getMessage() .'}}';
	// 	}
	// }
	/* Menú Productos */
	public function menu_productos($id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT distinct pc.div_id, pc.orden, p.orden, p.id_categoria, pp.precio_base, pp.descuento, p.id, p.miniatura, p.id_url, p.variante, pi.nombre, u.valor, pc.valor as valor_pc FROM productos AS p
			JOIN productos_precios AS pp ON p.id = pp.id_producto JOIN productos_info AS pi ON p.id = pi.id_producto JOIN urls AS u ON p.id_url = u.id_url
            JOIN productos_categorias AS pc ON p.id_categoria = pc.id_categoria
			WHERE p.es_variante=0 AND p.publicado=1 AND pp.id_idioma=:id_idioma
            AND pi.id_idioma=:id_idioma AND u.id_idioma=:id_idioma AND pc.id_idioma=:id_idioma ORDER BY pc.orden, p.orden ASC;");
			$stmt->bindParam(':id_idioma', $id_idioma, PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Producto */
	public function producto($id_url){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM productos WHERE id_url=:id_url and es_variante=0");
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Productos relacionados */
	public function productos_relacionados($id_productos, $id_idioma){
		$id_producto1= $id_productos[0];
		$id_producto2= $id_productos[1];
		$id_producto3= $id_productos[2];
		$id_producto4= $id_productos[3];
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT DISTINCT productos_precios.*, productos.*, productos_info.*, urls.valor
			FROM productos
			INNER JOIN productos_info ON productos.id = productos_info.id_producto
			INNER JOIN urls ON productos.id_url = urls.id_url
			INNER JOIN productos_precios ON productos_precios.id_producto = productos.id
			WHERE productos.es_variante = 0
			AND (
			  productos.id = :id_producto1 OR
			  productos.id = :id_producto2 OR
			  productos.id = :id_producto3 OR
			  productos.id = :id_producto4
			)
			AND productos_info.id_idioma=:id_idioma AND urls.id_idioma=:id_idioma AND productos_precios.id_idioma=:id_idioma");

			$stmt->bindParam("id_producto1", $id_producto1,PDO::PARAM_INT);
			$stmt->bindParam("id_producto2", $id_producto2,PDO::PARAM_INT);
			$stmt->bindParam("id_producto3", $id_producto3,PDO::PARAM_INT);
			$stmt->bindParam("id_producto4", $id_producto4,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);

			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}


	/* Variantes de Color con su Descuento */
	public function variantes_color($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT CAST(productos.color AS UNSIGNED) AS color, MAX(productos_precios.descuento) AS descuento,  MAX(productos_precios.precio_base) AS precio_base, productos_precios.precio AS precio, productos_atributos.agotado AS agotado, productos_atributos.valor AS valor FROM productos INNER JOIN productos_precios ON productos.id = productos_precios.id_producto AND productos_precios.id_idioma=:id_idioma AND productos.es_variante=:id_producto INNER JOIN productos_atributos ON productos.color = productos_atributos.id_atributo AND productos_atributos.activo=1 GROUP BY color ORDER BY color ASC;");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	// /* Variantes de Acabados */
	// public function variantes_acabado($id_producto, $id_idioma){
	// 	try{
	// 		$db = getDB();
	// 		$stmt = $db->prepare("SELECT DISTINCT productos.acabado FROM productos INNER JOIN productos_atributos ON productos.acabado = productos_atributos.id_atributo WHERE productos.es_variante = :id_producto  AND productos_atributos.id_idioma=:id_idioma ORDER BY productos.acabado ASC;");
	// 		$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
	// 		$stmt->execute();
	// 		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
	// 		return $data;
	// 	} catch(PDOException $e) {
	// 		echo '{"error":{"text":'. $e->getMessage() .'}}';
	// 	}
	// }

	/* Variantes de Formato con su precios */
	public function variantes_acabado($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT DISTINCT productos.acabado AS acabado, productos.miniatura AS miniatura, productos_precios.descuento AS descuento,  productos_precios.precio_base AS precio_base, productos_atributos.agotado AS agotado, productos_atributos.valor AS valor FROM productos INNER JOIN productos_precios ON productos.id = productos_precios.id_producto AND productos_precios.id_idioma=:id_idioma AND productos.es_variante=:id_producto INNER JOIN productos_atributos ON productos.acabado = productos_atributos.id_atributo AND productos_atributos.activo=1 group by acabado;");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	// /* Variantes de Juntas */
	// public function variantes_junta($id_producto,$id_idioma){
	// 	try{
	// 		$db = getDB();
	// 		$stmt = $db->prepare("SELECT DISTINCT productos.juntas FROM productos INNER JOIN productos_atributos ON productos.juntas = productos_atributos.id_atributo WHERE productos.es_variante = :id_producto AND productos_atributos.id_idioma=:id_idioma ORDER BY productos_atributos.id_atributo ASC;");
	// 		$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
	// 		$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
	// 		$stmt->execute();
	// 		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
	// 		return $data;
	// 	} catch(PDOException $e) {
	// 		echo '{"error":{"text":'. $e->getMessage() .'}}';
	// 	}
	// }


	public function variantes_junta($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT DISTINCT productos.juntas AS junta, productos.miniatura AS miniatura, productos_precios.descuento AS descuento,  productos_precios.precio_base AS precio_base, productos_atributos.agotado AS agotado, productos_atributos.valor AS valor FROM productos INNER JOIN productos_precios ON productos.id = productos_precios.id_producto AND productos_precios.id_idioma=:id_idioma AND productos.es_variante=:id_producto INNER JOIN productos_atributos ON productos.juntas = productos_atributos.id_atributo AND productos_atributos.activo=1 group by junta;");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	// /* Variantes de Juntas */
	// public function variantes_formato($id_producto,$id_idioma){
	// 	try{
	// 		$db = getDB();
	// 		$stmt = $db->prepare("SELECT DISTINCT productos.formato FROM productos INNER JOIN productos_atributos ON productos.formato = productos_atributos.id_atributo WHERE productos.es_variante = :id_producto AND productos_atributos.id_idioma=:id_idioma ORDER BY productos_atributos.id_atributo ASC;");
	// 		$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
	// 		$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
	// 		$stmt->execute();
	// 		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
	// 		return $data;
	// 	} catch(PDOException $e) {
	// 		echo '{"error":{"text":'. $e->getMessage() .'}}';
	// 	}
	// }

	/* Variantes de Formato con su precios */
	public function variantes_formato($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT DISTINCT productos.formato AS formato, productos.miniatura AS miniatura, productos_precios.descuento AS descuento,  productos_precios.precio_base AS precio_base, productos_atributos.agotado AS agotado, productos_atributos.valor AS valor FROM productos INNER JOIN productos_precios ON productos.id = productos_precios.id_producto AND productos_precios.id_idioma=:id_idioma AND productos.es_variante=:id_producto INNER JOIN productos_atributos ON productos.formato = productos_atributos.id_atributo AND productos_atributos.activo=1;");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Nombre del Atributo en el idioma actual */
	public function atributo($id_atributo,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT valor FROM productos_atributos WHERE id_atributo=:id_atributo AND id_idioma=:id_idioma");
			$stmt->bindParam("id_atributo", $id_atributo,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Info Producto */
	public function obtener_producto_informacion_y_precio($id_producto, $id_idioma){
		try{
			$db = getDB();
			// Obtener la información del producto
			$stmt = $db->prepare("SELECT  pp.precio_base, pp.descuento ,pi.* FROM productos_info pi, productos_precios pp WHERE pi.id_producto=:id_producto AND pi.id_idioma=:id_idioma AND pp.id_producto=:id_producto AND pp.id_idioma=:id_idioma");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Producto Info */
	public function producto_info($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM productos_info WHERE id_producto=:id_producto AND id_idioma=:id_idioma ");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Producto Precio */
	public function producto_precio($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT precio_base, descuento FROM productos_precios WHERE id_producto=:id_producto AND id_idioma=:id_idioma ");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Producto Tabs */
	public function productos_tabs(){
		try{
			$db = getDB();
			// $sql = "SELECT div_id, $idioma_url FROM productos_tabs ORDER BY orden ASC";
			// $stmt = $db->prepare($sql);
			$stmt = $db->prepare("SELECT * FROM productos_tabs ORDER BY orden ASC");
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Categoria del producto*/
	public function categoria_producto($id_categoria, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM productos_categorias WHERE id_categoria=:id_categoria AND id_idioma=:id_idioma");
			$stmt->bindParam("id_categoria", $id_categoria,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Vocabularios*/
	public function vocabulario($id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT id_vocabulario, valor FROM vocabulario WHERE id_idioma=:id_idioma ");
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	public function txt_lang( $text, $id_idioma ) {
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT v2.valor as valor FROM vocabulario AS v1 JOIN vocabulario AS v2 ON v1.id_vocabulario = v2.id_vocabulario WHERE v2.id_idioma =:id_idioma AND v1.valor =:text");
			$stmt->bindParam("text", $text,PDO::PARAM_STR);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ)->valor;
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* Contenido*/
	public function contenido($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM contenido WHERE id_url=:id_url and id_idioma=:id_idioma ");
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* DATOS */
	// public function productos_informacion_tienda($id_categoria,$id_idioma){
	// 	try{
	// 		$db = getDB();
	// 		$stmt = $db->prepare("SELECT pp.precio_base, pp.descuento, p.id, p.miniatura, p.id_url, p.variante, pi.nombre, u.valor FROM productos AS p
	// 		JOIN productos_precios AS pp ON p.id = pp.id_producto JOIN productos_info AS pi ON p.id = pi.id_producto JOIN urls AS u ON p.id_url = u.id_url
	// 		WHERE p.id_categoria=:id_categoria AND p.es_variante=0 AND p.publicado=1 AND pp.id_idioma=:id_idioma AND pi.id_idioma=:id_idioma AND u.id_idioma=:id_idioma ORDER BY p.orden ASC;");

	// 		$stmt->bindParam("id_categoria", $id_categoria,PDO::PARAM_INT);
	// 		$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
	// 		$stmt->execute();
	// 		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
	// 		return $data;
	// 	} catch(PDOException $e) {
	// 		echo '{"error":{"text":'. $e->getMessage() .'}}';
	// 	}
	// }
	public function productos_informacion_tienda($id_categoria,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT pp.precio_base, pp.descuento, p.id, p.miniatura, p.id_url, p.variante, pi.nombre, u.valor FROM productos AS p
			JOIN productos_precios AS pp ON p.id = pp.id_producto JOIN productos_info AS pi ON p.id = pi.id_producto JOIN urls AS u ON p.id_url = u.id_url
			WHERE p.id_categoria=:id_categoria AND p.es_variante=0 AND p.publicado=1 AND pp.id_idioma=:id_idioma AND pi.id_idioma=:id_idioma AND u.id_idioma=:id_idioma ORDER BY p.orden ASC;");

			$stmt->bindParam("id_categoria", $id_categoria,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Nombre Idioma */
	public function lang_code( $id_idioma, $tipo_guion=1 ) {
		$guion = ( $tipo_guion == 1 ) ? '-' : '_';
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM idiomas WHERE id=:id_idioma");
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return ( $data->pais != NULL ) ? $data->idioma . $guion . $data->pais : $data->idioma;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

}
?>