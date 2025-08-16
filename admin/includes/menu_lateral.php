<div class="col-md-2 menu-lat">
	<div class="menu-panel">
		<table>
			<thead>
				<tr class="admin_logo">
					<td colspan="2"><a class="navbar-brand logo" href="../"><img src="../assets/img/logo-smartcret.png" alt="Smartcret" title="Smartcret"></a></td>
				</tr>
			</thead>
				<tr class="tit">
					<td colspan="2"><i class="fa fa-user log" title="Administrador <?php echo $_SESSION['smart_user_admin']['nombre'] ?>"></i><?php echo $_SESSION['smart_user_admin']['nombre'] ?></td>
				</tr>
			</thead>
			<tbody>

				<?php  if ($_SESSION['smart_user_admin']['role'] == 3 || $_SESSION['smart_user_admin']['role'] == 4 || $_SESSION['smart_user_admin']['role'] == 5 ) { ?>

				<tr class="link">
					<td style="position:relative"><div class="item prep_ped"><a class="nav-link" href="./preparacion-pedidos"><i class="fas fa-box-open"></i>Preparación pedidos</a></div></td>
				</tr>
				<tr class="link">
						<td><div class="item color_mas"><a class="nav-link" href="./colores-venta"><i class="fas fa-palette"></i>Colores más vendidos</a></div></td>
					</tr>

				<?php } ?>

				<?php  if ($_SESSION['smart_user_admin']['role'] != 3 && $_SESSION['smart_user_admin']['role'] != 4 && $_SESSION['smart_user_admin']['role'] != 5 ) { ?>

					<tr class="link">
						<td style="position:relative"><div class="item ped"><a class="nav-link" href="./"><i class="fa fa-truck"></i>Pedidos</a><span class="oculto new_order">Nuevo!</span></div></td>
					</tr>
					<tr class="link">
						<td><div class="item clie"><a class="nav-link" href="./clientes?compras=0"><i class="fas fa-users"></i>Clientes</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item prod"><a class="nav-link" href="./articulos?vars=0"><i class="fas fa-box"></i>Articulos</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item cup"><a class="nav-link" href="./cupones-descuento?activo=0"><i class="fas fa-money-check-alt"></i>Cupones descuento</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item newser"><a class="nav-link" href="./usuarios-newsletter"><i class="fas fa-envelope"></i>Newsletter (usuarios)</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item formo"><a class="nav-link" href="./formulario-opinion"><i class="fa fa-question"></i>Formulario opinión</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item avis"><a class="nav-link" href="./avisos-web"><i class="fas fa-bullhorn"></i>Avisos web</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item intra"><a class="nav-link" href="./ventas-intracomunitarias"><i class="fas fa-globe-europe"></i>Ventas UE no VIES</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item estad"><a class="nav-link" href="./estadisticas?muestra=importes&periodo=2024"><i class="fas fa-chart-line"></i>Estadisticas</a></div></td>
					</tr>
					<tr class="link">
						<td><div class="item color"><a class="nav-link" href="./colores-web"><i class="fas fa-palette"></i>Colores on/off</a></div></td>
					</tr>

					<?php  if ($_SESSION['smart_user_admin']['role'] == 2 || $_SESSION['smart_user_admin']['role'] == 10) { ?>
						<tr class="link">
							<td><div class="item descuentos"><a class="nav-link" href="./modifica-descuentos"><i class="fas fa-percent"></i>Config. descuentos</a></div></td>
						</tr>
					<?php } ?>

					<tr class="link">
						<td style="position:relative"><div class="item prep_ped"><a class="nav-link" href="./preparacion-pedidos?v=Pendiente"><i class="fas fa-box-open"></i>Preparación pedidos</a></div></td>
					</tr>

					<tr>
						<td><div class="sep30"></div></td>
					</tr>
					<tr class="link">
						<td><div class="item creaso"><a class="nav-link" href="../assets/return/envio_tpv" target="_blank"><i class="fas fa-file-upload"></i>Crear orden venta Books</a></div></td>
					</tr>

					<?php  if ( $_SESSION['smart_user_admin']['role'] == 10 ) { ?>

						<tr class="link">
							<td><div class="item creaso"><a class="nav-link" href="https://canales.redsys.es/" target="_blank"><i class="fas fa-credit-card"></i>Acceso Redsys TPV</a></div></td>
						</tr>
						<tr class="link">
							<td><div class="item creaso"><a class="nav-link" href="llamadas-api" target="_blank"><i class="fas fa-wifi"></i>Llamadas API</a></div></td>
						</tr>
						<tr class="link">
							<td>
								<div class="item exit">
									<a class="nav-link"
									 href="javascript:void(0)"
									 url="form_config"
									 onclick="openModalConf( $(this).attr('url') )"><i class="fas fa-cogs"></i>Configuración</a>
									</div>
							</td>
						</tr>
					<?php } ?>

				<?php } ?>

				<tr>
					<td><div class="sep30"></div></td>

				<tr class="link">
					<td><div class="item exit"><a class="nav-link" href="javascript:exit()"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a></div></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>