
<?php

// include_once ( '../assets/lib/bbdd.php' );
include_once ( '../config/db_connect.php' );

$mysqli=$conn;

$sql ="SELECT * FROM idiomas";

$cab= "id\ttitle\tdescription\tlink\tcondition\tprice\tavailability\timage_link\tgtin\tbrand\tshipping_weight\tunit_pricing_measure\tgoogle_product_category\tcustom_label_0\r\n";

$res = $mysqli->query($sql);
while ( $reg = $res->fetch_object() ) {

	$id_idioma = $reg->id;
	$zip_idioma = $reg->idioma;

	switch ($id_idioma) {
		case 1:
			$zip_pais = "es";
			$link = "es";
			$moneda = "EUR";
			break;
		case 2:
			$zip_pais = "fr";
			$link = "es";
			$moneda = "EUR";
			break;
		case 3:
			$zip_pais = "gb";
			$link = "en-gb";
			$moneda = "EUR";
			break;
		case 4:
			$zip_pais = "us";
			$link = "en-us";
			$moneda = "USD";
			break;
		case 5:
			$zip_pais = "it";
			$link = "it";
			$moneda = "EUR";
			break;
		case 6:
			$zip_pais = "de";
			$link = "de";
			$moneda = "EUR";
			break;
	}

	$pais = strtoupper($zip_pais);

	$sqlPersonalizado ="
	WITH cte AS (
    SELECT 
        p.id AS id_producto,
        p.sku AS id, 
        p.miniatura AS miniatura, 
        p.ean AS ean, 
        p.peso, 
        p.id_categoria_google,
        pn.nombre AS title,
        pp.precio,
        pp.cod_pais,
        u.valor AS url,
		pc.valor AS categoria,
        ROW_NUMBER() OVER (PARTITION BY p.id ORDER BY p.id) AS rn
    FROM smartcret_web.productos p 
    JOIN smartcret_web.productos_nombres pn ON p.id = pn.id_producto 
    JOIN smartcret_web.urls u ON p.id_url = u.id_url
    JOIN smartcret_web.productos_precios_new pp ON p.id = pp.id_producto
	JOIN productos_categorias pc ON p.id_categoria = pc.id_categoria
    WHERE p.variante = 0 
      AND p.publicado = 1
      AND u.id_idioma = ".$id_idioma."
      AND pn.id_idioma = ".$id_idioma."
	  AND pc.id_idioma = ".$id_idioma."
      AND pp.cod_pais = '".$pais."'
)
SELECT 
    id_producto,
    id, 
    miniatura, 
    ean, 
    peso, 
    id_categoria_google,
    title,
    precio,
    cod_pais,
    url,
	categoria
FROM cte
WHERE rn = 1;
	";

	$linea = "";

	$resPersonalizado = $mysqli->query($sqlPersonalizado);

	while ( $regPersonalizado = $resPersonalizado->fetch_object() ) {
		$linea .= $regPersonalizado->id . "\t";
		$linea .= $regPersonalizado->title . "\t";
		$linea .= $regPersonalizado->title . "\t";
		$linea .= "https://www.smartcret.com/".$link."/".$regPersonalizado->url . "\t";
		$linea .= "nuevo"."\t";
		$linea .= $regPersonalizado->precio ." ".$moneda ."\t";
		$linea .= "en stock"."\t";
		$linea .= 'https://www.smartcret.com/assets/img/productos/'.$regPersonalizado->miniatura . "\t";
		$linea .= $regPersonalizado->ean . "\t";
		$linea .= "Smartcret"."\t";
		$linea .= $regPersonalizado->peso ."\t";
		$linea .= $regPersonalizado->peso ."\t";
		$linea .= $regPersonalizado->id_categoria_google ."\t";
		$linea .= $regPersonalizado->categoria . "\r\n";
	}


	$cab= "id\ttitle\tdescription\tlink\tcondition\tprice\tavailability\timage_link\tgtin\tbrand\tshipping_weight\tunit_pricing_measure\tgoogle_product_category\tcustom_label_0\r\n";

	$newfile='./productos_smartcret_'.$zip_pais.'.tsv';
	$file=fopen($newfile, 'w');
	fwrite($file, $cab);
	fwrite($file, $linea);
	fclose($file);
	
	echo "Creado <a href='./productos_smartcret_".$zip_pais.".tsv' target=''>productos_smartcret_".$zip_pais.".tsv</a>";
	echo "<br>";
}
?>