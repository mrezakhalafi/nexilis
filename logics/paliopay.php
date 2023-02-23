<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // TEST MODE ( base64( 'secret api key' + ':' ) )
    // $secretKey = "eG5kX2RldmVsb3BtZW50X3RCTnkxZGRWb2pjcEN1M0ZjQjdJbHhybDNFZnFUY3V0akp4eGxMQzJrcWNtcUc4TFdFYll2VDF1VFFoVmo6";
    $secretKey = "eG5kX2RldmVsb3BtZW50X2pKczJrU0dWbTNoN0xZOVVMcTJYWkJITGszVzh4VlVaWkpFZEVkYW9KT1h5bmx2WFlMakRVNnF0VnZ3WnZ4WWw6";


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