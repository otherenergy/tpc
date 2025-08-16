<?php

//1) Si es cliente nacional - Operac.Internas Sujetas 21% (REP) [21%]
//2) Canarias, Ceuta y Melilla, exportación - Exportaciones de bienes [0%]
//3) Unión Europea, con VIES - Entregas Intracomunitarias Exentas [0%]
//4) Unión Europea, sin VIES (no superado max anual) - Operac.Internas Sujetas 21% (REP) [21%]
//5) Unión Europea, sin VIES (superado max anual) - IVA según pais destino
//6) Resto de países - Exportaciones de bienes [0%]

//459933000005936001 - Operac.Internas Sujetas 21% (REP) [21%]
//459933000000355240 - Exportaciones de bienes [0%]
//459933000000355235 - Entregas Intracomunitarias Exentas [0%]

//$res = obten_datos_user($_SESSION['smart_user']['id']);
//$res2 = obten_dir_envio_user ( $_SESSION['smart_user']['dir_envio'] );
//$res3 = obten_dir_fact_user ( $_SESSION['smart_user']['dir_facturacion'] );
//$res4 = obten_metodo_pago ( $_SESSION['smart_user']['metodo_pago'] );

$tax_id;
$tipo_impuesto = obten_tipo_impuesto_envio( $res3->id, $res2->id);

// echo $tipo_impuesto;
// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo "<br>";
// exit;

switch ( $tipo_impuesto ) {

    case '1':
        $tax_id = '459933000005936001';
        $txt_impuesto = 'Operac.Internas Sujetas 21%';
        break;

    case '2':
        $tax_id = '459933000000355240';
        $txt_impuesto = 'Exportaciones de Bienes';
        break;

    case '3':
        $tax_id = '459933000000355235';
        $txt_impuesto = 'Entregas Intracomunitarias Exentas';
        break;

    case '4':
        $tax_id = '459933000005936001';
        $txt_impuesto = 'Operac.Internas Sujetas 21%';
        break;

    case '5':

        $cod_pais = $res2->pais;
        $iva = obten_iva_books ( $cod_pais );

        $tax_id = $iva->books_id_iva;
        $txt_impuesto = $iva->nombre_iva;
        break;

    case '6':
        $tax_id = '459933000000355240';
        $txt_impuesto = 'Exportaciones de Bienes';
        break;
}

// exit;

if( $met_pago == 1 ) {

// include_once( '/assets/lib/api-books.php' );
include_once( dirname( __DIR__ ) . '/assets/lib/api-books.php' );

$contact_id='';
//si el usuario ya tiene id en books utilizamos ese
if ( $reg->books_id != null && $reg->books_id != '' ) {

    $contact_id = $reg->books_id;

// si no comprobamos si para ese email ya existe un id en books y si es asi lo utilizmos
} else {

    $reg = obtenIdEmail ( trim ( $res->email ) );

    if ( $reg != '' || $reg != null ) {

        $contact_id = $reg;

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
            "billing_address" => array (
                                        "attention" => $res3->nombre . ' ' . $res3->apellidos,
                                        "address" => $res3->direccion,
                                        "city" => $res3->localidad,
                                        "state" => $prov_factura,
                                        "zip" => $res3->cp,
                                        "country" => $pais_factura,
                                        "phone" => $phone
            ),

            "shipping_address" => array (
                                        "attention" => $res->nombre . ' ' . $res->apellidos,
                                        "address" => $res2->direccion,
                                        "city" => $res2->localidad,
                                        "state" => $prov_factura,
                                        "zip" => $res2->cp,
                                        "country" => $pais_envio,
                                        "phone" => $res->telefono
            ),
            "custom_fields" => [
                array (
                    "label" => "NIF/CIF",
                    "value" => $value_nif
                ),
                array (
                    "label" => "Comisionista",
                    "value" => "smartcret"
                ),
                array (
                    "label" => "Empresa grupo",
                    "value" => "Smartcret"
                ),
                array (
                    "label" => "NIF CIF",
                    "value" => $value_nif
                ),
                array (
                    "label" => "Tipo Impuesto",
                    "value" => $txt_impuesto
                ),
                array (
                    "label" => "Forma de pago",
                    "value" => "Transferencia"
                )
            ],
        );

        $contactData = json_encode ( $contactData );
        $reg = createNewContact( $contactData ) ;
        $contact_id = $reg['contact']['contact_id'];
    }

    //guardamos el id de books asociaco al cliente
    guarda_books_id ( $contact_id, $res->email );

    //guardamos la llamada a la API en base de datos
    guarda_llamada_api ( 'cliente', $ref_pedido, $contact_id, $contactData );

}

