<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "hola";
?>
<tr class="descuento aplicado" title="El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío">
    <td class="izq nom_cup">
	<!-- <?php
		echo "hola";
		?> -->
        <span class="aplica_desc"><?php echo $vocabulario_descuento_aplicado ?></span>

        <?php
        $descuento = $checkout->obten_descuento($_SESSION['codigo_descuento']['id']);

        if ($descuento->aplicacion_descuento != 1) {
            $aplicacion_descuento = $checkout->obten_aplicacion_descuento($_SESSION['codigo_descuento']['aplicacion']);
            ?>
            <span class="aplicacion_descuento">(<?php echo $aplicacion_descuento->aplicacion_texto ?>)</span><br>
        <?php } ?>

        <span><?php echo $_SESSION['codigo_descuento']['nombre'] ?><i class="fa fa-info-circle"></i></span>
        <span class="tipo_desc"><?php echo $_SESSION['codigo_descuento']['valor'] . ' ' . $_SESSION['codigo_descuento']['tipo'] ?></span>
    </td>

    <td class="der importe_descuento">
        <?php
        // echo $_SESSION['codigo_descuento']['aplicacion'];
        if ($_SESSION['codigo_descuento']['aplicacion'] != 1) {
            ?>
            <span>
                <?php
                if ($_SESSION['codigo_descuento']['tipo'] == '%') {
                    $aplicacion_descuento = $checkout->obten_aplicacion_descuento($_SESSION['codigo_descuento']['aplicacion']);
                    $id_prods_descuento = $aplicacion_descuento->id_prods;
                    $array_id_prods_descuento = explode('|', $id_prods_descuento);

                    $carro = $carrito->get_content();
                    $importe_prod_descuento = 0;

                    foreach ($carro as $producto) {
                        $obten_descuento_producto = $checkout->obten_descuento_producto($producto['id'], $_SESSION['user_ubicacion']);
                        if ($obten_descuento_producto == 0){
                            if (in_array($producto['id'], $array_id_prods_descuento)) {
                                $importe_prod_descuento += $producto["cantidad"] * $producto["precio"];
                            }
                        }
                    }

                    $importe_descuento = $importe_prod_descuento * ($_SESSION['codigo_descuento']['valor'] / 100);
                } elseif ($_SESSION['codigo_descuento']['tipo'] == '€') {
                    $importe_descuento = $_SESSION['codigo_descuento']['valor'];
                }

                echo '- ' . $checkout->formatea_importe($importe_descuento) . ' ' . $moneda;
                ?>
            </span>
            <?php
        } else {
            ?>
            <span>
                <?php
                if ($_SESSION['codigo_descuento']['tipo'] == '%') {
                    $aplicacion_descuento = $checkout->obten_aplicacion_descuento($_SESSION['codigo_descuento']['aplicacion']);
                    $carro = $carrito->get_content();
                    $importe_prod_descuento = 0;

                    foreach ($carro as $producto) {
                        $obten_descuento_producto = $checkout->obten_descuento_producto($producto['id'], $_SESSION['user_ubicacion']);
                        if ($obten_descuento_producto == 0){
                            // echo "Hola";
                            $importe_prod_descuento += $producto["cantidad"] * $producto["precio"];
                        }

                        // echo $importe_prod_descuento;
                    }
                    $importe_descuento = $importe_prod_descuento * ($_SESSION['codigo_descuento']['valor'] / 100);
                    // echo $importe_descuento;
                } elseif ($_SESSION['codigo_descuento']['tipo'] == '€') {
                    $importe_descuento = $_SESSION['codigo_descuento']['valor'];
                }
                echo '- ' . $checkout->formatea_importe($importe_descuento) . ' ' . $moneda;
                ?>
            </span>
            <?php
        }
        ?>
    </td>
</tr>

<tr>
    <td align="center" colspan="2">
        <button type="button" id="elimina_descuento" class="cart_btn out" onclick='eliminaCupon()' style="font-size: 12px;padding: 2px 7px;background: #92bf23;"><?php echo $vocabulario_eliminar_descuento ?></button>
    </td>
</tr>
