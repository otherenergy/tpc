<?php
error_reporting(E_ALL & ~E_WARNING);

class userClass {

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

	public function executeInsert($sql, $args = []) {
		$pdo = getDB();

		$stmt = $pdo->prepare($sql);
		$stmt->execute($args);
		return $pdo->lastInsertId();
	}

	public function executeUpdate($sql, $args = []) {
		$pdo = getDB();

		$stmt = $pdo->prepare($sql);
		$stmt->execute($args);
		return $stmt->rowCount();
	}

	public function executeDelete($sql, $args = []) {
		$pdo = getDB();

		$stmt = $pdo->prepare($sql);
		$stmt->execute($args);
		return $stmt->rowCount();
	}

	public function fn_obtener_productos_padres($id_idioma){
		$cod_pais = $_SESSION['user_ubicacion'];
		$sql = "SELECT m.miniatura, m.alt, pn.nombre, pp.precio, pp.precio_base, pp.descuento, u.valor
				FROM multimedia m
				INNER JOIN productos p ON p.id_url = m.id_url
				INNER JOIN productos_nombres pn ON pn.id_producto = p.id
				INNER JOIN productos_precios_new pp ON pp.id_producto = p.id
				INNER JOIN urls u ON u.id_url = p.id_url
				WHERE m.id_idioma = ? 
				AND m.miniatura IS NOT NULL 
				AND m.miniatura != '' 
				AND m.tipo = 'imagen'
				AND m.activo = 1
				AND m.orden = 1
				AND p.publicado = 1
				AND p.es_variante = 0
				AND cod_pais = ?
				GROUP BY m.miniatura, m.alt 
				ORDER BY m.alt ASC";
		$arguments = [$id_idioma, $cod_pais];
		return $this->executeSelectObj($sql, $arguments);
	}
	


	public function fn_imagen_producto($id_url, $id_idioma) {
		$sql = "SELECT * FROM multimedia WHERE id_url = ? AND id_idioma = ? AND activo = 1 ORDER BY orden ASC";
		$arguments = [$id_url, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);

	}

	/* GAMMAS COLORES */
	public function obtener_gammas_colores($grupo, $id_idioma){
		$sql="SELECT * FROM atributos WHERE grupo=? AND id_idioma=? AND activo=1;";
		$arguments = [$grupo, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments );
	}

	public function ver_idiomas($id_url) {
		$sql = "SELECT i.*, u.valor
				FROM idiomas i
				LEFT JOIN urls u ON i.id = u.id_idioma AND u.id_url = ?
				ORDER BY i.orden";
		$arguments = [$id_url];
		return $this->executeSelectObj($sql, $arguments);
	}

	/* URLS */
	public function urls($id_url, $id_idioma) {
		$sql="SELECT * FROM urls WHERE id_url=? AND id_idioma=?";
		$arguments = [$id_url, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments );
	}

	public function obtener_moneda($cod_pais = null) {
		if ($cod_pais === null) {
			$cod_pais = $_SESSION['user_ubicacion'] ?? null;
		}

		$sql="SELECT * FROM paises WHERE cod_pais= ?";
		$arguments = [$cod_pais];
		return $this->executeSelectObj( $sql, $arguments )[0];
	}

	// public function obtener_moneda($id_idioma) {
	// 	$sql="SELECT * FROM monedas WHERE id_idioma=?";
	// 	$arguments = [$id_idioma];
	// 	return $this->executeSelectObj( $sql, $arguments )[0];
	// }

	// /* URLS */
	// public function urls($id_url, $id_idioma){
	// 	try{
	// 		$db = getDB();
	// 		$stmt = $db->prepare("SELECT * FROM urls WHERE id_url=:id_url AND id_idioma=:id_idioma");
	// 		$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
	// 		$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
	// 		$stmt->execute();
	// 		$data = $stmt->fetch(PDO::FETCH_OBJ);
	// 		return $data;
	// 	} catch(PDOException $e) {
	// 		echo '{"error":{"text":'. $e->getMessage() .'}}';
	// 	}
	// }

