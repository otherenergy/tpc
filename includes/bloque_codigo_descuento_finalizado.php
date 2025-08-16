<?php $lang=obten_idioma_actual() ?>

<?php

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

	$num_kits_descuento = intval( $num_kits / 2 );
	$importe_descuento += $descuento_kits;

	if ( $descuento_kits > 0 ) {


?>
	<tr class="descuento aplicado" title="<?php echo trad ('El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío') ?>">

		<td colspan="3" style="background-color: #fff;border-top: 0"></td>

	<td colspan="1" class="izq nom_cup">

		<span class="aplica_desc"><?php echo trad('Descuento aplicado:') ?></span>

			<span class="aplicacion_descuento"><?php echo $num_kits_descuento . ' Kits x ' . $precio_menor . '€' ?></span><br>

		<span ><?php echo $_SESSION['codigo_descuento']['nombre'] ?><i class="fa fa-info-circle"></i></span>
		<span class="tipo_desc">2x1</span>
	</td>

	<td class="der importe_descuento">
		<span>- <?php echo formatea_importe( $descuento_kits ) ?> €</span>
	</td>
</tr>

<?php

	}

	}

?>

<?php if ( $descuento_kits == 0 ) { ?>

<tr class="descuento aplicado" title="<?php echo trad ('El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío') ?>">
	<td colspan="3" style="background-color: #fff;border-top: 0"></td>
	<td colspan="1" class="izq nom_cup" style="border-top: 0;padding: 15px 0;">
		<span class="aplica_desc"><?php echo trad('Descuento aplicado:') ?></span>

		<?php if ( obten_descuento ( $_SESSION['codigo_descuento']['id'] )->aplicacion_descuento != 1 ) { ?>

			<span class="aplicacion_descuento">(<?php echo obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['aplicacion'] )->aplicacion_texto ?>)</span><br>

		<?php } ?>

		<span><?php echo $_SESSION['codigo_descuento']['nombre'] ?><i class="fa fa-info-circle"></i></span>
		<span class="tipo_desc"><?php echo $_SESSION['codigo_descuento']['valor'] . ' ' . $_SESSION['codigo_descuento']['tipo'] ?></span>
	</td>

	<td class="der importe_descuento" style="vertical-align: middle;border-top:0;">

		<?php
		/* si el descuento no es para todos los productos */
		if ( $_SESSION['codigo_descuento']['aplicacion'] != 1 ) { ?>

			<span>
				<?php if ( $_SESSION['codigo_descuento']['tipo'] == '%') {

					/* recorremos los productos del carrito para encontrar productos afectados por el descuento y aplicarlo sobre estos */
					$id_prods_descuento = obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['aplicacion'] )->id_prods;
					$array_id_prods_descuento = explode( '|', $id_prods_descuento );

					$carro = $carrito->get_content();
					$importe_prod_descuento = 0;

					foreach($carro as $producto) {
						if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
							$importe_prod_descuento += $producto["cantidad"] * $producto["precio"];
						}
					}

					$importe_descuento = $importe_prod_descuento * ( $_SESSION['codigo_descuento']['valor'] / 100 );

				}elseif ( $_SESSION['codigo_descuento']['tipo'] == '€') {
					$importe_descuento = $_SESSION['codigo_descuento']['valor'];
				}
				echo '- ' . formatea_importe( $importe_descuento ) . ' €';
				?>
			</span>


		<?php }else { ?>

			<span>
				<?php if ( $_SESSION['codigo_descuento']['tipo'] == '%') {
					$importe_descuento = ( $carrito->precio_total() ) * ( $_SESSION['codigo_descuento']['valor'] / 100 );
				}elseif ( $_SESSION['codigo_descuento']['tipo'] == '€') {
					$importe_descuento = $_SESSION['codigo_descuento']['valor'];
				}
				echo '- ' . formatea_importe( $importe_descuento ) . ' €';
				?>
			</span>

		<?php } ?>

	</td>
</tr>

<?php } ?>

<?php

/* si hay oferta regalo por el 2x1 */
if ( $_SESSION['codigo_descuento']['regalo'] != 0 ) {

	$id_prods_descuento = obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['regalo_condicion'] )->id_prods;
	$array_id_prods_descuento = explode( '|', $id_prods_descuento );

	$carro = $carrito->get_content();
	$productos_promocion = 0;

	foreach($carro as $producto) {
		if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
			$productos_promocion += $producto['cantidad'];
		}
	}

if ( $productos_promocion > 1 ) {

	$producto_regalo = obten_datos_producto( $_SESSION['codigo_descuento']['regalo'] );
	$nombre_idioma = 'nombre_' . $lang;

	?>

	<tr class="descuento aplicado" title="<?php echo trad ('El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío') ?>">
		<td colspan="3" style="background-color: #fff;border-top: 0"></td>
		<td class="izq nom_cup">

			<span class="aplica_desc"><?php echo trad('Regalo producto:') ?></span>

				<span class="regalo aplicacion_descuento"><?php echo '1 x ' . $producto_regalo->nombre_es; ?></span><br>

			<span ><?php echo $_SESSION['codigo_descuento']['nombre'] ?><i class="fa fa-info-circle"></i></span>
			<span class="tipo_desc"><?php echo trad ('GRATIS') ?></span>
		</td>

		<td class="der importe_descuento"><span>0.00 €</span></td>
	</tr>

<?php
}
}

?>

<?php

/* si hay promocion descuento en otro producto */
if ( $_SESSION['codigo_descuento']['descuento_otro_producto'] != 0 ) {

	$id_prod_descuento = $_SESSION['codigo_descuento']['descuento_otro_producto'];

	$carro = $carrito->get_content();
	$productos_promocion = 0;

	foreach($carro as $producto) {
		if( $producto['id'] == $id_prod_descuento ) {
			$productos_promocion += $producto['cantidad'];
		}
	}

if ( $productos_promocion > 0 ) {

	$prod_promocion = obten_datos_producto( $_SESSION['codigo_descuento']['descuento_otro_producto'] );

	$nombre_idioma = 'nombre_' . $lang;
	$precio_idioma = 'precio_' . $lang;

	$total_descuento_producto = formatea_importe( $productos_promocion * $prod_promocion->$precio_idioma *  $_SESSION['codigo_descuento']['cantidad_descuento_otro_producto'] / 100 );

	?>

	<tr class="descuento aplicado" title="<?php echo trad ('El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío') ?>">
		<td colspan="3" style="background-color: #fff;border-top: 0"></td>
		<td class="izq nom_cup">

		<span class="aplica_desc"><?php echo trad('Descuento aplicado:') ?></span>

			<span class="regalo aplicacion_descuento"><?php echo $prod_promocion->$nombre_idioma ?></span><br>

		<span ><?php echo $_SESSION['codigo_descuento']['nombre'] ?><i class="fa fa-info-circle"></i></span>
		<span class="tipo_desc"><?php echo  $_SESSION['codigo_descuento']['cantidad_descuento_otro_producto'] . '%' ?></span>
	</td>

	<td class="der importe_descuento"><span><?php echo - $total_descuento_producto . ' €' ?></span></td>
</tr>


<?php

	$importe_descuento += $total_descuento_producto;

}
}

?>

