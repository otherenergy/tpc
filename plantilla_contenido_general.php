<?php
session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');
include ('../config/db_connect.php');
include ('../class/userClass.php');

$userClass = new userClass();
$url_data=$userClass->obtener_informacion_url($id_url, $id_idioma);
$url_metas=$userClass->url_metas($id_url,$id_idioma);
$contenido_general=$userClass->contenido_general($id_url, $id_idioma);

include('../includes/vocabulario.php');
include('../includes/urls.php');

$url_page = $url;

?>

<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
    <?php include('../includes/head.php');?>
	<body>
        <!-- Header - Inicio -->
        <?php include('../includes/header.php'); ?>
        <!-- Header - Fin -->

        <?php echo $contenido_general->content ?>

		
		<?php 
		if ($id_url=="83"){
			include('../includes/formulario-contacto.php');
		}
		?>
		<div class="sep100 desktop"></div>

		<!-- Footer - Inicio -->
		<?php include('../includes/footer.php'); ?>
		<!-- Footer - Fin -->
    <script src="../assets/lib/form-js.js"></script>

	</body>

    <style>
        /* reformas-DIY */
		.cab_red {
		  height: 75px;
		  margin-bottom: 50px;
		  background-color: #92bf23;
		  text-align: center;
		  line-height: 75px;
		  font-size: 35px;
		}
		.vid_red .item {
		  padding: 30px;
		}
		.col-md-6.item iframe {
		  width: 100%;
		}
		.vid_red.youtube .item iframe {
		    height: 210px;
		    width: 100%;
		}
		.vid_red.instagram iframe {
		    min-width: 250px!important;
		}
		.vid_red.youtube .item {
	    padding: 20px;
	  }
	  .videos_red {
		    text-align: center;
		    margin-bottom: 40px;
		    margin-top: 35px;
		}
		.cab_img {
	    display: inline-block;
	    position: relative;
	  }
	  .cab_img h1 {
		  position: absolute;
		  top: 50px;
		  right: 1.8%;
		  text-align: right;
		  font-size: 2.4vw;
		  line-height: 2.8vw;
		  font-weight: 500;
		}
        /* reformas-DIY */
	</style>

	<?php
	$url = $url_page;
	include('../includes/esquemas_webpage.php'); ?>

</body>
</html>