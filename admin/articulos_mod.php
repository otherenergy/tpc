<?php
  $pagina = "Articulos Smartcret";
  $title = "Articulos | Smartcret";
  $description = "Página listado de articulos";
	include('./includes/header.php');
?>

	<body class="listado-articulos">
	<!-- Header - Inicio -->
	<div class="container">
		<div class="row">
			<div class=".col-md-10 off-set-1">

			</div>
		</div>
	</div>
		<!-- Header - Fin -->
<div class="container listado datos princ">

	<div class="row">
		<?php include ('./includes/menu_lateral.php') ?>
		<div class="col-md-1"><div class="sepv"></div></div>
		<div class="col-md-9">
			<h1 class="center"><?php echo $pagina ?></h1>
			<div class="sep20"></div>

			<div class=" table-responsive">
				<table id="tbl_pedidos" class="table carrito dir_fac">
					<thead>
						<tr>
							<!-- <th class="izq sku">SKU</th> -->
							<th class="izq img">Img / SKU</th>
							<th class="izq cat">Categoría</th>
							<th class="izq nom">Producto</th>
							<th class="izq des">Descripción</th>
							<th class="izq prec">Precio</th>
							<th class="izq info">Mas info</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sql="SELECT * FROM productos WHERE es_variante = 0 ORDER BY id ASC";
						$res=consulta($sql, $conn);
						if ( numFilas( $res) > 0 ) {
							while($reg=$res->fetch_object()) {

								?>
									<tr class="datos pedidos">
									  <td class="izq img">
											<?php echo "<img src='../assets/img/$reg->miniatura' title='$reg->nombre_es' >" ?>
											<br>
											<?php echo '<span class="sku">' . $reg->sku . '</span>' ?>
										</td>
										<td class="izq cat">
											<?php echo obten_nombre_categoria ( $reg->id_categoria )  ?>
										</td>
										<td class="izq nom">
											<?php echo $reg->nombre_es . ' ajdkfh adksjfh adskjfasdf asd' ?>
										</td>
										<td class="izq des">
											<?php echo $reg->descripcion_es ?>
										</td>
										<td class="izq prec">
											<?php echo ( $reg->precio_es > 0 ) ? $reg->precio_es . '€' : '';?>
										</td>

										<td>
											<button class="cart_btn btn-tabla" onclick="window.location.href='detalle-articulo?id=<?php echo $reg->id ?>' ">Ver +</button>
										</td>
									</tr>
								<?php

								if ( $reg->variantes == 1) {

									$sql_var = "SELECT * FROM productos WHERE es_variante = $reg->id";
									$res_var = consulta($sql_var, $conn);
									if ( numFilas( $res_var) > 0 ) { ?>

										<tr>
											<td colspan="6">
												<table id="<?php echo 'tbl_' . $reg->id ?>">
										<?php

										while($reg_var=$res_var->fetch_object()) { ?>

											<tr class="datos pedidos variante var_<?php echo $reg->id?>">
											  <td class="izq img">
													<?php echo "<img src='../assets/img/$reg_var->miniatura' title='$reg_var->nombre_es' >" ?>
													<br>
													<?php echo '<span class="sku">' . $reg_var->sku . '</span>' ?>
												</td>
												<td class="izq cat">
													<?php echo obten_nombre_categoria ( $reg_var->id_categoria )  ?>
												</td>
												<td class="izq nom">
													<?php echo $reg_var->nombre_es  ?>
												</td>
												<td class="izq des">
													<?php echo $reg_var->descripcion_es ?>
												</td>
												<td class="izq prec">
													<?php echo ( $reg_var->precio_es > 0 ) ? $reg_var->precio_es . '€' : '';?>
												</td>
												<td>
													<button class="cart_btn btn-tabla" onclick="window.location.href='detalle-articulo?id=<?php echo $reg_var->id ?>' ">Ver +</button>
												</td>
											</tr>
									<?php
										} ?>
											</table>
										</td>
									</tr>
										<?php
									}
								}

							}
							}else { ?>
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
		    "pageLength": 25
    	} );
		});
	</script>
	</body>
</html>

<script>
	$('.item.prod').addClass('menu_actual');
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