	/* URL Metas */
	public function url_metas($id_url, $id_idioma){
		$sql="SELECT * FROM url_metas WHERE id_url=? AND id_idioma=?";
		$arguments = [$id_url, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments )[0];
	}

	public function obtenerPrecios($cod_pais, $color, $acabado, $juntas, $formato, $id_producto) {
		$sql = "
			SELECT p.*
			FROM productos_precios_new p
			JOIN productos prod ON p.id_producto = prod.id
			WHERE p.cod_pais = ?
			  AND prod.color = ?
			  AND prod.acabado = ?
			  AND prod.juntas = ?
			  AND prod.formato = ?
			  AND prod.es_variante = ?
		";
		$arguments = [$cod_pais, $color, $acabado, $juntas, $formato, $id_producto];
		return $this->executeSelectObj($sql, $arguments);
	}


	public function recoger_atributos_color_acabado($id_producto, $id_idioma) {
		$cod_pais = $_SESSION['user_ubicacion'];

		$sql = "
SELECT
	p.sku,
	p.ean,
    pp.precio,
	pp.precio_base,
	pp.id_producto,
	pp.cod_pais,
    pn.nombre,
    pa.valor AS valor_atributo_color,
    paa.valor AS valor_atributo_acabado
FROM
    productos p
JOIN
    productos_precios_new pp ON p.id = pp.id_producto
JOIN
    productos_nombres pn ON p.id = pn.id_producto
JOIN
    productos_atributos pa ON p.color = pa.id_atributo AND pa.id_idioma = ?
JOIN
    productos_atributos paa ON p.acabado = paa.id_atributo AND paa.id_idioma = ?
WHERE
    p.es_variante = ?
    AND pn.id_idioma = ?;

		";
		$arguments = [$id_idioma, $id_idioma, $id_producto, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	public function recoger_atributos_color($id_producto, $id_idioma) {
		$sql = "
SELECT
	p.sku,
	p.ean,
    pp.precio,
	pp.precio_base,
	pp.id_producto,
	pp.cod_pais,
    pn.nombre,
    pa.valor AS valor_atributo_color
FROM
    productos p
JOIN
    productos_precios_new pp ON p.id = pp.id_producto
JOIN
    productos_nombres pn ON p.id = pn.id_producto
JOIN
    productos_atributos pa ON p.color = pa.id_atributo AND pa.id_idioma = ?
WHERE
    p.es_variante = ?
    AND pn.id_idioma = ?;
		";
		$arguments = [$id_idioma, $id_producto, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	public function recoger_atributos_formato($id_producto, $id_idioma) {
		$sql = "
SELECT
	p.sku,
	p.ean,
    pp.precio,
	pp.precio_base,
	pp.id_producto,
	pp.cod_pais,
    pn.nombre,
    pa.valor AS valor_atributo_formato
FROM
    productos p
JOIN
    productos_precios_new pp ON p.id = pp.id_producto
JOIN
    productos_nombres pn ON p.id = pn.id_producto
JOIN
    productos_atributos pa ON p.formato = pa.id_atributo AND pa.id_idioma = ?
WHERE
    p.es_variante = ?
    AND pn.id_idioma = ?;
		";
		$arguments = [$id_idioma, $id_producto, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	public function recoger_atributos_color_formato($id_producto, $id_idioma) {
		$sql = "
SELECT
	p.sku,
	p.ean,
    pp.precio,
	pp.precio_base,
	pp.id_producto,
	pp.cod_pais,
    pn.nombre,
    pa.valor AS valor_atributo_color,
	paa.valor AS valor_atributo_formato
FROM
    productos p
JOIN
    productos_precios_new pp ON p.id = pp.id_producto
JOIN
    productos_nombres pn ON p.id = pn.id_producto
JOIN
    productos_atributos pa ON p.color = pa.id_atributo AND pa.id_idioma = ?
JOIN
    productos_atributos paa ON p.formato = paa.id_atributo AND paa.id_idioma = ?
WHERE
    p.es_variante = ?
    AND pn.id_idioma = ?;
		";
		$arguments = [$id_idioma, $id_idioma, $id_producto, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	public function recoger_atributos_formato_acabado($id_producto, $id_idioma) {
		$sql = "
SELECT
	p.sku,
	p.ean,
    pp.precio,
	pp.precio_base,
	pp.id_producto,
	pp.cod_pais,
    pn.nombre,
    pa.valor AS valor_atributo_formato,
	paa.valor AS valor_atributo_acabado
FROM
    productos p
JOIN
    productos_precios_new pp ON p.id = pp.id_producto
JOIN
    productos_nombres pn ON p.id = pn.id_producto
JOIN
    productos_atributos pa ON p.formato = pa.id_atributo AND pa.id_idioma = ?
JOIN
    productos_atributos paa ON p.acabado = paa.id_atributo AND paa.id_idioma = ?
WHERE
    p.es_variante = ?
    AND pn.id_idioma = ?;
		";
		$arguments = [$id_idioma, $id_idioma, $id_producto, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}


	public function recoger_producto_unico($id_producto, $id_idioma) {
		$sql = "
SELECT
	p.sku,
	p.ean,
    pp.precio,
	pp.precio_base,
	pp.id_producto,
	pp.cod_pais,
    pn.nombre
FROM
    productos p
JOIN
    productos_precios_new pp ON p.id = pp.id_producto
JOIN
    productos_nombres pn ON p.id = pn.id_producto
WHERE
    p.id = ?
    AND pn.id_idioma = ?;

		";
		$arguments = [$id_producto, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	/* Menú Productos */
	public function menu_productos_orig($id_idioma){
		$sql="SELECT distinct pc.div_id, pc.orden as orden_categoria, p.orden orden_producto, p.id_categoria, pp.precio_base, pp.descuento, p.id, p.miniatura, p.id_url, p.variante, pn.nombre, u.valor, pc.valor as valor_pc FROM productos AS p
		JOIN productos_precios AS pp ON p.id = pp.id_producto JOIN productos_nombres AS pn ON p.id = pn.id_producto JOIN urls AS u ON p.id_url = u.id_url
		JOIN productos_categorias AS pc ON p.id_categoria = pc.id_categoria
		WHERE p.es_variante=0 AND p.publicado=1 AND pp.id_idioma=?
		AND pn.id_idioma=? AND u.id_idioma=? AND pc.id_idioma=? ORDER BY pc.orden, p.orden ASC;";
		$arguments = [$id_idioma, $id_idioma, $id_idioma, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments );
	}

	public function menu_productos($id_idioma){

		$cod_pais = $_SESSION['user_ubicacion'];

		$sql="SELECT
						distinct pc.div_id,
						pc.orden as orden_categoria,
						p.orden orden_producto,
						p.id_categoria,
						pp.precio_base,
						pp.descuento,
						p.id,
						p.miniatura,
						p.id_url,
						p.variante,
						pn.nombre,
						u.valor,
						pc.valor as valor_pc
					FROM
						productos AS p
					JOIN
						productos_precios_new AS pp ON p.id = pp.id_producto
					JOIN
						productos_nombres AS pn ON p.id = pn.id_producto
					JOIN
						urls AS u ON p.id_url = u.id_url
					JOIN
						productos_categorias AS pc ON p.id_categoria = pc.id_categoria
					WHERE
						p.es_variante=0
					AND
						p.publicado=1
					AND
						pp.cod_pais=?
					AND
						pn.id_idioma=?
					AND
						u.id_idioma=?
					AND
						pc.id_idioma=?
					ORDER BY pc.orden, p.orden ASC";

		$arguments = [$cod_pais, $id_idioma, $id_idioma, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments );
	}

	public function menu_productos_ofertas($id_idioma){

		$cod_pais = $_SESSION['user_ubicacion'];

		$sql="SELECT
						distinct pc.div_id,
						pc.orden as orden_categoria,
						p.orden orden_producto,
						p.id_categoria,
						pp.precio_base,
						pp.descuento,
						p.id,
						p.miniatura,
						p.id_url,
						p.variante,
						pn.nombre,
						u.valor,
						pc.valor as valor_pc
					FROM
						productos AS p
					JOIN
						productos_precios_new AS pp ON p.id = pp.id_producto
					JOIN
						productos_nombres AS pn ON p.id = pn.id_producto
					JOIN
						urls AS u ON p.id_url = u.id_url
					JOIN
						productos_categorias AS pc ON p.id_categoria = pc.id_categoria
					WHERE
						p.es_variante=0
					AND
						p.publicado=1
					AND
						pp.cod_pais=?
					AND
						pn.id_idioma=?
					AND
						u.id_idioma=?
					AND
						pc.id_idioma=?
				  AND
				  	pp.descuento!=0
					ORDER BY pc.orden, p.orden ASC";

		$arguments = [$cod_pais, $id_idioma, $id_idioma, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments );
	}

	/* Producto */
	public function producto($id_url){
		$sql="SELECT * FROM productos WHERE id_url=? and es_variante=0";
		$arguments = [$id_url];
		return $this->executeSelectObj( $sql, $arguments )[0];
	}

	/* Productos relacionados */
	public function productos_relacionados($id_productos, $id_idioma){

		$cod_pais = $_SESSION['user_ubicacion'];

		$id_producto1= $id_productos[0];
		$id_producto2= $id_productos[1];
		$id_producto3= $id_productos[2];
		$id_producto4= $id_productos[3];
		$sql="SELECT DISTINCT productos_precios_new.*, productos.*, productos.id as id_product, productos_info.*, urls.valor
		FROM productos
		INNER JOIN productos_info ON productos.id = productos_info.id_producto
		INNER JOIN urls ON productos.id_url = urls.id_url
		INNER JOIN productos_precios_new ON productos_precios_new.id_producto = productos.id
		WHERE productos.es_variante = 0
		AND (
		  productos.id = ? OR
		  productos.id = ? OR
		  productos.id = ? OR
		  productos.id = ?
		)
		AND productos_info.id_idioma=? AND urls.id_idioma=? AND productos_precios_new.cod_pais=?";
		$arguments = [$id_producto1, $id_producto2, $id_producto3, $id_producto4, $id_idioma, $id_idioma, $cod_pais];
		return $this->executeSelectObj( $sql, $arguments );
	}

	/* Productos destacados */
	public function productos_destacados($tipos, $id_idioma) {
		// var_dump($tipos);
		$tipo1= $tipos[0];
		$tipo2= $tipos[1];

		$cod_pais = $_SESSION['user_ubicacion'];

		$sqlProductos = "SELECT
				p.id,
				p.miniatura,
				p.sku,
				p.variante,
				pn.nombre,
				u.valor as url,
				pp.precio_base,
				pp.descuento,
				scp.tipo
			FROM productos AS p
			JOIN productos_precios_new AS pp ON p.id = pp.id_producto
			JOIN productos_nombres AS pn ON p.id = pn.id_producto
			JOIN urls AS u ON p.id_url = u.id_url
			JOIN sc_productos_destacados AS scp ON scp.sku = p.sku
			WHERE pp.cod_pais = ?
			AND pn.id_idioma = ?
			AND scp.id_idioma = ?
			AND u.id_idioma = ?
			AND scp.tipo IN (?, ?)
			AND p.publicado = 1
			AND scp.activo = 1;
		";


		$arguments = [$cod_pais, $id_idioma, $id_idioma, $id_idioma, $tipo1, $tipo2];

		$productos = $this->executeSelectObj($sqlProductos, $arguments);

		return $productos;
	}

	/* Fichas tecnicas */
	public function fichas_tecnicas($id_fichas_tecnicas, $id_idioma){
		$placeholders = implode(',', array_fill(0, count($id_fichas_tecnicas), '?'));
		$sql = "SELECT * from archivos where id_archivo in ($placeholders) and id_idioma = ?";
		$arguments = array_merge($id_fichas_tecnicas, [$id_idioma]);
		return $this->executeSelectObj($sql, $arguments);
	}

	/* Variantes de Formato con su precios */
	public function paso_paso($id_archivo,$id_idioma){
		$sql="SELECT * from archivos where id_archivo = ? and id_idioma = ?";
		$arguments = [$id_archivo, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments )[0];
	}

	/* Variantes de Color con su Descuento */
	public function variantes_color($id_producto){

		$cod_pais = $_SESSION['user_ubicacion'];

		$sql = "SELECT
			CAST(productos.color AS UNSIGNED) AS color,
			MAX(productos_precios_new.descuento) AS descuento,
			MAX(productos_precios_new.precio_base) AS precio_base,
			productos_precios_new.precio AS precio,
			productos_atributos.agotado AS agotado,
			productos_atributos.valor AS valor,
			productos.es_variante AS es_variante,
			sc_colores_ncs.color_ncs AS color_ncs
		FROM
			productos
		INNER JOIN
			productos_precios_new
			ON productos.id = productos_precios_new.id_producto
			AND productos_precios_new.cod_pais = ?
			AND productos.es_variante = ?
		INNER JOIN
			productos_atributos
			ON productos.color = productos_atributos.id_atributo
			AND productos_atributos.activo = 1
		LEFT JOIN
			sc_colores_ncs
			ON sc_colores_ncs.id_color = productos_atributos.id_atributo
		GROUP BY
			color
		ORDER BY
			color ASC;";

		$arguments = [$cod_pais, $id_producto];
		return $this->executeSelectObj( $sql, $arguments );
	}


	/* Variantes de Formato con su precios */
	public function variantes_acabado($id_producto, $id_idioma){

		$cod_pais = $_SESSION['user_ubicacion'];

		$sql="SELECT DISTINCT
			productos.acabado AS acabado,
			productos.miniatura AS miniatura,
			productos_precios_new.descuento AS descuento,
			productos_precios_new.precio_base AS precio_base,
			productos_atributos.agotado AS agotado,
			productos_atributos.valor AS valor,
			productos_precios_new.cod_pais
		FROM
			productos
		INNER JOIN
			productos_precios_new
			ON productos.id = productos_precios_new.id_producto
			AND productos_precios_new.cod_pais = ?
			AND productos.es_variante = ?
		INNER JOIN
			productos_atributos
			ON productos.acabado = productos_atributos.id_atributo
			AND productos_atributos.activo = 1
			AND productos_atributos.id_idioma = ?
		";
		$arguments = [$cod_pais, $id_producto, $id_idioma];
		return $this->executeSelectObj( $sql, $arguments );
	}

	// public function variantes_junta($id_producto,$cod_pais){
	// 	$sql="SELECT DISTINCT productos.juntas AS junta, productos.miniatura AS miniatura, productos_precios.descuento AS descuento,  productos_precios.precio_base AS precio_base, productos_atributos.agotado AS agotado, productos_atributos.valor AS valor FROM productos INNER JOIN productos_precios ON productos.id = productos_precios.id_producto AND productos_precios.cod_pais=? AND productos.es_variante=? INNER JOIN productos_atributos ON productos.juntas = productos_atributos.id_atributo AND productos_atributos.activo=1 group by junta;";
	// 	$arguments = [$cod_pais, $id_producto];
	// 	return $this->executeSelectObj( $sql, $arguments );
	// }

	/* URLS_TODAS */
	public function urls_todas($id_idioma){
		$sql = "SELECT id_url, valor FROM urls WHERE id_idioma=?";
		$arguments = [$id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	/* Variantes de Formato con su precios */
	public function variantes_formato($id_producto){
		$cod_pais = $_SESSION['user_ubicacion'];
		$sql = "SELECT DISTINCT productos.formato AS formato,
			productos.miniatura AS miniatura,
			productos_precios_new.descuento AS descuento,
			productos_precios_new.id_producto AS id_producto,
			productos_precios_new.precio_base AS precio_base,
			productos_atributos.agotado AS agotado,
			productos_atributos.valor AS valor
		FROM productos
		INNER JOIN productos_precios_new ON productos.id = productos_precios_new.id_producto AND productos_precios_new.cod_pais=? AND productos.es_variante=?
		INNER JOIN productos_atributos ON productos.formato = productos_atributos.id_atributo AND productos_atributos.activo=1 WHERE productos.publicado=1;";
		$arguments = [$cod_pais, $id_producto];
		return $this->executeSelectObj($sql, $arguments);
	}


	/* Nombre del Atributo en el idioma actual */
	public function atributo($id_atributo, $id_idioma){
		$sql = "SELECT valor FROM productos_atributos WHERE id_atributo=? AND id_idioma=?";
		$arguments = [$id_atributo, $id_idioma];
		return $this->executeSelectObj($sql, $arguments)[0];
	}

	/* IDIOMAS, URLS, METAS */
	public function obtener_informacion_url($id_url, $id_idioma) {
		$url_info = new stdClass();

		$sql_idiomas = "SELECT * FROM idiomas";
		$url_info->idiomas = $this->executeSelectObj($sql_idiomas, []);

		$sql_urls_nueva = "SELECT * FROM urls WHERE id_url=?";
		$url_info->hreflang = $this->executeSelectObj($sql_urls_nueva, [$id_url]);

		$sql_metas_url = "SELECT * FROM url_metas WHERE id_url=? AND id_idioma=?";
		$url_info->metas = $this->executeSelectObj($sql_metas_url, [$id_url, $id_idioma])[0];

		return $url_info;
	}

	// /* CONTENIDO POST */
	// public function post_contenido($id_url, $id_idioma){
	// 	$sql = "SELECT * FROM posts_contenido WHERE id_url = ? AND id_idioma = ?;"

	// 	$arguments = [$id_url, $id_idioma];
	// 	return $this->executeSelectObj($sql, $arguments);
	// }

	public function post_contenido($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM posts_contenido WHERE id_url = :id_url AND id_idioma = :id_idioma;");
			$stmt->bindParam('id_url', $id_url, PDO::PARAM_INT);
			$stmt->bindParam('id_idioma', $id_idioma, PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}

	/* HOME BLOG */
	public function blog_contenido($id_idioma){
		$sql = "SELECT distinct pc.id_url, pc.h1, um.title, pc.fecha, um.description, pc.image, pc.id_idioma, u.valor
				FROM urls u
				JOIN url_metas um ON u.id_url = um.id_url
				JOIN posts_contenido pc ON u.id_url = pc.id_url
				WHERE pc.id_url AND pc.id_idioma = ? AND um.id_idioma = ? AND u.id_idioma= ? AND u.id_tipo = 6
				ORDER BY pc.fecha DESC;";
		$arguments = [$id_idioma, $id_idioma, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	/* Producto Info */
	public function producto_info($id_producto, $id_idioma){
		$sql = "SELECT * FROM productos_info WHERE id_producto=? AND id_idioma=?";
		$arguments = [$id_producto, $id_idioma];
		return $this->executeSelectObj($sql, $arguments)[0];
	}

	/* Producto Precio */
	public function producto_precio($id_producto, $id_idioma){

		$cod_pais = $_SESSION['user_ubicacion'];

		$sql = "SELECT precio_base, descuento FROM productos_precios_new WHERE id_producto=? AND cod_pais=?";
		$arguments = [$id_producto, $cod_pais];
		return $this->executeSelectObj($sql, $arguments)[0];
	}
	// public function producto_precio($id_producto, $id_idioma){

	// 	$cod_pais = $_SESSION['user_ubicacion'];

	// 	$sql = "SELECT precio_base, descuento FROM productos_precios WHERE id_producto=? AND id_idioma=?";
	// 	$arguments = [$id_producto, $id_idioma];
	// 	return $this->executeSelectObj($sql, $arguments)[0];
	// }

	/* Producto Tabs */
	public function productos_tabs(){
		$sql = "SELECT * FROM productos_tabs ORDER BY orden ASC";
		return $this->executeSelectObj($sql, []);
	}

	/* Categoria del producto */
	public function categoria_producto($id_categoria, $id_idioma){
		$sql = "SELECT * FROM productos_categorias WHERE id_categoria=? AND id_idioma=?";
		$arguments = [$id_categoria, $id_idioma];
		return $this->executeSelectObj($sql, $arguments)[0];
	}

	/* Vocabularios */
	public function vocabulario($id_idioma){
		$sql = "SELECT id_vocabulario, valor FROM vocabulario WHERE id_idioma=?";
		$arguments = [$id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	/* Contenido */
	public function contenido($id_url, $id_idioma){
		$sql = "SELECT * FROM contenido WHERE id_url=? AND id_idioma=?";
		$arguments = [$id_url, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	/* Productos Información Tienda */
	public function productos_informacion_tienda($id_categoria, $id_idioma){
		$cod_pais = $_SESSION['user_ubicacion'];
		$sql = "SELECT pp.precio_base, pp.descuento, p.id, p.miniatura, p.id_url, p.variante, pi.nombre, u.valor
				FROM productos AS p
				JOIN productos_precios_new AS pp ON p.id = pp.id_producto
				JOIN productos_info AS pi ON p.id = pi.id_producto
				JOIN urls AS u ON p.id_url = u.id_url
				WHERE p.id_categoria=? AND p.es_variante=0 AND p.publicado=1 AND pp.cod_pais=? AND pi.id_idioma=? AND u.id_idioma=?
				ORDER BY p.orden ASC;";
		$arguments = [$id_categoria, $cod_pais, $id_idioma, $id_idioma];
		return $this->executeSelectObj($sql, $arguments);
	}

	/* Nombre Idioma */
	public function lang_code($id_idioma){
		$sql = "SELECT * FROM url_metas WHERE id_url=?";
		$arguments = [$id_url];
		return $this->executeSelectObj($sql, $arguments)[0];
	}

	// /* CONTENIDO GENERAL */
	// public function contenido_general($id_url, $id_idioma){
	// 	$sql = "SELECT * FROM contenido WHERE id_url = ? AND id_idioma = ?;";
	// 	$arguments = [$id_url, $id_idioma];
	// 	return $this->executeSelectObj($sql, $arguments);
	// }

	/* CONTENIDO GENERAL */
	public function contenido_general($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM contenido WHERE id_url = :id_url AND id_idioma = :id_idioma;");
			$stmt->bindParam('id_url', $id_url, PDO::PARAM_INT);
			$stmt->bindParam('id_idioma', $id_idioma, PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* CONTENIDO CORE */
	public function contenido_bloque($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM contenido WHERE id_url = :id_url AND id_idioma = :id_idioma;");
			$stmt->bindParam('id_url', $id_url, PDO::PARAM_INT);
			$stmt->bindParam('id_idioma', $id_idioma, PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* CONTENIDO EMAIL */
	public function contenido_email($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM emails WHERE id_url = :id_url AND id_idioma = :id_idioma;");
			$stmt->bindParam('id_url', $id_url, PDO::PARAM_INT);
			$stmt->bindParam('id_idioma', $id_idioma, PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	// public function contenido_email($id_url, $id_idioma){
	// 	$sql = "SELECT * FROM emails WHERE id_url = :id_url AND id_idioma = :id_idioma;";
	// 	$arguments = [$id_url, $id_idioma];
	// 	return $this->executeSelectObj($sql, $arguments);
	// }


	/* VIDEOS */
	public function obtener_video($id_url, $id_idioma, $orden){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM multimedia WHERE id_url=:id_url AND id_idioma=:id_idioma AND orden=:orden");
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->bindParam("orden", $orden,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
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

	/* URL datos pais a partir de su codigo */
	public function obten_datos_pais( $cod_pais ){
		$sql="SELECT * FROM paises WHERE cod_pais=?";
		$arguments = [ $cod_pais ];
		return $this->executeSelectObj( $sql, $arguments )[0];
	}

	/* URL para 'eliminar' un usuario, aunque realmente cambia el campo 'eliminado' al valor 1  */
	public function baja_usuario($email, $token){
		$sql = "UPDATE users SET eliminado=1 WHERE email=? AND password=?";
		$arguments = [$email,  $token];

		return $this->executeUpdate($sql, $arguments);
	}

	/* URL para 'activar' un usuario cambiando el campo activo al valor 1  */
	public function activar_cuenta($email, $token){


		$sql = "UPDATE users SET activo=1 WHERE email=? AND password=?";
		$arguments = [$email, $token];

		return $this->executeUpdate($sql, $arguments);
	}

	public function banner_promocion_activo() {

		$sql = "SELECT activado FROM configuracion WHERE id = ?";
		$arguments = [6];
		$result = $this->executeSelectObj($sql, $arguments);

    if (empty($result)) {
        return false;
    }
    return ( $result[0]->activado == 1 ) ? true : false;
	}


}