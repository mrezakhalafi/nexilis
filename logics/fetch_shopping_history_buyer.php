<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliobrowser();
$dbConnPalioLite = paliolite();

$f_pin = $_GET['f_pin'];
// $f_pin = "02b3c7f2db";

// insert company
$query = $dbconn->prepare("SELECT p.* FROM PAYMENT p WHERE p.F_PIN = ? AND p.STATUS LIKE '%SUCCESS%'");
$query->bind_param('s', $f_pin);
$query->execute();
$records = $query->get_result();
$query->close();

$i = 0;
$array = [];

// raw history
foreach ($records as $record) {
    $all_merchants = json_decode(base64_decode($record['ITEMS']), true);
    $all_items = [];
    $l = 0;
    $price_total = 0;
    foreach($all_merchants as $mrc) {
        foreach ($mrc['items'] as $its) {
            $all_items[$l] = array(
                'item_name'=>$its['itemName'],
                'item_qty'=>$its['itemQuantity'],
                'item_price_total'=>$its['itemQuantity'] * $its['itemPrice'],
            );
            $price_total += $its['itemQuantity'] * $its['itemPrice'];
            $l++;
        }
    }
    $array[$i] = array(
        'id'=>$record['ID'], 
        'items'=>$all_items, 
        'price_total'=>$price_total,
        'date'=>$record['CREATED_AT'], 
        'method'=>$record['METHOD']
    );
    $i++;
}

// echo "<pre>";
// echo print_r($array,true);
// echo "</pre>";

echo json_encode($array);