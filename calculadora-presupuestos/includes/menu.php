<li class="nav-item">
	<a class="nav-link" href="../microcemento-listo-al-uso">Microcemento listo al uso</a>
</li>
<li class="nav-item">
	<a class="nav-link" href="../pintura-azulejos-smartcover">Pintura Azulejos</a>
</li>
<li class="nav-item">
	<a class="nav-link" href="../hormigon-impreso">Hormigón impreso</a>
</li>
<li class="nav-item">
	<a class="nav-link" href="../tienda-microcemento">Tienda</a>
</li>
<li class="nav-item">
	<a class="nav-link" href="./">Blog</a>
</li>

<?php if ( esta_logueado() ) { ?>

<li class="nav-item perfil">
			<a class="nav-link" href="javascript:$('#menu-usuario').fadeIn(200);"><i class="fa fa-user log"></i></a>
</li>
<div id="menu-usuario" style="display: none;">
	<table>
		<tr class="tit">
			<td colspan="2">@ <?php echo $_SESSION['smart_user']['nombre'] ?> <i class="fa fa-times transform" onclick="$('#menu-usuario').fadeOut(200)"></i></td>
		</tr>
		<tr class="link">
			<td><div class="item"><a class="nav-link" href="../panel/mis-datos"><i class="far fa-user"></i>Mis datos</a></div></td>
		</tr>
		<tr class="link">
			<td><div class="item"><a class="nav-link" href="../panel/mis-pedidos"><i class="fas fa-box"></i>Mis Pedidos</a></div></td>
		</tr>
		<tr>
			<td><div class="item exit"><a class="nav-link" href="javascript:exit(2)"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a></div></td>
		</tr>
	</table>
</div>
<?php }else { ?>

<li class="nav-item perfil">
			<a class="nav-link" href="../login"><i class="far fa-user"></i></a>
</li>

<?php } ?>


<li class="nav-item carrito">
	<a class="nav-link" href="javascript:void(0)" alt="Carrito" title="Carrito" rel="nofollow"><img src="../assets/img/iconos/carrito.png" alt="Carrito" title="Carrito" style="width: 25px;"><span id="numprod" class="circ"><?php echo $carrito->articulos_total() ?></span></a>
</li>


<?php
if($carrito->articulos_total() > 0) { ?>
<div id="lista-productos" style="display: none;">
	<table>
		<tr class="tit">
			<td colspan="2">Mis productos <i class="fa fa-times transform" onclick="javascript:void(0)"></i></td>
		</tr>
		<?php
		  $carro = $carrito->get_content();
			foreach($carro as $producto) { ?>

				<tr class="datos">
					<td><img src="../assets/img/<?php echo $producto["img"] ?>" alt="<?php echo $producto["nombre"] ?>"></td>
					<td>
						<div class="desc"><?php echo $producto["nombre"] ?></div>
						<div class="prec"><?php echo $producto["cantidad"] ?> x <?php echo $producto["precio"]?> €</div>
						<div class="subtot"><?php echo $producto["cantidad"] * $producto["precio"] ?> €</div>
						<i class="fa fa-times transform" onclick="eliminaArticuloCalc('<?php echo $producto["unique_id"] ?>');actualizaCarritoCalc()"></i>
					</td>
				</tr>

			<?php } ?>
			<tr class="cantidad">
				<td>Unidades:</td>
				<td><?php echo $carrito->articulos_total() ?></td>
			</tr>
			<tr class="total">
				<td>TOTAL:</td>
				<td class="tot"><div><?php echo $carrito->precio_total()?>€</div></td>
			</tr>
			<tr class="bt_carrito">
				<td colspan="2"><a class="btn_carrito" href="../carrito" >Ir al carrito</a></td>
			</tr>
	</table>
</div>


<?php } else { ?>

	<div id="lista-productos" style="display: none;">
	<table>
		<tr class="tit">
			<td colspan="2">Mis productos <i class="fa fa-times transform" onclick="$('#lista-productos').fadeOut(200)"></i></td>
		</tr>
		<tr class="bt_carrito">
			<td colspan="2"><div class="vacio" style="padding: 30px;line-height: 30px">Todavía no hay productos en tu carrito</div></td>
		</tr>
	</table>
</div>



<?php } ?>


