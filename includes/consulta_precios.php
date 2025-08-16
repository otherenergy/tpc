<?php
session_start();
include ('../config/db_connect.php');
include ('../class/userClass.php');

if (isset($_POST['cod_pais']) && isset($_POST['color']) && isset($_POST['acabado']) && isset($_POST['juntas']) && isset($_POST['formato']) && isset($_POST['id_producto'])) {
    $cod_pais = $_POST['cod_pais'];
    $color = $_POST['color'];
    $acabado = $_POST['acabado'];
    $juntas = $_POST['juntas'];
    $formato = $_POST['formato'];
    $id_producto = $_POST['id_producto'];

    // Supone que $userClass ya está instanciado y disponible
    $userClass = new UserClass(); // O ajusta según tu instanciación
    $precios = $userClass->obtenerPrecios($cod_pais, $color, $acabado, $juntas, $formato, $id_producto);

    echo json_encode($precios);
}
?>