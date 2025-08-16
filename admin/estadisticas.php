<?php
$pagina = "Estadisticas";
$title = "Estadisticas | Smartcret";
$description = "Página de estadisticas de ventas y otros valores de la tienda";
include('./includes/header.php');

$meses = array("", "Ene", "Febo", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
// $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$tipo_grafico = 'bar';
$label_meses = array();

$color_pedidos = "#80a2e9";
$color_importes = "#92bf23";
$color_kits = "#f1d65c";
$color_productos = "#ff6347";


if ($_GET['muestra'] == 'todos') {

	$data = array(
		"labels" => $label_meses,
		"datasets" => array(
			array(
				"label" => "Pedidos",
				"data"	=> $pedidos,
				"backgroundColor" => $color_pedidos
			),
			array(
				"label" => "Kits",
				"data"	=> $kits,
				"backgroundColor" => $color_kits
			),
			array(
				"label" => "Importe Ventas",
				"data"	=> $importes,
				"backgroundColor" => $color_importes
			)
		)
	);
}

if ($_GET['muestra'] == 'pedidos') {

	$sql_pedidos = "SELECT COUNT(*) as num_ped, YEAR(fecha_creacion) as anio, MONTH(fecha_creacion) as mes FROM pedidos WHERE YEAR(fecha_creacion) IN ('2023', '2024') AND cancelado=0 GROUP BY YEAR(fecha_creacion), MONTH(fecha_creacion)";

	$res_pedidos = consulta($sql_pedidos, $conn);
	$pedidos = array();
	$mes = array();
	while ($reg = $res_pedidos->fetch_object()) {
		array_push($pedidos, $reg->num_ped);
		array_push($mes, $meses[$reg->mes] . ' ' . $reg->anio);
	}

	$data = array(
		"labels" => $mes,
		"datasets" => array(
			array(
				"label" => "Pedidos",
				"data"	=> $pedidos,
				"backgroundColor" => $color_pedidos
			)
		)
	);
}

// KITS
if ($_GET['muestra'] == 'kits') {

	$sql_kits = "SELECT SUM(DP.cantidad) AS kits, YEAR(DP.fecha_creacion) AS anio, MONTH(DP.fecha_creacion) AS mes FROM detalles_pedido DP JOIN pedidos P ON DP.id_pedido = P.id WHERE DP.id_prod IN (SELECT id FROM productos WHERE es_variante IN (1, 2, 812, 813, 815, 816, 981, 982)) AND YEAR(DP.fecha_creacion) IN ('2023', '2024') AND P.cancelado = 0 GROUP BY YEAR(DP.fecha_creacion), MONTH(DP.fecha_creacion);";

	$kits = array();
	$mes = array();
	$res_kits = consulta($sql_kits, $conn);
	while ($reg = $res_kits->fetch_object()) {
		array_push($kits, $reg->kits);
		array_push($mes, $meses[$reg->mes] . ' ' . $reg->anio);
	}

	$data = array(
		"labels" => $mes,
		"datasets" => array(
			array(
				"label" => "Kits",
				"data"	=> $kits,
				"backgroundColor" => $color_kits
			)
		)
	);
}

// CANTIDAD VENTA PRODUCTOS
if ($_GET['muestra'] == 'total_productos') {

	$sql_total_variantes = "SELECT PR.es_variante, SUM(DP.cantidad) AS Productos, YEAR(DP.fecha_creacion) AS anio, MONTH(DP.fecha_creacion) AS mes
	FROM detalles_pedido DP
	JOIN pedidos P ON DP.id_pedido = P.id
	JOIN productos PR ON DP.id_prod = PR.id
	WHERE DP.id_prod IN (
		SELECT id FROM productos WHERE es_variante IN (1, 2, 812, 813, 23, 3, 4, 394, 815, 816, 981, 982)
		UNION
		SELECT 430 AS id) AND P.cancelado = 0 AND YEAR(DP.fecha_creacion) IN ('2023', '2024') AND (YEAR(DP.fecha_creacion) < YEAR(CURDATE())OR(YEAR(DP.fecha_creacion) = YEAR(CURDATE()) AND MONTH(DP.fecha_creacion) <= MONTH(CURDATE())))
	GROUP BY PR.es_variante, YEAR(DP.fecha_creacion), MONTH(DP.fecha_creacion)
	ORDER BY YEAR(DP.fecha_creacion), MONTH(DP.fecha_creacion);";


	$mesActual = date('n');
	$anioActual = date('Y');

	$res_variantes = consulta($sql_total_variantes, $conn);

	$datos_variante1 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante2 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante813 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante812 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante3 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante4 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante23 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante394 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante815 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante816 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante981 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_variante982 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);
	$datos_id430 = array_fill(0, (($anioActual - 2023) * 12) + $mesActual, 0);

	// // procesamiento de consulta
	while ($reg = $res_variantes->fetch_object()) {
		$index = (($reg->anio - 2023) * 12) + ($reg->mes - 1);
		switch ($reg->es_variante) {
			case 1:
				$datos_variante1[$index] = $reg->Productos;
				break;
			case 2:
				$datos_variante2[$index] = $reg->Productos;
				break;
			case 813:
				$datos_variante813[$index] = $reg->Productos;
				break;
			case 812:
				$datos_variante812[$index] = $reg->Productos;
				break;
			case 3:
				$datos_variante3[$index] = $reg->Productos;
				break;
			case 4:
				$datos_variante4[$index] = $reg->Productos;
				break;
			case 23:
				$datos_variante23[$index] = $reg->Productos;
				break;
			case 394:
				$datos_variante394[$index] = $reg->Productos;
				break;
			case 815:
				$datos_variante815[$index] = $reg->Productos;
				break;
			case 816:
				$datos_variante816[$index] = $reg->Productos;
				break;
			case 981:
				$datos_variante981[$index] = $reg->Productos;
				break;
			case 982:
				$datos_variante982[$index] = $reg->Productos;
				break;
			case 0: // corresponde a Smartkit Herramientas id=430
				$datos_id430[$index] = $reg->Productos;
				break;
		}
	}

	$datos_variantes1y2 = array();
	for ($i = 0; $i < count($datos_variante1); $i++) {
		$datos_variantes1y2[$i] = $datos_variante1[$i] + $datos_variante2[$i];
	}
	$datos_variantes813y812 = array();
	for ($i = 0; $i < count($datos_variante813); $i++) {
		$datos_variantes813y812[$i] = $datos_variante813[$i] + $datos_variante812[$i];
	}
	$datos_variantes815y816 = array();
	for ($i = 0; $i < count($datos_variante816); $i++) {
		$datos_variantes815y816[$i] = $datos_variante816[$i] + $datos_variante815[$i];
	}
	$datos_variantes981y982 = array();
	for ($i = 0; $i < count($datos_variante982); $i++) {
		$datos_variantes981y982[$i] = $datos_variante982[$i] + $datos_variante981[$i];
	}

	$datasets = [
		[
			"label" => "Smartcret KIT 8m2",
			"data" => $datos_variantes1y2,
			"backgroundColor" => '#17c9ec',
		],
		[
			"label" => "Smartcret KIT 16m2",
			"data" => $datos_variantes813y812,
			"backgroundColor" => '#ff6347',
		],
		[
			"label" => "Smartcret KIT 4m2",
			"data" => $datos_variantes815y816,
			"backgroundColor" => '#035be4',
		],
		[
			"label" => "Smartcret KIT 24m2",
			"data" => $datos_variantes981y982,
			"backgroundColor" => '#fa8ddd',
		],
		[
			"label" => "Smartcret base 6kg",
			"data" => $datos_variante3,
			"backgroundColor" => '#36A327',
		],
		[
			"label" => "Smartcret liso 6kg",
			"data" => $datos_variante4,
			"backgroundColor" => '#4cf81d',
		],
		[
			"label" => "Smartcover Tiles",
			"data" => $datos_variante23,
			"backgroundColor" => '#ffc107',
		],
		[
			"label" => "Smartcover Repair 10Kg",
			"data" => $datos_variante394,
			"backgroundColor" => '#e73bd9',
		],
		[
			"label" => "Smart Kit herramientas",
			"data" => $datos_id430,
			"backgroundColor" => '#9873d0',
		]
	];

	$meses_labels = [];
	for ($anio = 2023; $anio <= $anioActual; $anio++) {
		foreach (["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"] as $index => $mes) {
			if ($anio == $anioActual && ($index + 1) > $mesActual) {
				break; // se detiene en el mes actual
			}
			$meses_labels[] = $mes . ' ' . $anio;
		}
	}

	$data = [
		"labels" => $meses_labels,
		"datasets" => $datasets,
	];

	$tipo_grafico = 'bar';
}

