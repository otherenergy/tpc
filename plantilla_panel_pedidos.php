<?php
// error_reporting(E_ALL & ~E_WARNING);
session_start();
$_SESSION['nivel_dir'] = 4;

include('../../includes/nivel_dir.php');

// error_reporting(E_ALL);
// ini_set('display_errors', '0');

include('../../includes/seguridad.php');
include('../../config/db_connect.php');
include('../../class/userClass.php');

include('../../includes/vocabulario.php');
include('../../includes/urls.php');

include('../../class/checkoutClass.php');

include_once('../../assets/lib/class.carrito.php');
include_once('../../assets/lib/funciones.php');

$userClass = new userClass();
$checkout = new Checkout();

$url_metas = $userClass->url_metas($id_url, $id_idioma);
$url_data = $userClass->obtener_informacion_url($id_url, $id_idioma);

?>

<?php
if (isset($_POST['descargar_csv'])) {
    ob_clean();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="pedidos.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    if (!$fecha_inicio || !$fecha_fin) {
        echo "Debe seleccionar un rango de fechas válido.";
        exit();
    }

    $output = fopen('php://output', 'w');

    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    fputcsv($output, array('Fecha', 'REF Factura', 'Nombre', 'Apellidos', 'Email', 'Telefono', 'Importe'), ';');

    $fecha_inicio .= " 00:00:00";
    $fecha_fin .= " 23:59:59";
    $sql = "SELECT p.*, penv.*
            FROM pedidos p 
            INNER JOIN pedidos_dir_envio penv ON penv.id = p.id_envio 
            WHERE p.id_cliente = " . $_SESSION['smart_user']['id'] . " 
            AND p.fecha_creacion BETWEEN '$fecha_inicio' AND '$fecha_fin'";

    $res = consulta($sql, $conn);

    if (numFilas($res) > 0) {
        while ($reg = $res->fetch_object()) {
            // echo $reg->nombre;
            // var_dump($reg);
            $pais_envio = $reg->pais;
            $moneda = ($pais_envio == 'US') ? '$' : '€';

            fputcsv($output, array(
                $reg->fecha_creacion,
                $reg->ref_pedido,
                $reg->nombre,
                $reg->apellidos,
                $reg->email,
                $reg->telefono,
                formatea_importe($reg->total_pagado) . $moneda
            ), ';');
        }
    }
    fclose($output);
    exit();
}

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

