<?php
session_start();
$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma = $id_idioma_global;

include('../../../includes/nivel_dir.php');
include('../../../includes/seguridad.php');
include ('../../../config/db_connect.php');
include('../../../class/userClass.php');
include('../../../includes/vocabulario.php');
include('../../../includes/urls.php');
include('../../../class/checkoutClass.php');
include_once('../../../assets/lib/class.carrito.php');
include_once('../../../assets/lib/funciones.php');

$userClass = new userClass();
$checkout = new Checkout();

$lista_productos = $userClass->fn_obtener_productos_padres($id_idioma);

$directory = '../../../assets/img/productos';

if (isset($_POST['descargar_todo'])) {

    $zip_filename = 'imagenes_productos.zip';
    $zip_filepath = sys_get_temp_dir() . '/' . $zip_filename; 

    if (file_exists($zip_filepath)) {
        unlink($zip_filepath);
    }

    $zip = new ZipArchive();
    if ($zip->open($zip_filepath, ZipArchive::CREATE) !== TRUE) {
        exit("No se pudo crear el archivo ZIP");
    }

    foreach ($lista_productos as $producto) {
        $ruta_imagen = $directory . '/' . $producto->miniatura; 

        if (file_exists($ruta_imagen)) {
            $image_name = basename($ruta_imagen); 
            $zip->addFile($ruta_imagen, $image_name);
        }
    }

    $zip->close();

    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
    header('Content-Length: ' . filesize($zip_filepath));

    readfile($zip_filepath);
    unlink($zip_filepath);
    exit();
}
?>

<?php
if (isset($_POST['descargar_csv_portes'])) {
    ob_clean();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="transporte_smartcret.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    $sql = "SELECT pn.nombre as Name, ts.peso as Kg, ts.* FROM transporte_smartcret ts INNER JOIN productos_nombres pn ON ts.id_producto = pn.id_producto where pn.id_idioma = ".$id_idioma."";
    $res = consulta($sql, $conn);

    if (numFilas($res) > 0) {
        $columnas = $res->fetch_fields();
        $headers = [];

        foreach ($columnas as $columna) {
            if ($columna->name != 'id_producto' && $columna->name != 'nombre' && $columna->name != 'id' && $columna->name != 'peso') { 
                $headers[] = $columna->name;
            }
        }

        fputcsv($output, $headers, ';');

        while ($reg = $res->fetch_assoc()) {
            unset($reg['id_producto']);
            unset($reg['nombre']);
            unset($reg['id']);
            unset($reg['peso']);
            fputcsv($output, $reg, ';');
        }
    }

    fclose($output);
    exit();
}
?>

