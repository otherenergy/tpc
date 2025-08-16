
<?php
$itemList = [];
$position = 1;

if (!empty($blog_contenido)) { 

    foreach ($blog_contenido as $tarjeta) { 
        $relative_image_path = $tarjeta->image;
        $cleaned_image_path = str_replace('../../', '', $relative_image_path);
        $image_url = 'https://www.smartcret.com/' . $cleaned_image_path;
        $title = $tarjeta->h1;
        $description = $tarjeta->description;
        $fecha = $tarjeta->fecha;
        $url = 'https://www.smartcret.com/' . $idioma_url . '/blog/' . $tarjeta->valor; 
        
        $itemList[] = [
            "@type" => "ListItem",
            "position" => $position++,
            "url" => $url,
            "item" => [
                "@type" => "BlogPosting",
                "headline" => $title,
                "description" => $description,
                "datePublished" => $fecha,
                "image" => [
                    "@type" => "ImageObject",
                    "url" => $image_url
                ],
                "author" => [
                    "@type" => "Organization",
                    "name" => "Smartcret"
                ]
            ]
        ];
    };

    $schema = [
        "@context" => "https://schema.org/",
        "@type" => "Blog",
        "name" => $url_metas->title,
        "url" => "https://www.smartcret.com/$idioma_url/blog",
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
            "itemListElement" => $itemList  
        ]
    ];
    
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';

}

?>