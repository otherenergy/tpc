<?php

if( !function_exists('popup_newsletter_activo') ) {

    global $mysqli;
    $mysqli=$conn;

    function popup_newsletter_activo () {
        global $mysqli;
        $sql = "SELECT activado FROM configuracion WHERE id=3";
        return ( $mysqli->query($sql)->fetch_object()->activado == 1) ? true : false;
    }

}



if ( popup_newsletter_activo () ) {

$lang = obten_idioma_actual();
if ( $lang = 'en-us') {
    $lang='en';
}

$urls_activo['es'] = array (
                        '',
                        '/',
                        '/blog',
                        '/microcemento-listo-al-uso',
                        '/pintura-azulejos-smartcover',
                        '/hormigon-impreso',
                        '/smartcret/',
                        '/smartcret/blog/',
                        '/smartcret/microcemento-listo-al-uso',
                        '/smartcret/pintura-azulejos-smartcover',
                        '/smartcret/hormigon-impreso',
                     );
$urls_activo['en'] = array (
                        '',
                        '/en',
                        '/en/',
                        '/en/blog',
                        '/en/smartcret-ready-to-use-microcement',
                        '/en/tile-paint-smartcover',
                        '/smartcret/en',
                        '/smartcret/en/',
                        '/smartcret/en/blog/',
                        '/smartcret/en/smartcret-ready-to-use-microcement',
                        '/smartcret/en/tile-paint-smartcover',
                     );
$urls_activo['fr'] = array (
                        '',
                        '/fr',
                        '/fr/',
                        '/fr/blog',
                        '/fr/beton-cire-pret-a-lemploi',
                        '/fr/peinture-carrelages-smartcover',
                        '/smartcret/fr',
                        '/smartcret/fr/',
                        '/smartcret/fr/blog/',
                        '/smartcret/fr/beton-cire-pret-a-lemploi',
                        '/smartcret/fr/peinture-carrelages-smartcover',
                     );
$urls_activo['it'] = array (
                        '',
                        '/it',
                        '/it/',
                        '/it/blog',
                        '/it/microcemento-pronto-all-uso-smartcret',
                        '/it/vernice-piastrella-smartcover',
                        '/smartcret/it',
                        '/smartcret/it/',
                        '/smartcret/it/blog/',
                        '/smartcret/it/microcemento-pronto-all-uso-smartcret',
                        '/smartcret/it/vernice-piastrella-smartcover',
                     );
$urls_activo['de'] = array (
                        '',
                        '/de/',
                        '/de',
                        '/de/blog',
                        '/de/gebrauchsfertiger-mikrozement',
                        '/de/farbe-fliesen-smartcover',
                        '/de/vertreiber',
                        '/smartcret/de',
                        '/smartcret/de/',
                        '/smartcret/de/blog',
                        '/smartcret/de/gebrauchsfertiger-mikrozement',
                        '/smartcret/de/farbe-fliesen-smartcover',
                        '/smartcret/de/vertreiber'
                     );

if ( ( in_array( $_SERVER['REQUEST_URI'], $urls_activo[ $lang ] ) ||
       strpos( $_SERVER['REQUEST_URI'], '/smartcret/blog/') !== false || strpos( $_SERVER['REQUEST_URI'], '/blog/' ) !== false ) && !isset( $_SESSION['popup_mostrado'] ) ) {

$path = ( strpos( $_SERVER['REQUEST_URI'], '/blog' ) !== false) ? '..' : '';

$_SESSION['popup_mostrado'] = 1;

$texto = array();
$urls_activo = array();

/* Ingles */
$texto['titulo']['en'] = 'JOIN THE GREEN WAVE!';
$texto['subtitulo']['en'] = 'Subscribe to our newsletter and find out everything.';
$texto['texto']['en'] = 'Trends, tips, advice, tips, new products and many surprises. You\'re one click away from receiving the first 🎁 in your inbox';
$texto['nombre']['en'] = 'Name';
$texto['email']['en'] = 'Email';
$texto['boton']['en'] = 'I\'M IN';

$texto['respuesta_ok']['en'] = "Thank you for subscribing to our newsletter! Your gift is waiting for you in your inbox.";
$texto['respuesta_ko']['en'] = "Error, it was not possible to complete the action";
$texto['respuesta_ya_esta']['en'] = " are already subscribed to our newsletter";
$texto['respuesta_incompleto']['en'] = "Please enter your name, birth date and a correct email address";
$texto['cumple']['en'] = 'Birth date';
$texto['cerrar']['en'] = 'Close';


/* español */
$texto['titulo']['es'] = '¡ÚNETE A LA MAREA VERDE!';
$texto['subtitulo']['es'] = 'Suscríbete a nuestra newsletter y entérate de todo.';
$texto['texto']['es'] = 'Tendencias, consejos, tips, nuevos productos y muchas sorpresas. Estás a un clic de recibir el primer 🎁 en tu bandeja de entrada.';
$texto['nombre']['es'] = 'Nombre';
$texto['email']['es'] = 'Email';
$texto['boton']['es'] = 'ME APUNTO';
$texto['cumple']['es'] = 'Fecha nacimiento';

$texto['respuesta_ok']['es'] = "¡Gracias por suscribirte a nuestra newsletter! Tu regalo te espera en la bandeja de entrada.";
$texto['respuesta_ko']['es'] ="'Error, no ha sido posible realizar la acción";
$texto['respuesta_ya_esta']['es'] = " ya se encuentra suscrito a nuestra newsletter";
$texto['respuesta_incompleto']['es'] = "Es necesario indicar nombre, fecha de nacimiento y email correcto";
$texto['cerrar']['es'] = "Cerrar";


/* francés */
$texto['titulo']['fr'] = 'REJOIGNEZ LA MARÉE VERTE !';
$texto['subtitulo']['fr'] = 'Abonnez-vous à notre lettre d\'information et découvrez tout.';
$texto['texto']['fr'] = 'Des tendances, des conseils, des astuces, des nouveautés et de nombreuses surprises. Vous n\'êtes qu\'à un clic de recevoir le premier 🎁 dans votre boîte de réception.';
$texto['nombre']['fr'] = 'Nom';
$texto['email']['fr'] = 'Courriel';
$texto['boton']['fr'] = 'JE SUIS DANS';

$texto['respuesta_ok']['fr'] = "Merci de vous être abonné à notre lettre d\'information! Votre cadeau vous attend dans votre boîte de réception.";
$texto['respuesta_ko']['fr'] = "Erreur, il n\'a pas été possible de compléter l\'action";
$texto['respuesta_ya_esta']['fr'] = "  vous êtes déjà inscrit à notre newsletter";
$texto['respuesta_incompleto']['fr'] = "Veuillez saisir votre nom, la date de naissance et une adresse e-mail valide";
$texto['cumple']['fr'] = 'Date de naissance';
$texto['cerrar']['fr'] = 'Fermer';


/* italiano */
$texto['titulo']['it'] = 'UNISCITI ALLA MAREA VERDE!';
$texto['subtitulo']['it'] = 'Iscrivetevi alla nostra newsletter e scoprite tutto.';
$texto['texto']['it'] = 'Tendenze, suggerimenti, consigli, suggerimenti, nuovi prodotti e tante sorprese. Basta un clic per ricevere la prima 🎁 nella tua casella di posta.';
$texto['nombre']['it'] = 'Nome';
$texto['email']['it'] = 'Email';
$texto['boton']['it'] = 'SONO IN';

$texto['respuesta_ok']['it'] = "Grazie per esservi iscritti alla nostra newsletter! Il tuo regalo ti aspetta nella tua casella di posta.";
$texto['respuesta_ko']['it'] = "Errore, non è stato possibile completare l\'azione";
$texto['respuesta_ya_esta']['it'] = " sei già iscritto alla nostra newsletter";
$texto['respuesta_incompleto']['it'] = "È necessario indicare nome, data di nascita e indirizzo e-mail corretto";
$texto['cumple']['it'] = 'Data di nascita';
$texto['cerrar']['it'] = 'Chiudere';


/* aleman */
$texto['titulo']['de'] = 'TRETEN SIE DER GRÜNEN WELLE BEI!';
$texto['subtitulo']['de'] = 'Abonnieren Sie unseren Newsletter und bleiben Sie auf dem Laufenden.';
$texto['texto']['de'] = 'Trends, Tipps, Tricks, neue Produkte und viele Überraschungen. Sie sind nur einen Klick entfernt, das erste 🎁 in Ihrem Posteingang zu erhalten.';
$texto['nombre']['de'] = 'Name';
$texto['email']['de'] = 'Email';
$texto['boton']['de'] = 'ICH MELDE MICH AN';
$texto['cumple']['de'] = 'Geburtsdatum';

$texto['respuesta_ok']['de'] = "Danke für das Abonnieren unseres Newsletters! Ihr Geschenk wartet in Ihrem Posteingang auf Sie.";
$texto['respuesta_ko']['de'] ="'Fehler, die Aktion konnte nicht durchgeführt werden";
$texto['respuesta_ya_esta']['de'] = " ist bereits für unseren Newsletter abonniert";
$texto['respuesta_incompleto']['de'] = "Es ist notwendig, Namen, Geburtsdatum und korrekte E-Mail anzugeben.";
$texto['cerrar']['de'] = "Schließen";



?>

<div class="modal fade gen" id="pop_up">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                <p class="modal-tit"><?php echo $texto['titulo'][$lang];  ?></p>
                <p class="modal-subtit center"><?php echo $texto['subtitulo'][$lang];  ?></p>
                <div class="form-modal">
                    <form id="newsletter" method='POST'>
                        <div class="row">
                            <div class="sep10"></div>
                            <div class="form-group col-md-12">
                                <p><?php echo $texto['texto'][$lang] ?></p>
                            </div>
                            <div class="sep10"></div>
                            <div class="form-group col-md-6">
                                <label for="form_empresa"><?php echo $texto['nombre'][$lang] ?></label>
                                <input type="text" class="form-control" id="input_nombre" name="input_nombre" value="" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="form_empresa"><?php echo $texto['cumple'][$lang] ?></label>
                                <input type="date" class="form-control" id="input_cumple" name="input_cumple" value="">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="form_empresa"><?php echo $texto['email'][$lang] = 'Email' ?></label>
                                <input type="text" class="form-control" id="input_email" name="input_email" value="" >
                            </div>
                            <div class="sep10"></div>
                            <div class="btns center">
                                <input type="hidden" name="input_ip" value="<?php echo obten_ip() ?>">
                                <input type="hidden" name="input_idioma" value="<?php echo $lang ?>">
                                <input type="hidden" name="accion" value="suscribe_news">
                                <input type="hidden" name="suscribe_news" value="1">
                                <button type="button" id="suscribe_news" class="cart_btn"><?php echo $texto['boton'][$lang] ?><i class="fa fa-check"></i></button>
                                <button type="button" id="cerrar" class="cart_btn" data-bs-dismiss="modal"><?php echo $texto['cerrar'][$lang] ?><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>

    function compruebaForm() {

        if( $('#input_nombre').val()=='' ) {
            muestraMensajeLn(`<?php  echo $texto['respuesta_incompleto'][$lang] ?>`);
            return false;
        }
        if( $('#input_cumple').val()=='' ) {
            muestraMensajeLn(`<?php  echo $texto['respuesta_incompleto'][$lang] ?>`);
            return false;
        }else if( !validaEmail( $('#input_email').val() ) ) {
            muestraMensajeLn(`<?php  echo $texto['respuesta_incompleto'][$lang] ?>`);
            return false;
        }else {
            return true;
        }

    }

    function esLocalhost() {
        var url = window.location.href;
        var idx = url.indexOf("localhost");
        return (idx != -1) ? true : false;
    }

    var myModal = new bootstrap.Modal(document.getElementById('pop_up'), {
        keyboard: false
    })
    myModal.show();

    $('#suscribe_news').click(function(e) {

        if ( compruebaForm() ) {

            setTimeout(function() {
                myModal.hide();
            },1000);

            var url = ( esLocalhost() ) ? '/smartcret/class/control.php' : '/class/control.php';
            // var url = ( esLocalhost() ) ? '<?php echo $path ?>/smartcret/class/control.php' : '<?php echo $path ?>/class/control.php';

            $.ajax({
                url: url,
                type: 'post',
                dataType: 'text',
                data: $('#newsletter').serialize()
            })
            .done(function(result) {
                var result = $.parseJSON(result);
                if(result.res==0) {
                    muestraMensajeMl("<?php  echo $texto['respuesta_ko'][$lang] ?>");
                }else if (result.res==1) {
                    muestraMensajeMl("<?php  echo $texto['respuesta_ok'][$lang] ?>");
                }else if (result.res==2) {
                    muestraMensajeMl(result.msg + "<?php echo $texto['respuesta_ya_esta'][$lang] ?>");
                }else if (result.res==3) {
                    muestraMensajeMl(result.msg + "<?php echo $texto['respuesta_incompleto'][$lang] ?>");
                }
            })
            .fail(function() {
                alert("error");
            });
        }
    });



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
            button#cerrar {
                margin-top: 17px;
            }
        }
    </style>
    ';
}

// unset( $_SESSION['popup_mostrado'] );

}

?>
