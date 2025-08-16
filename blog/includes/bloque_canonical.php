<?php
$idioma_actual = 'es';
// $root = realpath($_SERVER["DOCUMENT_ROOT"]);
// $dir = ( $_SERVER['HTTP_HOST'] == 'localhost' ) ? '/' . explode('/', $_SERVER['REQUEST_URI'])[1] : '' ;
// require_once( $root . $dir . '/lang.php' );
require_once( '../../lang.php' );

?>
  <link rel="canonical" href="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
	<link rel="alternate" href="https://www.smartcret.com/blog/<?php echo get_url( $idioma_actual, 'es') ?>" hreflang="es-ES" />
	<link rel="alternate" href="https://www.smartcret.com/en/blog/<?php echo get_url( $idioma_actual, 'en') ?>" hreflang="en-GB" />
	<link rel="alternate" href="https://www.smartcret.com/en-us/blog/<?php echo get_url( $idioma_actual, 'en-us') ?>" hreflang="en-US" />
	<link rel="alternate" href="https://www.smartcret.com/fr/blog/<?php echo get_url( $idioma_actual, 'fr' ) ?>" hreflang="fr-FR" />
	<link rel="alternate" href="https://www.smartcret.com/it/blog/<?php echo get_url( $idioma_actual, 'it' ) ?>" hreflang="it-IT" />
	<link rel="alternate" href="https://www.smartcret.com/de/blog/<?php echo get_url( $idioma_actual, 'de' ) ?>" hreflang="de-DE" />

