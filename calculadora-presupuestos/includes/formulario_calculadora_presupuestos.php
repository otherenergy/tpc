<?php

$tipo_presupuesto = $_POST['tipo_presupuesto'];
$title = $_POST['title'];

?>

<div class="">
	<form action="calculo_presupuesto" id="form-datos" class="form_datos_perso" method='POST' >
		<input type="hidden" id="input_tipo_pres" name="input_tipo_pres" value="<?php echo $tipo_presupuesto ?>">
		<input type="hidden" id="input_presupuesto" name="input_presupuesto" value="1">

		<div class="sep10"></div>

		<div class="form-group col-md-12 paso0">
			<h2>1. ¿Cuantos metros cuadrados?</h2>
			<div class="metros2">
				<div class="num_metros">
					<input class="input_num" placeholder="0.00" type="number" min="0" max="200" step="0.25">
				</div>
				<div class="m2">m<sup>2</sup></div>
			</div>
			<input type="range" class="slider" id="input_m2" name="input_m2" min="0" max="200" step="0.25" value="0">
		</div>

<!--
		<div class="form-group col-md-12 paso0">
			<h2>1. ¿Cuantos metros cuadrados?</h2>
			<div class="metros2">
				<div class="num_metros">0</div>
				<div class="m2">m<sup>2</sup></div>
			</div>
			<input type="range" class="slider" id="input_m2" name="input_m2" min="0" max="200" step="0.25" value="0">
		</div> -->

<?php

if ( $tipo_presupuesto == 1) { ?>

			<div class="form-group col-md-12 paso2">
				<h2>2. Selecciona tipo de superficie</h2>
				<select type="text" class="form-control" id="input_tipo_superficie" name="input_tipo_superficie">
					<option value="0">-- Seleccionar superficie --</option>
					<option value="1">Absorbente ( Yeso, pladur, etc )</option>
					<option value="2">NO Absorbente ( Azulejo, gres, terrazo, marmol, etc)</option>
				</select>
			</div>

			<div class="form-group col-md-12 paso3 pas_color">
				<h2>3. Selecciona color</h2>
				<div class="cuadro_select color_micro" onclick="openColores( 1, 'Microcemento listo al uso' )">
					-- Seleccionar color --
				</div>
				<input id="input_color" type="hidden" name="input_color" value="0">
				<input id="input_id_color" type="hidden" name="input_id_color" value="0">
			</div>

			<div class="form-group col-md-12 paso4 pas_acab">
				<h2>4. Selecciona tipo de acabado</h2>
				<select type="text" class="form-control" id="input_tipo_acabado" name="input_tipo_acabado">
					<option value="0">-- Seleccionar acabado --</option>
					<option value="1">Mate</option>
					<option value="2">Satinado</option>
				</select>
			</div>

			<div class="form-group col-md-12 paso5 pas_juntas">
				<h2>5. Selecciona tipo de juntas</h2>
				<select type="text" class="form-control" id="input_tipo_juntas" name="input_tipo_juntas">
					<option value="0">-- Seleccionar tipo juntas --</option>
					<option value="39">0 - 2 mm</option>
					<option value="40">2 - 5 mm</option>
					<option value="41">5 - 10 mm</option>
				</select>
			</div>

			<div class="form-group col-md-12 paso7 pas_herra">
				<h2>¿Necesitas herramientas?</h2>
				<select type="text" class="form-control" id="input_herramientas" name="input_herramientas">
					<option value="0">-- Seleccionar --</option>
					<option value="1">SI, añadir un kit de herramientas</option>
					<option value="2">NO</option>
				</select>
			</div>

			<div class="sep10"></div>

			<div class="form-group col-md-12 paso6 pas__enviar">
				<button class="microcemento_cta calcular" type="submit">Calcular presupuesto <i class="fa fa-calculator"></i></button>
			</div>

			</form>
		</div>
	</div>


<?php }

 // inicio presupuesto pintura
