<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

require_once( '../lib/bbdd.php' );
require_once( '../lib/funciones.php' );
require_once( '../lib/redsys/include/config.php' );
require_once( '../lib/redsys/include/apiRedsys7.php' );
require_once( '../lib/redsys/include/functions2.php' );

if ( isset( $_POST['datos_pedido'] ) ) {

	$datos = explode( '|', $_POST['datos_pedido'] );
	$Ds_Order = trim ( $datos[0] );
	$Ds_MerchantData = trim ( $datos[1] );
	$Ds_Response = $_POST['Ds_Response'];

	$ped_temp = obten_datos_pedido_temp ( $Ds_Order );

	$datetime = $ped_temp->fecha_creacion;
	$Ds_Amount = $ped_temp->total_pagado;
	$idioma = $ped_temp->idioma;

}else {

	$idioma = $_GET['lang'];

	$version = $_POST[ 'Ds_SignatureVersion' ];
	$params = $_POST[ 'Ds_MerchantParameters' ];
	$signatureRecibida = $_POST[ 'Ds_Signature' ];

	$data_array = json_decode( base64_decode ( $params ), true );

	$Ds_Date = $data_array["Ds_Date"];
	$Ds_Hour = $data_array["Ds_Hour"];
	$datetime = formato_fecha ( $Ds_Date, $Ds_Hour );
	$Ds_Amount = $data_array["Ds_Amount"]/100;
	$Ds_Order = $data_array["Ds_Order"];
	$Ds_MerchantCode = $data_array["Ds_MerchantCode"];
	$Ds_Terminal = $data_array["Ds_Terminal"];
	$Ds_Response = $data_array["Ds_Response"];
	$Ds_AuthorisationCode = $data_array["Ds_AuthorisationCode"];
	$Ds_MerchantData = $data_array["Ds_MerchantData"];

}

$log = "";
$log .= 'Ds_Date: ' . $Ds_Date . "\n";
$log .= 'Ds_Hour: ' . $Ds_Hour . "\n";
$log .= 'datetime: ' . $datetime . "\n";
$log .= 'Ds_Amount: ' . $Ds_Amount . "\n";
$log .= 'Ds_Order: ' . $Ds_Order . "\n";
$log .= 'Ds_MerchantCode: ' . $Ds_MerchantCode . "\n";
$log .= 'Ds_Terminal: ' . $Ds_Terminal . "\n";
$log .= 'Ds_Response: ' . $Ds_Response . "\n";
$log .= 'Ds_AuthorisationCode: ' . $Ds_AuthorisationCode . "\n";
$log .= 'Ds_MerchantData: ' . $Ds_MerchantData . "\n";
$log .= "\n";
$log .= "obtenemos id del pedido temporal\n\n";



/* obtenemos id del pedido temporal*/
$id_pedido_temp = obten_id_pedido_temp ( $Ds_Order );

$log .= 'id_pedido_temp: ' . $id_pedido_temp . "\n";
// echo 'id_pedido_temp: ' . $id_pedido_temp;
// echo "<br>";



/* obtenemos el id cliente del pedido */
$id_cliente = obten_id_user_pedido_temp ( $Ds_Order );

$log .= "id_cliente: " . $id_cliente . "\n\n";

// echo "id_cliente: " . $id_cliente;
// echo "<br>";



/* obtenemos datos del usuario */
$datos_user = obten_datos_user ( $id_cliente );

$nombre = $datos_user->nombre;
$email = $datos_user->email;
$ref_pedido = $Ds_MerchantData;

