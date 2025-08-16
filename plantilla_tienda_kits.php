<?php
session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');

include('../config/db_connect.php');
include('../class/userClass.php');
include('../includes/vocabulario.php');

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

if ($url == 'index') {
	$url = '';
}

$mostrar_categorias = false;

if ($id_url == 112) {
	$categorias_permitidas = [12, 10];
	$mostrar_categorias = true;
	$titulo_kits = $vocabulario_kits_baños_duchas_cocina;
} else if ($id_url == 113) {
	$categorias_permitidas = [11];
	$mostrar_categorias = true;
	$titulo_kits = $vocabulario_kits_paredes;
} else {
	$categorias_permitidas = [];
	$mostrar_categorias = false;
}

$url_page = $url;
?>
<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
<!-- Head -->
<?php include('../includes/head.php'); ?>
<!-- Head -->

<body class="tienda">
	<!-- Header - Inicio -->
	<?php include('../includes/header.php'); ?>
	<?php include_once('../includes/geolocation.php'); ?>
	<!-- Header - Fin -->

	<?php if (0) { ?>
		<section class="intro">
			<div class="imagen_tienda">
				<img src="../assets/img/smartstore.png?<?php echo rand() ?>" class="centrar" alt="Tienda de microcemento y pinturas para azulejos" title="Tienda de microcemento y pinturas para azulejos">
			</div>
		</section>
	<?php } ?>
	<!-- <div class="contenedor_inicial_tienda" style="display:none;">
		<?php //echo $contenido[1][1] ?>
	</div> -->

	<section class="productos_tienda">
		<div class="contenedor">
			<?php
			$lista_salidos = [];
			$contador = 0;
			foreach ($menu_productos as $categoria) {
				$id_categoria = htmlspecialchars($categoria->id_categoria, ENT_QUOTES, 'UTF-8');

				if (in_array($id_categoria, $categorias_permitidas)) {
					if (!in_array($categoria->valor_pc, $lista_salidos)) {
						$lista_salidos[] = $categoria->valor_pc;
						$id_producto = htmlspecialchars($categoria->id, ENT_QUOTES, 'UTF-8');
						if ($mostrar_categorias) {
			  ?>
							<div class="row">
								<div class="div_h2_tienda">
										<h1 id="kits" class="h2_tienda"><?php echo $titulo_kits; ?></h1>
									</div>
								<?php
								if ($contador == 0) {
									?>
				<?php

								} ?>

								<div class="div_h3_tienda_kits ">
									<h2 id="<?php echo $categoria->div_id; ?>" class="h3_tienda"><?php echo $categoria->valor_pc; ?></h2>
								</div>

								<?php
								$contador = 0;
								foreach ($menu_productos as $producto) {
									$id_categoria_producto = htmlspecialchars($producto->id_categoria, ENT_QUOTES, 'UTF-8');
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

										<a class="producto-item" href="<?php echo $url; ?>">
											<?php if ($descuento != 0) { ?>
												<div class='rebaja'>-<?php echo $descuento; ?>%</div>
											<?php } ?>
											<img src="../assets/img/productos/<?php echo $img_product; ?>" alt="<?php echo $nombre_producto; ?>" title="<?php echo $nombre_producto; ?>">
											<h3 class="nombre_producto"><?php echo $nombre_producto; ?></h3>
											<div class="barra_horizontal"> </div>
											<?php
											if ($precio_descuento > 100) {
												if ($descuento == '0') {
													echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small>' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
												} else {
													echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small>' . $precio_descuento . $moneda . '  <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . '€<br></strong> </small></span></p>';
												}
											} else {
												if ($descuento == '0') {
													echo '<p class="precio">' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
												} else {
													echo '<p class="precio">' . $precio_descuento . $moneda . '  <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . '€<br></strong> </small></span></p>';
												}
											}
											?>
											<button class="btn-palcarrito"><img class="icono_carrito" src="../assets/img/iconos/icono_carrito.svg" alt=""> <?php echo $vocabulario_comprar; ?></button>

										</a>
							<?php
									};
								}
							};
							?>
							</div>
				<?php
					}
				}
				$contador = 1;

			}
				?>

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
				toggleState = true;
			} else {
				$('.nav-item-ocultando').css('display', 'none');
				$('#iconomastienda').attr('src', '../assets/img/mastienda.png');

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
					seccion.style.top = "110px";
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
	</script>
	<?php
	$url = $url_page;
	include('../includes/esquema_tienda_kits.php'); ?>

</body>

</html>