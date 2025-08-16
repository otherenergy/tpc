<?php
  $pagina = "Usuarios Newsletter";
  $title = "Usuarios Newsletter | Smartcret";
  $description = "Página listado de usuarios que se han registrado en la newsletter";
	include('./includes/header.php');

	$sql = "SELECT * FROM newsletter";
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
			<h1 class="center"><?php echo $pagina ?><span class="num_usu">(<?php echo numFilas( $res ) ?> usuarios)</span></h1>
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
							<th class="izq reg">Fecha registro</th>
							<th class="izq ref">Nombre</th>
							<th class="izq email">Email</th>
							<th class="izq tel">Localización</th>
							<th class="izq tel">Origen</th>
							<th class="izq tel">IP</th>
							<th class="der reg">Email recuerdo<br>HOLADIYLOVER</th>
							<th class="izq info"></th>
						</tr>
					</thead>
					<tbody>

						<?php
						// $sql = "SELECT * FROM newsletter";
						// $res=consulta($sql, $conn);
						if ( numFilas( $res ) > 0 ) {
							while($reg=$res->fetch_object()) {

								?>
									<tr class="datos pedidos">
										<td class="izq">
											<?php echo '<span style="display:none">' . cambia_fecha_tabla( $reg->fecha_actualizacion ) . '</span>' ?>
											<?php echo $reg->fecha_actualizacion ?>
										</td>
										<td class="izq ref">
											<?php echo $reg->nombre ?>
										</td>
										<td class="izq ref">
											<?php echo $reg->email ?>
										</td>
										<td class="izq">
												<img class="user_flag" src="<?php echo $reg->bandera?>"
														 style="width: 20px;height: 15px;" >
												<?php echo $reg->pais . ' | ' . $reg->region . ' | ' . $reg->ciudad ?>
										</td>
										<td class="izq ref">
											<?php echo $reg->origen ?>
										</td>
										<td class="izq ref">
											<a href="https://www.geolocation.com/es?ip=<?php echo $reg->user_ip ?>#ipresult" target="_blank"><?php echo $reg->user_ip ?></a>
										</td>
										<td>
											<?php if ( $reg->envio_recordatorio_codigo_news == 0 ) {
												echo "-";
											}else {
												echo cambia_fecha_hora ( $reg->fecha_envio_recordatorio );
											}?>
										</td>
										<td>
											<button class="delete news"
														data-bs-toggle="tooltip"
														data-bs-placement="top"
														title="Eliminar '<?php echo $reg->email ?>' de la lista de newsletter"
														onclick="if (confirm('Si aceptas se eliminará el usuario de la lista de newsletter ¿Estas seguro?') == true ) { eliminaUsuNews( <?php echo $reg->id ?> )}">
														<i class="fa fa-trash" style="margin-right:0px!important;"></i>
										</button>
										</td>
									</tr>
								<?php }
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
		    "order": [[ 0, 'desc' ]],
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
	$('.item.newser').addClass('menu_actual');
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

	function eliminaUsuNews ( id_usu ) {
		$.ajax({
			url: './assets/lib/admin_control.php',
			type: 'post',
			dataType: 'text',
			data: {'accion': 'elimina_usuario_news', 'id_usu': id_usu }
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