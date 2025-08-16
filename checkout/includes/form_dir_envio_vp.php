<?php
include_once('../../assets/lib/bbdd.php');
include_once ('../../config/db_connect.php');
include_once('../../assets/lib/class.carrito.php');
include_once('../../assets/lib/funciones.php');
include_once('../../class/userClass.php');
include_once('../../class/checkoutClass.php');

$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma=$id_idioma_global;

include_once('../../includes/vocabulario.php');
echo $id_idioma;
$checkout = new Checkout();
$paises_permitidos_array_obj = $checkout->obten_dir_envios_paises_obj($id_idioma);
var_dump($paises_permitidos_array_obj);

if ( $_REQUEST['type'] == 'edit') {
	$envio_id = $_REQUEST['id'];
	$reg = obten_dir_envio_user( $envio_id );
	$type = "actualiza_dir_envios";
}elseif ( $_REQUEST['type'] == 'new') {
	$type = "nueva_dir_envios";
	$envio_id = 0;
}

?>

<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit"><?php echo $vocabulario_añadir_modificar_direccion_envio; ?></p>

<div class="row form-modal">
	<form class="form_dir_envio" id="form-datos" action='#' method='POST' >
		<div class="row">
			<div class="form-group col-md-12">
				<label for="form_dir"><?php echo $vocabulario_direccion_envio; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_dir" name="input_dir" value="<?php echo (!empty( $reg->direccion )) ? $reg->direccion : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_cod"><?php echo $vocabulario_codigo_postal; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_cod" name="input_cod" value="<?php echo (!empty( $reg->cp )) ? $reg->cp : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_pob"><?php echo $vocabulario_ciudad; ?> <span style="color:red;">*</span></label>
				<input type="text" class="form-control" id="input_pob" name="input_pob" value="<?php echo (!empty( $reg->localidad )) ? $reg->localidad : ''; ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="form_pais"><?php echo $vocabulario_pais; ?> <span style="color:red;">*</span></label>
				<select type="text" class="form-control" id="input_pais" name="input_pais">
					<option value="-"><?php echo $vocabulario_seleccione; ?></option>
					<?php foreach ($paises_permitidos_array_obj as $pais) { 
						$id_pais= $pais->id_pais;
						$cod_pais= $pais->cod_pais;
						$nombre_pais= $pais->nombre;
						?>
						<option id_pais="<?php echo $id_pais ?>" value="<?php echo $cod_pais ?>"><?php echo $nombre_pais ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="form_prov"><?php echo $vocabulario_provincia; ?> <span style="color:red;">*</span></label>
				<?php if ( $_REQUEST['type'] == 'edit' ) { ?>
					<select type="text" class="form-control" id="input_prov" name="input_prov" <?php if ( $_REQUEST['type'] != 'edit' ) echo 'disabled="true"' ?> >
						<option value="<?php echo $reg->provincia ?>" selected><?php  echo ( is_numeric ( $reg->provincia ) ) ? obten_nombre_provincia ( $reg->provincia ) : $reg->provincia ?></option>
					</select>
				<?php } else { ?>
					<select type="text" class="form-control" id="input_prov" name="input_prov" disabled="true">
					</select>
				<?php } ?>
				<input type="hidden" name="accion" value="<?php echo $type ?>">
				<input type="hidden" name="id_envio" value="<?php echo $envio_id ?>">
			</div>
			<div class="col-md-12 otro_pais">
				<p><?php echo $vocabulario_envios_otros_paises; ?></p>
				<p class="link" onclick="cambioUrlEnvio( '../en-us' )"><?php echo $vocabulario_estados_unidos; ?></p>
				<p class="link" onclick="cambioUrlEnvio( '../es' )"><?php echo $vocabulario_espana_andorra_portugal; ?></p>
				<p class="link" onclick="cambioUrlEnvio( '../it' )"><?php echo $vocabulario_italia; ?></p>
				<p class="link" onclick="cambioUrlEnvio( '../de' )"><?php echo $vocabulario_alemania_austria; ?></p>
				<p class="link" onclick="cambioUrlEnvio( '../en-gb' )"><?php echo $vocabulario_resto_europa; ?></p>
				<p class="link" onclick="cambioUrlEnvio( '../fr' )"><?php echo $vocabulario_francia_belgica_suiza; ?></p>
			</div>
			<div class="form-group col-md-12">
				<label for="form_observa"><?php echo $vocabulario_observaciones; ?></label>
				<textarea type="text" class="form-control" id="input_observa" name="input_observa"><?php echo (!empty( $reg->observaciones )) ? $reg->observaciones : ''; ?></textarea>
			</div>
		</div>
		<div class="btns">
			<button class="cancel cart_btn out" data-bs-dismiss="modal"><i class="fa fa-times"></i><?php echo $vocabulario_cancelar; ?></button>
			<button id="save_datos_envio" class="send cart_btn"><i class="fa fa-save"></i><?php echo $vocabulario_guardar; ?></button>
		</div>
	</form>
</div>

<script>
		$('#form-datos').submit(function(e) {
			e.preventDefault();
		});
		$('#save_datos_envio').click(function(e) {
		 			if(compruebaDatosEnvio()){
		 				$.ajax({
		 					url: '../class/control.php',
		 					type: 'post',
		 					dataType: 'text',
		 					data: $('#form-datos').serialize()
		 				})
		 				.done(function(result) {
		 					var result = $.parseJSON(result);
		 					$('#datos').modal('hide');
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
			}else {
				$('#input_prov').html('<option value="' + $(this).val() + '">' + $(this).val() + '</option>');
				$('#input_prov').prop('disabled', false);
			}
			if ($(this).val() == 'GB') {
					$('.blq-eori').fadeIn();
				}else {
					$('.blq-eori').fadeOut();
				}
		});

		if ($(input_pais).val() == 'GB') {
				$('.blq-eori').fadeIn();
			}else {
				$('.blq-eori').fadeOut();
			}

		function compruebaDatosEnvio () {
			if($('#input_dir').val()=='' || $('#input_cod').val()=='' || $('#input_pob').val()=='') {
				muestraMensajeLn('<?php echo $vocabulario_direccion_codigo_ciudad_vacios; ?>');
				return false;
			}else if($('#input_dir').val().length < 3 || $('#input_cod').val().length < 3 || $('#input_pob').val().length < 3) {
				muestraMensajeLn('<?php echo $vocabulario_direccion_codigo_ciudad_menor_3; ?>');
				return false;
			}
			else if($('#input_pais').val()=='-') {
				muestraMensajeLn('<?php echo $vocabulario_debe_seleccionar_pais; ?>');
				return false;
			}
			else if(!$('#input_prov').is('[disabled]')) {
				if($('#input_prov').val()=='-') {
					muestraMensajeLn('<?php echo $vocabulario_debe_seleccionar_provincia; ?>');
				  return false;
				}else if ( ( $('#input_prov').val() != $('#input_cod').val().substring( 0, 2 ) && '0' + $('#input_prov').val() != $('#input_cod').val().substring( 0, 2 ) ) && $('#input_pais').val()=='ES' ) {
					muestraMensajeLn('<?php echo $vocabulario_codigo_no_corresponde_provincia; ?>');
				  return false;
				}else {
					return true;
				}
			}
		}
</script>
<script>
	function isValidEori( eori ) {
		first2 = eori.substring(0, 2);
		last3 = eori.slice(-3);
		if ( ( first2 != 'GB' || first2 != 'gb') && last3 != '000' ) return false;
		return true;
	}
</script>
<style>
	p.txt-eori {
	    color: red;
	    font-size: 14px;
	    margin-top: -10px;
	}
</style>