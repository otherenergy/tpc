<?php
  $pagina = "Estadisticas";
  $title = "Estadisticas | Smartcret";
  $description = "Página de estadisticas de ventas y otros valores de la tienda";
	include('./includes/header.php');


	$meses = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

	$tipo_grafico = 'bar';
	$label_meses = array();


	$color_pedidos = "#80a2e9";
	$color_importes = "#92bf23";
	$color_kits = "#f1d65c";
	$color_productos = "#ff6347";


	$sql_colores = "SELECT * FROM `listado_colores`C, colores_hex H WHERE C.COLOR = H.color";
	// $sql_colores = "SELECT * FROM `listado_colores` ORDER BY kits DESC";

	$res_colores=consulta($sql_colores, $conn);
	$importes = array();
	$colores = array();
	$back_colores = array();
	while ( $reg=$res_colores->fetch_object() ) {
		array_push ( $importes, $reg->kits );
		array_push ( $colores, $reg->COLOR );
		array_push ( $back_colores, $reg->hex );
	}

	$data = array (
								"labels" => $colores,
								"datasets" => array (
																array(
																		  "label" => "Colores",
																		  "data"	=> $importes,
																		  "backgroundColor" => $back_colores
																		)
															)
	);

	$tipo_grafico = 'bar';


$datos_grafico = json_encode ( $data );


?>

	<body class="estadisticas listado-pedidos">
		<style>
			button.print_btn {
			   margin-bottom: 10px;
			}
		</style>
	<!-- Header - Inicio -->
	<div class="container">
		<div class="row">
			<div class=".col-md-10 off-set-1">

			</div>
		</div>
	</div>
		<!-- Header - Fin -->
