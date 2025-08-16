<?php

include_once($ruta_link1 . 'config/db_connect.php');
include_once($ruta_link1 . 'assets/lib/class.carrito.php');
include_once($ruta_link1 . 'assets/lib/funciones.php');
include_once($ruta_link1 . 'class/checkoutClass.php');
include_once($ruta_link1 . 'includes/urls.php');

$carrito = new Carrito();

$moneda_obj= $userClass->obtener_moneda();
$moneda = $moneda_obj->moneda;

$ver_idiomas=$userClass->ver_idiomas($id_url);

$rutaServer = $_ENV['RUTA_SERVER'];

$lista_demas_idiomas = [];
$lista_demas_idiomas_str = [];
$lista_demas_idiomas_str_nombres = [];
$lista_valor = [];
foreach ($ver_idiomas as $idioma) {
	if ($idioma->id == $id_idioma){
		$id_url_principal = $idioma->id;
		if ($idioma->pais == ""){
			$url_principal = $idioma->idioma;
		}else{
			$url_principal = $idioma->idioma . "-" . $idioma->pais;
		}
		if ($idioma->pais == '' || $idioma->pais == null){
			$str_principal = null;
		}else{
			$str_principal = strtoupper($idioma->pais);
		}
		$str_nombre_principal = $idioma->nombre_idioma;
	}else{
		$url_secundaria = $idioma->pais == "" ? $idioma->idioma : $idioma->idioma . "-" . $idioma->pais;
		if (!in_array($url_secundaria, $lista_demas_idiomas)) {
			// $lista_demas_idiomas_str[] = [
			// 	'idioma'=> $idioma->pais == "" ? strtoupper($idioma->idioma) : strtoupper($idioma->pais),
			// 	'nombre'=> $idioma->nombre_idioma,
			// ];
			$lista_demas_idiomas_str[] =$idioma->pais == "" ? strtoupper($idioma->idioma) : strtoupper($idioma->pais);
			$lista_demas_idiomas_str_nombres[] = $idioma->nombre_idioma;
			$lista_valor[] = $idioma->valor;
			$lista_demas_idiomas[] = $url_secundaria;
		}
	}
}

