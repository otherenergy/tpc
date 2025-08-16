<?php
include_once ('../../config/db_connect.php');
include_once('../../assets/lib/class.carrito.php');
include_once('../../assets/lib/funciones.php');
include_once('../../class/userClass.php');
include_once('../../class/checkoutClass.php');
?>
<option value="-">Seleccionar:</option>
<optgroup label='Peninsula'>
<?php
	$sql="SELECT * FROM provincias WHERE pais ='1' AND activo=1 AND id_prov < 100";
	$res=consulta($sql, $conn);
	while($reg=$res->fetch_object()) { ?>
		<?php if( $reg->id_prov < 10 ) $reg->id_prov = '0' . $reg->id_prov?>
		<option value="<?php echo $reg->id_prov ?>"><?php echo $reg->nombre_prov ?></option>
		<?php
	}
?>
</optgroup>

<optgroup label='Islas Canarias'>
<?php
	$sql="SELECT * FROM provincias WHERE pais ='1' AND activo=1 AND id_prov > 100";
	$res=consulta($sql, $conn);
	while($reg=$res->fetch_object()) { ?>
		<?php if( $reg->id_prov < 10 ) $reg->id_prov = '0' . $reg->id_prov?>
		<option value="<?php echo $reg->id_prov ?>"><?php echo $reg->nombre_prov ?></option>
		<?php
	}
?>
</optgroup>

