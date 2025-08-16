<?php
// error_reporting(E_ALL & ~E_WARNING);
// error_reporting(E_ALL);
// ini_set('display_errors', '0');
session_start();
$_SESSION['nivel_dir'] = 4;

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

<body class="carrito direccion misdatos">
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
                                <div class="item current"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_datos ?>"><i class="far fa-user"></i><?php echo $vocabulario_mis_datos ?></a></div>
                            </td>
                        </tr>
                        <tr class="link">
                            <td>
                                <div class="item"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/<?php echo $link_mis_pedidos ?>"><i class="fas fa-box"></i><?php echo $vocabulario_mis_pedidos ?></a></div>
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
            <div class="col-md-8">

                <div class="pruebas">

                </div>

                <div class="">
                    <table class="table carrito pass">
                        <thead>
                            <tr>
                                <th colspan="2" class="izq"><?php echo $vocabulario_contraseña ?></th>
                                <th class="izq"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <form id="form_cambia_pass" action="">
                                <?php
                                $sql = "SELECT * FROM users WHERE uid = " . $_SESSION['smart_user']['id'] . "";
                                $res = consulta($sql, $conn);
                                while ($reg = $res->fetch_object()) { ?>
                                    <tr class="datos">
                                        <td class="izq">
                                            <div class="form-group">
                                                <label for="form_nom"><?php echo $vocabulario_contraseña_actual ?><span style="color:red;">*</span></label>
                                                <input type="password" class="form-control" id="input_pass_old" name="input_pass_old">
                                            </div>
                                        </td>
                                        <td class="izq">
                                            <div class="form-group">
                                                <label for="form_nom"><?php echo $vocabulario_nueva_contraseña ?><span style="color:red;">*</span></label>
                                                <input type="password" class="form-control" id="input_pass" name="input_pass">
                                            </div>
                                        </td>
                                        <td class="izq">
                                            <div class="form-group">
                                                <label for="form_nom"><?php echo $vocabulario_repetir_contraseña ?><span style="color:red;">*</span></label>
                                                <input type="password" class="form-control" id="input_pass_repeat" name="input_pass_repeat">
                                            </div>
                                        </td>
                                        <td class="btn-guardar">
                                            <input type="hidden" name="accion" value="cambia_pass">
                                            <input type="hidden" name="uid" value="<?= $_SESSION['smart_user']['id'] ?>">
                                            <button id="cambia_pass" style="position: relative;top: 30px;" class="cart_btn btn-tabla"><i class="fa fa-save"></i><?php echo $vocabulario_guardar ?></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </form>
                        </tbody>
                    </table>
                </div>

                <div class="sep30"></div>

                <div class=" table-responsive">
                    <table class="table carrito dat_per">
                        <thead>
                            <tr>
                                <th><?php echo $vocabulario_datos_personales_panel ?></th>
                                <th class="izq"></th>
                            </tr>
                        </thead>
                        <tbody class="perso-dat">
                            <?php
                            $sql = "SELECT * FROM users WHERE uid = " . $_SESSION['smart_user']['id'] . "";
                            $res = consulta($sql, $conn);
                            while ($reg = $res->fetch_object()) { ?>

                                <tr class="datos">

                                    <?php
                                    $reg = obten_datos_user($_SESSION['smart_user']['id']);
                                    if ($reg->apellidos == '' ) { ?>
                                        <td colspan="2" class="izq">
                                            <p style="color: red;"><?php echo $vocabulario_debe_introducir_datos ?></p>
                                        </td>
                                        <td>
                                            <button style="margin-top: 10px;" class="cart_btn btn-tabla" onclick="openModal( 'form_dat_pers', <?php echo $reg->uid ?>, 'edit' ) "><?php echo $vocabulario_introducir_datos ?></button>
                                        </td>
                                    <?php } else { ?>
                                        <td colspan="2" class="izq">
                                            <?php echo '<i class="fa fa-user"></i>' . $reg->nombre . ' ' . $reg->apellidos . '<br><i class="fa fa-phone"></i>' . $reg->telefono . '<br><i class="fa fa-id-card"></i>' . $reg->nif_cif  ?></td>
                                        <br>
                                        <td>
                                            <button style="margin-top: 10px;" class="cart_btn btn-tabla" onclick="openModal( 'form_dat_pers', <?php echo $reg->uid ?>, 'edit' ) "><i class="fa fa-edit"></i><?php echo $vocabulario_editar ?></button>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                </div>

                <div class="sep30"></div>
                <div class=" table-responsive">
                    <table class="table carrito dir_env">
                        <thead>
                            <tr>
                                <th colspan="2" class="izq"><?php echo $vocabulario_direccion_envio_panel ?></th>
                                <th class="izq"></th>
                            </tr>
                        </thead>
                        <tbody style="border-bottom: 1px solid!important">
                            <?php
                            $sql = "SELECT * FROM datos_envio WHERE id_cliente = " . $_SESSION['smart_user']['id'] . " AND activo=1";
                            $res = consulta($sql, $conn);
                            if (mysqli_num_rows($res) > 0) {
                                while ($reg = $res->fetch_object()) { ?>
                                    <?php $eori = ($reg->eori != '' && strlen($reg->eori) > 10) ? '<br>EORI - ' . $reg->eori : ''; ?>
                                    <tr class="datos">
                                        <td colspan="2" class="izq"><input type="checkbox" class="dir env" name="dir-env" value="<?php echo $reg->id ?>" <?php if ($reg->predeterminado == 1) echo 'checked' ?>><i class="fa fa-map-marker-alt"></i><?php echo $reg->direccion . ', ' . $reg->cp . ', ' . $reg->localidad . ', ' . obten_nombre_provincia($reg->provincia) . ', ' . obten_nombre_pais($reg->pais) . $eori ?></td>
                                        <td class="der"><button class="cart_btn btn-tabla" onclick="openModal( 'form_dir_envio', <?php echo $reg->id ?>, 'edit' )"><i class="fa fa-edit"></i><?php echo $vocabulario_editar; ?></button></td>
                                        <td><i class="fa fa-times transform" onclick="eliminaDireccion('<?php echo $reg->id ?>', 'datos_envio', '../../')" title="<?php echo $vocabulario_eliminar_direccion ?>"></i></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <td text-align="center" colspan="3" class="no_item"><?php echo $vocabulario_todavia_no_hay_direccion ?></td>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="btn-new">
                        <button id="new-dir-env" class="cart_btn btn-tabla" onclick="openModal( 'form_dir_envio', <?php echo $_SESSION['smart_user']['id'] ?>, 'new' )"><i class="fa fa-plus"></i><?php echo $vocabulario_nueva_direccion ?></button>
                    </div>

                </div>

                <div class="sep30"></div>

                <div class=" table-responsive">
                    <table class="table carrito dir_fac">
                        <thead>
                            <tr>
                                <th colspan="2" class="izq"><?php echo $vocabulario_direccion_facturacion_panel ?></th>
                                <th class="izq"></th>
                                <th class="izq"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM datos_facturacion WHERE id_cliente = " . $_SESSION['smart_user']['id'] . " AND activo=1";
                            $res = consulta($sql, $conn);
                            if (mysqli_num_rows($res) > 0) {
                                while ($reg = $res->fetch_object()) { ?>
                                    <tr class="datos">
                                        <td colspan="2" class="izq"><input type="checkbox" class="dir fact" value="<?php echo $reg->id ?>" <?php if ($reg->predeterminado == 1) echo 'checked' ?>><i class="fa fa-map-marker-alt"></i><?php echo $reg->direccion . ', ' . $reg->cp . ', ' . $reg->localidad . ', ' . obten_nombre_provincia($reg->provincia) . ', ' . obten_nombre_pais($reg->pais) ?><br>
                                            <?php echo '<div class="perso-dat"><i class="fa fa-user"></i>' . $reg->nombre . ' ' . $reg->apellidos . ' (' . $reg->tipo_factura . ') ' . '<br><i class="fa fa-phone"></i>' . $reg->telefono . '<br><i class="fa fa-id-card"></i>' . $reg->nif . '</div>' ?></td>
                                        <td class="der"><button class="cart_btn btn-tabla" onclick="openModal( 'form_dir_fac', <?php echo $reg->id ?>, 'edit' )"><i class="fa fa-edit"></i><?php echo $vocabulario_editar; ?></button></td>
                                        <td><i class="fa fa-times transform" onclick="eliminaDireccion('<?php echo $reg->id ?>', 'datos_facturacion', '../../')" title="<?php echo $vocabulario_eliminar_direccion ?>"></i></td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <td text-align="center" colspan="3" class="no_item"><?php echo $vocabulario_nueva_direccion ?></td>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="btn-new">
                        <button id="new-dir-fac" class="cart_btn btn-tabla" onclick="openModal( 'form_dir_fac', <?php echo $_SESSION['smart_user']['id'] ?>, 'new' )"><i class="fa fa-plus"></i><?php echo $vocabulario_nueva_direccion ?></button>
                    </div>
                    <div class="float-start mt-5">
                        <button id="new-dir-fac" class="btn-tabla btn-delete" onclick="alertarBajaUsuario(<?php echo $_SESSION['smart_user']['id'] ?>)"><i class="fa fa-trash"></i><?php echo $vocabulario_eliminar_usuario ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sep40"></div>

    <div class="modal fade" id="datos">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
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

    <?php
    $reg = obten_datos_user($_SESSION['smart_user']['id']);
    // var_dump($reg);                         
    if ($reg->distribuidor == 1){
        if ($reg->apellidos == '' || $reg->nombre == '' || $reg->nif_cif == '' || $reg->telefono == '') {?>
        <script>
            muestraMensajeLn('Por favor, completa tus datos personales');
            setTimeout(function() {
                $('.perso-dat button').click();
            }, 2000);
        </script>
    <?php 
        }
    }else{ 
        if ($reg->apellidos == '' || $reg->nombre == '') { ?>
        <script>
            muestraMensajeLn('Por favor, completa tus datos personales');
            setTimeout(function() {
                $('.perso-dat button').click();
            }, 2000);
        </script>
    <?php
        }
    }?>

    <script>
        // Mostramos una ventana de alerta para confirmar la baja del usuario
        function alertarBajaUsuario(UserId) {
            server = `<?php echo $_SERVER['SERVER_NAME'] ?>`
            lang = `<?php echo $_SESSION['idioma_url']; ?>`
            Swal.fire({
            title: `<?php echo $vocabulario_preguntar_baja; ?>`,
            text: `<?php echo $vocabulario_notificar_mail_baja; ?>`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: `<?php echo $vocabulario_cancelar; ?>`,
            confirmButtonText: `<?php echo $vocabulario_confirmar; ?>`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../class/control.php',
                        type: 'POST',
                        datatype: 'html',
                        data: {
                            accion: 'baja_usuario',
                            UserId: UserId
                        }
                    })
                    .done(function(result) {
                        window.location.href = '<?php echo $rutaServer ?>/'+lang+'/login';
                    });
                };
            });
        }
        $('input.dir.env').click(function(e) {
            $(this).prop('checked', true);
            $('input.dir.env').not(this).prop('checked', false);
            $.ajax({
                    url: '../../class/control.php',
                    type: 'POST',
                    datatype: 'html',
                    data: {
                        accion: 'set_dir_envio',
                        idEnvio: $(this).val()
                    }
                })
                .done(function(result) {

                    location.reload();
                })
                .fail(function() {
                    alert('Se ha producido un error');
                })
        });


        $('input.dir.fact').click(function(e) {
            $(this).prop('checked', true);
            $('input.dir.fact').not(this).prop('checked', false);
            $.ajax({
                    url: '../../class/control.php',
                    type: 'POST',
                    datatype: 'html',
                    data: {
                        accion: 'set_dir_facturacion',
                        idEnvio: $(this).val()
                    }
                })
                .done(function(result) {

                    location.reload();
                })
                .fail(function() {
                    alert('Se ha producido un error');
                })
        });


        function openModal(url, id, type) {
            // alert(url+id+type);
            var myModal = new bootstrap.Modal(document.getElementById('datos'), {
                keyboard: false
            })
            $.ajax({
                url: './includes/' + url + '.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    id: id,
                    type: type
                }
            })
            .done(function(result) {
                $('#datos .modal-body').html(result);
                myModal.show();

            })
            .fail(function() {
                alert('Se ha producido un error');
            })
        }

        $('#form_cambia_pass').submit(function(e) {
            e.preventDefault();
        });

        $('#cambia_pass').click(function(e) {
            if (compruebaPass()) {
                $.ajax({
                        url: '../../class/control.php',
                        type: 'post',
                        dataType: 'text',
                        data: $('#form_cambia_pass').serialize()
                    })
                    .done(function(result) {
                        result = JSON.parse(result);

                        if (result.res == "1") {
                            $('#datos').modal('hide');
                            muestraMensajeLn(result.msg);
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            muestraMensajeLn(result.msg);
                        }
                    })
                    .fail(function() {
                        alert("Error en la solicitud.");
                    });
            }
        });

        function compruebaPass() {

            if ($('#input_pass_old').val() === '' || $('#input_pass').val() === '' || $('#input_pass_repeat').val() === '') {
                muestraMensajeLn(`<?php echo $vocabulario_antigua_contrasena_nueva_actual_no_coinciden; ?>`);
                return false;

            } else if ($('#input_pass').val() !== $('#input_pass_repeat').val()) {
                muestraMensajeLn(`<?php echo $vocabulario_contrasenas_no_coinciden; ?>`);
                return false;

            }

            return true;
        }
    </script>
</body>

</html>