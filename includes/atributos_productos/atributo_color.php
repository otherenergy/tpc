<fieldset class="row col-md-12 btn-group" role="group">		
					<div class="txt-var">
						<?php echo $vocabulario_color ?>
						<span class="line"></span>
					</div>	
					<div class="col-md-12 btn-group" role="group">						
					<?php
					foreach ($variantes_color as $variante){
						$color=$variante->color;
						$precio_base_color=$variante->precio_base;
						$nombre_color=$variante->valor;
						$descuento=$variante->descuento;
						$color_ncs=$variante->color_ncs;
						$precio_descuento_color=number_format(($precio_base)*(100-$descuento)/100, 2, ".", "");
						$agotado=$variante->agotado;
						$vocabulario_envio_comillas= "'" . $vocabulario_envio . "'";

						echo '<div class="color formatos-colores-productos">';
						if ($agotado==1){
							echo '	<label class="sel-color agotado" color="'.$color.'" >';
							echo '		<div class="item">';
							if ($descuento != '0'){
								echo '<div style="background-color: #C93285 !important;" class="rebaja">-'.$descuento.'%</div>';
							}
							if (in_array($variante->es_variante, [394, 23, 394])){
								echo '			<img src="../assets/img/colores/'.$nombre_color.'.webp" alt="'.$vocabulario_color.' '.$nombre_color.'" title="'.$vocabulario_color.' '.$nombre_color.'" class="variacion" color="'.$color_ncs.'" width="40px" height="40px">';
							}else{
								echo '			<img src="../assets/img/colores/'.$nombre_color.'.jpg" alt="'.$vocabulario_color.' '.$nombre_color.'" title="'.$vocabulario_color.' '.$nombre_color.'" class="variacion" color="'.$color_ncs.'" width="40px" height="40px">';
							}
							echo '			<img src="../assets/img/disable.png" alt="'.$vocabulario_color_agotado.'" title="'.$vocabulario_color_agotado.'" class="disable" data-bs-toggle="tooltip" data-bs-placement="top">';
							echo '		</div>';
							echo '		<div style="text-align: center;"><small>'.$nombre_color.'</small></div>';
							echo '	</label>';
							echo '</div>';
							echo '<div class="leyenda_agotado"><img src="../assets/img/disable.png"/>'.$vocabulario_color_agotado.'</div>';
						} else {
							echo '	<label class="sel-color" color="'.$color.'">';
							echo '		<div class="item">';
							if ($descuento != '0'){
								echo '<div style="background-color: #C93285 !important;" class="rebaja">-'.$descuento.'%</div>';
							}
							if (in_array($variante->es_variante, [394, 23, 394])){
								echo '			<img src="../assets/img/colores/'.$nombre_color.'.webp" alt="'.$vocabulario_color.' '.$nombre_color.'" title="'.$vocabulario_color.' '.$nombre_color.'" class="variacion" color="'.$color_ncs.'" width="40px" height="40px">';
							}else{
								echo '			<img src="../assets/img/colores/'.$nombre_color.'.jpg" alt="'.$vocabulario_color.' '.$nombre_color.'" title="'.$vocabulario_color.' '.$nombre_color.'" class="variacion" color="'.$color_ncs.'" width="40px" height="40px">';
							}		
							// echo '			<p>Colores</p>';					
							echo '		</div>';
							echo '		<div style="text-align: center;"><small>'.$nombre_color.'</small></div>';
							echo '	</label>';
							echo '</div>';
						}
					}
					$url_llana_policarbonato = $userClass->urls(29, $id_idioma)[0]->valor;
					?>
					</div>
				</fieldset>
				<p style="margin-top: 10px; font-size: 12px;">*<strong><?php echo $vocabulario_aviso_color ?></strong> <?php echo $vocabulario_texto_aviso_color ?><a style="color: green;" href="./<?php echo $url_llana_policarbonato ?>">>>></a></p>
