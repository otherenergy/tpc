<?php
include_once(dirname ( dirname ( __DIR__ ) ) . '/assets/lib/bbdd.php');
include_once(dirname ( dirname ( __DIR__ ) ) . '/assets/lib/class.carrito.php');
include_once(dirname ( dirname ( __DIR__ ) ) . '/assets/lib/funciones.php');
include_once ( '../../includes/array_precios.php' );


if(isset($_REQUEST['input_presupuesto']) && $_REQUEST['input_presupuesto']=='1') {
	$res=array();
	$post_keys = array_keys($_REQUEST);
	foreach( $post_keys as $key ) {
		$$key = $_REQUEST[$key];
		// echo $key . ' - ' . $$key . "<br>";
	}
}

$tipo_presupuesto = array(
													'1' => 'Microcemento',
													'2' => 'Pintura para azulejos',
													'3' => 'Mantenimiento / reparación de hormigón impreso'
												);


/* presupuesto de microcemento */
if ( $input_tipo_pres == 1 ) {

$tipo_superficie = array(
													'1' => 'Superficies Absorbentes ( Yeso, pladur, etc )',
													'2' => 'Superficies NO Absorbentes ( Azulejo, gres, terrazo )'
												);
$input_m2 = (float)str_replace ( ',' , '.' , $input_m2);
$variante = $input_tipo_superficie;
$color = $input_id_color;
$acabado = $input_tipo_acabado;
$juntas = $input_tipo_juntas;

$presupuesto=array();


/* calculo de kits */

$num_kits = intdiv($input_m2, 8);
$metros_sobrantes = $input_m2 % 8;

if ( $metros_sobrantes >= 6 ) {
	$num_kits++;
	$metros_sobrantes = 0;
}

if ($num_kits > 0) {

$kit = obten_producto ( null, $variante, $color, $acabado, $juntas );

$presupuesto['smart_kit'] = array (
																		"nombre" => $kit->nombre_es,
																		"cantidad" => $num_kits,
																		"precio" => $kit->precio_es,
																		"color" => $kit->color,
																		"acabado" => $kit->acabado,
																		"img" => $kit->miniatura,
																		"id" => $kit->id
																	);
// var_dump($presupuesto['smart_kit']);
}

if ( $input_herramientas == 1 ) {

$idProd=430;
$kit_herramientas = obten_producto ( $idProd, null, null, null, null );

$presupuesto['kit_herramientas'] = array (
																					"cantidad" => 1,
																					"nombre" => $kit_herramientas->nombre_es,
																					"precio" => $kit_herramientas->precio_es,
																					"color" => $kit_herramientas->color,
																					"acabado" => $kit_herramientas->acabado,
																					"img" => $kit_herramientas->miniatura,
																					"id" => $kit_herramientas->id
																					);
}

/* Consumo por capa 0,9 kg/m2 0,250 kg/m2 */

if ( $metros_sobrantes > 0 ) {

/* Base */
$variante=3;
$base = obten_producto ( null, $variante, $color, null, null );
$capas_base = 2;
$rendimiento_base = 0.9; /* kgs/m2 */
$capacidad_envase_base = 6; /* kgs */

$envases_base = ceil ( ( $metros_sobrantes * $rendimiento_base * $capas_base ) / $capacidad_envase_base );

$presupuesto['smart_base'] = array (
																		"cantidad" => $envases_base,
																		"nombre" => $base->nombre_es,
																		"precio" => $base->precio_es,
																		"color" => $base->color,
																		"acabado" => $base->acabado,
																		"img" => $base->miniatura,
																		"id" => $base->id
																		);

/* Liso */
$variante=4;
$liso = obten_producto ( null, $variante, $color, null, null );
$capas_liso = 2;
$rendimiento_liso = 0.25; /* kgs/m2 */
$capacidad_envase_liso = 6; /* kgs */

$envases_liso = ceil ( ( $metros_sobrantes * $rendimiento_liso * $capas_liso ) / $capacidad_envase_liso );

$presupuesto['smart_liso'] = array (
																		"cantidad" => $envases_liso,
																		"nombre" => $liso->nombre_es,
																		"precio" => $liso->precio_es,
																		"color" => $liso->color,
																		"acabado" => $liso->acabado,
																		"img" => $liso->miniatura,
																		"id" => $liso->id
																		);

/* Barniz */
$variante=8;
$barniz = obten_producto ( null, $variante, null, $acabado, null );
$capas_barniz = 3;
$rendimiento_barniz = 0.066667;  /* litros/m2 */
$capacidad_envase_barniz = 2; /* litros */

$envases_barniz = ceil ( ( $metros_sobrantes * $rendimiento_barniz * $capas_barniz ) / $capacidad_envase_barniz );

$presupuesto['smart_varnish'] = array (
																				"cantidad" => $envases_barniz,
																				"nombre" => $barniz->nombre_es,
																				"precio" => $barniz->precio_es,
																				"color" => $barniz->color,
																				"acabado" => $barniz->acabado,
																				"img" => $barniz->miniatura,
																				"id" => $barniz->id
																			);

if ( $input_tipo_superficie == 1 ) {

/* primer abs */
$variante=6;
$primer_abs = obten_producto ( null, $variante, null, null, null );
$rendimiento_primer_abs = 0.10; /* litros/m2 */
$capacidad_envase_primer_abs = 1; /* litros */

$envases_primer_abs = ceil ( ( $metros_sobrantes * $rendimiento_primer_abs ) / $capacidad_envase_primer_abs );

$presupuesto['smart_primer_abs'] = array (
																					"cantidad" => $envases_primer_abs,
																					"nombre" => $primer_abs->nombre_es,
																					"precio" => $primer_abs->precio_es,
																					"color" => $primer_abs->color,
																					"acabado" => $primer_abs->acabado,
																					"img" => $primer_abs->miniatura,
																					"id" => $primer_abs->id
																					);

$envases_primer_grip = 0;
$envases_smart_jointer = 0;

}else if ( $input_tipo_superficie == 2 ) {


/* primer grip */

$primer_grip = obten_producto ( 5, null, null, null, null );
$capas_primer_grip = 1;
$rendimiento_primer_grip = 0.12;  /* litros/m2 */
$capacidad_envase_primer_grip = 1.333334; /* litros */

$envases_primer_grip = ceil ( ( $metros_sobrantes * $rendimiento_primer_grip * $capas_primer_grip ) / $capacidad_envase_primer_grip );

$presupuesto['smart_primer_grip'] = array (
																						"cantidad" => $envases_primer_grip,
																						"nombre" => $primer_grip->nombre_es,
																						"precio" => $primer_grip->precio_es,
																						"color" => $primer_grip->color,
																						"acabado" => $primer_grip->acabado,
																						"img" => $primer_grip->miniatura,
																						"id" => $primer_grip->id
																					);

/* smart jointer */
$variante=7;
$smart_jointer = obten_producto ( null, $variante, null, null, $juntas );
$capas_smart_jointer = 1;
$rendimiento_smart_jointer = 0.125;  /* kgs/m2 */
$capacidad_envase_smart_jointer = 1; /* kilos */

$envases_smart_jointer = ceil ( ( $metros_sobrantes * $rendimiento_smart_jointer * $capas_smart_jointer ) / $capacidad_envase_smart_jointer );

$presupuesto['smart_jointer'] = array (
																				"cantidad" => $envases_smart_jointer,
																				"nombre" => $smart_jointer->nombre_es,
																				"precio" => $smart_jointer->precio_es,
																				"color" => $smart_jointer->color,
																				"acabado" => $smart_jointer->acabado,
																				"img" => $smart_jointer->miniatura,
																				"id" => $smart_jointer->id
																			);
$envases_primer_abs = 0;

}

/* lija 40 */
$idProd=16;
$lija_40 = obten_producto ( $idProd, null, null, null, null );

$presupuesto['lija_40'] = array (
																					"cantidad" => 1,
																					"nombre" => $lija_40->nombre_es,
																					"precio" => $lija_40->precio_es,
																					"img" => $lija_40->miniatura,
																					"id" => $lija_40->id
																					);

/* lija 220 */
$idProd=17;
$lija_220 = obten_producto ( $idProd, null, null, null, null );

$presupuesto['lija_220'] = array (
																					"cantidad" => 1,
																					"nombre" => $lija_220->nombre_es,
																					"precio" => $lija_220->precio_es,
																					"img" => $lija_220->miniatura,
																					"id" => $lija_220->id
																					);

/* lija 400 */
$idProd=18;
$lija_400 = obten_producto ( $idProd, null, null, null, null );

$presupuesto['lija_400'] = array (
																					"cantidad" => 1,
																					"nombre" => $lija_400->nombre_es,
																					"precio" => $lija_400->precio_es,
																					"img" => $lija_400->miniatura,
																					"id" => $lija_400->id
																					);

}

}

