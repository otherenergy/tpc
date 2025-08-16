<?php
  $pagina = "Preparación de pedidos Smartcret";
  $title = "Preparación de pedidos | Smartcret";
  $description = "Página listado de preparación de pedidos";
	include('./includes/header.php');


$importe_total = obten_ventas_no_vies ();
$muestra_mensaje = false;

$iva_es = obten_iva_books ( "ES" );

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
<div class="container listado datos pedidos princ">

	<div class="row">
		<?php include ('./includes/menu_lateral.php') ?>
		<div class="col-md-1"><div class="sepv"></div></div>
		<div class="col-md-9">

			<h1 class="center"><?php echo $pagina ?>

				<button class="print_btn lista" onclick="window.location.href='./preparacion-pedidos?v=todos'">Ver listado completo<i class="fa fa-list"></i></button>

			</h1>

			<div class="btns_funciones">
				<button class="print_btn"
								onclick="$('h1, #tbl_pedidos').printThis({
					        importCSS: true,
					        importStyle: true,
					        loadCSS: true,
					        canvas: true
						    })"
						    >Imprimir <i class="fa fa-print"></i>
				</button>

				<div class="btns_funciones center">
					<button class="print_btn lista " onclick="window.location.href='./preparacion-pedidos?v=Pendiente'">Pendientes<i class="fas fa-exclamation"></i></button>
					<button class="print_btn lista " onclick="window.location.href='./preparacion-pedidos?v=Enviado'">Enviados<i class="fas fa-check"></i></button>
					<button class="print_btn lista " onclick="window.location.href='./preparacion-pedidos'">Todos [200]<i class="fas fa-ellipsis-v"></i></button>
				</div>


			</div>

			<div class=" table-responsive">
				<table id="tbl_pedidos" class="prep_pedidos table carrito dir_fac ">
					<thead>
						<tr>
							<th></th>
							<th class="izq ref">Ref</th>
							<th class="izq ped_fecha">Fecha</th>
							<th class="izq ped_fecha">Pais envío</th>
							<th class="izq">Detalle pedido</th>
							<th class="izq ped_prep">Preparación</th>
							<th class="izq ped_doc">Documentación</th>
							<th class="izq ped_env">Envío</th>
							<th class="izq ped_env">Comentarios</th>

						</tr>
					</thead>
					<tbody>
						<?php

						if ( $_GET['v'] == 'Pendiente' || $_GET['v'] == 'Enviado' ) {

							if( $_GET['v'] == 'Pendiente' ) $orden='ASC';
							else $orden='DESC';

							$sql = "
												SELECT
												    pedidos.id,
													pedidos.id_envio,
												    pedidos.id_cliente,
												    pedidos.ref_pedido,
												    pedidos.fecha_creacion,
												    pedidos.estado_envio,
												    COALESCE(sc_estado_pedidos.preparacion, 0) AS preparacion,
												    COALESCE(sc_estado_pedidos.fecha_preparacion, '0000-00-00 00:00:00') AS fecha_preparacion,
												    COALESCE(sc_estado_pedidos.documentacion, 0) AS documentacion,
												    COALESCE(sc_estado_pedidos.fecha_documentacion, '0000-00-00 00:00:00') AS fecha_documentacion,
												    COALESCE(sc_estado_pedidos.envio, 0) AS envio,
												    COALESCE(sc_estado_pedidos.fecha_envio, '0000-00-00 00:00:00') AS fecha_envio,
												    COALESCE(comentarios.num_filas, 0) AS num_filas
												FROM
												    pedidos
												LEFT JOIN
												    sc_estado_pedidos ON pedidos.id = sc_estado_pedidos.pedido_id
												LEFT JOIN (
												    SELECT
												        id_pedido,
												        COUNT(*) AS num_filas
												    FROM
												        sc_comentarios_preparacion
												    GROUP BY
												        id_pedido
												) comentarios ON pedidos.id = comentarios.id_pedido
												WHERE pedidos.estado_envio = '" . $_GET['v'] . "' AND pedidos.cancelado=0
												ORDER BY
												    fecha_envio DESC
												LIMIT 500;
												";


						}
						else if ( $_GET['v'] == 'todos' ) {
								$sql = "
												SELECT
												    pedidos.id,
													pedidos.id_envio,
												    pedidos.id_cliente,
												    pedidos.ref_pedido,
												    pedidos.fecha_creacion,
												    pedidos.estado_envio,
												    COALESCE(sc_estado_pedidos.preparacion, 0) AS preparacion,
												    COALESCE(sc_estado_pedidos.fecha_preparacion, '0000-00-00 00:00:00') AS fecha_preparacion,
												    COALESCE(sc_estado_pedidos.documentacion, 0) AS documentacion,
												    COALESCE(sc_estado_pedidos.fecha_documentacion, '0000-00-00 00:00:00') AS fecha_documentacion,
												    COALESCE(sc_estado_pedidos.envio, 0) AS envio,
												    COALESCE(sc_estado_pedidos.fecha_envio, '0000-00-00 00:00:00') AS fecha_envio,
												    COALESCE(comentarios.num_filas, 0) AS num_filas
												FROM
												    pedidos
												LEFT JOIN
												    sc_estado_pedidos ON pedidos.id = sc_estado_pedidos.pedido_id
												LEFT JOIN (
														SELECT
														id_pedido,
														COUNT(*) AS num_filas
														FROM
														sc_comentarios_preparacion
														GROUP BY
														id_pedido
														) comentarios ON pedidos.id = comentarios.id_pedido
														WHERE pedidos.cancelado=0
												ORDER BY
												    pedidos.ref_pedido DESC
												";

						}else {

								$sql = "
												SELECT
												    pedidos.id,
													pedidos.id_envio,
												    pedidos.id_cliente,
												    pedidos.ref_pedido,
												    pedidos.fecha_creacion,
												    pedidos.estado_envio,
												    COALESCE(sc_estado_pedidos.preparacion, 0) AS preparacion,
												    COALESCE(sc_estado_pedidos.fecha_preparacion, '0000-00-00 00:00:00') AS fecha_preparacion,
												    COALESCE(sc_estado_pedidos.documentacion, 0) AS documentacion,
												    COALESCE(sc_estado_pedidos.fecha_documentacion, '0000-00-00 00:00:00') AS fecha_documentacion,
												    COALESCE(sc_estado_pedidos.envio, 0) AS envio,
												    COALESCE(sc_estado_pedidos.fecha_envio, '0000-00-00 00:00:00') AS fecha_envio,
												    COALESCE(comentarios.num_filas, 0) AS num_filas
												FROM
												    pedidos
												LEFT JOIN
												    sc_estado_pedidos ON pedidos.id = sc_estado_pedidos.pedido_id
												LEFT JOIN (
												    SELECT
												        id_pedido,
												        COUNT(*) AS num_filas
												    FROM
												        sc_comentarios_preparacion
												    GROUP BY
												        id_pedido
												) comentarios ON pedidos.id = comentarios.id_pedido
												WHERE pedidos.cancelado=0
												ORDER BY
												    pedidos.ref_pedido DESC
												LIMIT 200;
												";

						}

						// echo $sql;


						$res=consulta( $sql, $conn );
						if ( numFilas( $res) > 0 ) {

							while ( $reg=$res->fetch_object() ) {
								// var_dump($reg);
								$cliente = obten_datos_user( $reg->id_cliente );
								$envio = obten_dir_envio( $reg->id_envio );
								// var_dump($envio);
								$fecha_entrega = ( empty( $reg->fecha_entrega ) ) ? 'Pendiente' : $reg->fecha_entrega;
								?>
									<tr class="datos pedidos <?php echo ($reg->cancelado==1)? 'cancelado' : ''; ?>">
										<td>
											<?php if ( usuario_tiene_pedidos( $reg->id_cliente ) > 1 ) echo "<i class='fa fa-exclamation-circle atencion' title='Este usuario ya ha realizado un pedido recientemente. Comprobar si es necesario enviar producto del mismo numero de lote'></i>" ?>
										</td>
										<td class="izq ref">
											<span class="ref_p" style="font-size: 18px"><?php echo $reg->ref_pedido; ?></span>
										</td>
										<td class="izq fecha_ped">
											<?php echo cambia_fecha_guion ( $reg->fecha_creacion ) ?>
										</td>

										<td class="izq fecha_ped">
											<?php echo $envio->pais; ?>
										</td>

										<td class="detalle izq">

										 <?php echo lista_pedido_ref( $reg->ref_pedido ) ?>

										</td>


										<td class="center td_preparacion">

											<?php
											$permisos_preparacion = array( 4,10 );

											if ( in_array( $_SESSION['smart_user_admin']['role'], $permisos_preparacion ) ) { ?>

												<?php if ( $reg->preparacion != 1 ) { ?>

													<button id_ped='<?php echo $reg->id ?>' class="btn_preparacion" onclick="cambia_estado_pedido( $(this).attr('id_ped'), 1, 'preparacion' )">PENDIENTE</button>

												<?php }else {?>

													<span id_ped='<?php echo $reg->id ?>' class="btn_preparado" onclick="if ( confirmacion('Preparación') ) {cambia_estado_pedido( $(this).attr('id_ped'), 0, 'preparacion' )}">OK<i class="fas fa-check"></i></span>
													<br>
													<span class="fecha_preparacion"><?php echo cambia_fecha_guion ( $reg->fecha_preparacion ) ?></span>

												<?php } ?>

											<?php }else { ?>

												<?php if ( $reg->preparacion != 1 ) { ?>

													<span class="btn_preparacion">PENDIENTE</span>

												<?php }else {?>

													<span class="btn_preparado">OK<i class="fas fa-check"></i></span>
													<br>
													<span class="fecha_preparacion"><?php echo cambia_fecha_guion ( $reg->fecha_preparacion ) ?></span>

												<?php } ?>

											<?php } ?>

										</td>

										<td class="center td_documentacion">

											<?php

											$permisos_documentacion = array( 4,3,10 );

											if ( in_array( $_SESSION['smart_user_admin']['role'], $permisos_documentacion ) ) { ?>

												<?php if ( $reg->documentacion != 1 ) { ?>

													<button id_ped='<?php echo $reg->id ?>' class="btn_preparacion" onclick="cambia_estado_pedido( $(this).attr('id_ped'), 1, 'documentacion' )">PENDIENTE</button>

												<?php }else {?>

													<span id_ped='<?php echo $reg->id ?>' class="btn_preparado" onclick="if ( confirmacion('Documentación') ) { cambia_estado_pedido( $(this).attr('id_ped'), 0, 'documentacion' )}">OK<i class="fas fa-check"></i></span>
													<br>
													<span class="fecha_preparacion"><?php echo cambia_fecha_guion ( $reg->fecha_documentacion ) ?></span>

												<?php } ?>

											<?php }else { ?>

													<?php if ( $reg->documentacion != 1 ) { ?>

														<span class="btn_preparacion">PENDIENTE</span>

													<?php }else {?>

														<span class="btn_preparado">OK<i class="fas fa-check"></i></span>
														<br>
														<span class="fecha_preparacion"><?php echo cambia_fecha_guion ( $reg->fecha_documentacion ) ?></span>

													<?php } ?>

											<?php } ?>

										</td>


										<td class="center td_envio">

											<?php

											$permisos_envio = array( 3,4,10 );

											if ( in_array( $_SESSION['smart_user_admin']['role'], $permisos_documentacion ) ) { ?>

												<?php if ( $reg->envio != 1 ) { ?>

													<button id_ped='<?php echo $reg->id ?>' class="btn_preparacion" onclick="cambia_estado_pedido( $(this).attr('id_ped'), 1, 'envio' )">PENDIENTE</button>

												<?php }else {?>

													<span id_ped='<?php echo $reg->id ?>' class="btn_preparado" onclick="if ( confirmacion('envio') ) {cambia_estado_pedido( $(this).attr('id_ped'), 0, 'envio' )}">OK<i class="fas fa-check"></i></span>
													<br>
													<span class="fecha_preparacion"><?php echo cambia_fecha_guion ( $reg->fecha_envio ) ?></span>

												<?php } ?>

											<?php }else { ?>

												 <?php if ( $reg->envio != 1 ) { ?>

													<span class="btn_preparacion">PENDIENTE</span>

												<?php }else {?>

													<span class="btn_preparado" >OK<i class="fas fa-check"></i></span>
													<br>
													<span class="fecha_preparacion"><?php echo cambia_fecha_guion ( $reg->fecha_envio ) ?></span>

												<?php } ?>

											<?php } ?>

										</td>
										<td>
											<button
												class="btn_coment"
												url="form_comentario_preparacion"
												onclick="openModal( $(this).attr('url'), <?php echo $reg->id ?>, '<?php echo $reg->ref_pedido ?>' )"><i class="far fa-comments"></i>
												<?php if ( $reg->num_filas > 0 ) { ?>
													<span class="num_coment"><?php echo $reg->num_filas ?></span></button>
												<?php } ?>
										</td>

									</tr>
								<?php }
							}else { ?>
							<tr class="info">
								<td valign="center" colspan="9" style="height: 80px;vertical-align: middle;text-align: center;">No hay pedidos</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>

		</div>

		<?php if ( $_GET['v'] != 'todos' && $_GET['v'] != 'Pendiente' && $_GET['v'] != 'Enviado') { ?>

			<div class="btns_funciones center">

						<button class="print_btn lista" onclick="window.location.href='./preparacion-pedidos?v=todos'">Ver listado completo<i class="fa fa-list"></i></button>

			</div>

		<?php } ?>

	</div>
	</div>
	<div class="sep40"></div>

	<div class="modal fade" id="comentarios">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>

	<?php  include ('./includes/footer.php') ?>
	<script>
		$(document).ready(function () {
		   $('#tbl_pedidos').DataTable( {
       language: {
            url: 'includes/es-ES.json',
       },
		    "pageLength": 200,
		    <?php if( $_GET['v'] == 'Pendiente' ) { ?>
		    "order": [[ 1, 'asc' ]]
		  	<?php }else if ( $_GET['v'] == 'Enviado' ) { ?>
		  	<?php }else { ?>
		  	"order": [[ 1, 'desc' ]]
		  	<?php } ?>
    	} );
		});
	</script>
	</body>
</html>

<script>
	$('.item.prep_ped').addClass('menu_actual');
	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}
	function checkScroll(scroll) {
		if (scroll > 120) {
				var left = $('.menu-lat').width()+$(window).width()*0.09;
				$('h1').addClass("fixed borde_sombra").css('left', left + 'px');
		}
		else {
			$('h1').removeClass("fixed borde_sombra");
		}

	}


	function cambia_estado_pedido ( pedido_id, estado, tipo_estado ) {

		var datos = {pedido_id: pedido_id, estado: estado, tipo_estado: tipo_estado, accion: 'cambia_estado_pedido'}

		$.ajax({
			url: './assets/lib/admin_control.php',
			type: 'post',
			dataType: 'text',
			data: datos
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

	function confirmacion( estado ) {

		var resultado = confirm( 'Se va a cambiar el estado ' + estado );
		if (!resultado) {
			return false;
		}else {
			return true;
		}
	}

	function openModal( url, id_pedido, ref_pedido) {
		var myModal = new bootstrap.Modal(document.getElementById('comentarios'), {
			keyboard: false
		})
		$.ajax({
			url: './includes/' + url + '.php',
			type: 'POST',
			datatype: 'html',
			data: {id_pedido:id_pedido, ref_pedido:ref_pedido}
		})
		.done(function(result) {
			$('#comentarios .modal-body').html(result);
			myModal.show();

		})
		.fail(function() {
			alert('Se ha producido un error');
		})
	}


</script>
