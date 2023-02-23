<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include class
include_once 'paxel.php';

// initialize child
$shipper = new Paxel();

// make sure data is not empty
if (

    // origin
    !empty($_POST['address_origin']) &&
    !empty($_POST['province_origin']) &&
    !empty($_POST['city_origin']) &&
    !empty($_POST['district_origin']) &&
    !empty($_POST['zip_code_origin']) &&

    // destination
    !empty($_POST['address_destination']) &&
    !empty($_POST['province_destination']) &&
    !empty($_POST['city_destination']) &&
    !empty($_POST['district_destination']) &&
    !empty($_POST['zip_code_destination']) &&

    // items
    !empty($_POST['weight_items']) &&
    !empty($_POST['length_items']) &&
    !empty($_POST['width_items']) &&
    !empty($_POST['height_items'])

) {
    // for non required variables
    $service_type = isset($_POST['service_type']) ? $_POST['service_type'] : "SAMEDAY";

    try {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://stage-commerce-api.paxel.co/v1/rates/city',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "origin": {
                    "address": "' . $_POST['address_origin'] . '",
                    "province": "' . $_POST['province_origin'] . '",
                    "city": "' . $_POST['city_origin'] . '",
                    "district": "' . $_POST['district_origin'] . '",
                    "zip_code": "' . $_POST['zip_code_origin'] . '"
                },
                "destination": {
                    "address": "' . $_POST['address_destination'] . '",
                    "province": "' . $_POST['province_destination'] . '",
                    "city": "' . $_POST['city_destination'] . '",
                    "district": "' . $_POST['district_destination'] . '",
                    "zip_code": "' . $_POST['zip_code_destination'] . '"
                },
                "weight": ' . $_POST['weight_items'] . ',
                "dimension": "' . $_POST['length_items'] .'x'. $_POST['width_items'] .'x'. $_POST['height_items'] . '",
                "service_type": "' . $service_type . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'X-Paxel-API-Key: ' . Paxel::API_KEY,
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
//     "city_origin": "Kota Jakarta Selatan",
//     "city_destination": "Kota Jakarta Timur",
//     "small_price": 10000,
//     "medium_price": 12000,
//     "large_price": 20000,
//     "custom_price": 20000,
//     "time_detail": [
//       {
//         "time_pickup_start": "08:00:00",
//         "time_pickup_end": "10:00:00",
//         "time_delivery_start": "16:00:00",
//         "time_delivery_end": "18:00:00",
//         "service": "same_day"
//       }
//     ],
//     "fixed_price": 20000,
//     "fixed_price_type": "dimension",
//     "fixed_short_size": "LAR",
//     "fixed_size": "large"
//   }
// }