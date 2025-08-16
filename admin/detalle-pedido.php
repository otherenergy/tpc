<?php

include('./includes/header.php');

$id_pedido = $_GET['id'];

// $pedido = obten_datos_pedido( $id_pedido )->fetch_object();

$res = obten_datos_pedido( $id_pedido );
$reg = $res->fetch_object();
// var_dump($reg);
if ( !$reg ) {
	header ('Location: pedidos');
}

// $sql2 = "SELECT * FROM detalles_pedido WHERE id_pedido = $id_pedido";
$sql2 = "
					SELECT
						DP.*
					FROM detalles_pedido DP

					WHERE
					id_pedido = $id_pedido";

// echo "IDDDDD PEDIDO: ";
// echo $id_pedido;
$res2=consulta($sql2, $conn);

$pagina = "Pedido Smartcret " . $reg->ref_pedido;
$title = "Pedidos | Smartcret";
$description = "Página listado de pedidos";

$env_pais = obten_dir_envio_user ( $reg->id_envio );
// $total_pagar = $reg->total_pagado;


?>

	<body class="listado-pedidos">
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
		<?php include ('./includes/menu_lateral.php') ?>
		<div class="col-md-2"><div class="sepv"></div></div>
		<div class="col-md-7">
			<h1 class="center"><?php echo $pagina . '<span style="font-size: 15px;margin-left: 12px;">[' . cambia_fecha_hora( $reg->fecha_creacion ) . '] [ ' . $reg->idioma . ' ]</span>'?></h1>
			<?php

			if ( $reg->cancelado == 1 ) {

				$datos_cancela = obten_datos_cancelacion( $reg->ref_pedido );

			?>

			<div class="cancelacion">

				<table>
					<tr>
						<td><b>Fecha cancelación:</b></td>
						<td style="font-size: 15px;padding-left:20px"><?php echo (!empty( $datos_cancela->fecha_actualizacion )) ? cambia_fecha_slash ( $datos_cancela->fecha_actualizacion ) : ''; ?></td>
					</tr>
					<tr>
						<td><b>Motivo:</b></td>
						<td style="font-size: 15px;padding-left:20px"><?php echo ( !empty( $datos_cancela->motivo ) ) ? $datos_cancela->motivo : '' ?></td>
					</tr>
				</table>

			</div>

			<?php } ?>

			<div class="sep20"></div>
			<div class="btns_funciones">
				<button class="print_btn"
								onclick="$('h1, .table-responsive').printThis({
					        importCSS: true,
					        importStyle: true,
					        loadCSS: true,
					        canvas: false
						    })"
						    >Imprimir <i class="fa fa-print"></i>
				</button>
			</div>
			<div class="table-responsive">
				<table id="tbl_pedidos" class="table carrito finalizado">
					<thead>
						<tr>
							<th colspan="2" class="b-top0 ">Articulo</th>
							<th></th>
							<th class="b-top0 der">Unidades</th>
							<th class="b-top0 der">Precio</th>
							<th class="b-top0 der" style="width: 110px">Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while($reg2=$res2->fetch_object()) {

								$reg_prod = obten_datos_producto( $reg2->id_prod );

							?>
								<tr class="datos" id="<?php echo $reg2->id_prod ?>">
									<td class="izq" width="90px"><img src="../assets/img/productos/<?php echo $reg_prod->miniatura ?>" alt="<?php echo $reg_prod->nombre_es?>" title="<?php echo $reg_prod->nombre_es?>" style="width: 70px;height: 70px;"></td>

									<td class="izq"><div class="desc"><?php echo $reg_prod->nombre_es?></div></td>

									<td class='desc'>
										<?php if ( $reg2->descuento != 0 ) {
											// var_dump($reg2);
											if ( $reg->total_pagado_div != 0 ) {

												$precio_idioma = ( $reg->idioma == 'en-us' ) ? 'en_us' : $reg->idioma;

												echo "<div class='box_importes'>";
												echo formatea_importe( $reg2->precio ) . ' €  ';
												if ( $env_pais->pais == 'US') {
													echo '<br><span class="mini"><sup>$</sup>' . formatea_importe( $reg2->precio_div ) . '</span>';
												}
												echo "</div>";
												echo '<span class="prod_desc div"> -' . $reg2->descuento . '% </span>';


											}else {

												// $precio_idioma = ( $reg->idioma == 'en-us' ) ? 'en_us' : $reg->idioma;
												// $precio = 'precio_base_' . $precio_idioma;
												// echo $reg2->precio . ' *  1 +' . $reg2->descuento . ' * 100    ';
												echo formatea_importe( $reg2->precio / ( 1 - ($reg2->descuento/100) ) ) . ' €  ';
												echo '<span class="prod_desc"> -' . $reg2->descuento . '% </span>';

											}

										} ?>

									</td>

									<td class="td-unidades">
										<?php echo $reg2->cantidad ?>
									</td>


									<td class='desc'>

										<?php if ( $reg2->descuento != 0 ) {
											// var_dump($reg2);

											if ( $reg->total_pagado_div != 0 ) {
												echo formatea_importe( $reg2->precio ) . ' €  ';

												if ( $env_pais->pais == 'US' ) {
													echo '<br><span class="mini"><sup>$</sup>' . formatea_importe( $reg2->precio_div ) . '</span>';
												} ?>


											<?php }else {

												?>
												<?php echo formatea_importe($reg2->precio)?> €
												<?php if ( $env_pais->pais == 'US') {
													echo '<br><span class="mini"><sup>$</sup>' . formatea_importe( $reg2->precio_div ) . '</span>';
												} ?>

											<?php } ?>

										<?php }else {

											echo formatea_importe($reg2->precio)?> €
												<?php if ( $env_pais->pais == 'US') {
													echo '<br><span class="mini"><sup>$</sup>' . formatea_importe( $reg2->precio_div ) . '</span>';
												} ?>

											<?php } ?>

									</td>


									<td><?php echo formatea_importe( $reg2->cantidad * $reg2->precio ) ?> €
										<?php if ( $env_pais->pais == 'US') {
											echo '<br><span class="mini"><sup>$</sup>' . formatea_importe( $reg2->cantidad * $reg2->precio_div ) . '</span>';
										} ?>
									</td>
								</tr>

							<?php } ?>

							<?php /*******  SUBTOTAL  *********/ ?>

							<tr class="total envios">
								<td colspan="5" class="der">Subtotal productos:</td>
								<td><?php echo formatea_importe( $reg->total_sinenvio ) ?> €
									<?php if ( $env_pais->pais == 'US') {
											echo '<span class="mini"><sup>$</sup>' . formatea_importe( $reg->total_sinenvio_div ) . '</span>';
									} ?>
								</td>
							</tr>

							<?php /*******  CUPON DESCUENTO  *********/ ?>

							<?php

							if ( $reg->cupon_id != null && $reg->cupon_id != 0 ) {

								$cupon = obten_cupon_aplicado_pedido( $reg->cupon_id);

								?>

								<tr class="total envios descuento">
									<td colspan="2"></td>
									<td colspan="3" class="der vd"><i class="fa fa-info-circle" title="El descuento de <?php echo $cupon->valor . ' ' . $cupon->tipo ?> se aplica sobre los artículos antes de sumarles los gastos de envío"> <span class="desc_txt">Descuento aplicado<br>
										<?php echo obten_aplicacion_descuento ( $cupon->aplicacion_descuento )->aplicacion_texto ?>
										<br> <?php echo $cupon->nombre_descuento . ' (' . $cupon->valor . ' ' . $cupon->tipo . ')' ?></span></td>
										<td class="vd">- <?php echo formatea_importe( $reg->cupon_importe ) ?> €
											<?php if ( $env_pais == 'US') {
												echo '<br><span class="mini">- <sup>$</sup>' . formatea_importe( $reg->cupon_importe / $reg->cambio_divisa ) . '</span>';
											} ?>
										</td>
								</tr>

							<?php	} ?>

							<?php /*******  GASTOS ENVÍO  *********/ ?>

							<tr class="total envios">
								<td colspan="5" class="der">Gastos de envío:</td>
								<td><?php echo formatea_importe( $reg->gastos_envio ) ?> €

									<?php if ( $env_pais->pais == 'US') {
											echo '<br><span class="mini"><sup>$</sup>' . formatea_importe( $reg->gastos_envio_div ) . '</span>';
									} ?>

								</td>
							</tr>

							<?php /*******  TOTAL  *********/ ?>

							<?php $total_pagado = formatea_importe( $reg->total_sinenvio - $reg->cupon_importe + $reg->gastos_envio  ) ?>
							<?php $total_pagado_div = formatea_importe( $reg->total_sinenvio_div - $reg->cupon_importe_div + $reg->gastos_envio_div ) ?>

							<tr class="total pagado">
									<td colspan="5" class="der total">TOTAL <span class="peque">(IVA incl.)</span>:</td>
									<td id="total_pagado"><b><?php echo $total_pagado ?> €</b>
										<?php if ( $env_pais->pais == 'US') {
											echo '<span class="mini"><sup>$</sup>' . $total_pagado_div . '</span>';
											// echo '<span class="mini"><sup>$</sup>' . formatea_importe( $reg->total_pagado_div ) . '</span>';
										} ?>
									</td>
								</tr>


							<?php /*******  IMPUESTOS  *********/ ?>

							<?php

							if ( ( $reg->tipo_impuesto == 2 || $reg->tipo_impuesto == 3 || $reg->tipo_impuesto == 6 || $reg->descuento_iva != 0 ) && $env_pais->pais != 'US') {

								$iva_es = obten_iva_books ( "ES" );
								$importe_descuento_iva = $reg->total_sinenvio  - ( $reg->total_sinenvio  / ( 1 + ( $iva_es->iva / 100 ) ) );

							?>

								<tr class="descuento_iva total">
									<td colspan="5" class="der total">Descuento IVA <span class="peque">(21%)</span>:</td>
									<td><b>- <?php echo formatea_importe( $importe_descuento_iva ) ?> €</b></td>
								</tr>
								<tr class="descuento_iva total">
									<td colspan="5" class="der total">TOTAL A PAGAR:</td>
									<td id="total_pagado"><b><?php echo formatea_importe( $reg->total_pagado ) ?> €</b></td>
									<!-- <td id="total_pagado"><b><?php echo formatea_importe( $reg->total_sinenvio  - $importe_descuento_iva ) ?> €</b></td> -->
								</tr>

							<?php }


							if ( $reg->tipo_impuesto == 5 ) {

								$iva_es = obten_iva_books ( "ES" )->iva;
								$importe_descuento_iva = $total_pagado - ( $total_pagado  / ( 1 + ( $iva_es / 100 ) ) );

								$pais_envio = obten_dir_envio( $reg->id_envio )->pais;

								$nombre_pais = obten_nombre_pais ( $pais_envio );
								$importe_final_sin_iva_es = $total_pagado  - $importe_descuento_iva;
								$impuesto_iva_pais = obten_iva_pais ( $pais_envio );

								?>

								<tr class="descuento_iva total">
									<td colspan="5" class="der total">Impuesto IVA España<span class="peque"> (-21%)</span>:</td>
									<td><b>- <?php echo formatea_importe( $importe_descuento_iva ) ?> €</b></td>
								</tr>
								<tr class="descuento_iva total">
									<td colspan="5" class="der total">Impuesto IVA <?php echo $nombre_pais ?><span class="peque"> (+<?php echo $impuesto_iva_pais ?>%)</span>:</td>
									<td><b>+ <?php echo formatea_importe( $importe_final_sin_iva_es * ( 1 + $impuesto_iva_pais / 100 ) -$importe_final_sin_iva_es ) ?> €</b></td>
								</tr>

								<?php /*******  TOTAL CON IMPUESTOS *********/ ?>

								<tr class="descuento_iva total">
									<td colspan="5" class="der total">TOTAL A PAGAR:</td>
									<td id="total_pagado"><b><?php echo formatea_importe( $importe_final_sin_iva_es * (1+$impuesto_iva_pais/100) ) ?> €</b></td>
								</tr>

							<?php } ?>

					</tbody>
				</table>































				<div class="sep20"></div>
				<div class="">
				<table class="table">

					<tbody>
						<?php
						$res_user = obten_datos_user( $reg->id_cliente );
						$res_env = obten_dir_envio_user ( $reg->id_envio );
						// var_dump($res_env);
						$res_fact = obten_dir_fact_user ( $reg->id_facturacion );
						$res_pag = obten_metodo_pago ( $reg->metodo_pago );
						$tracking = obten_datos_tracking ( $id_pedido );

						?>
						<tr class="datos izq dir">
							<td rowspan="1" colspan="2">Datos de envío:</td>
							<td colspan="1" class="izq dat">
								<i class="fa fa-user"></i>
								<?php echo $res_env->nombre . ', ' . $res_env->apellidos ?><br>
								<i class="fa fa-phone"></i>
								<?php echo $res_env->telefono ?><br>
								<i class="fa fa-envelope"></i>
								<?php echo $res_env->email ?><br>
									<i class="fa fa-map-marker-alt"></i>
									<?php echo $res_env->direccion . ', ' . $res_env->cp . ', ' . $res_env->localidad . ', ' . $res_env->provincia . ', ' . $res_env->pais ?>
								</td>
								<td></td>
						</tr>
						<tr class="datos izq dir">
							<td rowspan="1" colspan="2">Datos de facturación:</td>
							<td colspan="1" class="izq dat">
								<i class="fa fa-user"></i>
								<?php echo $res_fact->nombre . ', ' . $res_fact->apellidos ?><br>
								<i class="fa fa-address-card"></i>
								<?php echo $res_fact->nif ?><br>
								<i class="fa fa-file"></i>
								<?php echo $res_fact->tipo_factura ?><br>
								<i class="fa fa-phone"></i>
								<?php echo $res_fact->telefono ?><br>
									<i class="fa fa-map-marker-alt"></i>
									<?php echo $res_fact->direccion . ', ' . $res_fact->cp . ', ' . $res_fact->localidad . ', ' . $res_fact->provincia . ', ' . $res_fact->pais ?>
								</td>
								<td></td>
						</tr>
						<tr class="datos izq dir">
							<td colspan="2" class="">Método de pago:</td>
							<td colspan="1" class="izq dat">
								<i class="fas fa-euro-sign"></i>
								<?php echo $res_pag->nombre  ?><br>
								</td>
								<td></td>
						</tr>
						<tr class="datos izq dir">
							<td colspan="2" class="">Estado del pago:</td>
							<td colspan="1" class="izq dat">
								<?php if ( $reg->cancelado == 1 ) { ?>
									<?php echo "<span class='cancel'>CANCELADO</span>" ?>
								<?php }else { ?>
									<span
										id="estado_pago"
										url="form_pago"
										estado="<?php echo $reg->estado_pago ?>"
										class="estado <?php echo strtolower( $reg->estado_pago ) ?>"
										onclick="openModal( $(this).attr('url'), '<?php echo $reg->ref_pedido ?>', '<?php echo $reg->estado_pago ?>' )"
										data-bs-toggle="tooltip"
										data-bs-placement="top"
										title="Cambiar estado de pago del pedido <?php echo $reg->ref_pedido ?>">
										<?php echo $reg->estado_pago ?>
									</span>
									<span><?php echo ( is_null( $reg->fecha_pago) ) ? '' : '   -   [ ' . cambia_fecha_hora ( $reg->fecha_pago ) . ' ]'; ?></span>
								<?php } ?>
							</td>
							<td></td>
						</tr>
						<tr class="datos izq dir">
							<td colspan="2" class="">Estado del envío:</td>
							<td colspan="1" class="izq dat">
								<?php if ( $reg->cancelado == 1 ) { ?>
									<?php echo "<span class='cancel'>CANCELADO</span>" ?>
								<?php }else { ?>
									<span
										id="estado_envio"
										url="form_envio"
										estado="<?php echo $reg->estado_envio ?>"
										class="estado <?php echo strtolower( $reg->estado_envio ) ?>"
										onclick="openModal( $(this).attr('url'), '<?php echo $reg->ref_pedido ?>', '<?php echo $reg->estado_envio ?>' )"
										data-bs-toggle="tooltip"
										data-bs-placement="top"
										title="Cambiar estado de envío del pedido <?php echo $reg->ref_pedido ?>"
										><?php echo $reg->estado_envio ?>
									</span>
								<?php } ?>

								<?php if ( $reg->estado_envio == 'Enviado' ) { ?>
									<span><?php echo ( !empty( $tracking->fecha_envio ) ) ? '    -   [ ' . $tracking->fecha_envio . ' ]' : ''; ?></span>
								<?php } ?>
								</td>
							<td></td>
						</tr>

						<?php if( !empty($tracking->transportista)) { ?>
							<tr class="datos izq dir">
								<td colspan="2" class="">Transportista:</td>
								<td colspan="1" class="izq dat">
									<?php echo $tracking->transportista ?><br>
									</td>
									<td></td>
							</tr>
						<?php } ?>

						<?php if( !empty($tracking->num_tracking)) { ?>
							<tr class="datos izq dir">
								<td colspan="2" class="">Código seguimiento:</td>
								<td colspan="1" class="izq dat">
									<?php echo $tracking->num_tracking ?><br>
									</td>
									<td></td>
							</tr>
							<tr class="datos izq dir">
								<td colspan="2" class="">Email seguimento enviado a cliente:</td>
								<td colspan="1" class="izq dat envio_email">
									<?php echo ( $tracking->email_enviado == 1 ) ? '<i class="fa fa-check-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Los datos de seguimiento se han enviado al cliente por email"></i>' : '<i class="fa fa-times-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="No se ha enviado datos de seguimiento al cliente"></i>' ; ?><br>
								</td>
								<td class="btn_envio"></td>
							</tr>
						<?php } ?>

					</tbody>
				</table>
			</div>
			</div>
			<div class="sep30"></div>
			<div class="row">
				<div class="col-md-4">
					<button class="delete cancel"
								data-bs-toggle="tooltip"
								data-bs-placement="top"
								url="form_cancela";
								title="Cancelar pedido '<?php echo $reg->ref_pedido ?>'"
								onclick="openModal( $(this).attr('url'), '<?php echo $reg->ref_pedido ?>', '<?php echo $reg->estado_envio ?>' )">Cancelación pedido
								<i class="fas fa-ban"></i>
				  </button>
				</div>
				<div class="col-md-4 ">
					<div class="btns center">
						<button onclick="window.location.href='index'" class="cart_btn">Volver</button>
					</div>
				</div>
				<div class="col-md-4" style="text-align: right;">
					<button class="delete"
								data-bs-toggle="tooltip"
								data-bs-placement="top"
								title="Eliminar pedido '<?php echo $reg->ref_pedido ?>'"
								onclick="if (confirm('Si aceptas se eliminará el pedido <?php echo $reg->ref_pedido ?> ¿Estas seguro?') == true ) { eliminaPedido( <?php echo $reg->id ?> )}">Elimina pedido
								<i class="fa fa-trash"></i>
				  </button>
				</div>
			</div>

			<div class="sep30"></div>
		</div>
	</div>
	</div>
	<div class="sep40"></div>

	<div class="modal fade" id="datos-pedido">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<?php include ('./includes/footer.php') ?>

	</body>
