<?php
include_once('../../assets/lib/bbdd.php');

$sql="SELECT * FROM provincias WHERE pais ='1' AND activo=1";
$res=consulta($sql, $conn);
while($reg=$res->fetch_object()) { ?>
	<?php if( $reg->id_prov < 10 ) $reg->id_prov = '0' . $reg->id_prov?>
	<option value="<?php echo $reg->id_prov ?>"><?php echo $reg->nombre_prov ?></option>
	<?php
}
?>
