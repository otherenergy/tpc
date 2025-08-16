				<fieldset class="row col-md-12 btn-group" role="group">		
					<div class="txt-var">
						<?php echo $vocabulario_juntas ?>
						<span class="line"></span>
					</div>						
					<div class="col-md-12 btn-group" role="group">

                    <?php
					  	foreach ($variantes_junta as $variante){
							$precio_base_junta=$variante->precio_base;
							$descuento_junta=$variante->descuento;
							$id_junta = $variante->junta;
							$valor_junta=$variante->valor;
							$miniatura_junta=$variante->miniatura;
							$precio_descuento_junta=number_format(($precio_base_junta)*(100-$descuento_junta)/100, 2, ".", "");
							$agotado=$variante->agotado;
							$vocabulario_envio_comillas= "'" . $vocabulario_envio . "'";
                    ?>

					  <div class="form-check form-check-inline">
						<label class="form-check-label sel-juntas" juntas="<?php echo $id_junta ?>"  junta-src="../assets/img/<?php echo $miniatura_junta ?>" onclick="sustituir_precio(<?php echo $precio_base_junta . ', ' . $precio_descuento_junta . ', ' . $vocabulario_envio_comillas; ?>)">
						  <img src="../assets/img/<?php echo $miniatura_junta ?>" alt="<?php echo $vocabulario_junta ?> <?php echo $valor_junta ?>" title="<?php echo $vocabulario_junta ?> <?php echo $valor_junta ?>" class="variacion">
						  <div style="text-align: center;"><small><?php echo $valor_junta ?></small></div>
						</label>
					  </div>
        
                    <?php } ?>
					</div>

				</fieldset>