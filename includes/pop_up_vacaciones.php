<?php

if( !function_exists('popup_vacaciones_activo') ) {

    global $mysqli;
    $mysqli=$conn;

    function popup_vacaciones_activo () {
        global $mysqli;
        $sql = "SELECT activado FROM configuracion WHERE id=4";
        return ( $mysqli->query($sql)->fetch_object()->activado == 1) ? true : false;
    }

}


if ( popup_vacaciones_activo () ) {

    $conn = new mysqli(SERVER, USER, PASS, DB);
    $conn->set_charset("utf8");

    $sql = "SELECT * FROM configuracion WHERE id=4";
    $conn->set_charset( "utf8mb4" );
    $res=$conn->query($sql);
    $reg=$res->fetch_object();

    //comprobamos si estamos dentro del periodo de tiempo
    $fecha_inicio = strtotime( $reg->inicio );
    $fecha_fin = strtotime( $reg->fin );
    $fecha = strtotime(date("d-m-Y H:i:00",time()));

    if ( ( $fecha >= $fecha_inicio ) && ( $fecha <= $fecha_fin ) ) {

        /* Mensaje en parte superior*/

        if ( 1 )  {

            $url = $_SERVER['REQUEST_URI'];

            if ( strpos( $url, '/en' ) !== false ) {
                $msg = "IMPORTANT: Smartlover, your order may experience a slight delay in delivery due to high demand during Christmas. Merry Christmas! 🎄🎁";
            } elseif ( strpos( $url, '/en-us' ) !== false ) {
                $msg = "IMPORTANT: Smartlover, your order may experience a slight delay in delivery due to high demand during Christmas. Merry Christmas! 🎄🎁";
            } elseif ( strpos( $url, '/fr' ) !== false ) {
                $msg = "IMPORTANT : Smartlover, votre commande pourrait connaître un léger retard de livraison en raison de la forte demande pendant Noël. Joyeux Noël ! 🎄🎁";
            } elseif ( strpos( $url, '/it' ) !== false ) {
                $msg = "IMPORTANTE: Smartlover, il tuo ordine potrebbe subire un leggero ritardo nella consegna a causa dell'alta domanda durante il Natale. Buon Natale! 🎄🎁";
            } elseif ( strpos( $url, '/de' ) !== false ) {
                $msg = "WICHTIG: Smartlover, deine Bestellung könnte aufgrund der hohen Nachfrage zu Weihnachten leichte Verzögerungen bei der Lieferung erfahren. Frohe Weihnachten! 🎄🎁";
            } else {
                $msg = "IMPORTANTE: Smartlover, tu pedido podría experimentar un ligero retraso en la entrega debido a la alta demanda en Navidad. ¡Feliz Navidad! 🎄🎁";
            }

            echo '
            <style>
            .anuncio {
                text-align: center;
                position: fixed;
                display: block;
                top: 0;
                right: 0;
                left: 0;
                width: 100%;
                padding: 8px;
                background-color: #92bf23;
                color: #fff;
                z-index: 99999999999999999999999999999999999;
                height: 40px;
                font-size: 16px;
                font-weight: 500;
            }
            header {
                margin-top: 36px;
            }
            div#lista-productos,
            div#menu-usuario {
                top: 36px;
            }
            .container.listado {
                margin-top: 17vh!important;
            }
            @media only screen and (max-device-width:769px) {

            .anuncio {
                height: 70px;
                font-size: 15px;
                line-height: 20px;
            }
            header {
                margin-top: 70px;
            }
            div#lista-productos,
            div#menu-usuario {
                top: 70px;
            }
            ul#icons {
                top: 89px;
            }

            }
            </style>
            ';

            echo '<div class="anuncio">' . $msg . '</div>';
        }


        // mostramos el pop up si no se ha mostrado ya
        if ( !isset( $_SESSION['popup_vacaciones_mostrado'] ) ) {


            $lang = obten_idioma_actual();

            $_SESSION['popup_vacaciones_mostrado'] = 1;

            $texto = array();
            $urls_activo = array();

            /* Ingles */
            $texto['titulo']['en'] = 'IMPORTANT!';
            $texto['titulo2']['en'] = 'Smartlover, your order may experience a slight delay in delivery due to high demand during Christmas.';
            $texto['boton']['en'] = 'Accept';
            $texto['texto']['en'] = 'You\'ll be getting your hands on it very soon. We appreciate your understanding and trust in Smartcret.<br><br><b>Merry Christmas! 🎄🎁';

            /* Ingles US */
            $texto['titulo']['en-us'] = 'IMPORTANT!';
            $texto['titulo2']['en-us'] = 'Smartlover, your order may experience a slight delay in delivery due to high demand during Christmas.';
            $texto['boton']['en-us'] = 'Accept';
            $texto['texto']['en-us'] = 'You\'ll be getting your hands on it very soon. We appreciate your understanding and trust in Smartcret.<br><br><b>Merry Christmas! 🎄🎁';


            /* español */
            $texto['titulo']['es'] = '¡IMPORTANTE!';
            $texto['titulo2']['es'] = 'Smartlover, tu pedido podría experimentar un ligero retraso en la entrega debido a la alta demanda en Navidad.';
            $texto['boton']['es'] = 'Aceptar';
            $texto['texto']['es'] = 'Muy pronto te pondrás manos a la no obra. Agradecemos tu comprensión y confianza en Smartcret.<br><br><b>¡Feliz Navidad! 🎄🎁</b>';


            /* francés */
            $texto['titulo']['fr'] = 'IMPORTANT!';
            $texto['titulo2']['fr'] = 'Smartlover, votre commande pourrait connaître un léger retard de livraison en raison de la forte demande pendant Noël.';
            $texto['boton']['fr'] = 'Accepter';
            $texto['texto']['fr'] = 'Vous la recevrez très bientôt. Nous apprécions votre compréhension et votre confiance en Smartcret.<br><br><b>Joyeux Noël ! 🎄🎁';


            /* italiano */
            $texto['titulo']['it'] = 'IMPORTANTE';
            $texto['titulo2']['it'] = 'Smartlover, il tuo ordine potrebbe subire un leggero ritardo nella consegna a causa dell\'alta domanda durante il Natale.';
            $texto['boton']['it'] = 'Accettare';
            $texto['texto']['it'] = 'Lo riceverai molto presto. Apprezziamo la tua comprensione e la fiducia in Smartcret.<br><br><b>Buon Natale! 🎄🎁';


            /* aleman */
            $texto['titulo']['it'] = 'WICHTIG';
            $texto['titulo2']['it'] = 'Smartlover, deine Bestellung könnte aufgrund der hohen Nachfrage zu Weihnachten leichte Verzögerungen bei der Lieferung erfahren.';
            $texto['boton']['it'] = 'Accettare';
            $texto['texto']['it'] = 'Du wirst sie jedoch sehr bald erhalten. Wir danken für dein Verständnis und dein Vertrauen in Smartcret.<br><br><b>Frohe Weihnachten! 🎄🎁';
            ?>

            <div class="modal fade gen" id="pop_up">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                            <p class="modal-tit"><?php echo $texto['titulo'][$lang];  ?></p>
                            <div class="sep20"></div>
                            <p class="modal-subtit center"><?php echo $texto['titulo2'][$lang];  ?></p>
                            <div class="sep05"></div>
                            <p class="modal-subtit center"><?php echo $texto['texto'][$lang] ?></p>
                            <div class="sep20"></div>
                            <p class="center"><button type="button" id="suscribe_news" class="cart_btn" data-bs-dismiss="modal"><?php echo $texto['boton'][$lang] ?><i class="fa fa-check"></i></button></p>
                        </div>
                    </div>
                </div>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <script>

                var myModal = new bootstrap.Modal(document.getElementById('pop_up'), {
                    keyboard: false
                })
                myModal.show();

            </script>

            <?php

                echo '
                <style>
                    .modal#pop_up .modal-dialog {
                        max-width: 630px;
                        width: 630px!important;
                        position: absolute;
                        top: 40%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                    }
                    button#suscribe_news {
                        padding: 10px 20px;
                    }
                    p.modal-tit {
                        text-align: center;
                        border-bottom: none;
                        font-size: 22px;
                        margin-bottom: 0;
                    }
                    button#suscribe_news i {
                        margin-left: 14px;
                        margin-right: 0;
                    }
                    button:focus {
                        outline: none;
                        border-radius: 0;
                    }
                    form#newsletter .row {
                        background-color: #fff;
                    }
                    #pop_up .modal-dialog {
                        border: 5px solid #92bf23;
                    }
                    #pop_up .modal-content {
                        border: none;
                        border-radius: 0;
                    }
                    p.modal-subtit.center {
                        margin-top: 0px;
                    }
                    p {
                        line-height: 22px;
                    }
                    button#cerrar {
                        background-color: #672a2a;
                        font-size: 12px;
                        padding: 4px 10px;
                        border: 1px solid #672a2a;
                    }
                    button#cerrar:hover {
                        background-color: transparent;
                        color: #672a2a;

                    }
                    button#cerrar i {
                        margin: 0 0 0 5px;
                    }


                    @media only screen and (max-device-width:769px) {

                        .modal#pop_up .modal-dialog {
                            max-width: 400px;
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                        }
                    }
                </style>
                ';
        }

    }

}

// unset( $_SESSION['popup_vacaciones_mostrado'] );



?>
