<?php
include_once('../../assets/lib/bbdd.php');
include_once('../../assets/lib/funciones.php');
include_once('../assets/lib/funciones_admin.php');

$id_usuario = $_REQUEST['id'];

?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<div class="sep10"></div>
<div class="row">
	<div class="col-md-9">
		<p class="modal-tit">Compras realizadas por <?php echo obten_datos_user ( $id_usuario )->nombre . ' ' . obten_datos_user ( $id_usuario )->apellidos ?></p>
	</div>
	<div class="col-md-3 btns_funciones" style="text-align: right;">
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
					<td>Fecha</td>
					<td>Importe</td>
				</tr>
			</thead>
			<tbody>
		<?php
				$total_importe = 0;
				$res = pedidos_usuario( $id_usuario );
				while($reg=$res->fetch_object()) {
					$total_importe = $total_importe + $reg->total_pagado;
					?>
					<tr>
						<td> <?php  echo '<a href="detalle-pedido?id=' . obten_datos_pedido_ref( $reg->ref_pedido )->id . '" target="_blank" class="abre_pedido">' . $reg->ref_pedido . '</span>' ?></td>
						<td> <?php  echo obten_datos_user( $reg->id_cliente )->nombre . '<br>' . obten_datos_user( $reg->id_cliente )->apellidos ?></td>
						<td> <?php  echo cambia_fecha_slash ( $reg->fecha_creacion ) ?></td>
						<td> <?php  echo $reg->total_pagado ?> €</td>
					</tr>

		<?php }
		?>
		<tr class="importe_total">
			<td colspan="3" align="right">TOTAL</td>
			<td class="total_importe"><b><?php echo $total_importe ?> €</b></td>
		</tr>
		</tbody>
	</table>
	</div>
</div>
	<style>
		select{-webkit-appearance: listbox !important}
		.form-control:focus {border-color: #ccc;box-shadow: none;}
		.form-modal input[type="text"], .form-modal select {margin-bottom: 20px;}
		.total_importe {font-size: 16px!important;font-weight: 600;}
	</style>
