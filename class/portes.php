<?php

#[AllowDynamicProperties]
class Portes {

public function __construct() {
        // Crear una instancia de Clase2
        $this->checkout = new checkout();
    }

function executeQuery($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    // return $stmt->fetchAll();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function executeSelect($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll();
}

public function executeSelectArray($sql, $args = []) {
    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function executeSelectObj($sql, $args = []) {

    $pdo = getDB();

    $stmt = $pdo->prepare($sql);
    $stmt->execute($args);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

public function obten_portes_producto_pais( $id, $pais ) {

    $id_variante = $this->es_variante( $id );

    if ( $id_variante == 0 ) {
        $idx = $id;
        $arguments = [$id];
    }else {
        $idx = $id_variante;
        $arguments = [$id_variante];
    }

    $sql = "SELECT " . strtolower( $pais ) . " FROM transporte_smartcret WHERE id_producto =$idx";
    $importe = $this->executeSelectArray( $sql );

    return $importe[0][strtolower( $pais )];

}

public function obten_portes_zonas_especiales( $cd, $peso ) {

    $tramo_peso = $this->obtener_tramo_peso_especial ( $peso );
    $sql = "SELECT $tramo_peso as tramo, tasas, aduana FROM transporte_zonas_especiales WHERE cp = '$cd'";

   return $this->executeSelectObj( $sql )[0];

}

public function es_variante( $id ) {

    $sql = "SELECT es_variante FROM productos WHERE id =?";
    // echo "SELECT es_variante FROM productos WHERE id =$id";
    $arguments = [$id];
    return $this->executeSelectObj($sql, $arguments)[0]->es_variante;

}


public function calcula_portes( $dir_envio, $peso ) {

    $min_importe = 100;
    $importe_portes = 0;

    $reg = $this->checkout->obten_dir_envio($dir_envio)[0];

    $peso = $this->checkout->calcula_peso_pedido();
    $cod_postal = $reg['cp'];
    $pais = $reg['pais'];

    $carrito = new Carrito();
    $carro = $carrito->get_content();

    if ( $pais !='ES' ) {

        if ( $carrito->precio_total() > $min_importe )  {
            return 0;
        }else {

            if( $carrito->articulos_total() > 0 ) {

                foreach($carro as $producto) {
                    $importe_portes += ($this->obten_portes_producto_pais( $producto['id'], $pais) * $producto['cantidad']);
                }

                $importe_portes_total = $importe_portes * 1.21;

            }

        }


    }else {

        if ( substr( $reg['provincia'], 0, 2 ) != '35' && substr( $reg['provincia'], 0, 2 ) != '38' && substr( $reg['provincia'], 0, 2 ) != '51' && substr( $reg['provincia'], 0, 2 ) != '52' && substr( $reg['provincia'], 0, 2 ) != '07' ) {
                if ( $carrito->precio_total() > $min_importe )  {

                return 0;

            }else {

                if( $carrito->articulos_total() > 0 ) {
                    foreach($carro as $producto) {
                        $importe_portes += ($this->obten_portes_producto_pais( $producto['id'], $pais) * $producto['cantidad']);
                    }
                    $importe_portes_total = $importe_portes * 1.21;
                }

            }


        }else {

            $cod_postal_3 =  substr( $cod_postal, 0, 3);
            $cod_postal_4 =  substr( $cod_postal, 0, 4);
            $cod_postal = substr( $cod_postal, 0, 2);

            if ( $cod_postal == 35 || $cod_postal == 38 ) {

                $reg = $this->obten_portes_zonas_especiales( $cod_postal_3, $peso);
                $impuestos =  $this->formatea_importe( $carrito->precio_total() * ( $reg->tasas / 100  ) );

                return $this->formatea_importe( ( $reg->tramo + $reg->aduana + $impuestos) * 1.21 );

            }

            if ( $cod_postal == 51 || $cod_postal == 52 ) {

                return 85.90;

            }

            if ( $cod_postal == 07 ) {

                if ( $cod_postal_4 == '0780' ) $zona = "IBIZA";
                elseif ( $cod_postal_4 == '0786' ) $zona = "FORMENTERA";
                elseif ( $cod_postal_3 == '070' || $cod_postal_3 == '076' || $cod_postal_3 == '073' ) $zona = "MALLORCA";
                elseif ( $cod_postal_3 == '077' ) $zona = "MENORCA";
                elseif ( $cod_postal_3 == '071' ) $zona = "SOLLER";
                elseif ( $cod_postal_3 == '074' ) $zona = "ALCUDIA";
                else $zona = "MALLORCA";

                $reg = $this->obten_portes_zonas_especiales( $zona, $peso);
                $impuestos =  $this->formatea_importe( $carrito->precio_total() * ( $reg->tasas / 100  ) );

                return $this->formatea_importe( ( $reg->tramo + $reg->aduana + $impuestos) * 1.21 );



            }

        }

    }



    return $importe_portes_total;
}

function formatea_importe($precio){
    $precio_formateado= number_format($precio, 2, ".", "");
    return $precio_formateado;
}


public function obtener_tramo_peso_especial( $peso ) {

    $tramos_peso = array(
        '10' => 'kg_10',
        '25' => 'kg_25',
        '50' => 'kg_50',
        '75' => 'kg_75',
        '100' => 'kg_100',
        '120' => 'kg_125',
        '150' => 'kg_150',
        '170' => 'kg_175',
        '200' => 'kg_200',
        '220' => 'kg_225',
        '250' => 'kg_250',
        '270' => 'kg_275',
        '300' => 'kg_300',
        '310' => 'kg_400',
        '320' => 'kg_500',
        '330' => 'kg_600',
        '340' => 'kg_700',
        '350' => 'kg_800',
        '360' => 'kg_900',
        '370' => 'kg_1000',
    );

    if ($peso > 500) {
        return 'kg_500';
    } else {
        foreach ($tramos_peso as $key => $value) {
            if ($peso <= $key) {
                return $value;
            }
        }
    }

    return '';
}

}

?>