// COMPRAS LISO / BASE
if ($_GET['muestra'] == 'compras_x_cliente') {
	$sql = "SELECT pr.es_variante, compras.cant_compras, SUM(dp.cantidad) AS Cantidad
				FROM pedidos pe
				JOIN detalles_pedido dp ON pe.id = dp.id_pedido
				JOIN productos pr ON dp.id_prod = pr.id
				JOIN (SELECT id_cliente, COUNT(id) AS n_compras,
						CASE
							WHEN COUNT(id) = 1 THEN '1 compra'
							ELSE '+1 compra'
						END AS cant_compras
						FROM pedidos
						WHERE cancelado = 0
						GROUP BY id_cliente
					) AS compras ON pe.id_cliente = compras.id_cliente
				WHERE pe.cancelado = 0 AND pr.es_variante IN (3, 4)
				GROUP BY pr.es_variante, compras.cant_compras
				ORDER BY pr.es_variante, compras.cant_compras;";

	$result = consulta($sql, $conn);

	$datos = [
		'1 compra' => ['3' => 0, '4' => 0],
		'+1 compra' => ['3' => 0, '4' => 0]
	];

	while ($row = $result->fetch_assoc()) {
		$datos[$row['cant_compras']][$row['es_variante']] = $row['Cantidad'];
	}


	$datasets = [
		[
			"label" => "Smartcret Base (Única compra)",
			"data" => [$datos['1 compra']['3']],
			"backgroundColor" => "#46bcf6",
		],
		[
			"label" => "Smartcret Liso (Única compra)",
			"data" => [$datos['1 compra']['4']],
			"backgroundColor" => "#d7ff07",
		],
		[
			"label" => "Smartcret Base (2das compras)",
			"data" => [$datos['+1 compra']['3']],
			"backgroundColor" => "#176aec",
		],
		[
			"label" => "Smartcret Liso (2das compras)",
			"data" => [$datos['+1 compra']['4']],
			"backgroundColor" => "#ffc107",
		]
	];

	$data = [
		"labels" => ["Total de ventas por ''Clientes de 1 compra'' vs ''Clientes de +1 compra''"],
		"datasets" => $datasets
	];

	$tipo_grafico = 'bar';
}

