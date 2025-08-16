
<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');

include ('../config/db_connect.php');
include ('../class/userClass.php');
$userClass = new userClass();
$url_metas=$userClass->url_metas($id_url,$id_idioma);

include('../includes/vocabulario.php');
include('../includes/urls.php');


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
    <link rel="stylesheet" type="text/css" href="../assets/css/pinturas.css">

    <body class="microcemento_listo_uso">
        <?php include('../includes/header.php'); ?>

        <div class="contenedor_inicial_microcemento">
            <div class="contenedor_texto_inicial_microcemento">
                <?php echo $contenido[1][1]; ?>
            </div>
            <div class="contenedor_imagen_inicial_microcemento">
                <img src="../assets/img/chica_rodillo.webp" alt="Image">
            </div>
            <div class="contenedor_imagen_secundaria_microcemento">
                <img src="../assets/img/pinturas.webp" alt="Image">
            </div>
            <div class="contenedor_imagen_fondo_microcemento">
                <img src="../assets/img/fondo_pinturas_version2.webp" alt="Image">
            </div>
        </div>

        <div class="contenedor_secundario_pinturas_azulejos">
            <div class="contenedor_texto_secundario_pinturas_azulejos">
                <?php echo $contenido[2][1]; ?>

            </div>
            <div class="contenedor_imagen_secundario_pinturas_azulejos">
                <img src="../assets/img/llana_peque_a_u1596.webp" alt="Image" style="transform: translateX(-50px);">
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

        <div class="contenedor_video_pinturas_azulejos">
            <div >
                <img class="img_tv_pinturas_azulejos" src="../assets/img/mueble_amarillo_tv.webp" alt="Mueble amarillo con tv sobre el que se apoya la TV">
            </div>
            <img class="img_pareja_viendo" src="../assets/img/pareja_viendo_tv.webp" alt="Image" >
            <img class="plantas_cuadros" src="../assets/img/plantas_cuadros.png" alt="Image">
            <iframe width="704" height="348" src="https://www.youtube.com/embed/AprjRG-5Slw" title="🔥 ¡TRANSFORMA tu BAÑO! Descubre cómo cambiar el aspecto sin cambiar los azulejos con MICROCEMENTO 👌" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
        
        <div class="contendor_pinturas_opciones_aplicar">
            <div class="contenedor_opciones_pinturas_opciones_aplicar">
                <?php echo $contenido[3][1]; ?>
            </div>

            <div class="contenedor_contenido_pinturas_opciones" style="display: flex;">
                <div class="contenedor_texto_pinturas_opciones">
                    <h3 id="section-title"></h3>
                    <div id="section-description">
                    </div>
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
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/<?php echo $color->valor; ?>.webp" src="../assets/img/colores/<?php echo $color->valor; ?>.webp" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
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
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/<?php echo $color->valor; ?>.webp" src="../assets/img/colores/<?php echo $color->valor; ?>.webp" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
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
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/<?php echo $color->valor; ?>.webp" src="../assets/img/colores/<?php echo $color->valor; ?>.webp" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
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
                                        <img class="imagen_color_gammas<?php echo $claseImagenAdicional; ?>" src-zoom="../assets/img/colores/<?php echo $color->valor; ?>.webp" src="../assets/img/colores/<?php echo $color->valor; ?>.webp" alt="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>" title="<?php echo $vocabulario_ellegance_colletion ?> <?php echo $color->valor; ?>">
                                        <i id="close-color" onclick="cerrar_color()" class="fa fa-times"></i>
                                    </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contenedor_fondo_colores"></div>
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

    <?php echo $contenido[7][1] ?>

    <?php echo $contenido[7][2] ?>    

</div>

<div class="contenedor_pinturas_tambien_interesa">
    <div class="contenedor_pinturas_tambien_interesa_txt">
        <?php echo $contenido[8][1] ?>    
    </div>
    <div class="contenedor_pinturas_tambien_interesa_btns">
        <a href="./<?php echo $link_tienda ?>#pinturas"><?php echo $vocabulario_quiero_mi_pintura_azulejos ?></a>
    </div>        
</div>

<div class="contenedor_microcemento_preguntas_y_respuestas">
    <?php echo $contenido[9][1] ?>    
</div>


<script>
$("#preguntaSelect").on( "change", function() {
    $("#respuestas").css('padding', '20px');
} );
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
        }
    }

    window.onload = checkScreenSize;

    window.onresize = checkScreenSize;
</script>

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