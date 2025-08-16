<?php
$urls_todas = $userClass->urls_todas($id_idioma);
foreach ($urls_todas as $url_link) {
    $processed_value = htmlspecialchars($url_link->valor, ENT_QUOTES, 'UTF-8');

    match($url_link->id_url) {
        2 => $link_microcemento_listo_al_uso = $processed_value,
        3 => $link_pintura_smartcover = $processed_value,
        4 => $link_hormigon_impreso = $processed_value,
        5 => $link_tienda = $processed_value,
        6 => $link_blog = $processed_value,
        8 => $link_smart_kit_sj = $processed_value,
        9 => $link_smart_kit_cj = $processed_value,
        14 => $link_smart_cover_repair = $processed_value,
        15 => $link_smart_jointer = $processed_value,
        18 => $link_smart_varnish = $processed_value,
        19 => $link_smart_varnish_repair = $processed_value,
        20 => $link_smart_cleaner = $processed_value,
        21 => $link_smart_wax = $processed_value,
        72 => $link_reforma_ban_sin_obra = $processed_value,
        82 => $link_fabricante_microcemento = $processed_value,
        83 => $link_distribuidores = $processed_value,
        84 => $link_calculadora_presupuestos = $processed_value,
        85 => $link_contacto = $processed_value,
        86 => $link_politica_privacidad = $processed_value,
        87 => $link_politica_venta_devolucion = $processed_value,
        88 => $link_reformas_diy = $processed_value,
        // 105 => $link_paso_a_paso = $processed_value,
        106 => $mini_kit_sj = $processed_value,
        110 => $link_mis_datos = $processed_value,
        111 => $link_mis_pedidos = $processed_value,
        112 => $link_kit_banos_duchas_cocinas = $processed_value,
        113 => $link_kit_paredes = $processed_value,
        54 => $link_microcementobano_sin_limites = $processed_value,
        122 => $link_microcementobano_guia_debutantes = $processed_value,
        121 => $link_microcementobano_ducha_italiana = $processed_value,
        129 => $link_checkout = $processed_value,
        120 => $link_mantenimiento_microcemento = $processed_value,
        123 => $link_colores_microcemento = $processed_value,
        133 => $link_ofertas_smartcret = $processed_value,


        default => null
    };

}
?>