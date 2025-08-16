<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');
if (session_status() === PHP_SESSION_NONE){session_start();}
if ( isset($_SESSION["smart_user"]["login"]) && $_SESSION["smart_user"]["login"]!='0') {
	header("Location: /");
}
?>
<?php

include ('../config/db_connect.php');
include ('../class/userClass.php');

$userClass = new userClass();

$url_data=$userClass->obtener_informacion_url($id_url, $id_idioma);
$url_metas=$userClass->url_metas($id_url,$id_idioma);
$contenido_general=$userClass->contenido_general($id_url, $id_idioma);

// echo '<pre>';
// var_dump($contenido_general->content);
// echo '</pre>';

?>
<?php
header("HTTP/1.1 404 Not Found");
?>
<!DOCTYPE html>
<html lang="es-ES">
    <?php include('../includes/head.php');?>
	<body>
        <!-- Header - Inicio -->
        <?php include('../includes/header.php'); ?>
        <!-- Header - Fin -->

        <?php echo $contenido_general->content ?>

		<div class="sep100 desktop"></div>

		<!-- Footer - Inicio -->
		<?php include('../includes/footer.php'); ?>
		<!-- Footer - Fin -->
        <script src="../assets/lib/form-js.js"></script>
	</body>
</html>