<fieldset class="row col-md-12 btn-group" role="group">
    <div class="txt-var">
        <?php echo $vocabulario_formato ?>
        <span class="line"></span>
    </div>
    <div class="col-md-12 btn-group" role="group">
        <?php
        foreach ($unique_formatos as $variante) {
            $precio_base_formato = $variante->precio_base;
            $descuento_formato = $variante->descuento;
            $id_formato = $variante->formato;
            $valor_formato = $variante->valor;
            $miniatura_formato = $variante->miniatura;
            $precio_descuento_formato = number_format(($precio_base_formato) * (100 - $descuento_formato) / 100, 2, ".", "");
            $agotado = $variante->agotado;
            $vocabulario_envio_comillas = "'" . $vocabulario_envio . "'";
        ?>

        <div class="formato">
            <label class="form-check-label sel-formato" formato="<?php echo $id_formato ?>" formato-src="../assets/img/productos/<?php echo $miniatura_formato ?>" >
                <img src="../assets/img/productos/<?php echo $miniatura_formato ?>" alt="<?php echo $vocabulario_formato ?> <?php echo $valor_formato ?>" title="<?php echo $vocabulario_formato ?> <?php echo $valor_formato ?>" class="variacion" width="40px" height="40px">
                <div style="text-align: center;"><small><?php echo $valor_formato ?></small></div>
            </label>
        </div>

        <?php } ?>
    </div>
</fieldset>