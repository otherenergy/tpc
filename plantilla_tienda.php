<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');
include('../config/db_connect.php');
include('../class/userClass.php');

$userClass = new userClass();
$url_metas = $userClass->url_metas($id_url, $id_idioma);

$moneda_obj = $userClass->obtener_moneda();
$moneda = $moneda_obj->moneda;
$menu_productos = $userClass->menu_productos($id_idioma);
$contenido_bloque = $userClass->contenido_bloque(5, $id_idioma);

$contenido = [];

foreach ($contenido_bloque as $item) {
	$id_bloque = $item->id_bloque;
	$id_bloque_hijo = $item->id_bloque_hijo;
	$contenido[$id_bloque][$id_bloque_hijo] = $item->content;
}

include('../includes/vocabulario.php');
include('../includes/urls.php');

if ($url == 'index') {
	$url = '';
}

$url_page = $url;
?>
<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
<?php include('../includes/head.php'); ?>

<body class="tienda">
	<!-- Header - Inicio -->
	<?php include('../includes/header.php'); ?>
	<?php include_once('../includes/geolocation.php'); ?>

	<div class="contenedor_inicial_tienda">
		<?php echo $contenido[1][1] ?>
	</div>
	<section class="tienda_menu">

		<nav id="seccion-prod-div-tienda" class="navbar navbar-expand-lg">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0" id="ul_tienda_menu">

				<li id="item_productos" class="pintura-class-store nav-item" style="display:none;">
					<a class="nav-link scroll-adjust">
						<p style="font-size:16px;"><strong><?php echo $vocabulario_productos ?></strong></p>
					</a>
					<img src="../assets/img/mastienda.png" id="iconomastienda" alt="Icon +" width="20px" height='20px'>
				</li>

				<?php
				$contador = 0;
				$lista_salidos = [];
				foreach ($menu_productos as $categoria) {

					if (!in_array($categoria->valor_pc, $lista_salidos)) {
						$lista_salidos[] = $categoria->valor_pc;
						if ($categoria->id_categoria == 10 || $categoria->id_categoria == 11 || $categoria->id_categoria == 12) {
							if ($contador == 1) {
								continue;
							}
							$contador = 1;

				?>
							<li class="nav-item nav-item-ocultando" style="display:none;">
								<a class="nav-link scroll-adjust" href="<?php echo $url ?>#kits">
									<p><?php echo $vocabulario_kits_microcemento_red ?></p>
									<img width='10px' height='10px' src="../assets/img/iconos/kits.png" alt="">
								</a>
							</li>
						<?php
						} elseif ($categoria->orden_categoria == 1) {
						?>
							<li class="pintura-class-store nav-item">
								<a class="nav-link scroll-adjust" href="<?php echo $url ?>#<?php echo htmlspecialchars($categoria->div_id, ENT_QUOTES, 'UTF-8');  ?>">
									<p><?php echo htmlspecialchars($categoria->valor_pc, ENT_QUOTES, 'UTF-8');  ?></p>
									<img width='10px' height='10px' src="../assets/img/iconos/<?php echo htmlspecialchars($categoria->div_id, ENT_QUOTES, 'UTF-8');  ?>.png" alt="">
								</a>
								<img width='10px' height='10px' src="../assets/img/mastienda.png" id="iconomastienda" alt="Icon +" width="20px"  height='20px'>

							</li>
						<?php
						} else {
							switch ($categoria->id_categoria) {
								case 5:
									$texto = $vocabulario_microcemento_listo_al_uso_red;
									break;
								case 4:
									$texto = $vocabulario_mantanimiento_y_limpieza_red;
									break;
								default:
									$texto = $categoria->valor_pc;
							}
						?>
							<li class="nav-item nav-item-ocultando" style="display:none;">
								<a class="nav-link scroll-adjust" href="<?php echo $url ?>#<?php echo htmlspecialchars($categoria->div_id, ENT_QUOTES, 'UTF-8');  ?>">
									<p><?php echo htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');  ?></p>
									<img width='10px' height='10px' src="../assets/img/iconos/<?php echo htmlspecialchars($categoria->div_id, ENT_QUOTES, 'UTF-8');  ?>.png" alt="">
								</a>
							</li>
					<?php
						}
					} ?>
				<?php }
				?>
			</ul>
		</nav>
	</section>

	<section class="productos_tienda">
		<div class="contenedor">
			<div class="row">
				<div class="div_h2_tienda">
					<h2 id="kits" class="h2_tienda"><?php echo $vocabulario_kits_microcemento; ?></h2>
				</div>
				<a href="<?php echo $link_kit_banos_duchas_cocinas; ?>" class="producto-item caja_descripcion">
					<?php echo $contenido[2][1] ?>
				</a>
				<a href="<?php echo $link_kit_banos_duchas_cocinas; ?>" class="producto-item-imagen">
					<img width='100px' height='440px' class="img-producto-ia cocina_desktop" src="../assets/img/microcemento_cocinas.webp" alt="<?php echo $vocabulario_kits_microcemento; ?>">
				</a>
				<a href="<?php echo $link_kit_paredes; ?>" class="producto-item caja_descripcion">
					<?php echo $contenido[2][2] ?>
				</a>
				<a href="<?php echo $link_kit_paredes; ?>" class="producto-item-imagen">
					<img width='100px' height='440px' class="img-producto-ia pared_desktop" src="../assets/img/microcemento_pared.webp" alt="<?php echo $vocabulario_kits_microcemento; ?>">
				</a>
			</div>
			<?php
			$lista_salidos = [];
			foreach ($menu_productos as $categoria) {
				if (!in_array($categoria->valor_pc, $lista_salidos)) {
					$lista_salidos[] = $categoria->valor_pc;
					$id_categoria =  htmlspecialchars($categoria->id_categoria, ENT_QUOTES, 'UTF-8');
					if ($id_categoria == 10 || $id_categoria == 11 || $id_categoria == 12) {
						continue;
					}
			?>

					<div class="row">
						<div class="div_h2_tienda">
							<h2 id="<?php echo $categoria->div_id; ?>" class="h2_tienda"><?php echo $categoria->valor_pc; ?></h2>
						</div>

						<?php
						$contador = 0;
						foreach ($menu_productos as $producto) {
							$id_categoria_producto =  htmlspecialchars($producto->id_categoria, ENT_QUOTES, 'UTF-8');
							if ($id_categoria == $id_categoria_producto) {
								$img_product = htmlspecialchars($producto->miniatura, ENT_QUOTES, 'UTF-8');
								$id_producto = htmlspecialchars($producto->id, ENT_QUOTES, 'UTF-8');
								$variante = htmlspecialchars($producto->variante, ENT_QUOTES, 'UTF-8');

								$precio_base = number_format($producto->precio_base, 2, ".", "");
								$descuento = htmlspecialchars($producto->descuento, ENT_QUOTES, 'UTF-8');
								$precio_descuento = number_format(($precio_base) * (100 - $descuento) / 100, 2, ".", "");

								$nombre_producto = htmlspecialchars($producto->nombre, ENT_QUOTES, 'UTF-8');

								$url = htmlspecialchars($producto->valor, ENT_QUOTES, 'UTF-8');
						?>

								<a class="producto-item" href="<?php echo $url;  ?>">
									<?php if ($descuento != 0) { ?>
										<div class='rebaja'>-<?php echo $descuento; ?>%</div>
									<?php } ?>
									<img width='350px' height='350px' src="../assets/img/productos/<?php echo $img_product; ?>" alt="<?php echo $nombre_producto;  ?>" title="<?php echo $nombre_producto;  ?>">
									<h3 class="nombre_producto"><?php echo $nombre_producto;  ?></h3>
									<div class="barra_horizontal"> </div>
									<?php
									if ($precio_descuento > 100) {
										if ($descuento == '0') {
											echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small>' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
										} else {
											echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small>' . $precio_descuento . $moneda . '  <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . $moneda . '<br></strong> </small></span></p>';
										}
									} else {
										if ($descuento == '0') {
											echo '<p class="precio">' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
										} else {
											echo '<p class="precio">' . $precio_descuento . $moneda . '  <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . $moneda . '<br></strong> </small></span></p>';
										}
									}
									?>
									<button class="btn-palcarrito"><img  width='25px' height='25px' class="icono_carrito" src="../assets/img/iconos/icono_carrito.svg" alt=""> <?php echo $vocabulario_comprar; ?></button>
								</a>
								<?php if ($producto->id_categoria == 8) { ?>
									<div class="producto-item-imagen">
										<img width='10px' height='10px' class="img-producto-ia" src="../assets/img/imagen_ia_1.webp" alt="">
										<?php echo $contenido[3][1] ?>

									</div>
								<?php } ?>

								<?php if ($producto->id_categoria == 9) { ?>
									<div class="producto-item-imagen">
										<img width='10px' height='10px' class="img-producto-ia" src="../assets/img/imagen_ia_2.webp" alt="">
										<?php echo $contenido[3][2] ?>
									</div>
						<?php }
							};
						}; ?>
					</div>
			<?php
				};
			}; ?>

		</div>
	</section>
	<div class="contenedor_final_tienda">
		<?php echo $contenido[4][1] ?>
	</div>
	<style>
		a img {
			height: auto;
		}
	</style>

	<!-- Footer - Inicio -->
	<?php include('../includes/footer.php'); ?>
	<!-- Footer - Fin -->

	<script>
		var toggleState = false;
		$('#iconomastienda').click(function(event) {
			if (!toggleState) {
				$('.nav-item-ocultando').css('display', 'block');
				$('#iconomastienda').attr('src', '../assets/img/menostienda.png');
				$('#item_productos > a > p').css('display', 'none');
				toggleState = true;
			} else {
				$('.nav-item-ocultando').css('display', 'none');
				$('#iconomastienda').attr('src', '../assets/img/mastienda.png');
				$('#item_productos > a > p').css('display', 'block');
				toggleState = false;
			}
		});

		window.addEventListener('scroll', function() {

			var section = document.getElementById('seccion-prod-div-tienda');
			var ul = document.getElementById('ul_tienda_menu');
			var sectionTop = section.offsetTop;
			var scrollPosition = window.scrollY;
			var valor_top = 60
			const seccion = document.querySelector(".seccion-fija");

			const anuncio = document.querySelector(".anuncio");
			if (anuncio) {
				var valor_top = 140
				if (scrollPosition >= sectionTop - valor_top && scrollPosition > 0) {
					section.classList.add('seccion-fija');
					ul.classList.add('seccion-fija-ul');
					ul.classList.remove('transition-ul');

					if ($(window).width() <= 767) {
						seccion.style.top = "90px";
					} else if ($(window).width() <= 992) {
						seccion.style.top = "55px";
					} else {
						seccion.style.top = "110px";
					}


				} else {
					section.classList.remove('seccion-fija');
					ul.classList.remove('seccion-fija-ul');
					ul.classList.add('transition-ul');
					seccion.style.top = "0px";
				}

			} else {

				if (scrollPosition >= sectionTop - valor_top && scrollPosition > 0) {
					section.classList.add('seccion-fija');
					ul.classList.add('seccion-fija-ul');
					ul.classList.remove('transition-ul');
				} else {
					section.classList.remove('seccion-fija');
					ul.classList.remove('seccion-fija-ul');
					ul.classList.add('transition-ul');
				}
			}
		});

		function addToCart_prod(el) {
			addProductCar(el);
		}

		document.addEventListener("DOMContentLoaded", function() {
			// Selecciona todos los enlaces con la clase 'scroll-adjust'
			const links = document.querySelectorAll('.scroll-adjust');

			links.forEach(link => {
				link.addEventListener('click', function(event) {
					mostrarOcultarMenu();
					// Obtén el href del enlace
					const targetId = this.getAttribute('href').split('#')[1];
					const targetElement = document.getElementById(targetId);

					// Si el elemento objetivo existe, ajusta el desplazamiento
					if (targetElement) {
						event.preventDefault(); // Previene el comportamiento por defecto del enlace
						window.scrollTo({
							top: targetElement.offsetTop - 150, // Ajusta 100 píxeles más arriba
							behavior: 'smooth' // Desplazamiento suave
						});
						// Actualiza la URL hash sin desplazar la página
						history.pushState(null, null, '#' + targetId);
					}
				});
			});
		});

		function mostrarOcultarMenu() {
			if (!toggleState) {
				$('.nav-item-ocultando').css('display', 'block');
				$('#iconomastienda').attr('src', '../assets/img/menostienda.png');
				$('#item_productos > a > p').css('display', 'none');
				toggleState = true;
			} else {
				$('.nav-item-ocultando').css('display', 'none');
				$('#iconomastienda').attr('src', '../assets/img/mastienda.png');
				$('#item_productos > a > p').css('display', 'block');
				toggleState = false;
			}
		}

	</script>

	<?php

	$url = $url_page;

	include('../includes/esquema_tienda.php'); ?>

</body>

</html>