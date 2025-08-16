<?php
include_once('../../assets/lib/bbdd.php');
// include_once('../../assets/lib/funciones.php');
include_once('../assets/lib/funciones_admin.php');

$id_aviso = $_REQUEST['id_aviso'];

if ($id_aviso != 0) {
	$datos_aviso = obten_aviso_web($id_aviso);
	$accion = 'actualiza_aviso';
	$tit_form = 'Modificar aviso web ' .  $datos_aviso->nombre_aviso;
} else {
	$accion = 'nuevo_aviso';
	$tit_form = 'Nuevo aviso web';
}

?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit"> </p>

<div class="row form-modal">
	<form id="form-datos" class="form_datos_perso" method='POST' onsubmit="return valida_form()">
		<div class="row">
			<div class="sep10"></div>
			<div class="form-group col-md-12 datetime">
				<div class="row">
					<div class="col-md-8">
						<label for="form_empresa">Nombre aviso <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
						<input type="text" class="form-control" id="input_nombre" name="input_nombre" value="<?php echo (!empty($datos_aviso->nombre_aviso)) ? $datos_aviso->nombre_aviso : ''; ?>">
					</div>
					<div class="col-md-4">
						<label for="form_empresa">Estado <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
						<select type="text" class="form-control" id="input_estado_aviso" name="input_estado_aviso">
							<option value="1" <?php if (isset($datos_aviso) && $datos_aviso->activo == 1) echo 'selected'; ?>>Activo</option>
							<option value="0" <?php if (isset($datos_aviso) && $datos_aviso->activo == 0) echo 'selected'; ?>>Inactivo</option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group col-md-12 datetime">
				<div class="row">
					<div class="col-md-6">
						<label for="form_empresa">Fecha inicio <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
						<input type="text" class="form-control" id="input_fecha_ini" name="input_fecha_ini" value="<?php echo (!empty($datos_aviso->fecha_inicio)) ? obten_fecha($datos_aviso->fecha_inicio) : date('d/m/Y'); ?>">
					</div>
					<div class="col-md-6">
						<label for="form_empresa">Hora inicio <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
						<input type="text" class="form-control" id="input_hora_ini" name="input_hora_ini" value="<?php echo (!empty($datos_aviso->fecha_inicio)) ? obten_hora($datos_aviso->fecha_inicio) : '00:00:00'; ?>">
					</div>
				</div>
			</div>
			<div class="form-group col-md-12 datetime">

				<div class="row">
					<div class="col-md-6">
						<label for="form_empresa">Fecha fin <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
						<input type="text" class="form-control" id="input_fecha_fin" name="input_fecha_fin" value="<?php echo (!empty($datos_aviso->fecha_fin)) ? obten_fecha($datos_aviso->fecha_fin) : ''; ?>">
					</div>
					<div class="col-md-6">
						<label for="form_empresa">Hora fin <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
						<input type="text" class="form-control" id="input_hora_fin" name="input_hora_fin" value="<?php echo (!empty($datos_aviso->fecha_fin)) ? obten_hora($datos_aviso->fecha_fin) : '00:00:00'; ?>">
					</div>
				</div>
			</div>

			<div class="form-group col-md-12">
				<label for="form_empresa">Texto español </label>
				<textarea rows="3" class="form-control" id="text_coment" name="aviso_es"><?php echo (!empty($datos_aviso->aviso_es)) ? $datos_aviso->aviso_es : ''; ?></textarea>
			</div>
			<div class="sep10"></div>

			<div class="form-group col-md-12">
				<label for="form_empresa">Texto inglés GB</label>
				<textarea rows="3" class="form-control" id="text_coment" name="aviso_en"><?php echo (!empty($datos_aviso->aviso_en)) ? $datos_aviso->aviso_en : ''; ?></textarea>
			</div>
			<div class="sep10"></div>

			<div class="form-group col-md-12">
				<label for="form_empresa">Texto francés </label>
				<textarea rows="3" class="form-control" id="text_coment" name="aviso_fr"><?php echo (!empty($datos_aviso->aviso_fr)) ? $datos_aviso->aviso_fr : ''; ?></textarea>
			</div>
			<div class="sep10"></div>

			<div class="form-group col-md-12">
				<label for="form_empresa">Texto italiano </label>
				<textarea rows="3" class="form-control" id="text_coment" name="aviso_it"><?php echo (!empty($datos_aviso->aviso_it)) ? $datos_aviso->aviso_it : ''; ?></textarea>
			</div>
			<div class="sep10"></div>

			<div class="form-group col-md-12">
				<label for="form_empresa">Texto alemán </label>
				<textarea rows="3" class="form-control" id="text_coment" name="aviso_de"><?php echo (!empty($datos_aviso->aviso_de)) ? $datos_aviso->aviso_de : ''; ?></textarea>
			</div>
			<div class="sep10"></div>

			<div class="form-group col-md-12">
				<label for="form_empresa">Texto inglés US</label>
				<textarea rows="3" class="form-control" id="text_coment" name="aviso_en_us"><?php echo (!empty($datos_aviso->aviso_en_us)) ? $datos_aviso->aviso_en : ''; ?></textarea>
			</div>



			<div class="sep20"></div>

			<div class="btns">
				<input type="hidden" name="id_aviso" value="<?php echo $id_aviso ?>">
				<input type="hidden" name="accion" value="<?php echo $accion ?>">
				<button id="aceptar" class="send cart_btn"><i class="fa fa-save"></i>Guardar</button>
				<?php if (!empty($datos_aviso)): ?>
				<button class="delete" data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar aviso '<?php echo $datos_aviso->nombre_aviso ?>'" onclick="if (confirm('Si aceptas se eliminará el aviso <?php echo $datos_aviso->nombre_aviso ?> ¿Estás seguro?') == true ) { eliminaAviso( <?php echo $datos_aviso->id ?> )}">
					<i class="fa fa-trash"></i>
				</button>
				<?php endif; ?>
			</div>
	</form>
