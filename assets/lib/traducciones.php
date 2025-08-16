<?php

if ( !isset( $lg ) ) {
	$lg = obten_idioma_actual();
}

function trad( $txt ) {

global $lg;
$lang = array();

/*INGLES*/
$lang['en']['Pagado']='Paid';
$lang['en']['Pendiente']='Pending';
$lang['en']['Entregado'] = 'Delivered';
$lang['en']['Enviado'] = 'Shipped';
$lang['en']['Descuento aplicado:']='Discount applied:';
$lang['en']['Sólo Kits microcemento:']='Microcement kits only:';
$lang['en']['Eliminar descuento']='Remove discount coupon';
$lang['en']['El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío']='The discount is applied to the subtotal of the items before adding shipping costs';
$lang['en']["Actualmente no podemos realizar pedidos a las Islas Canarias a través de la web. Por favor ponte en contacto con nosotros y te atenderemos personalmente con tu pedido:\n\n  Telefono/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/contacto"] = "Currently we can not place orders to the Canary Islands through the web. Please contact us and we will personally assist you with your order:\n\n  Phone/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com \n  www.smartcret.com/en/contact";

/* formulario opinion */
$lang['en']['Tu opinión es muy importante para nosotros.']='Your opinion is very important to us.';
$lang['en']['¿Recibiste el folleto del paso a paso?']='Did you receive the step by step brochure?';
$lang['en']['Sí']='Yes';
$lang['en']['No']='No';
$lang['en']['¿El color era el que esperabas?']='Was the color what you expected?';
$lang['en']['¿Qué es lo que más te ha gustado?']='What did you like the most?';
$lang['en']['¿Y lo que menos?']='What did you like the least?';
$lang['en']['Comentarios y/o sugerencias']='Comments and/or suggestions';
$lang['en']['Enviar']='Submit';
$lang['en']['Es necesario rellenar los campos obligatorios del formulario']='It is necessary to fill in the required fields of the form';
$lang['en']['GRATIS']='FREE';
$lang['en']['Regalo producto:']='Product gift';
$lang['en']['']='';
$lang['en']['']='';
$lang['en']['']='';

/*INGLES US*/
$lang['en-us']['Pagado']='Paid';
$lang['en-us']['Pendiente']='Pending';
$lang['en-us']['Entregado'] = 'Delivered';
$lang['en-us']['Enviado'] = 'Shipped';
$lang['en-us']['Descuento aplicado:']='Discount applied:';
$lang['en-us']['Sólo Kits microcemento:']='Microcement kits only:';
$lang['en-us']['Eliminar descuento']='Remove discount coupon';
$lang['en-us']['El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío']='The discount is applied to the subtotal of the items before adding shipping costs';
$lang['en-us']["Actualmente no podemos realizar pedidos a las Islas Canarias a través de la web. Por favor ponte en contacto con nosotros y te atenderemos personalmente con tu pedido:\n\n  Telefono/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/contacto"] = "Currently we can not place orders to the Canary Islands through the web. Please contact us and we will personally assist you with your order:\n\n  Phone/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com \n  www.smartcret.com/en/contact";

/* formulario opinion */
$lang['en-us']['Tu opinión es muy importante para nosotros.']='Your opinion is very important to us.';
$lang['en-us']['¿Recibiste el folleto del paso a paso?']='Did you receive the step by step brochure?';
$lang['en-us']['Sí']='Yes';
$lang['en-us']['No']='No';
$lang['en-us']['¿El color era el que esperabas?']='Was the color what you expected?';
$lang['en-us']['¿Qué es lo que más te ha gustado?']='What did you like the most?';
$lang['en-us']['¿Y lo que menos?']='What did you like the least?';
$lang['en-us']['Comentarios y/o sugerencias']='Comments and/or suggestions';
$lang['en-us']['Enviar']='Submit';
$lang['en-us']['Es necesario rellenar los campos obligatorios del formulario']='It is necessary to fill in the required fields of the form';
$lang['en-us']['GRATIS']='FREE';
$lang['en-us']['Regalo producto:']='Product gift';
$lang['en-us']['']='';
$lang['en-us']['']='';
$lang['en-us']['']='';


/*FRANCES*/
$lang['fr']['Pagado']='Paid';
$lang['fr']['Pendiente']='Pending';
$lang['fr']['Entregado'] = 'Delivered';
$lang['fr']['Enviado'] = 'Shipped';
$lang['fr']['Descuento aplicado:']='Réduction appliquée:';
$lang['fr']['Eliminar descuento']='Retirer coupon de réduction';
$lang['fr']['Sólo Kits microcemento']='Kits de microciment uniquement';
$lang['fr']['El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío']='La remise de est appliquée sur la base des articles, avant le calcul de la TVA et avant l\'ajout des frais de port';
$lang['fr']["Actualmente no podemos realizar pedidos a las Islas Canarias a través de la web. Por favor ponte en contacto con nosotros y te atenderemos personalmente con tu pedido:\n\n  Telefono/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/contacto"] = "Nous ne sommes actuellement pas en mesure d'expédier des marchandises aux îles Canaries via le web. Veuillez nous contacter et nous vous assisterons personnellement pour votre commande:\n\n  Téléphone/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com www.smartcret.com/fr/contactez";

/*formulario opinion*/
$lang['fr']['Tu opinión es muy importante para nosotros.']='Votre avis est très important pour nous.';
$lang['fr']['¿Recibiste el folleto del paso a paso?']='Avez-vous reçu la brochure étape par étape ?';
$lang['fr']['Sí']='Oui';
$lang['fr']['No']='Non';
$lang['fr']['¿El color era el que esperabas?']='La couleur correspondait-elle à vos attentes ?';
$lang['fr']['¿Qué es lo que más te ha gustado?']='Qu\'est-ce qui vous a le plus plus ?';
$lang['fr']['¿Y lo que menos?']='Qu\'est-ce qui vous a le moins plus ?';
$lang['fr']['Comentarios y/o sugerencias']='Commentaires et/ou suggestions';
$lang['fr']['Enviar']='Envoyer';
$lang['fr']['Es necesario rellenar los campos obligatorios del formulario']='Vous devez remplir les champs obligatoires du formulaire';
$lang['fr']['GRATIS']='GRATUIT';
$lang['fr']['Regalo producto:']='Cadeau produit';
$lang['fr']['']='';
$lang['fr']['']='';


/*ITALIANO*/
$lang['it']['Pagado']='Pagato';
$lang['it']['Pendiente']='In attesa';
$lang['it']['Entregado'] = 'Consegnato';
$lang['it']['Enviado'] = 'Inviato';
$lang['it']['Descuento aplicado:']='Sconto applicato:';
$lang['it']['Eliminar descuento']='Rimuovi coupon di sconto';
$lang['it']['Sólo Kits microcemento']='Solo kit di microcemento';
$lang['it']['El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío']='Lo sconto viene applicato al subtotale degli articoli prima che vengano aggiunte le spese di spedizione';
$lang['it']["Actualmente no podemos realizar pedidos a las Islas Canarias a través de la web. Por favor ponte en contacto con nosotros y te atenderemos personalmente con tu pedido:\n\n  Telefono/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/contacto"] = "Al momento non siamo in grado di effettuare spedizioni alle Isole Canarie via web. Contattateci e vi risponderemo personalmente con il vostro ordine: \n\n  Telefono/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com \n  www.smartcret.com/contact";

$lang['it']['Tu opinión es muy importante para nosotros.']='La vostra opinione è molto importante per noi.';
$lang['it']['¿Recibiste el folleto del paso a paso?']='Ha ricevuto l\'opuscolo con le istruzioni per l\'uso?';
$lang['it']['Si']='Sì';
$lang['it']['No']='Si';
$lang['it']['¿El color era el que esperabas?']='Il colore era quello che si aspettava?';
$lang['it']['¿Qué es lo que más te ha gustado?']='Cosa le è piaciuto di più?';
$lang['it']['¿Y lo que menos?']='Cosa vi è piaciuto di meno?';
$lang['it']['Comentarios y/o sugerencias']='Commenti e/o suggerimenti';
$lang['it']['Enviar']='Inviare';
$lang['it']['Es necesario rellenar los campos obligatorios del formulario']='È necessario compilare i campi obbligatori del modulo';
$lang['it']['GRATIS']='GRATIS';
$lang['it']['Regalo producto:']='Prodotto regalo';
$lang['it']['']='';
$lang['it']['']='';

/*ALEMAN*/
$lang['de']['Pagado']='Bezahlt';
$lang['de']['Pendiente']='Ausstehend';
$lang['de']['Entregado'] = 'Ausgeliefert';
$lang['de']['Enviado'] = 'Versandt';
$lang['de']['Descuento aplicado:']='Angewandter Rabatt:';
$lang['de']['Sólo Kits microcemento:']='Nur Mikrozement-Kits:';
$lang['de']['Eliminar descuento']='Rabatt entfernen';
$lang['de']['El descuento se aplica sobre el subtotal de los artículos antes de sumar los gastos de envío']='Der Rabatt wird auf die Zwischensumme der Artikel angewendet, bevor die Versandkosten hinzugerechnet werden';
$lang['de']["Actualmente no podemos realizar pedidos a las Islas Canarias a través de la web. Por favor ponte en contacto con nosotros y te atenderemos personalmente con tu pedido:\n\n  Telefono/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/contacto"] = "Derzeit können wir über die Website keine Bestellungen für die Kanarischen Inseln tätigen. Bitte kontaktiere uns und wir helfen dir persönlich mit deiner Bestellung:\n\n  Telefon/Whatsapp: +34 674 409 942 \n  Email: info@smartcret.com\n  www.smartcret.com/de/kontakt";

/* formulario opinion */
$lang['de']['Tu opinión es muy importante para nosotros.']='Deine Meinung ist uns sehr wichtig.';
$lang['de']['¿Recibiste el folleto del paso a paso?']='Hast du die Schritt-für-Schritt-Broschüre erhalten?';
$lang['de']['Sí']='Ja';
$lang['de']['No']='Nein';
$lang['de']['¿El color era el que esperabas?']='Entsprach die Farbe deinen Erwartungen?';
$lang['de']['¿Qué es lo que más te ha gustado?']='Was hat dir am besten gefallen?';
$lang['de']['¿Y lo que menos?']='Und was am wenigsten?';
$lang['de']['Comentarios y/o sugerencias']='Kommentare und/oder Vorschläge';
$lang['de']['Enviar']='Senden';
$lang['de']['Es necesario rellenar los campos obligatorios del formulario']='Die obligatorischen Felder des Formulars müssen ausgefüllt werden';
$lang['de']['GRATIS']='KOSTENLOS';
$lang['de']['Regalo producto:']='Produktgeschenk';
$lang['de']['']='';
$lang['de']['']='';
$lang['de']['']='';
$lang['de']['']='';



return ( $lg=='es' ) ? $txt : $lang[$lg][$txt];


}


?>

