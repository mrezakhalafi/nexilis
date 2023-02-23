<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliobrowser();
$dbConnPalioLite = paliolite();

// insert company
$query = $dbconn->prepare("SELECT p.* FROM PAYMENT p WHERE p.STATUS LIKE '%SUCCESS%'");
$query->execute();
$records = $query->get_result();
$query->close();

$i = 0;
$array = [];

// raw history
foreach ($records as $record) {
    $array[$i] = array(
        'id'=>$record['ID'], 
        'f_pin'=>$record['F_PIN'], 
        'itemlist'=>json_decode(base64_decode($record['ITEMS'])), 
        'date'=>$record['CREATED_AT'], 
        'method'=>$record['METHOD']
    );
    $i++;
}

// echo "<pre>";
// echo print_r($array,true);
// echo "</pre>";

$store_id = $_GET['store_id'];
// $store_id = 33;

$query_store_id = $dbConnPalioLite->prepare("SELECT s.NAME FROM SHOP s WHERE s.CODE = ?");
$query_store_id->bind_param('i', $store_id);
$query_store_id->execute();
$store = $query_store_id->get_result()->fetch_assoc();
$store_name = $store['NAME'];
$query_store_id->close();

// echo $store_name;

// filter history based on merchant
$filtered_array = [];
$j = 0;
foreach($array as $arr) {
    foreach($arr['itemlist'] as $il) {
        if ($il->merchant_name == $store_name) {
            $items_arr = [];
            $k = 0;
            $price_total = 0;
            foreach($il->items as $its) {
                $items_arr[$k] = array(
                    'item_name'=>$its->itemName,
                    'item_qty'=>$its->itemQuantity,
                    'item_price_total'=>$its->itemPrice * $its->itemQuantity,
                );
                $price_total += $its->itemPrice * $its->itemQuantity;
                $k++;
            }
            $filtered_array[$j] = array (
                'id'=>$arr['id'],
                'items'=>$items_arr,
                'date'=>$arr['date'],
                'method'=>$arr['method'],
                'price_total'=>$price_total
            );
            $j++;
        }
    }
}

// echo "<pre>";
// echo print_r($filtered_array,true);
// echo "</pre>";


echo json_encode($filtered_array);