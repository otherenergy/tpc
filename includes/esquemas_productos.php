<?php
	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);
	// ini_set('html_errors', 1);

	if ($producto_prod->formato != '1111' && $producto_prod->acabado != '1111' && $producto_prod->color != '1111'){
		$recoger_producto_unico = $userClass->recoger_producto_unico($id_producto, $id_idioma);
		$offers = [];	
		
		foreach ($recoger_producto_unico as $variante) {

			$offers[] = [
				"@type" => "Offer",
				"priceCurrency" => ($variante->cod_pais === 'US') ? 'USD' : 'EUR',
				"price" => $variante->precio, 
				"availability" => "https://schema.org/InStock",
				"url" => "https://www.smartcret.com/$idioma_url/$url",
				"seller" => [
					"@type" => "Organization",
					"name" => "Smartcret"
				],
				"eligibleRegion" => [
                    "@type" => "GeoShape",
                    "addressCountry" => $variante->cod_pais 
                ],
				"priceSpecification" => [
					"@type" => "UnitPriceSpecification",
					"priceCurrency" => ($variante->cod_pais === 'US') ? 'USD' : 'EUR',
					"price" => $variante->precio, 
					"referencePrice" => $variante->precio_base,
					"eligibleQuantity" => [
						"@type" => "QuantitativeValue",
						"value" => 1,
						"unitCode" => "C62" 
					]
				]
			];
		};

		$schema = [
			"@context" => "https://schema.org/",
			"@type" => "Product", // Cambiado de ProductGroup a Product
			"url" => "https://www.smartcret.com/$idioma_url/$url",
			"name" => $url_metas->title,
			"description" => $url_metas->description,
			"image" => "https://www.smartcret.com/$imagen_principal_producto",
			"sku" => $producto_prod->sku,
			"gtin13" => $producto_prod->ean,
			"brand" => [
				"@type" => "Brand",
				"name" => "Smartcret"
			],
			"manufacturer" => [
				"@type" => "Organization",
				"name" => "Smartcret",
				"address" => [
					"@type" => "PostalAddress",
					"streetAddress" => "Pol. Ind. Mas de Tous",
					"addressLocality" => "Pobla de Vallbona",
					"postalCode" => "46185",
					"addressRegion" => "Valencia",
					"addressCountry" => "ES"
				],
				"image" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"logo" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"url" => "https://www.smartcret.com",
				"legalName" => "Grupo Negocios PO SLU",
				"telephone" => "+34674409942",
				"taxID" => "B97539076"
			],
			"category" => "https://www.google.com/search?tbm=shop&q=Home+Improvement+%3E+Paint",
			"aggregateRating" => [
				"@type" => "AggregateRating",
				"ratingValue" => "5",
				"reviewCount" => "17"
			],
			"offers" => $offers
		];
		
	}
	elseif ($producto_prod->acabado == '1111' && $producto_prod->color == '1111' && $producto_prod->formato != '1111'){
		$recoger_atributos_color_acabado = $userClass->recoger_atributos_color_acabado($id_producto, $id_idioma);

		$productosAgrupados = [];

		foreach ($recoger_atributos_color_acabado as $fila) {
			$idProducto = $fila->id_producto;
			
			if (!isset($productosAgrupados[$idProducto])) {
				$productosAgrupados[$idProducto] = [
					'id_producto' => $idProducto,
					'nombre' => $fila->nombre,
					'sku' => $fila->sku,
					'ean' => $fila->ean,
					'valor_atributo_color' => $fila->valor_atributo_color,
					'valor_atributo_acabado' => $fila->valor_atributo_acabado,
					'variantes_precios' => [] 
				];
			};
			
			$productosAgrupados[$idProducto]['variantes_precios'][] = [
				'cod_pais' => $fila->cod_pais,
				'precio' => $fila->precio,
				'precio_base' => $fila->precio_base
			];
		}
		
		$variantes = [];
		foreach ($productosAgrupados as $variante) {
			$offers = [];
			
			foreach ($variante['variantes_precios'] as $variante_precio) {
				$offers[] = [
					"@type" => "Offer",
					"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
					"price" => $variante_precio['precio'], // Precio con descuento
					"availability" => "https://schema.org/InStock",
					"url" => "https://www.smartcret.com/$idioma_url/$url",
					"seller" => [
						"@type" => "Organization",
						"name" => "Smartcret"
					],
					"eligibleRegion" => [
						"@type" => "GeoShape",
						"addressCountry" => $variante_precio['cod_pais'] // Reino Unido
					],
					"priceSpecification" => [
						"@type" => "UnitPriceSpecification",
						"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
						"price" => $variante_precio['precio'], // Precio con descuento
						"referencePrice" => $variante_precio['precio_base'], // Precio original antes del descuento
						"eligibleQuantity" => [
							"@type" => "QuantitativeValue",
							"value" => 1,
							"unitCode" => "C62" // Código ISO para unidades
						]
					]
				];
			};

			$variantes[] = [
				"@type" => "Product",
				"url" => "https://www.smartcret.com/$idioma_url/$url",
				"name" => $variante['nombre'],
				"description" => $url_metas->description,
				"image" => "https://www.smartcret.com/$imagen_principal_producto",
				"color" => $variante['valor_atributo_color'],
				"sku" => $variante['sku'],
				"gtin13" => $variante['ean'],
				"additionalProperty" => [
					"@type" => "PropertyValue",
					"name" => "Acabado",
					"value" => $variante['valor_atributo_acabado']
				],
				"offers" => $offers
			];
		}
		
		// Crear el esquema principal y asignar las variantes
		$schema = [
			"@context" => "https://schema.org/",
			"@type" => "ProductGroup",
			"url" => "https://www.smartcret.com/$idioma_url/$url",
			"name" => $url_metas->title,
			"description" => $url_metas->description,
			"image" => "https://www.smartcret.com/$imagen_principal_producto",
			"brand" => [
				"@type" => "Brand",
				"name" => "Smartcret"
			],
			"manufacturer" => [
				"@type" => "Organization",
				"name" => "Smartcret",
				"address" => [
					"@type" => "PostalAddress",
					"streetAddress" => "Pol. Ind. Mas de Tous",
					"addressLocality" => "Pobla de Vallbona",
					"postalCode" => "46185",
					"addressRegion" => "Valencia",
					"addressCountry" => "ES"
				],
				"image" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"logo" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"url" => "https://www.smartcret.com",
				"legalName" => "Grupo Negocios PO SLU",
				"telephone" => "+34674409942",
				"taxID" => "B97539076"
			],
			"category" => "https://www.google.com/search?tbm=shop&q=Home+Improvement+%3E+Paint",
			"aggregateRating" => [
				"@type" => "AggregateRating",
				"ratingValue" => "5",
				"reviewCount" => "17"
			],
			"hasVariant" => $variantes
		];
		
	}
	elseif ($producto_prod->acabado != '1111' && $producto_prod->color == '1111' && $producto_prod->formato != '1111'){
		$recoger_atributos_color = $userClass->recoger_atributos_color($id_producto, $id_idioma);
		$productosAgrupados = [];

		foreach ($recoger_atributos_color as $fila) {

			$idProducto = $fila->id_producto;
			
			if (!isset($productosAgrupados[$idProducto])) {
				$productosAgrupados[$idProducto] = [
					'id_producto' => $idProducto,
					'nombre' => $fila->nombre,
					'sku' => $fila->sku,
					'ean' => $fila->ean,
					'valor_atributo_color' => $fila->valor_atributo_color,
					'variantes_precios' => [] 
				];
			};
			
			$productosAgrupados[$idProducto]['variantes_precios'][] = [
				'cod_pais' => $fila->cod_pais,
				'precio' => $fila->precio,
				'precio_base' => $fila->precio_base
			];
		}
		
		$variantes = [];
		foreach ($productosAgrupados as $variante) {
			$offers = [];
			foreach ($variante['variantes_precios'] as $variante_precio) {
				$offers[] = [
					"@type" => "Offer",
					"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
					"price" => $variante_precio['precio'], // Precio con descuento
					"availability" => "https://schema.org/InStock",
					"url" => "https://www.smartcret.com/$idioma_url/$url",
					"seller" => [
						"@type" => "Organization",
						"name" => "Smartcret"
					],
					"eligibleRegion" => [
						"@type" => "GeoShape",
						"addressCountry" => $variante_precio['cod_pais'] // Reino Unido
					],
					"priceSpecification" => [
						"@type" => "UnitPriceSpecification",
						"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
						"price" => $variante_precio['precio'], // Precio con descuento
						"referencePrice" => $variante_precio['precio_base'], // Precio original antes del descuento
						"eligibleQuantity" => [
							"@type" => "QuantitativeValue",
							"value" => 1,
							"unitCode" => "C62" // Código ISO para unidades
						]
					]
				];
			};

			$variantes[] = [
				"@type" => "Product",
				"url" => "https://www.smartcret.com/$idioma_url/$url",
				"name" => $variante['nombre'],
				"description" => $url_metas->description,
				"image" => "https://www.smartcret.com/$imagen_principal_producto",
				"color" => $variante['valor_atributo_color'],
				"sku" => $variante['sku'],
				"gtin13" => $variante['ean'],
				"offers" => $offers
			];
		}
		
		// Crear el esquema principal y asignar las variantes
		$schema = [
			"@context" => "https://schema.org/",
			"@type" => "ProductGroup",
			"url" => "https://www.smartcret.com/$idioma_url/$url",
			"name" => $url_metas->title,
			"description" => $url_metas->description,
			"image" => "https://www.smartcret.com/$imagen_principal_producto",
			"brand" => [
				"@type" => "Brand",
				"name" => "Smartcret"
			],
			"manufacturer" => [
				"@type" => "Organization",
				"name" => "Smartcret",
				"address" => [
					"@type" => "PostalAddress",
					"streetAddress" => "Pol. Ind. Mas de Tous",
					"addressLocality" => "Pobla de Vallbona",
					"postalCode" => "46185",
					"addressRegion" => "Valencia",
					"addressCountry" => "ES"
				],
				"image" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"logo" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"url" => "https://www.smartcret.com",
				"legalName" => "Grupo Negocios PO SLU",
				"telephone" => "+34674409942",
				"taxID" => "B97539076"
			],
			"category" => "https://www.google.com/search?tbm=shop&q=Home+Improvement+%3E+Paint",
			"aggregateRating" => [
				"@type" => "AggregateRating",
				"ratingValue" => "5",
				"reviewCount" => "17"
			],
			"hasVariant" => $variantes
		];
		
	}
	elseif ($producto_prod->acabado != '1111' && $producto_prod->color != '1111' && $producto_prod->formato == '1111'){
		$recoger_atributos_formato = $userClass->recoger_atributos_formato($id_producto, $id_idioma);
		$productosAgrupados = [];

		foreach ($recoger_atributos_formato as $fila) {

			$idProducto = $fila->id_producto;
			
			if (!isset($productosAgrupados[$idProducto])) {
				$productosAgrupados[$idProducto] = [
					'id_producto' => $idProducto,
					'nombre' => $fila->nombre,
					'sku' => $fila->sku,
					'ean' => $fila->ean,
					'valor_atributo_formato' => $fila->valor_atributo_formato,
					'variantes_precios' => [] 
				];
			};
			
			$productosAgrupados[$idProducto]['variantes_precios'][] = [
				'cod_pais' => $fila->cod_pais,
				'precio' => $fila->precio,
				'precio_base' => $fila->precio_base
			];
		}
		
		$variantes = [];
		foreach ($productosAgrupados as $variante) {
			$offers = [];
			foreach ($variante['variantes_precios'] as $variante_precio) {
				$offers[] = [
					"@type" => "Offer",
					"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
					"price" => $variante_precio['precio'], // Precio con descuento
					"availability" => "https://schema.org/InStock",
					"url" => "https://www.smartcret.com/$idioma_url/$url",
					"seller" => [
						"@type" => "Organization",
						"name" => "Smartcret"
					],
					"eligibleRegion" => [
						"@type" => "GeoShape",
						"addressCountry" => $variante_precio['cod_pais'] // Reino Unido
					],
					"priceSpecification" => [
						"@type" => "UnitPriceSpecification",
						"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
						"price" => $variante_precio['precio'], // Precio con descuento
						"referencePrice" => $variante_precio['precio_base'], // Precio original antes del descuento
						"eligibleQuantity" => [
							"@type" => "QuantitativeValue",
							"value" => 1,
							"unitCode" => "C62" // Código ISO para unidades
						]
					]
				];
			};

			$variantes[] = [
				"@type" => "Product",
				"url" => "https://www.smartcret.com/$idioma_url/$url",
				"name" => $variante['nombre'],
				"description" => $url_metas->description,
				"image" => "https://www.smartcret.com/$imagen_principal_producto",
				"size" => $variante['valor_atributo_formato'],
				"sku" => $variante['sku'],
				"gtin13" => $variante['ean'],
				"offers" => $offers
			];
		}
		
		// Crear el esquema principal y asignar las variantes
		$schema = [
			"@context" => "https://schema.org/",
			"@type" => "ProductGroup",
			"url" => "https://www.smartcret.com/$idioma_url/$url",
			"name" => $url_metas->title,
			"description" => $url_metas->description,
			"image" => "https://www.smartcret.com/$imagen_principal_producto",
			"brand" => [
				"@type" => "Brand",
				"name" => "Smartcret"
			],
			"manufacturer" => [
				"@type" => "Organization",
				"name" => "Smartcret",
				"address" => [
					"@type" => "PostalAddress",
					"streetAddress" => "Pol. Ind. Mas de Tous",
					"addressLocality" => "Pobla de Vallbona",
					"postalCode" => "46185",
					"addressRegion" => "Valencia",
					"addressCountry" => "ES"
				],
				"image" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"logo" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"url" => "https://www.smartcret.com",
				"legalName" => "Grupo Negocios PO SLU",
				"telephone" => "+34674409942",
				"taxID" => "B97539076"
			],
			"category" => "https://www.google.com/search?tbm=shop&q=Home+Improvement+%3E+Paint",
			"aggregateRating" => [
				"@type" => "AggregateRating",
				"ratingValue" => "5",
				"reviewCount" => "17"
			],
			"hasVariant" => $variantes
		];
		
	}
	elseif ($producto_prod->acabado != '1111' && $producto_prod->color == '1111' && $producto_prod->formato == '1111'){
		$recoger_atributos_color_formato = $userClass->recoger_atributos_color_formato($id_producto, $id_idioma);
		$productosAgrupados = [];

		foreach ($recoger_atributos_color_formato as $fila) {

			$idProducto = $fila->id_producto;
			
			if (!isset($productosAgrupados[$idProducto])) {
				$productosAgrupados[$idProducto] = [
					'id_producto' => $idProducto,
					'nombre' => $fila->nombre,
					'sku' => $fila->sku,
					'ean' => $fila->ean,
					'valor_atributo_color' => $fila->valor_atributo_color,
					'valor_atributo_formato' => $fila->valor_atributo_formato,
					'variantes_precios' => [] 
				];
			};
			
			$productosAgrupados[$idProducto]['variantes_precios'][] = [
				'cod_pais' => $fila->cod_pais,
				'precio' => $fila->precio,
				'precio_base' => $fila->precio_base
			];
		}
		
		$variantes = [];
		foreach ($productosAgrupados as $variante) {
			$offers = [];
			foreach ($variante['variantes_precios'] as $variante_precio) {
				$offers[] = [
					"@type" => "Offer",
					"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
					"price" => $variante_precio['precio'], // Precio con descuento
					"availability" => "https://schema.org/InStock",
					"url" => "https://www.smartcret.com/$idioma_url/$url",
					"seller" => [
						"@type" => "Organization",
						"name" => "Smartcret"
					],
					"eligibleRegion" => [
						"@type" => "GeoShape",
						"addressCountry" => $variante_precio['cod_pais'] // Reino Unido
					],
					"priceSpecification" => [
						"@type" => "UnitPriceSpecification",
						"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
						"price" => $variante_precio['precio'], // Precio con descuento
						"referencePrice" => $variante_precio['precio_base'], // Precio original antes del descuento
						"eligibleQuantity" => [
							"@type" => "QuantitativeValue",
							"value" => 1,
							"unitCode" => "C62" // Código ISO para unidades
						]
					]
				];
			};

			$variantes[] = [
				"@type" => "Product",
				"url" => "https://www.smartcret.com/$idioma_url/$url",
				"name" => $variante['nombre'],
				"description" => $url_metas->description,
				"image" => "https://www.smartcret.com/$imagen_principal_producto",
				"color" => $variante['valor_atributo_color'],
				"sku" => $variante['sku'],
				"gtin13" => $variante['ean'],
				"size" => $variante['valor_atributo_formato'],
				"offers" => $offers
			];
		}
		
		// Crear el esquema principal y asignar las variantes
		$schema = [
			"@context" => "https://schema.org/",
			"@type" => "ProductGroup",
			"url" => "https://www.smartcret.com/$idioma_url/$url",
			"name" => $url_metas->title,
			"description" => $url_metas->description,
			"image" => "https://www.smartcret.com/$imagen_principal_producto",
			"brand" => [
				"@type" => "Brand",
				"name" => "Smartcret"
			],
			"manufacturer" => [
				"@type" => "Organization",
				"name" => "Smartcret",
				"address" => [
					"@type" => "PostalAddress",
					"streetAddress" => "Pol. Ind. Mas de Tous",
					"addressLocality" => "Pobla de Vallbona",
					"postalCode" => "46185",
					"addressRegion" => "Valencia",
					"addressCountry" => "ES"
				],
				"image" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"logo" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"url" => "https://www.smartcret.com",
				"legalName" => "Grupo Negocios PO SLU",
				"telephone" => "+34674409942",
				"taxID" => "B97539076"
			],
			"category" => "https://www.google.com/search?tbm=shop&q=Home+Improvement+%3E+Paint",
			"aggregateRating" => [
				"@type" => "AggregateRating",
				"ratingValue" => "5",
				"reviewCount" => "17"
			],
			"hasVariant" => $variantes
		];
		
	}
	elseif ($producto_prod->acabado == '1111' && $producto_prod->color != '1111' && $producto_prod->formato == '1111'){
		$recoger_atributos_formato_acabado = $userClass->recoger_atributos_formato_acabado($id_producto, $id_idioma);
		$productosAgrupados = [];

		foreach ($recoger_atributos_formato_acabado as $fila) {

			$idProducto = $fila->id_producto;
			
			if (!isset($productosAgrupados[$idProducto])) {
				$productosAgrupados[$idProducto] = [
					'id_producto' => $idProducto,
					'nombre' => $fila->nombre,
					'sku' => $fila->sku,
					'ean' => $fila->ean,
					'valor_atributo_formato' => $fila->valor_atributo_formato,
					'valor_atributo_acabado' => $fila->valor_atributo_acabado,
					'variantes_precios' => [] 
				];
			};
			
			$productosAgrupados[$idProducto]['variantes_precios'][] = [
				'cod_pais' => $fila->cod_pais,
				'precio' => $fila->precio,
				'precio_base' => $fila->precio_base
			];
		}
		
		$variantes = [];
		foreach ($productosAgrupados as $variante) {
			$offers = [];
			foreach ($variante['variantes_precios'] as $variante_precio) {
				$offers[] = [
					"@type" => "Offer",
					"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
					"price" => $variante_precio['precio'], // Precio con descuento
					"availability" => "https://schema.org/InStock",
					"url" => "https://www.smartcret.com/$idioma_url/$url",
					"seller" => [
						"@type" => "Organization",
						"name" => "Smartcret"
					],
					"eligibleRegion" => [
						"@type" => "GeoShape",
						"addressCountry" => $variante_precio['cod_pais'] // Reino Unido
					],
					"priceSpecification" => [
						"@type" => "UnitPriceSpecification",
						"priceCurrency" => ($variante_precio['cod_pais'] === 'US') ? 'USD' : 'EUR',
						"price" => $variante_precio['precio'], // Precio con descuento
						"referencePrice" => $variante_precio['precio_base'], // Precio original antes del descuento
						"eligibleQuantity" => [
							"@type" => "QuantitativeValue",
							"value" => 1,
							"unitCode" => "C62" // Código ISO para unidades
						]
					]
				];
			};

			$variantes[] = [
				"@type" => "Product",
				"url" => "https://www.smartcret.com/$idioma_url/$url",
				"name" => $variante['nombre'],
				"description" => $url_metas->description,
				"image" => "https://www.smartcret.com/$imagen_principal_producto",
				"sku" => $variante['sku'],
				"gtin13" => $variante['ean'],
				"size" => $variante['valor_atributo_formato'],
				"additionalProperty" => [
					"@type" => "PropertyValue",
					"name" => "Acabado",
					"value" => $variante['valor_atributo_acabado']
				],
				"offers" => $offers
			];
		}
		
		// Crear el esquema principal y asignar las variantes
		$schema = [
			"@context" => "https://schema.org/",
			"@type" => "ProductGroup",
			"url" => "https://www.smartcret.com/$idioma_url/$url",
			"name" => $url_metas->title,
			"description" => $url_metas->description,
			"image" => "https://www.smartcret.com/$imagen_principal_producto",
			"brand" => [
				"@type" => "Brand",
				"name" => "Smartcret"
			],
			"manufacturer" => [
				"@type" => "Organization",
				"name" => "Smartcret",
				"address" => [
					"@type" => "PostalAddress",
					"streetAddress" => "Pol. Ind. Mas de Tous",
					"addressLocality" => "Pobla de Vallbona",
					"postalCode" => "46185",
					"addressRegion" => "Valencia",
					"addressCountry" => "ES"
				],
				"image" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"logo" => "https://www.smartcret.com/assets/img/logo-smartcret.png",
				"url" => "https://www.smartcret.com",
				"legalName" => "Grupo Negocios PO SLU",
				"telephone" => "+34674409942",
				"taxID" => "B97539076"
			],
			"category" => "https://www.google.com/search?tbm=shop&q=Home+Improvement+%3E+Paint",
			"aggregateRating" => [
				"@type" => "AggregateRating",
				"ratingValue" => "5",
				"reviewCount" => "17"
			],
			"hasVariant" => $variantes
		];
		
	}
	
	echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
	
?>