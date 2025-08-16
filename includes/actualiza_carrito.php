<?php
session_start();
if (isset($_SESSION['nivel_dir']) && $_SESSION['nivel_dir'] == 2) {
    $ruta_link1 = "../../";
    $ruta_link2 = "../";
} else {
    $ruta_link1 = "../";
    $ruta_link2 = "./";
}

$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma=$id_idioma_global;

// echo $id_idioma;
include ('./db_connect.php');
include ('../class/userClass.php');
include ('../assets/lib/class.carrito.php');
require("../assets/lib/funciones.php");
include('./vocabulario.php');
$carrito = new Carrito();

$moneda_obj= $userClass->obtener_moneda();
$moneda = $moneda_obj->moneda;

// echo "hola";
// echo $su_pedido_ha_sido_eliminado;
if($carrito->articulos_total() > 0) { ?>

<table>
	<tr class="tit">
		<td colspan="2"><?php echo $vocabulario_mis_productos?> <i class="fa fa-times transform" onclick="$('#lista-productos').fadeOut(200)"></i></td>
	</tr>
	<?php
	$carro = $carrito->get_content();
	foreach($carro as $producto) { ?>

		<tr class="datos">
			<td><img src="<?php echo $ruta_link1 ?>assets/img/productos/<?php echo $producto["img"] ?>" alt="<?php echo $producto["nombre"] ?>"></td>
			<td>
				<div class="desc"><?php echo $producto["nombre"] ?></div>
				<div class="prec"><?php echo $producto["cantidad"] ?> x <?php echo formatea_importe ( $producto["precio"] ) ?> <?php echo $moneda ?></div>
				<div class="subtot"><?php echo formatea_importe ( $producto["cantidad"] * $producto["precio"] ) ?> <?php echo $moneda ?></div>
				<i class="fa fa-times transform" onclick="eliminaArticulo('<?php echo htmlspecialchars($producto['unique_id'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($ruta_link1, ENT_QUOTES, 'UTF-8'); ?>');"></i>

			</td>
		</tr>

	<?php } ?>
	<tr class="cantidad">
		<td><?php echo $vocabulario_unidades?>:</td>
		<td><?php echo $carrito->articulos_total() ?></td>
	</tr>
	<tr class="total">
		<td><?php echo $vocabulario_total ?>:</td>
		<td class="tot"><div><?php echo formatea_importe ( $carrito->precio_total() )?><?php echo $moneda ?></div></td>
	</tr>
	<tr class="bt_carrito">
	<?php if ( esta_logueado() ) { ?>
		<td colspan="2"><a class="btn_carrito" href="<?php echo $ruta_link1 ?>checkout" ><?php echo $vocabulario_procesar_compra?></a></td>
	<?php } else { ?>
		<td colspan="2"><a class="btn_carrito" href="<?php echo $ruta_link2 ?>login" ><?php echo $vocabulario_procesar_compra?></a></td>
	<?php } ?>
	</tr>
	<tr class="bt_carrito">
		<td colspan="2"><button class="btn_carrito_continuar_compra" onclick="fnc_ocultar_carrito_header()" ><?php echo $vocabulario_continuar_comprando?></button></td>
	</tr>

</table>

<?php } else { ?>

<table>
		<tr class="tit">
			<td colspan="2"><?php echo $vocabulario_mis_productos?> <i class="fa fa-times transform" onclick="$('#lista-productos').fadeOut(200)"></i></td>
		</tr>
		<tr class="bt_carrito">
			<td colspan="2"><div class="vacio" style="padding: 30px;line-height: 30px"><?php echo $vocabulario_tod_no_hay_prod_en_t_carrito ?></div></td>
		</tr>
	</table>

<?php } ?>