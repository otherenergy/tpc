<?php
  $pagina = "Pedidos Amazon";
  $title = "Pedidos | Amazon";
  $description = "Página listado de pedidos AMAZON";
	include('./includes/header.php');
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
			<h1 class="center"><?php echo $pagina ?></h1>

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
			</div>

			<div class=" table-responsive">
				<table id="tbl_pedidos" class="table carrito dir_fac">
					<thead>
						<tr>
							<th class="izq ref">Ref</th>
							<th class="izq">Fecha</th>
							<th class="izq">Cliente</th>
							<th class="izq">Contacto</th>
							<th class="izq">Dirección envío</th>
							<th class="izq">Total</th>
							<th class="izq">Pago</th>
							<th class="izq">Envío</th>
							<th class="izq info">Mas info</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ( esta_activo( 'mostrar_datos_prueba' ) ) {
							$sql="SELECT * FROM pedidos";
						}else {
							$sql="SELECT * FROM pedidos WHERE id_cliente NOT IN ( SELECT id_user FROM user_test)";
						}
						$res=consulta( $sql, $conn );
						if ( numFilas( $res) > 0 ) {
							while ( $reg=$res->fetch_object() ) {
								$cliente = obten_datos_user( $reg->id_cliente );
								$envio = obten_dir_envio( $reg->id_envio );
								$fecha_entrega = ( empty( $reg->fecha_entrega ) ) ? 'Pendiente' : $reg->fecha_entrega;
								?>
									<tr class="datos pedidos <?php echo ($reg->cancelado==1)? 'cancelado' : ''; ?>">
										<td class="izq ref">
											<?php echo $reg->ref_pedido ?>
											<?php if ( $reg->descuento_aplicado ) { echo '<br><span class="listado-pedido-descuento">' . $reg->descuento_aplicado . '</span>'; } ?>
										</td>
										<td class="izq">
											<?php echo cambia_fecha_guion ( $reg->fecha_creacion ) ?>
										</td>
										<td class="izq">
											<?php echo $cliente->apellidos . ', ' . $cliente->nombre ?>
										</td>
										<td class="izq">
											<?php echo $cliente->email . '<br>' . $cliente->telefono ?>
										</td>
										<td class="izq">
											<?php echo $envio->direccion . ' | ' . $envio->localidad . ' | ' . $envio->cp . ' | ' . obten_nombre_provincia ( $envio->provincia ) . ' | ' . obten_nombre_pais ( $envio->pais ) ?>
											<img class="user_flag"
													 src="https://www.smartcret.com/admin/assets/img/flags/<?php echo strtolower( $envio->pais ) ?>.png"
													 alt="<?php echo obten_nombre_pais( $envio->pais ) ?>"
													 title="<?php echo obten_nombre_pais( $envio->pais ) ?>"
													 style="width: 20px;height: 15px;">
										</td>
										<td class="izq prec">
											<?php echo formatea_importe( $reg->total_pagado ) ?>€
										</td>

										<?php  if( $reg->cancelado == 1 ) { ?>
											<td></td>
											<td class="cancel"><b>CANCELADO</b></td>

										<?php }else { ?>

											<td class="izq">
												<span class="estado <?php echo strtolower( $reg->estado_pago ) ?>"><?php echo $reg->estado_pago ?></span><br>
												<span><?php echo '<span class="met">' . obten_metodo_pago( $reg->metodo_pago )->nombre . '</span>' ?></span>
											</td>
											<td class="izq">
												<span class="estado <?php echo strtolower( $reg->estado_envio ) ?>"><?php echo $reg->estado_envio ?></span>
											</td>

										<?php } ?>
										<td>
											<button class="cart_btn btn-tabla" onclick="window.location.href='detalle-pedido?id=<?php echo $reg->id ?>' ">Ver +</button>
										</td>
									</tr>
								<?php }
							}else { ?>
							<tr class="info">
								<td valign="center" colspan="7" style="height: 80px;vertical-align: middle;text-align: center;">No hay pedidos</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>

		</div>

	</div>
	</div>
	<div class="sep40"></div>

	<div class="modal fade" id="datos-pedido">
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
		    "pageLength": 25,
		    "order": [[ 0, 'desc' ]]
    	} );
		});
	</script>
	</body>
</html>

<script>
	$('.item.ped').addClass('menu_actual');
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
		// if (scroll > 142) {
		// 		// var left = $('.menu-lat').width()+$(window).width()*0.09;
		// 		$('table.carrito thead').addClass("fixed").css({
		// 			width: $('#tbl_pedidos').width() + 'px',
		// 			left: $('.menu-lat').width()+$(window).width()*0.127 + 'px'
		// 		});
		// }
		// else {
		// 	$('table.carrito thead').removeClass("fixed");
		// }
	}
</script>