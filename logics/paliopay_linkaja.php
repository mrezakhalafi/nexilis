<?php 

	$amount = $_POST["amount"];
	$callback = $_POST["callback"];
	$external_id = round(microtime(true)*1000) + 1;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.xendit.co/ewallets/charges",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{\n\t\"reference_id\": \"".$external_id."\",\n\t\"currency\": \"IDR\",\n\t\"amount\": ".$amount.",\n\t\"checkout_method\": \"ONE_TIME_PAYMENT\",\n\t\"channel_code\": \"ID_LINKAJA\",\n\t\"channel_properties\": {\n\t\t\"success_redirect_url\": \"".$callback."\"\n\t}\n}",
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
	  // echo $response;
	  $json = json_decode(utf8_encode($response), true);
      echo json_encode($json);
	}

 ?>