<div class="container listado datos pedidos princ">

	<div class="row">
		<?php include ('./includes/menu_lateral.php') ?>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<div class="col-md-1"><div class="sepv"></div></div>
		<div class="col-md-9">
			<h1 class="center"><?php echo $pagina . ' ' .  $_GET['muestra'] ?></h1>


			<?php if ( $_GET['muestra'] == 'colores') { ?>
				<div class="sep20"></div>
				<h3 style="color: #35471e">Colores de kits más vendidos</h3>
				<div class="sep30"></div>

		  <?php } ?>

			<?php if ( $_GET['muestra'] == 'importes_dia') { ?>

				<div class="sep20"></div>
				<div style="text-align: center;">
					<label>Mostrar meses: </label>
					<select name="meses" id="meses">
						<option value="1" <?php echo ($meses == 1) ? 'selected' : ''; ?>>1</option>
						<option value="2" <?php echo ($meses == 2) ? 'selected' : ''; ?>>2</option>
						<option value="3" <?php echo ($meses == 3) ? 'selected' : ''; ?>>3</option>
						<option value="4" <?php echo ($meses == 4) ? 'selected' : ''; ?>>4</option>
						<option value="5" <?php echo ($meses == 5) ? 'selected' : ''; ?>>5</option>
						<option value="6" <?php echo ($meses == 6) ? 'selected' : ''; ?>>6</option>
						<option value="7" <?php echo ($meses == 7) ? 'selected' : ''; ?>>7</option>
						<option value="8" <?php echo ($meses == 8) ? 'selected' : ''; ?>>8</option>
						<option value="9" <?php echo ($meses == 9) ? 'selected' : ''; ?>>9</option>
						<option value="10" <?php echo ($meses == 10) ? 'selected' : ''; ?>>10</option>
						<option value="11" <?php echo ($meses == 11) ? 'selected' : ''; ?>>11</option>
						<option value="12" <?php echo ($meses == 12) ? 'selected' : ''; ?>>12</option>

					</select>
				</div>
				<div class="sep30"></div>

				<script>
				    $(function(){
				      $('#meses').on('change', function () {
				          var url = 'estadisticas?muestra=importes_dia&meses=' + $(this).val(); // get selected value
				          if (url) { // require a URL
				              window.location = url; // redirect
				          }
				          return false;
				      });
				    });
				</script>

		  <?php } ?>


			<?php if ( $_GET['muestra'] == 'colores_mensual_todos') { ?>

				<div class="sep20"></div>
				<div style="text-align: center;">
					<label for="colorDeseado">Seleccione un color:</label>
					<select name="colorDeseado" id="colorDeseado">
						<option value="Todos" <?php echo ($colorDeseado == 'Todos') ? 'selected' : ''; ?>>Todos</option>
						<?php
						$sql_activos = "SELECT valor FROM atributos WHERE activo = 1 AND nombre = 'color' AND LENGTH(valor) = 2;";
						$res = consulta($sql_activos, $conn);

						if ($res && $res->num_rows > 0) {
							while($fila = $res->fetch_assoc()) {
								$valor = $fila['valor'];
								$selected = ($colorDeseado == $valor) ? 'selected' : '';
								echo "<option value='$valor' $selected>$valor</option>";
							}
						}
						?>
					</select>

					<label for="tipoGrafico">Tipo de Gráfico:</label>
					<select name="tipoGrafico" id="tipoGrafico">
						<option value="line" <?php echo $tipo_grafico == 'line' ? 'selected' : ''; ?>>Línea</option>
						<option value="bar" <?php echo $tipo_grafico == 'bar' ? 'selected' : ''; ?>>Barra</option>
					</select>
				</div>

				<div class="sep30"></div>

				<script>
					$(function(){
						$('#colorDeseado').on('change', function () {
							var url = 'estadisticas?muestra=colores_mensual_todos&meses=' + $('#meses').val() + '&colorDeseado=' + $(this).val() + '&t=' + new Date().getTime();
							if (url) { // require a URL
								window.location = url; // redirect
							}
							return false;
						});
					});

					// script tipo de gráfico
					document.getElementById('tipoGrafico').addEventListener('change', function() {
						var tipoGrafico = this.value;
						var url = new URL(window.location.href);
						url.searchParams.set('tipoGrafico', tipoGrafico); // actualiza el parametro tipoGrafico en la URL
						window.location.href = url; // redirige a la nueva URL
					});
				</script>

		  	<?php } ?>

		<canvas id="grafica"></canvas>

    <div class="sep50"></div>

    <?php if ( $_GET['muestra'] == 'colores') {

    	$sql_colores_no = "SELECT distinct A.valor as color_id, H.hex FROM productos P, atributos A, colores_hex H where P.publicado=1 AND P.es_variante='3' and A.id = P.color AND A.valor NOT IN (SELECT COLOR FROM listado_colores) AND H.color = A.valor";

    	$res_colores_no =consulta($sql_colores_no, $conn);

    	?>

    	<div class="colores_no_vendidos">

    		<h3 style="color: #35471e">Colores no vendidos</h3>
    		<div class="sep20"></div>
	    		<div class="row">

	    		<?php

	    			while ( $reg=$res_colores_no->fetch_object() ) {
	    				echo "<div class='col-md-2'>";
							echo '<div class="item"><img class="colore_no" src="../assets/img/colores/' . $reg->color_id . '.jpg"><div class="col_nom">' . $reg->color_id . '</div> </div>';
							echo "</div>";
						}

	    		?>

	    	</div>
    	</div>


    <?php } ?>

		</div>
	</div>
	</div>
	<div class="sep40"></div>

	<div class="modal fade" id="pedidos-cliente">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>



	<?php  include ('./includes/footer.php') ?>

	<div class="sep60"></div>

	</body>
</html>



<script>
	$('.item.color_mas').addClass('menu_actual');
	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}

	setTimeout(function() {
		$('.print_btn').appendTo('.dt-buttons').addClass('dt-button buttons-html5');
	},1000);

	function checkScroll(scroll) {
		if (scroll > 120) {
			var left = $('.menu-lat').width()+$(window).width()*0.09;
			$('h1').addClass("fixed borde_sombra").css('left', left + 'px');
		}
		else {
			$('h1').removeClass("fixed borde_sombra");
		}
	}

	const labels = <?php echo json_encode ( $label_meses ) ?>;
	const graph = document.querySelector("#grafica");

	const data = <?php echo $datos_grafico ?>;

	const config = {
	    type: '<?php echo $tipo_grafico; ?>',
	    data: data,
	    options: {
			plugins: {
				ingraphdatashow: true,
				legend: {
					labels: {
						// This more specific font property overrides the global property
						font: {
							size: 20
						}
					}
				}
			},
		}
	}


	new Chart(graph, config);

</script>