/////// IMPORTE VENTAS MESES ///////
if ($_GET['muestra'] == 'importes') {

	$periodo="";

	switch ( $_GET['periodo'] ) {

		case 'historico':

			$periodo="('2022', '2023', '2024')";

			break;

		default:

			$periodo="('" . $_GET['periodo'] . "')";

			break;
	}

	$sql_importes = "SELECT ROUND( SUM(total_pagado), 2) as importe, YEAR(fecha_creacion) as anio, MONTH(fecha_creacion) as mes FROM pedidos WHERE YEAR(fecha_creacion) IN " . $periodo . " AND cancelado=0 GROUP BY YEAR(fecha_creacion), MONTH(fecha_creacion)";

	$res_importes = consulta($sql_importes, $conn);
	$importes = array();
	$mes = array();
	while ($reg = $res_importes->fetch_object()) {
		array_push($importes, $reg->importe);
		array_push( $mes, $meses[$reg->mes] . ' ' . $reg->anio );
	}

	$data = array(
		"labels" => $mes,
		"datasets" => array(
			array(
				"label" => "Importe Ventas",
				"data"	=> $importes,
				"backgroundColor" => $color_importes
			)
		)
	);
}

//// COLORES ////
if ($_GET['muestra'] == 'colores') {

	$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'historico'; // Asigna un valor por defecto si 'periodo' no está definido.
	$sql_colores = '';

	switch ($periodo) {
		case 'historico':
			$sql_colores = "SELECT * FROM `listado_colores` C, colores_hex H WHERE C.COLOR = H.color";
			break;

		case '2023':
			$sql_colores = "SELECT * FROM `listado_colores_2023` C, colores_hex H WHERE C.COLOR = H.color";
			break;

		case '2024':
			$sql_colores = "SELECT * FROM `listado_colores_2024` C, colores_hex H WHERE C.COLOR = H.color";
			break;

		default:
			// Asignar una consulta SQL por defecto en caso de que no coincida con ninguno de los casos anteriores
			$sql_colores = "SELECT * FROM `listado_colores` C, colores_hex H WHERE C.COLOR = H.color";
			break;
	}

	// Verificar si $sql_colores tiene una consulta válida antes de proceder
	if ($sql_colores) {
		$res_colores = consulta($sql_colores, $conn);
		$importes = array();
		$colores = array();
		$back_colores = array();
		while ($reg = $res_colores->fetch_object()) {
			array_push($importes, $reg->kits);
			array_push($colores, $reg->COLOR);
			array_push($back_colores, $reg->hex);
		}

		$data = array(
			"labels" => $colores,
			"datasets" => array(
				array(
					"label" => "Colores",
					"data"  => $importes,
					"backgroundColor" => $back_colores
				)
			)
		);

		$tipo_grafico = 'bar';
	} else {
		echo "No se pudo generar la consulta SQL.";
	}
}

////// COLORES MENSUAL //////
if ($_GET['muestra'] == 'colores_mensual_todos') {

	$colorDeseado = isset($_GET['colorDeseado']) ? $_GET['colorDeseado'] : 'Todos';

	if ($colorDeseado != 'Todos') {
		$filtroColor = "AND a.valor = '$colorDeseado'";
	} else {
		$filtroColor = ""; // sin filtro de color
	}

	$sql_colores = "SELECT DATE_FORMAT(p.fecha_creacion, '%Y-%m') AS Fecha, a.valor AS Color, h.hex AS ColorHex, COUNT(dp.id) AS Apariciones, SUM(dp.cantidad) AS CantidadVendida
					FROM detalles_pedido dp
					JOIN pedidos p ON dp.id_pedido = p.id
					JOIN productos pr ON dp.id_prod = pr.id
					JOIN atributos a ON pr.color = a.id_atributo
					JOIN colores_hex h ON a.valor = h.color
					WHERE p.cancelado = 0
					AND a.activo = 1
					AND a.id_idioma = 1
					AND pr.es_variante IN (1, 2, 812, 813, 815, 816, 981, 982)
					AND p.fecha_creacion >= '2023-01-01'
					$filtroColor
					GROUP BY DATE_FORMAT(p.fecha_creacion, '%Y-%m'), a.valor
					ORDER BY a.valor, DATE_FORMAT(p.fecha_creacion, '%Y-%m') ASC;";

	$res = consulta($sql_colores, $conn);

	$datasets = [];
	$fechasUnicas = [];

	if ($res) {
		while ($fila = $res->fetch_assoc()) {
			if (!array_key_exists($fila['Color'], $datasets)) {
				$datasets[$fila['Color']] = ['fechas' => [], 'cantidades' => [], 'colorHex' => $fila['ColorHex']];
			}

			$datasets[$fila['Color']]['fechas'][] = $fila['Fecha'];
			$datasets[$fila['Color']]['cantidades'][] = $fila['CantidadVendida'];

			if (!in_array($fila['Fecha'], $fechasUnicas)) {
				$fechasUnicas[] = $fila['Fecha'];
			}
		}
	}

	sort($fechasUnicas);

	$data['labels'] = $fechasUnicas;
	$data['datasets'] = [];


	foreach ($datasets as $color => $info) {
		// Inicializa todos los puntos de datos con 0 para cada mes en el rango
		$dataPoints = array_fill(0, count($fechasUnicas), 0);

		foreach ($info['fechas'] as $index => $fecha) {
			$posicion = array_search($fecha, $fechasUnicas);
			if ($posicion !== false) {
				$dataPoints[$posicion] = $info['cantidades'][$index]; // Asigna la cantidad vendida real
			}
		}

		$data['datasets'][] = [
			"label" => "Color " . $color,
			"data" => $dataPoints,
			"backgroundColor" => $info['colorHex'],
			"borderColor" => $info['colorHex'],
			"fill" => false,
			"pointStyle" => 'circle',
			"pointRadius" => 5,
			"pointHoverRadius" => 10
		];
	}

	$tipo_grafico = isset($_GET['tipoGrafico']) ? $_GET['tipoGrafico'] : 'line';
}

