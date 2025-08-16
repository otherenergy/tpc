<?php
  $pagina = "Activar desactivar colores en web";
  $title = "Activar desactivar colores en web | Smartcret";
  $description = "Activar desactivar colores en web";
	include('./includes/header.php');
?>

	<body class="listado-pedidos colores_web">
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
		<?php include ('./includes/menu_lateral.php') ?>
		<div class="col-md-3"><div class="sepv"></div></div>
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-12">
					<h1 class="center"><?php echo $pagina ?></h1>
				</div>
				<div class="col-md-4" style="text-align: right;">
			</div>
			<div class="sep20"></div>

			<?php /*
						$res = consulta_colores(2);
						while($reg=$res->fetch_object()) { ?>
							<div class="col-md-2 color web">
								<label class="sel-color <?php if ( $reg->agotado == 1) echo 'agotado' ?>">
									<div class="item" onclick="cambiaEstadoColor( <?php echo $reg->color_id ?> )">
										  <img id="img_color" src="../assets/img/colores/<?php echo $reg->valor ?>.jpg" alt="Color <?php echo $reg->valor ?>" title="Color <?php echo $reg->valor ?>" class="variacion">
										<?php if ( $reg->agotado == 1) { ?>
											<img id="img_color_agotado" src="../assets/img/disable.png" alt="Color no disponible" title="Color no disponible" class="disable">
										<?php } ?>
									</div>
								  <div style="text-align: center;"><small><?php echo $reg->valor ?></small></div>
								</label>
							</div>

						<?php }
					*/?>

				<?php
						$res = consulta_colores(2);
						$lista_colores = [];
						while($reg=$res->fetch_object()) {
							if (!in_array($reg->valor, $lista_colores) && $reg->publicado == 1 ) {
								$lista_colores[] = $reg->valor;?>
							<div class="col-md-2 color web">
								<label class="sel-color <?php if ( $reg->agotado == 1) echo 'agotado' ?>">
									<div class="item" onclick="cambiaEstadoColor( <?php echo $reg->color_id ?> )">
										  <img id="img_color" src="../assets/img/colores/<?php echo $reg->valor ?>.jpg" alt="Color <?php echo $reg->valor ?>" title="Color <?php echo $reg->valor ?>" class="variacion">
										<?php if ( $reg->agotado == 1) { ?>
											<img id="img_color_agotado" src="../assets/img/disable.png" alt="Color no disponible" title="Color no disponible" class="disable">
										<?php } ?>
									</div>
								  <div style="text-align: center;"><small><?php echo $reg->valor . '  <span style="font-size:10px;">[' . $reg->color_id . ']</span>'; ?></small></div>
								</label>
							</div>

						<?php }
					     }
						?>

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

	<?php  include ('./includes/footer.php') ?>
	<script>
		$(document).ready(function () {
		   $('#tbl_pedidos').DataTable( {
       language: {
            url: 'includes/es-ES.json',
       },
		    "pageLength": 25,
		    "order": [[ 8, 'desc' ]]
    	} );
		});
	</script>
	</body>
	<style>
		table#tbl_pedidos tr > td {
		  font-size: 12px!important;
		  line-height: 16px;
		}
		select {
    	-webkit-appearance: none;
		}
	</style>
</html>

<script>
	$('.item.color').addClass('menu_actual');
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

	function cambiaEstadoColor ( color_id ) {
		$.ajax({
			url: './assets/lib/admin_control.php',
			type: 'post',
			dataType: 'text',
			data: {'accion': 'cambia_estado_color', 'color_id': color_id }
		})
		.done(function(result) {
			var result = $.parseJSON(result);
			if(result.res==0) {
				muestraMensajeLn(result.msg)
			}else if (result.res==1) {
				muestraMensaje(result.msg);
				setTimeout(function() {
					location.reload();
				},400);
			}
		})
		.fail(function() {
			alert("error");
		});
	}

</script>