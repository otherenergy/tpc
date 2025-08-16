<?php
include_once('../../../assets/lib/bbdd.php');
include_once ('../../../config/db_connect.php');
include_once('../../../assets/lib/class.carrito.php');
include_once('../../../assets/lib/funciones.php');
include_once('../../../class/userClass.php');

$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma=$id_idioma_global;

include_once('../../../includes/vocabulario.php');
// var_dump($_POST);exit;

if ( $_POST['type'] == 'edit') {
	$id = $_POST['id'];
	$reg = obten_datos_user( $id );
}

// var_dump($res);
// echo $sql;
// exit;

?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit"><?php echo $vocabulario_añadir_modificar_datos_personales; ?></p>

<div class="pruebas">

</div>

<div class="row form-modal">
	<form id="form-datos" class="form_datos_perso" method='POST' >
		<div class="row">
			<div class="col-md-12">
				<div class="tit"><?php echo $vocabulario_datos_personales; ?></div>
			</div>
			<div class="form-group col-md-6">
				<label for="form_nom"><?php echo $vocabulario_nombre; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_nom" name="input_nom" value="<?php echo (!empty( $reg->nombre )) ? $reg->nombre : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_ape"><?php echo $vocabulario_apellidos; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_ape" name="input_ape" value="<?php echo (!empty( $reg->apellidos )) ? $reg->apellidos : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_nif"><?php echo $vocabulario_dni_nif_nie; ?> <span style="color:red;"></span></label>
				<input type="text" class="form-control" id="input_nif" name="input_nif" value="<?php echo (!empty( $reg->nif_cif )) ? $reg->nif_cif : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_empresa"><?php echo $vocabulario_empresa; ?></label>
				<input type="text" class="form-control" id="input_empresa" name="input_empresa" value="<?php echo (!empty( $reg->empresa )) ? $reg->empresa : ''; ?>">
			</div>
			<!-- <div class="form-group col-md-12">
				<p class="msj_nif"><?php echo $vocabulario_esencial_identificacion_correcta; ?></p>
			</div> -->
			<div class="form-group col-md-6">
				<label for="form_telf"><?php echo $vocabulario_telefono; ?> <span style="color:red;"></span></label>
				<input type="text" class="form-control" id="input_telf" name="input_telf" value="<?php echo (!empty( $reg->telefono )) ? $reg->telefono : ''; ?>">
			</div>
			<!-- <div class="form-group col-md-12">
				<p class="msj_nif"><?php echo $vocabulario_indispensable_telefono_valido; ?></p>
			</div> -->
			<div class="btns">
				<input type="hidden" name="accion" value="actualiza_datos_personales">
				<button class="cancel cart_btn out" data-bs-dismiss="modal"><i class="fa fa-times"></i><?php echo $vocabulario_cancelar; ?></button>
				<button id="save_user" class="send cart_btn"><i class="fa fa-save"></i><?php echo $vocabulario_guardar; ?></button>
			</div>
		</div>
	</form>
</div>

	<script>
		$('#form-datos').submit(function(e) {
			e.preventDefault();
		});
		// $('.pruebas').html($('.form_datos_perso').serialize());
		$('#save_user').click(function(e) {
				// alert($('.form_datos_perso').serialize());
				if(compruebaDatosUser()){
					// alert('ok')
					$.ajax({
						url: '../../class/control.php',
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
				}
		});

		// function compruebaDatosUser () {

		// 	if($('#input_nom').val()=='' || $('#input_ape').val()=='') {
		// 		muestraMensajeLn(`<?php echo $vocabulario_nombre_apellido_vacios; ?>`);
		// 		return false;
		// 	}else if($('#input_nom').val().length < 3 || $('#input_ape').val().length < 3) {
		// 		muestraMensajeLn(`<?php echo $vocabulario_nombre_apellido_menor_3; ?>`);
		// 		return false;
		// 	}
		// 	// else if($('#input_email').val()=='') {

		// 	// 	muestraMensajeLn('Email no puede estar vacío');
		// 	// 	return false;

		// 	// }else if ( !validaEmail ( $('#input_email').val() ) ) {

		// 	// 	return false;

		// 	// }
		// 	// else if($('#input_nif').val()=='') {
		// 	// 	muestraMensajeLn(`CIF/NIF/NIE no puede estar vacío`);
		// 	// 	return false;
		// 	// }else if($('#input_telf').val()=='') {
		// 	// 	muestraMensajeLn(`Teléfono puede estar vacío`);
		// 	// 	return false;
		// 	// }
			
		// 	else {
		// 		return true;
		// 	}
		// }

		function compruebaDatosUser () {

			if($('#input_nom').val()=='' || $('#input_ape').val()=='') {
				muestraMensajeLn(`<?php echo $vocabulario_nombre_apellido_vacios; ?>`);
				return false;
			}else if($('#input_nom').val().length < 3 || $('#input_ape').val().length < 3) {
				muestraMensajeLn(`<?php echo $vocabulario_nombre_apellido_menor_3; ?>`);
				return false;
			}
			
			else if ('<?php echo $_SESSION['smart_user']['distribuidor'] ?>' == '1'){
				if($('#input_telf').val()=='') {
					muestraMensajeLn(`<?php echo $vocabulario_indispensable_telefono_valido; ?>`);
					return false;
				}
				else if($('#input_nif').val()=='') {
					muestraMensajeLn(`Debes introducir el DNI`);
					return false;
				}
			}
			
			
			return true;
		}
		function contieneLetras(campo) {
			const regex = /[a-zA-Z]/;
		  return regex.test(campo);
		}
	</script>