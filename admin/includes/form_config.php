<?php
include_once('../../assets/lib/bbdd.php');
include_once('../../assets/lib/funciones.php');

?>

<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit">Opciones de configuración</p>

<div class="row form-modal">
	<form id="form-config" class="form_datos_perso" method='POST' >
		<div class="row">
			<div class="sep10"></div>
				<div class="form-group col-md-12">
					<table class="conf">

						<?php

						$sql="SELECT * FROM configuracion ORDER BY orden ASC";
						$res=consulta( $sql, $conn );
							while( $reg=$res->fetch_object() ) { ?>
								<tr class="line_conf">
									<td> <?php echo $reg->accion ?></td>
									<td align="center ">
										<span class="act_des act" onclick="cambiaEstadoConfiguracion( <?php echo $reg->id ?> )">
											<?php echo ( $reg->activado == 1 ) ? '<i class="fas fa-check-square on"></i>' : '<i class="far fa-square"></i>' ?></td>
										</span>
								</tr>
							<?php } ?>
					</table>
				</div>
			</div>
			<div class="sep30"></div>
			<div class="btns">
				<input type="hidden" name="ref_pedido" value="<?php echo $ref_pedido ?>">
				<input type="hidden" name="accion" value="actualiza_estado_pago">
	  		<button id="aceptar" class="send cart_btn" data-bs-dismiss="modal"><i class="fa fa-check"></i>Aceptar</button>
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

		$('.act_des').click(function(e) {
				$('i', this).toggleClass('fa-check-square fa-square far fas on');
		});

		function cambiaEstadoConfiguracion ( id ) {

			$.ajax({
				url: './assets/lib/admin_control.php',
				type: 'post',
				dataType: 'text',
				data: {"accion": "cambia_estado_configuracion", "id": id}
			})
				.done(function(result) {
					var result = $.parseJSON(result);
					if(result.res==0) {
						muestraMensajeLn(result.msg);
					}else if (result.res==1) {
						muestraMensajeLn(result.msg);
						// setTimeout(function() {
						// 	location.reload();
						// },2000);
					}
				})
				.fail(function() {
					alert("error");
				});
		}





	</script>
