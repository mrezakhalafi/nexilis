<?php

$auth = include_once 'jwt.php';

$product = $_POST['product']; // code "PESAWAT"/"KERETA"
$trxid = $_POST['trxid'];
$partner_trxid = $_POST['partner_trxid'];
$amount = $_POST['amount'];
$f_pin = $_POST['f_pin']; //

$curl = curl_init();

// DO INQUIRY TO GET BILL DETAILS

$postfields = '{
    "command": "CONFIRM",
    "product": "' . $product . '",
    "trxid": "'.$trxid.'",
    "partner_trxid": "'.$partner_trxid.'",
    "amount": "'.$amount.'"
}';

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

echo $response;

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $json = json_decode(utf8_encode($response), true);

    $data = json_encode($json['data']);

    $sql = "INSERT INTO DIGIPOS_PURCHASE 
    (
        F_PIN,
        TRX_ID, 
        PRODUCT_CODE, 
        CUSTOMER_ID, 
        COMMAND, 
        DATA,
        STATUS, 
        PAYMENT_METHOD, 
        AMOUNT, 
        LAST_UPDATE
    ) VALUES (
        '$f_pin',
        '$partner_trxid', 
        '$product',
        '$trxid',
        '$command',
        '$data',
        2,
        '$method',
        $amount,
        $last_update
    )";

    try {
        $query = $dbconn->prepare($sql);
        $query->execute();
        $query->close();
    } catch (Exception $e) {
        echo 'Exception -> ';
        var_dump($e->getMessage());
    }
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