if ( $tipo_presupuesto == 2) { ?>

	<div class="form-group col-md-12 paso2 pas_superficie">
		<h2>2. ¿Qué superficie vas a pintar?</h2>
		<select type="text" class="form-control" id="input_tipo_superficie" name="input_tipo_superficie">
			<option value="0">-- Seleccionar superficie --</option>
			<option value="1">Azulejos (cocina, baño, etc)</option>
			<option value="2">Otras superficies ( Yeso, pladur, etc )</option>
		</select>
	</div>

	<div class="form-group col-md-12 paso3 pas_color">
		<h2>3. Selecciona color</h2>
		<div class="cuadro_select color_micro" onclick="openColores( 2, 'Pintura para azulejos Smartcover Tiles' )">
			-- Seleccionar color --
		</div>
		<input id="input_color" type="hidden" name="input_color" value="0">
		<input id="input_id_color" type="hidden" name="input_id_color" value="0">
	</div>

	<div class="sep20"></div>

			<div class="form-group col-md-12 paso6 pas__enviar">
				<button class="microcemento_cta calcular" type="submit">Calcular presupuesto <i class="fa fa-calculator"></i></button>
			</div>

			</form>
		</div>
	</div>

<?php } // fin presupuesto pintura

 // inicio presupuesto reparacion hormigon impreso
if ( $tipo_presupuesto == 3) { ?>

	<div class="form-group col-md-12 paso2 pas_superficie">
		<h2>2. ¿Qué necesitas?</h2>
		<select type="text" class="form-control" id="input_tipo_hormigon" name="input_tipo_superficie">
			<option value="0">-- Seleccionar --</option>
			<option value="1">Mantenimiento ( solo barniz )</option>
			<option value="2">Reparación ( mortero reparador + barniz )</option>
		</select>
	</div>

	<div class="form-group col-md-12 paso3 pas_color">
		<h2>3. Selecciona color</h2>
		<div class="cuadro_select color_micro" onclick="openColores( 3, 'Reparación hormigón impreso' )">
			-- Seleccionar color --
		</div>
		<input id="input_color" type="hidden" name="input_color" value="0">
		<input id="input_id_color" type="hidden" name="input_id_color" value="0">
	</div>



	<div class="sep20"></div>

			<div class="form-group col-md-12 paso6 pas_enviar">
				<button class="microcemento_cta calcular" type="submit">Calcular presupuesto <i class="fa fa-calculator"></i></button>
			</div>

			</form>
		</div>
	</div>

<?php } // fin presupuesto reparacion hormigon impreso ?>


<div class="modal fade" id="colores-presupuesto">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<div class="sep30"></div>


<script>
		var primero=true;

		$(document).ready(function() {
			$('#input_tipo_superficie').change(function(event) {
				$('.paso3, .paso4, .paso5, .paso6').fadeOut();
				$('.paso3').fadeIn();
			});
		});

		function openColores( tipo_presupuesto, title ) {
			var myModal = new bootstrap.Modal(document.getElementById('colores-presupuesto'), {
				keyboard: false
			})
			$.ajax({
				url: './includes/colores_calculadora_presupuesto.php',
				type: 'POST',
				datatype: 'html',
				data: { tipo_presupuesto:tipo_presupuesto, title:title }
			})
			.done(function(result) {
				$('.modal-body').html(result);
				myModal.show();
			})
			.fail(function() {
				alert('Se ha producido un error');
			})
		}

		if (primero) {
			$('#input_tipo_acabado').change(function(event) {
				if ( $('#input_tipo_superficie').val() == 2) {
					$('.paso5').fadeIn();
				}else if ( $('#input_tipo_superficie').val() == 1 ) {
					$('.paso7').fadeIn();
				}
				primero=false;
			});
		}else {
			$('#input_tipo_acabado').click(function(event) {
				if ( $('#input_tipo_superficie').val() == 2) {
					$('.paso5').fadeIn();
				}else if ( $('#input_tipo_superficie').val() == 1 ) {
					$('.paso7').fadeIn();
				}
			});
		}

		$('#input_tipo_juntas').change(function(event) {
				$('.paso7').fadeIn();
		});

		$('#input_herramientas').change(function(event) {
				$('.paso6').fadeIn();
		});

		$('form').submit(function(e) {
			e.preventDefault();
			$('.procesando').fadeIn();
			$('html').addClass('no-scroll');
			$.ajax({
				url: 'includes/calculo_presupuesto.php',
				type: 'POST',
				datatype: 'html',
				data: $('form').serialize()
			})
			.done(function(result) {
				$('#resultado_presupuesto').html(result).fadeIn(600);
				$('.procesando').fadeOut();
			  $('html').removeClass('no-scroll');
			  if( $(window).width() <= 768 ) {
			  	scrollToId('resultado_presupuesto');
			  }
			})
			.fail(function() {
				alert('Se ha producido un error');
			})
		});

		$('#input_tipo_hormigon').change(function(event) {

			if ( $(this).val() == 2) {
				$('.paso3').fadeIn();
			}else if ( $(this).val() == 1 ) {
				$('.paso3').fadeOut();
				$('.paso6').fadeIn();
			}
		});



	</script>




