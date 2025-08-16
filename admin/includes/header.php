<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
include( './includes/admin_seguridad.php' );
include_once('../assets/lib/bbdd.php');
// include_once('../assets/lib/funciones.php');
// include_once('../config/db_connect.php');
// include_once('../class/userClass.php');
include_once('./assets/lib/funciones_admin.php');

?>

<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<title><?php echo $title ?></title>
		<meta name="description" content="<?php echo $description ?>">
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex,nofollow">
		<link rel="icon" href="../assets/img/favicon.png">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
		<link rel='stylesheet' href='../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
		<link rel='stylesheet' href='./assets/css/style.css?<?php echo rand() ?>' type='text/css' />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"> -->

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css" integrity="sha512-E4kKreeYBpruCG4YNe4A/jIj3ZoPdpWhWgj9qwrr19ui84pU5gvNafQZKyghqpFIHHE4ELK7L9bqAv7wfIXULQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.1/r-2.4.0/datatables.min.css"/>

	</head>
		<header>

			<div id="mensaje"><p></p></div>
			<div class="container"></div>
			</nav>
		</header>
