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
include_once( "../../class/emailClass.php");
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

    <style>
.contendor-formulario-distribuidor {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.contendor-formulario-distribuidor h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 10px;
    color: #333;
}

.contendor-formulario-distribuidor p {
    text-align: center;
    font-size: 16px;
    color: #666;
}

.contendor-formulario-distribuidor textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    resize: none;
    margin-bottom: 15px;
}

.contendor-formulario-distribuidor button {
    display: block;
    width: 100%;
    padding: 10px 20px;
    background-color: #92bf23;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.contendor-formulario-distribuidor button:hover {
    background-color: #7ba41a;
}

.contendor-formulario-distribuidor button:active {
    background-color: #659017;
}

.contendor-formulario-distribuidor form p {
    color: #333;
    text-align: center;
    margin-top: 10px;
}

.contendor-formulario-distribuidor p.success {
    color: green;
}

.contendor-formulario-distribuidor p.error {
    color: red;
}


.file-upload-wrapper {
    position: relative;
    width: 100%;
}

.custom-file-upload {
    display: inline-block;
    padding: 10px 20px;
    cursor: pointer;
    background-color: #e1e1e1;
    border-radius: 5px;
    font-size: 16px;
    text-align: center;
    width: 100%;
}

#file-upload {
    display: none;
}

#file-list-container {
    margin-top: 10px;
}

.file-item {
    display: flex;
    justify-content: space-between;
    background-color: #f1f1f1;
    padding: 8px;
    border-radius: 5px;
    margin-bottom: 5px;
    font-size: 14px;
}

.file-item button {
    background-color: #ff5c5c !important;
    border: none;
    color: white;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
    max-width: 115px;
}

    </style>
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
                                <div class="item current"><a class="nav-link" href="<?php echo $ruta_link2 ?>panel/contacto"><i class="fas fa-envelope"></i>Contacto</a></div>
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
                <div class="contendor-formulario-distribuidor">
                    <h2>Contacto</h2>
                    <p>Envíanos un mensaje y nos pondremos en contacto contigo.</p>


                    <form id="form-distribuidor" method="post" enctype="multipart/form-data">
                        <textarea name="contenido" rows="5" cols="50" placeholder="Escribe tu mensaje aquí..."></textarea><br><br>

                        <div class="file-upload-wrapper">
                            <label for="file-upload" class="custom-file-upload">
                                Selecciona archivos
                            </label>
                            <input id="file-upload" type="file" multiple />
                            <div id="file-list-container"></div> 
                        </div>
                        <br><br>

                        <button type="submit" id="enviar_mensaje">Enviar</button>
                    </form>

                    <div id="message-status"></div>
                    <?php 
                    //var_dump($_SESSION['smart_user']);
                    ?>

                    <?php
                    if (isset($_POST['contenido'])) {
                        $contenido = $_POST['contenido'];

                        if (!empty($contenido)) {
                            $archivos = isset($_FILES['archivo_adjuntar']) ? $_FILES['archivo_adjuntar'] : null;

                            $emailclass = new emailClass();
                            $email_pedido_recibido = $emailclass->email_contacto_distribuidores('luis@topciment.com', $contenido, $archivos);

                            if ($email_pedido_recibido) {
                                echo "Mensaje enviado con éxito.";
                            } else {
                                echo "Hubo un error al enviar el mensaje.";
                            }
                        } else {
                            echo "Por favor, escribe un mensaje antes de enviarlo.";
                        }
                    }
                    ?>


                </div>
            </div>

        </div>
    </div>
<script>
let archivosSeleccionados = [];

document.getElementById('file-upload').addEventListener('change', function(event) {
    const files = event.target.files;

    for (let i = 0; i < files.length; i++) {
        archivosSeleccionados.push(files[i]);
    }

    actualizarListaArchivos();
});

function actualizarListaArchivos() {
    const fileListContainer = document.getElementById('file-list-container');
    fileListContainer.innerHTML = '';

    archivosSeleccionados.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.classList.add('file-item');

        const fileName = document.createElement('span');
        fileName.textContent = file.name;

        const removeButton = document.createElement('button');
        removeButton.textContent = 'Eliminar';
        removeButton.addEventListener('click', function() {
            eliminarArchivo(index);
        });

        fileItem.appendChild(fileName);
        fileItem.appendChild(removeButton);
        fileListContainer.appendChild(fileItem);
    });
}

function eliminarArchivo(index) {
    archivosSeleccionados.splice(index, 1);
    actualizarListaArchivos();
}

document.getElementById('form-distribuidor').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const formData = new FormData();
    const contenido = document.querySelector('textarea[name="contenido"]').value;
    
    formData.append('contenido', contenido);

    archivosSeleccionados.forEach((file, index) => {
        formData.append('archivo_adjuntar[]', file);
    });

    fetch('', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(result => {
          document.getElementById('message-status').innerHTML = "<p>Mensaje enviado con éxito.</p>";
          document.querySelector('textarea[name="contenido"]').value = '';
          archivosSeleccionados = [];
          actualizarListaArchivos();
      }).catch(error => {
          document.getElementById('message-status').innerHTML = "<p>Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.</p>";
      });
});

</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../../assets/js/custom-js.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" rel="stylesheet">

</body>

</html>