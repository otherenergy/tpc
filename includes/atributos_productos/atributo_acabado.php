<fieldset class="row col-md-12 btn-group" role="group">
    <div class="txt-var">
        <?php echo $vocabulario_acabado ?>
        <span class="line"></span>
    </div>
    <div class="col-md-12 btn-group" role="group">
        <?php
        foreach ($unique_acabados as $variante) {
            $precio_base_acabado = $variante->precio_base;
            $descuento_acabado = $variante->descuento;
            $id_acabado = $variante->acabado;
            $valor_acabado = $variante->valor;
            $miniatura_acabado = $variante->miniatura;
            $precio_descuento_acabado = number_format(($precio_base_acabado) * (100 - $descuento_acabado) / 100, 2, ".", "");
            $agotado = $variante->agotado;
            $vocabulario_envio_comillas = "'" . $vocabulario_envio . "'";
        ?>

        <div class="acabado">
            <label class="sel-acabado" acabado="<?php echo $id_acabado ?>" acabado-src="../assets/img/acabado/<?php echo $valor_acabado ?>.png" >
                <img src="../assets/img/acabado/<?php echo $valor_acabado ?>.png" alt="<?php echo $vocabulario_acabado ?> <?php echo $valor_acabado ?>" title="<?php echo $vocabulario_acabado ?> <?php echo $valor_acabado ?>" class="variacion" width="40px" height="40px">
                <div style="text-align: center;"><small><?php echo $valor_acabado ?></small></div>
            </label>
        </div>

        <?php } ?>
    </div>
</fieldset>