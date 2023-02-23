<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    // TEST MODE ( base64( 'secret api key' + ':' ) )
    // eG5kX2RldmVsb3BtZW50X2pKczJrU0dWbTNoN0xZOVVMcTJYWkJITGszVzh4VlVaWkpFZEVkYW9KT1h5bmx2WFlMakRVNnF0VnZ3WnZ4WWw6
    // OCT 2022 xnd_development_k7XNUQW3wNhQJlxAzMWt7XMbn42ED0Ccsusz3WCmFmgtxu35UgDbEtWKNqUEa5J
    $secretKey = "eG5kX2RldmVsb3BtZW50X2s3WE5VUVczd05oUUpseEF6TVd0N1hNYm40MkVEMENjc3VzejNXQ21GbWd0eHUzNVVnRGJFdFdLTnFVRWE1Sjo=";

    // prod
    // $secretKey = "eG5kX3Byb2R1Y3Rpb25feXBwVkFkM1R3UTJNNFdVQW9lWWZKYkNORjhVZFpwUVlQYjZRTnVlSkw5VnVtM3RDMktJN2Jma1pjaXpaZ256Og==";

    $token = $_POST["token_id"];
    $amount = $_POST["amount"];
    $cvv = $_POST["cvv"];
    $external_id = round(microtime(true)*1000) + 1;

    // xendit api
    $url = "https://api.xendit.co/credit_card_charges";
    $data = array(
        'token_id' => $token,
        'external_id' => strval($external_id),
        'amount' => $amount,
        'card_cvn' => $cvv
    );

    $options = array(
        'http' => array(
            'header'  => 
                "Authorization: Basic ".$secretKey."\r\n".
                "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => strval(json_encode($data))
        )
    );
    $stream = stream_context_create($options); 
    $result = file_get_contents($url, false, $stream);
    $json_result = json_decode($result);
    // end xendit api

    if(!$json_result){
        echo $result;
    }
    else if($json_result->status == "FAILED"){
        echo $result;
    }
    else if($json_result->status == "CAPTURED"){
        echo $result;
    }
    
?>