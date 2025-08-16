<?php
// error_reporting(E_ALL & ~E_WARNING);
// error_reporting(E_ALL);
// ini_set('display_errors', '0');
session_start();
$_SESSION['nivel_dir'] = 4;

$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma=$id_idioma_global;

if( $_SESSION['smart_user']['distribuidor'] == 0 ){
	header('Location: ../');
    exit;
}

include('../../includes/nivel_dir.php');
include('../../includes/seguridad.php');
include ('../../config/db_connect.php');

$rutaServer = $_ENV['RUTA_SERVER'];

include('../../class/userClass.php');
include('../../includes/vocabulario.php');
include('../../includes/urls.php');

include('../../class/checkoutClass.php');
include_once('../../assets/lib/class.carrito.php');
include_once('../../assets/lib/funciones.php');

$userClass = new userClass();
$checkout = new Checkout();

$url_metas=$userClass->url_metas($id_url,$id_idioma);
$url_data = $userClass->obtener_informacion_url($id_url, $id_idioma);

?>


<!DOCTYPE html>
<html lang="es-ES">
<?php include('../../includes/head.php'); ?>
<!-- Estilos CSS ADICIONALES -->
<link rel='stylesheet' href='../../assets/css/panel_usuario.css?<?php echo rand() ?>' type='text/css' />

<script type="text/javascript">
    var idiomaUrl = <?php echo json_encode($_SESSION['idioma_url']); ?>;
    var ruta_link = <?php echo json_encode($_SESSION['ruta_link1']); ?>;
</script>

<body class="body-documentacion">
    <!-- Header - Inicio -->
    <?php include('../../includes/header.php'); ?>
    <!-- Header - Fin -->
    <div class="container documentacion">
        <div class="row">
            <div class="col-md-2 menu-lat">
                <div class="menu-panel">
                    <table>
                        <thead>
                        <tr class="tit">
                            <td colspan="2">@ <?php echo $_SESSION['smart_user']['nombre'] ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="link">
                            <td>
                                <div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_datos ?>"><i class="far fa-user"></i><?php echo $vocabulario_mis_datos ?></a></div>
                            </td>
                        </tr>
                        <tr class="link">
                            <td>
                                <div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_pedidos ?>"><i class="fas fa-box"></i><?php echo $vocabulario_mis_pedidos ?></a></div>
                            </td>
                        </tr>
                        <tr class="link">
                            <td>
                                <div class="item current"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/documentacion"><i class="far fa-file-alt"></i>Documentación</a></div>
                            </td>
                        </tr>
                        <tr class="link">
                            <td>
                                <div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/mis-cupones"><i class="fas fa-tags"></i>Mis cupones</a></div>
                            </td>
                        </tr>
                        <tr class="link">
                            <td>
                                <div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/contacto"><i class="fas fa-envelope"></i>Contacto</a></div>
                            </td>
                        </tr>
                        <tr class="link">
                            <td>
                                <div class="item exit"><a class="nav-link" href="javascript:exit('<?php echo $ruta_link1 ?>')"><i class="fas fa-sign-out-alt"></i><?php echo $vocabulario_cerrar_sesion ?></a></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-1">
                <div class="sepv"></div>
            </div>
            <div class="col-md-8">

                <div class="div-documentos">
                    <a href="./includes/images">Información de productos (Títulos, imagenes, precios, portes..)</a>
                    <!-- <a href="">Documento de precios, pesos y envíos</a> -->
                    <a href="../../assets/downloads/Smartcret_domain_name_policy.pdf" target="_blank">Política de Nombres de Dominio de Smartcret</a>
                    <a href="../../assets/downloads/Directrices_uso_marca_Smartcret.pdf" target="_blank">Directrices de uso de la marca Smartcret</a>

                </div>

            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../../assets/js/custom-js.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" rel="stylesheet">

</body>

</html>