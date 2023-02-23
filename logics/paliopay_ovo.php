<?php
    // ini_set("display_errors", 1);
    // error_reporting(E_ALL);

    $mobile_number = $_POST["phone_number"];
    $amount = $_POST["amount"];
    $external_id = round(microtime(true)*1000) + 1;

    function checkOvo($charge_id){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.xendit.co/ewallets/charges/" . $charge_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "{\n\t\"charge_id\": \"".$charge_id."\"\n}",
          CURLOPT_HTTPHEADER => array(
            "authorization: Basic eG5kX2RldmVsb3BtZW50X3RCTnkxZGRWb2pjcEN1M0ZjQjdJbHhybDNFZnFUY3V0akp4eGxMQzJrcWNtcUc4TFdFYll2VDF1VFFoVmo6",
            "cache-control: no-cache",
            "content-type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $json = json_decode(utf8_encode($response), true);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            // echo $response;

            if($json['status'] == "SUCCEEDED"){
                // echo $response;
                echo "SUCCEEDED";

            } else {
                checkOvo($charge_id);
                // echo $response;
            }
        }
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.xendit.co/ewallets/charges",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\n\t\"reference_id\": \"". $external_id ."\",\n\t\"currency\": \"IDR\",\n\t\"amount\": ".$amount.",\n\t\"checkout_method\": \"ONE_TIME_PAYMENT\",\n\t\"channel_code\": \"ID_OVO\",\n\t\"channel_properties\": {\n\t\t\"mobile_number\": \"".$mobile_number."\"\n\t}\n}",
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic eG5kX2RldmVsb3BtZW50X3RCTnkxZGRWb2pjcEN1M0ZjQjdJbHhybDNFZnFUY3V0akp4eGxMQzJrcWNtcUc4TFdFYll2VDF1VFFoVmo6",
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $json = json_decode(utf8_encode($response), true);
        // echo $response;
        checkOvo($json["id"]);
    }

?>