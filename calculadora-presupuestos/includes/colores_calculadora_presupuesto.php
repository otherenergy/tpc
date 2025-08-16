<?php
include_once('../../assets/lib/bbdd.php');
include_once('../../assets/lib/funciones.php');

$tipo_presupuesto = $_POST['tipo_presupuesto'];
$title = $_POST['title'];


?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit">Colores de <?php echo $title ?> </p>

<div class="row form-modal">
	<form id="form-datos" class="form_datos_perso" method='POST' >
		<div class="row">
			<div class="sep10"></div>

			<div class="form-group col-md-12">
				<h2>Selecciona color</h2>
				<div class="colores">

					<?php

						if ($tipo_presupuesto == 1 ) {

							$res = consulta_colores(1);
							$lista_colores = [];
							while($reg=$res->fetch_object()) { 
								if (!in_array($reg->valor, $lista_colores)) {
									$lista_colores[] = $reg->valor;
						?>
								<div class="color">
									<label class="sel-color-calculadora" id_color="<?php echo $reg->color_id ?>" color="<?php echo $reg->valor ?>">
										<img src="../assets/img/colores/<?php echo $reg->valor ?>.jpg" alt="Color <?php echo $reg->valor ?>" title="Color <?php echo $reg->valor ?>" class="variacion microcemento">
										<div style="text-align: center;"><small><?php echo $reg->valor ?></small></div>
									</label>
								</div>
							<?php } 
							}
							?>

						<script>

							$('.sel-color-calculadora').click(function(event) {
								$('.color_micro').html( '<div class="select_color" style="background-image:url(../assets/img/colores/' + $(this).attr('color') + '.jpg)"><span class="tit_color">' + $( 'img', this ).attr('title') + '</span></div>');
								$('#input_color').val( $(this).attr('color') );
								$('#input_id_color').val( $(this).attr('id_color') );
								$('button.close').click();
								$('.paso4').fadeIn();
							});

						</script>

						<?php
					}

					if ($tipo_presupuesto == 2 ) {

						$res = consulta_colores(23);
						$lista_colores = [];
						while($reg=$res->fetch_object()) { 
							if (!in_array($reg->valor, $lista_colores)) {
								$lista_colores[] = $reg->valor;
					?>
							<div class="color">
								<label class="sel-color-calculadora" id_color="<?php echo $reg->color_id ?>" color="<?php echo $reg->valor ?>">
									<img src="../assets/img/colores_smartcover/maxi/<?php echo str_replace ( 'sc0' , 'sc' , strtolower($reg->valor) ) ?>.jpg" alt="Color <?php echo $reg->valor ?>" title="Color <?php echo $reg->valor ?>" class="variacion pintura">
									<div style="text-align: center;"><small><?php echo $reg->valor ?></small></div>
								</label>
							</div>
						<?php } 
						}?>

						<script>

							$('.sel-color-calculadora').click(function(event) {
								$('.color_micro').html( '<div class="select_color" style="background-image:url(../assets/img/colores_smartcover/maxi/' + $(this).attr('color') + '.jpg)"><span class="tit_color">' + $( 'img', this ).attr('title') + '</span></div>');
								$('#input_color').val( $(this).attr('color') );
								$('#input_id_color').val( $(this).attr('id_color') );
								$('button.close').click();
								$('.paso6').fadeIn();
							});

						</script>
						<?php
					}

					if ($tipo_presupuesto == 3 ) {

						$res = consulta_colores(396);
						while($reg=$res->fetch_object()) { ?>
							<div class="color">
								<label class="sel-color-calculadora" id_color="<?php echo $reg->color_id ?>" color="<?php echo $reg->valor ?>">
									<img src="../assets/img/colores-smartcover-repair/<?php echo $reg->valor ?>.jpg" alt="Color <?php echo $reg->valor ?>" title="Color <?php echo $reg->valor ?>" class="variacion impreso">
									<div style="text-align: center;"><small><?php echo $reg->valor ?></small></div>
								</label>
							</div>
						<?php } ?>

						<script>

							$('.sel-color-calculadora').click(function(event) {
								$('.color_micro').html( '<div class="select_color" style="background-image:url(../assets/img/colores-smartcover-repair/<?php echo $reg->valor ?>' + $(this).attr('color') + '.jpg)"><span class="tit_color">' + $( 'img', this ).attr('title') + '</span></div>');
								$('#input_color').val( $(this).attr('color') );
								$('#input_id_color').val( $(this).attr('id_color') );
								$('button.close').click();
								$('.paso6').fadeIn();
							});

						</script>
						<?php
					}

					?>

				</div>
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



	<style>
		img.variacion {
	    width: 42px!important;
	    border: 3px solid #fff;
	  }
	  img.variacion.impreso {
		  width: 67px!important;
		  border: 3px solid #fff;
		}

	</style>
