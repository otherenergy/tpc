<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if ( session_status() === PHP_SESSION_NONE){session_start();}
require( dirname ( __DIR__ ) . "/assets/lib/bbdd.php" );
require( dirname ( __DIR__ ) . "/assets/lib/funciones.php" );

$pagina = "Ventas intracomunitarias sin VIES";
$title = "Ventas intracomunitarias sin VIES";
$description = "Consulta de las llamadas a la API de Books";
include('./includes/header.php');


// $sql="SELECT ROUND(sum(P.total_pagado),2) AS importe_total FROM pedidos P, datos_envio E, datos_facturacion F where E.pais != 'ES' AND E.pais !='GB' and P.id_envio = E.id AND P.id_facturacion = F.id AND F.vies is null AND P.cancelado=0 AND P.fecha_creacion BETWEEN '2023-01-01' AND '2023-12-31'";

// $sql="SELECT ROUND( sum( P.total_pagado ),2 ) AS importe_total FROM pedidos P, datos_envio E, datos_facturacion F where E.pais != 'ES' AND E.pais IN ( SELECT codigo FROM paises_ue ) AND P.id_envio = E.id AND P.id_facturacion = F.id AND F.vies is null AND P.cancelado=0 AND P.fecha_creacion BETWEEN '2023-01-01' AND '2023-12-31'";



// $sql="SELECT ROUND( SUM( P.total_pagado ), 2 ) as importe_total FROM pedidos P, datos_envio E, datos_facturacion F WHERE E.pais != 'ES' and P.id_envio = E.id AND P.id_facturacion = F.id AND F.vies is null AND F.vies is null;";

// $res=consulta($sql, $conn);
// $reg=$res->fetch_object();

// $importe_total = $reg->importe_total;


$sql2 = "SELECT P.id, P.ref_pedido, E.email, E.pais, date_format(P.fecha_creacion, '%d-%m-%Y') as fecha, ROUND(P.total_pagado,2) as importe FROM pedidos P, datos_envio E, datos_facturacion F WHERE E.pais != 'ES' AND E.pais IN ( SELECT codigo FROM paises_ue ) AND P.id_envio = E.id AND P.id_facturacion = F.id AND F.vies is null AND P.cancelado=0 AND P.fecha_creacion BETWEEN '2023-01-01' AND '2023-12-31' ORDER BY ref_pedido";

$res2 = consulta( $sql2, $conn );


?>

	<body class="listado-pedidos no_vies">
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

			<div style="text-align: center;">
				<label for="max_importe" style="font-size: 14px;font-weight: 500;margin-right: 10px">Importe máximo ventas UE no VIES</label>
				<input type="text" id="max_importe" value="<?php echo obten_max_importe_ventas_ue_no_vies() ?>" style="width: 90px;text-align: right;padding-right:5px;"> €
				<button class="cart_btn btn-tabla" onclick="guarda_max_importe( $('#max_importe').val() )" style="margin-left:20px">Guardar</button>
			</div>

			<div class="sep20"></div>

			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">

					<div class=" table-responsive">
						<table id="tbl_pedidos" class="table carrito dir_fac">
							<thead>
								<tr>
									<th class="izq ref">Importe 2023</th>
									<th class="der">Restante (max <?php echo number_format( (float)obten_max_importe_ventas_ue_no_vies(), 0, "", "") ?>€)</th>
								</tr>
							</thead>
							<tbody>

								<tr>
									<td class="izq" id="importe" style="font-size: 30px!important;font-weight: 600;"><?php echo formatea_importe( obten_ventas_no_vies () ) ?>€</td>
									<td class="der" style="font-size: 20px!important;font-weight: 500;color: grey;"><?php echo formatea_importe( obten_max_importe_ventas_ue_no_vies() - obten_ventas_no_vies () ) ?>€</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="sep20"></div>

					<div class=" table-responsive">
						<table id="tbl_pedidos" class="table carrito dir_fac tbl_vies">
							<thead>
								<tr>
									<th class="izq ref">Ref</th>
									<th class="izq">Email</th>
									<th class="izq">País</th>
									<th class="izq">Fecha</th>
									<th class="der info">Importe</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$total = 0;
								if ( numFilas( $res2) > 0 ) {
									while ( $reg2 = $res2->fetch_object() ) {

										$total += $reg2->importe;
										?>
											<tr class="datos pedidos">
												<td class="izq ref">
													<a href="https://www.smartcret.com/admin/detalle-pedido?id=<?php echo $reg2->id ?>" target="_blank"><?php echo $reg2->ref_pedido ?></a>
												</td>
												<td class="izq ref">
													<?php echo $reg2->email ?>
												</td>
												<td class="izq ref">
													<?php echo $reg2->pais ?>
												</td>
												<td class="izq ref">
													<?php echo $reg2->fecha ?>
												</td>
												<td class="der ref">
													<?php echo formatea_importe( $reg2->importe ) ?>€
												</td>
											</tr>
										<?php }
										// echo "<tr>";
										// echo "<td colspan='4'>TOTAL</td>";
										// echo '<td class="der ref total" style="font-size:18px!important;font-weight:500;height:50px">' . formatea_importe( $total ) . '€</td>';
										// echo "</tr>";
									}else { ?>
									<tr class="info">
										<td valign="center" colspan="5" style="height: 80px;vertical-align: middle;text-align: center;">No hay pedidos</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>

						<table id="tbl_pedidos" class="tbl_totales" style="width: 100%;">
							<?php
								echo "<tr>";
								echo "<td class='der' style='width:75%'>TOTAL</td>";
								echo '<td class="der ref total" style="font-size:18px!important;font-weight:500;height:50px">' . formatea_importe( $total ) . '€</td>';
								echo "</tr>";
							 ?>

						</table>
					</div>

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
		   $('.tbl_vies').DataTable( {
       language: {
            url: 'includes/es-ES.json',
       },
		    "pageLength": 525,
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

	setTimeout(function() {
		$('.print_btn').appendTo('.dt-buttons').addClass('dt-button buttons-html5');
	},1000);

	setTimeout(function() {
		// $('#tbl_pedidos_paginate').insertAfter('.tbl_totales');
		$('#tbl_pedidos_paginate').remove();

	},300);

	function guarda_max_importe ( importe ) {
		$.ajax({
			url: './assets/lib/admin_control.php',
			type: 'post',
			dataType: 'text',
			data: { 'accion': 'guarda_max_importe_ventas_ue_no_vies', 'importe': importe }
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


