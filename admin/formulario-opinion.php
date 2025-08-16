<?php
  $pagina = "Formularios de opinión";
  $title = "Formulario de opinión de clientes | Smartcret";
  $description = "Página listado de respuestas en el formulario de opinión enviado a los clientes un mes después de realizar su compra";
	include('./includes/header.php');

	if ( esta_activo( 'mostrar_datos_prueba' ) ) {
		$sql="SELECT * FROM formulario_opinion";
	}else {
		$sql="SELECT * FROM formulario_opinion WHERE user_id NOT IN ( SELECT id_user FROM user_test)";
	}

	$sql = "SELECT * FROM formulario_opinion";
	$res=consulta($sql, $conn);

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
			<h1 class="center"><?php echo $pagina ?><span class="num_usu">(<?php echo numFilas( $res ) ?> formularios)</span></h1>
			<div class="btns_funciones" style="display: none;">
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
							<th class="izq ref">Pedido<br>envío</th>
							<th class="izq ref">Idioma</th>
							<th class="izq email">Cliente</th>
							<th class="izq tel">Folleto paso a paso</th>
							<th class="izq tel">Color correcto</th>
							<th class="izq reg">Lo mejor</th>
							<th class="izq info">Lo peor</th>
							<th class="izq info">Comentarios</th>
							<th class="izq info">Envio formulario</th>
							<th class="izq info"></th>
						</tr>
					</thead>
					<tbody>

						<?php
						// $sql = "SELECT * FROM newsletter";
						// $res=consulta($sql, $conn);
						if ( numFilas( $res ) > 0 ) {
							while($reg=$res->fetch_object()) {

								$user = obten_datos_user ( $reg->user_id );
								$pedido = obten_datos_pedido_ref( $reg->ref_pedido );

								?>
									<tr class="datos pedidos">
										<td class="izq ref">
											<a href="detalle-pedido?id=<?php echo $pedido->id ?>" target="_blank"><?php echo $reg->ref_pedido ?></a>
											<span class="fech_envio">[<?php echo $reg->fecha_envio ?>]</span>
										</td>
										<td class="izq">
											<?php echo $reg->idioma  ?>
												<img class="user_flag" src="./assets/img/flags/<?php echo strtolower( $reg->idioma ) ?>.png"
														 style="width: 20px;height: 15px;" >
										</td>
										<td class="izq">
											<?php echo $user->apellidos .', '.$user->nombre ?>
										</td>
										<td class="izq">
											<?php echo ( $reg->folleto == 1) ? 'Sí' : 'No' ?>
										</td>
										<td class="izq">
											<?php echo ( $reg->color == 1) ? 'Sí' : 'No' ?>
										</td>
										<td class="izq">
											<?php echo $reg->mejor ?>
										</td>
										<td class="izq">
											<?php echo $reg->peor ?>
										</td>
										<td class="izq">
											<?php echo $reg->comentarios ?>
										</td>
										<td class="izq">
											<?php echo '<span style="display:none">' . cambia_fecha_tabla( $reg->fecha ) . '</span>' ?>
											<?php echo $reg->fecha  ?>
										</td>
										<td>
											<button class="delete news"
														data-bs-toggle="tooltip"
														data-bs-placement="top"
														title="Eliminar formulario"
														onclick="if (confirm('Si aceptas se eliminarán los datos de este formulario de opinión ¿Estas seguro?') == true ) { eliminaFormulario( <?php echo $reg->id ?> )}">
														<i class="fa fa-trash" style="margin-right:0px!important;"></i>
										  </button>
										</td>
									</tr>
								<?php }
							}else { ?>
							<tr class="info">
								<td valign="center" colspan="10" style="height: 80px;vertical-align: middle;text-align: center;">No hay formularios</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
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
		    "pageLength": 25,
		    "order": [[ 8, 'desc' ]],
		    "dom": 'Bfrtip',
		    "buttons": [
            'excelHtml5',
            'pdfHtml5'
        ]
    	} );
		});
	</script>
	</body>
</html>
<script>
	$('.item.formo').addClass('menu_actual');
	if ($(window).width() > 974) {
		checkScroll($(this).scrollTop());
		$(window).scroll(function() {
			checkScroll($(this).scrollTop());
		});
	}

	setTimeout(function() {
		$('.print_btn').appendTo('.dt-buttons').addClass('dt-button buttons-html5');
	},1000);

	function checkScroll(scroll) {
		if (scroll > 120) {
			var left = $('.menu-lat').width()+$(window).width()*0.09;
			$('h1').addClass("fixed borde_sombra").css('left', left + 'px');
		}
		else {
			$('h1').removeClass("fixed borde_sombra");
		}
	}

	function eliminaFormulario ( id_form ) {
		$.ajax({
			url: './assets/lib/admin_control.php',
			type: 'post',
			dataType: 'text',
			data: {'accion': 'elimina_form_opinion', 'id_form': id_form }
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