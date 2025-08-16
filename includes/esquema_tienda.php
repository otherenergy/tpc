<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('html_errors', 1);

$lista_salidos = [];

$catalogo_productos = [];
$position = 1;

if ($isGoogleBot == 1){
  if (!isset($_SESSION['user_ubicacion'])){
    $_SESSION['user_ubicacion'] = 'US';
  }
};

$userClass = new userClass();
$menu_productos = $userClass->menu_productos($id_idioma);

foreach ($menu_productos as $categoria) {
  if (!in_array($categoria->valor_pc, $lista_salidos)) {
    $lista_salidos[] = $categoria->valor_pc;
    $id_categoria =  htmlspecialchars($categoria->id_categoria, ENT_QUOTES, 'UTF-8');
    if ($id_categoria == 10 || $id_categoria == 11 || $id_categoria == 12) {
      continue;
    };

    $contador = 0;
    foreach ($menu_productos as $producto) {
      $id_categoria_producto =  htmlspecialchars($producto->id_categoria, ENT_QUOTES, 'UTF-8');
      if ($id_categoria == $id_categoria_producto) {
        $img_product = htmlspecialchars($producto->miniatura, ENT_QUOTES, 'UTF-8');
        $id_producto = htmlspecialchars($producto->id, ENT_QUOTES, 'UTF-8');
        $variante = htmlspecialchars($producto->variante, ENT_QUOTES, 'UTF-8');

        $precio_base = number_format($producto->precio_base, 2, ".", "");
        $descuento = htmlspecialchars($producto->descuento, ENT_QUOTES, 'UTF-8');
        $precio_descuento = number_format(($precio_base) * (100 - $descuento) / 100, 2, ".", "");

        $nombre_producto = htmlspecialchars($producto->nombre, ENT_QUOTES, 'UTF-8');
        $url_producto = htmlspecialchars($producto->valor, ENT_QUOTES, 'UTF-8');

        $catalogo_productos[] = [
          "@type" => "ListItem",
          "position" => $position++,
          "url" => "https://www.smartcret.com/$idioma_url/$url_producto",
          "item" => [
            "@type" => "Product",
            "name" => $nombre_producto,
            "image" => "https://www.smartcret.com/assets/img/productos/$img_product",
            "aggregateRating" => [
              "@type" => "AggregateRating",
              "ratingValue" => "5",
              "reviewCount" => "17"
            ]
          ]
        ];
      };
    };
  };
};

$schema = [
    "@context" => "https://schema.org/",
    "@type" => "CollectionPage",
    "name" => "Tienda Smartcret", 
    "url" => "https://www.smartcret.com/$idioma_url/$url",  
    "description" => $url_metas->description,  
    "publisher" => [
        "@type" => "Organization",
        "name" => "Smartcret",
        "logo" => [
            "@type" => "ImageObject",
            "url" => "https://www.smartcret.com/assets/img/logo-smartcret.png"
        ],
        "url" => "https://www.smartcret.com"
    ],
    "mainEntity" => [
        "@type" => "ItemList",
        "itemListElement" => $catalogo_productos 
    ]
];

echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
?>