////// VENTAS PROVINCIA //////
if ($_GET['muestra'] == 'provincia') {

	$sql_provincia = "SELECT PR.nombre_prov as Provincia, COUNT(P.id) as Pedidos, ROUND(SUM(P.total_pagado), 2) as Importe
                      FROM pedidos P
                      JOIN pedidos_dir_envio E ON P.id_envio = E.id
                      JOIN provincias PR ON E.provincia = PR.id_prov
                      WHERE P.cancelado = 0
                      AND E.pais = 'ES'
                      GROUP BY E.provincia
                      ORDER BY Importe ASC;";

	$res_provincia = consulta($sql_provincia, $conn);
	$importes = array();
	$provincias = array();
	$back_provincia = array();
	while ($reg = $res_provincia->fetch_object()) {
		array_push($importes, $reg->Importe);
		array_push($provincias, $reg->Provincia);
	}

	$data = array(
		"labels" => $provincias,
		"datasets" => array(
			array(
				"label" => "Ventas provincia",
				"data"  => $importes,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}

////// VENTAS ESTADO (USA) ///////
if ($_GET['muestra'] == 'estados') {
	$sql_estado = "SELECT S.nombre AS Estado, COUNT(P.id) AS Pedidos, ROUND(SUM(P.total_pagado), 2) AS Importe
                   FROM pedidos P
                   JOIN pedidos_dir_envio E ON P.id_envio = E.id
                   JOIN sc_estados_us S ON E.provincia = S.cod
                   WHERE P.cancelado = 0
                   AND E.pais = 'US'
                   GROUP BY S.nombre
                   ORDER BY Importe ASC;";
	$res_estado = consulta($sql_estado, $conn);
	$importes = array();
	$estados = array();
	$back_estado = array();
	while ($reg = $res_estado->fetch_object()) {
		array_push($importes, $reg->Importe);
		array_push($estados, $reg->Estado);
	}

	$data = array(
		"labels" => $estados,
		"datasets" => array(
			array(
				"label" => "Ventas por estado",
				"data" => $importes,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	// Consulta para mostrar los estados sin pedidos
	$sql_estados_sin_pedidos = "SELECT S.nombre AS Estado
                                FROM sc_estados_us S
                                LEFT JOIN (
                                    SELECT E.provincia
                                    FROM pedidos P
                                    JOIN pedidos_dir_envio E ON P.id_envio = E.id
                                    WHERE P.cancelado = 0 AND E.pais = 'US'
                                    GROUP BY E.provincia
                                ) AS SubqueryResult ON S.cod = SubqueryResult.provincia
                                WHERE SubqueryResult.provincia IS NULL;";
	$res_estados_sin_pedidos = consulta($sql_estados_sin_pedidos, $conn);
	$estados_sin_pedidos = array();
	while ($reg = $res_estados_sin_pedidos->fetch_object()) {
		array_push($estados_sin_pedidos, $reg->Estado);
	}

	$tipo_grafico = 'bar';
}

///// VENTAS PAIS /////
if ($_GET['muestra'] == 'ventas_pais') {

	$sql_pais = "SELECT PR.nombre as Pais, COUNT(P.id) as Pedidos, ROUND(SUM(P.total_pagado), 2) as Importe
                 FROM pedidos P
                 JOIN pedidos_dir_envio E ON P.id_envio = E.id AND P.id = E.id_pedido
                 JOIN paises PR ON E.pais = PR.cod_pais
                 WHERE P.cancelado = 0
                 AND YEAR(P.fecha_creacion) IN ('2023', '2024')
                 GROUP BY E.pais
                 ORDER BY Importe ASC";

	$res_pais = consulta($sql_pais, $conn);
	$importes = array();
	$paises = array();
	$back_provincia = array();
	while ($reg = $res_pais->fetch_object()) {
		array_push($importes, $reg->Importe);
		array_push($paises, $reg->Pais . " | " . $reg->Pedidos . ' ped');
	}

	$data = array(
		"labels" => $paises,
		"datasets" => array(
			array(
				"label" => "Ventas país",
				"data"  => $importes,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}

///// VENTAS MENSUAL PAIS /////
if ($_GET['muestra'] == 'ventas_pais_mes') {

	$sql_pais = "SELECT PR.nombre AS Pais, YEAR(P.fecha_creacion) AS Ano, MONTH(P.fecha_creacion) AS Mes, COUNT(P.id) AS Pedidos, ROUND(SUM(P.total_pagado), 2) AS Importe, ROUND((SUM(P.total_pagado) / T.TotalMes) * 100, 2) AS Porcentaje
                 FROM pedidos P
                 JOIN pedidos_dir_envio E ON P.id_envio = E.id
                 JOIN paises PR ON E.pais = PR.cod_pais
                 JOIN (SELECT YEAR(fecha_creacion) AS Ano, MONTH(fecha_creacion) AS Mes, SUM(total_pagado) AS TotalMes
                       FROM pedidos
                       WHERE cancelado = 0 AND fecha_creacion >= '2023-01-01'
                       GROUP BY YEAR(fecha_creacion), MONTH(fecha_creacion)) T ON YEAR(P.fecha_creacion) = T.Ano AND MONTH(P.fecha_creacion) = T.Mes
                 WHERE P.cancelado = 0 AND P.fecha_creacion >= '2023-01-01'
                 GROUP BY PR.nombre, YEAR(P.fecha_creacion), MONTH(P.fecha_creacion)
                 ORDER BY PR.nombre, YEAR(P.fecha_creacion), MONTH(P.fecha_creacion);";

	$res_pais = consulta($sql_pais, $conn);
	$datos = [];

	while ($reg = $res_pais->fetch_object()) {
		$clavePais = $reg->Pais;

		if (!isset($datos[$clavePais])) {
			$datos[$clavePais] = ["labels" => [], "data" => [], "percentages" => []];
		}

		$etiquetaMes = $reg->Ano . '-' . sprintf("%02d", $reg->Mes);
		array_push($datos[$clavePais]["labels"], $etiquetaMes);
		array_push($datos[$clavePais]["data"], $reg->Importe);
		array_push($datos[$clavePais]["percentages"], $reg->Porcentaje);
	}

	// generación de etiquetas mensuales
	$mesActual = date('n');
	$anioActual = date('Y');
	$labels = [];
	for ($anio = 2023; $anio <= $anioActual; $anio++) {
		for ($mes = 1; $mes <= 12; $mes++) {
			if ($anio == $anioActual && $mes > $mesActual) {
				break;
			}
			$labels[] = $anio . '-' . sprintf("%02d", $mes);
		}
	}

	$datasets = [];
	$backgroundColor = ['#92bf23', '#3498db', '#e74c3c', '#be7dd9', '#e6af0c', '#ffd0d8'];
	$otrosPaisesColor = '#aaaaaa';
	$i = 0;
	$paisesDeInteres = ['España', 'Francia', 'United Kingdom', 'Italia', 'Estados Unidos', 'Alemania'];

	foreach ($paisesDeInteres as $pais) {
		if (isset($datos[$pais])) {
			$dataCompleta = array_fill(0, count($labels), 0); // inicializa en 0 para cada mes
			$percentagesCompleta = array_fill(0, count($labels), 0); // inicializa en 0 para cada porcentaje
			foreach ($datos[$pais]['labels'] as $index => $mes) {
				$posicion = array_search($mes, $labels); // posición mes
				if ($posicion !== false) {
					$dataCompleta[$posicion] = $datos[$pais]['data'][$index];
					$percentagesCompleta[$posicion] = $datos[$pais]['percentages'][$index];
				}
			}

			$datasets[] = [
				"label" => "Ventas $pais ",
				"data"  => $dataCompleta,
				"backgroundColor" => $backgroundColor[$i % count($backgroundColor)],
				"percentages" => $percentagesCompleta
			];
		}
		$i++;
	}

	// datos otros paises
	$otrosPaises = array_fill(0, count($labels), 0);
	$otrosPaisesPercentajes = array_fill(0, count($labels), 0); // Almacena los porcentajes para otros países

	foreach ($datos as $pais => $info) {
		if (!in_array($pais, $paisesDeInteres)) {
			foreach ($info['labels'] as $index => $mesLabel) {
				$posicionMes = array_search($mesLabel, $labels);
				if ($posicionMes !== false) {
					$otrosPaises[$posicionMes] += $info['data'][$index]; // suma importes de otros paises
					$otrosPaisesPercentajes[$posicionMes] += $info['percentages'][$index]; // suma porcentajes
				}
			}
		}
	}

	// Añade 'Otros Países' al conjunto de datos para el gráfico
	$datasets[] = [
		"label" => "Ventas Otros Países",
		"data"  => $otrosPaises,
		"backgroundColor" => $otrosPaisesColor,
		"percentages" => $otrosPaisesPercentajes
	];

	$data = [
		"labels" => $labels,
		"datasets" => $datasets
	];

	$tipo_grafico = 'bar';
}

// NEWSLETTER / SEMANA
if ($_GET['muestra'] == 'newsletter_semana') {

	$sql_newsletter = "SELECT WEEK(fecha_actualizacion) as semana, COUNT(*) as users FROM newsletter WHERE YEAR(fecha_actualizacion) BETWEEN '2023' AND YEAR(CURRENT_DATE()) GROUP BY WEEK(fecha_actualizacion) ORDER BY fecha_actualizacion ASC;";

	$res_newsletter = consulta($sql_newsletter, $conn);
	$semana = array();
	$usuarios = array();
	$back_newsletter = array();
	while ($reg = $res_newsletter->fetch_object()) {
		array_push($usuarios, $reg->users);
		array_push($semana, $reg->semana);
	}

	$data = array(
		"labels" => $semana,
		"datasets" => array(
			array(
				"label" => "Usuarios newsletter semana",
				"data"	=> $usuarios,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}

// NEWSLETTER / DIA
if ($_GET['muestra'] == 'newsletter_dia') {

	$sql_newsletter = 'SELECT DATE_FORMAT(fecha_actualizacion, "%m/%d/%Y") as dia, COUNT(id) as users FROM `newsletter` WHERE YEAR(fecha_actualizacion) BETWEEN "2023" AND YEAR(CURRENT_DATE()) GROUP BY DATE_FORMAT(fecha_actualizacion, "%m/%d/%Y") ORDER BY fecha_actualizacion ASC;';

	$res_newsletter = consulta($sql_newsletter, $conn);
	$dias = array();
	$usuarios = array();
	$back_newsletter = array();
	while ($reg = $res_newsletter->fetch_object()) {
		array_push($usuarios, $reg->users);
		array_push($dias, cambia_fecha_slash($reg->dia));
	}

	$data = array(
		"labels" => $dias,
		"datasets" => array(
			array(
				"label" => "Usuarios newsletter día",
				"data"	=> $usuarios,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}

// NEWSLETTER PAIS
if ($_GET['muestra'] == 'newsletter_pais') {

	$sql_newsletter = "SELECT pais, count(id) as users FROM `newsletter` WHERE YEAR(fecha_actualizacion) BETWEEN '2023' AND YEAR(CURRENT_DATE()) AND pais != '' GROUP BY pais ORDER BY users ASC";

	$res_newsletter = consulta($sql_newsletter, $conn);
	$pais = array();
	$usuarios = array();
	$back_newsletter = array();
	while ($reg = $res_newsletter->fetch_object()) {
		array_push($usuarios, $reg->users);
		array_push($pais, $reg->pais);
	}

	$data = array(
		"labels" => $pais,
		"datasets" => array(
			array(
				"label" => "Usuarios por pais",
				"data"	=> $usuarios,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}

// REGISTRO USUARIOS DIA
if ($_GET['muestra'] == 'registro_usuarios') {

	$sql_newsletter = 'SELECT DATE_FORMAT(fecha_alta, "%m/%d/%Y") as dia, COUNT(uid) as users FROM `users` WHERE YEAR(fecha_alta) BETWEEN "2023" AND YEAR(CURRENT_DATE()) GROUP BY DATE_FORMAT(fecha_alta, "%m/%d/%Y") ORDER BY fecha_alta ASC;';

	$res_newsletter = consulta($sql_newsletter, $conn);
	$dias = array();
	$usuarios = array();
	$back_newsletter = array();
	while ($reg = $res_newsletter->fetch_object()) {
		array_push($usuarios, $reg->users);
		array_push($dias, cambia_fecha_slash($reg->dia));
	}

	$data = array(
		"labels" => $dias,
		"datasets" => array(
			array(
				"label" => "Registros usuarios",
				"data"	=> $usuarios,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}

// VENTAS DIARIAS
if (isset($_GET['muestra']) && $_GET['muestra'] == 'importes_dia') {

	$dias_semana = array('', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');

	$meses = (isset($_GET['meses'])) ? (int)$_GET['meses'] : 2;

	$sql_importes_dia = 'SELECT DATE_FORMAT(p.fecha_creacion, "%Y-%m-%d") as dia,
                                ROUND(SUM(p.total_pagado), 2) as total,
                                COUNT(p.id) as num_ped
                         FROM pedidos p
                         LEFT JOIN pedidos_cupones_aplicados pca ON p.cupon_id = pca.id
                         WHERE p.cancelado = 0
                         AND p.fecha_creacion BETWEEN DATE_SUB(NOW(), INTERVAL ? MONTH) AND NOW()
                         GROUP BY DATE_FORMAT(p.fecha_creacion, "%Y-%m-%d")';

	$stmt = $conn->prepare($sql_importes_dia);
	$stmt->bind_param('i', $meses);
	$stmt->execute();
	$res_importes_dia = $stmt->get_result();

	$dias = array();
	$total = array();
	$back_importes_dia = array();

	while ($reg = $res_importes_dia->fetch_object()) {
		$num_kits = obten_num_kits_dia($reg->dia);
		$cupon = obten_codigo_activo($reg->dia);
		$hay_cupon = (strlen($cupon) > 4) ? ' - [' . $cupon . ']' : '';
		// $hay_cupon = (strlen($cupon) > 4) ? ' - [' . $cupon . ']' : '[No utilizado cupón]';

		$ped = ($reg->num_ped == 1) ? ' pedido' : ' pedidos';
		array_push($total, $reg->total);
		array_push($dias, $dias_semana[date('N', strtotime($reg->dia))] . ' [' . cambia_fecha_slash($reg->dia) . '] - ' . $reg->num_ped . $ped . ' - ' . $num_kits . ' kits' . $hay_cupon);
	}

	$data = array(
		"labels" => $dias,
		"datasets" => array(
			array(
				"label" => "Ventas",
				"data"  => $total,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}

// VENTAS CUPONES
if ($_GET['muestra'] == 'importes_cupones') {

	$sql_importe_cupones = "SELECT pca.nombre_descuento,
                                   COUNT(pca.nombre_descuento) AS num,
                                   ROUND(SUM(p.total_pagado), 2) AS cupon_importe
                            FROM pedidos p
                            INNER JOIN pedidos_cupones_aplicados pca ON p.cupon_id = pca.id
                            WHERE p.cancelado = 0
                              AND pca.nombre_descuento IS NOT NULL
                              AND pca.nombre_descuento != ''
                            GROUP BY pca.nombre_descuento
                            ORDER BY num DESC;";

	$res_importe_cupones = consulta($sql_importe_cupones, $conn);
	$cupones = array();
	$importe = array();
	$back_importe_cupones = array();
	while ($reg = $res_importe_cupones->fetch_object()) {
		array_push($importe, $reg->cupon_importe); // Cambié a $reg->cupon_importe en lugar de $reg->importe_descuento
		array_push($cupones, $reg->nombre_descuento . ' [' . $reg->num . ' pedidos]');
	}

	$data = array(
		"labels" => $cupones,
		"datasets" => array(
			array(
				"label" => "Venta descuento: ",
				"data"  => $importe,
				"backgroundColor" => '#92bf23'
			)
		)
	);

	$tipo_grafico = 'bar';
}


////// FACTURACIÓN ANUAL ////////
if ($_GET['muestra'] == 'facturacion_anual') {

    $sql_newsletter = 'SELECT YEAR(fecha_creacion) AS anio, ROUND( SUM( total_pagado ), 2 ) AS total_pagado_anual FROM pedidos GROUP BY YEAR(fecha_creacion)';

    $res_newsletter = consulta($sql_newsletter, $conn);

    $reg = $res_newsletter->fetch_all();
    // var_dump( $reg );
    // exit;

    // $reg = $res_newsletter->fetch_object();

    $anio = array();
    $importe = array();


    // while ($reg = $res_newsletter->fetch_object()) {
    //     array_push($anio, $reg->anio);
    //     array_push($importe, $reg->total_pagado_anual);
    // }

    foreach ($reg as $key => $value) {
    	// echo $key . ' -> ' . $value[1];
    	// echo "<br>";
    	array_push( $anio, $value[0] . " - ( " . number_format($value[1], 2, ',', '.') . "€ )" );
      array_push( $importe, $value[1] );
    }



    	// array_push($anio, $reg['anio']);
     //  array_push($importe, $reg['total_pagado_anual']);



    // var_dump($anio);

    $data = array(
        "labels" => $anio,
        "datasets" => array(
            array(
                "label" => "Importe facturado",
                "data" => $importe,
                "backgroundColor" => '#92bf23',
                "barThickness" => 80
            )
        )
    );

    $tipo_grafico = 'bar';
}

$datos_grafico = json_encode($data);


?>

<body class="estadisticas listado-pedidos">
	<style>
		button.print_btn {
			margin-bottom: 10px;
		}
	</style>
	<style>
		.estados-sin-pedidos {
			background-color: #b1b1b17a;
			border-radius: 5px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			padding: 20px;
			max-width: 350px;
		}

		.estados-sin-pedidos ul {
			display: flex;
			flex-wrap: wrap;
			list-style: none;
			padding: 0;
			margin: 20px 0 0 0;
		}

		.estados-sin-pedidos ul li {
			flex: 1 0 45%;
			margin: 5px;
		}
	</style>
	<!-- Header - Inicio -->
	<div class="container">
		<div class="row">
			<div class=".col-md-10 off-set-1">

			</div>
		</div>
	</div>
	<!-- Header - Fin -->
	<div class="container listado datos pedidos princ">

		<div class="row">
			<?php include('./includes/menu_lateral.php') ?>
			<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
			<div class="col-md-1">
				<div class="sepv"></div>
			</div>
			<div class="col-md-9">
				<h1 class="center"><?php echo $pagina . ' ' .  $_GET['muestra'] ?></h1>

				<div class="btns_funciones">

					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=importes&periodo=2024'">Importe Ventas Meses<i class="fas fa-euro-sign"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=pedidos'">Pedidos<i class="fas fa-truck-moving"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=registro_usuarios'">Registro Usuarios Día<i class="fas fa-users"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=colores'">Colores<i class="fas fa-palette"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=colores_mensual_todos'">Colores Mensual<i class="fas fa-palette"></i></button>
					<br>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=compras_x_cliente'">Compras Liso / Base<i class="fas fa-history"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=newsletter_semana'">Newsletter / Semana<i class="fa fa-calendar"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=newsletter_dia'">Newsletter / Día<i class="fa fa-calendar"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=newsletter_pais'">Newsletter País<i class="fa fa-globe-americas"></i></button>
					<br>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=ventas_pais'">Ventas país<i class="fa fa-globe-americas"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=provincia'">Ventas Provincia<i class="fa fa-map-marker-alt"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=estados'">Ventas Estado<i class="fa fa-map-marker-alt"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=importes_cupones'">Ventas Cupones<i class="far fa-credit-card"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=kits'">Venta Kits<i class="fas fa-box"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=total_productos'">Cantidad Venta Productos<i class="fas fa-box"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=ventas_pais_mes'">Ventas Mensual País<i class="fa fa-globe-americas"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=importes_dia'">Ventas Diarias<i class="fas fa-history"></i></button>
					<button class="print_btn me-2" onclick="window.location.href='estadisticas?muestra=facturacion_anual'">Facturación anual<i class="far fa-money-bill-alt"></i></button>

				</div>



				<?php if (isset($_GET['muestra']) && $_GET['muestra'] == 'importes_dia') { ?>
					<div class="sep20"></div>
					<div style="text-align: center;">
						<label>Mostrar meses: </label>
						<select name="meses" id="meses">
							<?php
							for ($i = 1; $i <= 12; $i++) {
								$selected = ($meses == $i) ? 'selected' : '';
								echo "<option value=\"$i\" $selected>$i</option>";
							}
							?>
						</select>
					</div>
					<div class="sep30"></div>

					<script>
						$(function() {
							$('#meses').on('change', function() {
								var url = 'estadisticas?muestra=importes_dia&meses=' + $(this).val(); // get selected value
								if (url) {
									window.location = url;
								}
								return false;
							});
						});
					</script>
				<?php } ?>




<?php /***************/ ?>



				<?php if (isset($_GET['muestra']) && $_GET['muestra'] == 'importes') { ?>
					<div class="sep20"></div>
					<div style="text-align: center;">
						<label>Años: </label>
						<select name="periodo" id="periodo">
								<option value="historico" <?php if($_GET['periodo']=='historico') echo 'selected' ?>>Historico</option>";
								<option value="2024" <?php if($_GET['periodo']=='2024') echo 'selected' ?>>2024</option>";
								<option value="2023" <?php if($_GET['periodo']=='2023') echo 'selected' ?>>2023</option>";
								<option value="2022" <?php if($_GET['periodo']=='2022') echo 'selected' ?>>2022</option>";
							?>
						</select>
					</div>
					<div class="sep30"></div>

					<script>
						$(function() {
							$('#periodo').on('change', function() {
								var url = 'estadisticas?muestra=importes&periodo=' + $(this).val();
								if (url) {
									window.location = url;
								}
								return false;
							});
						});
					</script>
				<?php } ?>


				<?php if ($_GET['muestra'] == 'colores_mensual_todos') { ?>

					<div class="sep20"></div>
					<div style="text-align: center;">
						<label for="colorDeseado">Seleccione un color:</label>
						<select name="colorDeseado" id="colorDeseado">
							<option value="Todos" <?php echo ($colorDeseado == 'Todos') ? 'selected' : ''; ?>>Todos</option>
							<?php
							$sql_activos = "SELECT valor FROM atributos WHERE activo = 1 AND nombre = 'color' AND LENGTH(valor) = 2 AND id_idioma = 1;";
							$res = consulta($sql_activos, $conn);

							if ($res && $res->num_rows > 0) {
								while ($fila = $res->fetch_assoc()) {
									$valor = $fila['valor'];
									$selected = ($colorDeseado == $valor) ? 'selected' : '';
									echo "<option value='$valor' $selected>$valor</option>";
								}
							}
							?>
						</select>

						<label for="tipoGrafico">Tipo de Gráfico:</label>
						<select name="tipoGrafico" id="tipoGrafico">
							<option value="line" <?php echo $tipo_grafico == 'line' ? 'selected' : ''; ?>>Línea</option>
							<option value="bar" <?php echo $tipo_grafico == 'bar' ? 'selected' : ''; ?>>Barra</option>
						</select>
					</div>

					<div class="sep30"></div>

					<script>
						$(function() {
							$('#colorDeseado').on('change', function() {
								var url = 'estadisticas?muestra=colores_mensual_todos&colorDeseado=' + $(this).val() + '&tipoGrafico=' + $('#tipoGrafico').val() + '&t=' + new Date().getTime();
								if (url) { // require a URL
									window.location = url; // redirect
								}
								return false;
							});
						});

						// script tipo de gráfico
						document.getElementById('tipoGrafico').addEventListener('change', function() {
							var tipoGrafico = this.value;
							var url = new URL(window.location.href);
							url.searchParams.set('tipoGrafico', tipoGrafico); // actualiza el parametro tipoGrafico en la URL
							window.location.href = url; // redirige a la nueva URL
						});
					</script>

				<?php } ?>

				<?php if ($_GET['muestra'] == 'colores') { ?>
					<div class="sep20"></div>
					<h3 style="color: #35471e">Colores de kits más vendidos</h3>
					<div class="sep30"></div>
					<div class="sep20"></div>
					<div style="text-align: center;">
						<label>Periodo: </label>
						<select name="periodo" id="periodo">
							<option value="historico" <?php echo ($periodo == 'historico') ? 'selected' : ''; ?>>Historico</option>
							<option value="2023" <?php echo ($periodo == '2023') ? 'selected' : ''; ?>>2023</option>
							<option value="2024" <?php echo ($periodo == '2024') ? 'selected' : ''; ?>>2024</option>
						</select>
						<script>
							$(function() {
								$('#periodo').on('change', function() {
									var url = 'estadisticas?muestra=colores&periodo=' + $(this).val();
									if (url) {
										window.location = url;
									}
									return false;
								});
							});
						</script>
					</div>
					<div class="sep30"></div>
				<?php } ?>

				<canvas id="grafica"></canvas>
				<canvas id="grafica2"></canvas>
				<canvas id="grafica3"></canvas>

				<div class="sep50"></div>

				<?php if ($_GET['muestra'] == 'colores') {
					$sql_colores_no = "SELECT DISTINCT A.valor AS color_id, H.hex
                       FROM productos P
                       JOIN atributos A ON P.color = A.id
                       JOIN colores_hex H ON A.valor = H.color
                       WHERE P.publicado = 1
                       AND P.es_variante IN (1, 2, 812, 813, 815, 816, 981, 982)
                       AND A.valor NOT IN (SELECT c.valor
                                           FROM detalles_pedido dp
                                           JOIN pedidos p ON dp.id_pedido = p.id
                                           JOIN productos pr ON dp.id_prod = pr.id
                                           JOIN atributos c ON pr.color = c.id_atributo
                                           WHERE c.activo = 1
                                           AND c.id_idioma = 1
                                           GROUP BY c.valor)
                       AND A.activo = 1
                       AND A.id_idioma = 1";

					$res_colores_no = consulta($sql_colores_no, $conn);
				?>
					<div class="colores_no_vendidos">
						<h3 style="color: #35471e">Colores no vendidos</h3>
						<div class="sep20"></div>
						<div class="row">
							<?php while ($reg = $res_colores_no->fetch_object()) {
								echo "<div class='col-md-2'>";
								echo '<div class="item"><img class="colore_no" src="../assets/img/colores/' . $reg->color_id . '.jpg"><div class="col_nom">' . $reg->color_id . '</div> </div>';
								echo "</div>";
							} ?>
						</div>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>
	<div class="sep40"></div>

	<div class="modal fade" id="pedidos-cliente">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<?php if ($_GET['muestra'] == 'estados') { ?>
		<div style="display:flex;justify-content:center;">
			<div class="estados-sin-pedidos">
				<h3>Estados sin pedidos</h3>
				<ul>
					<?php foreach ($estados_sin_pedidos as $estado) {
						echo "<li>$estado</li>";
					} ?>
				</ul>
			</div>
		</div>
	<?php } ?>


	<?php include('./includes/footer.php') ?>

	<div class="sep60"></div>

</body>

</html>

<script>
	$('.item.estad').addClass('menu_actual');
	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}

	setTimeout(function() {
		$('.print_btn').appendTo('.dt-buttons').addClass('dt-button buttons-html5');
	}, 1000);

	function checkScroll(scroll) {
		if (scroll > 120) {
			var left = $('.menu-lat').width() + $(window).width() * 0.09;
			$('h1').addClass("fixed borde_sombra").css('left', left + 'px');
		} else {
			$('h1').removeClass("fixed borde_sombra");
		}
	}

	const labels = <?php echo json_encode($label_meses) ?>;
	const graph = document.querySelector("#grafica");

	const data = <?php echo $datos_grafico ?>;

	const config = {
		type: '<?php echo $tipo_grafico; ?>',
		data: data,
		options: {
			plugins: {
				ingraphdatashow: true,
				legend: {
					labels: {
						// This more specific font property overrides the global property
						font: {
							size: 20
						}
					}
				}
			},
		}
	}

	<?php if ($_GET['muestra'] == 'ventas_pais_mes') { ?>
		// Específico para el gráfico de ventas por país y mes
		config.options.plugins.tooltip = {
			callbacks: {
				label: function(context) {
					let label = context.dataset.label || '';
					let value = context.raw;
					let percentage = context.dataset.percentages[context.dataIndex];
					return `${label}: ${value} (${percentage}%)`;
				}
			}
		};
	<?php } ?>

	new Chart(graph, config);
</script>