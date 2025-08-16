<?php

include('pdfparser/alt_autoload.php-dist');
include('../../../assets/lib/bbdd.php');

use Smalot\PdfParser\Parser;

// Configuración de la base de datos
$dbHost = 'localhost';  // Cambia esto si tu base de datos no está en localhost
$dbUser = 'usuario';    // Nombre de usuario de la base de datos
$dbPass = 'contraseña'; // Contraseña de la base de datos
$dbName = 'nombre_bd';  // Nombre de la base de datos

// Función para conectar a la base de datos
// function conectarBaseDatos($dbHost, $dbUser, $dbPass, $dbName)
// {
//     $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

//     // Verifica la conexión
//     if ($conn->connect_error) {
//         die("Conexión fallida: " . $conn->connect_error);
//     }

//     return $conn;
// }

// Función para actualizar el campo factura en la tabla pedidos
function actualizarFactura($conn, $codigo)
{
    $sql = "UPDATE pedidos SET factura = 1 WHERE ref_pedido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $codigo);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Función para extraer el código SC-xxxxx del texto del PDF
function extraerCodigo($texto)
{
    preg_match('/SC-\d{5}/', $texto, $matches);
    return $matches[0] ?? null; // Retorna la primera coincidencia encontrada o null si no hay coincidencias
}

// Función para renombrar y mover el archivo
function renombrarYMoverArchivo($archivoPdf, $codigo, $directorioDestino)
{
    // Asegúrate de que el nombre de destino use un separador correcto y el directorio exista
    $nuevoNombre = rtrim($directorioDestino, '/') . '/' . $codigo . '.pdf';

    if (rename($archivoPdf, $nuevoNombre)) {
        return $nuevoNombre;
    } else {
        return false;
    }
}

// Función para escribir en el archivo de log
function escribirLog($mensaje)
{
    file_put_contents('log.txt', $mensaje . PHP_EOL, FILE_APPEND);
}

// Función principal para leer el PDF, procesar el contenido y moverlo
function leerPdfYProcesar($archivoPdf, $directorioDestino, $conn)
{
    $parser = new Parser();
    try {
        $pdf = $parser->parseFile($archivoPdf);
        $texto = $pdf->getText();

        $codigo = extraerCodigo($texto);

        if ($codigo) {
            // Actualiza la base de datos si se encuentra el código
            $actualizado = actualizarFactura($conn, $codigo);

            if ($actualizado) {
                // Renombra y mueve el archivo si la actualización fue exitosa
                $nuevoNombre = renombrarYMoverArchivo($archivoPdf, $codigo, $directorioDestino);
                if ($nuevoNombre) {
                    $mensaje = "$archivoPdf - $nuevoNombre";
                } else {
                    $mensaje = "$archivoPdf - ERROR al mover el archivo.";
                }
            } else {
                $mensaje = "$archivoPdf - ERROR al actualizar BBDD.";
            }
        } else {
            $mensaje = "$archivoPdf - ERROR: Código no encontrado.";
        }

        escribirLog($mensaje);

    } catch (Exception $e) {
        escribirLog("Error al leer el archivo $archivoPdf: " . $e->getMessage());
    }
}

// Función para procesar todos los archivos PDF en un directorio
function procesarArchivosPdfEnDirectorio($directorio, $conn)
{
    // Define el directorio destino como './facturas_usuario'
    $directorioDestino = '../facturas_usuario';

    // Verifica si el directorio destino existe, si no, lo crea
    if (!file_exists($directorioDestino)) {
        if (!mkdir($directorioDestino, 0777, true)) {
            die("Error al crear el directorio destino: $directorioDestino");
        }
    }

    // Busca todos los archivos PDF en el directorio actual
    $archivos = glob($directorio . '/*.pdf');

    foreach ($archivos as $archivo) {
        leerPdfYProcesar($archivo, $directorioDestino, $conn);
    }
}

// Conectar a la base de datos
// $conn = conectarBaseDatos($dbHost, $dbUser, $dbPass, $dbName);

// Procesar los PDFs en el directorio actual
procesarArchivosPdfEnDirectorio(__DIR__, $conn);

// Cerrar la conexión a la base de datos
$conn->close();