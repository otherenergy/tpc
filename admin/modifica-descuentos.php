<?php
$pagina = "Configuración descuentos";
$title = "Configuración descuentos";
$description = "Configuración descuentos";
include('./includes/header.php');

$idiomas = array('es', 'en', 'en_us', 'fr', 'it', 'de');
// $descuentos = array(0,10,20,30,40,50,60,70,80,90,100);
$descuentos = array(0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 70, 80);
$kits_micro = array(1, 2, 23, 812, 813, 981, 982, 815, 816);

?>

<style>
	.item_nombre {
		background-color: #35471e;
		padding: 18px 20px;
		font-weight: 600;
		margin: 20px 0 0px;
		color: #fff;
	}

	.form-group.item_idioma {
		width: 12%;
		display: inline-block;
	}

	.item_idioma label {
		font-size: 12px;
		font-weight: 600;
		line-height: 13px;
		margin-top: 10px;
		position: relative;
	}

	.item_producto {
		margin-bottom: 60px;
	}

	.grupo_descuentos {
		display: flex;
		flex-direction: row;
		flex-wrap: nowrap;
		justify-content: space-around;
		border: 1px solid #9f9f9f;
		padding: 15px 5px;
		margin-bottom: 20px;
		position: relative;
	}

	.center.todos_colores,
	.center.segun_colores {
		margin-bottom: 16px;
		font-weight: 600;
	}

	.guarda_descuento {
		height: 27px;
		position: relative;
		top: 33px;
		font-size: 14px;
	}

	.item_producto .color_item img {
		height: 73px;
	}

	.color_item .nombre_color {
		font-size: 18px;
		font-weight: 600;
		position: relative;
		top: -32px;
	}

	.grupo_descuentos .color_item {
		height: 74px;
	}

	.config_todos {
		/* border: 1px solid #9f9f9f; */
		padding: 25px 0px 40px;
		display: none;
	}

	.bloque_producto {
		display: none;
		border: 1px solid;
		padding: 30px 10px 0;
		margin-bottom: 50px;
	}

	.item_nombre:hover {
		cursor: pointer;
	}

	.item_nombre.descuentos i {
		float: right;
		font-size: 16px;
		position: relative;
		top: 5px;
	}

	button.guarda_descuento {
		background-color: #35471e;
		color: #fff;
		border: 1px solid #35471e;
	}

	button.guarda_descuento:hover {
		background-color: transparent;
		color: #35471e;
		border: 1px solid #35471e;
	}

	button.guarda_descuento.todos {
		top: 10px;
	}

	.center.segun_colores span,
	.center.todos_colores span {
		border: 1px solid;
		padding: 4px 10px;
	}

	.center.segun_colores span:hover,
	.center.todos_colores span:hover {
		background-color: #35471e;
		color: #fff;
		cursor: pointer;
	}

	.item_idioma img {
		margin-left: 3px;
	}

	.center {
		text-align: center !important;
	}

	select#cambia_todos {
		width: 48px;
		height: 18px;
		position: absolute;
		right: 15px;
		text-align: center;
		font-size: 12px;
		top: 14px;
	}

	select.form-control {
		font-size: 14px;
	}

	.item_idioma select {
		margin-top: 7px;
	}

	.espacio {
		margin-right: 5%;
	}

	.table-responsive.descuentos {
		display: none;
	}

	.table-responsive.descuentos th img {
		margin-left: 10px;
		position: relative;
		bottom: 2px;
	}



	@media (max-width: 768px) {

		.menu-panel {
			display: none;
		}

		.config_todos {
			padding: 25px 0px 40px;
			display: none;
		}

		.form-group.item_idioma {
			width: 45%;
			display: inline-block;
			margin-bottom: 7px;
			margin-right: 4%;
		}

		.grupo_descuentos {
			display: block;
		}

		.grupo_descuentos.todos {
			padding: 15px 0 13px 16px;
		}

		select#cambia_todos {
			bottom: 12px;
			top: unset;
		}

		.guarda_descuento {
			height: 27px;
			position: relative;
			font-size: 14px;
			margin-top: 11px;
			top: 0;
		}

		.grupo_descuentos img.variacion {
			width: 100% !important;
		}

		.color_item .nombre_color {
			font-size: 20px;
			font-weight: 600;
			position: relative;
			top: -50px;
		}

		.grupo_descuentos .color_item {
			height: 75px;
			margin-bottom: 12px;
			padding-right: 12px;
		}

		.espacio {
			margin-right: unset;
		}

	}
