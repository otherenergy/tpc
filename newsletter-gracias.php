<?php
$email=$_POST['email'];
$url= 'mailings/email_bienvenido_newsletter.php?email='.$email;

?>
<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title>Smartcret | Do It Yourself | Reforma sin obras</title>
		<meta name="description" content="¿Imaginas poder hacer una reforma sin obras? Con el microcemento Smartcret puedes hacerlo tú mismo de manera fácil, rápida y divertida.">
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- 		<meta http-equiv="refresh" content="3;url=mailings/bienvenido-newsletter.php?email=<?php echo $email; ?>"> -->
		<link rel='stylesheet' href='assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel='stylesheet' href='cookies/cookies.css' type='text/css' />
		<link rel="icon" href="assets/img/favicon.png">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-169820052-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-169820052-1');
		</script>
		
	</head>
	<body>
	<!-- Header - Inicio -->
	<?php include('includes/header.php'); ?>
	<!-- Header - Fin -->
	<?php


		$nombre=$_POST['nombre'];
		$email=$_POST['email'];
		$idioma='es';
		$user_ip = obten_ip();
		$fecha_nacimiento=$_POST['fecha'];
		function cambiarFormatoAMysql($fecha_nacimiento){
			preg_match( '/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})/', $fecha_nacimiento, $mifecha);
			$fecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
			return $fecha;
		}

		$sql_existe = "SELECT * FROM newsletter WHERE email = '$email'";
		$res_existe=consulta($sql_existe, $conn);

		if( numFilas( $res_existe ) > 0 ) {

			echo "
			<script>
				alert('" . $email . " ya está suscrito a nuestra newsletter');
				window.location.href ='./';
			</script>";

		}else {

			$fecha_nacimiento=cambiarFormatoAMysql($fecha_nacimiento);

			$valores="";
			$valores.="nombre='$nombre', ";
			$valores.="email='$email', ";
			$valores.="fecha_nacimiento='$fecha_nacimiento', ";
			$valores.="idioma='$idioma', ";
			$valores.="user_ip='$user_ip', ";
			$valores.="origen='Form. home'";

			$sql="INSERT INTO newsletter SET $valores";

			if ( consulta( $sql, $conn ) ) { ?>

				<div class="container">
				<div class="row">
					<h3 style="margin-top:5%;margin-bottom:3%">En Smartcret hemos encendido las luces de fiesta y puesto a toda pastilla nuestra playlist.</br>Porque las buenas noticias hay que celebrarlas.</h3>
				</div>
				<div class="row">
					<h1 style="margin-bottom:3%">Gracias por suscribirte, <?php echo $nombre?>!</h1>
				</div>
				<div class="row">
					<img src="assets/img/gracias-por-suscribirte.jpg">
				</div>
			</div>
			<h2 style="margin-bottom:5%;margin-top:3%;">En breve te redirigiremos a nuestra <a href="tienda-microcemento" class="verde" style="text-decoration:none;">Tienda</a></h2>
			<script>
				setTimeout( function() {
			 			window.location.href ='./tienda-microcemento';
			 		},10000);
			</script>
			<!-- Footer - Inicio -->
			<?php include('includes/footer.php'); ?>
			<!-- Footer - Fin -->
			<script>
				$.ajax({
					url: '<?php echo $url ?>',
					type: 'GET',
					data: {email: '<?php echo $email ?>'},
				})
				.done(function() {

				})

			</script>
			</body>
		</html>

		<?php

			} else {
				echo "<script>alert('Se ha producido un error')</script>";
			}
		}

?>
