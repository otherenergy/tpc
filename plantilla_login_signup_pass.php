<?php

session_start();
$_SESSION['nivel_dir'] = 1;

include('../includes/nivel_dir.php');

include_once ('../config/db_connect.php');

$rutaServer = $_ENV['RUTA_SERVER'];

if (session_status() === PHP_SESSION_NONE){session_start();}

if ( isset($_SESSION["smart_user"]["login"]) && $_SESSION["smart_user"]["login"]!='0') {
	header("Location: ".$rutaServer."/checkout");

	exit();
}

// if(!isset($_GET['reset'])) header("location: https://www.smartcret.com/");
?>

<?php

include_once ('../class/userClass.php');
include_once ('../class/emailClass.php');
include_once ('../includes/vocabulario.php');

$userClass = new userClass();

$url_data=$userClass->obtener_informacion_url($id_url, $id_idioma);
$url_metas=$userClass->url_metas($id_url,$id_idioma);
$contenido_general=$userClass->contenido_general($id_url, $id_idioma);

?>

<!DOCTYPE html>
<html lang="es-ES">
    <?php include('../includes/head.php');?>
	<body>
        <!-- Header - Inicio -->
        <?php include('../includes/header.php'); ?>
        <!-- Header - Fin -->
        <?php echo $contenido_general->content ?>
        <br>
			<script src="https://accounts.google.com/gsi/client" async defer></script>
    		<div id="g_id_onload"
         		data-client_id="623607501139-jptk33ljlc57vi8e7ll1miju73copgfl.apps.googleusercontent.com"
         		data-callback="handleCredentialResponse">
    		</div>
        <br>
        <br>
        <br>
        <br>
		<!-- Footer - Inicio -->
		<?php include('../includes/footer.php'); ?>
		<!-- Footer - Fin -->
        <script src="https://apis.google.com/js/platform.js" async defer></script>
		<script>

		var reset_form = document.querySelectorAll('#reset-form');
		const clientId = '623607501139-jptk33ljlc57vi8e7ll1miju73copgfl.apps.googleusercontent.com';

        function handleCredentialResponse(response) {

            const userObject = parseJwt(response.credential);

			if(userObject.email) {
				$.ajax({
					url: '../class/control.php',
					type: 'post',
					datatype: 'text',
					data: {accion: 'login_google', emailgoogle: userObject.email, namegoogle: userObject.given_name}
				})
				.done(function(result) {
					console.log(result);
					var result = $.parseJSON(result);
					muestraMensajeLn(result.msg);
					if(result.res==1) {
						setTimeout( function() {
						window.location.href = result.srv
						},3000);
					}else{
						setTimeout( function() {
						window.location.href = result.srv+"/login";
						},3000);
					}
				})
				.fail(function() {
					alert("error");
				});
			}

        }

        function parseJwt(token) {
            const base64Url = token.split('.')[1];
            const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
            const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));

            return JSON.parse(jsonPayload);
        }

		function getUrlParameter(name) {
			name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
			var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
			var results = regex.exec(location.search);
			return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
		}

		if (reset_form.length > 0) {

			$(document).ready(function() {
				$('#reset-form').submit(function(e) {
					e.preventDefault();
				});
				$('#reset_pass').click(function(e) {
					if(compruebaDatos()){
						var reset = getUrlParameter('reset');
						var key = getUrlParameter('key');
						var newPass = $('#input_pass').val()

						$.ajax({
							url: '../class/control.php',
							type: 'post',
							dataType: 'text',
							data: {accion: 'reset_pass', old_pass: reset, input_email: key, input_pass: newPass}
						})
						.done(function(result) {
							var result = $.parseJSON(result);
							muestraMensajeLn(result.msg);
							setTimeout( function() {
								window.location.href = "../";
							},2000);
						})
						.fail(function() {
							alert("error");
						});
					}
				});
			});
			function compruebaDatos() {
				if($('#input_pass').val()!=$('#input_re_pass').val()) {
					muestraMensajeLn(`<?php echo $vocabulario_contrasenas_no_coinciden ?>`);
					return false;
				}else {
					return true;
				}
			}
		}

		var recupera_form = document.querySelectorAll('#recupera-form');

		if (recupera_form.length > 0) {

			$(document).ready(function() {
				$('#recupera-form').submit(function(e) {
					e.preventDefault();
				});
				$('#recupera_btn').click(function(e) {
					if ( compruebaDatosEnvio() ) {

							$.ajax({
								url: '../class/control.php',
								type: 'post',
								dataType: 'text',
								data: $('#recupera-form').serialize()
							})
							.done(function(result) {
								var result = $.parseJSON(result);
								muestraMensajeLn(result.msg);
								setTimeout( function() {
									location.reload();
								},2000);
						})
							.fail(function() {
								alert("error");
							});
					}
				});
			});

			function compruebaDatosEnvio () {
				if($('#input_email').val()=='') {
					muestraMensajeLn(`<?php echo $vocabulario_tienes_introducir_direccion_email ?>`);
					return false
				} else if ( !validaEmail( $('#input_email').val() ) ) {
					muestraMensajeLn(`<?php echo $vocabulario_email_no_valido ?>`);
					return false
				}
				return true;
			}
		}
        </script>
    </body>
</html>