</div>
<style>
	select {
		-webkit-appearance: listbox !important
	}

	.form-control:focus {
		border-color: #ccc;
		box-shadow: none;
	}

	.form-modal input[type="text"],
	.form-modal select {
		margin-bottom: 20px;
	}

	textarea#text_coment {
		font-size: 14px;
		line-height: 18px;
	}
</style>

<script>
	$(function() {
		$('[data-bs-toggle="tooltip"]').tooltip()
	});

	$('#form-datos').submit(function(e) {
		e.preventDefault();
	});

	$('#aceptar').click(function(e) {

		// if ( valida_form() ) {
		$.ajax({
				url: './assets/lib/admin_control.php',
				type: 'post',
				dataType: 'text',
				data: $('#form-datos').serialize()
			})
			.done(function(result) {
				var result = $.parseJSON(result);
				if (result.res == 0) {
					muestraMensajeLn(result.msg)
				} else if (result.res == 1) {
					muestraMensajeLn(result.msg);
					setTimeout(function() {
						location.reload();
					}, 2000);
				}
			})
			.fail(function() {
				alert("error");
			});
		// }
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

	$(function() {
		$('.input_fecha, #input_fecha_ini, #input_fecha_fin').datepicker({
			orientation: 'bottom',
			language: 'es'
		});
	});

	$('.input_hora, #input_hora_ini, #input_hora_fin').timepicker({
		minuteStep: 1,
		secondStep: 1,
		useCurrent: false,
		format: 'DD/MM/YYYY HH:mm:ss',
		showMeridian: false,
		showSeconds: true,

	})

	function valida_form() {

		if ($('#input_nombre').val() == '') {
			muestraMensajeLn('Es necesario un nombre para el aviso');
			return false;
		} else if ($('#input_fecha_ini').val() == '' || $('input_fecha_fin').val() == '') {
			muestraMensajeLn('Es necesario introducir Fecha inicio y Fecha fin');
			return false;
		} else if ($('#input_hora_ini').val() == '' || $('input_hora_fin').val() == '') {
			muestraMensajeLn('Es necesario introducir Hora inicio y Hora fin');
			return false;
		} else if ($('#input_fecha_ini').val() != '' || $('input_fecha_fin').val() != '') {

			var d1 = $('#input_fecha_ini').val();
			var parts = d1.split('/');
			var d1 = Number(parts[2] + parts[1] + parts[0]);
			var d2 = $('#input_fecha_fin').val();
			parts = d2.split('/');
			var d2 = Number(parts[2] + parts[1] + parts[0]);

			if (d1 > d2) {
				muestraMensajeLn('Fecha fin debe ser posterior a Fecha inicio');
				return false;
			} else {
				return true
			}
		} else {
			return true;
		}
	}
</script>
