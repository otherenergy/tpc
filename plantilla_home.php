<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');

include('../config/db_connect.php');
include('../class/userClass.php');
$userClass = new userClass();

$url_metas = $userClass->url_metas($id_url, $id_idioma);
$contenido_bloque = $userClass->contenido_bloque($id_url, $id_idioma);
$productos_destacados = $userClass->productos_destacados(['ofertas', 'superventas'], $id_idioma);

$productos_destacados_ofertas = [];
$productos_destacados_superventas = [];

foreach ($productos_destacados as $producto_destacado) {
    if ($producto_destacado->tipo == 'superventas') {
        $productos_destacados_superventas[] = $producto_destacado;
    };
    if ($producto_destacado->tipo == 'ofertas') {
        $productos_destacados_ofertas[] = $producto_destacado;
    };
}

$moneda_obj = $userClass->obtener_moneda();
$moneda = $moneda_obj->moneda;
$video = $userClass->obtener_video($id_url, $id_idioma, 0);
$blog_contenido = $userClass->blog_contenido($id_idioma);

include('../includes/vocabulario.php');
include('../includes/urls.php');

$contenido = [];

foreach ($contenido_bloque as $item) {
    $id_bloque = $item->id_bloque;
    $id_bloque_hijo = $item->id_bloque_hijo;
    $contenido[$id_bloque][$id_bloque_hijo] = $item->content;
}

