<?php
$pagina = "Avisos web";
$title = "Avisos web | Smartcret";
$description = "Página listado de avisos web";
include('./includes/header.php');
?>

<?php
// echo '<pre>';
// print_r($_REQUEST);
// echo '</pre>';
?>

<body class="listado-pedidos">
	<!-- Header - Inicio -->
	<div class="container">
		<div class="row">
			<div class=".col-md-10 off-set-1">

			</div>
		</div>
	</div>
	<!-- Header - Fin -->
	<div class="container listado datos cupones princ">

		<div class="row">
			<?php include('./includes/menu_lateral.php') ?>
			<div class="col-md-1">
				<div class="sepv"></div>
			</div>
			<div class="col-md-9">
				<div class="row">
					<div class="col-md-5 offset-3">
						<h1 class="center"><?php echo $pagina ?></h1>
					</div>
					<div class="col-md-4" style="text-align: right;">
						<div class="col-md-4" style="text-align: right;">
							<button id="new" url="form_aviso" onclick="openModal( $(this).attr('url'), 0 )" title="Crear nuevo aviso">Nuevo aviso<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
				<div class="sep20"></div>

				<div class=" table-responsive">
					<table id="tbl_pedidos" class="table carrito dir_fac">
						<thead>
							<tr>
								<th class="izq act">Activo</th>
								<th class="izq act">Nombre</th>
								<th class="izq es">Español</th>
								<th class="izq in">Inglés GB</th>
								<th class="izq fr">Francés</th>
								<th class="izq it">Italiano</th>
								<th class="izq it">Alemán</th>
								<th class="izq it">Inglés US</th>
								<th class="izq fecini">Fecha inicio</th>
								<th class="izq fecfin">Fecha fin</th>
								<th class="izq fec">Fecha creación</th>
								<th class="izq info">Editar</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$conn->set_charset("utf8mb4");
							$sql = "SELECT * FROM avisos_web";
							$res = consulta($sql, $conn);
							if (numFilas($res) > 0) {
								while ($reg = $res->fetch_object()) {
							?>
									<tr class="datos avisos">
										<td class="izq act ">
											<span style="display: none;"><?php echo $reg->id ?></span>
											<span class="act_des" onclick="cambiaEstado( 'aviso', <?php echo $reg->id ?>, <?php echo $reg->activo ?> );">
												<?php echo ($reg->activo == 1) ? '<i class="fas fa-check-square on"></i>' : '<i class="far fa-square"></i>' ?>
											</span>
										</td>
										<td class="izq es">
											<?php echo $reg->nombre_aviso ?>
										</td>
										<td class="izq es">
											<?php echo $reg->aviso_es ?>
										</td>
										<td class="izq en">
											<?php echo $reg->aviso_en ?>
										</td>
										<td class="izq fr">
											<?php echo $reg->aviso_fr ?>
										</td>
										<td class="izq it">
											<?php echo $reg->aviso_it ?>
										</td>
										<td class="izq it">
											<?php echo $reg->aviso_de ?>
										</td>
										<td class="izq it">
											<?php echo $reg->aviso_en_us ?>
										</td>
										<td class="izq fecini">
											<?php echo cambia_fecha_hora($reg->fecha_inicio) ?>
										</td>
										<td class="izq fecfin">
											<?php echo cambia_fecha_hora($reg->fecha_fin) ?>
										</td>
										<td class="izq fec">
											<?php echo cambia_fecha_hora($reg->fecha_creacion) ?>
										</td>
										<td class="izq info">
											<button class="cart_btn btn-tabla" url="form_aviso" onclick="openModal( $(this).attr('url'), <?php echo $reg->id ?> )" title="Editar aviso <?php echo $reg->nombre_aviso ?>">
												<i class="fa fa-edit"></i>Editar
											</button>
										</td>
									</tr>
								<?php }
							} else { ?>
								<tr class="info">
									<td valign="center" colspan="7" style="height: 80px;vertical-align: middle;text-align: center;">No hay avisos</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>

			</div>

		</div>
	</div>
	<div class="sep40"></div>

	<div class="modal fade" id="aviso-web">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<?php include('./includes/footer.php') ?>
	<script>
		$(document).ready(function() {
			$('#tbl_pedidos').DataTable({
				language: {
					url: 'includes/es-ES.json',
				},
				"pageLength": 25,
				"order": [
					[8, 'desc']
				]
			});
		});
	</script>
</body>
<style>
	table#tbl_pedidos tr>td {
		font-size: 12px !important;
		line-height: 16px;
	}

	select {
		-webkit-appearance: none;
	}
</style>

</html>

<script>
	$('.item.avis').addClass('menu_actual');
	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}

	function checkScroll(scroll) {
		if (scroll > 120) {
			var left = $('.menu-lat').width() + $(window).width() * 0.09;
			$('h1').addClass("fixed borde_sombra").css('left', left + 'px');
		} else {
			$('h1').removeClass("fixed borde_sombra");
		}
	}

	function openModal(url, id_aviso = 0) {
		var myModal = new bootstrap.Modal(document.getElementById('aviso-web'), {
			keyboard: false
		})
		$.ajax({
				url: './includes/' + url + '.php',
				type: 'POST',
				datatype: 'html',
				data: {
					id_aviso: id_aviso
				}
			})
			.done(function(result) {
				$('#aviso-web .modal-body').html(result);
				myModal.show();
			})
			.fail(function() {
				alert('Se ha producido un error');
			})
	}

	function eliminaAviso(id_aviso) {
		$.ajax({
				url: './assets/lib/admin_control.php',
				type: 'post',
				dataType: 'text',
				data: {
					'accion': 'elimina_aviso',
					'id_aviso': id_aviso
				}
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
	}
</script>