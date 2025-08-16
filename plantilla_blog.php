<?php

session_start();
$_SESSION['nivel_dir'] = 2;

include('../../includes/nivel_dir.php');
include('../../config/db_connect.php');
include('../../class/userClass.php');

$userClass = new userClass();
$url_data = $userClass->obtener_informacion_url($id_url, $id_idioma);
$blog_contenido = $userClass->blog_contenido($id_idioma);
$url_metas = $userClass->url_metas($id_url, $id_idioma);

include('../../includes/vocabulario.php');
include('../../includes/urls.php');

?>

<!DOCTYPE html>


<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
<?php include('../../includes/head.php'); ?>

<body>
	<!-- Header - Inicio -->
	<?php include('../../includes/header.php'); ?>
	<!-- Header - Fin -->

	<section class="blog">
		<div class="container">
			<div class="row">
				<h1 style="width: 100%; text-align: center;"><?php echo $url_data->metas->title ?></h1>
				<p style="width: 100%;"><?php echo $url_data->metas->description ?></p>

				<?php if (!empty($blog_contenido)) { ?>
					<?php foreach ($blog_contenido as $tarjeta) { ?>
						<div class="col-md-4">
							<div class="post" id="post-<?php echo $tarjeta->id; ?>">
								<a href="<?php echo $tarjeta->valor; ?>">
									<img src="<?php echo $tarjeta->image; ?>" alt="<?php echo $tarjeta->title; ?>" title="<?php echo $tarjeta->title; ?>">
								</a>
								<a class="link_h2" href="<?php echo $tarjeta->valor; ?>">
									<h2 class="titulo_blog"><?php echo $tarjeta->h1; ?></h2>
									<span class="sr-only">Enlace a <?php echo $tarjeta->h1; ?></span>
								</a>
								<p class="fecha-blog"><?php echo $tarjeta->fecha; ?></p>
								<p><?php echo $tarjeta->description; ?></p>
								<p style="text-align:center;">
									<a href="<?php echo $tarjeta->valor; ?>" alt="<?php echo $vocabulario_leer_mas ?>" title="<?php echo $vocabulario_leer_mas ?>" class="verde"><?php echo $vocabulario_leer_mas ?></a>
								</p>
							</div>
						</div>
					<?php } ?>
				<?php } else { ?>
					<div class="sin_articulos">
						<h2><?php echo $vocabulario_blog_sin_articulos ?></h2>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>


	<!-- Footer - Inicio -->
	<?php include('../../includes/footer.php'); ?>
	<!-- Footer - Fin -->

	<style>
		.sin_articulos {
			width: 100%;
			text-align: center;
			height: 49vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.sin_articulos h2 {
			text-align: center;
		}
	</style>

	<script>
		// Controla que las tarjhetas de los post sean links con query 768px
		document.addEventListener("DOMContentLoaded", function() {
			function addLinkToPosts() {
				const posts = document.querySelectorAll('.post');
				const mediaQuery = window.matchMedia('(max-width: 768px)');

				posts.forEach(post => {
					const link = post.querySelector('a.verde');
					const postId = post.id;

					if (mediaQuery.matches) {
						if (!post.querySelector('.post-link')) {
							const postLink = document.createElement('a');
							postLink.href = link.href;
							postLink.className = 'post-link';
							postLink.style.display = 'block';
							postLink.style.position = 'absolute';
							postLink.style.top = '0';
							postLink.style.left = '0';
							postLink.style.right = '0';
							postLink.style.bottom = '0';
							postLink.style.zIndex = '1';
							postLink.style.textIndent = '-9999px';
							postLink.style.overflow = 'hidden';
							postLink.title = link.title;
							postLink.alt = link.alt;
							post.appendChild(postLink);
						}
					} else {
						const existingLink = post.querySelector('.post-link');
						if (existingLink) {
							post.removeChild(existingLink);
						}
					}
				});
			}

			window.addEventListener('resize', addLinkToPosts);
			addLinkToPosts();
		});
	</script>

	<?php include('../../includes/esquema_blog.php'); ?>

</body>

</html>