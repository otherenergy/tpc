<?php
include_once('../../../config/db_connect.php');
include_once('../../../assets/lib/class.carrito.php');
include_once('../../../assets/lib/funciones.php');
include_once('../../../class/userClass.php');
include_once('../../../class/checkoutClass.php');


$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma = $id_idioma_global;

include_once('../../../includes/vocabulario.php');

$checkout = new Checkout();
$paises_permitidos_array_obj = $checkout->obten_dir_envios_paises_obj($id_idioma);
if ($_REQUEST['type'] == 'edit') {
	$envio_id = $_REQUEST['id'];
	$reg = $checkout->obten_dir_facturacion($envio_id)[0];
	$type = "actualiza_dir_facturacion";
} elseif ($_REQUEST['type'] == 'new') {
	$type = "nueva_dir_facturacion";
	$envio_id = 0;
}

// var_dump($reg);exit;

?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit"><?php echo $vocabulario_añadir_modificar_direccion_facturacion; ?></p>

<div class="row form-modal">
	<form id="form-datos" class="form_dir_facturacion" action='#' method='POST'>
		<div class="row">
			<div class="col-md-12">
				<a href="javascript:copiarDatosEnvio()" id="copy-dat" class="cart_btn btn-tabla"><i class="fa fa-arrow-down"></i><?php echo $vocabulario_copiar_datos_envio; ?></a>
			</div>
			<div class="col-md-12 tipo_factura">
				<input type="radio" id="particular" name="tip_factura" value="Particular" <?php echo (!empty($reg) && $reg->tipo_factura == 'Particular') ? 'checked' : ''; ?>>
				<label for="particular"><?php echo $vocabulario_particular; ?></label>
				<input type="radio" id="autonomo" name="tip_factura" value="Autonomo" <?php echo (!empty($reg) && $reg->tipo_factura == 'Autonomo') ? 'checked' : ''; ?>>
				<label for="autonomo"><?php echo $vocabulario_autonomo; ?></label>
				<input type="radio" id="empresa" name="tip_factura" value="Empresa" <?php echo (!empty($reg) && $reg->tipo_factura == 'Empresa') ? 'checked' : ''; ?>>
				<label for="empresa"><?php echo $vocabulario_empresa; ?></label>
			</div>
			<div class="form-group col-md-6">
				<label for="form_pais"><?php echo $vocabulario_pais; ?> <span style="color:red;">*</span></label>
				<select type="text" class="form-control" id="input_pais" name="input_pais">
					<option value="-"><?php echo $vocabulario_seleccione; ?></option>
					<?php foreach ($paises_permitidos_array_obj as $pais) {
						$id_pais = $pais->id_pais;
						$cod_pais = $pais->cod_pais;
						$nombre_pais = $pais->nombre;
					?>
						<option id_pais="<?php echo $id_pais ?>" value="<?php echo $cod_pais ?>" <?php if ($cod_pais == $reg->pais) echo 'selected' ?>><?php echo $nombre_pais ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group col-md-6">
				<label for="form_prov" id="label_prov"><?php echo $vocabulario_provincia; ?> <span style="color:red;">*</span></label>

				<?php if ($_REQUEST['type'] == 'edit') { ?>
					<select type="text" class="form-control" id="input_prov" name="input_prov" <?php if ($_REQUEST['type'] != 'edit') echo 'disabled="true"'; ?>>
						<option value="<?php echo $reg->provincia; ?>" selected>
							<?php echo is_numeric($reg->provincia) ? obten_nombre_provincia($reg->provincia) : $reg->provincia; ?>
						</option>
					</select>
				<?php } else { ?>
					<select type="text" class="form-control" id="input_prov" name="input_prov" disabled="true">
					</select>
				<?php } ?>
				<input type="hidden" name="accion" value="<?php echo $type ?>">
				<input type="hidden" name="id_envio" value="<?php echo $envio_id ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_nif"><?php echo $vocabulario_dni_nif_nie; ?> <span style="color:red;"></span></label>
				<input type="text" class="form-control" id="input_nif" name="input_nif" value="<?php echo (!empty($reg->nif)) ? $reg->nif : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_empresa"><?php echo $vocabulario_empresa; ?></label>
				<input type="text" class="form-control" id="input_empresa" name="input_empresa" value="<?php echo (!empty($reg->empresa)) ? $reg->empresa : ''; ?>">
			</div>
			<div class="form-group col-md-12">
				<p class="msj_nif"><?php echo $vocabulario_esencial_identificacion_correcta; ?></p>
			</div>
			<div class="form-group col-md-6">
				<label for="form_nom"><?php echo $vocabulario_nombre; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_nom" name="input_nom" value="<?php echo (!empty($reg->nombre)) ? $reg->nombre : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_ape"><?php echo $vocabulario_apellidos; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_ape" name="input_ape" value="<?php echo (!empty($reg->apellidos)) ? $reg->apellidos : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_dir"><?php echo $vocabulario_direccion_envio; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_dir" name="input_dir" value="<?php echo (!empty($reg->direccion)) ? $reg->direccion : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_cod"><?php echo $vocabulario_codigo_postal; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_cod" name="input_cod" value="<?php echo (!empty($reg->cp)) ? $reg->cp : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_pob"><?php echo $vocabulario_ciudad; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_pob" name="input_pob" value="<?php echo (!empty($reg->localidad)) ? $reg->localidad : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_telf"><?php echo $vocabulario_telefono; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_telf" name="input_telf" value="<?php echo (!empty($reg->telefono)) ? $reg->telefono : ''; ?>">
			</div>
			<div class="form-group col-md-12">
				<p class="msj_nif"><?php echo $vocabulario_esencial_identificacion_correcta; ?></p>
			</div>
		</div>
		<div class="btns">
			<button class="cancel cart_btn out" data-bs-dismiss="modal"><i class="fa fa-times"></i><?php echo $vocabulario_cancelar; ?></button>
			<button id="save_datos_facturacion" class="send cart_btn"><i class="fa fa-save"></i><?php echo $vocabulario_guardar; ?></button>
		</div>
	</form>
</div>

<script>
	$('#form-datos').submit(function(e) {
		e.preventDefault();
	});

	function copiarDatosEnvio() {
		$.ajax({
			url: '../../class/control.php',
			type: 'post',
			dataType: 'json',
			data: {
				accion: 'igual-dir-envio'
			},
		}).done(function(result) {
			$('#input_nif').val(result.nif_cif);
			$('#input_nom').val(result.nombre);
			$('#input_ape').val(result.apellidos);
			$('#input_dir').val(result.direccion);
			$('#input_cod').val(result.cp);
			$('#input_pob').val(result.localidad);
			$('#input_telf').val(result.telefono);
			$('#input_pais').val(result.pais).change();
			muestraMensajeLn(result.msg);
			setTimeout(function() {
				$('#input_prov').val(result.provincia).change();
			}, 500);

			setTimeout(function() {
				location.reload();
			}, 1000);
		})
		.fail(function() {
			alert("error");
		});
	};

	$('#save_datos_facturacion').click(function(e) {
		console.log("ejecutado");
		if (compruebaDatosEnvio()) {
			$.ajax({
					url: '../../class/control.php',
					type: 'post',
					dataType: 'text',
					data: $('#form-datos').serialize()
				})
				.done(function(result) {

					var result = $.parseJSON(result);
					$('#datos').modal('hide');
					muestraMensajeLn(result.msg);
					setTimeout(function() {
						location.reload();
					}, 2000);
				})
				.fail(function() {
					alert("error");
				});
		}
	});

	$('#input_pais').change(function(event) {
		if ($(this).val() == 'ES') {
			$.ajax({
					url: './includes/lista_provincias.php',
					type: 'post',
					dataType: 'html',
				})
				.done(function(result) {
					$('#input_prov').html(result);
					$('#input_prov').prop('disabled', false);
				})
				.fail(function() {
					alert("error");
				});
		} else {
			$('#input_prov').html('<option value="' + $(this).val() + '">' + $(this).val() + '</option>');
			$('#input_prov').prop('disabled', false);
		}
		if ($(this).val() == 'GB') {
			$('.blq-eori').fadeIn();
		} else {
			$('.blq-eori').fadeOut();
		}

		if ($(this).val() == 'US') {
			$.ajax({
					url: './includes/lista_estados_us.php',
					type: 'post',
					dataType: 'html',
				})
				.done(function(result) {
					$('#input_prov').html(result);
					$('#input_prov').prop('disabled', false);
				})
				.fail(function() {
					alert("error");
				});
		}
	});

	function compruebaDatosEnvio() {
		if ($('input[type="radio"]:checked').length == 0) {
			muestraMensajeLn(`<?php echo $vocabulario_seleccionar_tipo_cliente; ?>`);
			return false;
		}
		if ($('#empresa:checked').length == 1 && $('#input_empresa').val() == '') {
			muestraMensajeLn(`<?php echo $vocabulario_indicar_empresa; ?>`);
			return false;
		} else if ($('#input_nom').val() == '' || $('#input_ape').val() == '') {
			muestraMensajeLn(`<?php echo $vocabulario_nombre_apellido_vacios; ?>`);
			return false;
		} else if (contieneLetras($('#input_telf').val())) {
			muestraMensajeLn(`<?php echo $vocabulario_telefono_no_letras; ?>`);
			return false;
		} else if ($('#input_telf').val().length < 7) {
			muestraMensajeLn(`<?php echo $vocabulario_telefono_no_correcto; ?>`);
			return false;
		} else if ($('#input_dir').val() == '' || $('#input_cod').val() == '' || $('#input_pob').val() == '' || $('#input_telf').val() == '') {
			muestraMensajeLn(`<?php echo $vocabulario_direccion_codigo_ciudad_telefono_vacios; ?>`);
			return false;
		} else if ($('#input_dir').val().length < 3 || $('#input_cod').val().length < 3 || $('#input_pob').val().length < 3) {
			muestraMensajeLn(`<?php echo $vocabulario_direccion_codigo_ciudad_menor_3; ?>`);
			return false;
		} else if ($('#input_pais').val() == '-') {
			muestraMensajeLn(`<?php echo $vocabulario_debe_seleccionar_pais; ?>`);
			return false;
		} else if (!$('#input_prov').is('[disabled]')) {
			if ($('#input_prov').val() == '-') {
				muestraMensajeLn(`<?php echo $vocabulario_debe_seleccionar_provincia; ?>`);
				return false;
			} else if (($('#input_prov').val() != $('#input_cod').val().substring(0, 2) && '0' + $('#input_prov').val() != $('#input_cod').val().substring(0, 2)) && $('#input_pais').val() == 'ES') {
				muestraMensajeLn(`<?php echo $vocabulario_codigo_no_corresponde_provincia; ?>`);
				return false;
			} else {
				return true;
			}
		}
	}

	function contieneLetras(campo) {
		const regex = /[a-zA-Z]/;
		return regex.test(campo);
	}
</script>