$importe_descuento = 0;
$compensa_descuento = 0;
$notas_descuento = "";

if ( isset ( $_SESSION['codigo_descuento']['nombre'] ) && isset ( $_SESSION['codigo_descuento']['valor'] ) )  {

    $notas_descuento = " | Código descuento: " . $_SESSION['codigo_descuento']['nombre'] . " (" . $_SESSION['codigo_descuento']['valor'] . $_SESSION['codigo_descuento']['tipo'] . " - " . obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['aplicacion'] )->aplicacion_texto . ")";

    /* comprobamos si el codigo descuento es solo para determinados productos*/
    if ( $_SESSION['codigo_descuento']['aplicacion'] != 1 || $_SESSION['codigo_descuento']['aplicacion'] != 2) {

        $importe_descuento = 0;

    }else {

    	if ( $_SESSION['codigo_descuento']['tipo'] == '%') {
    		$importe_descuento = $_SESSION['codigo_descuento']['valor'] . $_SESSION['codigo_descuento']['tipo'];

            /* compensamos los gastos de envio porque al crear la SO se le aplica tambien el descuento */
            $compensa_descuento = $gastos_envio / ( 1 + $_SESSION['codigo_descuento']['valor']/100 ) * ( $_SESSION['codigo_descuento']['valor'] / 100);

    	}elseif ( $_SESSION['codigo_descuento']['tipo'] == '€') {
    		$importe_descuento = $_SESSION['codigo_descuento']['valor'];
    	}
    }
}


/* si hay oferta 2x1*/
$importe_descuento_kits = 0;
if ( $_SESSION['codigo_descuento']['p2x1'] != 0 ) {

    $id_prods_descuento = obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['p2x1'] )->id_prods;
    $array_id_prods_descuento = explode( '|', $id_prods_descuento );

    $carro = $carrito->get_content();
    $num_kits = 0;
    $precio_menor = 0;
    $descuento_kits = 0;
    $importe_descuento_kits = 0;


    foreach($carro as $producto) {
        if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
            $num_kits += $producto['cantidad'];
            if ( $precio_menor == 0) {
                $precio_menor = $producto['precio'];
            }else {
                $precio_menor = ( $precio_menor < $producto['precio'] ) ? $precio_menor : $producto['precio'];
            }
        }
    }


    switch ( $num_kits ) {

        case '1':
            $descuento_kits = 0;
            break;
        case '2':
            $descuento_kits = 1 * $precio_menor;
            break;
        case '3':
            $descuento_kits = 1 * $precio_menor;
            break;
        case '4':
            $descuento_kits = 2 * $precio_menor;
            break;
        case '5':
            $descuento_kits = 2 * $precio_menor;
            break;
        case '6':
            $descuento_kits = 3 * $precio_menor;
            break;
        case '7':
            $descuento_kits = 3 * $precio_menor;
            break;
        case '8':
            $descuento_kits = 4 * $precio_menor;
            break;
        case '9':
            $descuento_kits = 4 * $precio_menor;
            break;
        case '10':
            $descuento_kits = 5 * $precio_menor;
            break;
        case '11':
            $descuento_kits = 5 * $precio_menor;
            break;
        case '12':
            $descuento_kits = 6 * $precio_menor;
            break;
        case '13':
            $descuento_kits = 6 * $precio_menor;
            break;
        case '14':
            $descuento_kits = 7 * $precio_menor;
            break;
        case '15':
            $descuento_kits = 7 * $precio_menor;
            break;
        case '16':
            $descuento_kits = 8 * $precio_menor;
            break;
        case '17':
            $descuento_kits = 8 * $precio_menor;
            break;
        case '18':
            $descuento_kits = 9 * $precio_menor;
            break;
        case '19':
            $descuento_kits = 9 * $precio_menor;
            break;
        case '20':
            $descuento_kits = 10 * $precio_menor;
            break;
        case '21':
            $descuento_kits = 10 * $precio_menor;
            break;

        default:
            # code...
            break;
    }

    $num_kits_descuento = intval( $num_kits / 2 );
    $importe_descuento_kits += $descuento_kits;

}


