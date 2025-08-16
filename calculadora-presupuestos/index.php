<?php error_reporting(E_ERROR | E_WARNING | E_PARSE);
include_once('../assets/lib/bbdd.php');
include_once('../assets/lib/funciones.php');
?>

<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>Calculadora de presupuestos | Smartcret</title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="language" content="es-ES">
		<!-- Etiquetas META SEO -->
		<meta name="robots" content="index,follow">
		<meta name="description" content="¿Quieres saber que necesitas para tu reforma? calcula tu mismo todo lo que necesitas">
		<meta property="og:locale" content="es_ES">
		<meta property="og:type" content="website">
		<meta property="og:title" content="Calculadora de presupuestos | Smartcret">
		<meta property="og:description" content="¿Quieres saber que necesitas para tu reforma? calcula tu mismo todo lo que necesitas">
		<meta property="og:url" content="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
		<meta property="og:site_name" content="Smartcret">
		<meta name="twitter:card" content="summary">
		<meta name="twitter:title" content="Calculadora de presupuestos | Smartcret">
		<meta name="twitter:description" content="¿Quieres saber que necesitas para tu reforma? calcula tu mismo todo lo que necesitas">
		<meta name="twitter:site" content="https://www.smartcret.com">
		<meta name="twitter:creator" content="@SmartcretD">

		<?php //include_once ( '../includes/bloque_canonical.php' ) ?>
		<link rel="canonical" href="https://www.smartcret.com/calculadora-presupuestos/" />
		<link rel="alternate" href="https://www.smartcret.com/calculadora-presupuestos/" hreflang="es-ES" />
		<link rel="alternate" href="https://www.smartcret.com/en/budget-calculator/" hreflang="en-GB" />
		<link rel="alternate" href="https://www.smartcret.com/en-us/budget-calculator/" hreflang="en-US" />
		<link rel="alternate" href="https://www.smartcret.com/fr/calculateur-de-budget/" hreflang="fr-FR" />
		<link rel="alternate" href="https://www.smartcret.com/it/calcolatore-di-budget/" hreflang="it-IT" />
		<!-- <link rel="alternate" href="https://www.smartcret.com/de/budget-calculator/" hreflang="de-DE" /> -->

		<!-- Estilos CSS -->
		<link rel='stylesheet' href='../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel='stylesheet' href='./css/calc_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel="icon" href="../assets/img/favicon.png">
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
		<?php include_once ( '../includes/seguimiento_hotjar.php' ) ?>
	</head>
	<body class="calculadora">
	<!-- Header - Inicio -->
	<?php include( dirname ( __DIR__ ) . '/blog/includes/header-blog.php'); ?>
	<!-- Header - Fin -->
	<section class="calculadora-imagen-fondo"  style="background-image: url(../assets/img/calculadora-presupuesto.jpg
	);"	>
		<div style="background-color:#000;opacity:0.8;width:100%;height:100%;">
			<h1>Calculadora de presupuestos</h1>
		</div>
	</section>
	<div class="sep20"></div>
	<section style="margin-bottom: 5%;">
		<div class="container">
			<section>
					<div class="container img-circ sel_mat">
						<div class="row">
							<h2 class="tit_pr">Selecciona material</h2>
						</div>
						<div class="sep20"></div>
						<div class="row calcula">
							<div class="col-md-3"></div>
							<div class="col-md-2 col-6 center item n1 gris">
								<img class="circ_home" src="../assets/img/microcemento.avif" alt="Microcemento listo al uso" title="Microcemento listo al uso" style="display: block;margin-left: auto;margin-right: auto;margin-bottom: 3%;" width="304" height="304">
								<a valor="micro" class="microcemento_cta pres" href="javascript:void(0)" alt="Microcemento listo al uso" title="Microcemento listo al uso" onclick="openCalculadora( 1, 'Microcemento listo al uso' );$('#input_tipo_pres').val('1')"><h3>Microcemento<br> listo al uso</h3></a>
							</div>
							<div class="col-md-2 col-6 center item n2 gris">
								<img class="circ_home" src="../assets/img/pintura_azulejos.avif" alt="Pintura para azulejos" title="Pintura para azulejos" style="display: block;margin-left: auto;margin-right: auto;margin-bottom: 3%;" width="304" height="304">
								<a valor="pintura" class="pintura_cta pres" href="javascript:void(0)" alt="Pintura para azulejos" title="Pintura para azulejos" onclick="openCalculadora( 2, 'Pintura para azulejos' );$('#input_tipo_pres').val('2')"><h3>Pintura azulejos<br>Smartcover Tiles</h3></a>
							</div>
							<div class="col-md-2 col-6 center item n3 gris">
								<img class="circ_home" src="../assets/img/hormigon-impreso.avif" alt="Hormigón impreso" title="Hormigón impreso" style="display: block;margin-left: auto;margin-right: auto;margin-bottom: 3%;" width="304" height="304">
								<a valor="hormigon_impreso" class="pintura_cta pres" href="javascript:void(0)" alt="Hormigón impreso" title="Hormigón impreso" onclick="openCalculadora( 3, 'Hormigón impreso' );$('#input_tipo_pres').val('3')"><h3>Reparación de<br> Hormigón impreso</h3></a>
							</div>
						</div>
					</div>
				</section>

				<section>
					<div class="container">
						<div class="row blq_calcula">
							<div class="procesando"><img src="img/loading.gif" alt="Procesando"></div>
							<div id="form_calculadora" class="col-md-3 offset-md-1"></div>
							<div id="resultado_presupuesto" class="col-md-7">
						</div>
					</div>
				</section>

		</div>
	</section>
	<div class="sep30"></div>

	<!-- Footer - Inicio -->
	<?php include('../includes/footer-post.php'); ?>

	<!-- Footer - Fin -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

	<script>

		function scrollToId(id) {
			$('html, body').animate({
				scrollTop: $("#"+id).offset().top -90
			}, 1000);
		}
	</script>
	</body>
