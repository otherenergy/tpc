<?php
class userClass {
	
	#URL
	#CONTENIDO
	#PRODUCTO
	#UTILIDADES
	
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/****************************************************************************************************  URL  *********************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/	

	public function obtener_informacion_url($id_url, $id_idioma) {
		try {
			$db = getDB();
			$url_info = new stdClass();			
			// Obtener detalles de los idiomas disponibles
			$stmt_idiomas = $db->prepare("SELECT * FROM idiomas");
			$stmt_idiomas->execute();
			$url_info->idiomas = $stmt_idiomas->fetchAll(PDO::FETCH_OBJ);
			// Obtener detalles de la URL para todos los idiomas
			$stmt_urls_nueva = $db->prepare("SELECT * FROM urls WHERE id_url = :id_url");
			$stmt_urls_nueva->bindParam("id_url", $id_url, PDO::PARAM_INT);
			$stmt_urls_nueva->execute();
			$url_info->hreflang = $stmt_urls_nueva->fetchAll(PDO::FETCH_OBJ);
			// Obtener metadatos de la URL para el idioma específico
			$stmt_metas_url = $db->prepare("SELECT * FROM url_metas WHERE id_url = :id_url AND id_idioma = :id_idioma");
			$stmt_metas_url->bindParam("id_url", $id_url, PDO::PARAM_INT);
			$stmt_metas_url->bindParam("id_idioma", $id_idioma, PDO::PARAM_INT);
			$stmt_metas_url->execute();
			$url_info->metas = $stmt_metas_url->fetch(PDO::FETCH_OBJ);
			return $url_info;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/***********************************************************************************************  CONTENIDO  ********************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
	/* Menú Contenido */
	public function menu_contenido($id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT cc.*, u.valor as href FROM contenido_categorias cc INNER JOIN urls u ON cc.id_url = u.id_url WHERE u.id_idioma=:id_idioma AND cc.id_idioma =:id_idioma AND cc.menu = 1 ORDER BY cc.orden ASC");
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Contenido */
	public function ver_contenido($id_url,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM contenido WHERE id_url=:id_url AND id_idioma=:id_idioma"); 
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/***********************************************************************************************  PRODUCTO  *********************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
	/* Menú Productos */
	public function menu_productos($idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT $idioma FROM productos_categorias WHERE menu = 1 ORDER BY orden ASC"); 
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Info Producto */
	public function obtener_producto_informacion_y_precio($id_url, $id_idioma){
		try{
			$db = getDB();
			// Obtener la información del producto
			$stmt = $db->prepare("SELECT p.*, pc.div_id as div_id, pc.valor as categoria, pi.*, pp.* FROM productos p 
								  INNER JOIN productos_categorias pc ON p.id_categoria = pc.id_categoria
								  INNER JOIN productos_info pi ON p.id = pi.id_producto
								  INNER JOIN productos_precios pp ON p.id = pp.id_producto 
								  WHERE p.id_url=:id_url AND p.es_variante='' AND pi.id_idioma=:id_idioma AND pp.id_idioma=:id_idioma");  
			$stmt->bindParam("id_url", $id_url, PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma, PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Variantes de Juntas */
	public function variantes_junta($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT DISTINCT productos.juntas FROM productos INNER JOIN productos_atributos ON productos.juntas = productos_atributos.id_atributo WHERE productos.es_variante = :id_producto AND productos_atributos.id_idioma=:id_idioma ORDER BY productos_atributos.id_atributo ASC;"); 
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
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
			$stmt = $db->prepare("SELECT CAST(productos.color AS UNSIGNED) AS color, MAX(productos_precios.descuento) AS descuento, productos_atributos.agotado AS agotado FROM productos INNER JOIN productos_precios ON productos.id = productos_precios.id_producto AND productos_precios.id_idioma=:id_idioma AND productos.es_variante=:id_producto INNER JOIN productos_atributos ON productos.color = productos_atributos.id AND productos_atributos.activo=1 GROUP BY color ORDER BY color ASC;"); 
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Variantes de Acabados */
	public function variantes_acabado($id_producto){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT DISTINCT productos.acabado FROM productos INNER JOIN productos_atributos ON productos.acabado = productos_atributos.id WHERE productos.es_variante = :id_producto ORDER BY productos.acabado ASC;"); 
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
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
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/**************************************************************************************************  UTILIDADES  ****************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/
/********************************************************************************************************************************************************************************************************************************/	
	/* Ver Enlace */
	public function ver_enlace($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT u.*,um.* FROM urls u INNER JOIN url_metas um ON u.id_url=um.id_url AND u.id_idioma = :id_idioma AND u.id_url = :id_url"); 
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Ver Producto */
	public function ver_producto($id_url, $id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT u.*,um.*,p.miniatura, pp.precio, pp.descuento FROM urls u INNER JOIN url_metas um INNER JOIN productos p ON u.id_url=um.id_url INNER JOIN productos_precios pp ON p.id = pp.id_producto AND u.id_idioma = :id_idioma AND u.id_url = :id_url AND p.id_url=:id_url AND pp.id_idioma=:id_idioma"); 
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Nombre Idioma */
	public function lang_code($id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM url_metas WHERE id_url=:id_url"); 
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Vocabulario */
	public function vocabulario($id_vocabulario,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT valor FROM vocabulario WHERE id_vocabulario=:id_vocabulario AND id_idioma=:id_idioma");
			$stmt->bindParam("id_vocabulario", $id_vocabulario,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data->valor;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		/* URL Details */
	public function url_idiomas($id_url){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM urls WHERE id=:id_url");
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* URL Idioma */
	public function url_idioma($id_url,$idioma_url){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT $idioma_url FROM urls WHERE id=:id_url");
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data->$idioma_url;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}	
	/* URL Metas */
	public function url_metas($id_url,$id_idioma){
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
	
	/* Producto */
	public function producto($id_url){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM productos WHERE id_url=:id_url AND es_variante='' ");  
			$stmt->bindParam("id_url", $id_url,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Producto Info */
	public function productos_info($id_producto,$id_idioma){
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
	/* Producto Info */
	public function productos_variantes($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT productos.id as id, productos.juntas as juntas, productos.color as color, productos.acabado as acabado, productos_precios.precio as precio, productos_precios.descuento as descuento FROM productos INNER JOIN productos_precios ON productos.id = productos_precios.id_producto AND productos_precios.id_idioma=:id_idioma AND productos.es_variante=:id_producto"); 
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("id_idioma", $id_idioma,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Producto Precio */
	public function productos_precio($id_producto,$id_idioma){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM productos_precios WHERE id_producto=:id_producto AND id_idioma=:id_idioma "); 
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
	public function productos_tabs($id,$idioma_url){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT div_id,$idioma_url FROM productos_tabs WHERE id=:id"); 
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data = $stmt->fetch(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
	/* Producto Atributos */
	public function variantes_atributo($id_producto,$atributo){
		try{
			$db = getDB();
			$stmt = $db->prepare("SELECT $atributo FROM productos WHERE es_variante=:id_producto AND publicado=1");
			$stmt->bindParam("id_producto", $id_producto,PDO::PARAM_INT);
			$stmt->bindParam("atributo", $atributo,PDO::PARAM_STR);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_OBJ);
			return $data;
		} catch(PDOException $e) {
			echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
}

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
?>