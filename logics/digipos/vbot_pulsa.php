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
$amount = $_POST['amount'];
$last_update = $_POST['last_update'];
$f_pin = $_POST['f_pin']; //

// $curl = curl_init();

$postfields = '{
    "command": "PURCHASE",
    "product": "' . $product . '",
    "customer": "' . $customer . '",
    "partner_trxid": "' . $trxid . '"
}';

// $postfields = '{
//     "command": "PURCHASE",
//     "product": "THREE_TEST",
//     "customer": "' . $customer . '",
//     "partner_trxid": "' . $trxid . '"
// }';

$curl_arr = array(
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
);

// curl_setopt_array($curl, $curl_arr);

// echo "<pre>";
// print_r($curl_arr);
// echo "</pre>";

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    // echo $response;
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

        // echo $response;
        // echo '<br>';
        echo $response;
    } catch (Exception $e) {
        echo 'Exception -> ';
        var_dump($e->getMessage());
    }
}

// $response = '{
// 	"status": 0,
// 	"message": "Purchase success. UNIPIN100-xxxxx . Price: Rp.' . $amount . '",
// 	"trx_id": 12126,
// 	"partner_trxid": "' . $trxid . '",
// 	"serial_number": "UPGC-4-S-010xxxx|7255-1644-xxxx-xxx",
// 	"data": {
// 		"customer_number": "' . $customer . '",
// 		"serial_number": "UPGC-4-S-01020xxxx|7255-1644-9239-xxxxx",
// 		"price": ' . $amount . ',
// 		"product": "UNIPINxxxx"
// 	}
// }
// ';
