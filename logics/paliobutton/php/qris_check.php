<?php 

	$amount = $_POST["amount"];
	$external_id = $_POST["external_id"];;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.xendit.co/qr_codes/$external_id/payments/simulate",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => "{\n\t\"amount\": ".$amount ."}",
	  CURLOPT_HTTPHEADER => array(
		// "authorization: Basic eG5kX2RldmVsb3BtZW50X3RCTnkxZGRWb2pjcEN1M0ZjQjdJbHhybDNFZnFUY3V0akp4eGxMQzJrcWNtcUc4TFdFYll2VDF1VFFoVmo6",
		// eG5kX2RldmVsb3BtZW50X2pKczJrU0dWbTNoN0xZOVVMcTJYWkJITGszVzh4VlVaWkpFZEVkYW9KT1h5bmx2WFlMakRVNnF0VnZ3WnZ4WWw6
		// OCT 2022 eG5kX2RldmVsb3BtZW50X2s3WE5VUVczd05oUUpseEF6TVd0N1hNYm40MkVEMENjc3VzejNXQ21GbWd0eHUzNVVnRGJFdFdLTnFVRWE1Sjo=
		"authorization: Basic eG5kX2RldmVsb3BtZW50X2s3WE5VUVczd05oUUpseEF6TVd0N1hNYm40MkVEMENjc3VzejNXQ21GbWd0eHUzNVVnRGJFdFdLTnFVRWE1Sjo=",
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
