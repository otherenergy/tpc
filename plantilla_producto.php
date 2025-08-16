<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');

include('../config/db_connect.php');
include('../class/userClass.php');

$userClass = new userClass();

$url_metas = $userClass->url_metas($id_url, $id_idioma);
$producto_prod = $userClass->producto($id_url);
$id_producto = $producto_prod->id;
$producto_info = $userClass->producto_info($id_producto, $id_idioma);
$producto_precio = $userClass->producto_precio($id_producto, $id_idioma);
$url_tienda = $userClass->urls(5, $id_idioma);

include('../includes/vocabulario.php');
include('../includes/urls.php');

if ( $_SESSION['user_ubicacion'] != null){
$precio_base = number_format($producto_precio->precio_base, 2, ".", "");
$descuento = htmlspecialchars($producto_precio->descuento, ENT_QUOTES, 'UTF-8');
$precio_descuento = number_format(($precio_base) * (100 - $descuento) / 100, 2, ".", "");

$productos_tabs = $userClass->productos_tabs();
$categoria_producto = $userClass->categoria_producto($producto_prod->id_categoria, $id_idioma);

if ($url == 'index') {
	$url = '';
}
$moneda_obj = $userClass->obtener_moneda();
$moneda = $moneda_obj->moneda;

$id_productos = $producto_info->productos_relacionados;
$id_productos = explode('-', $id_productos);
$productos_relacionados = $userClass->productos_relacionados($id_productos, $id_idioma);
}

?>
<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
<?php include('../includes/head.php'); ?>

