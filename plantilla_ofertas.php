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
$menu_productos = $userClass->menu_productos_ofertas($id_idioma);
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

$titulo_kits = $vocabulario_ofertas_smartcret;
$url_page = $url;
?>
<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
<!-- Head -->
<?php include('../includes/head.php'); ?>
<!-- Head -->

<style>
	.banner img {
    width: 100%;
	}
	.tit_ofertas {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 24px;
	}
	.tienda .producto-item {
    min-height: 480px;
  }
</style>

<body  class="tienda">
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

	<?php if ( $userClass->banner_promocion_activo() ) { ?>

    <div class="banner">
        <a class="swiper-container" href="<?php echo $link_kit_banos_duchas_cocinas; ?>">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/banners/descuentos_verano24/smart_kit_2_<?php echo $idioma_url ?>.webp" media="(max-width: 768px)">
                        <img height="500px" width="500px" src="../assets/img/banners/descuentos_verano24/smart_kit_2_<?php echo $idioma_url ?>.webp" alt="Banner" loading="eager" fetchpriority="high">
                    </picture>
                </div>

                <!-- <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/banners/ECO/banner_2_<?php echo $idioma_url ?>_mov.webp" media="(max-width: 768px)">
                        <img height="10px" width="10px" src="../assets/img/banners/ECO/banner_2_<?php echo $idioma_url ?>.webp" alt="Banner promocional">
                    </picture>
                </div>
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/banners/ECO/banner_3_<?php echo $idioma_url ?>_mov.webp" media="(max-width: 768px)">
                        <img height="10px" width="10px" src="../assets/img/banners/ECO/banner_3_<?php echo $idioma_url ?>.webp" alt="Banner promocional">
                    </picture>
                </div>
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/banners/ECO/banner_4_<?php echo $idioma_url ?>_mov.webp" media="(max-width: 768px)">
                        <img height="10px" width="10px" src="../assets/img/banners/ECO/banner_4_<?php echo $idioma_url ?>.webp" alt="Banner promocional">
                    </picture>
                </div>
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/banners/descuentos_verano24/super_kit_2_<?php echo $idioma_url ?>_mov.webp" media="(max-width: 768px)">
                        <img height="10px" width="10px" src="../assets/img/banners/descuentos_verano24/super_kit_2_<?php echo $idioma_url ?>.webp" alt="Banner promocional">
                    </picture>
                </div> -->

            </div>
        </a>
        <div class="swiper-pagination"></div>
    </div>

<?php } ?>

<div class="sep20"></div>

	<section class="productos_tienda">
		<div class="contenedor">
			<div class="row">
				<div class="div_h2_tienda">
					<h1 id="kits" class="h2_tienda"><?php echo $titulo_kits; ?></h1>
				</div>
				<div class="tit_ofertas">
					<?php echo $vocabulario_tiempo_limitado ?>
				</div>
			</div>

			<div class="row">

			<?php
			$lista_salidos = [];
			$contador = 0;

			foreach ($menu_productos as $producto) {
				$id_categoria_producto = htmlspecialchars($producto->id_categoria, ENT_QUOTES, 'UTF-8');

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
				$contador = 1;
			}
		?>

		</div>
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

		document.addEventListener("DOMContentLoaded", function() {
			const swiper = new Swiper('.swiper-container', {
				loop: true,
				autoplay: {
					delay: 5000,
				},
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
				},
			});
		});

		var toggleState = false;

	</script>
	<?php
	$url = $url_page;
?>

</body>
</html>