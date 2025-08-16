<?php
  $pagina = "Llamadas API Books";
  $title = "Llamadas API Books | Smartcret";
  $description = "Consulta de las llamadas a la API de Books";
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

			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<form action="llamadas-api" id="form_api"  class="form_datos_perso" method='GET' >
						<div class="sep10"></div>
						<div class="input-group">
						  <!-- <input type="text" class="form-control" placeholder="Ref. Pedido" name="ref" value="<?php echo ( !empty( $_GET['ref'] )) ? $_GET['ref'] : ''; ?>"> -->
						  <select class="form-control" name="ref">
						  	<option value="" >Seleccionar</option>
							<?php
							  $sql="SELECT DISTINCT ref_pedido FROM llamadas_api ORDER by ref_pedido DESC";
								$res=consulta($sql, $conn);
								while($reg=$res->fetch_object()) { ?>
									<option value="<?php echo $reg->ref_pedido ?>" <?php if ( $reg->ref_pedido == $_GET['ref'] ) echo 'selected'; ?>><?php echo $reg->ref_pedido ?></option>
								<?php
							  }
							?>
						  </select>
						  <button type="submit" class="btn btn-outline-secondary llamada" id="button-addon2">Enviar</button>
						</div>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col-md-1"></div>
				<div class="resultado col-md-10">

					<?php if ( null != ( $_GET['ref'] || $_GET['ref'] != "" ) ) {

						$ref_pedido = $_GET['ref'];

						$sql_cliente = "SELECT * FROM llamadas_api WHERE ref_pedido = '$ref_pedido' AND tipo='cliente'";
						$res_cliente = consulta($sql_cliente, $conn);
						$cliente = $res_cliente->fetch_object();

						$sql_so = "SELECT * FROM llamadas_api WHERE ref_pedido = '$ref_pedido' AND tipo='orden_venta'";
						$res_so = consulta($sql_so, $conn);
						$orden_venta = $res_so->fetch_object();

						$sql_envio = "SELECT * FROM llamadas_api WHERE ref_pedido = '$ref_pedido' AND tipo='direccion_envio'";
						$res_envio = consulta($sql_envio, $conn);
						$envio = $res_envio->fetch_object();

						$sql_factura = "SELECT * FROM llamadas_api WHERE ref_pedido = '$ref_pedido' AND tipo='direccion_facturacion'";
						$res_factura = consulta($sql_factura, $conn);
						$facturacion = $res_factura->fetch_object();

					?>

					<div class="datos_llamada">

						<?php if ( $res_cliente->num_rows ) { ?>
							<div class="item">
								<h4>Cliente:</h4>
								<p><?php echo '<b>Id:</b><br> ' . $cliente->identificador ?></p>
								<p><?php echo '<b>fecha:</b><br>' . $cliente->fecha_creacion ?></p>
								<p><?php echo '<b>Llamada:</b><br>' . $cliente->contenido ?></p>
							</div>
						<?php } ?>

						<div class="item">
							<h4>Orden de venta:</h4>
							<p><?php echo '<b>Id:</b><br> ' . $orden_venta->identificador ?></p>
							<p><?php echo '<b>fecha:</b><br>' . $orden_venta->fecha_creacion ?></p>
							<p><?php echo '<b>Llamada:</b><br>' . $orden_venta->contenido ?></p>
						</div>

						<div class="item">
							<h4>Envío:</h4>
							<p><?php echo '<b>fecha:</b><br>' . $envio->fecha_creacion ?></p>
							<p><?php echo '<b>Llamada:</b><br>' . $envio->contenido ?></p>
						</div>

						<div class="item">
							<h4>Facturación:</h4>
							<p><?php echo '<b>fecha:</b><br>' . $facturacion->fecha_creacion ?></p>
							<p><?php echo '<b>Llamada:</b><br>' . $facturacion->contenido ?></p>
						</div>

					</div>

					<?php } ?>

				</div>
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
	}
</script>