?>
		<header class="mess">
			<div id="mensaje"><p></p></div>
			<div id="mensajeAlerta"><p></p></div>
			<nav class="nav-header-principal">
			  	<div class="div-header-principal">
					<a class="enlace-logo-smartcret" href="<?php echo $ruta_link2 ?>">
						<nav class='contenedor-logo-smartcret'>
							<nav class='contenedor-moverse-logo-smartcret'>
								<img class="img-logo-smartcret-small" src="<?php echo $ruta_link1 ?>assets/img/logo-smartcret.webp" alt="Smartcret" title="Smartcret" width="120px" height="66.67px">
								<img class="img-logo-smartcret-big" src="<?php echo $ruta_link1 ?>assets/img/logo-smartcret.webp" alt="Smartcret" title="Smartcret" width="193px" height="107px">
							</nav>
						</nav>
						<img class="img-logo-smartcret img-logo-smartcret-small-grey" src="<?php echo $ruta_link1 ?>assets/img/iconos/icono_smartcret_logo_gris.png" alt="Smartcret" title="Smartcret" width="20px" height="20px">
					</a>
				  	<!-- MENU -->
				  	<div class="menu-header">
					  <img class="img-logo-smartcret ocultar" src="<?php echo $ruta_link1 ?>assets/img/iconos/logo_smartcret_blanco.webp" alt="Smartcret" title="Smartcret">
					  <ul class="menu_accesible">
							<li class="">
								<a class="" href="<?php echo $ruta_link2 ?><?php echo $link_microcemento_listo_al_uso ?>"><?php echo $vocabulario_microcemento_listo_al_uso ?></a>
							</li>
							<li class="barra_vertical_header" style="padding: 0px !important; margin: 0px !important;"></li>
							<li class="">
								<a class="" href="<?php echo $ruta_link2 ?><?php echo $link_pintura_smartcover ?>"><?php echo $vocabulario_pintura_azulejos ?></a>
							</li>
							<li class="barra_vertical_header" style="padding: 0px !important; margin: 0px !important;"></li>
							<li class="">
								<a class="" href="<?php echo $ruta_link2 ?><?php echo $link_hormigon_impreso ?>"><?php echo $vocabulario_hormigon_impreso ?></a>
							</li>
							<li class="barra_vertical_header" style="padding: 0px !important; margin: 0px !important;"></li>
							<li class="">
								<a class="" href="<?php echo $ruta_link2 ?>blog/">Blog</a>
							</li>
							<li class="barra_vertical_header" style="padding: 0px !important; margin: 0px !important;"></li>
							<li class="">
								<a class="" href="<?php echo $ruta_link2 ?><?php echo $link_tienda ?>"><?php echo $vocabulario_tienda ?></a>
							</li>
							<li class="barra_vertical_header" style="padding: 0px !important; margin: 0px !important;"></li>
							<li class="menu_oferta">
								<a class="" style="color: #c93285;" href="<?php echo $ruta_link2 ?><?php echo $vocabulario_enlace_tienda ?>"><?php echo $vocabulario_ofertas?>
									<img alt="icono descuento web" src="<?php echo $ruta_link1 ?>assets/img/iconos/DESCUENTO_WEB.png" alt="Ofertas" width="20px" height="20px" />
								</a>
							</li>
							<?php 
							if ($_SESSION['smart_user']['distribuidor'] == 1){
							?>
							<!-- <li class="barra_vertical_header" style="padding: 0px !important; margin: 0px !important;"></li>
							<li class="">
								<a class="" href="<?php echo $ruta_link2 ?><?php echo $link_tienda ?>">Panel Distribuidor</a>
							</li> -->
							<?php } ?>
						</ul>
					</div>

					<div class="opciones-header">
						<!-- IDIOMAS -->
						<div class="dropdown lang idiomas-header">
							<a class="dropdown-toggle" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="fas fa-globe"></i> <?php echo $vocabulario_idiomas ?>
							</a>
							<ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
							<li class='li-principal-idioma'>
								<p><?php echo $str_nombre_principal;?></p>
							</li>
							<?php
							$contador_idiomas_str = 0;
							foreach ($lista_demas_idiomas as $idioma) {
							?>
								<li>
								<!-- https://www.smartcret.com -->

									<a class="dropdown-item cambio_idioma" href="<?php echo $rutaServer ?>/<?php echo $idioma; ?><?php echo $ruta_adicional; ?><?php echo ($lista_valor[$contador_idiomas_str] == '/') ? '' : '/'; ?><?php echo $lista_valor[$contador_idiomas_str]; ?>">
										<?php 
										echo $lista_demas_idiomas_str_nombres[$contador_idiomas_str];
										?>
									</a>
								</li>
							<?php
							$contador_idiomas_str++;
							} ?>
							</ul>
						</div>

						<!-- PERFIL USUARIO -->
						<?php if ( esta_logueado() ) { ?>

						<div class="nav-item perfil-header">
							<div class="nav-link" role="button" style="cursor: pointer;" onclick="javascript:$('#menu-usuario').fadeIn(200);" aria-label="Menu usuario">
								<img class="logo_login" src="<?php echo $ruta_link1 ?>assets/img/iconos/logo_user.png" alt="Login" width="30px" height="30px">
							</div>

						</div>
						<div id="menu-usuario" style="display:none;">
							<table>
								<tr class="tit">
									<td colspan="2">@ <?php echo $_SESSION['smart_user']['nombre'] ?> <i class="fa fa-times transform" onclick="$('#menu-usuario').fadeOut(200)"></i></td>
								</tr>
							<?php 
							if ($_SESSION['smart_user']['distribuidor'] == 1){
							?>
								<tr class="link">
									<td><div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_datos ?>"><i class="far fa-user"></i>Panel Distribuidor</a></div></td>
								</tr>
							<?php 
							}else{
							?>
								<tr class="link">
									<td><div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_datos ?>"><i class="far fa-user"></i><?php echo $vocabulario_mis_datos ?></a></div></td>
								</tr>
								<tr class="link">
									<td><div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_pedidos ?>"><i class="fas fa-box"></i><?php echo $vocabulario_mis_pedidos?></a></div></td>
								</tr>
							<?php 
							}
							?>
								<tr>
									<td><div class="item exit"><a class="nav-link" href="javascript:exit('<?php echo $ruta_link1 ?>')"><i class="fas fa-sign-out-alt"></i><?php echo $vocabulario_cerrar_sesion?></a></div></td>
								</tr>
							</table>
						</div>
						<?php }else { ?>

						<div class="nav-item perfil-header">
							<a class="nav-link" href="<?php echo $ruta_link2 ?>login" aria-label="Login">
								<img class="logo_login" src="<?php echo $ruta_link1 ?>assets/img/iconos/logo_login.png" alt="Login" width="30px" height="30px">
							</a>
						</div>

						<?php } ?>

						<!-- CARRITO -->
						<div class="carrito-header" onclick="fnc_carrito_header()">
							<div class="btn-carrito" role="button" aria-label="Carrito" title="Carrito" onclick="javascript:void(0);" style="cursor: pointer;">
								<img src="<?php echo $ruta_link1 ?>assets/img/iconos/icono_carrito.png" alt="Carrito" title="Carrito" width="30px" height="30px">
								<span id="numprod" class="circ"><?php echo $carrito->articulos_total(); ?></span>
							</div>
						</div>

						<?php
						if($carrito->articulos_total() > 0) { ?>
						<div id="lista-productos" style="display: none;">
							<table>
								<tr class="tit">
									<td colspan="2"><?php echo $vocabulario_mis_productos?> <i class="fa fa-times transform" onclick="javascript:void(0)"></i></td>
									<!-- <td colspan="2">Mis productos <i class="fa fa-times transform" onclick="$('#lista-productos').fadeOut(200);$('.navbar-collapse').removeClass('show')"></i></td> -->
								</tr>
								<?php
								$carro = $carrito->get_content();
									foreach($carro as $producto) { ?>

										<tr class="datos">
											<td><img src="<?php echo $ruta_link1 ?>assets/img/productos/<?php echo $producto["img"] ?>" alt="<?php echo $producto["nombre"] ?>"></td>
											<td>
												<div class="desc"><?php echo $producto["nombre"] ?></div>
												<div class="prec"><?php echo $producto["cantidad"] ?> x <?php echo formatea_importe ( $producto["precio"] ) ?> €</div>
												<div class="subtot"><?php echo formatea_importe ( $producto["cantidad"] * $producto["precio"] ) ?> €</div>
												<i class="fa fa-times transform" onclick="eliminaArticulo('<?php echo htmlspecialchars($producto['unique_id'], ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($ruta_link1, ENT_QUOTES, 'UTF-8'); ?>');"></i>
											</td>
										</tr>

									<?php } ?>
									<tr class="cantidad">
										<td><?php echo $vocabulario_unidades ?>:</td>
										<td><?php echo $carrito->articulos_total() ?></td>
									</tr>
									<tr class="total">
										<td><?php echo $vocabulario_total ?>:</td>
										<td class="tot"><div><?php echo formatea_importe ( $carrito->precio_total() )?>€</div></td>
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
						</div>

						<?php } else { ?>

						<div id="lista-productos" style="display: none;">
							<table>
								<tr class="tit">
									<td colspan="2"><?php echo $vocabulario_mis_productos?> <i class="fa fa-times transform" onclick="$('#lista-productos').fadeOut(200)"></i></td>
								</tr>
								<tr class="bt_carrito">
									<td colspan="2"><div class="vacio" style="padding: 30px;line-height: 30px"><?php echo $vocabulario_tod_no_hay_prod_en_t_carrito ?></div></td>
								</tr>
							</table>
						</div>

						<?php } ?>

						<!-- MENU HAMBURGUESA -->
						<ul class="menu-hamburguesa" onclick="fnc_menu_hamburguesa()">
							<li></li>
							<li></li>
							<li></li>
						</ul>
						<i id="close-hamburguesa" onclick="fnc_menu_hamburguesa()" class="fa fa-times ocultar"></i>
					</div>
			  	</div>
			</nav>
		</header>



		<?php //include_once('array_precios.php') ?>


		<!-- ESTO ES EL CAMBIO DE IDIOMA -->
		<!-- <p>CAMBIAR IDIOMA SIN VACIAR CARRITO</p>

		<ul>
		<?php

		$ver_idiomas=$userClass->ver_idiomas($id_url);

		$lista_demas_idiomas = [];
		$lista_demas_idiomas_str = [];
		$lista_valor = [];
		foreach ($ver_idiomas as $idioma) {

			$url_secundaria = $idioma->pais == "" ? $idioma->idioma : $idioma->idioma . "-" . $idioma->pais;
			if (!in_array($url_secundaria, $lista_demas_idiomas)) {
				$lista_demas_idiomas_str[] = $idioma->pais == "" ? strtoupper($idioma->idioma) : strtoupper($idioma->pais);
				$lista_valor[] = ( $idioma->valor != '/' ) ? $idioma->valor : '';
				$lista_demas_idiomas[] = $url_secundaria;
			}

		}

		$contador_idiomas_str = 0;
		foreach ($lista_demas_idiomas as $idioma) {
		?>
			<li>
				<a class="" href="<?php echo $ruta_link1 ?><?php echo $idioma?>/<?php echo $lista_valor[$contador_idiomas_str]?>">
					<img class="menu-flag" src='<?php echo $ruta_link1 ?>assets/img/flags/<?php echo $idioma?>.png'>
					<?php echo $lista_demas_idiomas_str[$contador_idiomas_str]?>
				</a>
			</li>
		<?php
		$contador_idiomas_str++;
		} ?>
		</ul> -->
		<!-- ESTO ES EL FINAL DE CAMBIO DE IDIOMA -->

		<script>

			$('#input_localizacion').change(function() {

			    $.ajax({
			        url: '<?php echo $ruta_link1 ?>/class/control.php',
			        type: 'post',
			        dataType: 'text',
			        data: {accion: 'modifica_ubicacion', input_pais: $(this).val()}
			    })
			    .done(function(result) {
			        var result = $.parseJSON(result);
			        // window.location.href='<?php echo $dominio ?>' + result.msg;
			        muestraMensajeLn(result.msg);
			        setTimeout(function() {
								location.reload();
							},2000);

			    })
			    .fail(function() {
			        alert("error");
			    });
			});
		</script>