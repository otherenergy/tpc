<?php
if (session_status() === PHP_SESSION_NONE){session_start();}

  if ( $_SESSION['smart_user_admin']['role'] == 3 || $_SESSION['smart_user_admin']['role'] == 4 || $_SESSION['smart_user_admin']['role'] == 5 ) {
  	header("Location:preparacion-pedidos?v=Pendiente");
  }

  $pagina = "Pedidos Smartcret";
  $title = "Pedidos | Smartcret";
  $description = "Página listado de pedidos";
	include('./includes/header.php');


// $importe_total = obten_ventas_no_vies ();
// $muestra_mensaje = ( $importe_total > (float)obten_max_importe_ventas_ue_no_vies() ) ? true : false;
// $muestra_mensaje = false;

// $iva_es = obten_iva_books ( "ES" );




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

			<h1 class="center">
				<?php if ( !isset( $_GET['v'] ) )  {
					echo "Últimos " . $pagina;?>
					<button class="print_btn lista" onclick="window.location.href='./?v=todos'">Ver todos<i class="fa fa-list"></i></button>
				<?php }

			if ( isset( $_GET['v'] ) && $_GET['v'] == 'todos' )  {
					echo $pagina; ?>
					<button class="print_btn lista" onclick="window.location.href='window.location.href='/admin/'">Ultimos pedidos<i class="fa fa-list"></i></button>
			<?php } ?>

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
			</div>

			<div class=" table-responsive">
				<table id="tbl_pedidos" class="table carrito dir_fac">
					<thead>
						<tr>
							<th></th>
							<th class="izq ref">Ref</th>
							<th class="izq ped_fecha">Fecha</th>
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
						if (  isset( $_GET['v'] ) && $_GET['v'] == 'todos' ) {

							$limit = "";

						}else {

							$limit = " LIMIT 100";

						}

						$sql = "SELECT
											P.id,
										    P.id_cliente,
										    P.cancelado,
										    P.ref_pedido,
										    P.total_pagado,
										    P.fecha_creacion,
										    P.metodo_pago,
											P.total_sinenvio,
											P.gastos_envio,
											P.descuento_iva,
											P.tipo_impuesto,
										    P.estado_pago,
											P.estado_envio,
											P.total_pagado_div,
											P.id_envio,
										    P.redsys_num_order,
										    P.paypal_id,
										    COALESCE(CPA.nombre_descuento, '0') AS descuento_aplicado,
										    U.nombre,
										    U.apellidos,
										    U.email,
										    U.telefono,
										    PE.direccion,
										    PE.localidad,
										    PE.provincia as nombre_prov,
										    PE.cp,
										    PE.pais,
										    MP.nombre as nombre_metodo_pago,
										    PA.nombre as nombre_pais
										FROM
										    pedidos P
										LEFT JOIN
										    pedidos_dir_envio PE ON P.id_envio = PE.id
										LEFT JOIN
										    pedidos_dir_factura PF ON P.id_facturacion = PF.id
										LEFT JOIN
										    pedidos_cupones_aplicados CPA ON CPA.id = P.cupon_id
										LEFT JOIN
										    users U ON P.id_cliente = U.uid
										JOIN
										    estados_pago EP ON EP.id = P.estado_pago
										JOIN
										    metodos_pago MP ON MP.id = P.metodo_pago
										JOIN
										    estados_envio EV ON EV.id = P.estado_envio
										JOIN
										    paises PA ON PA.cod_pais = PE.pais
										ORDER BY
										    P.ref_pedido DESC
										$limit
										";

						$res=consulta( $sql, $conn );
						// var_dump($res);
						// // echo "Hola";

						if ( numFilas( $res) > 0 ) {

							while ( $reg=$res->fetch_object() ) {
								// var_dump($reg=$res->fetch_object());
								// $cliente = obten_datos_user( $reg->id_cliente );
								// $envio = obten_dir_envio( $reg->id_envio );

								?>
									<tr class="datos pedidos <?php echo ($reg->cancelado==1)? 'cancelado' : ''; ?>">
										<td>
											<?php // if ( usuario_tiene_pedidos( $reg->id_cliente ) > 1 ) echo "<i class='fa fa-exclamation-circle atencion' title='Este usuario ya ha realizado un pedido recientemente. Comprobar si es necesario enviar producto del mismo numero de lote'></i>" ?>

											<!-- <?php echo $reg->tipo_impuesto ?> -->

										</td>
										<td class="izq ref">

											<?php echo $reg->ref_pedido; ?>
											<?php if ( $reg->descuento_aplicado != 0 ) { echo '<span class="listado-pedido-descuento">' . $reg->descuento_aplicado . '</span>'; } ?>
											<?php if ( pedido_con_descuento ( $reg->id ) ) echo '<span class="directo listado-pedido-descuento">DESCUENTO %</span>'?>
										</td>
										<td class="izq">
											<?php echo cambia_fecha_guion ( $reg->fecha_creacion ) ?>
										</td>
										<td class="izq">
											<?php echo $reg->apellidos . ', ' . $reg->nombre ?>
										</td>
										<td class="izq">
											<?php echo $reg->email . '<br>' . $reg->telefono ?>
										</td>
										<td class="izq">
											<?php echo $reg->direccion . ' | ' . $reg->localidad . ' | ' . $reg->cp . ' | ' . $reg->nombre_prov . ' | ' . $reg->nombre_pais ?>
											<img class="user_flag"
													 src="https://www.smartcret.com/admin/assets/img/flags/<?php echo strtolower( $reg->pais ) ?>.png"
													 alt="<?php echo $reg->nombre_pais ?>"
													 title="<?php echo $reg->nombre_pais ?>"
													 style="width: 20px;height: 15px;">
										</td>
										<td class="izq prec">
											<?php
											/** calculamos el total pagado **/
											// var_dump($reg);

											$env_pais = obten_dir_envio_user ( $reg->id_envio );

											// if ( ( $reg->tipo_impuesto == 2 || $reg->tipo_impuesto == 3 || $reg->tipo_impuesto == 6 ) && $reg->descuento_iva == 0 ) {
												$iva_es = obten_iva_books ( "ES" );

												if ( $env_pais->pais == 'US') {
													echo formatea_importe( $reg->total_pagado ) . '€';
													echo "<br>";
													echo '<span class="mini"><sup>$</sup>' . formatea_importe( $reg->total_pagado_div ) . '</span>';
												}else {
													// $importe_descuento_iva = $reg->total_pagado - ( $reg->total_pagado / ( 1 + ( $iva_es->iva / 100 ) ) );
													echo formatea_importe( $reg->total_pagado ) . '€';
												}

											// }
											// if ( $reg->tipo_impuesto == 5 ) {
											// 	echo formatea_importe( $reg->total_pagado ) . '€';
											// }
											// if ( $reg->tipo_impuesto == 1 || $reg->tipo_impuesto == 4 ) {
											// 	echo formatea_importe( $reg->total_pagado ) . '€';
											// }
											// if ( $reg->tipo_impuesto == 0 ) {
											// 	echo formatea_importe( $reg->total_pagado ) . '€';
											// }
											// if ( $reg->tipo_impuesto == 2 ) {
											// 	echo formatea_importe( $reg->total_pagado ) . '€';
											// }

											?>

										</td>

										<?php  if( $reg->cancelado == 1 ) {

											$datos_cancela = obten_datos_cancelacion( $reg->ref_pedido );

											?>
											<td class="izq">

												<span class="estado <?php echo strtolower( $reg->estado_pago ) ?>"><?php echo $reg->estado_pago ?></span><br>

												<span><?php echo '<span class="met">' . $reg->nombre_metodo_pago . '</span>' ?></span>
												<span class="num_redys"><?php echo $reg->redsys_num_order ?></span>
											</td>
											<td class="cancel"><b>CANCELADO</b><br>
												<i style="font-size: 16px"
												  class="cancela-info fa fa-info-circle"
												  data-bs-toggle="tooltip"
													data-bs-placement="top"
													title="<?php echo (!empty( $datos_cancela->fecha_actualizacion )) ? cambia_fecha_slash ( $datos_cancela->fecha_actualizacion ) : ''; ?> | <?php echo ( !empty( $datos_cancela->motivo ) ) ? $datos_cancela->motivo : '' ?>"></i>
												</td>

										<?php }else { ?>

											<td class="izq">

												<span class="estado <?php echo strtolower( $reg->estado_pago ) ?>"><?php echo $reg->estado_pago ?></span><br>
												<span><?php echo '<span class="met ' . $reg->nombre_metodo_pago . '">' . $reg->nombre_metodo_pago . '</span>' ?></span>

												<?php if ( $reg->paypal_id == 0 ) { ?>
													<span class="num_redys"><?php echo $reg->redsys_num_order ?></span>
												<?php }else { ?>
													<span class="num_redys paypal" style="background-color: #009ddf52;padding: 1px 2px;"><?php echo $reg->paypal_id ?></span>
												<?php } ?>

											</td>
											<td class="izq">
												<span class="estado <?php echo strtolower( $reg->estado_envio ) ?>"><?php echo $reg->estado_envio ?></span>
											</td>

										<?php } ?>
										<td>
											<a class="cart_btn btn-tabla" href="detalle-pedido?id=<?php echo $reg->id ?>">Ver +</a>
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
		    "pageLength": 100,
		    "order": [[ 1, 'desc' ]]
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