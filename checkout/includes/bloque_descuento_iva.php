<?php

/* Obtenemos el tipo de impuesto del envio */

//1) Si es cliente nacional - Operac.Internas Sujetas 21% (REP) [21%]
//2) Canarias, Ceuta y Melilla, exportación - Exportaciones de bienes [0%]
//3) Unión Europea, con VIES - Entregas Intracomunitarias Exentas [0%]
//4) Unión Europea, sin VIES (no superado max anual) - Operac.Internas Sujetas 21% (REP) [21%]
//5) Unión Europea, sin VIES (superado max anual) - IVA según pais destino
//6) Resto de países - Exportaciones de bienes [0%]

/****** INICIO - comprobacion del tipo de impuesto del pedido ******/
// echo $_SESSION['smart_user']['dir_facturacion'];
// echo "hola";
// echo  $_SESSION['smart_user']['dir_envio'];
$tipo_impuesto = $checkout->obten_tipo_impuesto_envio ( $_SESSION['smart_user']['dir_facturacion'] , $_SESSION['smart_user']['dir_envio'] );
$descuento_iva = ( $tipo_impuesto == 1 ) ? 1 : 1.21 ;
// echo "hola";
// echo $tipo_impuesto;
// echo "yewe";
// echo "Dir facturacion: " . $_SESSION['smart_user']['dir_facturacion'];
// echo "<br>";
// echo "Dir envio: " . $_SESSION['smart_user']['dir_envio'];
// echo "<br>";
// echo "Tipo impuesto: " . $tipo_impuesto;
// echo "<br>";
// echo (double) obten_max_importe_ventas_ue_no_vies();
// echo "<br>";
// echo obten_ventas_no_vies();
// echo "<br>";
// exit;

//****** INICIO - bloque descuento de iva *****//

//$importe_max_no_vies = (double) $checkout->obten_max_importe_ventas_ue_no_vies();
// $importe_ventas_no_vies = obten_ventas_no_vies();
// echo $_SESSION['smart_user']["dir_envio"];
$pais_envio = $checkout->obten_dir_envio( $_SESSION['smart_user']["dir_envio"])[0]['pais'];
// echo $pais_envio;


// Tipo impuesto 2, 3, 6 - Exportaciones de bienes [0%] (Canarias, Unión Europea con VIES y resto de paises)
if ( $_SESSION['smart_user']['dir_envio'] && ( $tipo_impuesto == 2 || $tipo_impuesto == 3 || $tipo_impuesto == 6 ) ) {
	// echo "hola";

	if ($pais_envio != 'US'){
	$importe_descuento_iva = $importe_final - ( $importe_final / $descuento_iva );
	?>
	<tr class="descuento_iva total">
		<td class="izq iva"><?php echo $vocabulario_reduccion_iva ?><span class="peque"> (-21%)</span>:</td>
		<td class="der">- <?php echo $checkout->formatea_importe( $importe_descuento_iva ) ?> <?php echo $moneda ?></td>
	</tr>
	<tr class="descuento_iva total">
		<td class="izq"><b><?php echo $vocabulario_total_pagar ?></b><span class="peque"></span>:</td>
		<?php  if( $carrito->articulos_total() > 0 ) {
			$importe_final = $carrito->precio_total() + $gastos_envio - $importe_descuento - $importe_descuento_iva;
			?>
			<td class="der"><b><?php echo $checkout->formatea_importe( $importe_final ) ?> <?php echo $moneda ?></b></td>
		<?php }else { ?>
			<td class="der"><b>0.00 <?php echo $moneda ?></b></td>
		<?php } ?>
	</tr>
<?php
	}
}

//VIES - Se supera el importe max anual - Aplicar IVA de país de envío
else if ( $_SESSION['smart_user']['dir_envio'] && $tipo_impuesto ==5 ) {

			$nombre_pais = $checkout->obten_nombre_pais ( $pais_envio );
			$impuesto_iva_pais = $checkout->obten_iva_pais ( $pais_envio );

			$importe_descuento_iva = $importe_final - ( $importe_final / $descuento_iva );
			$importe_final_sin_iva_es = $importe_final - $importe_descuento_iva;

			?>

			<tr class="descuento_iva total">
				<td class="izq iva"><b><?php echo $vocabulario_impuesto_sobre_iva_espana?><span class="peque"> (-21%)</b></span>:</td>
				<td class="der">- <?php echo $checkout->formatea_importe( $importe_descuento_iva ) ?> <?php echo $moneda ?></td>
			</tr>

			<tr class="descuento_iva total">
				<td class="izq iva"><b><?php echo $vocabulario_impuesto_sobre_iva?> <?php echo $nombre_pais ?><span class="peque"> (+<?php echo $impuesto_iva_pais ?>%)</b></span>:</td>
				<td class="der">+ <?php echo $checkout->formatea_importe( $importe_final_sin_iva_es * (1+$impuesto_iva_pais/100) -$importe_final_sin_iva_es ) ?> <?php echo $moneda ?></td>
			</tr>

			<tr class="descuento_iva total">
				<td class="izq"><b><?php echo $vocabulario_total_a_pagar ?></b><span class="peque"></span>:</td>

				<?php  if( $carrito->articulos_total() > 0 ) { ?>

					<td class="der"><b><?php echo $checkout->formatea_importe( $importe_final_sin_iva_es * (1+$impuesto_iva_pais/100) ) ?> <?php echo $moneda ?></b></td>

				<?php }else { ?>

					<td class="der"><b>0.00 <?php echo $moneda ?></b></td>

				<?php } ?>
			</tr>

		<?php

		$importe_final = $checkout->formatea_importe( $importe_final_sin_iva_es * (1 + $impuesto_iva_pais/100) );
}


?>
