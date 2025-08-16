<?php
include_once ('../../../config/db_connect.php');
include_once('../../../assets/lib/class.carrito.php');
include_once('../../../assets/lib/funciones.php');
include_once('../../../class/userClass.php');
include_once('../../../class/checkoutClass.php');

$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma=$id_idioma_global;
// echo $id_idioma;
include_once('../../../includes/vocabulario.php'); 

$id_pedido = $_POST['id_pedido'];
$res = obten_datos_pedido($id_pedido);
$reg = $res->fetch_object();
// var_dump($reg);
$sql2 = "SELECT * FROM detalles_pedido WHERE id_pedido = $id_pedido";
$res2 = consulta($sql2, $conn);
?>

<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit"><span class="ref-ped"><?php echo $vocabulario_pedido ?>: <?php echo $reg->ref_pedido ?></span><span class="fecha-ped"><?php echo cambia_fecha_normal($reg->fecha_creacion) ?></span></p>

<div class="row form-modal">
	<div class="container listado">
		<div class="col-md-10 offset-1">
			<div class="table-responsive">
				<table class="table carrito finalizado">
					<thead>
						<tr>
							<th colspan="2" class="b-top0"><?php echo $vocabulario_articulo ?></th>
							<th class="b-top0 der"><?php echo $vocabulario_unidades ?></th>
							<th class="b-top0 der"><?php echo $vocabulario_precio ?></th>
							<th class="b-top0 der"><?php echo $vocabulario_total ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$pais_envio = obten_dir_envio_user($reg->id_envio)->pais;
						if ($pais_envio == 'US'){
							$moneda = '$';
						}else{
							$moneda = '€';
						};

						while ($reg2 = $res2->fetch_object()) {
							$reg_prod = obten_datos_producto($reg2->id_prod);
						?>
							<tr class="datos" id="<?php echo $reg2->id_prod ?>">
								<td class="izq" width="90px"><img src="../../assets/img/productos/<?php echo $reg_prod->miniatura ?>" alt=""></td>
								<td class="izq">
									<div class="desc"><?php echo $reg_prod->nombre_es ?></div>
								</td>
								<td class="td-unidades">
									<?php echo $reg2->cantidad ?>
								</td>
								<td><?php echo formatea_importe($reg2->precio) ?> <?php echo $moneda ?></td>
								<td><?php echo formatea_importe($reg2->cantidad * $reg2->precio) ?> <?php echo $moneda ?></td>
							</tr>
						<?php } ?>
						<tr class="total envios">
							<td colspan="4" class="der"><?php echo $vocabulario_subtotal_productos?></td>
							<?php 
							if ($pais_envio == 'US'){ ?>
							<td><?php echo formatea_importe($reg->total_sinenvio_div) ?> <?php echo $moneda ?></td>
							<?php }else{?>
							<td><?php echo formatea_importe($reg->total_sinenvio) ?> <?php echo $moneda ?></td>
							<?php }?>

						</tr>
						<?php
						if ($reg->importe_descuento > 0) {
							$reg_descuento = obten_descuento($reg->descuento_id);
						?>
							<tr class="total envios descuento">
								<td colspan="4" class="der"><i class="fa fa-info-circle" title="El descuento de <?php echo $reg_descuento->valor . ' ' . $reg_descuento->tipo ?> se aplica sobre los artículos antes de sumarles los gastos de envío"> <?php echo $vocabulario_descuento_aplicado ?> <br>
										<?php echo obten_aplicacion_descuento($reg_descuento->aplicacion_descuento)->aplicacion_texto ?>
										<br> <?php echo $reg->descuento_aplicado . ' (' . $reg_descuento->valor . ' ' . $reg_descuento->tipo . ')' ?></td>
								<td>- <?php echo formatea_importe($reg->importe_descuento) ?> <?php echo $moneda ?></td>
							</tr>
						<?php } ?>
						<tr class="total envios">
							<td colspan="4" class="der"><?php echo $vocabulario_gastos_envio ?>:</td>
							<?php 
							if ($pais_envio == 'US'){ ?>
							<td><?php echo formatea_importe($reg->gastos_envio_div) ?> <?php echo $moneda ?></td>
							<?php }else{?>
							<td><?php echo formatea_importe($reg->gastos_envio) ?> <?php echo $moneda ?></td>
							<?php }?>
						</tr>
						<?php
						if ($pais_envio == 'US'){ 
							$total_pagar = $reg->total_sinenvio_div + $reg->gastos_envio_div - $reg->importe_descuento;
						}else{
							$total_pagar = $reg->total_sinenvio + $reg->gastos_envio - $reg->importe_descuento;
						}

						?>
						<tr class="total">
							<td colspan="4" class="der total"><?php echo $vocabulario_total ?> <span class="peque"><?php echo $vocabulario_iva_incluido ?></span>:</td>

							<td><b><?php echo formatea_importe($total_pagar) ?> <?php echo $moneda ?></b></td>

						</tr>	
						<?php
						if ($reg->tipo_impuesto == 2 || $reg->tipo_impuesto == 3 || $reg->tipo_impuesto == 6 || $reg->descuento_iva != 0) {
							$iva_es = obten_iva_books("ES");
							$importe_descuento_iva = $total_pagar - ($total_pagar / (1 + ($iva_es->iva / 100)));
						?>
							<tr class="descuento_iva total">
								<td colspan="4" class="der total"><?php echo $vocabulario_descuento_iva ?> <span class="peque">(21%)</span>:</td>
								<td><b>- <?php echo formatea_importe($importe_descuento_iva) ?> <?php echo $moneda ?></b></td>
							</tr>
							<tr class="descuento_iva total">
								<td colspan="4" class="der total"><?php echo $vocabulario_descuento_iva?></td>
								<td id="total_pagado"><b><?php echo formatea_importe($total_pagar - $importe_descuento_iva) ?> <?php echo $moneda ?></b></td>
							</tr>
						<?php }
						if ($reg->tipo_impuesto == 5) {
							$iva_es = obten_iva_books("ES")->iva;
							$importe_descuento_iva = $total_pagar - ($total_pagar / (1 + ($iva_es / 100)));
							$pais_envio = obten_dir_envio_user($reg->id_envio)->pais;
							$nombre_pais = obten_nombre_pais($pais_envio);
							$importe_final_sin_iva_es = $total_pagar - $importe_descuento_iva;
							$impuesto_iva_pais = obten_iva_pais($pais_envio);
							if ($pais_envio != 'ES'){
						?>
							<tr class="descuento_iva total">
								<td colspan="4" class="der total"><?php echo $vocabulario_impuesto_sobre_iva_espana ?><span class="peque"> (-21%)</span>:</td>
								<td><b>- <?php echo formatea_importe($importe_descuento_iva) ?> <?php echo $moneda ?></b></td>
							</tr>

							<?php
							if(formatea_importe($importe_final_sin_iva_es * (1 + $impuesto_iva_pais / 100) - $importe_final_sin_iva_es) > 0){
							?>
							<tr class="descuento_iva total">
								<td colspan="4" class="der total"><?php echo $vocabulario_impuesto_sobre_iva ?> <?php echo $nombre_pais ?><span class="peque"> (+<?php echo $impuesto_iva_pais ?>%)</span>:</td>
								<td><b>+ <?php echo formatea_importe($importe_final_sin_iva_es * (1 + $impuesto_iva_pais / 100) - $importe_final_sin_iva_es) ?> <?php echo $moneda ?></b></td>
							</tr>
							<?php 
							} ?>
							<tr class="descuento_iva total">
								<td colspan="4" class="der total"><?php echo $vocabulario_total_pagar ?></td>
								<td id="total_pagado"><b><?php echo formatea_importe($importe_final_sin_iva_es * (1 + $impuesto_iva_pais / 100)) ?> <?php echo $moneda ?></b></td>
							</tr>
						<?php } 							
						}?>
					</tbody>
				</table>
				<div class="sep20"></div>
				
				<div class="">
					<table class="table">
						<tbody>
							<?php
							$res_user = obten_datos_user($_SESSION['smart_user']['id']);
							$res_env = obten_dir_envio_user($reg->id_envio);
							$res_fact = obten_dir_fact_user($reg->id_facturacion);
							$res_pag = obten_metodo_pago($reg->metodo_pago);
							?>
							<tr class="datos izq dir">
								<td rowspan="1" colspan="2"><?php echo $vocabulario_datos_envio ?>:</td>
								<td colspan="1" class="izq dat">
									<i class="fa fa-user"></i>
									<?php echo $res_env->nombre . ', ' . $res_env->apellidos ?><br>
									<i class="fa fa-phone"></i>
									<?php echo $res_env->telefono ?><br>
									<i class="fa fa-map-marker-alt"></i>
									<?php echo $res_env->direccion . ', ' . $res_env->cp . ', ' . $res_env->localidad . ', ' . $res_env->provincia . ', ' . $res_env->pais ?>
								</td>
							</tr>
							<tr class="datos izq dir">
								<td rowspan="1" colspan="2"><?php echo $vocabulario_datos_facturacion ?>:</td>
								<td colspan="1" class="izq dat">
									<i class="fa fa-user"></i>
									<?php echo $res_fact->nombre . ', ' . $res_fact->apellidos ?><br>
									<i class="fa fa-phone"></i>
									<?php echo $res_fact->telefono ?><br>
									<i class="fa fa-map-marker-alt"></i>
									<?php echo $res_fact->direccion . ', ' . $res_fact->cp . ', ' . $res_fact->localidad . ', ' . $res_fact->provincia . ', ' . $res_fact->pais ?>
								</td>
							</tr>
							<tr class="datos izq dir">
								<td colspan="2" class=""><?php echo $vocabulario_metodo_pago_solo ?>:</td>
								<td colspan="1" class="izq dat">
									<i class="fas fa-euro-sign"></i>
									<?php echo $res_pag->nombre ?><br>
								</td>
							</tr>
							<tr class="datos izq dir">
								<td colspan="2" class=""><?php echo $vocabulario_estado_pago ?>:</td>
								<td colspan="1" class="izq dat">
								<?php if ($reg->estado_pago == 'Pendiente') { ?>
									<span class="estado" style="background-color: #ffc107;">
										<?php echo $vocabulario_pendiente; ?>
									</span>
								<?php } else { ?>
									<span class="estado" style="background-color: #4bdf4b;">
										<?php echo $vocabulario_pagado; ?>
									</span>
								<?php } ?>
									<br>
								</td>
							</tr>
							<tr class="datos izq dir">
								<td colspan="2" class=""><?php echo $vocabulario_fecha_pago ?>:</td>
								<td colspan="1" class="izq dat">
									<?php echo (is_null($reg->fecha_pago)) ? ' - ' : cambia_fecha_hora($reg->fecha_pago); ?><br>
								</td>
							</tr>
							<tr class="datos izq dir">
								<td colspan="2" class=""><?php echo $vocabulario_estado_envio ?>:</td>
								<td colspan="1" class="izq dat">
								<?php if ($reg->estado_envio == 'Pendiente') { ?>
									<span class="estado" style="background-color: #ffc107;">
										<?php echo $vocabulario_pendiente; ?>
									</span>
								<?php } else { ?>
									<span class="estado" style="background-color: #4bdf4b;">
										<?php echo $vocabulario_enviado; ?>
									</span>
								<?php } ?>
									<br>
								</td>
							</tr>
							<tr class="datos izq dir">
								<td colspan="2" class=""><?php echo $vocabulario_fecha_envio ?>:</td>
								<td colspan="1" class="izq dat">
									<?php echo cambiaFormatoFecha($reg->fecha_envio) ?><br>
								</td>
							</tr>
							<?php if (!empty($reg->transportista)) { ?>
								<tr class="datos izq dir">
									<td colspan="2" class=""><?php echo $vocabulario_transportista ?>:</td>
									<td colspan="1" class="izq dat">
										<?php echo $reg->transportista ?><br>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="btns">
						<button class="send cart_btn" data-bs-dismiss="modal"><?php echo $vocabulario_aceptar ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
