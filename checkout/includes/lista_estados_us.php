<?php
include_once ('../../config/db_connect.php');
include_once('../../assets/lib/class.carrito.php');
include_once('../../assets/lib/funciones.php');
include_once('../../class/userClass.php');
include_once('../../class/checkoutClass.php');
$id_idioma_global = $_SESSION['id_idioma'];
$id_idioma=$id_idioma_global;

include_once('../../includes/vocabulario.php');
?>
<option value="-">- <?php echo $vocabulario_seleccione_estado ?> -</option>
<?php

$sql="SELECT * FROM sc_estados_us WHERE activo=1";
$resp=consulta($sql, $conn);
while( $regp=$resp->fetch_object() ) { ?>
	<option value="<?php echo $regp->cod ?>" <?php  if ( $regp->cod == $reg->provincia ) echo "selected" ?>><?php echo $regp->nombre ?></option>
<?php
}
?>
