<?php
include_once('../../assets/lib/bbdd.php');
// include_once('../../assets/lib/funciones.php');
include_once('../assets/lib/funciones_admin.php');

$id_descuento = $_REQUEST['id_descuento'];

?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<div class="sep10"></div>
<div class="row">
	<div class="col-md-7">
		<p class="modal-tit">Usos descuento <?php echo obten_descuento( $id_descuento )->nombre_descuento ?></p>
	</div>
	<div class="col-md-5 btns_funciones" style="text-align: right;">
		<button class="print_btn"
								onclick="$('.modal-tit, .blq_descuentos').printThis({
					        importCSS: true,
					        importStyle: true,
					        loadCSS: true,
					        canvas: false
						    })"
						    >Imprimir <i class="fa fa-print"></i>
    </button>
	</div>
</div>

<div class="row blq_descuentos">
	<div class="col-md-12">
		<table id="tbl_pedidos" class="table carrito descuento" style="width: 100%">
			<thead>
				<tr>
					<td>Pedido</td>
					<td>Usuario</td>
					<td>Importe</td>
					<td>Fecha</td>
				</tr>
			</thead>
			<tbody>
		<?php
				$total = 0;
				// echo $id_descuento;
				$res = obten_pedidos_descuento ( $id_descuento );
				while($reg=$res->fetch_object()) {
					$total += $reg->total_pagado;
					?>
					<tr>
						<td> <?php  echo '<a href="detalle-pedido?id=' . obten_datos_pedido_ref( $reg->ref_pedido )->id . '" target="_blank" class="abre_pedido">' . $reg->ref_pedido . '</span>' ?></td>
						<td> <?php  echo obten_datos_user( $reg->id_cliente )->nombre . '<br>' . obten_datos_user( $reg->id_cliente )->apellidos ?></td>
						<td> <?php  echo $reg->total_pagado ?> €</td>
						<td> <?php  echo cambia_fecha_slash ( $reg->fecha_creacion ) ?></td>
					</tr>
		<?php }
		?>
		<tr style="background-color: #f6fbea;">
			<td colspan="2">TOTAL</td>
			<td><b><?php echo $total ?> €</b></td>
			<td></td>
		</tr>
		</tbody>
	</table>
	</div>
</div>
	<style>
		select{-webkit-appearance: listbox !important}
		.form-control:focus {border-color: #ccc;box-shadow: none;}
		.form-modal input[type="text"], .form-modal select {margin-bottom: 20px;}
	</style>

	<script>



	</script>