</style>

<body class="listado-articulos">
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
			<div class="col-md-1">
				<div class="sepv"></div>
			</div>
			<div class="col-md-1"></div>
			<div class="col-md-6">
				<h1 class="center"><?php echo $pagina ?></h1>
				<div class="sep20"></div>

				<div class="contenido">

					<?php

					$productos = array(
						"Kits 4m2 (ABS y NO ABS)" => 815,
						"Kits 8m2 (ABS y NO ABS)" => 1,
						"Kits 16m2 (ABS y NO ABS)" => 812,
						"Kits 24m2 (ABS y NO ABS)" => 981,
						"Pintura Smartcover Tiles" => 23,
						"Kit herramientas" => 430
					);
					?>

					<div class="listado_productos">

						<?php foreach ($productos as $nombre => $id_prod) { ?>


							<div class="item_nombre descuentos">
								<?php echo $nombre ?>
								<i class="fas fa-plus"></i>
							</div>

							<div class="bloque_producto">

								<div class="item_producto item_producto_<?php echo $id_prod ?> ">

									<div class="center todos_colores"><span>Configuración para todos los colores</span></div>

									<form id="form_<?php echo $id_prod ?>" form_prod="<?php echo $id_prod ?>" action="">

										<div class="grupo_descuentos todos">


											<?php foreach ($idiomas as $idioma) {

												$exploded = explode('_', $idioma);
												$flag = end($exploded);
											?>

												<div class="form-group item_idioma <?php echo $idioma ?>">
													<label for="todos_<?php echo $idioma ?>">Descuento <?php echo $flag ?>
														<img class="" src="./assets/img/flags/<?php echo $flag ?>.png" alt="<?php echo $flag ?>" title="<?php echo $flag ?>" style="width: 20px;height: 15px;">
													</label>
													<select class="form-control prod<?php echo $id_prod ?>" name="descuento_<?php echo $idioma ?>">
														<option value="-">-</option>
														<?php foreach ($descuentos as $descuento) { ?>
															<option value="<?php echo $descuento ?>"><?php echo $descuento . '%' ?></option>
														<?php } ?>
													</select>
												</div>

											<?php } ?>

											<input type="hidden" name="id_prod" value="<?php echo $id_prod ?>">
											<input type="hidden" name="accion" value="actualiza_descuentos_promocion">

											<button type="submit" class="guarda_descuento">Guardar</button>

									</form>

									<select name="cambia_todos" id="cambia_todos" prd="prod<?php echo $id_prod ?>">
										<option value="-">-</option>
										<?php foreach ($descuentos as $descuento) { ?>
											<option value="<?php echo $descuento ?>"><?php echo $descuento . '%' ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<?php if (in_array($id_prod, $kits_micro)) { ?>

								<div class="item_producto item_producto_<?php echo $id_prod ?> ">

									<div class="center segun_colores"><span>Configuración según el color</span></div>

									<div class="config_todos">

										<?php
										$res = consulta_colores($id_prod);
										$lista_colores = [];
										while ($reg = $res->fetch_object()) {
											if (!in_array($reg->valor, $lista_colores) && $reg->publicado == 1) {
												$lista_colores[] = $reg->valor;
										?>
												<form id="form_<?php echo $id_prod ?>" form_prod="<?php echo $id_prod ?>" action="">
													<div class="grupo_descuentos">

														<div class="color_item">
															<img src="./assets/img/colores/<?php echo $reg->valor ?>.jpg" alt="Color <?php echo $reg->valor ?>" title="Color <?php echo $reg->valor ?>" class="variacion">
															<div class="nombre_color" style="text-align: center;"><small><?php echo $reg->valor ?></small></div>
														</div>

														<?php foreach ($idiomas as $idioma) {
															$exploded = explode('_', $idioma);
															$flag = end($exploded);
														?>

															<div class="form-group item_idioma <?php echo $idioma ?> color_idioma">
																<label for="todos_<?php echo $idioma ?>">Descuento <?php echo $flag ?>
																	<img class="" src="./assets/img/flags/<?php echo $flag ?>.png" alt="<?php echo $flag ?>" title="<?php echo $flag ?>" style="width: 20px;height: 15px;"><br>
																	<select class="form-control <?php echo $reg->valor . '-' . $id_prod ?>" name="descuento_<?php echo $idioma ?>">

																		<option value="-">-</option>

																		<?php foreach ($descuentos as $descuento) { ?>
																			<option value="<?php echo $descuento ?>"><?php echo $descuento . '%' ?></option>
																		<?php } ?>

																	</select>
															</div>

														<?php } ?>

														<input type="hidden" name="id_prod" value="<?php echo $id_prod ?>">
														<input type="hidden" name="id_color" value="<?php echo $reg->color_id ?>">
														<input type="hidden" name="accion" value="actualiza_descuentos_por_color_promocion">

														<button type="submit" class="guarda_descuento">Guardar</button>

														<select name="cambia_todos" id="cambia_todos" prd="<?php echo $reg->valor . '-' . $id_prod ?>">
															<option value="-">-</option>
															<?php foreach ($descuentos as $descuento) { ?>
																<option value="<?php echo $descuento ?>"><?php echo $descuento . '%' ?></option>
															<?php } ?>
														</select>

												</form>

									</div>

							<?php
											}
										}
							?>

								</div>

							<?php } ?>


					</div>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="sep40"></div>

	<?php

	// Adaptando la consulta para obtener descuentos desde productos_precios_new
	$sql = "
    SELECT 
        p.id, 
        p.sku, 
        p.nombre_es, 
        MAX(CASE WHEN pp.cod_pais = 'ES' THEN pp.descuento ELSE 0 END) AS descuento_es,
        MAX(CASE WHEN pp.cod_pais = 'GB' THEN pp.descuento ELSE 0 END) AS descuento_en,
        MAX(CASE WHEN pp.cod_pais = 'US' THEN pp.descuento ELSE 0 END) AS descuento_en_us,
        MAX(CASE WHEN pp.cod_pais = 'FR' THEN pp.descuento ELSE 0 END) AS descuento_fr,
        MAX(CASE WHEN pp.cod_pais = 'IT' THEN pp.descuento ELSE 0 END) AS descuento_it,
        MAX(CASE WHEN pp.cod_pais = 'DE' THEN pp.descuento ELSE 0 END) AS descuento_de
    FROM productos p
    LEFT JOIN productos_precios_new pp ON p.id = pp.id_producto
    WHERE pp.descuento > 0
    GROUP BY p.id, p.sku, p.nombre_es
";

	$res = consulta($sql, $conn);

	?>

	<div class="container">
		<div class="row">
			<div class="col-md-1 espacio"></div>
			<div class="col-md-10" style="padding: 0">
				<div class="center">
					<button type="submit" class="guarda_descuento actuales">Ver descuentos activos</button>
				</div>
				<div class="sep50"></div>
				<div class="table-responsive descuentos">
					<table id="tbl_pedidos" class="table carrito dir_fac">
						<thead>
							<tr>
								<th class="izq des">Id</th>
								<th class="izq des">SKU</th>
								<th class="izq des">Articulo</th>
								<th class="izq des">Descuento ES<img class="" src="./assets/img/flags/es.png" alt="es" title="es" style="width: 20px;height: 15px;"></th>
								<th class="izq des">Descuento EN<img class="" src="./assets/img/flags/en.png" alt="en" title="en" style="width: 20px;height: 15px;"></th>
								<th class="izq des">Descuento US<img class="" src="./assets/img/flags/us.png" alt="us" title="us" style="width: 20px;height: 15px;"></th>
								<th class="izq des">Descuento FR<img class="" src="./assets/img/flags/fr.png" alt="fr" title="fr" style="width: 20px;height: 15px;"></th>
								<th class="izq des">Descuento IT<img class="" src="./assets/img/flags/it.png" alt="it" title="it" style="width: 20px;height: 15px;"></th>
								<th class="izq des">Descuento DE<img class="" src="./assets/img/flags/de.png" alt="de" title="de" style="width: 20px;height: 15px;"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (numFilas($res) > 0) {
								while ($reg = $res->fetch_object()) {
							?>
									<tr class="datos pedidos">
										<td class="izq des"><?php echo $reg->id ?></td>
										<td class="izq uso"><?php echo $reg->sku ?></td>
										<td class="izq uso"><?php echo $reg->nombre_es ?></td>
										<td class="izq uso"><?php echo $reg->descuento_es ?> %</td>
										<td class="izq uso"><?php echo $reg->descuento_en ?> %</td>
										<td class="izq uso"><?php echo $reg->descuento_en_us ?> %</td>
										<td class="izq uso"><?php echo $reg->descuento_fr ?> %</td>
										<td class="izq uso"><?php echo $reg->descuento_it ?> %</td>
										<td class="izq uso"><?php echo $reg->descuento_de ?> %</td>
									</tr>
								<?php }
							} else { ?>
								<tr class="datos pedidos">
									<td class="izq des" colspan="9" style="text-align: center;">No hay descuentos activos</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>




	<div class="sep100"></div>

	<div class="modal fade" id="datos-pedido">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<?php include('./includes/footer.php') ?>
	<script type="text/javascript" src="./assets/js/printThis.js"></script>
	<script>
		$(document).ready(function() {

			$('.item_nombre.descuentos').click(function() {
				$(this).next('.bloque_producto').fadeToggle(200);
			});

			$('.center.segun_colores').click(function() {
				$(this).next('.config_todos').fadeToggle(200);
			});

			$('.center.todos_colores').click(function() {
				$(this).next('.grupo_descuentos').fadeToggle(200);
			});

			$('select#cambia_todos').change(function(event) {

				var prd = $(this).attr('prd');
				var valor = $(this).val();
				$('select.' + prd + '').each(function() {
					$(this).val(valor);
				});
			});

			$('button.guarda_descuento.actuales').click(function(event) {
				$('.table-responsive.descuentos').toggle('fast');
			});

			$('#tbl_pedidos').DataTable({
				language: {
					url: 'includes/es-ES.json',
				},
				"pageLength": 25,
				"order": [
					[1, 'desc']
				]

			});

		});

		$('form').submit(function(e) {

			e.preventDefault();
			var datos = $(this).serialize();

			$.ajax({
					url: './assets/lib/admin_control.php',
					type: 'post',
					dataType: 'text',
					data: datos
				})
				.done(function(result) {
					var result = $.parseJSON(result);
					if (result.res == 0) {
						muestraMensajeLn(result.msg)
					} else if (result.res == 1) {
						muestraMensajeLn(result.msg);
						setTimeout(function() {
							// location.reload();
						}, 2000);
					}
				})
				.fail(function() {
					alert("error");
				});

		});
	</script>

</body>

</html>

<script>
	$('.item.descuentos').addClass('menu_actual');
	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}

	function checkScroll(scroll) {
		if (scroll > 120) {
			var left = $('.menu-lat').width() + $(window).width() * 0.09;
			$('h1').addClass("fixed borde_sombra").css('left', left + 'px');
		} else {
			$('h1').removeClass("fixed borde_sombra");
		}

	}
</script>