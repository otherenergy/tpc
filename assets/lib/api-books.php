<?php

global $CLIENT_ID;
global $CLIENT_SECRET;
global $REFRESH_TOKEN;
global $ORGANIZATION_ID;


$ORGANIZATION_ID = "637820086";
$CLIENT_ID = "1000.5HE04LJ4OLTW2SUJ7SHN8ZA2EBEXWI";
$CLIENT_SECRET = "00f7e0b3e9eb01c01d2f8ce5dd847abe1e69f880fd";
$REFRESH_TOKEN = "1000.d7a88c3fb044b7520e3b56652be0636e.f77ef0c70d81f6076647824e0c4da3cf";

$ACCESS_TOKEN = "";
$ACCESS_TOKEN = regenerateAccessToken($REFRESH_TOKEN, $CLIENT_ID, $CLIENT_SECRET);


function regenerateAccessToken($refreshToken, $clientId, $clientSecret)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token?refresh_token=' . $refreshToken . '&client_id=' . $clientId . '&client_secret=' . $clientSecret . '&redirect_uri=http://www.zoho.com/books&grant_type=refresh_token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response["access_token"];
}


function createSalesOrder( $salesOrderData )
{
    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://books.zoho.com/api/v3/salesorders?organization_id='. $ORGANIZATION_ID . '&ignore_auto_number_generation=true',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('JSONString' => $salesOrderData ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '. $ACCESS_TOKEN,
        ),
    ));

    $response = json_decode( curl_exec( $curl ) );

    curl_close($curl);
    return $response;
}

function updateSaleOrderShippingAddress( $saleorder_id, $shippingAddress )
{
    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://books.zoho.com/api/v3/salesorders/' . $saleorder_id . '/address/shipping?organization_id=' . $ORGANIZATION_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => array('JSONString' => $shippingAddress ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $ACCESS_TOKEN,
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $response;
}

function updateSaleOrderBillingAddress( $saleorder_id, $billingAddress )
{
    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://books.zoho.com/api/v3/salesorders/' . $saleorder_id . '/address/billing?organization_id=' . $ORGANIZATION_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => array('JSONString' => $billingAddress ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $ACCESS_TOKEN,
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $response;
}


function findContactNif ( $contactNif )
{
    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $contacts = getAllContacts( $ACCESS_TOKEN, $ORGANIZATION_ID )["contacts"];
    $res = array();
    $res['contact_found'] = FALSE;

    foreach ( $contacts as $contact ) {
        if ( isset ( $contact['custom_fields'] ) )
        {
            foreach ( $contact['custom_fields'] as $custom )
            {
                if ( $custom['label'] == 'NIF/CIF' && $custom['value_formatted'] == $contactNif )
                {
                    $res['nif_cif'] = $custom['value_formatted'];
                    $res['first_name'] = $contact['first_name'];
                    $res['contact_id'] = $contact['contact_id'];
                    $res['last_name'] = $contact['last_name'];
                    $res['email'] = $contact['email'];
                    $res['contact_found'] = TRUE;

                    // var_dump( $custom );
                }
            }
        }
    }
    return $res;
}

function createNewContact( $contactData )
{
    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://books.zoho.com/api/v3/contacts?organization_id=' . $ORGANIZATION_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('JSONString' => $contactData ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $ACCESS_TOKEN,
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $response;
}

function updateContact( $contactId, $contactData )
{
    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://books.zoho.com/api/v3/contacts/' . $contactId . '?organization_id=' . $ORGANIZATION_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => array('JSONString' => $contactData ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $ACCESS_TOKEN,
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $response;
}

function findContactEmail($accessToken, $organizationId, $contactEmail)
{
    $contacts = getAllContacts($accessToken, $organizationId)["contacts"];
    $contactFound = false;

    foreach ($contacts as $contact) {
        if ($contact["email"] == $contactEmail) {
            $contactFound = $contact["contact_id"];
            break;
        }
    }
    return $contactFound;
}

function getAllContacts()
{
    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://books.zoho.com/api/v3/contacts?organization_id=' . $ORGANIZATION_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $ACCESS_TOKEN,
            'Cookie: BuildCookie_637820086=1; JSESSIONID=432D2A6B63E551D9E45F333E8D16FFCD; _zcsr_tmp=6651ae60-f18c-4758-8d6d-c772a39430f5; ba05f91d88=86e4d659e5aa9a3b740db071dd22c303; zbcscook=6651ae60-f18c-4758-8d6d-c772a39430f5'
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response;
}

function listTaxes () {

    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://books.zoho.com/api/v3/settings/taxes?organization_id='. $ORGANIZATION_ID . '',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $ACCESS_TOKEN
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    // echo $response;
    return $response;
}

function getSalesOrder ( $sales_order_id ) {

    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://books.zoho.com/api/v3/salesorders/' . $sales_order_id . '?organization_id='. $ORGANIZATION_ID . '',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $ACCESS_TOKEN
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
}

function obtenIdEmail ( $email ) {

    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.zohoapis.com/books/v3/contacts?organization_id='. $ORGANIZATION_ID . '&email=' . $email,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $ACCESS_TOKEN
      ),
    ));

    $response = json_decode( curl_exec( $curl ) );
    curl_close($curl);
    return $response->contacts[0]->contact_id ;
}
