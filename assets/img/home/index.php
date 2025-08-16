<?php 
$id_tipo="1";
$id_url="1";
$idioma_url="es";
$url="index";
$id_idioma="1";
?>
<?php
include('../config/db_connect.php');
include('../class/userClass.php');
$userClass = new userClass();

session_start();
$_SESSION['nivel_dir'] = 1;

$url_metas = $userClass->url_metas($id_url, $id_idioma);
$url_data = $userClass->obtener_informacion_url($id_url, $id_idioma);
$contenido_bloque = $userClass->contenido_bloque($id_url, $id_idioma);
$productos_destacados_ofertas = $userClass->productos_destacados('ofertas', $id_idioma);
$productos_destacados_superventas = $userClass->productos_destacados('superventas', $id_idioma);
$moneda_obj = $userClass->obtener_moneda();
$moneda = htmlspecialchars($moneda_obj->moneda ?? '', ENT_QUOTES, 'UTF-8');
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

// echo '<pre>';
// var_dump($contenido[1][1]); 
// echo '</pre>';
?>
<?php
function render_productos($productos, $moneda, $vocabulario_envio, $vocabulario_IVA, $vocabulario_comprar, $vocabulario_anadir_carrito, $id_idioma, $mostrar_tiempo_oferta = false)
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
        <div class="producto-item">
            <div class="producto">
                <?php if ($descuento != 0) { ?>
                    <div class='rebaja'>-<?php echo $descuento; ?>%</div>
                <?php } ?>
                <a href="<?php echo $url_producto; ?>">
                    <img class="img_producto" src="../assets/img/productos/<?php echo $img_product; ?>" alt="<?php echo $nombre_producto; ?>" title="<?php echo $nombre_producto; ?>">
                    <h3 class="nombre_producto"><?php echo $nombre_producto; ?></h3>
                </a>
                <div class="barra_horizontal"> </div>
                <?php
                if ($precio_descuento > 100) {
                    if ($descuento == 0) {
                        echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio . '</small>' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
                    } else {
                        echo '<p class="precio"><small class="envio_gratis_txt">' . $vocabulario_envio . '</small>' . $precio_descuento . $moneda . ' <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . '€<br></strong></small></span></p>';
                    }
                } else {
                    if ($descuento == 0) {
                        echo '<p class="precio">' . $precio_base . $moneda . ' <span><small> ' . $vocabulario_IVA . '</small></span></p>';
                    } else {
                        echo '<p class="precio">' . $precio_descuento . $moneda . ' <span>' . $vocabulario_IVA . '<small><strong class="descuento">' . $precio_base . '€<br></strong></small></span></p>';
                    }
                }
                ?>
                <?php if ($variante == 1) { ?>
                    <a class="btn-palcarrito submit" href="<?php echo $url_producto; ?>">
                        <img class="icono_carrito" src="../assets/img/home/icons/carrito.svg" alt="">
                        <span><?php echo $vocabulario_comprar; ?></span>
                    </a>
                <?php } else { ?>
                    <button id="submit" idProd="<?php echo $id_producto; ?>" cantidad="1" type="button" class="btn-palcarrito" id_idioma="<?php echo $id_idioma; ?>" onclick="addProductCar($(this))">
                        <img class="icono_carrito" src="../assets/img/home/icons/carrito.svg" alt="">
                        <span><?php echo $vocabulario_anadir_carrito; ?></span>
                    </button>
                <?php } ?>
            </div>
            <?php if ($mostrar_tiempo_oferta) {
                $class = ($count == 0) ? 'izquierda' : (($count == 1) ? 'centro' : 'derecha');
            ?>
                <div class="tiempo_oferta <?php echo $class; ?>"><span><?php echo $vocabulario_termina_en_tiempo; ?> <strong>24:45:38</strong></span></div>
            <?php
                $count++;
            } ?>
        </div>
<?php
    }
}
?>

<!DOCTYPE html>
<html lang="es-ES">
<?php include('../includes/head.php'); ?>
<link rel="stylesheet" href="../assets/css/index.css">
<link rel="preload" href='../assets/css/index.css?<?php echo rand() ?>' as="style" />
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


