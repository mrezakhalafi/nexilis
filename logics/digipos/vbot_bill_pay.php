<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = newnus();

$auth = include_once 'jwt.php';

$product = $_POST['product']; // code
$customer = $_POST['customer']; // phone number, customer number, etc
$trxid = $_POST['partner_trxid']; // transaction id
$command = $_POST['command']; //
$method = $_POST['method']; //
$amount = $_POST['amount']; // total 
$last_update = $_POST['last_update'];
$f_pin = $_POST['f_pin']; //

$amount = $_POST['amount']; // amount

$curl = curl_init();

$postfields = '{
    "command": "PAY",
    "product": "' . $product . '",
    "customer": "' . $customer . '",
    "partner_trxid": "' . $trxid . '"
    "amount": ' . $amount . '
}';

// GET BILL DETAILS FROM INQUIRY

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
    // echo $response;
    $json = json_decode(utf8_encode($response), true);

    $data = json_encode($json['data']);

    // curl_close($curl);

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
        '$trxid', 
        '$product',
        '$customer',
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

        echo $response;
    } catch (Exception $e) {
        echo 'Exception -> ';
        var_dump($e->getMessage());
    }
}

// $response = '{
// 	"status": 0,
// 	"message": "Payment success. PLN50-860346xxxxx GEDUNG SERBA GUNA. Amount: Rp.49800, admin: Rp.2500, total: Rp.52300",
// 	"trx_id": 12477,
// 	"partner_trxid": "' . $trxid . '",
// 	"amount": 49800,
// 	"total": '.$amount.',
// 	"data": {
// 		"billing_id": "070017644099977829xxxxxx",
// 		"customer_number": "53831xxxxx",
// 		"customer_name": "GEDUNG SERBA GUNA",
// 		"group_rate": "R2/4400 VA",
// 		"admin_fee": 2500,
// 		"amount": 49800,
// 		"receipt": {
// 			"header": null,
// 			"footer": null,
// 			"fields": {
// 				"billing_id": "No.Reff",
// 				"customer_name": "Nama",
// 				"customer_number": "ID Pel",
// 				"group_rate": "Tarif/Daya"
// 			},
// 			"info": ""
// 		}
// 	}
// }';

// $resp = json_decode($response, true);
