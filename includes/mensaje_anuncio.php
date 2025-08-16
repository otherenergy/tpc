<?php

$contador = true;

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$conn->set_charset("utf8");

$sql = "SELECT * FROM avisos_web WHERE activo=1 AND NOW() BETWEEN fecha_inicio and fecha_fin;";

// $conn->set_charset( "utf8mb4" );
$res=$conn->query($sql);
$reg=$res->fetch_object();

$lang = obten_idioma_actual();

$idioma_url = $_SESSION['idioma_url'];

$texto = array();

$texto['url']['es'] = 'ofertas-smartcret';
$texto['url']['en-us'] = 'smartcret-offers';
$texto['url']['fr'] = 'offres-smartcret';
$texto['url']['it'] = 'offerte-smartcret';
$texto['url']['en'] = 'smartcret-offers';
$texto['url']['de'] = 'smartcret-angebote';

// $texto['url']['es'] = 'tienda-microcemento';
// $texto['url']['en-us'] = 'microcement-store';
// $texto['url']['fr'] = 'boutique-beton-cire';
// $texto['url']['it'] = 'negozio-microcemento';
// $texto['url']['en'] = 'microcement-store';
// $texto['url']['de'] = 'shop-mikrozement';


function esLocalhost()
{
    return (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
}

$base_url = esLocalhost() ? 'https://localhost/smartcret_new/' : 'https://www.smartcret.com/';

/* construye url */

$redirect_url = $base_url . $idioma_url . '/' . (isset($texto['url'][$lang]) ? $texto['url'][$lang] : 'tienda-microcemento');

$redirect_url = $base_url . $idioma_url  . '/' . (isset($texto['url'][$lang]) ? $texto['url'][$lang] : 'tienda-microcemento');


if ( $reg )  {

    /* fechas formato dd-mm-YYYY (2022-11-25) */

    $fecha_inicio = strtotime( $reg->fecha_inicio );
    $fecha_fin = strtotime( $reg->fecha_fin );
    $fecha = strtotime(date("d-m-Y H:i:00",time()));

    if ( ( $fecha >= $fecha_inicio ) && ( $fecha <= $fecha_fin ) ) {

        switch ($lang) {

            case 'es':
                //
                $diferencia_horaria = ' +0';
                $msg = $reg->aviso_es;
                $dias = "días";
                $dia = "día";
                break;

            case 'en-us':
                //
                $diferencia_horaria = ' -8';
                $msg = $reg->aviso_en;
                $dias = "days";
                $dia = "day";
                break;

            case 'fr':
                //
                $diferencia_horaria = ' +0';
                $msg = $reg->aviso_fr;
                $dias = "jours";
                $dia = "jour";
                break;

            case 'it':
                //
                $diferencia_horaria = ' +0';
                $msg = $reg->aviso_it;
                $dias = "giorni";
                $dia = "giorno";
                break;

            case 'en':
                //
                $diferencia_horaria = ' +0';
                $msg = $reg->aviso_en;
                $dias = "days";
                $dia = "day";
                break;

            case 'de':

                $diferencia_horaria = ' +0';
                $msg = $reg->aviso_de;
                $dias = "tage";
                $dia = "tag";
                break;

            default:
                break;
        }

        $date = new DateTime();
        $date->modify('+1 day');
        $fecha_fin = $date->format('m/d/Y 00:00:00');


    // $url = $_SERVER['REQUEST_URI'];

    // if ( strpos( $url, '/en' ) !== false ) {
    // 	$msg = $reg->aviso_en;
    //     $dias = "days";
    // } elseif ( strpos( $url, '/fr' ) !== false ) {
    // 	$msg = $reg->aviso_fr;
    //     $dias = "jours";
    // } elseif ( strpos( $url, '/it' ) !== false ) {
    //     $msg = $reg->aviso_it;
    //     $dias = "giorni";
    // } elseif ( strpos( $url, '/de' ) !== false ) {
    // 	$msg = $reg->aviso_de;
    //     $dias = "tage";
    // } else {
    // 	$msg = $reg->aviso_es;
    //     $dias = "días";
    // }

    if ( $msg != '' ) {

    echo '
    <style>
        a {color:#fff;}
        a:hover {color:#92bf23;}
        .anuncio {
            z-index: 9999999999999999!important;
            text-align: center;
            position: fixed;
            display: block;
            top: 0;
            right: 0;
            left: 0;
            width: 100%;
            padding: 8px;
            //background-color: #000;
            //background-color: #C93285;
            background-color: #92bf23;
            //background-color: #c50000;
            //background-color: #cf0411;
            color: #fff;
            height: 40px;
            font-size: 16px;
            font-weight: 500;
        }
        span#countdown {
            color: #000;
        }
        span#countdown {
            color: #fff;
            background-color: #C93285;
            padding: 3px 6px;
        }
        header {
            margin-top: 36px;
        }
        div#lista-productos,
        div#menu-usuario {
            top: 36px;
        }

        @media only screen and (max-width:767px) {

            .anuncio {
                height: 70px;
                font-size: 15px;
                line-height: 21px;
            }

            header {
                margin-top: 70px;
            }
            div#lista-productos,
            div#menu-usuario {
                top: 70px;
            }
            ul#icons {
                top: 89px;
            }
            span#countdown {
                color: #fff;
                background-color: #C93285;
                padding: 3px 6px;
                display: inline-block;
            }

        }
    </style>
    ';
    echo '<a href="' . $redirect_url . '" class="anuncio-link"><div class="anuncio">' . $msg . '</div></a>';
    }

    if ( $contador ) {

    ?>
        <script>
        //var end = new Date('<?php echo $fecha_fin ?>');
        // var end = new Date('7/<?php echo date('d')+1 ?>/2024 00:00 AM');
        var end = new Date('2024-10-07 10:00:00');
        var _second = 1000;
        var _minute = _second * 60;
        var _hour = _minute * 60;
        var _day = _hour * 24;
        var timer;

        function showRemaining() {
           var now = new Date();
           now.setHours(now.getHours() <?php echo $diferencia_horaria ?>);
           var distance = end - now;
           if (distance < 0) {
              clearInterval(timer);
              document.getElementById('countdown').innerHTML = '';
              return;
           }
            var days = Math.floor(distance / _day);
            var hours = Math.floor((distance % _day) / _hour);
            var minutes = Math.floor((distance % _hour) / _minute);
            if (minutes < 10 ) minutes = '0' + minutes;
            var seconds = Math.floor((distance % _minute) / _second);
            if (seconds < 10 ) seconds = '0' + seconds;

            // var horas = hours + (days * 24);
            // if (horas < 10 ) horas = '0' + horas;

            // document.getElementById('countdown').innerHTML = horas + ':' + minutes + ':' + seconds;
            // document.getElementById('countdown').innerHTML = horas + 'h ' + minutes + 'm ' + seconds+'s';

            if ( days > 1 ) {
                document.getElementById('countdown').innerHTML = days + ' <?php echo $dias ?> ' + hours + 'h ' + minutes + 'm ' + seconds+'s';
            }else if ( days == 1 ) {
                document.getElementById('countdown').innerHTML = days + ' <?php echo $dia ?> ' + hours + 'h ' + minutes + 'm ' + seconds+'s';
            }else {
                document.getElementById('countdown').innerHTML = hours + 'h ' + minutes + 'm ' + seconds+'s';
            }


        }
        timer = setInterval(showRemaining, 1000);

        </script>

    <?php }

}

}

?>