/******************** Presupuesto de pintura para azulejos ************************/

if ( $input_tipo_pres == 2 ) {

$tipo_superficie = array(
													'1' => 'Azulejos (cocina, baño, etc)',
													'2' => 'Otras superficies ( Yeso, pladur, etc )'
												);

/* calculo botes pintura */
$variante=23;
$color = $input_id_color;

$pintura = obten_producto ( null, $variante, $color, null, null );

$rendimiento_pintura = 0.15; /* litros/m2 */
$capacidad_envase_pintura = 2.5; /* litros */

$envases_pintura = ceil ( ( $input_m2 * $rendimiento_pintura ) / $capacidad_envase_pintura );

$presupuesto['smartcover'] = array (
																		"cantidad" => $envases_pintura,
																		"nombre" => $pintura->nombre_es,
																		"precio" => $pintura->precio_es,
																		"color" => $pintura->color,
																		"acabado" => $pintura->acabado,
																		"img" => $pintura->miniatura,
																		"id" => $pintura->id
																	 );

if ( $input_tipo_superficie == 2 ) {

/* primer abs */
$rendimiento_primer_abs = 0.10; /* litros/m2 */
$capacidad_envase_primer_abs = 1; /* litros */

$litros_abs = ceil ( $input_m2 * $rendimiento_primer_abs );

$envases_primer_abs_5l = intdiv($litros_abs, 5);
$envases_primer_abs_1l = $litros_abs % 5;

if ( $envases_primer_abs_5l > 0 ) {

$idProd=390;
$primer_abs_5l = obten_producto ( $idProd, null, null, null, null );

$presupuesto['smart_primer_abs_5l'] = array (
																					"cantidad" => $envases_primer_abs_5l,
																					"nombre" => $primer_abs_5l->nombre_es,
																					"precio" => $primer_abs_5l->precio_es,
																					"color" => $primer_abs_5l->color,
																					"acabado" => $primer_abs_5l->acabado,
																					"img" => $primer_abs_5l->miniatura,
																					"id" => $primer_abs_5l->id
																					);
}

if ( $envases_primer_abs_1l > 0 ) {

$idProd=389;
$primer_abs_1l = obten_producto ( $idProd, null, null, null, null );

$presupuesto['smart_primer_abs_1l'] = array (
																					"cantidad" => $envases_primer_abs_1l,
																					"nombre" => $primer_abs_1l->nombre_es,
																					"precio" => $primer_abs_1l->precio_es,
																					"color" => $primer_abs_1l->color,
																					"acabado" => $primer_abs_1l->acabado,
																					"img" => $primer_abs_1l->miniatura,
																					"id" => $primer_abs_1l->id
																					);
}

}

}


