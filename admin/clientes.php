<?php
  $pagina = "Clientes Smartcret";
  $title = "Clientes | Smartcret";
  $description = "Página listado de clientes";
	include('./includes/header.php');
	$compras = $_GET['compras'];
	// Definir variables de paginación
	$registros_por_pagina = 25;
	$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
	$offset = ($pagina_actual - 1) * $registros_por_pagina;

	// Contar el total de registros
	// $sql_total = "SELECT COUNT(*) as total FROM users";
	if ( $compras == 0 ) {
	    if ( esta_activo( 'mostrar_datos_prueba' ) ) {
	        $sql_total = "SELECT COUNT(*) as total FROM users";
	    } else {
	        $sql_total = "SELECT COUNT(*) as total FROM users WHERE uid NOT IN ( SELECT id_user FROM user_test)";
	    }
	} elseif ( $compras == 2 ){
	    if ( esta_activo( 'mostrar_datos_prueba' ) ) {
	        $sql_total = "SELECT COUNT(*) as total FROM users WHERE distribuidor = 1";
	    } else {
	        $sql_total = "SELECT COUNT(*) as total FROM users WHERE distribuidor = 1 AND uid NOT IN ( SELECT id_user FROM user_test)";
	    }
		
	} else {
	    if ( esta_activo( 'mostrar_datos_prueba' ) ) {
	        $sql_total = "SELECT COUNT(DISTINCT P.id_cliente) as total FROM users U, pedidos P WHERE P.id_cliente = U.uid";
	    } else {
	        $sql_total = "SELECT COUNT(DISTINCT P.id_cliente) as total FROM users U, pedidos P WHERE P.id_cliente = U.uid AND P.id_cliente NOT IN ( SELECT id_user FROM user_test)";
	    }
	}
	
	$res_total = consulta($sql_total, $conn);
	// $res_total = $conexion->query($sql_total);
	$total_registros = $res_total->fetch_object()->total;
	$total_paginas = ceil($total_registros / $registros_por_pagina);


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
			<div class="sep20"></div>
			<div class="btns_funciones">
			<button class="print_btn me-2" url="form_distribuidor" onclick="openModal( $(this).attr('url'))" title="Añadir distribuidor">Añadir distribuidor<i class="fa fa-plus"></i></button>		
			<?php if ( $compras!=2 ) { ?>
				<button class="print_btn me-2"	onclick="window.location.href='clientes?compras=2'">Ver distribuidores<i class="fa fa-user ps-1"></i></button>
			<?php } ?>

			<?php if ( $compras==0 ) { ?>
				<button class="print_btn me-2"	onclick="window.location.href='clientes?compras=1'">Ver usuarios con compras<i class="fa fa-user ps-1"></i></button>
			<?php } else { ?>
				<button class="print_btn me-2"	onclick="window.location.href='clientes?compras=0'">Ver todos usuarios<i class="fa fa-user ps-1"></i></button>
			<?php } ?>
				<button class="print_btn"
					onclick="$('h1, .table-responsive').printThis({
	        importCSS: true,
	        importStyle: true,
	        loadCSS: true,
	        canvas: true
		    })"
		    >Imprimir <i class="fa fa-print"></i></button>
			</div>

			<div class=" table-responsive">
				<table id="tbl_pedidos" class="table carrito dir_fac">
					<thead>
						<tr>
							<th class="izq ref">Apellido, nombre</th>
							<th class="izq cif">DNI/CIF</th>
							<th class="izq cif">Books id</th>
							<th class="izq email">Email</th>
							<th class="izq tel">Telefono</th>
							<th class="izq pais">Pais</th>
							<th class="izq compras">Compras</th>
							<th class="izq compras">Importe</th>
							<th class="izq reg">Fecha registro</th>
							<th class="izq reg">web id</th>
							<!-- <th class="izq info">Mas info</th> -->
						</tr>
					</thead>
					<tbody>

						<?php

						// Consulta principal con paginación
						if ( $compras == 0 ) {
						    if ( esta_activo( 'mostrar_datos_prueba' ) ) {
						        $sql = "SELECT * FROM users ORDER BY uid DESC LIMIT $registros_por_pagina OFFSET $offset";
						    } else {
						        $sql = "SELECT * FROM users WHERE uid NOT IN ( SELECT id_user FROM user_test) ORDER BY uid DESC LIMIT $registros_por_pagina OFFSET $offset";
						    }
						} elseif ( $compras == 2 ){
						    if ( esta_activo( 'mostrar_datos_prueba' ) ) {
						        $sql = "SELECT * FROM users WHERE distribuidor = 1 ORDER BY uid DESC LIMIT $registros_por_pagina OFFSET $offset";
						    } else {
						        $sql = "SELECT * FROM users WHERE distribuidor = 1 uid NOT IN ( SELECT id_user FROM user_test) ORDER BY uid DESC LIMIT $registros_por_pagina OFFSET $offset";
						    }
						}else {
						    if ( esta_activo( 'mostrar_datos_prueba' ) ) {
						        $sql = "SELECT DISTINCT P.id_cliente, U.* FROM users U, pedidos P WHERE P.id_cliente = U.uid ORDER BY uid DESC LIMIT $registros_por_pagina OFFSET $offset";
						    } else {
						        $sql = "SELECT DISTINCT P.id_cliente, U.* FROM users U, pedidos P WHERE P.id_cliente = U.uid AND P.id_cliente NOT IN ( SELECT id_user FROM user_test) ORDER BY uid DESC LIMIT $registros_por_pagina OFFSET $offset";
						    }
						}

							// if ( $compras==0 ) {
							// 	if ( esta_activo( 'mostrar_datos_prueba' ) ) {
							// 		$sql="SELECT * FROM users ORDER BY uid DESC LIMIT 50";
							// 	}else {
							// 		$sql="SELECT * FROM users WHERE uid NOT IN ( SELECT id_user FROM user_test) ORDER BY uid DESC LIMIT 50";
							// 	}
							// 	// $sql="SELECT * FROM users ORDER BY uid DESC";
							// }else {
							// 	if ( esta_activo( 'mostrar_datos_prueba' ) ) {
							// 		$sql="SELECT DISTINCT P.id_cliente, U.* FROM users U, pedidos P WHERE P.id_cliente = U.uid ORDER BY uid DESC LIMIT 50";
							// 	}else {
							// 		$sql="SELECT DISTINCT P.id_cliente, U.* FROM users U, pedidos P WHERE P.id_cliente = U.uid AND P.id_cliente NOT IN ( SELECT id_user FROM user_test) ORDER BY uid DESC LIMIT 50";
							// 		// $sql="SELECT * FROM users WHERE uid NOT IN ( SELECT id_user FROM user_test) ORDER BY uid DESC";
							// 	}

							// }

						$res=consulta($sql, $conn);
						if ( numFilas( $res) > 0 ) {
							while($reg=$res->fetch_object()) {
								$direccion = obten_dir_envio( obten_dir_envio_predeterminado ($reg->uid) );
								//var_dump($direccion);
								?>
									<tr class="datos pedidos">
										<td class="izq ref">
											<?php echo $reg->apellidos . ', ' . $reg->nombre ?>
										</td>
										<td class="izq ref">
											<?php echo $reg->nif_cif ?>
										</td>
										<td class="izq ref">
											<a href="https://books.topciment.com/app/637820086#/contacts/<?php echo $reg->books_id ?>?filter_by=Status.All&per_page=200&sort_column=created_time&sort_order=A" target="blank"><?php echo $reg->books_id ?></a>
										</td>
										<td class="izq">
											<?php echo $reg->email  ?>
										</td>
										<td class="izq">
											<?php echo $reg->telefono ?>
										</td>
										<td class="izq">

											<?php if ( isset ($direccion->pais) ) { ?>
												<?php echo $direccion->pais ?>
												<img class="user_flag" src="https://www.smartcret.com/admin/assets/img/flags/<?php echo strtolower( $direccion->pais ) ?>.png"
														 alt="<?php echo obten_nombre_pais( $direccion->pais ) ?>"
														 title="<?php echo obten_nombre_pais( $direccion->pais ) ?>"
														 style="width: 20px;height: 15px;" >
											<?php }else { echo "-";} ?>
										</td>
										<td class="izq num">
											<?php $num_ped = obten_num_pedidos_cliente ( $reg->uid ); ?>
											<span id="num_pedidos"
													<?php if ( $num_ped > 0 ) { ?>
														class="activ"
														data-bs-toggle="tooltip"
														data-bs-placement="top"
														url="compras_cliente"
												    onclick="openModal( $(this).attr('url'), <?php echo $reg->uid ?> )"
												    title="Ver pedidos realizados por el cliente <?php echo $reg->apellidos . ', ' . $reg->nombre ?>"
												  <?php } ?>
												    >
											<?php
												echo $num_ped . ' <i class="fa fa-box-open"></i>';
											?>
										</span>
										</td>

										<td class="izq">
											<?php
												echo @formatea_importe ( obten_compras_totales_cliente ( $reg->uid ) ) . '€'?>
										</td>
										<td class="izq">
											<?php echo cambia_fecha_guion( $reg->fecha_alta ) ?>
										</td>
										<td class="izq">
											<?php echo $reg->uid ?>
										</td>
										<!-- <td>
											<button class="cart_btn btn-tabla" onclick="window.location.href='detalle-cliente?id=<?php echo $reg->id ?>' ">Ver +</button>
										</td> -->
									</tr>
								<?php }
							}else { ?>
							<tr class="info">
								<td valign="center" colspan="7" style="height: 80px;vertical-align: middle;text-align: center;">No hay clientes</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

				<!-- Mostrar los botones de paginación con desplegable -->
				<div class="paginacion" style="text-align: center; margin-top: 20px;">
				    <!-- Botón de Página Anterior -->
				    <?php if ($pagina_actual > 1) { ?>
				        <a href="?pagina=<?php echo $pagina_actual - 1; ?>&compras=<?php echo $compras; ?>" class="btn-pagina">Anterior</a>
				    <?php } else { ?>
				        <span class="btn-pagina disabled">Anterior</span>
				    <?php } ?>

				    <!-- Desplegable para seleccionar la página -->
				    <select id="pagina-selector" class="pagina-selector" onchange="location = this.value;">
				        <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
				            <option value="?pagina=<?php echo $i; ?>&compras=<?php echo $compras; ?>" <?php if ($i == $pagina_actual) echo 'selected'; ?>>
				                Página <?php echo $i; ?>
				            </option>
				        <?php } ?>
				    </select>

				    <!-- Botón de Página Siguiente -->
				    <?php if ($pagina_actual < $total_paginas) { ?>
				        <a href="?pagina=<?php echo $pagina_actual + 1; ?>&compras=<?php echo $compras; ?>" class="btn-pagina">Siguiente</a>
				    <?php } else { ?>
				        <span class="btn-pagina disabled">Siguiente</span>
				    <?php } ?>
				</div>

				<style>
				    .btn-pagina {
				        margin: 0 10px;
				        padding: 5px 10px;
				        text-decoration: none;
				        background-color: #f4f4f4;
				        border: 1px solid #ddd;
				        color: #333;
				        cursor: pointer;
				    }

				    .btn-pagina.disabled {
				        background-color: #eaeaea;
				        cursor: not-allowed;
				    }

				    .pagina-selector {
				        padding: 5px 10px;
				        border-radius: 5px;
				        border: 1px solid #ddd;
				        font-size: 14px;
				        margin: 0 10px;
				    }
				</style>
			</div>

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
	<script>
		$(document).ready(function () {
		   $('#tbl_pedidos').DataTable( {
       language: {
            url: 'includes/es-ES.json',
       },
		    "pageLength": <?php echo $registros_por_pagina ?>,
		    "order": [[ 9, 'desc' ]],
		    "paging": false
    	} );
		});
	</script>
	</body>
</html>
<script>
	$('.item.clie').addClass('menu_actual');
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
		if (scroll > 142) {
				// var left = $('.menu-lat').width()+$(window).width()*0.09;
				$('table.carrito thead').addClass("fixed").css({
					width: $('#tbl_pedidos').width() + 'px',
					left: $('.menu-lat').width()+$(window).width()*0.127 + 'px'
				});
		}
		else {
			$('table.carrito thead').removeClass("fixed");
		}
	}

	function openModal( url, id=0) {
		var myModal = new bootstrap.Modal(document.getElementById('pedidos-cliente'), {
			keyboard: false
		})
		$.ajax({
			url: './includes/' + url + '.php',
			type: 'POST',
			datatype: 'html',
			data: {id:id}
		})
		.done(function(result) {
			$('#pedidos-cliente .modal-body').html(result);
			myModal.show();

		})
		.fail(function() {
			alert('Se ha producido un error');
		})
	}
</script>