<?php
if (isset($_POST['descargar_csv_portes_especiales'])) {
    ob_clean();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="transporte_smartcret.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    $sql = "SELECT * FROM transporte_zonas_especiales";
    $res = consulta($sql, $conn);

    if (numFilas($res) > 0) {
        $columnas = $res->fetch_fields();
        $headers = [];

        foreach ($columnas as $columna) {
            if ($columna->name != 'id') { 
                $headers[] = $columna->name;
            }
        }

        fputcsv($output, $headers, ';');

        while ($reg = $res->fetch_assoc()) {
            unset($reg['id']);
            fputcsv($output, $reg, ';');
        }
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!-- jQuery -->
	<!-- Estilos CSS -->
	<!-- <link rel='stylesheet' href='../../../assets/css/smart_style.css?<?php echo rand() ?>' type='text/css' /> -->
	<link rel="icon" href="../../../assets/img/favicon.png">

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


    <title>Lista de Imágenes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            padding: 0px !important;
            margin: 0px !important;
        }

        h1 {
            color: #4c4c4c;
        }

        .contenedor {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        .image-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 200px;
            max-width: 100%;
            word-wrap: break-word; 
            overflow-wrap: break-word; 
        }

        .image-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .button-contenedor {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 10px;
        }

        .acces-product, .download-btn {
            padding: 10px 20px;
            text-decoration: none !important;
            color: white !important;
            background-color: #007BFF;
            border-radius: 5px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .acces-product:hover, .download-btn:hover {
            background-color: #0056b3; 
        }

        .download-btn {
            background-color: #28a745;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px; */
        }

        .download-btn:hover {
            background-color: #218838;
        }

        .download-icon {
            width: 0; 
            height: 0; 
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-top: 10px solid white;
            margin-top: 5px;
        }

        .back-btn, .download-all-btn {
            background-color: #92bf23;
            color: #fff !important;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none !important;
            font-size: 16px;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover, .download-all-btn:hover {
            background-color: #7ea91e;
        }

        .download-all-btn{
            margin: 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            font-weight: bold;
            margin: 20px;
            text-transform: uppercase;
        }

        .back-btn::before {
            content: '←';
            margin-right: 8px;
            font-size: 1.2rem;
        }

        h1{
            margin: 15px 10px !important;
        }

        .contenedor-atras-descargazip{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media (max-width: 768px) {
            .contenedor {
                gap: 10px; 
            }

            .image-card {
                width: calc(50% - 10px);
            }

            .image-card img {
                max-width: 100%;
                height: auto;
            }
            .download-all-btn{
                margin: 30px 10px;
            }
            .back-btn, .download-all-btn {
                padding: 2px 4px;
                font-size: 12px;
                font-weight: bold;
            }
            .download-btn {
                width: 35px;
            }
            h1{
                font-size: 25px;
                margin: 16px 10px !important;
            }
            header{
                max-height: 65px !important;
            }
        }

        .product-price {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            max-width: 300px;
        }

        .product-price p {
            margin: 5px 0;
        }

        .product-price strong {
            color: #333;
        }

        header{
            display: flex;

            max-height: 75px;
            box-shadow: 0 5px 49px rgba(0, 0, 0, .349019607843137);
        }
    </style>
</head>
<body>

<header>
    <a href="../documentacion" class="back-btn">Atrás</a>
    <h1>Lista de productos</h1>

</header>


<div class="contenedor-atras-descargazip">
    <form method="POST">
        <button type="submit" name="descargar_todo" class="download-all-btn">Descargar todas las imágenes</button>
    </form>

    <form method="POST">
        <button type="submit" name="descargar_csv_portes" class="download-all-btn">CSV portes</button>
    </form>
    <form method="POST">
        <button type="submit" name="descargar_csv_portes_especiales" class="download-all-btn">CSV portes especiales España</button>
    </form>

</div>

<div class="contenedor">

    <div class="item" style="display: flex;justify-content: center;align-items: center;flex-direction:column;">        
        <p style="margin-bottom:0px;">Actualiza los precios según la dirección del envío</p>

        <div class="nav-link" style="display: flex;justify-content: center;align-items: center;">
            <i class="fas fa-globe" style="margin-right: 10px;"></i>
            <?php

                $checkout = new Checkout();
                $paises = $checkout->obten_paises_activos_idioma( $id_idioma );

            ?>

            <select type="text" class="form-control custom-select" id="input_localizacion_dir" name="input_localizacion_dir" style="width: 160px;">
                <?php foreach ($paises as $pais) {
                    ?>
                    <option value="<?php echo $pais->cod_pais ?>" <?php if( $pais->cod_pais == $_SESSION['user_ubicacion']) echo 'selected' ?>><?php echo $pais->nombre ?></option>
                <?php } ?>
            </select>

        </div>
    </div>
</div>

<div class="contenedor">

    <?php
    if (count($lista_productos) > 0) {
        foreach ($lista_productos as $producto) {
            $image_name = basename($producto->miniatura); 
            $alt_text = $producto->alt; 
            $title_text = $producto->nombre; 
            $precio_base = $producto->precio_base; 
            $precio = $producto->precio; 
            $descuento = $producto->descuento;
            $enlace_producto = $producto->valor;
            $download_path = '../../../assets/img/productos/' . $producto->miniatura; 
            ?>

            <div class="image-card">
                <img src="<?php echo $download_path; ?>" alt="<?php echo htmlspecialchars($alt_text); ?>">
                <p><?php echo htmlspecialchars($title_text); ?></p>
                <div class="product-price">
                    <p><strong>Precio:</strong> <?php echo number_format($precio_base, 2); ?> €</p>
                    
                    <?php if ($descuento > 0) { ?>
                        <p><strong>Descuento:</strong> <?php echo number_format($descuento, 2); ?> %</p>
                        <p><strong>Precio final:</strong> <?php echo number_format($precio, 2); ?> €</p>
                    <?php } ?>
                </div>


                <div class="button-contenedor">
                    <a class="acces-product" href="../../<?php echo $enlace_producto ?>">Acceder</a>
                    <a class="download-btn" href="<?php echo $download_path; ?>" download>
                        <i class="fas fa-download"></i>
                    </a>
                </div>

            </div>

            <?php
        }
    } else {
        echo "<p>No se encontraron imágenes en la carpeta especificada.</p>";
    }
    ?>
</div>
<script src="../../../assets/js/custom-js.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('#input_localizacion_dir').change(function() {
    $.ajax({
        url: '../../../class/control.php',
        type: 'post',
        dataType: 'text',
        data: {accion: 'modifica_ubicacion', input_pais: $(this).val()}
    })
    .done(function(result) {
        var result = $.parseJSON(result);

        Swal.fire({
            icon: 'info', 
            title: 'Actualización de Precios',
            text: 'La página se actualizará para mostrar los precios correspondientes a la ubicación seleccionada.',
            timer: 3000, 
            showConfirmButton: false, 
            position: 'center' 
        });

        setTimeout(function() {
            location.reload(); 
        }, 3000); 

    })
    .fail(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Se produjo un error en la solicitud',
            timer: 3000,
            showConfirmButton: false,
            position: 'center' 
        });

        setTimeout(function() {
            location.reload(); 
        }, 3000); 
    });
});



</script>

</body>
</html>
