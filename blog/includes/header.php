<?php
include_once( dirname ( dirname ( __DIR__ ) ) . '/assets/lib/bbdd.php');
include_once( dirname ( dirname ( __DIR__ ) ) . '/assets/lib/class.carrito.php');
include_once( dirname ( dirname ( __DIR__ ) ) . '/assets/lib/funciones.php');

$carrito = new Carrito();

?>
		<header>
			<div id="mensaje"><p></p></div>
			<nav class="navbar navbar-expand-lg navbar-light">
			  <div class="container-fluid">
				<a class="navbar-brand logo" href="../"><img src="../assets/img/logo-smartcret.png" alt="Smartcret" title="Smartcret"></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
				  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<?php
				  		include( __DIR__ . '/menu.php');
				  ?>
					<li class="nav-item dropdown lang">
					  <a class="nav-link dropdown-toggle cambio_idioma" href="" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img class="menu-flag" src='../../assets/img/flags/fr.png'>FR</a>
					  <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
							<li><a class="dropdown-item cambio_idioma" href="../../"><img class="menu-flag" src='../../assets/img/flags/es.png'>ES</a></li>
							<li><a class="dropdown-item cambio_idioma" href="../../en/"><img class="menu-flag" src='../../assets/img/flags/gb.png'>EN</a></li>
							<li><a class="dropdown-item cambio_idioma" href="../../en-us/"><img class="menu-flag" src='../../assets/img/flags/en-us.png'>US</a></li>
							<li><a class="dropdown-item cambio_idioma" href="../../de/"><img class="menu-flag" src='../../assets/img/flags/de.png'>DE</a></li>
							<li><a class="dropdown-item cambio_idioma" href="../../it/"><img class="menu-flag" src='../../assets/img/flags/it.png'>IT</a></li>
						</ul>
					</li>
				  </ul>
				</div>
			  </div>
			</nav>
		</header>
