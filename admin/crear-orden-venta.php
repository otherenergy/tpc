<?php
  $pagina = "Generar Orden de Venta en books";
  $title = "Generar orden de venta en books | Smartcret";
  $description = "Página creación orden de venta en Books";
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
<div class="container listado datos cupones princ">

	<div class="row">
		<?php include ('./includes/menu_lateral.php') ?>
		<div class="col-md-1"><div class="sepv"></div></div>
		<div class="col-md-9">
				<h1 class="center"><?php echo $pagina ?></h1>
		</div>
		<div class="sep20"></div>

		<div class="container">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<div class="sep60"></div>
					<!-- <form action="./assets/return/notifica.php?lang=en" method="POST"> -->
					<!-- <form action="https://www.smartcret.com/assets/return/notifica.php?lang=en" method="POST"> -->
						<h1>Confirmar pedido y generar SO</h1>
					<form action="notifica.php" method="POST">
						<label>Nº ref REDSYS</label>
<!-- 						<input class="form-control" type="text" name="Ds_Order"> -->
						<select class="form-control" name="datos_pedido">
							<option value="-">Seleccionar</option>
							<?php
							  $sql="SELECT * FROM pedidos_temp ORDER by id DESC LIMIT 20";
								$res=consulta($sql, $conn);
								while($reg=$res->fetch_object()) { ?>
									<option value="<?php echo $reg->redsys_num_order . '|' . $reg->ref_pedido ?>"><?php echo $reg->redsys_num_order . '   -  [ ' . $reg->total_pagado . '€ ]  [ ' . $reg->ref_pedido . ' ] '  . ' [ ' . $reg->fecha_creacion . ' ] [ ' . $reg->idioma . ' ]'; ?></option>
								<?php
							  }
							?>
						</select>
						<div class="sep10"></div>
						<label>Estado de operación</label>
						<select class="form-control" name="Ds_Response">
							<option value="0000">Aceptada</option>
							<option value="999">Denegada</option>
						</select>
						<div class="sep10"></div>
						<label>Enviar email 'Pedido recibido'</label>
						<select class="form-control" name="envio_email">
							<option value=1>SI</option>
							<option value=0>NO</option>
						</select>
						<div class="sep20"></div>
						<button class="btn_smart w-100" type="submit">Confirmar</button>
					</form>
				</div>
			</div>
		</div>
		<style>
			label {
			    font-weight: 500;
			    font-size: 14px;
			    margin-bottom: 2px;
			}
			h1{
				font-size: 22px;
    		margin-bottom: 25px;
			}
		</style>



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
	$('.item.creaso').addClass('menu_actual');
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

	function openModal( url, id_aviso=0 ) {
		var myModal = new bootstrap.Modal(document.getElementById('aviso-web'), {
			keyboard: false
		})
		$.ajax({
			url: './includes/' + url + '.php',
			type: 'POST',
			datatype: 'html',
			data: {id_aviso:id_aviso}
		})
		.done(function(result) {
			$('#aviso-web .modal-body').html(result);
			myModal.show();
		})
		.fail(function() {
			alert('Se ha producido un error');
		})
	}

	function eliminaAviso ( id_aviso ) {
		$.ajax({
			url: './assets/lib/admin_control.php',
			type: 'post',
			dataType: 'text',
			data: {'accion': 'elimina_aviso', 'id_aviso': id_aviso }
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

</script>