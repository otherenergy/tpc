<?php
include_once ( '../../includes/array_precios.php' );

include_once(dirname ( dirname ( __DIR__ ) ) . '/assets/lib/bbdd.php');
include_once(dirname ( dirname ( __DIR__ ) ) . '/assets/lib/class.carrito.php');
include_once(dirname ( dirname ( __DIR__ ) ) . '/assets/lib/funciones.php');


if(isset($_REQUEST['input_presupuesto']) && $_REQUEST['input_presupuesto']=='1') {
	$res=array();
	$post_keys = array_keys($_REQUEST);
	foreach( $post_keys as $key ) {
		$$key = $_REQUEST[$key];
		echo $key . ' - ' . $$key . "<br>";
	}
}

$presupuesto = array(
											"smart_kit" => null,
											"smart_base" => null,
											"smart_liso" => null,
											"smart_primer_abs" => null,
											"smart_primer_grip" => null,
											"smart_jointer" => null,
											"smart_varnish" => null
										 );

if ( $input_tipo_pres == 1 ) {

$tipo_presupuesto = array(
													'1' => 'Microcemento',
													'2' => 'Pintura para azulejos',
													'3' => 'Pintura para azulejos'
												);
$tipo_superficie = array(
													'1' => 'Superficies Absorbentes ( Yeso, pladur, etc )',
													'2' => 'Superficies NO Absorbentes ( Azulejo, gres, terrazo )'
												);

/* calculo de kits */

$variante = $input_tipo_superficie;
$color = $input_id_color;
$acabado = $input_tipo_acabado;
$tipo_juntas = $input_tipo_juntas;


$num_kits = intdiv($input_m2, 8);
$metros_sobrantes = $input_m2 % 8;

if ($num_kits > 0) {

$kit = obten_producto ( $variante, $color, $acabado, $juntas );

echo "<br>";
echo $kit->nombre_es;
echo "<br>";

$nom_kit = "Kit microcemento 8m2 " . $tipo_superficie[$input_tipo_superficie];
$precio_kit = ( $input_tipo_superficie == 1 ) ? $tarifa['SC1XXX'] : $tarifa['SC2XXX'];

$img_smartkit =  array(
												'1' => 'smartkit-abs.webp',
												'2' => 'smartkit-no-abs.webp'
											);

$presupuesto['smart_kit'] = array (
																		"nombre" => $nom_kit,
																		"cantidad" => $num_kits,
																		"precio" => $precio_kit,
																		"color" => $input_id_color,
																		"acabado" => $input_tipo_acabado,
																		"img" => $img_smartkit[ $input_tipo_superficie ]
																	);
}

/* Consumo por capa 0,9 kg/m2 0,250 kg/m2 */

/* Base */
$nom_base = "Smart Base 6Kg - Microcemento Listo al uso";
$precio_smart_base = $tarifa['SC0044'];
$img_smart_base = "microcemento-listo-uso-smart-base.webp";

$capas_base = 2;
$rendimiento_base = 0.9; /* kgs/m2 */
$capacidad_envase_base = 6; /* kgs */

$envases_base = ceil ( ( $metros_sobrantes * $rendimiento_base * $capas_base ) / $capacidad_envase_base );

$presupuesto['smart_base'] = array (
																		"nombre" => $nom_base,
																		"cantidad" => $envases_base,
																		"precio" => $precio_smart_base,
																		"img" => $img_smart_base
																		);

/* Liso */
$nom_liso = "Smart Liso 6Kg - Microcemento Listo al uso";
$precio_smart_liso = $tarifa['SC0043'];
$img_smart_liso = "microcemento-listo-uso-smart-liso.webp";

$capas_liso = 2;
$rendimiento_liso = 0.25; /* kgs/m2 */
$capacidad_envase_liso = 6; /* kgs */

$envases_liso = ceil ( ( $metros_sobrantes * $rendimiento_liso * $capas_liso ) / $capacidad_envase_liso );

$presupuesto['smart_liso'] = array (
																		"nombre" => $nom_liso,
																		"cantidad" => $envases_liso,
																		"precio" => $precio_smart_liso,
																		"img" => $img_smart_liso
																		);

/* Barniz */
$nom_barniz = "Smart Varnish 2L - Barniz poliuretano al agua listo al uso";
$precio_smart_barniz = $tarifa['SC0501'];
$img_smart_barniz = "barniz-poliuretano-agua-smart-varnish.webp";

$capas_barniz = 3;
$rendimiento_barniz = 0.066667;  /* litros/m2 */
$capacidad_envase_barniz = 2; /* litros */

$envases_barniz = ceil ( ( $metros_sobrantes * $rendimiento_barniz * $capas_barniz ) / $capacidad_envase_barniz );

$presupuesto['smart_varnish'] = array (
																				"nombre" => $nom_barniz,
																				"cantidad" => $envases_barniz,
																				"precio" => $precio_smart_barniz,
																				"img" => $img_smart_barniz
																			);

if ( $input_tipo_superficie == 1 ) {

/* primer abs */
$nom_primer_abs = "Smart Primer ABS 1L - Barniz poliuretano al agua listo al uso";
$precio_primer_abs = $tarifa['SC0311'];
$img_primer_abs = "imprimacion-superficies-absorbentes-smart-primer-abs.webp";

$capas_primer_abs = 1;
$rendimiento_primer_abs = 0.10; /* litros/m2 */
$capacidad_envase_primer_abs = 1; /* litros */

$envases_primer_abs = ceil ( ( $metros_sobrantes * $rendimiento_primer_abs * $capas_primer_abs ) / $capacidad_envase_primer_abs );

$presupuesto['smart_primer_abs'] = array (
																					"nombre" => $nom_primer_abs,
																					"cantidad" => $envases_primer_abs,
																					"precio" => $precio_primer_abs,
																					"img" => $img_primer_abs
																					);

$envases_primer_grip = 0;
$envases_smart_jointer = 0;

}else if ( $input_tipo_superficie == 2 ) {


/* primer grip */
$nom_primer_grip = "Smart Primer Grip 2Kg - Imprimación superficies no absorbentes";
$precio_primer_grip = $tarifa['SC0303'];
$img_primer_grip = "imprimacion-superficies-no-absorbentes-smart-primer-grip.webp";

$capas_primer_grip = 1;
$rendimiento_primer_grip = 0.12;  /* litros/m2 */
$capacidad_envase_primer_grip = 1.333334; /* litros */

$envases_primer_grip = ceil ( ( $metros_sobrantes * $rendimiento_primer_grip * $capas_primer_grip ) / $capacidad_envase_primer_grip );

$presupuesto['smart_primer_grip'] = array (
																						"nombre" => $nom_primer_grip,
																						"cantidad" => $envases_primer_grip,
																						"precio" => $precio_primer_grip,
																						"img" => $img_primer_grip
																					 );

/* smart jointer */
$nom_smart_jointer = "Smart Jointer 1Kg - Tapajuntas para azulejos";
$precio_smart_jointer = $tarifa['SC0400'];
$img_smart_jointer = "tapajuntas-azulejos-smart-jointer.webp";

$capas_smart_jointer = 1;
$rendimiento_smart_jointer = 0.125;  /* kgs/m2 */
$capacidad_envase_smart_jointer = 1; /* kilos */

$envases_smart_jointer = ceil ( ( $metros_sobrantes * $rendimiento_smart_jointer * $capas_smart_jointer ) / $capacidad_envase_smart_jointer );

$presupuesto['smart_jointer'] = array (
																				"nombre" => $nom_smart_jointer,
																				"cantidad" => $envases_primer_grip,
																				"precio" => $precio_smart_jointer,
																				"img" => $img_smart_jointer
																			);
$envases_primer_abs = 0;

}


}


