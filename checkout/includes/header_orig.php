<?php
include_once('../assets/lib/bbdd.php');
include_once('../assets/lib/class.carrito.php');
include_once('../assets/lib/funciones.php');

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
<!-- 				  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<?php
				  		include('./includes/menu.php');
				  ?>
					<li class="nav-item dropdown lang">
					  <a class="nav-link dropdown-toggle" href="" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">ES</a>
					  <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarDarkDropdownMenuLink">
						<li><a class="dropdown-item" href="../en/">EN</a></li>
						<li><a class="dropdown-item" href="../fr/">FR</a></li>
					  </ul>
					</li>
				  </ul> -->
				</div>
			  </div>
			</nav>
		</header>
