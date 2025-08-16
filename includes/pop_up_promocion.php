<?php

if( !function_exists('popup_promocion_activo') ) {

    global $mysqli;
    $mysqli=$conn;

    function popup_promocion_activo () {
        global $mysqli;
        $sql = "SELECT activado FROM configuracion WHERE id=5";
        return ( $mysqli->query($sql)->fetch_object()->activado == 1) ? true : false;
    }

}



if ( popup_promocion_activo () ) {

$lang = obten_idioma_actual();

$urls_activo['es'] = array (
                        '',
                        '/',
                        '/blog',
                        '/microcemento-listo-al-uso',
                        '/pintura-azulejos-smartcover',
                        '/hormigon-impreso',
                        '/smartcret/',
                        '/smartcret/blog/',
                        '/smartcret/microcemento-listo-al-uso',
                        '/smartcret/pintura-azulejos-smartcover',
                        '/smartcret/hormigon-impreso',
                     );
$urls_activo['en'] = array (
                        '',
                        '/en',
                        '/en/',
                        '/en/blog',
                        '/en/smartcret-ready-to-use-microcement',
                        '/en/tile-paint-smartcover',
                        '/smartcret/en',
                        '/smartcret/en/',
                        '/smartcret/en/blog/',
                        '/smartcret/en/smartcret-ready-to-use-microcement',
                        '/smartcret/en/tile-paint-smartcover',
                     );
$urls_activo['fr'] = array (
                        '',
                        '/fr',
                        '/fr/',
                        '/fr/blog',
                        '/fr/beton-cire-pret-a-lemploi',
                        '/fr/peinture-carrelages-smartcover',
                        '/smartcret/fr',
                        '/smartcret/fr/',
                        '/smartcret/fr/blog/',
                        '/smartcret/fr/beton-cire-pret-a-lemploi',
                        '/smartcret/fr/peinture-carrelages-smartcover',
                     );
$urls_activo['it'] = array (
                        '',
                        '/it',
                        '/it/',
                        '/it/blog',
                        '/it/microcemento-pronto-all-uso-smartcret',
                        '/it/vernice-piastrella-smartcover',
                        '/smartcret/it',
                        '/smartcret/it/',
                        '/smartcret/it/blog/',
                        '/smartcret/it/microcemento-pronto-all-uso-smartcret',
                        '/smartcret/it/vernice-piastrella-smartcover',
                     );
$urls_activo['de'] = array (
                        '',
                        '/de/',
                        '/de',
                        '/de/blog',
                        '/de/gebrauchsfertiger-mikrozement',
                        '/de/farbe-fliesen-smartcover',
                        '/de/vertreiber',
                        '/smartcret/de',
                        '/smartcret/de/',
                        '/smartcret/de/blog',
                        '/smartcret/de/gebrauchsfertiger-mikrozement',
                        '/smartcret/de/farbe-fliesen-smartcover',
                        '/smartcret/de/vertreiber'
                     );


$dia_semana = date('w');
// dom, lun, mar, mie, jue, vie, sab
$descuentos = array();
/*                        D     L     M     X     J     V     S    */
$descuentos['es'] = array( '', '', '', '40', '40', '', '' );
$descuentos['en'] = array( '', '', '', '40', '40', '', '' );
$descuentos['fr'] = array( '', '', '', '40', '40', '', '' );
$descuentos['it'] = array( '', '', '', '40', '40', '', '' );

//  X  J  V  S  D  L  M  X  J  V  S  D
//

if ( ( in_array( $_SERVER['REQUEST_URI'], $urls_activo[ $lang ] ) ||
       strpos( $_SERVER['REQUEST_URI'], '/smartcret/blog/') !== false || strpos( $_SERVER['REQUEST_URI'], '/blog/' ) !== false ) && !isset( $_SESSION['popup_promocion_mostrado'] ) && isset( $descuentos[ $lang ]) ) {

$path = ( strpos( $_SERVER['REQUEST_URI'], '/blog' ) !== false) ? '..' : '';

$_SESSION['popup_promocion_mostrado'] = 1;

$texto = array();
$urls_activo = array();


/* Ingles */
$texto['titulo']['en'] = '⚡ FLASH discount!';
$texto['entrada']['en'] = $descuentos['en'][$dia_semana] . '% discount on all our microcement KITS until 00:00h';
$texto['cupon_txt']['en'] = 'Coupon: ';
$texto['comprar']['en'] = 'Buy!';
$texto['cupon']['en'] = 'FLASH' . $descuentos['en'][$dia_semana];
$texto['url']['en'] = 'microcement-store';

/* español */
$texto['titulo']['es'] = '⚡ Descuento FLASH!';
$texto['entrada']['es'] = $descuentos['es'][$dia_semana] . '% de descuento en todos nuestros KITS de microcemento hasta las 00:00h';
$texto['cupon_txt']['es'] = 'Cupón: ';
$texto['comprar']['es'] = '¡Comprar!';
$texto['cupon']['es'] = 'FLASH' . $descuentos['es'][$dia_semana];
$texto['url']['es'] = 'tienda-microcemento';

/* francés */
$texto['titulo']['fr'] = '⚡ Remise FLASH!';
$texto['entrada']['fr'] = $descuentos['fr'][$dia_semana] . '% de réduction sur tous nos KITS de microcemento jusqu\'à 00:00h';
$texto['cupon_txt']['fr'] = 'Coupon: ';
$texto['comprar']['fr'] = 'Acheter!';
$texto['cupon']['fr'] = 'FLASH' . $descuentos['fr'][$dia_semana];
$texto['url']['fr'] = 'boutique-beton-cire';

/* italiano */
$texto['titulo']['it'] = '⚡ Sconto FLASH!';
$texto['entrada']['it'] = $descuentos['it'][$dia_semana] . '% di sconto su tutti i nostri KIT di microcemento fino alle ore 00:00.';
$texto['cupon_txt']['it'] = 'Coupon: ';
$texto['comprar']['it'] = 'Acquista!';
$texto['cupon']['it'] = 'FLASH' . $descuentos['it'][$dia_semana];
$texto['url']['it'] = 'negozio-microcemento';

/* aleman */
$texto['titulo']['de'] = '⚡ FLASH Rabatt!';
$texto['entrada']['de'] = $descuentos['de'][$dia_semana] . '% Rabatt auf alle unsere Mikrozement-KITS bis 00:00 Uhr.';
$texto['cupon_txt']['de'] = 'Gutschein: ';
$texto['comprar']['de'] = 'Kaufen!';
$texto['cupon']['de'] = 'FLASH' . $descuentos['de'][$dia_semana];
$texto['url']['de'] = 'geschaft-mikrozement';

?>

<div class="promo_box">

    <div class="promo_content">
        <div class="close_promo"><i class="fa fa-times" onclick="$('.promo_box').remove()"></i></div>
        <div class="row">

            <div class="col-md-7">
                <p class="prom_tit"><?php echo $texto['titulo'][$lang]?></p>
                <p class="txt"><?php echo $texto['entrada'][$lang]?></p>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <div class="cont">
                    <p><span id='cuenta_prom'></span></p>
                    <p class="txt2"><?php echo $texto['cupon_txt'][$lang]?><span class="extra"><?php echo $texto['cupon'][$lang] ?></span></p>
                </div>
            </div>
            <div class="col-md-12">
                <a href="<?php echo $texto['url'][$lang] ?>" class="prom_compra"><?php echo $texto['comprar'][$lang] ?></a>
            </div>

        </div>
    </div>

</div>

<?php

if ( $contador ) { ?>

        <script>

        var end = new Date('11/<?php echo date('d')+1 ?>/2023 00:00 AM');
        // var end = new Date('11/01/2023 00:00 AM');
        var _second = 1000;
        var _minute = _second * 60;
        var _hour = _minute * 60;
        var _day = _hour * 24;
        var timer;
        function showRemaining() {
           var now = new Date();
           var distance = end - now;
           if (distance < 0) {
              clearInterval(timer);
              document.getElementById('cuenta_prom').innerHTML = '';
              return;
           }
        var days = Math.floor(distance / _day);
        var hours = Math.floor((distance % _day) / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        if (minutes < 10 ) minutes = '0' + minutes;
        var seconds = Math.floor((distance % _minute) / _second);
        if (seconds < 10 ) seconds = '0' + seconds;
        document.getElementById('cuenta_prom').innerHTML = hours + 'h ' + minutes + 'm ' + seconds+'s';
        }
        timer = setInterval(showRemaining, 1000);

        setTimeout(function() {$('.promo_box').fadeIn(100);}, 1000);
        </script>

    <?php }

    ?>


<script>

    function esLocalhost() {
        var url = window.location.href;
        var idx = url.indexOf("localhost");
        return (idx != -1) ? true : false;
    }

</script>


<?php

    echo '
    <style>
.promo_box {
    display:none;
}
.promo_box, .promo_content {
    background-color: #C93285!important;
    position: fixed;
    bottom: 0px;
    width: 100%;
    padding: 60px 0;
}
.promo_content .row {
    background-color: #c93285;
}
.promo_box {
    color: #fff!important;
}
.promo_content .txt {
    font-size: 40px;
    line-height: 53px;
    font-weight: 500;
}
p.prom_tit {
    font-size: 54px;
    margin-bottom: 20px;
}
a.prom_compra {
    margin-top: 40px;
    display: inline-block;
    color: #c93285;
    text-decoration: none;
    background-color: #fff;
    padding: 10px 40px;
    font-size: 35px;
    font-weight: 600;
    border: 2px solid #fff;
}
a.prom_compra:hover {
    color: #fff;
    background-color: #c93285;
    cursor:pointer;
}
.promo_content .txt2 {
    font-size: 30px;
}
span.extra {
    font-size: 60px;
    font-weight: 500;
}
#cuenta_prom {
    font-size: 53px;
    font-weight: 500;
    margin-top: -15px;
    margin-bottom: 49px;
    display: inline-block;
    background-color: #fff;
    color: #c93285;
    padding: 40px;
}
.close_promo {
    position: absolute;
    right: 25px;
    top: 15px;
    font-size: 30px;
}
.close_promo:hover {
    color:#000;
    cursor:pointer;
}

        @media only screen and (max-device-width:769px) {

            p.prom_tit {
                font-size: 30px;
                margin-bottom: 20px;
            }
            .promo_content .txt {
                font-size: 26px;
                line-height: 32px;
                font-weight: 500;
            }
            a.prom_compra {
                margin-top: 40px;
                margin-bottom: 40px;
                padding: 6px 33px;
                font-size: 28px;
            }
            #cuenta_prom {
                text-align: center;
                font-size: 3rem;
                font-weight: 500;
                margin-top: 35px;
                margin-bottom: 47px;
                display: inline-block;
                background-color: #fff;
                color: #c93285;
                padding: 30px;
                width: 97%;
            }
            span.extra {
                font-size: 40px;
                font-weight: 500;
            }
            .promo_content .txt2 {
                font-size: 26px;
            }
            p.prom_tit {
                font-size: 34px;
                margin-bottom: 20px;
                font-weight: 500;
            }
            .row.rev {
                display: flex !important;
                flex-flow: wrap!important;
                flex-direction: column-reverse!important;
            }
            .promo_box, .promo_content {
                padding: 36px 0;
            }

        }

    </style>
    ';
}

// unset( $_SESSION['popup_promocion_mostrado'] );

}

?>
