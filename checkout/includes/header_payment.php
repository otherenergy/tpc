<?php
include_once('../assets/lib/bbdd.php');
include_once('../assets/lib/class.carrito.php');
include_once('../assets/lib/funciones.php');

$carrito = new Carrito();

?>
		<header>
			<div id="mensaje"><p></p></div>
					<div class="row">
						<div class="col-sm-2">
							<a class="navbar-brand logo" href="../"><img src="../assets/img/logo-smartcret.png" alt="Smartcret" title="Smartcret"></a>
						</div>
						<div class="col-sm-8">
							<div class="row punto-pedido">
								<div class="col-md-2"><div class="linea-punto ped punto-activo"><div class="circulo rell" onclick="window.location.href='./'"><span class="num"><i class="ok fas fa-check"></i></span></div><p>Mon panier</p></div></div>
								<div class="col-md-1 lin-proc"><div class="ln"></div></div>
								<div class="col-md-2"><div class="linea-punto env "><div class="circulo rell" onclick="window.location.href='adresses'"><span class="num"><i class="ok fas fa-check"></i></span></div><p>Adresse de livraison</p></div></div>
								<div class="col-md-1 lin-proc"><div class="ln"></div></div>
								<div class="col-md-2"><div class="linea-punto pag"><div class="circulo rell"><span class="num">3</span></div><p>Méthode de paiement</p></div></div>
								<div class="col-md-1 lin-proc opac-50"><div class="ln"></div></div>
								<div class="col-md-2 opac-50"><div class="linea-punto conf"><div class="circulo"><span class="num">4</span></div><p>Résumé</p></div></div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="row">
								<?php if ( esta_logueado() ) { ?>
										<span class="cab perfil">
													<a class="nav-link" href="../panneau/mes-donnees"><i class="fa fa-user log"></i><?php echo $_SESSION['smart_user']['nombre'] ?></a>
										</span>
									<?php }else { ?>
										<span class="cab perfil off">
													<a class="nav-link" href="../login"><i class="far fa-user"></i></a>
										</span>
								<?php } ?>
							</div>
						</div>
					</div>
			</nav>
		</header>
