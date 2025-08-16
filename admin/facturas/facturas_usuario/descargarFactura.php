<?php
session_start();

include_once('../../../assets/lib/bbdd.php');
include_once('../../../assets/lib/funciones.php');

if (!isset($_SESSION['smart_user']) || $_SESSION['smart_user']['login'] != 1) {
    die("Acceso denegado.");
    // echo '<script language="javascript">alert("Acceso denegado. Debes estar logueado para descargar facturas.");</script>';
}

// Obtener el 'ref_pedido' desde la URL y sanitizarlo como medida de seguridad
if (isset($_GET['ref'])) {
    $refPedido = $mysqli->real_escape_string($_GET['ref']);
} else {
    die("Error: No se ha especificado ninguna referencia de factura.");
}

$sql = "SELECT ref_pedido FROM pedidos WHERE id_cliente='" . $_SESSION['smart_user']['id'] . "' AND factura='1' AND ref_pedido='" . $refPedido . "'";
$result = consulta($sql);

if (numFilas($result) > 0) {
    $fila = $result->fetch_assoc();
    $nombreArchivoFactura = $fila['ref_pedido'];
    $facturaPath = __DIR__ . "/" . $nombreArchivoFactura . ".pdf";

    if (file_exists($facturaPath)) {
        // Preparar los headers para la descarga del archivo PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($facturaPath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($facturaPath));
        readfile($facturaPath); // Enviar el archivo para descarga
        exit;
    } else {
        die("Error: El archivo no existe o no se puede leer.");
        // echo '<script language="javascript">alert("Error: El archivo no existe o no se puede leer.");</script>';
    }
} else {
    die("Acceso denegado.");
    // echo '<script language="javascript">alert("Acceso denegado. No tienes permiso para descargar esta factura.");</script>';
}
?>