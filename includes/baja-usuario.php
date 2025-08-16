<?php

include ('../config/db_connect.php');
include ('../class/userClass.php');

$rutaServer = $_ENV['RUTA_SERVER'];

$userClass = new userClass();
$borrado = $userClass->baja_usuario($_GET['mail'], $_GET['token']);

if($borrado) {

    header("Location: " . $rutaServer."/".$_SESSION['idioma_url']);
    exit();
}

?>