<form action="pedido-enviado" method="POST">
<label for="">Nº Pedido: </label>
	<input type="text" name="salesorder_number" >
	<br>
	<label for="">Transportista: </label>
	<input type="text" name="delivery_method" >
	<br>
	<label for="">Nº Seguimiento: </label>
	<input type="text" name="tracking_number" >
	<br>
	<label for="">Fecha de envío: </label>
	<input type="text" name="shipment_date" >
	<br>
	<input type="submit" value="Enviar">
</form>
<style>
	form {
		width: 500px;
		margin: 100px auto;
	}
	input {
		margin: 8px 0;
	}
</style>
