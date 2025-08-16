<?php
include_once('../../assets/lib/bbdd.php');
include_once('../../assets/lib/class.carrito.php');
include_once('../../assets/lib/funciones.php');

$carrito = new Carrito();

if($carrito->articulos_total() > 0) { ?>

<table>
	<tr class="tit">
		<td colspan="2">Mis productos <i class="fa fa-times transform" onclick="$('#lista-productos').fadeOut(200)"></i></td>
	</tr>
	<?php
	$carro = $carrito->get_content();
	foreach($carro as $producto) { ?>

		<tr class="datos">
			<td><img src= "../assets/img/<?php echo $producto["img"] ?>" alt="<?php echo $producto["nombre"] ?>"></td>
			<td>
				<div class="desc"><?php echo $producto["nombre"] ?></div>
				<div class="prec"><?php echo $producto["cantidad"] ?> x <?php echo formatea_importe ( $producto["precio"] ) ?> €</div>
				<div class="subtot"><?php echo formatea_importe ( $producto["cantidad"] * $producto["precio"] ) ?> €</div>
				<i class="fa fa-times transform" onclick="eliminaArticuloCalc('<?php echo $producto["unique_id"] ?>'); actualizaCarritoCalc()"></i>
			</td>
		</tr>

	<?php } ?>
	<tr class="cantidad">
		<td>Unidades:</td>
		<td><?php echo $carrito->articulos_total() ?></td>
	</tr>
	<tr class="total">
		<td>TOTAL:</td>
		<td class="tot"><div><?php echo formatea_importe ( $carrito->precio_total() )?>€</div></td>
	</tr>
	<tr class="bt_carrito">
		<td colspan="2"><a class="btn_carrito" href="../carrito" >Ir al carrito</a></td>
	</tr>

</table>


<?php } else { ?>

<table>
		<tr class="tit">
			<td colspan="2">Mis productos <i class="fa fa-times transform" onclick="$('#lista-productos').fadeOut(200)"></i></td>
		</tr>
		<tr class="bt_carrito">
			<td colspan="2"><div class="vacio" style="padding: 30px;line-height: 30px">Todavía no hay productos en tu carrito</div></td>
		</tr>
	</table>

<?php } ?>