/* si hay producto gratis */
if ( $_SESSION['codigo_descuento']['regalo'] != 0 ) {

    $id_prods_descuento = obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['regalo_condicion'] )->id_prods;
    $array_id_prods_descuento = explode( '|', $id_prods_descuento );

    $carro = $carrito->get_content();
    $productos_promocion = 0;
    $hay_producto_gratis = false;

    foreach($carro as $producto) {
        if( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
            $productos_promocion += $producto['cantidad'];
        }
    }

    if ( $productos_promocion > 1 )  $hay_producto_gratis = true;

}



// echo $importe_descuento;
// exit;

if ( $res2->eori != '' && strlen ( $res2->eori ) > 10 ) {
	$notes = "Numero EORI: " . $res2->eori . " | Forma de pago: " . $res4->nombre . " | Idioma: " . obten_idioma_actual () . $notas_descuento;
}else {
	$notes = "Forma de pago: " . $res4->nombre . " | Idioma: " . obten_idioma_actual () . $notas_descuento;
}

$salesOrderData = array(
    "customer_id" => $contact_id,
    "date" => date("Y-m-d"),
    "is_inclusive_tax" => false,
    "line_items" => array (),
    "payment_terms" => 2,
    "payment_terms_label" => "Transferencia",
    "notes" => $notes,
    // "discount_amount" => 0,
    // "discount" => $importe_descuento,
    "discount_applied_on_amount" => 0,
    "is_discount_before_tax" => true,
    "discount_type" => "item_level",
    // "discount_type" => "entity_level",
    // "shipping_charge" => $gastos_envio,
    "salesorder_number" => $ref_pedido,
    "tax_percentage" => '21',
    "reference_number" => "Tienda Online Smartcret",
    "salesperson_id" => "459933000119333749",
    "template_id" => "459933000037068755",
    "custom_fields" =>[ array (
                            "customfield_id" => "459933000093914053",
                            "value" => true
                        ),
                        // array (
                        //     "customfield_id" => "459933000048957187",
                        //     "value" => "TSB"
                        // ),
                        array (
                            "customfield_id" => "459933000088385214",
                            "value" => "Venta"
                        ),
                        array (
                            "customfield_id" => "459933000074198397",
                            "value" => "No"
                        )
                    ]
);

$id_prods_descuento = obten_aplicacion_descuento ( $_SESSION['codigo_descuento']['aplicacion'] )->id_prods;
$array_id_prods_descuento = explode( '|', $id_prods_descuento );