<body class="home_smart">

    <!-- Header - Inicio -->
    <?php include('../includes/header.php'); ?>
    <!-- Header - Fin -->

    <div class="banner">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/home/banner_mov.webp" media="(max-width: 768px)">
                        <img src="../assets/img/home/banner.png" alt="Banner promocional">
                    </picture>
                </div>
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/home/banner_mov.webp" media="(max-width: 768px)">
                        <img src="../assets/img/home/banner.png" alt="Banner promocional">
                    </picture>
                </div>
                <div class="swiper-slide">
                    <picture>
                        <source srcset="../assets/img/home/banner_mov.webp" media="(max-width: 768px)">
                        <img src="../assets/img/home/banner.png" alt="Banner promocional">
                    </picture>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <div class="sep_50" style="padding: 25px 0;"></div>

    <div class="home_productos_destacados">
        <div class="home_header">
            <?php echo $contenido[1][1] ?>
            <img src="../assets/img/home/curiosos.webp" alt="Curiosos">
        </div>

        <div class="<?php echo !empty($productos_destacados_ofertas) ? 'home_ofertas' : ''; ?>">
            <div class="row">
                <p class="slogan_destacados" style="line-height: 25px;"><?php echo $vocabulario_nuevas_ofertas ?></p>
                <?php render_productos($productos_destacados_ofertas, $moneda, $vocabulario_envio, $vocabulario_IVA, $vocabulario_comprar, $vocabulario_anadir_carrito, $id_idioma, true); ?>
            </div>
        </div>
        <div class="sep_50" style="padding: 25px 0;"></div>
        <div class="<?php echo !empty($productos_destacados_superventas) ? 'home_superventas' : ''; ?>">
            <div class="row">
                <p class="slogan_destacados" style="line-height: 25px;"><?php echo $vocabulario_mas_vendido ?></p>
                <?php render_productos($productos_destacados_superventas, $moneda, $vocabulario_envio, $vocabulario_IVA, $vocabulario_comprar, $vocabulario_anadir_carrito, $id_idioma, false); ?>
            </div>
        </div>
    </div>

    <div class="sep_20" style="padding: 10px 0;"></div>

    <div class="home_manos">
        <div class="mano mano_izquierda">
            <img src="../assets/img/home/mano_izquierda.png" alt="Mano Izquierda">
        </div>
        <div class="movil">
            <img src="../assets/img/home/movil.png" alt="Movil">
            <div class="video-wrapper">
                <video src="../<?php echo $video->enlace ?>" controls></video>
            </div>
        </div>
        <div class="mano mano_derecha">
            <img src="../assets/img/home/mano_derecha.png" alt="Mano Derecha">
        </div>
    </div>
    <div class="home_manos_movil">
        <div class="mano_movil mano_izquierda_movil">
            <img src="../assets/img/home/mano_izquierda_movil.png" alt="Mano Izquierda">
        </div>
        <div class="movil">
            <img src="../assets/img/home/movil.png" alt="Movil">
        </div>
        <div class="mano_movil mano_derecha_movil">
            <img src="../assets/img/home/mano_derecha_movil.png" alt="Mano Derecha">
        </div>
    </div>

    <div class="sep_30" style="padding: 15px 0;"></div>

    <div class="home_content">
        <section class="index_core">
            <?php echo $contenido[2][1] ?>
            <div class="products">
                <div class="product">
                    <img src="../assets/img/home/microcemento.webp" alt="Microcemento">
                    <h3><a href="<?php echo $ruta_link2 ?><?php echo $link_fabricante_microcemento ?>"><?php echo $vocabulario_microcemento_listo_al_uso ?></a></h3>
                </div>
                <div class="product">
                    <img src="../assets/img/home/smartcover_tiles.webp" alt="Pintura">
                    <h3><a href="<?php echo $ruta_link2 ?><?php echo $link_pintura_smartcover ?>"><?php echo $vocabulario_pintura_azulejos ?></a></h3>
                </div>
                <div class="product">
                    <img src="../assets/img/home/hormigon_impreso.webp" alt="Hormigón impreso">
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
                    <img src="../assets/img/home/realdiy_desk.webp" alt="Real DIY">
                    <a href="<?php echo $ruta_link2 ?><?php echo $link_reformas_diy ?>" class="play_realdiy"><?php echo $vocabulario_dale_play ?></a>
                </div>
                <?php echo $contenido[2][3] ?>
            </div>
        </section>

        <div class="sep_50" style="padding: 25px 0;"></div>

        <section class="home_blog">
            <h2><?php echo $vocabulario_blog_ultimas_noticias ?></h2>
            <div class="blog_posts">
                <?php foreach (array_slice($blog_contenido, 0, 3) as $tarjeta) {
                    $ruta_imagen_ajustada = str_replace("../../assets/", "../assets/", htmlspecialchars($tarjeta->image, ENT_QUOTES, 'UTF-8'));
                ?>
                    <div class="blog_post">
                        <img src="<?php echo $ruta_imagen_ajustada; ?>" alt="<?php echo htmlspecialchars($tarjeta->title, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="post_content">
                            <h3><?php echo htmlspecialchars($tarjeta->title, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="fecha"><?php echo htmlspecialchars($tarjeta->fecha, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p><?php echo htmlspecialchars($tarjeta->description, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="content_link">
                            <a class="btn_leer" href="./blog/<?php echo htmlspecialchars($tarjeta->valor, ENT_QUOTES, 'UTF-8'); ?>" style="color: #ffffff; text-decoration: none;">
                                <img src="../assets/img/home/icons/leer.svg" alt="<?php echo $vocabulario_leer_mas ?>"><?php echo $vocabulario_leer_mas ?>
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
                <img src="../assets/img/home/newsletter.webp" alt="Newsletter">
                <h2><?php echo $vocabulario_suscribete_newsletter ?></h2>
            </div>
            <div class="newsletter_form">
                <form action="#" method="post">
                    <div class="form_group">
                        <input type="text" name="nombre" placeholder="Nombre">
                        <input type="text" name="apellidos" placeholder="Apellidos">
                        <input type="email" name="email" placeholder="Email">
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
                <img src="../assets/img/home/colabora.webp" alt="<?php echo $vocabulario_colabora ?>">
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

    <script>
        $('#img-video-home').click(function(event) {
            var precio = '<video style=" z-index: 52 !important; " width="99%" id="miVideo" title="<?php echo $video->alt; ?>" controls><source src="../<?php echo $video->enlace ?>" type="video/mp4"></video>';
            $('#div-video-home').html(precio);
            var video = document.getElementById("miVideo");
            video.style.display = "block";
            video.play();

            video.addEventListener('ended', terminoVideo);

            function terminoVideo() {
                video.style.display = "none";
            }
        });
        $('#icono-yt-video').click(function(event) {
            var precio = '<video style=" z-index: 52 !important; " width="99%" id="miVideo" title="<?php echo $video->alt; ?>" controls><source src="../<?php echo $video->enlace ?>" type="video/mp4"></video>';
            $('#div-video-home').html(precio);
            var video = document.getElementById("miVideo");
            video.style.display = "block";
            video.play();

            video.addEventListener('ended', terminoVideo);

            function terminoVideo() {
                video.style.display = "none";
            }
        });
    </script>

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
    </script>

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

</body>

</html>