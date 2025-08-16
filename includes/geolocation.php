<?php

$rutaServer = $_ENV['RUTA_SERVER'];

if (session_status() === PHP_SESSION_NONE){session_start();}
include_once( dirname ( __DIR__ ) . "/config/db_connect.php");
include_once( dirname ( __DIR__ ) . "/class/checkoutClass.php");
include_once( dirname ( __DIR__ ) . "/class/userClass.php");
include_once( dirname ( __DIR__ ) . "/class/vocavulario.php");

$checkout = new Checkout();
$paises = $checkout->obten_paises_activos_idioma( $id_idioma );

$array_paises = [];

foreach ($paises as $pais) {
    array_push( $array_paises, $pais->cod_pais );
}

$_SESSION['user_idioma'] = $id_idioma;

// $_SESSION['user_ubicacion_id'] = $checkout->obten_idioma_pais( $_SESSION['user_ubicacion'] )->idioma;
// var_dump($_SESSION['user_ubicacion_id']);
// echo $_SESSION['user_ubicacion'] ;
?>
<script>
    console.log("User ubicación: ")
    console.log('<?php echo $_SESSION['user_ubicacion'] ; ?>')
    console.log('<?php echo $rutaServer  ?>')
</script>

<?php

$reiniciar=0;
if (!isset($_SESSION['user_ubicacion']) && isset($_COOKIE['user_ubicacion'])){
    $reiniciar=1;
}
if (isset($_COOKIE['user_ubicacion'])) {
    $_SESSION['user_ubicacion'] = $_COOKIE['user_ubicacion'];
}

// function isGoogleBot() {
//     $userAgent = $_SERVER['HTTP_USER_AGENT'];
//     // Lista de User-Agents de Googlebot comunes
//     $bots = [
//         'Googlebot',
//         'Googlebot-Image',
//         'Googlebot-News',
//         'Googlebot-Video',
//     ];
    
//     // Verifica si el User-Agent contiene alguno de los bots
//     foreach ($bots as $bot) {
//         if (strpos($userAgent, $bot) !== false) {
//             return true;
//         }
//     }
//     return false;
// }
function isGoogleBot() {

    $ipAddressComplete = $_SERVER['HTTP_CLIENT_IP'];
    $ipAddress = @strtok($ipAddressComplete, ':');

    $ip = $ipAddress;
    $hostname = gethostbyaddr($ip);

    if ($hostname && (strpos($hostname, '.googlebot.com') !== false || strpos($hostname, '.google.com') !== false)) {
        $ip_check = gethostbyname($hostname);
        if ($ip_check === $ip) {
            return true; 
        }
    }
    return false; 
}
$isGoogleBot = isGoogleBot();

if ($isGoogleBot == false){
    $isGoogleBot= 0;
};
?>

<script>        
    console.log(<?php echo $isGoogleBot ?>)
    if (<?php echo $reiniciar?> == 1){
        location.reload();
    }
</script>

<?php

// if ( !isset($_SESSION['user_ubicacion']) ){
//if ( (!isset($_SESSION['user_ubicacion']) || !in_array($_SESSION['user_ubicacion'], $array_paises)) || !isset($_COOKIE['user_ubicacion']) ){
if (
    (!isset($_SESSION['user_ubicacion']) || !in_array($_SESSION['user_ubicacion'], $array_paises)) ||
    !isset($_COOKIE['user_ubicacion']) ||
    (isset($_COOKIE['user_ubicacion']) && isset($_SESSION['user_ubicacion']) && $_COOKIE['user_ubicacion'] !== $_SESSION['user_ubicacion']) ){

$apiKey = '9bd596db66cb45c184de6e5034066a94';

ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL & ~E_DEPRECATED);

$ipAddressComplete = $_SERVER['HTTP_CLIENT_IP'];
$ipAddress = strtok($ipAddressComplete, ':');
// echo $ipAddress;

echo "<script>
    console.log('IP Address: $ipAddress');
</script>";

// echo "<script>
//     console.log('IP Address: $ipAddress');
//     console.log('HTTP_CLIENT_IP: $client_ip');
//     console.log('HTTP_X_FORWARDED_FOR: $forwarded_for');
//     console.log('HTTP_X_REAL_IP: $real_ip');
//     console.log('REMOTE_ADDR: $remote_addr');
// </script>";