</html>

<script>

	x=0;
	$(document).ready(function() {

		$('.sel_mat .item a').click(function(event) {
			$('#resultado_presupuesto').fadeOut('400', function() {
				$(this).html('');
			});
			$('.sel_mat .item').addClass('gris').removeClass('selected');
			$(this).closest('.sel_mat .item').removeClass('gris').addClass('selected');
		});
	});

	function openCalculadora( tipo_presupuesto, title ) {

		$.ajax({
			url: './includes/formulario_calculadora_presupuestos.php',
			type: 'POST',
			datatype: 'html',
			data: { tipo_presupuesto:tipo_presupuesto, title:title }
		})
		.done(function(result) {
			$('#form_calculadora').html(result).fadeIn(600);
		})
		.fail(function() {
			alert('Se ha producido un error');
		})
	}

	function pres_to_carrito( ) {
		$.ajax({
			url: './includes/control.php',
			type: 'POST',
			datatype: 'html',
			data: { 'accion': 'presupuesto_a_carrito' }
		})
		.done(function(result) {
			var result = $.parseJSON(result);
			$('#numprod').text(result.numProd);
			muestraMensajeLn(result.texto);
			actualizaCarritoCalc();
		})
		.fail(function() {
			alert('Se ha producido un error');
		})
	}

	function actualizaCarritoCalc(){
		$.ajax({
			url: 'includes/actualiza_carrito.php',
			type: 'POST',
			datatype: 'html',
		})
		.done(function(result) {
				$('#lista-productos').html(result);
			})
		.fail(function() {
			alert('Se ha producido un error');
		})
	}

	function eliminaArticuloCalc (uid){

		$.ajax({
			url: '../assets/lib/carrito.php',
			type: 'POST',
			datatype: 'json',
			data: { accion: 5, uid: uid },
		})
		.done(function(result) {
			var result = $.parseJSON(result);
				muestraMensaje(result.texto);
				$('#numprod').text(result.numProd);
			})
		.fail(function() {
			alert('Se ha producido un error');
		})
	}

	$(document).on('input change', '#input_m2', function() {
		$('.input_num').val(parseFloat($(this).val()).toFixed(2));
		$('.paso2').fadeIn();
	});

	$(document).on('input change', '.input_num', function() {
		$(this).val(parseFloat($(this).val()).toFixed(2));
		$('#input_m2').val( $(this).val() );
		$('.paso2').fadeIn();
	});

</script>
