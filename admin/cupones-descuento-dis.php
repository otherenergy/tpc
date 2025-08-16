<?php
$pagina = "Cupones descuentos Smartcret";
$title = "Cupones descuentos | Smartcret";
$description = "Página listado de cupones descuentos";
include('./includes/header.php');
$activo = $_GET['activo'];

$_SESSION['distribuidor'] = 1;
// $nombres_descuentos = fn_obtener_nombres_descuentos();
// var_dump($nombres_descuentos);
?>

<body class="listado-pedidos">
	<!-- Header - Inicio -->
	<div class="container">
		<div class="row">
			<div class=".col-md-10 off-set-1"></div>
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
						<button class="enlace_descuento_distribuidor" >
							<a href="./cupones-descuento?activo=0">Descuentos para clientes</a>	
						</button>
						<button id="new" url="form_descuento" onclick="openModal( $(this).attr('url'), 0 )" title="Crear nuevo descuento">Nuevo descuento<i class="fa fa-plus"></i>
						</button>
					</div>
				</div>
				<div class="sep20"></div>
				<div class="btns_funciones">
					<?php if ($activo == 0) { ?>
						<button class="print_btn me-2" onclick="window.location.href='cupones-descuento?activo=1'">Ver descuentos activos<i class="fas fa-check-square on ps-1"></i></button>
					<?php } else { ?>
						<button class="print_btn me-2" onclick="window.location.href='cupones-descuento?activo=0'">Ver todos los descuentos<i class="fa fa-times on ps-1"></i></button>
					<?php } ?>
					<button class="print_btn" onclick="$('h1, #tbl_pedidos').printThis({
					        importCSS: true,
					        importStyle: true,
					        loadCSS: false,
					        canvas: false
						    })">Imprimir <i class="fa fa-print"></i>
					</button>
				</div>

				<div class=" table-responsive">
					<table id="tbl_pedidos" class="table carrito dir_fac">
						<thead>
							<tr>
								<th class="izq des">Usuario</th>
								<th class="izq des">Descuento</th>
								<th class="izq des">Creado</th>
								<th class="izq des">Utilizado</th>
								<th class="izq des">Descripcion / Comentarios</th>
								<th class="izq val">Valor</th>
								<th class="izq uso">Max usos / usuario</th>
								<th class="izq uso">Aplicación</th>
								<th class="izq fecini">Fecha inicio</th>
								<th class="izq fecfin">Fecha fin</th>
								<th class="izq act">Activo</th>
								<th class="izq info"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							if ($activo == 0) {
								$sql = "SELECT cd.*, u.email FROM cupones_descuento cd INNER JOIN users u ON u.uid= cd.distribuidor WHERE cd.eliminado = 0 AND cd.distribuidor IS NOT NULL ORDER BY cd.fecha_creacion DESC";
							} else {
								$sql = "SELECT cd.*, u.email FROM cupones_descuento cd INNER JOIN users u ON u.uid= cd.distribuidor WHERE cd.activo = 1 AND cd.eliminado = 0 AND cd.distribuidor IS NOT NULL ORDER BY cd.fecha_creacion DESC";
							}


							$res = consulta($sql, $conn);
							if (numFilas($res) > 0) {
								while ($reg = $res->fetch_object()) {
							?>
									<tr class="datos pedidos">
										<td class="izq nom">
											<?php echo $reg->email ?>
										</td>
										<td class="izq nom">
											<?php echo $reg->nombre_descuento ?>
										</td>
										<td class="izq nom">
											<?php echo '<span style="display:none">' . cambia_fecha_tabla($reg->fecha_creacion) . '</span>' ?>
											<?php echo cambia_fecha_slash($reg->fecha_creacion) ?>
										</td>
										<td class="izq num">
											<?php 
											// echo $reg->id;
											$num = obten_num_descuento_utilizado($reg->id); ?>
											<span id="num_usos" <?php if ($num > 0) { ?> class="activ" data-bs-toggle="tooltip" data-bs-placement="top" url="uso_descuento" onclick="openModal( $(this).attr('url'), <?php echo $reg->id ?> )" title="Ver usos descuento <?php echo $reg->nombre_descuento ?>" <?php } ?>>
												<?php
												echo ($num == 1) ? $num . ' uso' : $num . ' usos';
												?>
											</span>
										</td>
										<td class="izq des">
											<?php echo $reg->comentario ?>
										</td>
										<td class="izq val">
											<?php echo $reg->valor . ' ' . $reg->tipo ?>
										</td>
										<td class="izq uso">
											<?php echo $reg->uso_persona  ?>
										</td>
										<td class="izq uso">
											<?php echo obten_aplicacion_descuento($reg->aplicacion_descuento)->aplicacion_texto  ?>
										</td>
										<td class="izq uso">
											<?php echo ($reg->fecha_inicio != null || $reg->fecha_inicio != '') ? obten_fecha($reg->fecha_inicio) : '-'  ?>
											<?php echo "<br>" ?>
											<?php echo ($reg->fecha_inicio != null || $reg->fecha_inicio != '') ? obten_hora($reg->fecha_inicio) : ''  ?>
										</td>
										<td class="izq uso">
											<?php echo ($reg->fecha_fin != null || $reg->fecha_fin != '') ? obten_fecha($reg->fecha_fin) : '-'  ?>
											<?php echo "<br>"; ?>
											<?php echo ($reg->fecha_fin != null || $reg->fecha_fin != '') ? obten_hora($reg->fecha_fin) : ''  ?>
										</td>
										<td class="izq act ">
											<span style="display: none"><?php echo $reg->activo ?></span>
											<span class="act_des" onclick="cambiaEstado( 'descuento', <?php echo $reg->id ?>, <?php echo $reg->activo ?> );">
												<?php echo ($reg->activo == 1) ? '<i class="fas fa-check-square on"></i>' : '<i class="far fa-square"></i>' ?>
											</span>
										</td>
										<td class="izq info">
											<button class="cart_btn btn-tabla" url="form_descuento" onclick="openModal( $(this).attr('url'), <?php echo $reg->id ?> )" title="Editar el descuento <?php echo $reg->nombre_descuento ?>">
												<i class="fa fa-edit"></i>Editar
											</button>
										</td>
									</tr>
								<?php }
							} else { ?>
								<tr class="info">
									<td valign="center" colspan="7" style="height: 80px;vertical-align: middle;text-align: center;">No hay clientes</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="sep40"></div>

	<div class="modal fade" id="cupon-descuento">
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
					[1, 'desc']
				]

			});
		});
	</script>
