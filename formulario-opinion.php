<?php error_reporting(E_ERROR | E_WARNING | E_PARSE);
if ( !isset( $_GET['user'] ) && $_GET['user'] == '') {
	header( "Location: ./" );
}
include_once('assets/lib/bbdd.php');
include_once('assets/lib/class.carrito.php');
include_once('assets/lib/funciones.php');

$ref = base64_decode ( $_GET['ref'] );
$user = obten_datos_user( base64_decode ( $_GET['user'] ) );
$lang = ( isset( $_GET['lang'] ) ) ? $_GET['lang'] : 'es' ;

$lg = $lang;
include_once('assets/lib/traducciones.php');

?>

<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>Tu opinión nos importa | Smartcret</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="language" content="es-ES">
		<!-- Etiquetas META SEO -->
		<meta name="robots" content="index,follow">
		<meta name="description" content="¿Quieres hacer una reforma en casa? En Smartcret te acompañamos en todo para que todo sea fácil y rápido. Contacta con nosotros.">
		<meta property="og:locale" content="es_ES">
		<meta property="og:type" content="website">
		<meta property="og:title" content="Tu opinión nos importa | Smartcret">
		<meta property="og:description" content="¿Quieres hacer una reforma en casa? En Smartcret te acompañamos en todo para que todo sea fácil y rápido. Contacta con nosotros.">
		<meta property="og:url" content="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
		<meta property="og:site_name" content="Smartcret">
		<meta name="twitter:card" content="summary">
		<meta name="twitter:title" content="Tu opinión nos importa | Smartcret">
		<meta name="twitter:description" content="¿Quieres hacer una reforma en casa? En Smartcret te acompañamos en todo para que todo sea fácil y rápido. Contacta con nosotros.">
		<meta name="twitter:site" content="https://www.smartcret.com">
		<meta name="twitter:creator" content="@SmartcretD">

		<?php include_once ( './includes/bloque_canonical.php' ) ?>

		<!-- Estilos CSS -->
		<link rel='stylesheet' href='assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel="icon" href="assets/img/favicon.png">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-169820052-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', 'UA-169820052-1');
		  gtag('config', 'AW-1001206950');
		</script>
		<script async src="https://www.googletagmanager.com/gtag/js?id=AW-1001206950"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'AW-1001206950');
		</script>
		<script>
		  gtag('event', 'conversion', {
			  'send_to': 'AW-1001206950/NNt5CLbl_o8DEKbptN0D',
			  'transaction_id': ''
		  });
		</script>

	</head>


	<body class="contacto opinion">
	<!-- Header - Inicio -->
	<?php include('./includes/header.php'); ?>
	<!-- Header - Fin -->
	<div class="sep40"></div>
	<section class="formulario-contacto">
			<div class="row formulario">
				<div class="contenido offset-md-2 offset-sm-6 offset-lg-6 col-sm-8 col-md-4 col-lg-4">

			<div id='crmWebToEntityForm' class='zcwf_lblLeft crmWebToEntityForm'>

			<form action='class/control.php' id="form_opinion" name='form_opinion' method='POST' onSubmit=''>
				<h2><strong><?php echo $user->nombre ?></strong> - <span class="ref_form">Pedido [<?php echo $ref ?>]</span><br> <?php echo trad('Tu opinión es muy importante para nosotros.') ?></h2>

				<div class="form-group col-md-12 form_item">
					<label for='folleto'><?php echo trad('¿Recibiste el folleto del paso a paso?') ?>' <span style='color:red;'>*</span></label>
					<div class="folleto radio_items">
						<input type="radio" name="folleto" value="1"> <?php echo trad('Sí') ?>
						<input type="radio" name="folleto" value="0"> <?php echo trad('No') ?>
					</div>
				</div>

				<div class="form-group col-md-12 form_item">
					<label for='color'><?php echo trad('¿El color era el que esperabas?') ?> <span style='color:red;'>*</span></label>
					<div class="color radio_items">
						<input type="radio" name="color" value="1"> <?php echo trad('Sí') ?>
						<input type="radio" name="color" value="0"> <?php echo trad('No') ?>
					</div>
				</div>

				<div class="form-group col-md-12 form_item">
					<label for='mejor'><?php echo trad('¿Qué es lo que más te ha gustado?') ?><span style='color:red;'>*</span></label>
					<textarea id='mejor' name='mejor' rows="4"></textarea>
				</div>

				<div class="form-group col-md-12 form_item">
					<label for='peor'><?php echo trad('¿Y lo que menos?')?><span style='color:red;'>*</span></label>
					<textarea id='peor' name='peor' rows="4"></textarea>
				</div>

				<div class="form-group col-md-12 form_item">
					<label for='comentarios'><?php echo trad('Comentarios y/o sugerencias') ?></label>
					<textarea id='comentarios' name='comentarios' rows="6"></textarea>
				</div>

				<div class="form-group col-md-12 form_item center">
					<input type="hidden" name="ref_pedido" value="<?php echo $ref ?>">
					<input type="hidden" name="user_nom" value="<?php echo $user->nombre ?>">
					<input type="hidden" name="user_id" value="<?php echo $user->uid ?>">
					<input type="hidden" name="idioma" value="<?php echo $lang ?>">
					<input type="hidden" name="accion" value="form_opinion">
					<input type="submit" value="<?php echo trad('Enviar') ?>" id="envia_opinion">
				</div>

			</form>
			</div>
			</div>

			</div> <!-- fin formulario -->
			<div class="sep60"></div>
			<img src="./assets/img/lo_puedes_hacer_tu_mismo.webp" alt="" class="fondo_opinion">
			<!-- <img src="./assets/img/fondo_opinion.jpg" alt="" class="fondo_opinion"> -->
			<div class="overlay_verde"></div>
	</section>
	<div class="sep30"></div>


	<!-- Footer - Inicio -->
	<?php include('includes/footer.php'); ?>
	<?php include('./includes/gracias_envio_formulario.php') ?>
	<!-- Footer - Fin -->
	<!--<script data-id='xenioo' data-node='app' src="https://static.xenioo.com/webchat/xenioowebchat.js"></script>-->


	<script>

		function scrollToId(id) {
			$('html, body').animate({
				scrollTop: $("#"+id).offset().top
			}, 1000);
		}

		$('form').submit(function(e) {
			e.preventDefault();

			if ( datosFormOk() ) {
				$.ajax({
					url: './class/control.php',
					type: 'post',
					dataType: 'text',
					data: $('#form_opinion').serialize()
				})
				.done(function(result) {
					var result = $.parseJSON(result);
					muestraMensajeLn(result.msg);
					setTimeout(function() {
						location.reload();
					},4000);
				})
				.fail(function() {
					alert("error");
				});

			}else {
				muestraMensajeLn(`<?php echo trad('Es necesario rellenar los campos obligatorios del formulario') ?>`);
			}
		});


		function datosFormOk () {

			var correcto = true;

			if( $('input[name="folleto"]:checked').length == 0 ) {
				correcto = false;
			}
			else if( $('input[name="color"]:checked').length == 0 ) {
				correcto = false;
			}
			else if( $('textarea#mejor').val()=='' || $('textarea#peor').val()=='' || $('textarea#comentarios').val()=='' ) {
				correcto = false;
			}

			return correcto;
		}


	</script>
	</body>
</html>