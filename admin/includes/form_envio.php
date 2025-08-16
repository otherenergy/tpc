<?php
include_once('../../assets/lib/bbdd.php');
include_once('../../assets/lib/funciones.php');

// var_dump($_POST);exit;

$estado = $_POST['estado'];
$ref_pedido = $_POST['ref_pedido'];
$oculto = ( $estado == 'Pendiente') ? 'oculto' : '';
// var_dump($res);
// echo $sql;
// exit;
$tracking = obten_datos_tracking_ref_pedido( $ref_pedido );

?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit">Modificar estado de envío del pedido <?php echo $ref_pedido ?> </p>

<div class="row form-modal">
	<form id="form-datos" class="form_datos_perso" method='POST' >
		<div class="row">
			<div class="sep10"></div>
			<div class="form-group col-md-12">
				<label for="form_empresa">Estado del envío</label>
				<select type="text" class="form-control" id="input_estado_envio" name="input_estado_envio">
					<option value="Enviado" <?php if ( $estado == 'Enviado') echo 'selected'; ?>>Enviado</option>
					<option value="Pendiente" <?php if ( $estado == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
				</select>
			</div>
			<div class="dat_track <?php echo $oculto ?>">
				<div class="form-group col-md-12">
					<label for="form_empresa">Fecha (dd/mm/yyyy)</label>
					<input type="text" class="form-control input_fecha" id="input_fecha" name="input_fecha" value="<?php echo (!empty( $tracking->fecha_envio )) ? $tracking->fecha_envio : ''; ?>" >
				</div>
				<div class="form-group col-md-12">
					<label for="form_empresa">Transportista </label>
					<input type="text" class="form-control" id="input_transportista" name="input_transportista" value="<?php echo (!empty( $tracking->transportista )) ? $tracking->transportista : ''; ?>" >
				</div>
				<div class="form-group col-md-12">
					<label for="form_empresa">Nº seguimiento </label>
					<input type="text" class="form-control" id="input_tracking" name="input_seguimiento" value="<?php echo (!empty( $tracking->num_tracking )) ? $tracking->num_tracking : ''; ?>" >
				</div>
				<div class="form-group col-md-12">
					<label for="form_empresa">Enviar email a cliente</label>
					<select type="text" class="form-control" id="input_envio_email" name="input_envio_email">
						<option value="0">NO</option>
						<option value="1">SI</option>
					</select>
				</div>
			</div>
			<div class="btns">
				<input type="hidden" name="ref_pedido" value="<?php echo $ref_pedido ?>">
				<input type="hidden" name="accion" value="actualiza_estado_envio">
  			<button class="cancel cart_btn out" data-bs-dismiss="modal"><i class="fa fa-times"></i>Cancelar</button>
  			<button id="aceptar" class="send cart_btn"><i class="fa fa-check"></i>Aceptar</button>
			</div>

		</form>
	</div>
	<style>
		select{
			-webkit-appearance: listbox !important
		}
		.form-control:focus {
	    border-color: #ccc;
	    box-shadow: none;
	}
	</style>

	<script>
		$('#input_estado_envio').change(function(event) {
			if( $(this).val() == 'Pendiente' ) {
				$('.dat_track').fadeOut();
			}else {
				$('.dat_track').removeClass('oculto');
				$('.dat_track').fadeIn();
			}
		});

		$('#form-datos').submit(function(e) {
			e.preventDefault();
		});

		$('#aceptar').click(function(e) {
			$.ajax({
				url: './assets/lib/admin_control.php',
				type: 'post',
				dataType: 'text',
				data: $('#form-datos').serialize()
			})
			.done(function(result) {
				var result = $.parseJSON(result);
				if(result.res==0) {
					muestraMensajeLn(result.msg)
				}else if (result.res==1) {
					muestraMensajeLn(result.msg);
					setTimeout(function() {
						location.reload();
					},2000);
				}
			})
			.fail(function() {
				alert("error");
			});
		});

		$.fn.datepicker.dates['es'] = {
			days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
			daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
			daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
			months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
			monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
			today: "Hoy",
			clear: "Borrar",
			format: "dd/mm/yyyy",
			titleFormat: "MM yyyy",
			weekStart: 1
		};

		$(function(){
			$('.input_fecha, #input_fecha_ini, #input_fecha_fin').datepicker({
				orientation: 'bottom',
				language: 'es'
			});
		});

	</script>
