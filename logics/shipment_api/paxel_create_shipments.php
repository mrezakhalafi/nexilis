<?php

// include class
include_once 'paxel.php';

// initialize child
$shipper = new Paxel();

// make sure data is not empty
if (
    
    !empty($_POST['invoice_number']) &&
    !empty($_POST['payment_type']) &&
    !empty($_POST['invoice_value']) &&

    // origin
    !empty($_POST['name_origin']) &&
    !empty($_POST['phone_origin']) &&
    !empty($_POST['address_origin']) &&
    !empty($_POST['note_origin']) &&
    !empty($_POST['province_origin']) &&
    !empty($_POST['city_origin']) &&
    !empty($_POST['district_origin']) &&
    !empty($_POST['village_origin']) &&
    !empty($_POST['zip_code_origin']) &&

    // destination
    !empty($_POST['name_destination']) &&
    !empty($_POST['phone_destination']) &&
    !empty($_POST['address_destination']) &&
    !empty($_POST['note_destination']) &&
    !empty($_POST['province_destination']) &&
    !empty($_POST['city_destination']) &&
    !empty($_POST['district_destination']) &&
    !empty($_POST['village_destination']) &&
    !empty($_POST['zip_code_destination']) &&

    // items
    !empty($_POST['items'])

) {

    if($_POST['is_highvalue'] == 'true'){

        if(empty($_POST['name_id']) || empty($_POST['name_en'])){
            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(array("Error: " => "Data is incomplete."));

            return;
        }
    }

    if($_POST['is_dropship'] == 'true'){

        if(empty($_POST['name_dropshipper']) || empty($_POST['phone_dropshipper'])){
            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(array("Error: " => "Data is incomplete."));

            return;
        }
    }

    // for non required variables
    $email_origin = isset($_POST['email_origin']) ? $_POST['email_origin'] : null;
    $longitude_origin = isset($_POST['longitude_origin']) ? $_POST['longitude_origin'] : null;
    $latitude_origin = isset($_POST['latitude_origin']) ? $_POST['latitude_origin'] : null;

    $email_destination = isset($_POST['email_destination']) ? $_POST['email_destination'] : null;
    $longitude_destination = isset($_POST['longitude_destination']) ? $_POST['longitude_destination'] : null;
    $latitude_destination = isset($_POST['latitude_destination']) ? $_POST['latitude_destination'] : null;

    $name_id = isset($_POST['name_id']) ? $_POST['name_id'] : null;
    $name_en = isset($_POST['name_en']) ? $_POST['name_en'] : null;

    $name_dropshipper = isset($_POST['name_dropshipper']) ? $_POST['name_dropshipper'] : null;
    $phone_dropshipper = isset($_POST['phone_dropshipper']) ? $_POST['phone_dropshipper'] : null;

    $service_type = isset($_POST['service_type']) ? $_POST['service_type'] : "SAMEDAY";

    try {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://stage-commerce-api.paxel.co/v1/shipments',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "invoice_number": "'.$_POST['invoice_number'].'_'.substr(md5(mt_rand()), 0,5).'",
                "payment_type": "CRD",
                "invoice_value": '.$_POST['invoice_value'].',
                "origin": {
                    "name": "'.$_POST['name_origin'].'",
                    "phone": "'.$_POST['phone_origin'].'",
                    "address": "'.$_POST['address_origin'].'",
                    "note": "'.$_POST['note_origin'].'",
                    "province": "'.$_POST['province_origin'].'",
                    "city": "'.$_POST['city_origin'].'",
                    "district": "'.$_POST['district_origin'].'",
                    "village": "'.$_POST['village_origin'].'",
                    "zip_code": "'.$_POST['zip_code_origin'].'"
                },
                "destination": {
                    "name": "'.$_POST['name_destination'].'",
                    "phone": "'.$_POST['phone_destination'].'",
                    "address": "'.$_POST['address_destination'].'",
                    "note": "'.$_POST['note_destination'].'",
                    "province": "'.$_POST['province_destination'].'",
                    "city": "'.$_POST['city_destination'].'",
                    "district": "'.$_POST['district_destination'].'",
                    "village": "'.$_POST['village_destination'].'",
                    "zip_code": "'.$_POST['zip_code_destination'].'"
                },
                "items": '.stripslashes($_POST['items']).',
                "pickup_datetime": "'.date("Y-m-d H:i:s", strtotime('+1 hours')).'",
                "need_insurance": false,
                "service_type": "'.$service_type.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Paxel-API-Key: ' . Paxel::API_KEY,
                'X-Paxel-Signature: ' . $shipper->generate_signature($_POST['invoice_number'], $_POST['name_origin'], $_POST['name_destination'], $_POST['first_item_name']),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

    } catch (Throwable $error) {
        http_response_code(500);
        echo "Error: {$error->getMessage()}";
    }

}

// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("Error: " => "Data is incomplete."));
}

// contoh response
// {
//   "message": "OK",
//   "status_code": 200,
//   "data": {
//     "airwaybill_code": "MERCHANT-20200224-1-HB4OBT",
//     "shipping_cost": 30000,
//     "created_datetime": "2020-02-24 13:48:37",
//     "estimated_pickup_date": "2020-02-25",
//     "estimated_pickup_min_time": "12:00",
//     "estimated_pickup_max_time": "14:00",
//     "estimated_arrival_date": "2020-02-25",
//     "estimated_arrival_min_time": "20:00",
//     "estimated_arrival_max_time": "22:00"
//   }
// }