<body class="productos_class">

	<!-- Header - Inicio -->
	<?php include_once('../includes/header.php'); ?>
	<?php include_once('../includes/geolocation.php'); ?>

	<!-- Header - Fin -->
	<style>
		.imgs_products,
		.video_products {
			width: 80px;
			height: 80px;
			border: 1px solid grey;
			opacity: 0.6;
			margin: 5px;
			border-radius: 10px !important;
		}

		.class_imgs_products {
			max-width: 100%;
			margin-top: 20px;
		}

		.imgs_products:hover,
		.video_products:hover {
			border: 2px solid black;
			cursor: pointer;
		}

		.imgs_products:active,
		.video_products:active {
			border: 2px solid green;
		}

		.img_product_activado,
		.video_product_activado {
			opacity: 1 !important;
			border: 2px solid black;
			border-radius: 10px !important;
		}

		#contenedor_video_producto {
			width: 100%;
			padding-top: 100%;
			position: relative;
			background-color: white;
			border: 1px solid grey;
			position: relative;
			z-index: 2;
			border-radius: 20px;
		}

		#contenedor_video_producto .container.video {
			position: absolute;
			top: 50%;
			transform: translate(-50%, -50%);
			left: 50%;
			width: 100% !important;
		}
	</style>
	<section class="info_producto">
		<div class="row">
			<div class="img_product" id="productos_img_var">
				<?php
				$resultados = $userClass->fn_imagen_producto($id_url, $id_idioma);
				foreach ($resultados as $fila) {
					if ($fila->orden == "1") {
						$imagen_principal_producto = 'assets/img/productos/' . $fila->miniatura;
						echo "<div style='position: relative;'>";
							echo "<img src='../assets/img/productos/" . $fila->miniatura . "' alt='" . $fila->alt . "' title='" . $fila->alt . "' class='img_product' id='img-principal-prod' img-prod='../assets/img/productos/" . $fila->miniatura . "' color='' width='300px' height='300px'>  ";
							echo "<p class='p_color_ncs'></p>";
						echo "</div>";
					}
				}

				$contador = 0;
				foreach ($resultados as $fila) {

					if ($fila->orden == "1") {
						echo '
							<div id="contenedor_video_producto" class="video_product_activado" style="display:none;">
								<div class="container video">
									<section class="you_video row">
										<div class="video-responsive">
											<div class="div-video-responsive">
												<img src="../assets/img/video_home/cap3.png" alt="Img Smartcret" id="img-video-prod">
												<img style="position: absolute; z-index: 50 !important;" src="../assets/img/video_home/logo_youtube.png" alt="Play" width="57px"  height="40px" id="icono-yt-video">
												<div id="div-video-home" style="position: absolute; z-index: 52 !important;">
													<video style="z-index: 52 !important;" width="100%" id="miVideo" controls><source src="" type="video/mp4"></video>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>';
						echo "<div class='class_imgs_products'>";

						if ($fila->tipo == "imagen") {
							echo "<img src='../assets/img/productos/" . $fila->miniatura . "' alt='" . $fila->alt . "' title='" . $fila->alt . "' class='imgs_products img_product_activado' width='40px' height='40px' >";
						} elseif ($fila->tipo == "video" && $fila->enlace != "") {
							echo "<img src='../assets/video/" . $fila->miniatura . "' alt='" . $fila->alt . "' title='" . $fila->alt . "' class='imgs_products img_product_activado' video='../assets/video/" . $idioma_url . "/" . $fila->enlace . "' width='40px' height='40px' >";
						}
					} else {
						if ($fila->tipo == "imagen") {
							echo "<img src='../assets/img/productos/" . $fila->miniatura . "' alt='" . $fila->alt . "' title='" . $fila->alt . "' class='imgs_products' width='40px' height='40px' >";
						} elseif ($fila->tipo == "video" && $fila->enlace != "") {
							echo "<img src='../assets/video/" . $fila->miniatura . "' alt='" . $fila->alt . "' title='" . $fila->alt . "' class='video_products' video='../assets/video/" . $idioma_url . "/" . $fila->enlace . "' width='40px' height='40px' >";
						}
					}
				}

				echo "</div>";
				?>

				<?php if ($producto_precio->descuento != '0') {
					echo '<div style="background-color: #C93285 !important;" class="rebaja">-' . $producto_precio->descuento . '%</div>';
				} ?>

				<style>

					.txt_unidades{
						margin-bottom: 0px !important;
						color: #c93285;
					}
					.stock-bar-container {
						width: 100%;
						background-color: white;
						border-radius: 25px;
						margin: 0px 20px 0px 0px;
						box-shadow: inset 0 0 1px grey;
					}

					.stock-bar {
						width: 0%;
						height: 10px;
						background-color: #c93285;
						border-radius: 20px;
						text-align: center;
						line-height: 30px;
						color: white;
						font-weight: bold;
					}

				</style>

				<script>
					function updateStockBar(stockValue) {
						const stockBar = document.getElementById('stock-bar');
						stockBar.style.width = stockValue + '%';
						// stockBar.innerHTML = stockValue + '%';
					}

					updateStockBar(25);

				</script>
			</div>
			<div class="desc_product">

				<div class="contendor_invertidor_prod">
				<div class="contenedor_info_producto">

				<h1><?php echo $producto_info->nombre ?></h1>
				<div class="barra_horizontal"> </div>
				<?php

					if ($precio_descuento > 100) {
						if ($descuento == '0') {
							echo '<div class="price">
									<span class="verde">
										<strong style="margin-left: 0px;">' . $precio_base . $moneda . '<small> ' . $vocabulario_IVA . '</small>  </strong>
										<!-- <p class="peso_txt">' . $producto_prod->peso . 'Kg <img src="../assets/img/iconos/icono_peso.png" alt=""><</p> -->
									</span>
									<p class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</p>
								</div>';
						} else {
							echo '<div class="price">
									<span class="verde">
										<strong class="descuento">' . $precio_base . $moneda . '</strong>
										<strong>' . $precio_descuento . $moneda . '<small> ' . $vocabulario_IVA . '</small> </strong>
										<!-- <p class="peso_txt">' . $producto_prod->peso . 'Kg <img src="../assets/img/iconos/icono_peso.png" alt=""></p> -->
									</span>
									<p class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</p>
								</div>';
						}
					}else{
						if ($descuento == '0') {
							echo '<div class="price">
									<span class="verde">
										<strong style="margin-left: 0px;">' . $precio_base . $moneda . '<small> ' . $vocabulario_IVA . '</small>  </strong>
										<!-- <p class="peso_txt">' . $producto_prod->peso . 'Kg <img src="../assets/img/iconos/icono_peso.png" alt=""><</p> -->
									</span>
								</div>';
						} else {
							echo '<div class="price">
									<span class="verde">
										<strong class="descuento">' . $precio_base . $moneda . '</strong>
										<strong>' . $precio_descuento . $moneda . '<small> ' . $vocabulario_IVA . '</small> </strong>
										<!-- <p class="peso_txt">' . $producto_prod->peso . 'Kg <img src="../assets/img/iconos/icono_peso.png" alt=""></p> -->
									</span>
								</div>';
						}
					}

				?>
				<div class="barra_horizontal_clara"> </div>
				<?php echo $producto_info->descripcion_corta ?>

					</div>
					<div class="contenedor_info_formatos">

				<?php

				if ($producto_prod->formato == '1111') {
					$variantes_formato = $userClass->variantes_formato($id_producto);
					$unique_formatos = [];
					foreach ($variantes_formato as $variante) {
						$key = $variante->formato . '-' . $variante->valor;
						if (!isset($unique_formatos[$key])) {
							$unique_formatos[$key] = $variante;
						}
					}

					include('../includes/atributos_productos/atributo_formato.php');
				}

				if ($producto_prod->acabado == '1111') {
					$variantes_acabado = $userClass->variantes_acabado($id_producto, $id_idioma);
					$unique_acabados = [];
					foreach ($variantes_acabado as $variante) {
						$key = $variante->acabado . '-' . $variante->valor;
						if (!isset($unique_acabados[$key])) {
							$unique_acabados[$key] = $variante;
						}
					}

					include('../includes/atributos_productos/atributo_acabado.php');
				}
				if ($producto_prod->color == '1111') {
					$variantes_color = $userClass->variantes_color($id_producto);
					include('../includes/atributos_productos/atributo_color.php');
				}

				?>
					</div>
				</div>

				<div class="barra_horizontal_clara"> </div>

				<form method="post" action="carrito.php">
					<div class="div-cantidad-carrito">
						<div class="cant">
							<label class="txt-cantidad" for="cant"><?php echo $vocabulario_cantidad ?></label>
							<div class="form-check form-check-inline" style="margin-right:0;margin-top:1%;display:block;">
								<div class="contadores input-group no-select">
									<div class="input-group-btn btn-menos">
										<div class="menos"><i class="fa fa-minus"></i></div>
									</div>
									<input type="text" id="cant" name="cant" class="inputcajas form-control input-number" value="1" min="1" max="999" readonly="true" />
									<div class="input-group-btn btn-mas">
										<div class="mas"><i class="fa fa-plus"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class="tocart">
						<?php if ($producto_prod->juntas == '1111'){ ?>
							<button id="submit" juntas="1" idProd="<?php echo $producto_prod->id ?>" cantidad="1" type="button" class="btn palcarrito btn-lg mb-3" id_idioma="<?php echo $id_idioma ?>" onclick="addToCart( $(this), <?php echo !empty($producto_prod->color) ? $producto_prod->color : 'null' ?> ,  <?php echo !empty($producto_prod->juntas) ? $producto_prod->juntas : 'null' ?> ,  <?php echo !empty($producto_prod->acabado) ? $producto_prod->acabado : 'null' ?> ,  <?php echo !empty($producto_prod->formato) ? $producto_prod->formato : 'null' ?> , '<?php echo $vocabulario_selecciona ?>', '<?php echo $vocabulario_color ?>', '<?php echo $vocabulario_juntas ?>', '<?php echo $vocabulario_acabado ?>', '<?php echo $vocabulario_formato ?>' )" variante='<?php echo ($producto_prod->color === "1111" || $producto_prod->juntas === "1111" || $producto_prod->acabado === "1111" || $producto_prod->formato === "1111") ? $producto_prod->id : 0; ?>'><?php echo $vocabulario_anadir_carrito ?></button>
							<div class="metodos-pago"><img src="../assets/img/iconos/pago-seguro-garantizado-3ways.png" alt="Metodos de pago" width='79px' height='100px'></div>
						<?php }else{ ?>
							<button id="submit" idProd="<?php echo $producto_prod->id ?>" cantidad="1" type="button" class="btn palcarrito btn-lg mb-3" id_idioma="<?php echo $id_idioma ?>" onclick="addToCart( $(this), <?php echo !empty($producto_prod->color) ? $producto_prod->color : 'null' ?> ,  <?php echo !empty($producto_prod->juntas) ? $producto_prod->juntas : 'null' ?> ,  <?php echo !empty($producto_prod->acabado) ? $producto_prod->acabado : 'null' ?> ,  <?php echo !empty($producto_prod->formato) ? $producto_prod->formato : 'null' ?> , '<?php echo $vocabulario_selecciona ?>', '<?php echo $vocabulario_color ?>', '<?php echo $vocabulario_juntas ?>', '<?php echo $vocabulario_acabado ?>', '<?php echo $vocabulario_formato ?>' )" variante='<?php echo ($producto_prod->color === "1111" || $producto_prod->juntas === "1111" || $producto_prod->acabado === "1111" || $producto_prod->formato === "1111") ? $producto_prod->id : 0; ?>'><?php echo $vocabulario_anadir_carrito ?></button>
							<div class="metodos-pago"><img src="../assets/img/iconos/pago-seguro-garantizado-3ways.png" alt="Metodos de pago" width='79px' height='100px'></div>
						<?php } ?>
						</div>
					</div>
					<div class='envios'><?php echo $vocabulario_plazos_envio ?></div>
					<?php if($id_idioma=="1") {
						echo "<div class='envios' style='font-size: 14px; font-weight:700;'>".$vocabulario_envios_gratuitos."</div>";
					}
					?>

				</form>

				<!-- cambios -->
			</div>
		</div>
	</section>

	<script>
		function watchButtonAttributes() {
			const button = document.getElementById('submit');

			const observer = new MutationObserver((mutations) => {
				mutations.forEach((mutation) => {
					if (mutation.type === 'attributes') {

						console.log("YES");
						consultaPrecios(button);
						if ('<?php echo $producto_prod->color; ?>' == '1111' && button.getAttribute('color') && '<?php echo $producto_prod->formato; ?>' == '0' && '<?php echo $producto_prod->acabado; ?>' == '0') {
							consultaPrecios(button);
						} else if ('<?php echo $producto_prod->color; ?>' == '1111' && button.getAttribute('color') && '<?php echo $producto_prod->formato; ?>' == '1111' && button.getAttribute('formato') && '<?php echo $producto_prod->acabado; ?>' == '0') {
							consultaPrecios(button);
						} else if ('<?php echo $producto_prod->color; ?>' == '1111' && button.getAttribute('color') && '<?php echo $producto_prod->formato; ?>' == '1111' && button.getAttribute('formato') && '<?php echo $producto_prod->acabado; ?>' == '1111' && button.getAttribute('acabado')) {
							consultaPrecios(button);
						} else if ('<?php echo $producto_prod->color; ?>' == '0' && '<?php echo $producto_prod->formato; ?>' == '1111' && button.getAttribute('formato') && '<?php echo $producto_prod->acabado; ?>' == '0') {
							consultaPrecios(button);
						} else if ('<?php echo $producto_prod->color; ?>' == '0' && '<?php echo $producto_prod->formato; ?>' == '1111' && button.getAttribute('formato') && '<?php echo $producto_prod->acabado; ?>' == '1111' && button.getAttribute('acabado')) {
							consultaPrecios(button);
						} else if ('<?php echo $producto_prod->color; ?>' == '1111' && button.getAttribute('color') && '<?php echo $producto_prod->formato; ?>' == '0' && '<?php echo $producto_prod->acabado; ?>' == '1111' && button.getAttribute('acabado')) {
							consultaPrecios(button);
						} else if ('<?php echo $producto_prod->color; ?>' == '1111' && button.getAttribute('color') && '<?php echo $producto_prod->acabado; ?>' == '1111' && button.getAttribute('acabado')) {
							consultaPrecios(button);
						}

					}
				});
			});

			observer.observe(button, {
				attributes: true
			});
		}

		function consultaPrecios(button) {
			const cod_pais = '<?php echo $_SESSION['user_ubicacion']; ?>';
			const color = button.getAttribute('color') || '0';
			const acabado = button.getAttribute('acabado') || '0';
			const juntas = button.getAttribute('juntas') || '0';
			const formato = button.getAttribute('formato') || '0';

			const id_producto = '<?php echo $id_producto ?>';

			fetch('../includes/consulta_precios.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: new URLSearchParams({
						cod_pais: cod_pais,
						color: color,
						acabado: acabado,
						juntas: juntas,
						formato: formato,
						id_producto: id_producto
					})
				})
				.then(response => response.json())
				.then(data => {

					$('.img_product .rebaja').css('z-index', '-1');
					var precio_base = data[0]['precio_base'];
					var precio_descuento = data[0]['precio'];
					console.log(precio_base)
					console.log(precio_descuento)
					if (precio_base == precio_descuento) {
						precio = `<strong style="margin-left: 0px;">${precio_base}<?php echo $moneda ?><small> ${vocabularios.vocabulario_IVA}</small> </strong>`;
					} else {
						precio = `<strong class="descuento">${precio_base}<?php echo $moneda ?></strong>
					<strong>${precio_descuento}<?php echo $moneda ?><small> ${vocabularios.vocabulario_IVA}</small> </strong>`;

						precio = `<strong class="descuento">${precio_base}<?php echo $moneda ?></strong>
					<strong>${precio_descuento}<?php echo $moneda ?><small> ${vocabularios.vocabulario_IVA}</small> </strong>`
					}
					$('.price span').html(precio);

				})
				.catch(error => console.error('Error:', error));
		}

		watchButtonAttributes();
	</script>

	<section class="product_tabs">
		<div class="row">
			<div class="col-md-12">
				<div class="bs-example">
					<ul class="nav nav-tabs">
						<?php
						foreach ($productos_tabs as $contenido) {
							$div_id_tabs = $contenido->div_id;
						?>
							<?php
							if ($producto_info->$div_id_tabs != "") { ?>
								<?php if ($contenido->orden == 1) { ?>
									<li class="nav-item">
										<a href="#<?php echo $contenido->div_id ?>" class="nav-link active" data-toggle="tab"><?php echo $contenido->$idioma_url ?></a>
										<img class="img_nav_item" src="../assets/img/iconos/icono_flecha_desc_prod.svg" alt="Icon" width='12px' height='12px'>
									</li>
								<?php } else { ?>
									<li class="nav-item">
										<a href="#<?php echo $contenido->div_id ?>" class="nav-link" data-toggle="tab"><?php echo $contenido->$idioma_url ?></a>
										<img class="img_nav_item ocultar" src="../assets/img/iconos/icono_flecha_desc_prod.svg" alt="Icon" width='12px' height='12px'>
									</li>
						<?php }
							}
						}
						?>
					</ul>
					<div class="tab-content">
						<?php
						foreach ($productos_tabs as $contenido) {
							$div_id_tabs = $contenido->div_id;
							if ($div_id_tabs == "aplicacion") {

								if ($producto_info->$div_id_tabs != "") { ?>
									<?php
									if ($contenido->orden == 1) { ?>
										<div class="tab-pane fade show active" id="<?php echo $contenido->div_id ?>">
										<?php
									} else { ?>
											<div class="tab-pane fade" id="<?php echo $contenido->div_id ?>">
											<?php } ?>
											<?php echo $producto_info->$div_id_tabs ?>


											<?php
											if ($producto_info->paso_paso == 1) {
												$paso_paso = $userClass->paso_paso($producto_info->paso_paso, $id_idioma); ?>
												<div class="center btn_paso">
													<a class="btn btn-lg mb-3" href="../assets/downloads/<?php echo $paso_paso->valor ?>" target="_blank"><?php echo $paso_paso->texto ?><i class="fa fa-download"></i></a>
												</div>
											</div>
										<?php
											} else { ?>
										</div>
									<?php
											}
										}
									} elseif ($div_id_tabs == "ficha_tecnica") {
										if ($producto_info->$div_id_tabs != "") {
											$id_fichas_tecnicas = explode('-', $producto_info->$div_id_tabs);
											$fichas_tecnicas = $userClass->fichas_tecnicas($id_fichas_tecnicas, $id_idioma);
									?>
									<?php
											if ($contenido->orden == 1) { ?>
										<div class="tab-pane fade show" id="<?php echo $contenido->div_id ?>">
										<?php
											} else { ?>
											<div class="tab-pane fade" id="<?php echo $contenido->div_id ?>"> <?php
																											} ?>
											<!-- <h2 class="mt-5"><?php echo $vocabulario_fichas_tencicas ?> <?php echo $producto_info->nombre ?></h2> -->
											<?php echo $producto_info->ficha_tecnica_txt; ?>
											<ul>
												<?php
												foreach ($fichas_tecnicas as $contenido) { ?>
													<li style="list-style:none;"><a href="../assets/downloads/<?php echo $contenido->valor ?>" class="verde" style="text-decoration:none;color: rgba(0,0,0,.5);" target="_blank"><img src="../assets/img/iconos/pdf.jpg" width='50px' height='30px' alt="<?php echo $contenido->texto ?>" title="<?php echo $contenido->texto ?>"><?php echo $contenido->texto ?></a></li>
												<?php
												} ?>
											</ul>
											</div>
										<?php
										}
									} else {
										if ($producto_info->$div_id_tabs != "") { ?>
											<?php
											if ($contenido->orden == 1) { ?>
												<div class="tab-pane fade show active" id="<?php echo $contenido->div_id ?>">
												<?php
											} else { ?>
													<div class="tab-pane fade" id="<?php echo $contenido->div_id ?>">
													<?php } ?>
													<?php echo $producto_info->$div_id_tabs ?>
													</div>
										<?php
										}
									}
								} ?>
												</div>
										</div>
					</div>
				</div>
	</section>

	<!-- cambios -->
	<section class="productos_relacionados">
		<div class="row">
			<h2><strong><?php echo $tl_productos_relacionados ?></strong> <?php echo $producto_info->nombre ?></h2>
			<?php
			foreach ($productos_relacionados as $producto_relacionado) {
				$precio_base = number_format($producto_relacionado->precio_base, 2, ".", "");
				$descuento = htmlspecialchars($producto_relacionado->descuento, ENT_QUOTES, 'UTF-8');
				$precio_descuento = number_format(($precio_base) * (100 - $descuento) / 100, 2, ".", "");
			?>
				<a class="producto-relacionado-item" href="<?php echo $producto_relacionado->valor ?>" alt="<?php echo $producto_relacionado->nombre ?>" title="<?php echo $producto_relacionado->nombre ?>">
					<div class="enlace_producto_relacionado">
						<img width='50px' height='50px' src="../assets/img/productos/<?php echo $producto_relacionado->miniatura ?>" alt="<?php echo $producto_relacionado->nombre ?>" title="<?php echo $producto_relacionado->nombre ?>">
						<h3 class="nombre_producto"><?php echo $producto_relacionado->nombre ?></h3>
						<?php if ($descuento != '0') {
							echo '<div class="rebaja">-' . $descuento . '%</div>';
						} ?>
						<div class="barra_horizontal"> </div>
						<?php
						if ($precio_descuento > 100) {
							if ($descuento == '0') {
								echo '<p class="precio" style="margin-top: 16px !important;"><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small>' . $precio_base . $moneda . ' <br><span><small> ' . $vocabulario_IVA . '</small></span></p>';
							} else {
								echo '<p class="precio" ><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small><strong class="descuento">' . $precio_base . $moneda . '<br></strong>' . $precio_descuento . $moneda . ' <br><span><small> ' . $vocabulario_IVA . '</small></span></p>';
							}
						} else {
							if ($descuento == '0') {
								echo '<p class="precio" style="margin-top: 16px !important;">' . $precio_base . $moneda . ' <br><span><small> ' . $vocabulario_IVA . '</small></span></p>';
							} else {
								echo '<p class="precio" ><strong class="descuento">' . $precio_base . $moneda . '<br></strong>' . $precio_descuento . $moneda . ' <br><span><small> ' . $vocabulario_IVA . '</small></span></p>';
							}
						}
						?>
					</div>
					<button class="btn-palcarrito"><img width='20px' height='20px' class="icono_carrito" src="../assets/img/iconos/icono_carrito.svg" alt=""> <?php echo $vocabulario_comprar; ?></button>
				</a>
			<?php } ?>
		</div>
	</section>
	<!-- cambios -->
	<!-- Footer - Inicio -->
	<?php include('../includes/footer.php'); ?>
	<!-- Footer - Fin -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const navItems = document.querySelectorAll('.nav-item');
			let previousImage = document.querySelector('.img_nav_item:not(.ocultar)');

			navItems.forEach(item => {
				item.addEventListener('click', function() {
					const currentImage = item.querySelector('.img_nav_item');

					if (currentImage && currentImage.classList.contains('ocultar')) {
						if (previousImage) {
							previousImage.classList.add('ocultar');
						}
						currentImage.classList.remove('ocultar');
						previousImage = currentImage;
					}
				});
			});
		});
	</script>
	<script>
		// if (color == 1111 || juntas == 1111 || acabado == 1111 || formato == 1111){
		// 	var variante = document.getAtributte()
		// }

		//Función de añadir al carrito dependiendo del tipo de producto y características
		function addToCart(el, color, juntas, acabado, formato, v_selecciona, v_color, v_juntas, v_acabado, v_formato) {
			console.log("--------------")
			console.log(el.attr('color'))
			console.log(el.attr('juntas'))
			console.log(el.attr('acabado'))
			console.log(el.attr('formato'))

			if (juntas == 1111) {
				$('#submit').attr('juntas', $(1).attr('juntas'));
			}

			if (color == 1111 && el.attr('color') == undefined) {
				muestraMensajeLn(v_selecciona + ' ' + v_color);
				$('.color').get(0).scrollIntoView({
					behavior: 'smooth',
					block: 'center'
				});
			} else if (acabado == 1111 && el.attr('acabado') == undefined) {
				muestraMensajeLn(v_selecciona + ' ' + v_acabado);
				$('.acabado').get(0).scrollIntoView({
					behavior: 'smooth',
					block: 'center'
				});
			} else if (formato == 1111 && el.attr('formato') == undefined) {
				muestraMensajeLn(v_selecciona + ' ' + v_formato);
				$('.formato').get(0).scrollIntoView({
					behavior: 'smooth',
					block: 'center'
				});
			} else {
				addProductCar(el);
			}
		}

		function addToCart_relacionado(el) {
			addProductCar(el);
		}

		$('.sel-formato').click(function(event) {
			$('.img_product .rebaja').css('z-index', '-1');
			$('img.img_product').attr('src', $(this).attr('formato-src'));
			$('.close-img').remove();
			$('img.img_product').attr('img-prod', $(this).attr('formato-src'));
			$('p.p_color_ncs').html('');
		});

		$('.sel-juntas').click(function(event) {
			$('.img_product .rebaja').css('z-index', '-1');
			$('img.img_product').attr('src', $(this).attr('junta-src'));
			$('.close-img').remove();
			$('img.img_product').attr('img-prod', $(this).attr('junta-src'));
			$('p.p_color_ncs').html('');
		});

		$('.sel-color').click(function(event) {
			$('.img_product .rebaja').css('z-index', '-1');
		});
		$(document).on('click', '.close-img', function(event) {
			$('.img_product .rebaja').css('z-index', '1');
			$('p.p_color_ncs').html('');

		});
	</script>

	<!-- <div id="esquema-productos"></div>
    <script>
        $(document).ready(function() {
            $("#esquema-productos").load("../includes/esquemas_productos.php");
        });
    </script> -->

	<?php include('../includes/esquemas_productos.php'); ?>

</body>

</html>