function getGeolocationData($apiKey, $ipAddress) {

    $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ipAddress";

    echo "<script>
        console.log('URL: $url');
    </script>";

    $response = @file_get_contents($url);
    if ($response === FALSE) {
        // error_log("Error fetching geolocation data for IP: $ipAddress");
        return NULL;
    }

    return json_decode($response, true);
}

$data = getGeolocationData($apiKey, $ipAddress);

if ($data === NULL) {
    // echo "Error fetching geolocation data.";
} else {
    $geo_cod_pais = strtoupper($data['country_code2']);
    // echo "Country code: " . $geo_cod_pais;
}

echo "<script>
    console.log('COD PAIS: $geo_cod_pais');
</script>";

// if ($geo_cod_pais == "" || $geo_cod_pais == NULL) {
//     echo "La variable está vacía.";
//     // Aquí puedes manejar la situación, como asignar un valor predeterminado, mostrar un mensaje, etc.
// } else {
//     echo "La variable tiene valor: " . $ipAddress;
// }

// $geo_cod_pais = 'US';

$idiomas_pais = $checkout->obten_idiomas();

// var_dump( $idiomas_pais);exit;

?>



<style>

button#acepta_geolocation {
    width: 100%;
    margin: 0;
    height: 49px;
    border-radius: 17px;
    background: #8cb73e;
    border-color: #8cb73e;
    font-size: 18px;
}
button#acepta_geolocation:hover {
    background: transparent;
    color: #8cb73e;
}
form#form_geolocation label {
    font-size: 13px;
}
form#form_geolocation select {
    border-radius: 10px;
    background-color: transparent;
    border-color: #8cb73e;
}
.geolocation .modal-content {
    background-color: #f0f7e6;
    border-radius: 40px;
    /*margin-top: 38%;*/
    padding: 4px 16px 14px;
}
.custom-select-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.custom-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: white url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="gray"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center;
    background-size: 16px 16px;
}

.custom-select:focus {
    border-color: #666;
    outline: none;
}
form#form_geolocation .col-md-6 {
    margin-bottom: 2px;
}
div#pop_up_geolocation {
    display: flex;
    align-items: center;
    justify-content: center;
    align-content: space-around;
}
form#form_geolocation .row {
    background-color: #f0f7e6 !important;
}
@media (max-width: 768px) {
    .modal.show .modal-dialog {
        transform: none;
        max-width: fit-content;
        display: flex;
        justify-content: center;
    }
}


</style>

<?php if ($geo_cod_pais != "" && $geo_cod_pais != null) { ?>
    <div class="geolocation">
        <div class="modal fade gen" id="pop_up_geolocation" >
            <div class="modal-dialog">
                <div class="modal-content" style="background-color: transparent;border: none;">
                    <div class="modal-body">
                        <div style="width: 100%; height: 60px; display: flex; justify-content: center; align-items: center;">
                            <p id="loading-text">
                                <span id="dots"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .loading-container {
            width: 100%;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .spinner {
            width: 80px;
            height: 80px;
            border: 10px solid rgba(255, 255, 255, 0.3);
            border-top: 10px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }

        #dots::after {
            content: '...';
            animation: blink 1s infinite;
            color: #3498db;
        }

        #loading-text {
            font-size: 200px !important;
            color: white;
            text-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            position: relative;
            bottom: 50px;
        }

        .modal-body {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
        }
    </style>
<?php } ?>

<!-- <div class="geolocation"> -->
<div class="geolocation"
<?php if ($geo_cod_pais != "" && $geo_cod_pais != null) { ?>
style="display: none;"

<?php } ?> >