<body class="listado-pedidos">
    <!-- Header - Inicio -->
    <?php include('../../includes/header.php'); ?>
    <!-- Header - Fin -->
    <div class="container listado datos">
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
                                <div class="item current"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_pedidos ?>"><i class="fas fa-box"></i><?php echo $vocabulario_mis_pedidos ?></a></div>
                            </td>
                        </tr>
                        <?php 
                        if( $_SESSION['smart_user']['distribuidor'] == 1 ){?>
                        <tr class="link">
                            <td>
                                <div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/documentacion"><i class="far fa-file-alt"></i>Documentación</a></div>
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
                        <?php } ?>
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

            <div class="col-md-9">
                <h1><?php echo $vocabulario_mis_pedidos ?></h1>
                <div class="sep20"></div>

                <form method="POST">
                    <div class="contenedor_fechas_inicio_fin">
                        <div>
                            <label for="fecha_inicio">Fecha inicio:</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div>
                            <label for="fecha_fin">Fecha fin:</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" required>
                        </div>
                        <div>
                        <button class="boton_descarga_csv" type="submit" name="descargar_csv" class="btn btn-primary">Descargar CSV</button>

                        </div>
                    </div>

                </form>

                
                <div class="sep20"></div> <!-- Separador -->

                <div class="table-responsive">
                    <table class="table carrito dir_fac">
                        <thead>
                            <tr>
                                <th class="izq ref">Ref</th>
                                <th class="der fech"><?php echo $vocabulario_fecha ?></th>
                                <th class="der tot"><?php echo $vocabulario_total ?></th>
                                <th class="der pag"><?php echo $vocabulario_pago ?></th>
                                <th class="der env"><?php echo $vocabulario_envio ?></th>
                                <th class="der fac"><?php echo $vocabulario_factura ?></th>
                                <th class="der"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM pedidos WHERE id_cliente = " . $_SESSION['smart_user']['id'] . "";
                            $res = consulta($sql, $conn);
                            if (numFilas($res) > 0) {
                                while ($reg = $res->fetch_object()) {
                                    $fecha_entrega = (empty($reg->fecha_entrega)) ? 'Pendiente' : $reg->fecha_entrega;
                                    ?>

                                    <?php
                                    $pais_envio = obten_dir_envio_user($reg->id_envio)->pais;
                                    if ($pais_envio == 'US') {
                                        $moneda = '$';
                                    } else {
                                        $moneda = '€';
                                    };
                                    
                                    if ($pais_envio == 'US') {
                                        $total_pagar = formatea_importe($reg->total_pagado_div);
                                    } else {
                                        $total_pagar = formatea_importe($reg->total_pagado);
                                    }
                                    ?>

                                    <tr class="datos pedidos">
                                        <td class="der"><?php echo $reg->ref_pedido ?></td>
                                        <td class="der"><?php echo cambia_fecha_normal($reg->fecha_creacion) ?></td>
                                        <td class="der"><?php echo $total_pagar ?><?php echo $moneda ?></td>

                                        <?php if ($reg->estado_pago == 'Pendiente') { ?>
                                            <td class="der"><span style="background-color: #ffc107;" class="estado"><?php echo $vocabulario_pendiente ?></span></td>
                                        <?php } else { ?>
                                            <td class="der"><span style="background-color: #4bdf4b;" class="estado"><?php echo $vocabulario_pagado ?></span></td>
                                        <?php } ?>

                                        <?php if ($reg->estado_envio == 'Pendiente') { ?>
                                            <td class="der"><span style="background-color: #ffc107;" class="estado"><?php echo $vocabulario_pendiente ?></span></td>
                                        <?php } else { ?>
                                            <td class="der"><span style="background-color: #4bdf4b;" class="estado"><?php echo $vocabulario_enviado ?></span></td>
                                        <?php } ?>

                                        <?php if ($reg->factura == 0) { ?>
                                            <td class="der"><span style="background-color: #ffc107;" class="estado"><?php echo $vocabulario_pendiente ?></span></td>
                                        <?php } else { ?>
                                            <td class="der">
                                                <a class="estado" target='_blank' href="../../admin/facturas/facturas_usuario/<?php echo urlencode($reg->ref_pedido) . '.pdf'; ?>" style="cursor: pointer;">
                                                    <img style="height: 45px; width: 30%;" class="pdf" src="../../assets/img/pdf-download-icon.webp" alt="Descargar Factura" />
                                                </a>
                                            </td>
                                        <?php } ?>
                                        <td><button class="cart_btn btn-tabla" onclick="openModal('lista_pedido', <?php echo $reg->id ?>)">Ver +</button></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr class="datos">
                                    <td valign="center" colspan="7" style="height: 80px;vertical-align: middle;text-align: center;">No hay pedidos</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="sep40"></div>

    <div class="modal fade" id="datos-pedido">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (min-width: 768px) {
            .pedidos .der .estado .pdf {
                width: 30%;
            }
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../../assets/js/custom-js.js"></script>

    <script>
        function openModal(url, id_pedido) {
            // alert(url+id+type);
            var myModal = new bootstrap.Modal(document.getElementById('datos-pedido'), {
                keyboard: false
            })

            $.ajax({
                    url: './includes/' + url + '.php',
                    type: 'POST',
                    datatype: 'html',
                    data: {
                        id_pedido: id_pedido
                    }
                })
                .done(function(result) {
                    $('#datos-pedido .modal-body').html(result);
                    myModal.show();

                })
                .fail(function() {
                    alert('Se ha producido un error');
                })
        }
    </script>
</body>

</html>