<?php
include_once('../../assets/lib/bbdd.php');
include_once('../../assets/lib/funciones.php');

$estado_pago = $_POST['estado'];
$ref_pedido = $_POST['ref_pedido'];
$oculto = ( $estado_pago == 'Pendiente') ? 'oculto' : '';

$datos_pedido = obten_datos_pedido_ref( $ref_pedido );
$metodo_pago = $datos_pedido->metodo_pago;

// var_dump($res);
// echo $sql;
// exit;
?>

<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit">Modificar estado pago del pedido <?php echo $ref_pedido ?> </p>

<div class="row form-modal">
	<form id="form-datos" class="form_datos_perso" method='POST' >
		<div class="row">
			<div class="sep10"></div>
			<div class="form-group col-md-12">
				<label for="form_empresa">Estado del pago</label>
				<select type="text" class="form-control" id="input_estado_pago" name="estado_pago">
					<option value="Pagado" <?php if ( $estado_pago == 'Pagado') echo 'selected'; ?>>Pagado</option>
					<option value="Pendiente" <?php if ( $estado_pago == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
				</select>
			</div>
			<div class="dat_track <?php echo $oculto ?>">
				<div class="form-group col-md-12">
					<label for="form_empresa">Método de pago</label>
					<select type="text" class="form-control" id="metodo_pago" name="metodo_pago">
						<?php
							$metodos_pago = obten_metodos_pago();
							while ( $met_pago = $metodos_pago->fetch_object() ) { ?>
								<option value="<?php echo $met_pago->id ?>" <?php if ( $met_pago->id == $metodo_pago ) echo 'selected'; ?>><?php echo $met_pago->nombre ?></option>
							<?php }
						 ?>
					</select>
				</div>
				<div class="form-group col-md-12">
					<label for="form_empresa">Fecha (dd/mm/yyyy)</label>
					<input type="text" class="form-control input_fecha" id="fecha_pago" name="fecha_pago" value="<?php echo (!empty( $datos_descuento->fecha_inicio )) ? obten_fecha( $datos_descuento->fecha_inicio ) : date('d/m/Y'); ?>" >
				</div>
			</div>
			<div class="btns">
				<input type="hidden" name="ref_pedido" value="<?php echo $ref_pedido ?>">
				<input type="hidden" name="accion" value="actualiza_estado_pago">
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

		$('#input_estado_pago').change(function(event) {
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

		$('.input_hora, #input_hora_ini, #input_hora_fin').timepicker({
			minuteStep: 1,
			secondStep: 1,
			useCurrent: false,
			format : 'DD/MM/YYYY HH:mm:ss',
			showMeridian: false,
			showSeconds: true,

		})

	</script>
