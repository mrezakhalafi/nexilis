<?php

$auth = include_once 'jwt.php';

$command = $_POST['command']; // list station or airport
$product = $_POST['product']; // code "PESAWAT"/"KERETA"
$data_raw = $_POST['data']; // data, json stringify->base64
$data = base64_decode($data_raw);

$curl = curl_init();

// DO INQUIRY TO GET BILL DETAILS

$postfields = '{
    "command": "' . $command . '",
    "product": "' . $product . '",
    "data": '. $data .'
}';

// echo $postfields;

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://dev.vbotdigital.com/json',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $postfields,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $auth,
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
    // $json = json_decode(utf8_encode($response), true);
    // echo json_encode($json);
}

// $response = '{
// 	"status": 0,
// 	"message": "Inquiry success. PLN500-22111486050 YAC** ********. Amount: Rp.499800, admin: Rp.2500, total: Rp.502300",
// 	"inquiry_id": 1861,
// 	"partner_trxid": "'.$trxid.'",
// 	"amount": 499800,
// 	"total": 502300,
// 	"data": {
// 		"customer_name": "YAC** ********",
// 		"group_rate": "B1/5500 VA",
// 		"admin_fee": 2500,
// 		"amount": 499800,
// 		"receipt": {
// 			"header": null,
// 			"footer": null,
// 			"fields": {
// 				"customer_name": "Nama",
// 				"group_rate": "Tarif/Daya"
// 			},
// 			"info": ""
// 		}
// 	}
// }';

// echo $response;
