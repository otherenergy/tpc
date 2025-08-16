<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');

include ('../config/db_connect.php');
include ('../class/userClass.php');
$userClass = new userClass();

$url_metas=$userClass->url_metas($id_url,$id_idioma);
$url_data=$userClass->obtener_informacion_url($id_url, $id_idioma);
$contenido_general=$userClass->contenido_general($id_url, $id_idioma);
include('../includes/vocabulario.php');
include('../includes/urls.php');

$url_page = $url;

$video=$userClass->obtener_video($id_url,$id_idioma,0);

$urlsDinamicas = [
    "{url_tienda}" => $link_tienda,
    "{url_smart_cover_repair}" => $link_smart_cover_repair,
	"{url_smart_varnish_repair}" => $link_smart_varnish_repair,
	"{url_smart_cleaner}" => $link_smart_cleaner,
	"{url_smart_kit_sj}" => $link_smart_kit_sj,
	"{url_smart_kit_cj}" => $link_smart_kit_cj,
    "{video_youtube}" => $video->enlace,
];

$content = $contenido_general->content;

// Reemplaza URLs dinámicas en el contenido
foreach ($urlsDinamicas as $marcador => $valor) {
    $content = str_replace($marcador, $valor, $content);
}

// Función para generar bloques de imágenes
function colores_elegance($prefix, $numbers) {
    $block = '';
    foreach ($numbers as $i) {
        $block .= '<div class="item">
            <img class="img_color" src-zoom="../assets/img/colores/alta/' . $prefix . $i . '.jpg" src="../assets/img/colores/' . $prefix . $i . '.jpg" alt="Ellegance Collection color ' . $prefix . $i . '" title="Ellegance Collection color ' . $prefix . $i . '" width="158" height="112">
            <div class="pie_fot">Color ' . $prefix . $i . '</div>
        </div>';
    }
    return $block;
}

// Gamas de colores específicas
$colorRanges = [
    'A' => [1],         
    'B' => [1, 2, 3],    
    'C' => [1, 2, 3],     
    'D' => [1, 2],     
    'E' => [1, 2, 3, 4, 6],  
    'F' => [1],           
    'G' => [2],           
    'H' => [1, 2],       
    'I' => [1],           
    'J' => [1]            
];


foreach ($colorRanges as $prefix => $numbers) {
    $content = str_replace("{bloque_colores_$prefix}", colores_elegance($prefix, $numbers), $content);
}

function colores_pintura_azulejos($start, $end) {
    $block = '';
    for ($i = $start; $i <= $end; $i++) {
        $block .= '<div class="item">
            <img class="img_color" src-zoom="../assets/img/colores_smartcover/maxi/sc' . $i . '.jpg" src="../assets/img/colores_smartcover/maxi/sc' . $i . '.jpg" alt="Smartcover Tiles color SC' . $i . '" title="Smartcover Tiles color SC' . $i . '" width="158" height="112">
            <div class="pie_fot">Color SC' . $i . '</div>
        </div>';
    }
    return $block;
}

$content = str_replace('{bloque_colores_1_5}', colores_pintura_azulejos(1, 5), $content);
$content = str_replace('{bloque_colores_6_10}', colores_pintura_azulejos(6, 10), $content);

?>


<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
    <?php include('../includes/head.php');?>
    <link rel="preload" href='assets/css/smart_style.css?<?php echo rand() ?>'  as="style" />
	<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"  integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" as="style">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	



    <body class="smartcover" id="core_inicio">



        <!-- Header - Inicio -->
        <?php include('../includes/header.php'); ?>
        <!-- Header - Fin -->


        <?php echo $content ?>
		<?php include('../includes/formulario-contacto.php') ?>
	    <?php include('../includes/gracias_envio_formulario.php') ?>
		<div class="sep100 desktop"></div>



		<!-- Footer - Inicio -->
		<?php include('../includes/footer.php'); ?>
		<!-- Footer - Fin -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
        </script> -->

        <script>
            <?php $link = $_SERVER['REQUEST_URI'];
        $link_array = explode('/',$link);
        $page = end($link_array); ?>
            $('ul.navbar-nav .nav-item a').removeClass('active');
        $('ul.navbar-nav .nav-item a[href="<?php echo $page ?>"]').addClass('active');

        $('.dib').click(function(event) {
            $('.submenu').toggleClass('open')
        });
        $('.submenu_items li').click(function(event) {
            $('.submenu').removeClass('open');
        });
        </script>

        <?php 
        $url = $url_page;
        include('../includes/esquemas_webpage.php'); ?>
	</body>
</html>


