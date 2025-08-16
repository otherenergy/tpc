<?php

$_SESSION['ruta_link1'] = $ruta_link1;

$ver_idiomas=$userClass->ver_idiomas($id_url);

$lista_demas_idiomas = [];
$lista_demas_idiomas_str = [];
$lista_valor = [];
foreach ($ver_idiomas as $idioma) {
	if ($idioma->id == $id_idioma){
		$id_url_principal = $idioma->id;
		$valor_idioma_principal =$idioma->valor;
		if ($idioma->pais == ""){
			$url_principal = $idioma->idioma;
			$str_principal = strtoupper($idioma->idioma);
		}else{
			$url_principal = $idioma->idioma . "-" . $idioma->pais;
			$str_principal = strtoupper($idioma->pais);
		}
	}else{
		$url_secundaria = $idioma->pais == "" ? $idioma->idioma : $idioma->idioma . "-" . $idioma->pais;
		if (!in_array($url_secundaria, $lista_demas_idiomas)) {
			$lista_demas_idiomas_str[] = $idioma->pais == "" ? strtoupper($idioma->idioma) : strtoupper($idioma->pais);
			$lista_valor[] = $idioma->valor;
			$lista_demas_idiomas[] = $url_secundaria;
		}
	}
}

?>

<head>
	<title><?php echo $url_metas->title;?></title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="language" content="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">
	<!-- Etiquetas META SEO -->
	<meta name="robots" content="index,follow">
	<meta name="description" content="<?php echo $url_metas->description;?>">

	<meta property="og:locale" content="<?php echo (strpos($idioma_url, '-') === false) ? $idioma_url . '-' . strtoupper($idioma_url) : strtolower(explode('-', $idioma_url)[0]) . '-' . strtoupper(explode('-', $idioma_url)[1]); ?>">

	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php echo $url_metas->title;?>">
	<meta property="og:description" content="<?php echo $url_metas->description;?>">
	<meta property="og:url" content="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>">
	<meta property="og:site_name" content="Smartcret">
	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="<?php echo $url_metas->title;?>">
	<meta name="twitter:description" content="<?php echo $url_metas->description;?>">
	<meta name="twitter:site" content="https://www.smartcret.com">
	<meta name="twitter:creator" content="@SmartcretD">
	<meta name="google-signin-client_id" content="623607501139-jptk33ljlc57vi8e7ll1miju73copgfl.apps.googleusercontent.com">
	<link rel="preload" href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swapp" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
	<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500&amp;display=swap" rel="stylesheet">

	<noscript>
  		<link async href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap" rel="stylesheet" type="text/css"/>
	</noscript>
	
	<link rel="canonical" href="https://www.smartcret.com/<?php echo $url_principal ?><?php echo $ruta_adicional ?>/<?php echo ($valor_idioma_principal == '/') ? '' : $valor_idioma_principal; ?>"  />
	<link rel="alternate" href="https://www.smartcret.com/<?php echo $url_principal ?><?php echo $ruta_adicional ?>/<?php echo ($valor_idioma_principal == '/') ? '' : $valor_idioma_principal; ?>" hreflang="<?php echo (strpos($url_principal, '-') === false) ? $url_principal . '-' . strtoupper($url_principal) : strtolower(explode('-', $url_principal)[0]) . '-' . strtoupper(explode('-', $url_principal)[1]); ?>" />
<?php
// echo $url_principal;
$contador_idiomas_str = 0;

foreach ($lista_demas_idiomas as $idioma) {
	if ($lista_valor[$contador_idiomas_str] != ''){
?>
	<link rel="alternate" href="https://www.smartcret.com/<?php echo $idioma?><?php echo $ruta_adicional ?><?php echo ( $lista_valor[$contador_idiomas_str] == '/') ? '': '/'; ?><?php echo $lista_valor[$contador_idiomas_str]?>" hreflang="<?php echo (strpos($idioma, '-') === false) ? $idioma . '-' . strtoupper($idioma) : strtolower(explode('-', $idioma)[0]) . '-' . strtoupper(explode('-', $idioma)[1]); ?>" />
<?php
	};
	$contador_idiomas_str++;
} ?>

	<!-- Estilos CSS -->
	<link rel='stylesheet' href='<?php echo $ruta_link1 ?>assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' />
	<link rel="icon" href="<?php echo $ruta_link1 ?>assets/img/favicon.png">

	<!-- Estilos CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	
	<!-- Bootstrap 4.6.0 -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
	
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- Bootstrap 5.0.0-beta1 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

	<!-- Bootstrap 5.0.0-beta1 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>


	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-169820052-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'UA-169820052-1');
		gtag('config', 'AW-1001206950');
	</script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-1001206950"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'AW-1001206950');
	</script>
	<script>
		gtag('event', 'conversion', {
			'send_to': 'AW-1001206950/NNt5CLbl_o8DEKbptN0D',
			'transaction_id': ''
		});
	</script>
</head>

<?php
include_once($ruta_link1 . 'assets/lib/class.carrito.php');
$carrito = new Carrito();


if (isset($_SESSION['id_idioma']) && $_SESSION['id_idioma'] != $id_idioma) {
    $_SESSION['id_idioma'] = $id_idioma;
	// echo "NO";
	if($carrito->articulos_total() > 0) {
?>
<!-- <nav id="nav_cambio_idioma"></nav> -->
<?php
	}
} else {
    $_SESSION['id_idioma'] = $id_idioma;
	// echo "YES";
}
$_SESSION['idioma_url'] = $idioma_url;
?>


<script type="text/javascript">
	var idiomaUrl = <?php echo json_encode($_SESSION['idioma_url']); ?>;
	var ruta_link = <?php echo json_encode($_SESSION['ruta_link1']); ?>;
</script>