$importe_total=0;

?>
		<div class="row">
			<div class="col-md-12">
				<h2 class="pres">Cálculo para <?php echo $input_m2 ?> m<sup>2</sup> de <?php echo $tipo_presupuesto[ $input_tipo_pres ] ?><br>
					<?php echo ( $input_tipo_pres == 1 ) ? ' <span class="tip_pres">' . $tipo_superficie[$input_tipo_superficie] . '</span>' : '' ?>
			  </h2>
				<div class=" table-responsive">
					<table class="table carrito">
						<thead>
							<tr>
								<th colspan="2">Articulo</th>
								<th class="">Unidades</th>
								<th class="der">Precio</th>
								<th class="der">Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody>

							<?php if ( isset( $presupuesto['smart_kit']) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $presupuesto['smart_kit']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_kit']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_kit']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_kit']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_kit']['precio'] * $presupuesto['smart_kit']['cantidad'] ?>€</td>
								</tr>
							<?php $importe_total += ($presupuesto['smart_kit']['precio'] * $presupuesto['smart_kit']['cantidad']); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_base'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $presupuesto['smart_base']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_base']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_base']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_base']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_base']['precio'] * $presupuesto['smart_base']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ($presupuesto['smart_base']['precio'] * $presupuesto['smart_base']['cantidad']); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_liso'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $presupuesto['smart_liso']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_liso']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_liso']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_liso']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_liso']['precio'] * $presupuesto['smart_liso']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_liso']['precio'] * $presupuesto['smart_liso']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_varnish'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $presupuesto['smart_varnish']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_varnish']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_varnish']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_varnish']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_varnish']['precio'] * $presupuesto['smart_varnish']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_varnish']['precio'] * $presupuesto['smart_varnish']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_primer_abs'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $presupuesto['smart_primer_abs']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_primer_abs']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_primer_abs']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_primer_abs']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_primer_abs']['precio'] * $presupuesto['smart_primer_abs']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_primer_abs']['precio'] * $presupuesto['smart_primer_abs']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_primer_grip'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $presupuesto['smart_primer_grip']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_primer_grip']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_primer_grip']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_primer_grip']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_primer_grip']['precio'] * $presupuesto['smart_primer_grip']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_primer_grip']['precio'] * $presupuesto['smart_primer_grip']['cantidad'] ); ?>
							<?php } ?>

							<?php if ( isset( $presupuesto['smart_jointer'] ) ) { ?>
								<tr class="datos" uid="<?php echo $producto["unique_id"] ?>">
									<td><img src="../assets/img/productos/<?php echo $presupuesto['smart_jointer']['img'] ?>" alt=""/></td>
									<td><div class="desc"><?php echo $presupuesto['smart_jointer']['nombre'] ?></div></td>
									<td class=""><?php echo $presupuesto['smart_jointer']['cantidad'] ?></td>
									<td><?php echo $presupuesto['smart_jointer']['precio'] ?>€</td>
									<td><?php echo $presupuesto['smart_jointer']['precio'] * $presupuesto['smart_jointer']['cantidad'] ?>€</td>
								</tr>
								<?php $importe_total += ( $presupuesto['smart_jointer']['precio'] * $presupuesto['smart_jointer']['cantidad'] ); ?>
							<?php } ?>

							<tr class="linea_total">
								<td colspan="3" style="text-align: right;">
								    TOTAL
								</td>
								<td class="total_importe" colspan="2"><?php echo formatea_importe ( $importe_total ) ?> €</td>
							</tr>

						</tbody>
					</table>
				</div>
				<div class="btns">
					<button class="delete" data-bs-toggle="tooltip" data-bs-placement="top" title="" onclick="javascript:location.reload();">Eliminar	<i class="fa fa-trash"></i>
				</button>
					<button type="button" class="btn palcarrito btn-lg submit" onclick="addToCart()" > <i class="fa fa-cart-plus"></i>Añadir productos al carrito</button>
				</div>
			</div>
		</div>




<?php

function obten_producto ( $variante=null, $color=null, $acabado=null, $juntas=null ) {

	$where = "WHERE es_variante='$variante'";
	if( isset( $color ) && $color != '' ) {
		$where .= " AND color='$color'";
	}
	if( isset ( $acabado ) && $acabado != '' ) {
		$where .= " AND acabado='$acabado'";
	}
	if( isset ( $juntas ) && $juntas != '' ) {
		$where .= " AND juntas='$juntas'";
	}
	if( isset ( $formato ) && $formato != '' ) {
		$where .= " AND formato='$formato'";
	}
	$sql = "SELECT * FROM productos $where";
	$res=consulta( $sql, $conn );
	// $reg=$res->fetch_object();

	return $res->fetch_object();

}


?>