</html>

<script>
	$('.item.ped').addClass('menu_actual');
	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}
	function checkScroll(scroll) {
		if (scroll > 120) {
				var left = $('.menu-lat').width()+$(window).width()*0.09;
				$('h1').addClass("fixed borde_sombra").css('left', left + 'px');
		}
		else {
			$('h1').removeClass("fixed borde_sombra");
		}
	}
	function openModal( url, ref_pedido, estado) {
			// alert(url+id+type);
			var myModal = new bootstrap.Modal(document.getElementById('datos-pedido'), {
				keyboard: false
			})
			$.ajax({
				url: './includes/' + url + '.php',
				type: 'POST',
				datatype: 'html',
				data: {ref_pedido:ref_pedido, estado:estado}
			})
			.done(function(result) {
				$('#datos-pedido .modal-body').html(result);
				myModal.show();

			})
			.fail(function() {
				alert('Se ha producido un error');
			})
	}
	function eliminaPedido ( id_pedido ) {

		$.ajax({
			url: './assets/lib/admin_control.php',
			type: 'post',
			dataType: 'text',
			data: {'accion': 'elimina_pedido', 'id_pedido': id_pedido }
		})
		.done(function(result) {
			var result = $.parseJSON(result);
			if(result.res==0) {
				muestraMensajeLn(result.msg)
			}else if (result.res==1) {
				muestraMensajeLn(result.msg);
				setTimeout(function() {
					window.location.href = "/admin/index";
				},2000);
			}
		})
		.fail(function() {
			alert("error");
		});

	}

</script>