if ( $carrito->articulos_total() > 0 ) {

    $i = 1;
	$carro = $carrito->get_content();
    $descuento_aplicado_articulo = 0;

	foreach ( $carro as $producto ) {

        if ( isset ( $_SESSION['codigo_descuento']['nombre'] ) && isset ( $_SESSION['codigo_descuento']['valor'] ) && $_SESSION['codigo_descuento']['aplicacion'] != 1 ) {

            if ( in_array ( $producto['id'], $array_id_prods_descuento ) ) {
                // $producto_descuento = $producto["precio"] - ( $producto["precio"] * $_SESSION['codigo_descuento']['valor'] /100 );
                // $producto["precio"] = $producto_descuento;

                if ( $_SESSION['codigo_descuento']['tipo'] == '%' ) {
                    $descuento_aplicado_articulo = $_SESSION['codigo_descuento']['valor'].$_SESSION['codigo_descuento']['tipo'];
                }else {
                    $descuento_aplicado_articulo = $_SESSION['codigo_descuento']['valor'];
                }
            }else $descuento_aplicado_articulo = 0;
        }

        /***** si hay descuento adicional en otro articulo *****/

        if ( $_SESSION['codigo_descuento']['descuento_otro_producto'] == $producto['id'] ) {
            $descuento_aplicado_articulo = $_SESSION['codigo_descuento']['cantidad_descuento_otro_producto'] . '%';
        }

		array_push (
			$salesOrderData['line_items'] ,
			array (
        		"item_order" => $i,
                "item_id" => $producto["books_id"],
                "name" => $producto["nombre"],
                "quantity" => $producto["cantidad"],
                "discount" => $descuento_aplicado_articulo,
                "rate" => formatea_importe( $producto["precio"] / 1.21 ),
                "tax_id" => $tax_id,
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
            "item_id" => "459933000000278064",
            "quantity" => 1,
            // "rate" =>  $gastos_envio_final,
            "rate" => formatea_importe( ( $gastos_envio / 1.21 ) + $compensa_descuento ),
            "tax_id" => $tax_id,
            "warehouse_id" => "459933000007839005",
            "warehouse_name" => "Grupo Negocios PO SLU",
            "unit" => "ud",
        )
    );

    if ( $importe_descuento_kits != 0 ) {

        $i++;
        array_push (
            $salesOrderData['line_items'] ,
            array (
                "item_order" => $i,
                "item_id" => "459933000154804795",
                "quantity" => $num_kits_descuento,
                // "discount" => $importe_descuento_kits / 1.21,
                "rate" => - formatea_importe( $precio_menor / 1.21 ),
                "tax_id" => $tax_id,
                "warehouse_id" => "459933000007839005",
                "warehouse_name" => "Grupo Negocios PO SLU",
                "unit" => "ud",
            )
        );
    }

    /* hay producto regalo*/
    if ( $_SESSION['codigo_descuento']['regalo'] != 0 ) {
        $i++;

        $id_prod_regalo = $_SESSION['codigo_descuento']['regalo'];
        $prod_regalo = obten_datos_producto ($id_prod_regalo);

    	array_push (
    		$salesOrderData['line_items'] ,
    		array (
    			"item_order" => $i,
                "item_id" => $prod_regalo->books_id,
    			"quantity" => 1,
                "discount" => "100%",
    			"rate" => formatea_importe( $prod_regalo->precio_es / 1.21 ),
                "tax_id" => $tax_id,
    			"warehouse_id" => "459933000007839005",
    			"warehouse_name" => "Grupo Negocios PO SLU",
    			"unit" => "ud",
    		)
    	);
    }

}


// var_dump ( $salesOrderData );
// echo "<br><br>";
// echo json_encode ( $salesOrderData );
// echo "<br><br>";

// exit;

$data = json_encode ( $salesOrderData );
$reg_so = createSalesOrder ( $data );

// var_dump ( $reg_so );
// echo "<br><br>";

$res_so = ( (array) ( $reg_so ) );
$saleorder_id = $res_so['salesorder']->salesorder_id;

// guardamos la llamada a la API en base de datos
guarda_llamada_api ( 'orden_venta', $ref_pedido, $saleorder_id, $data );

// echo 'SO id: ' . $saleorder_id . "<br><br>";
// echo "<br><br>";

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

// guardamos la llamada a la API en base de datos
guarda_llamada_api ( 'direccion_envio', $ref_pedido, '', $shippingAddress );

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

// guardamos la llamada a la API en base de datos
guarda_llamada_api ( 'direccion_facturacion', $ref_pedido, '', $billingAddress );

} else {
	// echo "<p style='font-size:40px; font-weight: bold'>Pedido OK</p>";
	// echo "<br><br><br>";
}

?>