/******************** Presupuesto para reparación de hormigon impreso ************************/

if ( $input_tipo_pres == 3 ) {

$tipo_superficie = array(
													'1' => 'Mantenimiento de hormigón impreso ( solo barniz )',
													'2' => 'Reparación de hormigón impreso ( mortero reparador + barniz )'
												);

/* smartvarnish repair */
$idProd=395;

$varnish_repair = obten_producto ( $idProd, null, null, null, null );

$rendimiento_varnish_repair = 0.125; /* litros/m2 */
$capacidad_envase_varnish_repair = 5; /* litros */

$envases_varnish_repair = ceil ( ( $input_m2 * $rendimiento_varnish_repair ) / $capacidad_envase_varnish_repair );

$presupuesto['varnish_repair'] = array (
																		"cantidad" => $envases_varnish_repair,
																		"nombre" => $varnish_repair->nombre_es,
																		"precio" => $varnish_repair->precio_es,
																		"color" => $varnish_repair->color,
																		"acabado" => $varnish_repair->acabado,
																		"img" => $varnish_repair->miniatura,
																		"id" => $varnish_repair->id
																	 );

if ( $input_tipo_superficie == 2 ) {

/* smartcover repair */
$variante=396;
$color = $input_id_color;

$varnish_repair = obten_producto ( null, $variante, $color, null, null );

$rendimiento_smartcover_repair = 0.15; /* litros/m2 */
$capacidad_envase_smartcover_repair = 8.3; /* litros */

$envases_smartcover_repair = ceil ( $input_m2 * $rendimiento_smartcover_repair );


$presupuesto['smartcover_repair'] = array (
																					"cantidad" => $envases_smartcover_repair,
																					"nombre" => $varnish_repair->nombre_es,
																					"precio" => $varnish_repair->precio_es,
																					"color" => $varnish_repair->color,
																					"acabado" => $varnish_repair->acabado,
																					"img" => $varnish_repair->miniatura,
																					"id" => $varnish_repair->id
																					);
}

}


