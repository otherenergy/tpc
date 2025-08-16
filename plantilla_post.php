<?php

session_start();
$_SESSION['nivel_dir'] = 3;

include('../../includes/nivel_dir.php');
include ('../../config/db_connect.php');
include ('../../class/userClass.php');

$userClass = new userClass();
$url_metas=$userClass->url_metas($id_url,$id_idioma);
$url_data=$userClass->obtener_informacion_url($id_url, $id_idioma);
$post_contenido=$userClass->post_contenido($id_url, $id_idioma);

include('../../includes/vocabulario.php');
include('../../includes/urls.php');

$urlsDinamicas = [
	"{url_tienda}" => $link_tienda,
	"{url_smart_jointer}" => $link_smart_jointer,
	"{url_pintura_smartcover}" => $link_pintura_smartcover,
	"{url_microcemento_listo_uso}" => $link_microcemento_listo_al_uso,
	"{url_hormigon_impreso}" => $link_hormigon_impreso,
	"{url_smart_cover_repair}" => $link_smart_cover_repair,
	"{url_smart_varnish_repair}" => $link_smart_varnish_repair,
	"{url_smart_cleaner}" => $link_smart_cleaner,
	"{url_smart_varnish}" => $link_smart_varnish,
	"{url_smart_wax}" => $link_smart_wax,
	"{url_reforma_baño_sin_obra}" => $link_reforma_ban_sin_obra,
	"{url_microcementobano_sin_limites}" => $link_microcementobano_sin_limites,
	"{url_microcementobano_guia_debutantes}" => $link_microcementobano_guia_debutantes,
	"{url_microcementobano_ducha_italiana}" => $link_microcementobano_ducha_italiana,
	"{mini_kit_sj}" => $mini_kit_sj,
	"{link_kit_banos_duchas_cocinas}" => $link_kit_banos_duchas_cocinas,
	"{url_mantenimiento_microcemento}" => $link_mantenimiento_microcemento,
	"{url_colores_microcemento}" => $link_colores_microcemento,
	"{url_kit_banos_duchas_cocinas}" => $link_kit_banos_duchas_cocinas,
];

$content = $post_contenido[0]->content;
foreach ($urlsDinamicas as $marcador => $valor) {
    if (is_null($valor)) {
        $valor = '';
    }
    $content = str_replace($marcador, $valor, $content);
}

$relative_image_path = $post_contenido[0]->image;

$cleaned_image_path = str_replace('../../', '', $relative_image_path);

$image_url = 'https://www.smartcret.com/' . $cleaned_image_path;

?>
<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
	<?php include('../../includes/head.php');?>
	<body>
		<!-- Header - Inicio -->
		<?php include('../../includes/header.php'); ?>
		<!-- Header - Fin -->

		<?php if ( $id_url < 131) { ?>

			<section class="post-imagen-fondo" style="background-image: url('<?php echo $post_contenido[0]->image;?>');background-position: center!important">
				<div style="width:100%;background-color:#000;opacity:0.8;height: 100%;">
					<h1 style="color: #92bf23;"><strong><?php echo $post_contenido[0]->h1;?></strong></h1>
				</div>
			</section>

		<?php } ?>

		<section class="post-body">
			<?php  echo $content ?>
		</section>
		<!-- Footer - Inicio -->
		<?php include('../../includes/footer.php'); ?>
		<?php include('../../includes/esquemas_posts.php'); ?>
	</body>
</html>