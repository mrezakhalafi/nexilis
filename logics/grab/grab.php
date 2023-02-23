<?php

// include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

class Grab {

    private const CLIENT_ID = "352415cc409a48b6866ac4f01e7b89e1";
    private const CLIENT_SECRET = "7YDcqC2VSwcAr_Ax";

    public function load_token(){
        $token = false;
        try{
            $token = @file_get_contents("grab-token.txt");
        }
        catch(Exception $e) {
            
        }
        if(!$token){
            return "";
        }
        return $token;
    }

    public function get_access_token() {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grabid/v1/oauth2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "client_id": "' . self::CLIENT_ID . '",
            "client_secret": "' . self::CLIENT_SECRET . '",
            "grant_type" : "client_credentials",
            "scope": "grab_express.partner_deliveries"
        }',
        // CURLOPT_POSTFIELDS =>'{
        //     "client_id": "' . self::CLIENT_ID . '",
        //         "client_secret": "' . self::CLIENT_SECRET . '",
        //         "grant_type" : "client_credentials",
        //         "scope": "food.partner_api.express_deliveries"
        //     }',
        CURLOPT_HTTPHEADER => array(
            'Cache-Control: no-cache',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($http_code == 200){
            file_put_contents("grab-token.txt",$response);
        }
        
        curl_close($curl);
        http_response_code($http_code);
        return $response;

        // example response
        // {
        //     "access_token": "eyJhbGciOiJSUzI1NiIsImtpZCI6Il9kZWZhdWx0IiwidHlwIjoiSldUIn0.eyJhdWQiOiI1NWRkNzEyZTViNWY0MDQyYTEzZjVkZTM0ZjM4NmVjMSIsImV4cCI6MTU2MDMwMDgzMSwiaWF0IjoxNTU5Njk2MDMxLCJpc3MiOiJodHRwczovL2lkcC5ncmFiLmNvbSIsImp0aSI6InFJZDB6Wmh1UkhxX0F4STZPdncwekEiLCJuYmYiOjE1NTk2OTU4NTEsInBpZCI6IjIwM2E5MmFmLTJmN2ItNDNjZS1hNTVmLTc5NGE4ZWQzZWE2NyIsInBzdCI6MSwic2NwIjoiW1wiYzE2MWM0NTdlZGVlNGExYmI4YTQwMzZmMDM5ZTYzZDZcIl0iLCJzdWIiOiJUV09fTEVHR0VEX09BVVRIIiwic3ZjIjoiIiwidGtfdHlwZSI6ImFjY2VzcyJ9.DEpeDobxey2YIEiAYWJ4zB5chCwGwc7Fojb8GEjnn4bORqb8-vDD5zQ-X7BRPRxAUv0MVam4q2annB4z4tEBNYYv1PW-w9UiJ-224kR8QJ1lWOsN9ZKibSWRMS6Mt9LN3r0_VSTDQB_X0QzMOOV00EeloMTbf6TKJ-3YQwDuD8GJeK1aNaLFmfcQC6avvyGcfO2VFnDDnLbDLmJ-oYQXn9Xb7SX_GDsnWUukxaOriQFW7PW0JVSvrAmq3lV5C1aioDI_qJD0MV065MJJT9xOVPIf_Myq5R6AHTF5Gon6ga3eA8fAYNpQ9Qbmq2jH-aFYBMcx27fL2-4vIhBCHKnhww",
        //     "token_type": "Bearer",
        //     "expires_in": 604799
        // }

    }

    public function get_delivery_quotes($access_token, $service_type, $packages, $origin, $destination, $first_try = true) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express-sandbox/v1/deliveries/quotes',
        // CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express/v1/deliveries/quotes',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "serviceType": "'.$service_type.'",
            "packages": '.stripslashes($packages).',
            "origin": '.stripslashes($origin).',
            "destination": '.stripslashes($destination).'
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($http_code == 401 &&  $first_try){
            $access_token = $this->get_access_token();
            $this->get_delivery_quotes($access_token, $service_type, $packages, $origin, $destination, false);
        }
        else{
            http_response_code($http_code);
            echo $response;
        }