$_SESSION['presupuesto'] = array();
foreach ($presupuesto as $prod) {
	if ( $prod['cantidad'] )	array_push ( $_SESSION['presupuesto'], $prod['id'] . '|' . $prod['cantidad'] );
}

$importe_total=0;

?>
		<div class="row">
			<div class="col-md-12">
				<h2 class="pres">Cálculo para <?php echo $input_m2 ?> m<sup>2</sup><br><?php echo $tipo_presupuesto[ $input_tipo_pres ] ?>
					<?php echo ( $input_tipo_pres == 1 ) ? ' <span class="tip_pres">' . $tipo_superficie[$input_tipo_superficie] . '</span>' : '' ?>
			  </h2>
				<div class=" table-responsive">
					<table class="table carrito">
						<thead>
							<tr>
								<th colspan="2">Articulo</th>
								<th class="">Uds</th>
								<th class="der">Precio</th>
								<th class="der">Total</th>
							</tr>
						</thead>
						<tbody>

							<?php if ( isset( $presupuesto['smart_kit']) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_kit']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_kit']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_kit']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_kit']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_kit']['precio'] * $presupuesto['smart_kit']['cantidad'] ?>€</td>
								</tr>
							<?php $importe_total += ($presupuesto['smart_kit']['precio'] * $presupuesto['smart_kit']['cantidad']); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_base'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_base']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_base']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_base']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_base']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_base']['precio'] * $presupuesto['smart_base']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ($presupuesto['smart_base']['precio'] * $presupuesto['smart_base']['cantidad']); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_liso'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_liso']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_liso']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_liso']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_liso']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_liso']['precio'] * $presupuesto['smart_liso']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_liso']['precio'] * $presupuesto['smart_liso']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_varnish'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_varnish']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_varnish']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_varnish']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_varnish']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_varnish']['precio'] * $presupuesto['smart_varnish']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_varnish']['precio'] * $presupuesto['smart_varnish']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_primer_abs'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_primer_abs']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_primer_abs']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_primer_abs']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_primer_abs']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_primer_abs']['precio'] * $presupuesto['smart_primer_abs']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_primer_abs']['precio'] * $presupuesto['smart_primer_abs']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_primer_grip'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_primer_grip']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_primer_grip']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_primer_grip']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_primer_grip']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_primer_grip']['precio'] * $presupuesto['smart_primer_grip']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_primer_grip']['precio'] * $presupuesto['smart_primer_grip']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_jointer'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_jointer']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_jointer']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_jointer']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_jointer']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_jointer']['precio'] * $presupuesto['smart_jointer']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_jointer']['precio'] * $presupuesto['smart_jointer']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smartcover'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smartcover']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smartcover']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smartcover']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smartcover']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smartcover']['precio'] * $presupuesto['smartcover']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smartcover']['precio'] * $presupuesto['smartcover']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_primer_abs_1l'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_primer_abs_1l']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_primer_abs_1l']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_primer_abs_1l']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_primer_abs_1l']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_primer_abs_1l']['precio'] * $presupuesto['smart_primer_abs_1l']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_primer_abs_1l']['precio'] * $presupuesto['smart_primer_abs_1l']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_primer_abs_5l'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smart_primer_abs_5l']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_primer_abs_5l']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_primer_abs_5l']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_primer_abs_5l']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_primer_abs_5l']['precio'] * $presupuesto['smart_primer_abs_5l']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_primer_abs_5l']['precio'] * $presupuesto['smartcover']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smartcover_repair'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['smartcover_repair']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smartcover_repair']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smartcover_repair']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smartcover_repair']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smartcover_repair']['precio'] * $presupuesto['smartcover_repair']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smartcover_repair']['precio'] * $presupuesto['smartcover_repair']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['varnish_repair'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['varnish_repair']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['varnish_repair']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['varnish_repair']['cantidad'] ?></td>
									<td><?php echo $presupuesto['varnish_repair']['precio'] ?>€</td>
									<td><?php echo $presupuesto['varnish_repair']['precio'] * $presupuesto['varnish_repair']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['varnish_repair']['precio'] * $presupuesto['varnish_repair']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['kit_herramientas'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['kit_herramientas']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['kit_herramientas']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['kit_herramientas']['cantidad'] ?></td>
									<td><?php echo $presupuesto['kit_herramientas']['precio'] ?>€</td>
									<td><?php echo $presupuesto['kit_herramientas']['precio'] * $presupuesto['kit_herramientas']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['kit_herramientas']['precio'] * $presupuesto['kit_herramientas']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['lija_40'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['lija_40']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['lija_40']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['lija_40']['cantidad'] ?></td>
									<td><?php echo $presupuesto['lija_40']['precio'] ?>€</td>
									<td><?php echo $presupuesto['lija_40']['precio'] * $presupuesto['lija_40']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['lija_40']['precio'] * $presupuesto['lija_40']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['lija_220'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['lija_220']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['lija_220']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['lija_220']['cantidad'] ?></td>
									<td><?php echo $presupuesto['lija_220']['precio'] ?>€</td>
									<td><?php echo $presupuesto['lija_220']['precio'] * $presupuesto['lija_220']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['lija_220']['precio'] * $presupuesto['lija_220']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['lija_400'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/<?php echo $presupuesto['lija_400']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['lija_400']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['lija_400']['cantidad'] ?></td>
									<td><?php echo $presupuesto['lija_400']['precio'] ?>€</td>
									<td><?php echo $presupuesto['lija_400']['precio'] * $presupuesto['lija_400']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['lija_400']['precio'] * $presupuesto['lija_400']['cantidad'] ); ?>
							<?php } ?>

							<tr class="linea_total">
								<td colspan="3" style="text-align: right;">
								    <b>TOTAL</b>
								</td>
								<td class="total_importe" colspan="2"><?php echo formatea_importe ( $importe_total ) ?> €</td>
							</tr>
						</tbody>
					</table>
				</div>


				<div class="btns">
					<button class="delete" data-bs-toggle="tooltip" data-bs-placement="top" title="" onclick="javascript:location.reload();">Nuevo cálculo	<i class="fa fa-trash"></i>
				</button>
					<button type="button" class="btn palcarrito btn-lg submit" onclick="pres_to_carrito()" > <i class="fa fa-cart-plus"></i>Añadir productos al carrito</button>
				</div>
			</div>
		</div>
<?php

function obten_producto ( $idProd=null, $variante=null, $color=null, $acabado=null, $juntas=null ) {

	if ( isset( $variante ) && $variante != 0) {
		$where = "WHERE es_variante='$variante'";
		if( isset( $color ) && $color != '' ) {
			$where .= " AND color='$color'";
		}
		if( isset ( $acabado ) && $acabado != '' ) {
			$where .= " AND acabado='$acabado'";
		}
		if( isset ( $juntas ) && $juntas != '' && $juntas != '0' ) {
			$where .= " AND juntas='$juntas'";
		}
		if( isset ( $formato ) && $formato != '' ) {
			$where .= " AND formato='$formato'";
		}
		$sql = "SELECT * FROM productos $where";
		$res=consulta($sql, $conn);
	}else if ( isset( $idProd ) && $idProd != 0 ) {
		$where = "WHERE id ='$idProd'";
		$sql = "SELECT * FROM productos $where";
		$res=consulta($sql, $conn);
	}

	return $res->fetch_object();

}


?>