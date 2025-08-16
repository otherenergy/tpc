<?php

$test = true;

if ( $test ) {

/* PRUEBA */

// URL de REDSYS con el formulario de procesamiento de pagos (TEST / REAL)
define( 'REDSYS_URL_TPV', 'https://sis-t.redsys.es:25443/sis/realizarPago' ); #test

if ( $user_ubicacion == 'US' ) {
	// Clave secreta de encriptación (SHA-256)
  define( 'REDSYS_KEYCODE', 'sq7HjrUOBfKmC576ILgskD5srU870gJ7' );

}else{
	// Clave secreta de encriptación (SHA-256)
  define( 'REDSYS_KEYCODE', 'sq7HjrUOBfKmC576ILgskD5srU870gJ7' ); #test
}

}else {

/* PRODUCCION */

// URL de REDSYS con el formulario de procesamiento de pagos (TEST / REAL)
define( 'REDSYS_URL_TPV', 'https://sis.redsys.es/sis/realizarPago' );//bueno

if ( $user_ubicacion == 'US' ) {
	// Clave secreta de encriptación (SHA-256)
  define( 'REDSYS_KEYCODE', '2MVRBlPr23KBDQBX1DlKatZOQaWGivj1' );

}else{
	// Clave secreta de encriptación (SHA-256)
  define( 'REDSYS_KEYCODE', 'ZOKo/OvgRRFUC16N1/Mzj0P7a052ZdI3' );//bueno
}

}

// Nombre del comercio
define( 'REDSYS_NAME', 'Smartcret' );


if ( $user_ubicacion == 'US' ) {

	// Número de comercio (FUC)
	define( 'REDSYS_FUC_CODE', '363467176' );

	// Número de terminal
	define( 'REDSYS_TERMINAL', '1');

	// Código de divisa
	define( 'REDSYS_CURRENCY', '840');


}else {

	// Número de comercio (FUC)
	define( 'REDSYS_FUC_CODE', '351939194' );

	// Número de terminal
	define( 'REDSYS_TERMINAL', '001');

	// Código de divisa
	define( 'REDSYS_CURRENCY', '978');

}

if( $user_ubicacion ) {

	if($_SERVER['SERVER_NAME'] == 'localhost') {
		define( 'REDSYS_URL_MERCHANT', 'localhost/smartcret_new/assets/return/notifica.php' );	
		define( 'REDSYS_URL_OK', 'localhost/smartcret_new/checkout/order-completed?from=tpv');
		define( 'REDSYS_URL_KO', 'localhost/smartcret_new/checkout/payment-error');
	}else{
		define( 'REDSYS_URL_MERCHANT', 'https://www.smartcret.com/assets/return/notifica.php' );	
		define( 'REDSYS_URL_OK', 'https://www.smartcret.com/checkout/order-completed?from=tpv');
		define( 'REDSYS_URL_KO', 'https://www.smartcret.com/checkout/payment-error');
	}

}

// Indica si se debe escribir información en el log sobre las operaciones de confirmación procesadas
define( 'REDSYS_LOG', true );

//Ds_SignatureVersion
define( 'VERSION', 'HMAC_SHA256_V1' );