?>
<?php
// function render_productos($productos, $moneda, $vocabulario_envio_gratis, $vocabulario_IVA, $vocabulario_comprar, $vocabulario_anadir_carrito, $id_idioma, $mostrar_tiempo_oferta = false)
function render_productos($productos, $moneda, $vocabulario_envio_gratis, $vocabulario_IVA, $vocabulario_comprar, $vocabulario_anadir_carrito, $id_idioma)
{
    $count = 0; // Contador para el orden
    foreach ($productos as $producto) {
        $img_product = htmlspecialchars($producto->miniatura ?? '', ENT_QUOTES, 'UTF-8');
        $id_producto = htmlspecialchars($producto->id ?? '', ENT_QUOTES, 'UTF-8');
        $variante = htmlspecialchars($producto->variante ?? '', ENT_QUOTES, 'UTF-8');
        $nombre_producto = htmlspecialchars($producto->nombre ?? '', ENT_QUOTES, 'UTF-8');
        $url_producto = htmlspecialchars($producto->url ?? '', ENT_QUOTES, 'UTF-8');
        $precio_base = isset($producto->precio_base) ? number_format((float)$producto->precio_base, 2, ".", "") : '0.00';
        $descuento = isset($producto->descuento) ? (int)$producto->descuento : 0;
        $precio_descuento = number_format((float)$producto->precio_base * (100 - $descuento) / 100, 2, ".", "");
?>
        <a class="producto-item" href="<?php echo $url_producto; ?>">
            <div class="producto">
                <?php if ($descuento != 0) { ?>
                    <div class='rebaja'>-<?php echo $descuento; ?>%</div>
                <?php } ?>
                <div>
                    <img height="10px" width="100%" class="img_producto" src="../assets/img/productos/<?php echo $img_product; ?>" alt="<?php echo $nombre_producto; ?>" title="<?php echo $nombre_producto; ?>">
                    <h3 class="nombre_producto"><?php echo $nombre_producto; ?></h3>
                </div>
                <div class="barra_horizontal"> </div>
                <?php
                if ($precio_descuento > 100) {
                    if ($descuento == 0) {
                        echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small>' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
                    } else {
                        echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio_gratis . '</small>' . $precio_descuento . $moneda . ' <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . $moneda . '<br></strong></small></span></p>';
                    }
                } else {
                    if ($descuento == 0) {
                        echo '<p class="precio">' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
                    } else {
                        echo '<p class="precio">' . $precio_descuento . $moneda . ' <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . $moneda . '<br></strong></small></span></p>';
                    }
                }
                ?>
                <button class="btn-palcarrito"><img height="24px" width="24px" class="icono_carrito" src="../assets/img/iconos/icono_carrito.svg" alt=""> <?php echo $vocabulario_comprar; ?></button>
            </div>
            <!-- <?php # if ($mostrar_tiempo_oferta) {
                    # $class = ($count == 0) ? 'izquierda' : (($count == 1) ? 'centro' : 'derecha');
                    ?>
                <div class="tiempo_oferta <?php #echo $class;
                                            ?>"><span><?php #echo $vocabulario_termina_en_tiempo;
                                                        ?> <strong>24:45:38</strong></span></div>
            <?php
            #$count++;
            #}
            ?> -->
        </a>
<?php
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
<?php include('../includes/head.php'); ?>
<link rel="preload" href="../assets/img/banners/descuentos_verano24/smart_kit_2_<?php echo $idioma_url ?>.webp" as="image" type="image/webp" crossorigin="anonymous">

<link rel="stylesheet" href="../assets/css/index.css?<?php echo rand() ?>">
<link rel="preload" href='../assets/css/index.css?<?php echo rand() ?>' as="style" />
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


<body class="home_smart">

    <!-- Header - Inicio -->
    <?php include_once('../includes/header.php'); ?>
    <?php include_once('../includes/geolocation.php'); ?>
    <!-- Header - Fin -->

    <?php if ( $userClass->banner_promocion_activo() ) { ?>

    <div class="banner">
        <a class="swiper-container" href="<?php echo $link_ofertas_smartcret; ?>">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/banners/banners_promos_diarias/banner_ofertas_<?php echo $idioma_url ?>_mov.webp" media="(max-width: 768px)">
                        <img height="500px" width="500px" src="../assets/img/banners/banners_promos_diarias/banner_ofertas_<?php echo $idioma_url ?>.webp" alt="Banner" loading="eager" fetchpriority="high">
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

    <div class="sep_50" style="padding: 25px 0;"></div>

    <div class="home_productos_destacados">
        <div class="home_header">
            <?php echo $contenido[1][1] ?>
            <img height="100px" width="100px" src="../assets/img/home/curiosos.webp" alt="Curiosos">
        </div>

        <div class="<?php echo !empty($productos_destacados_ofertas) ? 'home_ofertas' : ''; ?>">
            <div class="row">
                <h2 class="slogan_destacados" style="line-height: 25px;"><?php echo $vocabulario_nuevas_ofertas ?></h2>
                <?php render_productos($productos_destacados_ofertas, $moneda, $vocabulario_envio_gratis, $vocabulario_IVA, $vocabulario_comprar, $vocabulario_anadir_carrito, $id_idioma, true); ?>
            </div>
        </div>
        <div class="sep_50" style="padding: 25px 0;"></div>
        <div class="<?php echo !empty($productos_destacados_superventas) ? 'home_superventas' : ''; ?>">
            <div class="row">
                <h2 class="slogan_destacados" style="line-height: 25px;"><?php echo $vocabulario_mas_vendido ?></h2>
                <?php render_productos($productos_destacados_superventas, $moneda, $vocabulario_envio_gratis, $vocabulario_IVA, $vocabulario_comprar, $vocabulario_anadir_carrito, $id_idioma, false); ?>
            </div>
        </div>
    </div>

    <div class="sep_20" style="padding: 10px 0;"></div>

    <div class="home_manos">
        <div class="mano mano_izquierda">
            <img height="100px" width="100px" src="../assets/img/home/mano_izquierda.png" alt="Mano Izquierda">
        </div>
        <div class="movil">
            <img height="100px" width="100px" src="../assets/img/home/movil.png" alt="Movil">
            <div class="video-wrapper-home">
                <img height="10px" width="10px" src="../assets/video/cap2.webp" alt="Img video" id="img-video-home">
                <img height="80px" width="10px" src="../assets/video/logo_youtube.png" alt="Youtube" id="icono-yt-video">
                <div id="div-video-home"></div>
            </div>
        </div>
        <div class="mano mano_derecha">
            <img height="100px" width="100px" src="../assets/img/home/mano_derecha.png" alt="Mano Derecha">
        </div>
    </div>
    <div class="home_manos_movil">
        <div class="movil">
            <img height="768px" width="619px" src="../assets/img/home/manos_movil.png" alt="Movil">
            <div class="video-wrapper-home">
                <img height="10px" width="10px" src="../assets/video/cap2.webp" alt="Img video" id="img-video-home-movil">
                <img height="80px" width="113.6px" src="../assets/video/logo_youtube.png" alt="Youtube" id="icono-yt-video-movil">
                <div id="div-video-home-movil"></div>
            </div>
        </div>
    </div>
    <div class="sep_30" style="padding: 15px 0;"></div>

    <div class="home_content">
        <section class="index_core">
            <?php echo $contenido[2][1] ?>
            <div class="products">
                <div class="product">
                    <img height="10px" width="10px" src="../assets/img/home/microcemento.webp" alt="Microcemento">
                    <h3><a href="<?php echo $ruta_link2 ?><?php echo $link_microcemento_listo_al_uso ?>"><?php echo $vocabulario_microcemento_listo_al_uso ?></a></h3>
                </div>
                <div class="product">
                    <img height="10px" width="10px" src="../assets/img/home/smartcover_tiles.webp" alt="Pintura">
                    <h3><a href="<?php echo $ruta_link2 ?><?php echo $link_pintura_smartcover ?>"><?php echo $vocabulario_pintura_azulejos ?></a></h3>
                </div>
                <div class="product">
                    <img height="10px" width="10px" src="../assets/img/home/hormigon_impreso.webp" alt="Hormigón impreso">
                    <h3><a href="<?php echo $ruta_link2 ?><?php echo $link_hormigon_impreso ?>"><?php echo $vocabulario_hormigon_impreso ?></a></h3>
                </div>
            </div>
        </section>

        <div class="sep_50" style="padding: 25px 0;"></div>

        <section class="home_calidad">
            <?php echo $contenido[2][2] ?>
        </section>

        <div class="sep_50" style="padding: 25px 0;"></div>

        <section class="home_realdiy">
            <div class="realdiy_content">
                <div class="realdiy_image">
                    <img height="10px" width="10px" class="real_desktop" src="../assets/img/home/real_DIY_desktop.webp" alt="Real DIY">
                    <img height="360px" width="752px" class="real_movil" src="../assets/img/home/real_DIY_mobile_new.webp" alt="Real DIY">
                    <div class="video-wrapper-diy">
                        <video class="video_influencers" controls>
                            <source src="../assets/video/video_ifluencers.mp4" type="video/mp4">
                            <track src="captions_en.vtt" kind="captions" srclang="en" label="english_captions">
                            <track src="captions_es.vtt" kind="captions" srclang="es" label="spanish_captions">
                        </video>
                    </div>
                    <a href="<?php echo $ruta_link2 ?><?php echo $link_reformas_diy ?>" class="play_realdiy"><?php echo $vocabulario_dale_play ?></a>
                </div>
                <?php echo $contenido[2][3] ?>
            </div>
        </section>


        <div class="sep_100 espacio_realdiy" style="padding: 50px 0;"></div>

        <section class="home_blog">
            <h2><?php echo $vocabulario_blog_ultimas_noticias ?></h2>
            <div class="blog_posts">
                <?php foreach (array_slice($blog_contenido, 0, 3) as $tarjeta) {
                    $ruta_imagen_ajustada = str_replace("../../assets/", "../assets/", htmlspecialchars($tarjeta->image, ENT_QUOTES, 'UTF-8'));
                ?>
                    <div class="blog_post">
                        <a href="./blog/<?php echo htmlspecialchars($tarjeta->valor, ENT_QUOTES, 'UTF-8'); ?>">
                            <img height="100px" width="100px" src="<?php echo $ruta_imagen_ajustada; ?>" alt="<?php echo htmlspecialchars($tarjeta->title, ENT_QUOTES, 'UTF-8'); ?>">
                        </a>
                        <div class="post_content">
                            <h3>
                                <a href="./blog/<?php echo htmlspecialchars($tarjeta->valor, ENT_QUOTES, 'UTF-8'); ?>" style="color: #92bf23; text-decoration: none;">
                                    <?php echo htmlspecialchars($tarjeta->title, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h3>
                            <p class="fecha"><?php echo htmlspecialchars($tarjeta->fecha, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><?php echo htmlspecialchars($tarjeta->description, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="content_link">
                            <a class="btn_leer" href="./blog/<?php echo htmlspecialchars($tarjeta->valor, ENT_QUOTES, 'UTF-8'); ?>" style="color: #ffffff; text-decoration: none;">
                                <img height="25px" width="25px" alt="icono boton leer mas" src="../assets/img/home/icons/leer.svg" alt="<?php echo $vocabulario_leer_mas ?>"><?php echo $vocabulario_leer_mas ?>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
        <div class="sep_60" style="padding: 30px 0;"></div>
    </div>

    <div class="home_newsletter">
        <div class="newsletter_content">
            <div class="newsletter_image_title">
                <img height="100px" width="100px" src="../assets/img/home/newsletter.webp" alt="Newsletter">
                <h2><?php echo $vocabulario_suscribete_newsletter ?></h2>
            </div>
            <div class="newsletter_form">
                <form action="#" method="post">
                    <div class="form_group">
                        <input type="text" name="nombre" placeholder="<?php echo $vocabulario_nombre; ?>">
                        <input type="text" name="apellidos" placeholder="<?php echo $vocabulario_apellidos; ?>">
                        <input type="email" name="email" placeholder="<?php echo $vocabulario_email; ?>">
                    </div>
                    <button type="submit"><?php echo $vocabulario_suscribirme ?></button>
                </form>
            </div>
        </div>
    </div>

    <div class="home_colabora">
        <div class="colabora_content">
            <h2><?php echo $vocabulario_colabora ?></h2>
            <div class="colabora_image">
                <img height="100px" width="50px" src="../assets/img/home/colabora.webp" alt="<?php echo $vocabulario_colabora ?>">
            </div>
            <?php echo $contenido[3][1] ?>
            <div class="link_content">
                <a href="<?php echo $ruta_link2 ?><?php echo $link_distribuidores ?>" class="colabora_link"><?php echo $vocabulario_mas_informacion ?></a>
            </div>
        </div>
    </div>

    <!-- Footer - Inicio -->
    <?php include('../includes/footer.php'); ?>
    <!-- Footer - Fin -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#img-video-home, #icono-yt-video').click(function(event) {
                var videoHtml = '<video style=" z-index: 52 !important; " width="99%" id="miVideo" controls><source src="../assets/video/<?php echo $idioma_url; ?>/<?php echo $video->enlace ?>" type="video/mp4"></video>';
                $('#div-video-home').html(videoHtml);
                var video = document.getElementById("miVideo");
                video.style.display = "block";
                video.play();

                video.addEventListener('ended', function() {
                    video.style.display = "none";
                });
            });

            $('#img-video-home-movil, #icono-yt-video-movil').click(function(event) {
                var videoHtml = '<video style=" z-index: 52 !important; " width="99%" id="miVideoMovil" controls><source src="../assets/video/<?php echo $idioma_url; ?>/<?php echo $video->enlace ?>" type="video/mp4"></video>';
                $('#div-video-home-movil').html(videoHtml);
                var video = document.getElementById("miVideoMovil");
                video.style.display = "block";
                video.play();

                video.addEventListener('ended', function() {
                    video.style.display = "none";
                });
            });
        });
    </script>
<!--
    <script type="text/javascript" id="zsiqchat">
        var $zoho = $zoho || {};
        $zoho.salesiq = $zoho.salesiq || {
            widgetcode: "siq8be00c3e2edd8fdde146aecc6b49e3c5933bd4edd27c7269a73bc1b43930c07f",
            values: {},
            ready: function() {}
        };
        var d = document;
        s = d.createElement("script");
        s.type = "text/javascript";
        s.id = "zsiqscript";
        s.defer = true;
        s.src = "https://salesiq.zohopublic.com/widget";
        t = d.getElementsByTagName("script")[0];
        t.parentNode.insertBefore(s, t);
    </script> -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.addEventListener('scroll', function() {
                var homeManos = document.querySelector('.home_manos');
                var manoIzquierda = document.querySelector('.mano_izquierda img');
                var manoDerecha = document.querySelector('.mano_derecha img');
                var rect = homeManos.getBoundingClientRect();
                var inView = (rect.top < window.innerHeight) && (rect.bottom >= 0);

                if (inView) {
                    manoIzquierda.classList.add('animate-mano-izquierda');
                    manoDerecha.classList.add('animate-mano-derecha');
                } else {
                    manoIzquierda.classList.remove('animate-mano-izquierda');
                    manoDerecha.classList.remove('animate-mano-derecha');
                }
            });
        });
    </script>

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
    </script>

    <script>
        // Controla que los post sean link a partir de 768px
        document.addEventListener("DOMContentLoaded", function() {
            function addFullLink() {
                const posts = document.querySelectorAll('.blog_post');
                const mediaQuery = window.matchMedia('(max-width: 768px)');

                posts.forEach(post => {
                    const url = post.querySelector('.btn_leer').href;
                    let fullLink = post.querySelector('a.full-link');

                    if (mediaQuery.matches) {
                        if (!fullLink) {
                            fullLink = document.createElement('a');
                            fullLink.href = url;
                            fullLink.aria-label = 'visualizar contenido';
                            fullLink.className = 'full-link';
                            post.appendChild(fullLink);
                        }
                    } else {
                        if (fullLink) {
                            post.removeChild(fullLink);
                        }
                    }
                });
            }

            addFullLink();
            window.addEventListener('resize', addFullLink);
        });
    </script>

    <!-- <div id="esquema-home"></div>
    <script>
        $(document).ready(function() {
            $("#esquema-home").load("../includes/esquema_home.php");
        });
    </script> -->

    <?php include('../includes/esquema_home.php'); ?>
</body>

</html>