<div class="modal fade gen" id="pop_up_geolocation" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-modal">
                    <form id="form_geolocation" method='POST'>
                        <div class="row">
                            <div class="form-group col-md-6">

                                <label for="form_empresa"><?php echo $vocabulario_pais_envio ?></label>
                                <div class="custom-select-wrapper">
                                    <select type="text" class="form-control custom-select" id="input_pais" name="input_pais">
                                    <?php foreach ($paises as $pais) { ?>
                                        <option value="<?php echo $pais->cod_pais ?>" <?php if ( $geo_cod_pais == $pais->cod_pais ) echo 'selected'?>><?php echo $pais->nombre ?></option>
                                    <?php } ?>
                                </select>

                                </div>

                            </div>
                            <div class="form-group col-md-6">
                                <label for="form_empresa"><?php echo $vocabulario_idioma ?></label>
                                <div class="custom-select-wrapper">
                                    <select type="text" class="form-control custom-select" id="input_idioma" name="input_idioma">
                                    </select>

                                    <script>
                                        var data = {accion: 'actualiza_form_lenguaje_geolocaliza', input_pais: $('#input_pais').val()}
                                        $.ajax({
                                            url: '<?php echo $ruta_link1 ?>class/control.php',
                                            type: 'post',
                                            dataType: 'html',
                                            data: data
                                        })
                                        .done(function(result) {
                                            $('#input_idioma').html(result);
                                        })
                                        .fail(function() {
                                            alert("error");
                                        });
                                    </script>
                                </div>
                            </div>
                            <input type="hidden" name="accion" value="actualiza_datos_ubicacion_idioma">
                            <input type="hidden" name="id_url" value="<?php echo $id_url ?>">
                            <div class="btns center">
                                <button type="button" id="acepta_geolocation" class="cart_btn"><?php echo $vocabulario_aceptar ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>


var myModal = new bootstrap.Modal(document.getElementById('pop_up_geolocation'), {
    backdrop: 'static',
    keyboard: false
})
myModal.show();


$(document).ready(function() {
    $('#input_pais').change(function(event) {

        var data = {accion: 'actualiza_form_lenguaje_geolocaliza', input_pais: $('#input_pais').val()}
        $.ajax({
            url: '<?php echo $ruta_link1 ?>class/control.php',
            type: 'post',
            dataType: 'html',
            data: data
        })
        .done(function(result) {
            $('#input_idioma').html(result);
        })
        .fail(function() {
            alert("error");
        });

    });
});


$(document).ready(function() {
    $('#acepta_geolocation').click(function(e) {
        e.preventDefault();

        // Esperar a que el #input_idioma tenga un valor antes de proceder
        var checkInputIdioma = setInterval(function() {
            var input_idioma = $('#input_idioma').val();
            if (input_idioma !== null && input_idioma !== '') {
                clearInterval(checkInputIdioma);

                var paisSeleccionado = $('#input_pais').val();

                console.log("Input", input_idioma);

                document.cookie = "user_ubicacion=" + paisSeleccionado + "; expires=" + new Date(new Date().getTime() + (24 * 60 * 60 * 1000)).toUTCString() + "; path=/";

                var formData = $('#form_geolocation').serializeArray();
                formData.push({ name: 'paisSeleccionado', value: paisSeleccionado });

                $.ajax({
                    url: '<?php echo $ruta_link1 ?>class/control.php',
                    type: 'post',
                    dataType: 'text',
                    data: $.param(formData),
                })
                .done(function(result) {
                    console.log(result);
                    var parsedResult = $.parseJSON(result);
                    console.log(parsedResult);
                    console.log(parsedResult.msg);

                    if (<?php echo $isGoogleBot?> == 1){
                        window.location.href = '<?php echo $rutaServer ?>/<?php echo $idioma_url ?>/<?php echo $url ?>';
                    }else{
                        window.location.href = '<?php echo $rutaServer ?>/' + parsedResult.msg;
                    }

                })
                .fail(function() {
                    alert("error");
                });
            }
        }, 100); // Verifica cada 100 ms si el valor está disponible
    });
});


$(document).ready(function() {
    var geo_cod_pais = '<?php echo $geo_cod_pais ?>';

    if (geo_cod_pais != "" && geo_cod_pais != null && geo_cod_pais != undefined) {
        var checkInputIdioma = setInterval(function() {
            var input_idioma = $('#input_idioma').val();
            if (input_idioma !== null && input_idioma !== '') {
                clearInterval(checkInputIdioma);
                document.getElementById('acepta_geolocation').click();
            }
        }, 100);
    }
});

</script>
<?php } ?>
