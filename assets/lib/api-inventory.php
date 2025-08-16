<?php

global $CLIENT_ID;
global $CLIENT_SECRET;
global $REFRESH_TOKEN;
global $ORGANIZATION_ID;

//estos datos son del usuario de Hector
$ORGANIZATION_ID = "637820086";
$CLIENT_ID = "1000.CUWZVUTAI1UJ1Q60CKGUGFSR3Y9NVZ";
$CLIENT_SECRET = "f2a6d79cfa7e99b522b1f3fd6266d19c1f2f512c35";
$REFRESH_TOKEN = "1000.219602a1bcb5f5859d18a3e82b47857d.32745db4dc11741973f6046640ec1307";

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


function xxfindContactNif ( $contactNif )
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


function xxfindContactEmail($accessToken, $organizationId, $contactEmail)
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




function getInventorySalesOrder ( $sales_order_id ) {

    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.zohoapis.com/inventory/v1/salesorders/' . $sales_order_id . '?organization_id=' . $ORGANIZATION_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $ACCESS_TOKEN
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    return $response;

}

function getInventorySalesOrders ( $items ) {

    global $ACCESS_TOKEN;
    global $ORGANIZATION_ID;

    $filterx = '&filter_by=Status.All&per_page=5&search_criteria=%7B"search_text"%3A"SC-"%7D&sort_column=created_time&sort_order=D';
    $filter = '&filter_by=Status.Closed&per_page=' . $items . '&sort_column=created_time&sort_order=D';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.zohoapis.com/inventory/v1/salesorders?organization_id=' . $ORGANIZATION_ID . $filter ,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $ACCESS_TOKEN
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    return $response;

}

