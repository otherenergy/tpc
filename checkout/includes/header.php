<?php
include_once('../assets/lib/bbdd.php');
include_once('../assets/lib/class.carrito.php');
include_once('../assets/lib/funciones.php');

$carrito = new Carrito();

?>
		<header>
			<div id="mensaje"><p></p></div>
					<div class="row cab_proceso">
						<div class="col-sm-2">
							<a class="navbar-brand logo" href="../"><img src="../assets/img/logo-smartcret.png" alt="Smartcret" title="Smartcret"></a>
						</div>
						<div class="col-sm-8 mt-70">
							<!-- <div class="linea-pedido"></div> -->
							<div class="row punto-pedido">
								<div class="col-md-2"><div class="linea-punto ped punto-activo"><div class="circulo rell" onclick="window.location.href='/carrito'"><span class="num"><i class="ok fas fa-check"></i></span></div><p>Mi carrito</p></div></div>
								<div class="col-md-1 lin-proc"><div class="ln"></div></div>
								<div class="col-md-2"><div class="linea-punto env "><div class="circulo rell"><span class="num">2</span></div><p>Dirección envío</p></div></div>
								<div class="col-md-1 lin-proc opac-50"><div class="ln"></div></div>
								<div class="col-md-2 opac-50"><div class="linea-punto pag"><div class="circulo"><span class="num">3</span></div><p>Método pago</p></div></div>
								<div class="col-md-1 lin-proc opac-50"><div class="ln"></div></div>
								<div class="col-md-2 opac-50"><div class="linea-punto conf"><div class="circulo"><span class="num">4</span></div><p>Resumen</p></div></div>
							</div>
						</div>
					</div>
			</nav>
		</header>
