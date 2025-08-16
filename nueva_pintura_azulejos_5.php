<?php 
$id_tipo="15";
$id_url="3";
$idioma_url="es";
$url="nueva_pintura_azulejos";
$id_idioma="1";

// $id_tipo="2";
// $id_url="3";
// $idioma_url="es";
// $url="pintura-azulejos-smartcover";
// $id_idioma="1";

?>
<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');

include ('../config/db_connect.php');
include ('../class/userClass.php');
$userClass = new userClass();
$url_metas=$userClass->url_metas($id_url,$id_idioma);

include('../includes/vocabulario.php');



if ($url == 'index'){$url='';}

$contenido_bloque = $userClass->contenido_bloque($id_url, $id_idioma);
$contenido = [];
foreach ($contenido_bloque as $item) {
    $id_bloque = $item->id_bloque;
    $id_bloque_hijo = $item->id_bloque_hijo;
    $contenido[$id_bloque][$id_bloque_hijo] = $item->content;
}
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma_url . "-" . strtoupper($idioma_url)?>">
	<?php include('../includes/head.php');?>
    <link rel="stylesheet" type="text/css" href="pinturas.css">

    <body class="microcemento_listo_uso">
        <?php include('../includes/header.php'); ?>

        <div class="contenedor_inicial_microcemento">
            <div class="contenedor_texto_inicial_microcemento">
                <?php echo $contenido[1][1]; ?>
                </div>
                <div class="contenedor_imagen_inicial_microcemento">
                    <img src="../assets/img/chica_rodillo.webp" alt="">
                </div>
                <div class="contenedor_imagen_secundaria_microcemento">
                    <img src="../assets/img/pinturas.webp" alt="">
                </div>
                </div>
            <div class="contenedor_imagen_fondo_microcemento">
                <img src="../assets/img/fondo_pinturas_version2.webp" alt="">
            </div>
        </div>

        <!-- <div class="contenedor_secundario_pinturas_azulejos">
            <?php // echo $contenido[2][1] ?>
        </div>   -->


        <div class="contenedor_secundario_pinturas_azulejos">
            <div class="contenedor_texto_secundario_pinturas_azulejos">
                <?php echo $contenido[2][1]; ?>

            </div>
            <div class="contenedor_imagen_secundario_pinturas_azulejos">
                <img src="../assets/img/llana_peque_a_u1596.webp" alt="" style="transform: translateX(-50px);">
            </div>
            <div class="contenedor_texto_secundario_pinturas_azulejos">
                <?php echo $contenido[2][2]; ?>
            </div>        
        </div>

        
        <script>
            let lastScrollTop = 0;

            document.addEventListener('scroll', function() {
                var image = document.querySelector('.contenedor_imagen_secundario_pinturas_azulejos img');
                var scrollTop = window.scrollY;
                var scrollDirection = scrollTop - lastScrollTop;

                if (scrollDirection > 0) {
                    image.style.transform = 'translateX(50px)';
                } else {
                    image.style.transform = 'translateX(-50px)';
                }

                lastScrollTop = scrollTop;
            });

        </script>

        <!-- /////////////////  VIDEO    ////////////////////  -->

        <div class="contenedor_video_pinturas_azulejos">
            <div >
                <img class="img_tv_pinturas_azulejos" src="../assets/img/mueble_amarillo_tv.webp" alt="Mueble amarillo con tv sobre el que se apoya la TV">
            </div>
            <img class="img_pareja_viendo" src="../assets/img/pareja_viendo_tv.webp" alt="" >
            <img class="plantas_cuadros" src="../assets/img/plantas_cuadros.png" alt="">
            <iframe width="704" height="348" src="https://www.youtube.com/embed/AprjRG-5Slw" title="🔥 ¡TRANSFORMA tu BAÑO! Descubre cómo cambiar el aspecto sin cambiar los azulejos con MICROCEMENTO 👌" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>

        <!-- /////////////////  OPCIONES     ////////////////////  -->
        
        <div class="contendor_pinturas_opciones_aplicar">
            <div class="contenedor_opciones_pinturas_opciones_aplicar">
                <?php echo $contenido[3][1]; ?>
            </div>

            <div class="contenedor_contenido_pinturas_opciones" style="display: flex;">
                <div class="contenedor_texto_pinturas_opciones">
                    <?php echo $contenido[3][2]; ?>

                </div>

                <div class="contenedor_imagen_pinturas_opciones">
                    <?php echo $contenido[3][3]; ?>
                </div>
                
            </div>  
            <div class="contenedor_fondo_opciones_aplicar"></div>  
        </div>


        <div class="contenedor_ventajas_pinturas">
            <?php echo $contenido[4][1]; ?>
        </div>

        <div class="contenedor_colores_pinturas">
            <div class="contenedor_primero_colores_pinturas">
                <?php echo $contenido[5][1]; ?>
            </div>
            <div class="contenedor_segundo_colores_microcemento">
                <?php echo $contenido[5][2]; ?>          
                <div class="contenedores_listado_colores">

                    <div class="gama_a">
                        <div class="contenedor_txt_gamma_colores">
                            <?php echo $contenido[5][3]; ?>          
                            <div class="contenedor_gammas_colores">
                                <?php
                                    $colores = $userClass->obtener_gammas_colores(11, $id_idioma);
                                    foreach ($colores as $color) { ?>
                                <button class="btn_color" data-color="<?php echo $color->valor; ?>">
                                    <?php echo $color->valor; ?>
                                </button>
                            <?php   } ?>
                            </div>
                            
                        </div>
                        <div class="contenedor_colores_gammas colores_gammas_toogle" >
                            <?php
                                $colores = $userClass->obtener_gammas_colores(11, $id_idioma);
                                $numColores = count($colores);

                                foreach ($colores as $color) {
                                    $claseImagenAdicional = ($numColores === 1) ? ' imagen_grande_gamma_colores' : '';
                                    $claseDivAdicional = ($numColores === 1) ? ' contenedor_imagen_grande_color_gammas' : '';
                                    ?>
                                    <div class="contenedor_imagen_color_gammas<?php echo $claseDivAdicional; ?>" data-color="<?php echo $color->valor; ?>">
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/alta/<?php echo $color->valor; ?>.jpg" src="../assets/img/colores/<?php echo $color->valor; ?>.jpg" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
                                        <i id="close-color" onclick="cerrar_color()" class="fa fa-times"></i>
                                    </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="gama_b" style="display:none;">

                        <div class="contenedor_txt_gamma_colores">
                            <?php echo $contenido[5][4] ?>  
                            <div class="contenedor_gammas_colores">
                                <?php
                                    $colores = $userClass->obtener_gammas_colores(12, $id_idioma);
                                    foreach ($colores as $color) { ?>
                                <button class="btn_color" data-color="<?php echo $color->valor; ?>">
                                    <?php echo $color->valor; ?>
                                </button>
                            <?php   } ?>
                            </div>
                            
                        </div>
                        <div class="contenedor_colores_gammas colores_gammas_toogle" >
                            <?php
                                $colores = $userClass->obtener_gammas_colores(12, $id_idioma);
                                $numColores = count($colores);

                                foreach ($colores as $color) {
                                    $claseImagenAdicional = ($numColores === 1) ? ' imagen_grande_gamma_colores' : '';
                                    $claseDivAdicional = ($numColores === 1) ? ' contenedor_imagen_grande_color_gammas' : '';
                                    ?>
                                    <div class="contenedor_imagen_color_gammas<?php echo $claseDivAdicional; ?>" data-color="<?php echo $color->valor; ?>">
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/alta/<?php echo $color->valor; ?>.jpg" src="../assets/img/colores/<?php echo $color->valor; ?>.jpg" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
                                        <i id="close-color" onclick="cerrar_color()" class="fa fa-times"></i>
                                    </div>
                            <?php } ?>
                        </div>
                    </div>

                            
                    <div class="gama_c" style="display:none;">
                        <div class="contenedor_txt_gamma_colores">
                            <?php  echo $contenido[5][5] ?>
                            <div class="contenedor_gammas_colores">
                                <?php
                                    $colores = $userClass->obtener_gammas_colores(13, $id_idioma);
                                    foreach ($colores as $color) { ?>
                                <button class="btn_color" data-color="<?php echo $color->valor; ?>">
                                    <?php echo $color->valor; ?>
                                </button>
                            <?php   } ?>
                            </div>

                        </div>
                        <div class="contenedor_colores_gammas colores_gammas_toogle" >
                            <?php
                                $colores = $userClass->obtener_gammas_colores(13, $id_idioma);
                                $numColores = count($colores);

                                foreach ($colores as $color) {
                                    $claseImagenAdicional = ($numColores === 1) ? ' imagen_grande_gamma_colores' : '';
                                    $claseDivAdicional = ($numColores === 1) ? ' contenedor_imagen_grande_color_gammas' : '';
                                    ?>
                                    <div class="contenedor_imagen_color_gammas<?php echo $claseDivAdicional; ?>" data-color="<?php echo $color->valor; ?>">
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/alta/<?php echo $color->valor; ?>.jpg" src="../assets/img/colores/<?php echo $color->valor; ?>.jpg" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
                                        <i id="close-color" onclick="cerrar_color()" class="fa fa-times"></i>
                                    </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="gama_d" style="display:none;">
                        <div class="contenedor_txt_gamma_colores">
                            <?php echo $contenido[5][6] ?>
                            <div class="contenedor_gammas_colores">
                                <?php
                                    $colores = $userClass->obtener_gammas_colores(14, $id_idioma);
                                    foreach ($colores as $color) { ?>
                                <button class="btn_color" data-color="<?php echo $color->valor; ?>">
                                    <?php echo $color->valor; ?>
                                </button>
                            <?php   } ?>
                            </div>

                        </div>
                        <div class="contenedor_colores_gammas colores_gammas_toogle" >
                            <?php
                                $colores = $userClass->obtener_gammas_colores(14, $id_idioma);
                                $numColores = count($colores);

                                foreach ($colores as $color) {
                                    $claseImagenAdicional = ($numColores === 1) ? ' imagen_grande_gamma_colores' : '';
                                    $claseDivAdicional = ($numColores === 1) ? ' contenedor_imagen_grande_color_gammas' : '';
                                    ?>
                                    <div class="contenedor_imagen_color_gammas<?php echo $claseDivAdicional; ?>" data-color="<?php echo $color->valor; ?>">
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/alta/<?php echo $color->valor; ?>.jpg" src="../assets/img/colores/<?php echo $color->valor; ?>.jpg" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
                                        <i id="close-color" onclick="cerrar_color()" class="fa fa-times"></i>
                                    </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="contenedor_inicial_paso_paso_microcemento">
            <div class="contenedor_inicial_paso_paso_microcemento_txt">
                <?php echo $contenido[6][1] ?>
            </div>
            <div class="contenedor_botones_mostar_paso_paso">
                <div id="mostrar_paso_paso_1">
                    <img src="../assets/img/u1706.svg" alt="Smartcover Tiles">
                </div>
                <div id="mostrar_paso_paso_2">
                    <img src="../assets/img/u1705.svg" alt="Image">
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sinJuntasButton = document.getElementById('mostrar_paso_paso_1');
                const sinJuntasElement = document.getElementById('paso_paso_microcemento_1');
                const sinJuntasH3 =  document.getElementById('h3_paso_paso_microcemento_1')

                sinJuntasButton.addEventListener('click', function() {
                    sinJuntasElement.classList.remove('ocultar-acordeon');
                    sinJuntasH3.classList.remove('ocultar');
                });

            });

        </script>
    

<div class="acordeon" id="paso_paso_microcemento_1">

    <div class="acordeon-item activo_paso_paso_1" onclick="desplegablePasoSinJuntas(580, 400)">
        <div class="acordeon-header acordeon-header-paso-paso-1" id="pregunta6">
            <button class="acordeon-button" type="button">
                <p class="txt_num_paso_paso">1</p>
                <p class="txt_paso_paso">PASO</p>
                <h4 class="h4_mitad">LIMPIEZA Y PREPARACIÓN DEL SOPORTE</h4>
                <img src="../assets/img/iconos/icono_paso_paso_limpieza.png" alt="Icono paso a paso limpieza">
            </button>
        </div>
        <div id="respuesta6" class="acordeon-contenido">
            <div class="acordeon-body">
                <h4 class="h4_mitad">LIMPIEZA Y PREPARACIÓN DEL SOPORT</h4>
                <p class="expl"><img loading="lazy" width="25" height="25" src="../assets/img/iconos/icono_step_by_step_surface.png" alt="Icono paso a paso limpieza">Limpia cuidadosamente la superficie a recubrir para dejarla libre de polvo y grasa. Enjuágala con agua limpia y sécala. Para una limpieza más completa, recomendamos nuestro limpiador exclusivo Smart Cleaner.</p>
                <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_cinta.png" alt="Icono paso a paso cinta">Coloca cinta adhesiva para delimitar la superficie donde vas a pintar.</p>
                <div>
                    <img src="../assets/img/productos/smart-booster.webp" alt="Smart Booster">
                </div>
            </div>
        </div>
    </div>

    <div class="acordeon-item posterior_paso_paso_1" onclick="desplegablePasoSinJuntas(660, 470)" style="right: -500px;">
        <div class="acordeon-header acordeon-header-paso-paso-1" id="pregunta7">
            <button class="acordeon-button" type="button">
                <p class="txt_num_paso_paso">2</p>
                <p class="txt_paso_paso">PASO</p>
                <h4 class="h4_mitad">APLICACIÓN SMARTCOVER TILES</h4>
                <img src="../assets/img/iconos/icono_paso_paso_rodillo.png" alt="Icono paso a paso rodillo">
            </button>
        </div>
        <div id="respuesta7" class="acordeon-contenido">
            <div class="acordeon-body">
                <h4 class="h4_mitad">APLICACIÓN SMARTCOVER TILES</h4>
                <p class="expl"><img loading="lazy" width="25" height="25" src="../assets/img/iconos/icono_step_by_step_stir.png" alt="Icono paso a paso remueve">Remueve el producto de manera manual con espátula</p>
                <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_pour.png" alt="Icono paso a paso cubo">Vierte el producto en una bandeja.</p>
                <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_roller.png" alt="Icono paso a paso rodillo">Humedece el rodillo y aplica 2 capas de nuestra pintura para azulejos.</p>
                <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_cinta.png" alt="Icono paso a paso cinta">Retira la cinta protectora y deja 8 horas de secado entre ellas.</p>
                <p class="expl"><img loading="lazy" width="25" height="25" src="../assets/img/iconos/icono_step_by_step_roller.png" alt="Icono paso a paso rodillo">Si la cubrición no es la deseada, pinta los azulejos con una tercera capa de Smartcover Tiles.</p>
                <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_time.png" alt="Icono paso a paso tiempo">2-3 CAPAS
                8 HORAS SECADO ENTRE CAPAS</p>
                <div>
                    <img src="../assets/img/smart-varnish.jpg" alt="Smart Varnish">
                </div>
            </div>
        </div>
    </div>        

</div>

<div class="contenedor_pinturas_tambien_interesa">
        <div class="contenedor_pinturas_tambien_interesa_txt">
        <p>¡Y voilà! Así de fácil, rápido, económico y divertido es renovar y redecorar cualquier espacio de tu casa pintando azulejos y otros materiales con Smartcover Tiles.</p>
    </div>
    <div class="contenedor_pinturas_tambien_interesa_btns">
        <a href="./kits-microcemento-banos-duchas-cocinas#kits-bdc-no-abs">Quiero mi pintura para azulejos</a>
    </div>        
</div>

<div class="contenedor_microcemento_preguntas_y_respuestas">
    <h2 class="contenedor_microcemento_preguntas_y_respuestas_title">Guía de preguntas frecuentes sobre Smartcret</h2>
        <div class="container" style="margin-bottom: 5%;">
            <div class="accordion" id="microcementoSmartcret">
                <div class="accordion-item">
                    <div id="respuestaGeneral" class="accordion-collapse collapse show" aria-labelledby="preguntaGeneral" data-bs-parent="#microcementoSmartcret">
                        <div class="accordion-body">
                            <span><label for="preguntaSelect" class="sr-only">Dudas sobre el microcemento y Smartcret</label><span>
                            <select class="form-select" id="preguntaSelect">
                                <option value="" selected="" disabled="">Selecciona una duda...</option>
                                <option value="respuesta1">¿Como se limpian los azulejos antes de pintarlos?</option>
                                <option value="respuesta12">¿Donde usar pintura de azulejos?</option>
                                <option value="respuesta2">¿Que tipo de pintura se utiliza para pintar azulejos?</option>
                            </select>
                            <div id="respuestas" style="margin-top: 20px;">
                                <div id="respuesta1" class="d-none">
                                    <p class="respuesta">Antes de pintar los azulejos es imprescindible dejar la superficie bien limpia y libre de grasa (sobre todo en la cocina). En caso contrario, la pintura no quedará bien adherida al soporte. Para limpiarla, simplemente es necesario utilizar agua y jabón neutro y limpiar con estropajo. Para una limpieza más completa, recomendamos nuestro limpiador exclusivo Smart Cleaner.<br /><br />
                                    Si hay restos de silicona, habrá que retirarla. Hay que prestar especial atención a las juntas y dejarlas también muy limpias. Si la supeficie tiene juntas abiertas, es recomendable repasarlas con nuestro tapajuntas Smart Jointer para que no haya imperfecciones.<br /><br />
                                    Pero hay que tener en cuenta que Smartcover Tiles no ha sido creado para eliminar el azulejos, sino para mejorarlo. Por eso, las juntas deben seguir siendo juntas.<br /><br />
                                    Una vez hecho esto, solo hay que secar bien y ponerse a pintar.   
                                    </p>
                                </div>
                                <div id="respuesta12" class="d-none">
                                    <p class="respuesta">Generalmente, las zonas más comunes donde poner pintura para azulejos es en baños y cocinas, pero en cualquier sitio donde haya baldosas o azulejos, se puede pintar. El único requisito indispensable para que el producto se adhiera bien es que la superficie esté bien limpia y libre de grasa.<br /><br />
                                    Aunque el azulejo esté muy viejo, no importa. Si alguna junta necesita ser retocada, se puede utilizar nuestro tapajuntas Smart Jointer para juntas y mejorarla para un resultado aún más perfecto.
                                </p>
                                </div>
                                <div id="respuesta2" class="d-none">
                                    <p>Smartcover Tiles es una pintura que destaca especialmente por su adherencia y dureza en el acabado. Sus fórmulas ofrecen un acabado uniforme con el que no quedarán marcas.<br /><br />
                                    Los revestimientos de azulejos se encuentran normalmente en estancias donde la presencia del agua es común, como baños o cocinas. Por eso, Smartcover Tiles es una pintura con un alto grado de resistencia a la humedad y al contacto directo con el agua. Por este motivo, Smartcover Tiles es una pintura ideada exclusivamente para pintar azulejos. No se puede utilizar cualquier pintura para este fin.</p>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





        <!-- <div class="contenedor_microcemento_tambien_interesa">
            <?php // echo $contenido[10][1] ?>
        </div> -->



<script>
document.querySelectorAll('.form-select').forEach(select => {
    select.addEventListener('change', function() {
        var selectedValue = this.value;
        var respuestas = this.closest('.accordion-body').querySelectorAll('#respuestas > div');
        
        respuestas.forEach(function(respuesta) {
            if (respuesta.id === selectedValue) {
                respuesta.classList.remove('d-none');
            } else {
                respuesta.classList.add('d-none');
            }
        });
    });
});

</script>


<script>
document.querySelectorAll('.acordeon-header-paso-paso-1').forEach(header => {
    header.addEventListener('click', () => {
        const item = header.parentElement;
        const accordion = item.parentElement;
        const items = Array.from(accordion.children);
        const index = items.indexOf(item);

        items.forEach((el, i) => {
            if (i < index) {
                el.classList.remove('posterior_paso_paso_1', 'activo_paso_paso_1');
                el.classList.add('anterior_paso_paso_1');
            } else if (i === index) {
                el.classList.remove('anterior_paso_paso_1', 'posterior_paso_paso_1');
                el.classList.add('activo_paso_paso_1');
            } else {
                el.classList.remove('anterior_paso_paso_1', 'activo_paso_paso_1');
                el.classList.add('posterior_paso_paso_1');
            }
        });
    });

});


// document.querySelector('.anterior_paso_paso_1').style.right = `${containerWidth-600}px`;

function desplegablePasoSinJuntas(numero_big, numero_small) {
    const containerElement = document.querySelector('.acordeon');
    if (window.innerWidth > 992) {
        if (containerElement) {
            var containerWidth = containerElement.getBoundingClientRect().width;
            var currentWidth = containerWidth - numero_big;
            document.querySelector('.activo_paso_paso_1').style.right = `${currentWidth}px`;
            document.querySelector('.activo_paso_paso_1').style.left = 'auto';
            document.querySelector('.activo_paso_paso_1').style.transition = '1s';

            numero_big = 580;
            const elements_anterior_paso_paso_1 = document.querySelectorAll('.anterior_paso_paso_1');
            elements_anterior_paso_paso_1.forEach((elemento, index) => {
                var currentWidth = containerWidth - numero_big - (index * 80);
                elemento.style.right = `${currentWidth}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            });

            numero_big = -500;
            const elements_posterior_paso_paso_1 = document.querySelectorAll('.posterior_paso_paso_1');
            for (let index = elements_posterior_paso_paso_1.length - 1; index >= 0; index--) {
                const elemento = elements_posterior_paso_paso_1[index];
                var currentWidth = numero_big + ((elements_posterior_paso_paso_1.length - 1 - index) * 80);
                elemento.style.right = `${currentWidth}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            }


            console.log(`El ancho del contenedor es: ${containerWidth}px`);
        } else {
            console.error('No se encontró el contenedor con la clase .acordeon');
        }
    }else{
        if (containerElement) {
            var containerHeight = containerElement.getBoundingClientRect().height;
            console.log(containerHeight)

            numero_in = 0;
            var currentHeigh = 0;
            const elements_anterior_paso_paso_1 = document.querySelectorAll('.anterior_paso_paso_1');
            elements_anterior_paso_paso_1.forEach((elemento, index) => {
                currentHeight = numero_in + (index * 70);
                elemento.style.top = `${currentHeight}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            });

            if (numero_small == 400){
                var num_sumar = 0;
                var currentHeight = 0;
            }else{
                var num_sumar = 70;
            }
            document.querySelector('.activo_paso_paso_1').style.top = `${currentHeight+num_sumar}px`;
            document.querySelector('.activo_paso_paso_1').style.left = 'auto';
            document.querySelector('.activo_paso_paso_1').style.transition = '1s';

            // numero_small = 470;
            const elements_posterior_paso_paso_1 = document.querySelectorAll('.posterior_paso_paso_1');
            elements_posterior_paso_paso_1.forEach((elemento, index) => {
                var currentHeight = numero_small + (index * 70);
                elemento.style.top = `${currentHeight}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            });


            console.log(`El ancho del contenedor es: ${containerHeight}px`);
        } else {
            console.error('No se encontró el contenedor con la clase .acordeon');
        }
    }
}


document.querySelectorAll('.acordeon-header-paso-paso-2').forEach(header => {

    header.addEventListener('click', () => {
        const item = header.parentElement; 
        const accordion = item.parentElement;
        const items = Array.from(accordion.children);
        const index = items.indexOf(item);

        items.forEach((el, i) => {
            if (i < index) {
                el.classList.remove('posterior_paso_paso_2', 'activo_paso_paso_2');
                el.classList.add('anterior_paso_paso_2');
            } else if (i === index) {
                el.classList.remove('anterior_paso_paso_2', 'posterior_paso_paso_2');
                el.classList.add('activo_paso_paso_2');
            } else {
                el.classList.remove('anterior_paso_paso_2', 'activo_paso_paso_2');
                el.classList.add('posterior_paso_paso_2');
            }
        });
    });

});


// document.querySelector('.anterior_paso_paso_1').style.right = `${containerWidth-600}px`;

function desplegablePasoConJuntas(numero_big, numero_small) {
    const containerElement = document.querySelector('.acordeon');
    if (window.innerWidth > 992) {
        if (containerElement) {
            var containerWidth = containerElement.getBoundingClientRect().width;
            var currentWidth = containerWidth - numero_big;
            document.querySelector('.activo_paso_paso_2').style.right = `${currentWidth}px`;
            document.querySelector('.activo_paso_paso_2').style.left = 'auto';
            document.querySelector('.activo_paso_paso_2').style.transition = '1s';

            numero_big = 580;
            const elements_anterior_paso_paso_2 = document.querySelectorAll('.anterior_paso_paso_2');
            elements_anterior_paso_paso_2.forEach((elemento, index) => {
                var currentWidth = containerWidth - numero_big - (index * 80);
                elemento.style.right = `${currentWidth}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            });

            numero_big = -500;
            const elements_posterior_paso_paso_2 = document.querySelectorAll('.posterior_paso_paso_2');
            for (let index = elements_posterior_paso_paso_2.length - 1; index >= 0; index--) {
                const elemento = elements_posterior_paso_paso_2[index];
                var currentWidth = numero_big + ((elements_posterior_paso_paso_2.length - 1 - index) * 80);
                elemento.style.right = `${currentWidth}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            }


            console.log(`El ancho del contenedor es: ${containerWidth}px`);
        } else {
            console.error('No se encontró el contenedor con la clase .acordeon');
        }
    }else{
        if (containerElement) {
            var containerHeight = containerElement.getBoundingClientRect().height;
            console.log(containerHeight)

            numero_in = 0;
            var currentHeigh = 0; 
            const elements_anterior_paso_paso_2 = document.querySelectorAll('.anterior_paso_paso_2');
            elements_anterior_paso_paso_2.forEach((elemento, index) => {
                currentHeight = numero_in + (index * 70);
                elemento.style.top = `${currentHeight}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            });

            if (numero_small == 400){
                var num_sumar = 0;
                var currentHeight = 0;
            }else{
                var num_sumar = 70;
            }
            document.querySelector('.activo_paso_paso_2').style.top = `${currentHeight+num_sumar}px`;
            document.querySelector('.activo_paso_paso_2').style.left = 'auto';
            document.querySelector('.activo_paso_paso_2').style.transition = '1s';

            // numero_small = 470;
            const elements_posterior_paso_paso_2 = document.querySelectorAll('.posterior_paso_paso_2');
            elements_posterior_paso_paso_2.forEach((elemento, index) => {
                var currentHeight = numero_small + (index * 70);
                elemento.style.top = `${currentHeight}px`;
                elemento.style.left = 'auto';
                elemento.style.transition = '1s';
            });


            console.log(`El ancho del contenedor es: ${containerHeight}px`);
        } else {
            console.error('No se encontró el contenedor con la clase .acordeon');
        }
    }
}



</script>

<script>
    function checkScreenSize() {
        if (window.innerWidth < 992) {
            console.log("holaa")
            numero = 330;
            const elements_anterior_paso_paso_1 = document.querySelectorAll('.posterior_paso_paso_1');
            elements_anterior_paso_paso_1.forEach((elemento, index) => {
                numero = numero + 70;
                elemento.style.right = '0px';
                elemento.style.left = 'auto';
                elemento.style.top = `${numero}px`;
                elemento.style.transition = '1s';
            });

            numero = 330;
            const elements_anterior_paso_paso_2 = document.querySelectorAll('.posterior_paso_paso_2');
            elements_anterior_paso_paso_2.forEach((elemento, index) => {
                numero = numero + 70;
                elemento.style.right = '0px';
                elemento.style.left = 'auto';
                elemento.style.top = `${numero}px`;
                elemento.style.transition = '1s';
            });
            // numero = -500;
            // const elements_posterior_paso_paso_1 = document.querySelectorAll('.posterior_paso_paso_1');
            // for (let index = elements_posterior_paso_paso_1.length - 1; index >= 0; index--) {
            //     const elemento = elements_posterior_paso_paso_1[index];
            //     var currentWidth = numero + ((elements_posterior_paso_paso_1.length - 1 - index) * 80);
            //     elemento.style.right = 'none';
            //     elemento.style.left = 'auto';
            //     elemento.style.transition = '1s';
            // }
        }
    }

    window.onload = checkScreenSize;

    window.onresize = checkScreenSize;
</script>

<!-- 

        <div class="acordeon ocultar" id="paso_paso_microcemento_2">
            <div class="acordeon-item open">
                <div class="acordeon-header acordeon-header-paso-paso-1 open" id="pregunta11">
                    <button class="acordeon-button" type="button">
                        <img src="../assets/img/1_step_by_step_tiles.png" alt="">
                    </button>
                </div>
                <div id="respuesta11" class="acordeon-collapse show" aria-labelledby="pregunta11" data-bs-parent="#paso_paso_microcemento_2">
                    <div class="acordeon-body">
                        <h4>LIMPIEZA Y PREPARACIÓN DEL SOPORTE</h4>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/paso-paso/limpiar.png"
                                alt="paso a paso aplicación"> Limpia y seca la superficie a recubrir para dejarla libre de polvo y grasa.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_cinta.png"
                                alt="paso a paso aplicaci?n"> Coloca cinta adhesiva para delimitar la superficie donde vas a aplicar.</p>
                    </div>
                </div>
            </div>

            <div class="acordeon-item">
                <div class="acordeon-header acordeon-header-paso-paso-1" id="pregunta22">
                    <button class="acordeon-button" type="button" data-bs-target="#respuesta22" aria-expanded="false" aria-controls="respuesta22">
                        <img src="../assets/img/2_step_by_step_tiles.png" alt="">
                    </button>
                </div>
                <div id="respuesta22"  aria-labelledby="pregunta22" data-bs-parent="#paso_paso_microcemento_2" style="">
                    <div class="acordeon-body">
                        <h4>RELLENAR JUNTAS CON SMART JOINTER</h4>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/paso-paso/llana_03_icon.png" alt="crono_icon"> Rellena con espátula o llana las juntas con 1 capa de Smart Jointer.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_time.png"
                                alt="crono_icon">Deja 24 horas de secado.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_sand.png"
                                alt="lija_icon">Lija la superficie con una lija grano 40.</p>
                    </div>
                </div>
            </div>

            <div class="acordeon-item">
                <div class="acordeon-header acordeon-header-paso-paso-1" id="pregunta33">
                    <button class="acordeon-button" type="button" data-bs-target="#respuesta33" aria-expanded="false" aria-controls="respuesta33">
                        <img src="../assets/img/3_step_by_step_tiles.png" alt="">
                    </button>
                </div>
                <div id="respuesta33"  aria-labelledby="pregunta33" data-bs-parent="#paso_paso_microcemento_2" style="">
                    <div class="acordeon-body">
                        <h4>APLICACIÓN DE SMART PRIMER GRIP</h4>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/iconos/icono_step_by_step_stir.png" alt="agitar_icon">Remueve el producto de manera
                            manual con espátula.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/paso-paso/verter_icon.png" alt="agitar_icon"> Vierte el producto en una
                            bandeja.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/iconos/icono_step_by_step_trowel.png" alt="paso a paso aplicación"> Aplica con rodillo
                            1 capa de Smart Primer GRIP.</p>
                        <p class="expl"><img loading="lazy" width="25" height="25" src="../assets/img/iconos/icono_step_by_step_time.png"
                                alt="tiempo"> Espera 2-4 horas para su secado.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/paso-paso/clean_icon.png"
                                alt="tiempo"> Limpia el rodillo con agua inmediatamente después de su uso para alargar su
                            vida útil.</p>
                    </div>
                </div>
            </div>

            <div class="acordeon-item">
                <div class="acordeon-header acordeon-header-paso-paso-1" id="pregunta44">
                    <button class="acordeon-button" type="button" data-bs-target="#respuesta44" aria-expanded="false" aria-controls="respuesta44">
                        <img src="../assets/img/4_step_by_step_tiles.png" alt="">
                    </button>
                </div>
                <div id="respuesta44"  aria-labelledby="pregunta44" data-bs-parent="#paso_paso_microcemento_2">
                    <div class="acordeon-body">
                        <h4>APLICACIÓN DE SMART BASE</h4>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/iconos/icono_step_by_step_stir.png" alt="agitar_icon"> Remueve el producto de manera manual con espátula.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/iconos/icono_step_by_step_trowel.png" alt="paso a paso aplicación"> Moja el Smart Roller en el Smart Base y extiende el microcemento en capa fina. Alisa con la llana.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_cinta.png"
                                alt="paso a paso aplicación"> Retira y aplica la cinta adhesiva entre capas.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_time.png"
                                alt="crono_icon"> Deja 6 horas de secado entre capa y capa.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_sand.png"
                                alt="lija_icon"> Lija después de cada capa con una lija grano 40.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/paso-paso/llana_03_icon.png"
                                alt="Aplica dos capas de Smart Base en total."> Aplica dos capas de Smart Base en total.</p>
                    </div>
                </div>
            </div>
            <div class="acordeon-item">
                <div class="acordeon-header acordeon-header-paso-paso-1" id="pregunta55">
                    <button class="acordeon-button" type="button" data-bs-target="#respuesta55" aria-expanded="false" aria-controls="respuesta55">
                        <img src="../assets/img/5_step_by_step_tiles.png" alt="">
                    </button>
                </div>
                <div id="respuesta55"  aria-labelledby="pregunta55" data-bs-parent="#paso_paso_microcemento_2">
                    <div class="acordeon-body">
                        <h4>APLICACIÓN DE SMART LISO</h4>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/iconos/icono_step_by_step_stir.png" alt="agitar_icon"> Remueve el producto de manera manual con espátula y coloca de nuevo cinta adhesiva.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/iconos/icono_step_by_step_trowel.png" alt="paso a paso aplicación"> Haz el mismo proceso con el Smart Liso. Usa el Smart Roller y la llana y extiende el microcemento en capa fina.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_cinta.png"
                                alt="paso a paso aplicación"> Retira la cinta adhesiva.</p>
                        <p class="expl"><img loading="lazy" width="25" height="25" src="../assets/img/iconos/icono_step_by_step_time.png"
                                alt="crono_icon"> Deja 6 horas de secado entre capa y capa.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_sand.png"
                                alt="lija_icon"> Lija después de cada capa con una lija grano 220.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/paso-paso/llana_03_icon.png"
                                alt="Aplica dos capas de Smart Base en total. "> Aplica 2 capas de Smart Liso en total.</p>
                    </div>
                </div>
            </div>

            <div class="acordeon-item">
                <div class="acordeon-header acordeon-header-paso-paso-1" id="pregunta66">
                    <button class="acordeon-button" type="button" data-bs-target="#respuesta66" aria-expanded="false" aria-controls="respuesta66">
                        <img src="../assets/img/6_step_by_step_tiles.png" alt="">
                    </button>
                </div>
                <div id="respuesta66"  aria-labelledby="pregunta66" data-bs-parent="#paso_paso_microcemento_2">
                    <div class="acordeon-body">
                        <h4>PROTECCIÓN CON SMART VARNISH</h4>
                        <p class="expl"><img loading="lazy" width="25" height="25" src="../assets/img/iconos/icono_step_by_step_time.png"
                                alt="crono_icon"> Deja 24 horas de espera desde la aplicación de Smart Liso. </p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_cinta.png"
                                alt="paso a paso aplicación">Coloca de nuevo cinta adhesiva para delimitar la superficie donde se va a aplicar.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31"
                                src="../assets/img/iconos/icono_step_by_step_trowel.png" alt="paso a paso aplicación"> Aplica en un día con rodillo 3 capas de Smart Varnish. En áreas húmedas como baños y áreas de alto tránsito, aplica 4 capas para una mayor protección.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_cinta.png"
                                alt="crono_icon"> Retira la cinta adhesiva.</p>
                        <p class="expl"><img loading="lazy" width="25" height="25" src="../assets/img/iconos/icono_step_by_step_time.png"
                                alt="crono_icon"> Deja un tiempo de 4 horas de secado entre capa y capa.</p>
                        <p class="expl"><img loading="lazy" width="31" height="31" src="../assets/img/iconos/icono_step_by_step_sand.png"
                                alt="lija_icon"> Recomendamos lijar la primera y la segunda capa con una lija grano 400.</p>
                        <p class="expl">*Importante: aconsejamos esperar 7 días para que el barniz logre sus máximas resistencias.</p>
                    </div>
                </div>
            </div>
        </div> -->

        


        <script>


function cerrar_color(){
    var elementos = document.querySelectorAll('.contenedor_txt_gamma_colores');

    elementos.forEach(elemento => {
        elemento.style.display = "flex";
    });

    var elementos = document.querySelectorAll('.contenedor_imagen_grande_color_gammas');

    elementos.forEach(elemento => {
        elemento.style.display = "none";
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn_color');
    let activeContainer = null;

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // if (window.innerWidth < 992) {
            //     document.querySelector('.contenedor_colores_gammas').classList.remove('colores_gammas_toogle');
            // }


            
            const colorValue = this.getAttribute('data-color');
            const container = document.querySelector(`.contenedor_imagen_color_gammas[data-color="${colorValue}"]`);
            const image = container.querySelector('.imagen_color_gammas');

            if (activeContainer && activeContainer !== container) {
                activeContainer.classList.remove('contenedor_imagen_grande_color_gammas');
                activeContainer.querySelector('.imagen_color_gammas').classList.remove('imagen_grande_gamma_colores');
                if (window.innerWidth > 768) {
        
                    document.querySelectorAll('.contenedor_imagen_color_gammas').forEach(cont => {
                        if (cont !== activeContainer) cont.style.display = 'block';
                    });
                }
            }

            container.classList.add('contenedor_imagen_grande_color_gammas');
            image.classList.add('imagen_grande_gamma_colores');

            if (window.innerWidth < 768) {
                var elementos = document.querySelectorAll('.contenedor_txt_gamma_colores');

                elementos.forEach(elemento => {
                    elemento.style.display = "none";
                });

                var elementos = document.querySelectorAll('.contenedor_imagen_grande_color_gammas');

                elementos.forEach(elemento => {
                    elemento.style.display = "block";
                });
            }


            if (container.classList.contains('contenedor_imagen_grande_color_gammas')) {
                document.querySelectorAll('.contenedor_imagen_color_gammas').forEach(cont => {
                    if (cont !== container) cont.style.display = 'none';
                });
                activeContainer = container;

            } else {
                document.querySelectorAll('.contenedor_imagen_color_gammas').forEach(cont => cont.style.display = 'block');
                activeContainer = null;

            }
        });
    });

    const tabs = document.querySelectorAll('.contenedor_listado_colores li');
    const contentDivs = document.querySelectorAll('.contenedores_listado_colores > div');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const gama = this.getAttribute('data-gama');

            tabs.forEach(t => t.classList.remove('active'));
            contentDivs.forEach(div => div.style.display = 'none');

            if (window.innerWidth < 768) {
                var elementos = document.querySelectorAll('.contenedor_txt_gamma_colores');

                elementos.forEach(elemento => {
                    elemento.style.display = "flex";
                });

                var elementos = document.querySelectorAll('.contenedor_imagen_grande_color_gammas');

                elementos.forEach(elemento => {
                    elemento.style.display = "none";
                });
            }

            this.classList.add('active');
            if (window.innerWidth < 768) {
                document.querySelector(`.${gama}`).style.display = 'block';
            }

            if (activeContainer) {
                activeContainer.classList.remove('contenedor_imagen_grande_color_gammas');
                activeContainer.querySelector('.imagen_color_gammas').classList.remove('imagen_grande_gamma_colores');
                activeContainer = null;
                if (window.innerWidth > 768) {
                    document.querySelectorAll('.contenedor_imagen_color_gammas').forEach(cont => cont.style.display = 'block');
                }
            }
        });
    });

    document.querySelector('.contenedor_listado_colores li[data-gama="gama_a"]').click();
});


document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.contenedor_listado_colores li');
    const contents = document.querySelectorAll('.contenedores_listado_colores div');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const gama = this.getAttribute('data-gama');

            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            contents.forEach(content => content.classList.remove('active'));
            document.querySelector(`.${gama}`).classList.add('active');
        });
    });

    document.querySelector('.contenedor_listado_colores li[data-gama="gama_a"]').click();
});

        </script>

        <?php include('../includes/footer.php'); ?>
    </body>
    <?php echo $contenido[3][4] ?>

    <script>
let currentIndex = 0;
const images = document.querySelectorAll('.imagen_contenido_pinturas_opciones');
const totalImages = images.length;

document.querySelector('.prev').addEventListener('click', () => {
    images[currentIndex].classList.remove('active');
    if (currentIndex > 0) {
        currentIndex--;
    } else {
        currentIndex = totalImages - 1;
    }
    images[currentIndex].classList.add('active');
    updateText(images[currentIndex].dataset.section);
});

document.querySelector('.next').addEventListener('click', () => {
    images[currentIndex].classList.remove('active');
    if (currentIndex < totalImages - 1) {
        currentIndex++;
    } else {
        currentIndex = 0;
    }
    images[currentIndex].classList.add('active');
    updateText(images[currentIndex].dataset.section);
});

document.querySelectorAll('#pinturas-opciones li').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        const targetSection = this.getAttribute('data-target');
        const targetIndex = Array.from(images).findIndex(img => img.dataset.section === targetSection);
        if (targetIndex !== -1) {
            images[currentIndex].classList.remove('active');
            currentIndex = targetIndex;
            images[currentIndex].classList.add('active');
            updateText(images[currentIndex].dataset.section);
        }
    });
});

function updateText(section) {
    const titleElement = document.getElementById('section-title');
    const descriptionElement = document.getElementById('section-description');

    titleElement.innerText = titles[section];
    descriptionElement.innerHTML = descriptions[section].map(text => `<p>${text}</p>`).join('');
}

images[currentIndex].classList.add('active');
updateText(images[currentIndex].dataset.section);


    </script>

<?php include('../includes/esquemas_webpage.php'); ?>
</html>