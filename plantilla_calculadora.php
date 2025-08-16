<?php

session_start();
$_SESSION['nivel_dir'] = 2;

include('../../includes/nivel_dir.php');

include ('../../config/db_connect.php');
include ('../../class/userClass.php');

$userClass = new userClass();
$url_data=$userClass->obtener_informacion_url($id_url, $id_idioma);

$contenido_general=$userClass->contenido_general($id_url, $id_idioma);

?>

<!DOCTYPE html>
<html lang="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
    <?php include('../includes/head.php');?>
	<body>
        <!-- Header - Inicio -->
        <?php include('../includes/header.php'); ?>
        <!-- Header - Fin -->

        <?php echo $contenido_general->content ?>

		<div class="sep100 desktop"></div>

		<!-- Footer - Inicio -->
		<?php include('../includes/footer.php'); ?>
		<!-- Footer - Fin -->
        <script>
            function scrollToId(id) {
                $('html, body').animate({
                    scrollTop: $("#"+id).offset().top -90
                }, 1000);
            }
        </script>
        <script>
            x=0;
            $(document).ready(function() {

                $('.sel_mat .item a').click(function(event) {
                    $('#resultado_presupuesto').fadeOut('400', function() {
                        $(this).html('');
                    });
                    $('.sel_mat .item').addClass('gris').removeClass('selected');
                    $(this).closest('.sel_mat .item').removeClass('gris').addClass('selected');
                });
            });

            function openCalculadora( tipo_presupuesto, title ) {

                $.ajax({
                    url: './includes/formulario_calculadora_presupuestos.php',
                    type: 'POST',
                    datatype: 'html',
                    data: { tipo_presupuesto:tipo_presupuesto, title:title }
                })
                .done(function(result) {
                    $('#form_calculadora').html(result).fadeIn(600);
                })
                .fail(function() {
                    alert('Se ha producido un error');
                })
            }

            function pres_to_carrito( ) {
                $.ajax({
                    url: './includes/control.php',
                    type: 'POST',
                    datatype: 'html',
                    data: { 'accion': 'presupuesto_a_carrito' }
                })
                .done(function(result) {
                    var result = $.parseJSON(result);
                    $('#numprod').text(result.numProd);
                    muestraMensajeLn(result.texto);
                    actualizaCarritoCalc();
                })
                .fail(function() {
                    alert('Se ha producido un error');
                })
            }

            function actualizaCarritoCalc(){
                $.ajax({
                    url: 'includes/actualiza_carrito.php',
                    type: 'POST',
                    datatype: 'html',
                })
                .done(function(result) {
                        $('#lista-productos').html(result);
                    })
                .fail(function() {
                    alert('Se ha producido un error');
                })
            }

            function eliminaArticuloCalc (uid){

                $.ajax({
                    url: '../assets/lib/carrito.php',
                    type: 'POST',
                    datatype: 'json',
                    data: { accion: 5, uid: uid },
                })
                .done(function(result) {
                    var result = $.parseJSON(result);
                        muestraMensaje(result.texto);
                        $('#numprod').text(result.numProd);
                    })
                .fail(function() {
                    alert('Se ha producido un error');
                })
            }

            $(document).on('input change', '#input_m2', function() {
                $('.input_num').val(parseFloat($(this).val()).toFixed(2));
                $('.paso2').fadeIn();
            });

            $(document).on('input change', '.input_num', function() {
                $(this).val(parseFloat($(this).val()).toFixed(2));
                $('#input_m2').val( $(this).val() );
                $('.paso2').fadeIn();
            });
        </script>
	</body>
</html>