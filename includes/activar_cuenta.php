<?php

include ('../config/db_connect.php');
include ('../class/userClass.php');
require_once( "../class/checkoutClass.php");

$rutaServer = $_ENV['RUTA_SERVER'];

$userClass = new userClass();
$activado = $userClass->activar_cuenta($_GET['email'], $_GET['token']);

if($activado) {

    $sql = "SELECT * FROM users WHERE email=? AND password=? AND eliminado=0 AND ACTIVO=1";
    $arguments = [$_GET['email'], $_GET['token']];

    $resultado = $userClass->executeUpdate($sql, $arguments);

    if($resultado) {
        $sql = "SELECT * FROM users WHERE email=? AND password=? AND eliminado=0 AND ACTIVO=1";
        $arguments = [$_GET['email'], $_GET['token']];

        try {
            $results = $userClass->executeSelectObj($sql, $arguments);
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        if (count($results) > 0) {
            $usuario = $results[0];
            session_start();
            $_SESSION['smart_user']['id'] = $usuario->uid;
            $_SESSION['smart_user']['nombre'] = $usuario->nombre;
            $_SESSION['smart_user']['email'] = $usuario->email;
            $_SESSION['smart_user']['login'] = 1;

            if($usuario->idioma == 0) {
                $_SESSION['smart_user']['lang'] = 1;
            }
        }
    }

    $checkout = new Checkout();
    $idioma = $checkout->obten_idioma($_SESSION['smart_user']['lang'])->idioma;
    $url = $rutaServer."/".$idioma."/login";
    header("Location: " . $url);
    exit();
}
?>