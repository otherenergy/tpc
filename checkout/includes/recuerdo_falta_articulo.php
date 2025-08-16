<?php
			$txt=array();
      $txt['¿Seguro que no te falta nada?']='ÊTES-VOUS SÛR DE NE RIEN MANQUER ?';
      $txt['Tranqui, esto sólo es un recordatorio. Más vale prevenir que curar. Por eso, te recordamos todos los productos que necesitas para hacer una reforma con microcemento y convertirte en un DIY lover más.']='Ne vous inquiétez pas, il s\'agit simplement d\'un rappel. Mieux vaut prévenir que guérir. C\'est pourquoi nous vous rappelons tous les produits dont vous avez besoin pour rénover avec du microciment et devenir un bricoleur de plus.';
      $txt['Añadir']='AJOUTER AU PANIER';
      $txt['LO TENGO TODO']='J\'AI TOUT';


			$productos_carro = $carrito->get_content();

			// ids de todos los productos que hay en el carrito
			$array_productos_carro = array ();
			foreach ( $productos_carro as $prod_carro ) {
				array_push ( $array_productos_carro, $prod_carro['id'] );
			}

			// ids de todos los productos del sistema de microcemento (ids 5 y 9 son primer Grip y kit herramienta)
			$array_productos_micro = array();
			$sql2 = "SELECT id FROM productos where id=5 OR id=9 OR es_variante in (3,4,6,7,8)";
			$res2=consulta( $sql2, $conn );
			while ( $reg2=$res2->fetch_object() ) {
				array_push ( $array_productos_micro, $reg2->id );
			}

			// ids de los productos de los cuales son variantes los productos del carrito
			$variantes_carrito = array();
			// ids de productos de microcemento que no tienen variantes
			$no_variantes=array( 5, 9 );
			foreach ( $productos_carro as $prod ) {

				if ( in_array ( $prod['id'], $no_variantes ) ) {

					array_push( $variantes_carrito, $prod['id'] );

				} else {

					$sql1 = "SELECT es_variante FROM productos WHERE id=" . $prod['id'];
					$res1=consulta( $sql1, $conn );
					array_push( $variantes_carrito, $res1->fetch_object()->es_variante );

				}
			}

			// ids de todos los articulos que son kits de microcemento
			$array_kits = array();
			$sql3 = "SELECT id FROM productos where es_variante in ( 1,2 )";
			$res3=consulta( $sql3, $conn );
			while ( $reg3=$res3->fetch_object() ) {
				array_push ( $array_kits, $reg3->id );
			}

			$contiene_kits = sizeof( array_intersect( $array_kits, $array_productos_carro ) );
			$contiene_productos_micro = sizeof ( array_intersect( $array_productos_micro, $array_productos_carro ) );


			if ( $contiene_kits==0 && $contiene_productos_micro > 0 ) {

				$idioma = obten_idioma_actual ();

				?>

				<div class="recuerda_productos">
					<div class="tit" translate-tag><?php echo $txt['¿Seguro que no te falta nada?'] ?></div>
					<div class="txt" translate-tag><?php echo $txt['Tranqui, esto sólo es un recordatorio. Más vale prevenir que curar. Por eso, te recordamos todos los productos que necesitas para hacer una reforma con microcemento y convertirte en un DIY lover más.'] ?></div>

					<div class="sep20"></div>

					<div class="lista_productos">

						<div class=" table-responsive">
						<table class="table carrito">
							<tbody>
								<?php

								$nombre = 'nombre_' . $idioma;
								$descripcion = 'descripcion_' . $idioma;
								$url = 'url_' . $idioma;

								// obtenemos los productos padres de microcemento
								$sql = "SELECT * FROM productos WHERE id IN (3,4,5,6,7,8,9)";
				        $res=consulta( $sql, $conn );

				        // listado de productos del sistema de microcemento que no están en el carrito
								while ( $reg=$res->fetch_object() ) {
										if ( !in_array( $reg->id, $variantes_carrito ) ) {
									?>

									<tr class="datos" uid="">
										<td class="img"><img translate-img src="../assets/img/<?php echo $reg->miniatura ?>" alt="<?php echo $reg->$nombre ?>" title="<?php echo $reg->$nombre ?>"></td>
										<td><div class="desc"><?php echo $reg->$nombre ?></div></td>
										<td class=""><a translate-tag translate-link type="button" class="cart_btn btn-tabla" href="../<?php echo $reg->$url ?>" target="_blank"><?php echo $txt['Añadir'] ?></a></td>
									</tr>

								<?php
										}
									}
								?>

							</tbody>
						</table>
					</div>

					</div>
					<div class="cierre center mt-5 mb-3">
						<button type="button" class="cart_btn" onclick="$('.recuerda_productos').slideUp()" translate-tag><?php echo $txt['LO TENGO TODO'] ?></button>
					</div>
				</div>

				<script>
					setTimeout(function() {
		        $('.recuerda_productos').slideDown(500)
		    	}, 2000);

				</script>


		<?php } ?>
