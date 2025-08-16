<?php 

if (isset($_SESSION['nivel_dir']) && $_SESSION['nivel_dir'] == 2) {
    $ruta_link1 = "../../";
    $ruta_link2 = "../";
	$ruta_adicional = "";
} elseif (isset($_SESSION['nivel_dir']) && $_SESSION['nivel_dir'] == 3) {
    $ruta_link1 = "../../";
    $ruta_link2 = "../";
	$ruta_adicional = "/blog";

} elseif (isset($_SESSION['nivel_dir']) && $_SESSION['nivel_dir'] == 4) {
    $ruta_link1 = "../../";
    $ruta_link2 = "../";
	$ruta_adicional = "/panel";

} elseif (isset($_SESSION['nivel_dir']) && $_SESSION['nivel_dir'] == 5) {
    $ruta_link1 =  "../";
    $ruta_link2 = "../" . $idioma_url . "/";
}
else {
    $ruta_link1 = "../";
    $ruta_link2 = "./";
	$ruta_adicional = "";

}

?>