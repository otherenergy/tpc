<?php
include_once('../../assets/lib/bbdd.php');
// include_once('../../assets/lib/funciones.php');
include_once('../assets/lib/funciones_admin.php');

$id_pedido = $_REQUEST['id_pedido'];
$ref_pedido =  $_REQUEST['ref_pedido'];

$sql = "SELECT * FROM sc_comentarios_preparacion WHERE id_pedido = $id_pedido ORDER BY fecha ASC";

$res=consulta($sql, $conn);
$num_filas = numFilas( $res)


?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit coment">Predido <?php echo $ref_pedido ?></p>

<div class="row form-modal">
	<form id="form-datos" class="form_datos_perso" method='POST' onsubmit="return valida_form()">
		<div class="row">

			<div class="form-group col-md-12">

				<div class="coment_box">

					<?php while($reg=$res->fetch_object()) { ?>

						<div class="coment_item">
							<p class="coment_user"><?php echo $reg->usuario ?><span class="coment_date"> [ <?php echo cambia_fecha_guion ( $reg->fecha ) ?> ]</span></p>
							<p class="coment_text"><?php echo $reg->comentario  ?></p>
						</div>

					<?php } ?>

				</div>

			</div>

			<div class="form-group col-md-12 nuevo_coment">
				<label for="form_empresa">Nuevo comentario</label>
				<textarea rows="3" class="form-control" id="comentario" name="comentario"></textarea>
			</div>

			<div class="sep20"></div>

			<div class="btns">
				<input type="hidden" name="id_pedido" value="<?php echo $id_pedido ?>">
				<input type="hidden" name="ref_pedido" value="<?php echo $ref_pedido ?>">
				<input type="hidden" name="accion" value="guarda_comentario_preparacion">
  			<button id="aceptar" class="send cart_btn"><i class="fa fa-save"></i>Guardar comentario</button>

  			<?php if (0) { ?>
	  			<button class="delete"
									data-bs-toggle="tooltip"
									data-bs-placement="top"
									title="Eliminar aviso '<?php echo $reg->nombre_aviso ?>'"
									onclick="if (confirm('Si aceptas se eliminará el aviso <?php echo $datos_aviso->nombre_aviso ?> ¿Estas seguro?') == true ) { eliminaAviso( <?php echo $datos_aviso->id ?> )}">
									<i class="fa fa-trash"></i>
					</button>
				<?php } ?>
			</div>
		</form>
	</div>
	<style>
		select{-webkit-appearance: listbox !important}
		.form-control:focus {border-color: #ccc;box-shadow: none;}
		.form-modal input[type="text"], .form-modal select {margin-bottom: 20px;}
		textarea#text_coment {font-size: 14px;line-height: 18px;}
	</style>

	<script>

		$(function () {
		  $('[data-bs-toggle="tooltip"]').tooltip()
		});

		$('#form-datos').submit(function(e) {
			e.preventDefault();
		});

		$('#aceptar').click(function(e) {

			if ( valida_form() ) {

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
			}
		});

	function valida_form () {

      if( $( '#comentario').val()=='' ) {
				muestraMensajeLn('El campo comentario esta vacío.');
				return false;
			}
			else {
					return true;
			}
		}

	</script>