        // example response
        // {
        //     "quotes": [
        //         {
        //             "service": {
        //                 "id": 0,
        //                 "type": "INSTANT",
        //                 "name": "GrabExpress"
        //             },
        //             "currency": {
        //                 "code": "SGD",
        //                 "symbol": "S$",
        //                 "exponent": 2
        //             },
        //             "amount": 11,
        //             "estimatedTimeline": {
        //                 "pickup": "2020-04-01T07:35:05Z",
        //                 "dropoff": "2020-04-01T07:55:28Z"
        //             },
        //             "distance": 10449
        //         }
        //     ],
        //     "origin": {
        //         "address": "1 IJK View, Singapore 018936",
        //         "cityCode": "SIN",
        //         "coordinates": {
        //             "latitude": 1.2345678,
        //             "longitude": 3.4567890
        //         }
        //     },
        //     "destination": {
        //         "address": "1 ABC St, Singapore 078881",
        //         "cityCode": "SIN",
        //         "coordinates": {
        //             "latitude": 1.2345876,
        //             "longitude": 3.4567098
        //         }
        //     },
        //     "packages": [
        //         {
        //             "name": "Fish Burger",
        //             "description": "Fish Burger with mayonnaise sauce",
        //             "quantity": 2,
        //             "price": 5,
        //             "dimensions": {}
        //         },
        //         {
        //             "name": "Truffle Fries",
        //             "description": "Thin cut deep-fried potatoes topped with truffle oil",
        //             "quantity": 2,
        //             "price": 4,
        //             "dimensions": {}
        //         }
        //     ]
        // }

    }

    public function create_delivery_request($access_token, $merchant_order_id, $service_type, $packages, $origin, $destination, $recipient, $sender, $first_try = true) {
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express-sandbox/v1/deliveries',
        // CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express/v1/deliveries',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "merchantOrderID": "'.$merchant_order_id.'",
            "serviceType": "'.$service_type.'",
            "packages": '.stripslashes($packages).',
            "origin": '.stripslashes($origin).',
            "destination": '.stripslashes($destination).',
            "recipient": '.stripslashes($recipient).',
            "sender": '.stripslashes($sender).'
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($http_code == 401 &&  $first_try){
            $access_token = $this->get_access_token();
            $this->create_delivery_request($access_token, $merchant_order_id, $service_type, $packages, $origin, $destination, $recipient, $sender, false);
        }
        else{
            http_response_code($http_code);
            echo $response;
        }

        // example response
        // {
        //     "deliveryID": "IN-2-0BTPB1C1G8IL6W72ZHK8",
        //     "merchantOrderID": "1ac1fa2f-880a-43d3-8476-7cc6e99e40f6",
        //     "paymentMethod": "CASHLESS",
        //     "quote": {
        //         "service": {
        //             "id": 0,
        //             "type": "INSTANT",
        //             "name": "GrabExpress"
        //         },
        //         "currency": {
        //             "code": "SGD",
        //             "symbol": "S$",
        //             "exponent": 2
        //         },
        //         "amount": 11,
        //         "estimatedTimeline": {
        //             "pickup": "2020-04-01T04:21:29Z",
        //             "dropoff": "2020-04-01T04:41:40Z"
        //         },
        //         "distance": 10428,
        //         "origin": {
        //             "address": "1 IJK View, Singapore 018936",
        //             "coordinates": {
        //                 "latitude": 1.2345678,
        //                 "longitude": 3.4567890
        //             }
        //         },
        //         "destination": {
        //             "address": "1 ABC St, Singapore 078881",
        //             "coordinates": {
        //                 "latitude": 1.2345876,
        //                 "longitude": 3.4567098
        //             }
        //         },
        //         "packages": [
        //             {
        //                 "name": "Fish Burger",
        //                 "description": "Fish Burger with mayonnaise sauce",
        //                 "quantity": 2,
        //                 "price": 5,
        //                 "dimensions": {}
        //             },
        //             {
        //                 "name": "Truffle Fries",
        //                 "description": "Thin cut deep-fried potatoes topped with truffle oil",
        //                 "quantity": 2,
        //                 "price": 4,
        //                 "dimensions": {}
        //             }
        //         ]
        //     },
        //     "sender": {
        //         "firstName": "Jewel Changi Branch",
        //         "companyName": "Shake Shack",
        //         "email": "ssburger@gmail.com",
        //         "phone": "0124355834",
        //         "smsEnabled": true
        //     },
        //     "recipient": {
        //         "firstName": "John",
        //         "lastName": "Tan",
        //         "email": "john@gmail.com",
        //         "phone": "91526655",
        //         "smsEnabled": true
        //     },
        //     "status": "ALLOCATING",
        //     "trackingURL": "",
        //     "courier": null,
        //     "timeline": null,
        //     "schedule": {
        //     "pickupTimeFrom" : "2021-04-11T12:37:28+08:00",
        //     "pickupTimeTo" : "2021-04-11T12:37:28+08:00",
        //     },
        //     "cashOnDelivery": null,
        //     "invoiceNo": "",
        //     "pickupPin": "2354",
        //     "advanceInfo": null
        // }

    }

    public function get_multi_stop_delivery_quotes($access_token, $service_type, $origin, $destination, $first_try = true) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grabfood/partner/express/v1/deliveries/quotes',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "serviceType" : "'.$service_type.'",
            "origin" : '.stripslashes($origin).',
            "destination" : '.stripslashes($destination).'
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($http_code == 401 && $first_try){
            $access_token = $this->get_access_token();
            $this->get_multi_stop_delivery_quotes($access_token, $service_type, $origin, $destination, false);
        }
        else{
            http_response_code($http_code);
            echo $response;
        }
        // example response
        // {
        //     "quotes": [
        //         {
        //         "service": {
        //             "id": 3,
        //             "type": "MULTI_STOP",
        //             "name": "GrabExpress"
        //         },
        //         "currency": {
        //             "code": "IDR",
        //             "symbol": "Rp",
        //             "exponent": 2
        //         },
        //         "origin": [
        //             {
        //             "address": "1 IJK View, Singapore 018936",
        //             "keywords": "",
        //             "coordinates": {
        //                 "latitude": 1.2345678,
        //                 "longitude": 3.4567890
        //             },
        //             cityCode": ""
        //             }
        //         ],
        //         "destination": [
        //             {
        //             "address": "1 ABC St, Singapore 078881",
        //             "keywords": "",
        //             "coordinates": {
        //                 "latitude": 1.2345876,
        //                 "longitude": 3.4567098
        //             },
        //             "amount": 47850,
        //             "estimatedTimeline": {
        //                 "pickup": "2021-06-24T11:32:11Z",
        //                 "dropoff": "2021-06-24T12:19:24Z"
        //             },
        //             "packages": [
        //                 {
        //                 "name": "pkg 1",
        //                 "description": "pkg 1",
        //                 "quantity": 1,
        //                 "price": 0,
        //                 "invoiceNo": "1235",
        //                 "dimensions": {
        //                     "height": 10,
        //                     "width": 10,
        //                     "depth": 10,
        //                     "weight": 10
        //                 }
        //                 }
        //             ],
        //             "cityCode": ""
        //             },
        //             {
        //             "address": "1 BCD St, Singapore 078882",
        //             "keywords": "",
        //             "coordinates": {
        //                 "latitude": 1.2345768,
        //                 "longitude": 3.4567980
        //             },
        //             "amount": 47850,
        //             "estimatedTimeline": {
        //                 "pickup": "2021-06-24T11:32:11Z",
        //                 "dropoff": "2021-06-24T12:19:24Z"
        //             },
        //             "packages": [
        //                 {
        //                 "name": "pkg 1",
        //                 "description": "pkg 1",
        //                 "quantity": 1,
        //                 "price": 1000,
        //                 "invoiceNo": "1234",
        //                 "dimensions": {
        //                     "height": 10,
        //                     "width": 10,
        //                     "depth": 10,
        //                     "weight": 10
        //                 }
        //                 }
        //             ],
        //             "cityCode": ""
        //             }
        //         ],
        //         "distance": 21748
        //         }
        //     ]
        // }

    }

    public function create_multi_stop_delivery_request($access_token, $merchant_order_id, $service_type, $origin, $destination, $first_try = true) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grabfood/partner/express/v1/deliveries',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "merchantOrderID" : "'.$merchant_order_id.'",
            "serviceType" : "'.$service_type.'",
            "paymentMethod" : "CASHLESS",
            "origin" : '.stripslashes($origin).',
            "destination" : '.stripslashes($destination).'
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($http_code == 401 &&  $first_try){
            $access_token = $this->get_access_token();
            $this->create_multi_stop_delivery_request($access_token, $merchant_order_id, $service_type, $origin, $destination, false);
        }
        else{
            http_response_code($http_code);
            echo $response;
        }

        // example response
        // {
        //     "merchantOrderID": "MSD-2021-05-10-003",
        //     "paymentMethod": "CASHLESS",
        //     "advanceInfo": "",
        //     "quote": {
        //         "service": {
        //             "id": 3,
        //             "type": "MULTI_STOP",
        //             "name": "GrabExpress"
        //         },
        //         "currency": {
        //             "code": "IDR",
        //             "symbol": "Rp",
        //             "exponent": 2
        //         },
        //         "origin": [
        //             {
        //                 "address": "1 IJK View, Singapore 018936",
        //                 "keywords": "",
        //                 "coordinates": {
        //                     "latitude": 1.2345678,
        //                     "longitude": 3.4567890
        //                 },
        //                 "extra": "",
        //                 "firstName": "Jon",
        //                 "lastName": "Bon",
        //                 "title": "",
        //                 "companyName": "",
        //                 "email": "ijk@gmail.com",
        //                 "phone": "6595160778",
        //                 "smsEnabled": false,
        //                 "instruction": "",
        //                 "cityCode": ""
        //             }
        //         ],
        //         "destination": [
        //             {
        //                 "address": "1 ABC St, Singapore 078882",
        //                 "keywords": "",
        //                 "coordinates": {
        //                     "latitude": 1.2345876,
        //                     "longitude": 3.4567098
        //                 },
        //                 "extra": "",
        //                 "firstName": "Son",
        //                 "lastName": "Bon",
        //                 "title": "",
        //                 "companyName": "",
        //                 "email": "abc@gmail.com",
        //                 "phone": "6596150778",
        //                 "smsEnabled": false,
        //                 "instruction": "",
        //                 "amount": 43500,
        //                 "estimatedTimeline": {
        //                     "pickup": "2021-05-09T17:10:09Z",
        //                     "dropoff": "2021-05-09T17:57:30Z"
        //                 },
        //                 "deliveryID": "MS-3-0FESB7PJX5ESFQJQB5X2",
        //                 "status": "ALLOCATING",
        //                 "packages": [
        //                     {
        //                         "name": "pkg 1",
        //                         "description": "pkg 1",
        //                         "quantity": 1,
        //                         "price": 0,
        //                         "invoiceNo": "1235",
        //                         "dimensions": {
        //                             "height": 10,
        //                             "width": 10,
        //                             "depth": 10,
        //                             "weight": 10
        //                         }
        //                     }
        //                 ],
        //                 "cityCode": "",
        //                 "pickupPin": "7732"
        //             },
        //             {
        //                 "address": "1 BCD St, Singapore 078882",
        //                 "keywords": "",
        //                 "coordinates": {
        //                     "latitude": 1.2345768,
        //                     "longitude": 3.4567980
        //                 },
        //                 "extra": "",
        //                 "firstName": "Mon",
        //                 "lastName": "Bon",
        //                 "title": "",
        //                 "companyName": "",
        //                 "email": "bcd@gmail.com",
        //                 "phone": "6591650778",
        //                 "smsEnabled": false,
        //                 "instruction": "",
        //                 "amount": 43500,
        //                 "estimatedTimeline": {
        //                     "pickup": "2021-05-09T17:10:09Z",
        //                     "dropoff": "2021-05-09T17:57:30Z"
        //                 },
        //                 "deliveryID": "MS-3-0FESB7PJX5ESFQJQB6X2",
        //                 "status": "ALLOCATING",
        //                 "packages": [
        //                     {
        //                         "name": "pkg 1",
        //                         "description": "pkg 1",
        //                         "quantity": 1,
        //                         "price": 1000,
        //                         "invoiceNo": "1234",
        //                         "dimensions": {
        //                             "height": 10,
        //                             "width": 10,
        //                             "depth": 10,
        //                             "weight": 10
        //                         }
        //                     }
        //                 ],
        //                 "cityCode": "",
        //                 "pickupPin": "7001"
        //             }
        //         ],
        //         "distance": 21748
        //     }
        // }

    }

    public function get_delivery_details($access_token, $delivery_id, $first_try = true) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express-sandbox/v1/deliveries/' . $delivery_id,
        // CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express/v1/deliveries/' . $delivery_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($http_code == 401 && $first_try){
            $access_token = $this->get_access_token();
            $this->get_delivery_details($access_token, $delivery_id, false);
        }
        else{
            http_response_code($http_code);
            echo $response;
        }

        // example response
        // {
        //     "deliveryID": "IN-2-00D2B1CBMWBO7JND48J5",
        //     "merchantOrderID": "053e68b3-bcbf-4493-b270-afac9c923169",
        //     "paymentMethod": "CASH",
        //     "quote": {
        //         "service": {
        //             "id": 0,
        //             "type": "INSTANT",
        //             "name": "GrabExpress"
        //         },
        //         "currency": {
        //             "code": "VND",
        //             "symbol": "₫",
        //             "exponent": 0
        //         },
        //         "amount": 29000,
        //         "estimatedTimeline": null,
        //         "distance": 5672,
        //         "origin": {
        //             "address": "1 IJK View, Singapore 018936",
        //             "keywords": "PQR Tower",
        //             "coordinates": {
        //                 "latitude": 1.2345678,
        //                 "longitude": 3.4567890
        //             }
        //         },
        //         "destination": {
        //             "address": "1 ABC St, Singapore 078881",
        //             "keywords": "XYZ Tower",
        //             "coordinates": {
        //                 "latitude": 1.2345876,
        //                 "longitude": 3.4567098
        //             }
        //         },
        //         "packages": [
        //             {
        //                 "name": "Bún bò Huế - Bát nhỏ",
        //                 "description": "Bát nhỏ. Nước trong nhé Quán",
        //                 "quantity": 2,
        //                 "price": 28000,
        //                 "dimensions": {
        //                     "weight": 1
        //                 },
        //                 "currency": {
        //                     "code": "VND",
        //                     "symbol": "₫",
        //                     "exponent": 0
        //                 }
        //             },
        //             {
        //                 "name": "Bún bò Huế - Bát lớn",
        //                 "description": "Bát lớn. Nước trong nha, nhiều hành",
        //                 "quantity": 1,
        //                 "price": 36000,
        //                 "dimensions": {
        //                     "weight": 1
        //                 },
        //                 "currency": {
        //                     "code": "VND",
        //                     "symbol": "₫",
        //                     "exponent": 0
        //                 }
        //             }
        //         ]
        //     },
        //     "sender": {
        //         "firstName": "Nguyễn Anh Nguyên",
        //         "phone": "0901232468",
        //         "smsEnabled": true,
        //         "instruction": "Đến bấm chuông \n\n--- # TIỀN CẦN ỨNG CHO NHÀ HÀNG: 86,250\n"
        //     },
        //     "recipient": {
        //         "firstName": "Nguyễn Anh Nguyên",
        //         "phone": "0901232468",
        //         "smsEnabled": true,
        //         "instruction": "Chó dữ \n\n--- THU HỘ TỪ NGƯỜI MUA: 121,000\n"
        //     },
        //     "status": "COMPLETED",
        //     "trackingURL": "",
        //     "courier": null,
        //     "timeline": {
        //         "create": "2020-04-03T05:26:36Z",
        //         "allocate": "2020-04-03T05:33:03Z",
        //         "pickup": "2020-04-03T07:22:40Z",
        //         "dropoff": "2020-04-03T09:48:52Z",
        //         "completed": "2020-04-03T09:58:52Z"
        //     },
        //     "schedule": null,
        //     "cashOnDelivery": {
        //         "enable": true,
        //         "amount": 121000
        //     },
        //     "invoiceNo": "053e68b3-bcbf-4493-b270-afac9c923169",
        //     "pickupPin": "9092",
        //     "advanceInfo": {
        //         "failedReason": "6|Could not find driver"
        //     }
        // }

    }

    public function cancel_delivery($access_token, $delivery_id, $first_try = true) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express-sandbox/v1/deliveries/' . $delivery_id,
        // CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express/v1/deliveries/' . $delivery_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($http_code == 401 &&  $first_try){
            $access_token = $this->get_access_token();
            echo $this->cancel_delivery($access_token, $delivery_id, false);
        }
        else{
            http_response_code($http_code);
            echo $response;
        }

        // example response
        // Successful call, 204 No Content

    }

    public function cancel_delivery_by_merchant_order_id($access_token, $merchant_order_id, $first_try = true) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://partner-api.stg-myteksi.com/grab-express/v1/merchant/deliveries/' . $merchant_order_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $access_token,
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if($http_code == 401 &&  $first_try){
            $access_token = $this->get_access_token();
            $this->cancel_delivery_by_merchant_order_id($access_token, $merchant_order_id, false);
        }
        else{
            http_response_code($http_code);
            echo $response;
        }

        // example response
        // Successful call, 204 No Content

    }

}