</body>

</html>

<script>
	$('.item.cup').addClass('menu_actual');
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

	function openModal(url, id_descuento = 0) {
		var myModal = new bootstrap.Modal(document.getElementById('cupon-descuento'), {
			keyboard: false
		})
		$.ajax({
				url: './includes/' + url + '.php',
				type: 'POST',
				datatype: 'html',
				data: {
					id_descuento: id_descuento
				}
			})
			.done(function(result) {
				$('#cupon-descuento .modal-body').html(result);
				myModal.show();

			})
			.fail(function() {
				alert('Se ha producido un error');
			})
	}

	function eliminaDescuento(id_descuento) {
		$.ajax({
				url: './assets/lib/admin_control.php',
				type: 'post',
				dataType: 'text',
				data: {
					'accion': 'elimina_descuento',
					'id_descuento': id_descuento
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

	// $('.act_des').click(function(e) {
	// 	$('i', this).toggleClass('fa-check-square fa-square far fas on');
	// });

	// function cambiaEstadoDescuento ( id_descuento, estado_descuento ) {
	// 	$.ajax({
	// 		url: './assets/lib/admin_control.php',
	// 		type: 'post',
	// 		dataType: 'text',
	// 		data: {'accion': 'cambia_estado_descuento',
	// 					 'id_descuento': id_descuento,
	// 					 'estado_descuento': estado_descuento
	// 					}
	// 	})
	// 	.done(function(result) {
	// 		var result = $.parseJSON(result);
	// 		if(result.res==0) {
	// 			muestraMensajeLn(result.msg);
	// 		}else if (result.res==1) {
	// 			muestraMensajeLn(result.msg);
	// 			setTimeout(function() {
	// 				location.reload();
	// 			},2000);
	// 		}
	// 	})
	// 	.fail(function() {
	// 		alert("error");
	// 	});
	// }
</script>