if ( (int)$Ds_Response <= 99 ) {

	/* operación ACEPTADA */
	$log .= "operación ACEPTADA \n";

	/* insertamos el pedido copiando del pedido temporal con el numero generado por redsys */
	$log .= "insertamos el pedido copiando del pedido temporal con el numero generado por redsys\n\n";
	$ped_temp = obten_datos_pedido_temp ( $Ds_Order );

	$valores ="";
	$valores .="ref_pedido='" . $ref_pedido  . "'";
	$valores .=", id_cliente='" . $ped_temp->id_cliente  . "'";
	$valores .=", id_envio='" . $ped_temp->id_envio . "'";
	$valores .=", id_facturacion='" . $ped_temp->id_facturacion . "'";
	$valores .=", total_sinenvio='" . $ped_temp->total_sinenvio . "'";
	$valores .=", gastos_envio='" . $ped_temp->gastos_envio . "'";
	$valores .=", descuento_aplicado='" . $ped_temp->descuento_aplicado . "'";
	$valores .=", descuento_id='" . $ped_temp->descuento_id . "'";
	$valores .=", importe_descuento='" . $ped_temp->importe_descuento . "'";
	$valores .=", metodo_pago='2'";
	$valores .=", redsys_num_order='" . $ped_temp->redsys_num_order . "'";
	$valores .=", estado_pago='Pagado'";
	$valores .=", fecha_pago='" . date('Y-m-d H:i:s') ."'";
	$valores .=", total_pagado='" . $ped_temp->total_pagado . "'";
	$valores .=", estado_envio='" . $ped_temp->estado_envio . "'";
	$valores .=", fecha_envio='" . $ped_temp->fecha_envio . "'";
	$valores .=", transportista='" . $ped_temp->transportista . "'";
	$valores .=", idioma='" . $ped_temp->idioma . "'";

	$sql="INSERT INTO pedidos SET $valores";

	$log .= "SQL = $sql\n\n";

	$conn->query($sql);

	/* obtenemos id del nuevo pedido */
	$id_pedido = $conn->insert_id;

	/* obtenemos las lineas de pedido temporal y las insertamos al pedido final */
	$sql="SELECT * FROM detalles_pedido_temp WHERE id_pedido = $id_pedido_temp";
	$res=consulta($sql, $conn);

	$log .= "insertamos lineas de pedido\n";

	while($reg=$res->fetch_object()) {

		$valores="";
		$valores.="id_pedido='" . $id_pedido . "'";
		$valores.=", id_prod='" . $reg->id_prod ."'";
		$valores.=", sku='" . $reg->sku ."'";
		$valores.=", cantidad='" . $reg->cantidad ."'";
		$valores.=", precio='" . $reg->precio ."'";
		$valores.=", fecha_creacion='" . date('Y-m-d H:i:s') ."'";

		$sql2="INSERT INTO detalles_pedido SET $valores";
		consulta( $sql2, $conn );

		$log .= "$sql2 \n";

	}

	$log .= "\n\nDetalle pedido\n" . detalle_pedido_ref ( $ref_pedido ) . "\n\n";


	/* marcamos el pedido como pagado*/
	$sql3 = "UPDATE pedidos SET redsys_num_order='" . $Ds_Order . "', estado_pago='Pagado', fecha_pago='" . date('Y-m-d H:i:s') ."' WHERE id=$id_pedido";
	consulta( $sql3, $conn );

	/* insertamos datos operación TPV */
	$valores_tpv ="";
	$valores_tpv .="tpv_order='" . $Ds_Order  . "'";
	$valores_tpv .=", ref_pedido='" . $Ds_MerchantData  . "'";
	$valores_tpv .=", tpv_amount='" . $Ds_Amount . "'";
	$valores_tpv .=", tpv_response='" . $Ds_Response . "'";
	$valores_tpv .=", tpv_authorisation_code='" . $Ds_AuthorisationCode . "'";
	$valores_tpv .=", tpv_datetime='" . $datetime . "'";
	$valores_tpv .=", tpv_estado=1";

	$sql_tpv = "INSERT INTO datos_tpv SET $valores_tpv";
	consulta( $sql_tpv, $conn );

	$log .= "\ninsertamos datos de operación TPV\n";
	$log .= "$sql_tpv\n\n";

	$detalle_pedido = lista_pedido_ref ( $ref_pedido );

	// echo 'Pedido: <br>';
	// echo $detalle_pedido;

	$lang = $idioma;


/*** CREAMOS LA SO EN ZOHO ***/

	if ( 1 ) {

		include_once('../lib/api-books.php');

		$res = obten_datos_user( $id_cliente );
		$res2 = obten_dir_envio ( obten_dir_envio_pedido( $id_pedido ) );
		$res3 = obten_dir_facturacion ( obten_dir_fact_pedido( $id_pedido ) );
		$res4 = obten_metodo_pago ( 2 );

		$prov_envio = obten_nombre_provincia ( $res2->provincia );
		$pais_envio = obten_nombre_pais ( $res2->pais );
		$gastos_envio = $ped_temp->gastos_envio;

		$prov_factura = obten_nombre_provincia ( $res3->provincia );
		$pais_factura = obten_nombre_pais ( $res3->pais );

		$contact_id='';
		$nif = $res->nif_cif;
		$reg = findContactNif ( $nif );

		if ( $reg["contact_found"] ) {
			$contact_id = $reg['contact_id'];
		}else {

		$contact_name = $res->nombre . ' ' . $res->apellidos;
		$contact_name = str_replace( '***', '', trim( $contact_name ) );
		$company_name = $res->empresa;
		$value_nif = $res->nif_cif;
		$first_name = $res->nombre;
		$last_name = $res->apellidos;
		$email = $res->email;
		$phone = $res->telefono;

		$contactData = array(
		    "contact_name" => $contact_name,
		    "company_name" => $company_name,
		    "custom_fields" => [ array (
		                                "label" => "NIF/CIF",
		                                "value" => $value_nif
		                        ),
		],
		"contact_persons" =>[ array (
		                            "first_name" => $first_name,
		                            "last_name" => $last_name,
		                            "email" => $email,
		                            "phone" => $phone,
		                        ),
		]
		);

		$contactData = json_encode ( $contactData );
		$reg = createNewContact( $contactData ) ;
		$contact_id = $reg['contact']['contact_id'];
		}

		echo 'user id: ' . $contact_id . "<br><br>";

		$log .= "\nIdentificador usuario: $contact_id\n";

		$importe_descuento = 0;
		$compensa_descuento = 0;
		$notas_descuento = "";


		if ( $ped_temp->importe_descuento > 0 ) {

			$descuento = obten_descuento( $ped_temp->descuento_id );

			$notas_descuento = " | Código descuento: " . $descuento->nombre_descuento . " (" . $descuento->valor . $descuento->tipo . " - " . obten_aplicacion_descuento ( $descuento->aplicacion_descuento )->aplicacion_texto . ")";

			/* comprobamos si el codigo descuento es solo para determinados productos*/
	    if ( $descuento->aplicacion_descuento != 1 ) {

	        $importe_descuento = 0;

	    }else {

	    	if ( $descuento->tipo == '%') {
	    		$importe_descuento = $descuento->valor.$descuento->tipo;

	            /* compensamos los gastos de envio porque al crear la SO se le aplica tambien el descuento */
	            $compensa_descuento = $gastos_envio / ( 1 + $descuento->valor/100 ) * ( $descuento->valor / 100);

	    	}elseif ( $descuento->tipo == '€') {
	    		$importe_descuento = $descuento->valor;
	    	}
	    }
		}


		if ( $res2->eori != '' && strlen ( $res2->eori ) > 10 ) {
			$notes = "Numero EORI: " . $res2->eori . " | Forma de pago: " . $res4->nombre . " | Idioma: " . $idioma . $notas_descuento;
		}else {
			$notes = "Forma de pago: " . $res4->nombre . " | Idioma: " . $idioma . $notas_descuento;
		}

		$salesOrderData = array(
		    "customer_id" => $contact_id,
		    "date" => date("Y-m-d"),
		    "is_inclusive_tax" => false,
		    "line_items" => array (),
		    "notes" => $notes,
		    "discount_amount" => 0,
		    "discount" => $importe_descuento,
		    "discount_applied_on_amount" => 0,
		    "is_discount_before_tax" => true,
		    "discount_type" => "entity_level",
		    "salesorder_number" => $ref_pedido,
		    "tax_percentage" => 21,
		    "reference_number" => "Tienda Online Smartcret",
		    "salesperson_id" => "459933000119333749",
		    "template_id" => "459933000037745462",
		    "custom_fields" =>[ array (
		                            "customfield_id" => "459933000093914053",
		                            "value" => true
		                        )
													]
		);

		$sql="SELECT * FROM detalles_pedido WHERE id_pedido = $id_pedido";
	  $res=consulta($sql, $conn);

	  $id_prods_descuento = obten_aplicacion_descuento ( $descuento->aplicacion_descuento )->id_prods;
    $array_id_prods_descuento = explode( '|', $id_prods_descuento );

	  $i = 1;
	  while($reg=$res->fetch_object()) {

	  	$prod = obten_datos_producto ( $reg->id_prod );

	  	if ( $ped_temp->importe_descuento > 0 && $descuento->aplicacion_descuento != 1 ) {

            if ( in_array ( $prod->id, $array_id_prods_descuento ) ) {
                $producto_descuento = $prod->precio_es - ( $prod->precio_es * $descuento->valor /100 );
                $reg->precio = $producto_descuento;

                echo $prod->nombre_es . ' - ' . $prod->precio_es;
                echo "<br>";
            }
        }

	  	array_push (
	  		$salesOrderData['line_items'] ,
	  		array (
	  			"item_order" => $i,
	  			"name" => $prod->nombre_es,
	  			"quantity" => $reg->cantidad,
	  			"rate" => $reg->precio / 1.21,
	  			"tax_id" => "459933000005936001",
	  			"warehouse_id" => "459933000007839005",
	  			"warehouse_name" => "Grupo Negocios PO SLU",
	  			"unit" => "ud",
	  		)
	  	);
			$i++;
	  	}

	  	$i++;
	  	array_push (
	  		$salesOrderData['line_items'] ,
	  		array (
	  			"item_order" => $i,
	  			"name" => "Portes",
					"quantity" => 1,
	  			"rate" => ( $gastos_envio / 1.21 ) + $compensa_descuento,
	  			"tax_id" => "459933000005936001",
	  			"warehouse_id" => "459933000007839005",
	  			"warehouse_name" => "Grupo Negocios PO SLU",
	  			"unit" => "ud",
	  		)
	  	);

		// var_dump ( $salesOrderData );
	 //  echo "<br><br>";
		// exit;

		$data = json_encode ( $salesOrderData );
		$reg_so = createSalesOrder ( $data );

		$res_so = ( (array) ( $reg_so ) );
		$saleorder_id = $res_so['salesorder']->salesorder_id;

		echo 'SO id: ' . $saleorder_id . "<br><br>";
		echo '<a href="https://books.topciment.com/app/637820086#/salesorders/' . $saleorder_id . '?filter_by=Status.All&per_page=200&sort_column=created_time&sort_order=D" target="_blank">Ver orden de venta</a>';
		echo "<br><br>";

		if ( $saleorder_id != '' ) {
			$log .= "Se ha creado la orden de venta ( $saleorder_id )\n";
			$log .= "URL: https://books.topciment.com/app/637820086#/salesorders/" . $saleorder_id . "?filter_by=Status.All&per_page=200&sort_column=created_time&sort_order=D\n";
		}else {
			$log .= "NO se ha creado la orden de venta\n";
		}

		$s_address = $res2->direccion;
		$s_city = $res2->localidad;
		$s_state = $prov_envio;
		$s_zip = $res2->cp;
		$s_country = $pais_envio;
		$s_phone = $res->telefono;
		$s_attention = $res->nombre . $res->apellidos;

		$shippingAddress = array(
		    "address" => $s_address,
		    "city" => $s_city,
		    "state" => $s_state,
		    "zip" => $s_zip,
		    "country" => $s_country,
		    "phone" => $s_phone,
		    "attention" => $s_attention,
		);

		// var_dump ( $shippingAddress );
		// echo "<br><br>";

		$shippingAddress = json_encode ( $shippingAddress );
		$reg = updateSaleOrderShippingAddress( $saleorder_id, $shippingAddress );

		$b_address = $res3->direccion;
		$b_city = $res3->localidad;
		$b_state = $prov_factura;
		$b_zip = $res3->cp;
		$b_country = $pais_factura;
		$b_phone = $res3->telefono;
		$b_attention = $res3->nombre . $res3->apellidos;

		$billingAddress = array(
		    "address" => $b_address,
		    "city" => $b_city,
		    "state" => $b_state,
		    "zip" => $b_zip,
		    "country" => $b_country,
		    "phone" => $b_phone,
		    "attention" => $b_attention,
		);

		// var_dump ( $billingAddress );
		// echo "<br><br>";

		$billingAddress = json_encode ( $billingAddress );
		$reg = updateSaleOrderBillingAddress( $saleorder_id, $billingAddress );

		} else {
			echo "<p style='font-size:18px; font-weight: bold'>Pedido OK</p>";
			echo "<br><br><br>";
		}

}else {
	/* operacion DENEGADA*/
	echo 'Operación denegada';

	$log .= "\n\noperacion DENEGADA\n";
	$log .= "\ninsertamos datos operación denegada TPV\n";

	/* insertamos datos operación TPV */
	$valores_tpv ="";
	$valores_tpv .="tpv_order='" . $Ds_Order  . "'";
	$valores_tpv .=", ref_pedido='" . $Ds_MerchantData  . "'";
	$valores_tpv .=", tpv_amount='" . $Ds_Amount . "'";
	$valores_tpv .=", tpv_response='" . $Ds_Response . "'";
	$valores_tpv .=", tpv_authorisation_code='" . $Ds_AuthorisationCode . "'";
	$valores_tpv .=", tpv_datetime='" . $datetime . "'";
	$valores_tpv .=", tpv_estado=0";

	$sql_tpv = "INSERT INTO datos_tpv SET $valores_tpv";
	consulta( $sql_tpv, $conn );

	$log .= "$sql_tpv";

	$lang = $idioma;

}

/* Escribimos el proceso en el archivo log */
print_log ('pedidos', $log);

function formato_fecha ( $Ds_Date, $Ds_Hour) {

	$fecha_temp = explode( '%2F', $Ds_Date );
	$dia = $fecha_temp[0];
	$mes = $fecha_temp[1];
	$anio = $fecha_temp[2];

	$hora_temp = explode( '%3A', $Ds_Hour );
	$hora = $hora_temp[0];
	$min = $hora_temp[1];

	return $anio . '-' . $mes . '-' . $dia . ' ' . $hora . ':' . $min;

}
