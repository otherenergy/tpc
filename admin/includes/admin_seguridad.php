<?php
if (session_status() === PHP_SESSION_NONE){session_start();}
// if ( !isset ( $_SESSION["idioma"] ) ) {$_SESSION["idioma"] = "es";}
if (!isset($_SESSION["smart_user_admin"]["login"]) || $_SESSION["smart_user_admin"]["login"]=='0') {
	header("Location: ./login");
}

?>