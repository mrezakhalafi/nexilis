<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

if (isset($_SESSION['f_pin'])) {
    $f_pin = $_SESSION['f_pin'];
  } else {
    $f_pin = $_POST['f_pin'];
    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();
  
    $f_pin = $getNewFPIN["F_PIN"];
    $_SESSION["f_pin"] = $f_pin;
  }
$method = $_POST['method'];
$status = $_POST['status'];
$transaction_id = md5(date('Y-m-d H:i:s') . $fpin);
$cart = json_decode(base64_decode($_POST['cart']));

foreach ($cart as $c) {
    # code...
    $p_code = $c->p_code;
    $price = $c->price;
    $amount = $c->amount;    
    $maxQty = $c->maxQty;   

    // get store products
    $query = $dbconn->prepare("SELECT SHOP_CODE FROM PRODUCT WHERE CODE = ?");
    $query->bind_param("s", $p_code);
    $query->execute();
    $merchant_id  = $query->get_result()->fetch_assoc()['SHOP_CODE'];
    $query->close();

    $finalQty = $maxQty - $amount;

    // update product stock
    $updSql = "UPDATE PRODUCT SET QUANTITY = $finalQty WHERE CODE = '$p_code'";
    $query = $dbconn->prepare($updSql);
    $query->execute();
    $query->close();

    // insert to purchase table
    // check if item is Post or product
    if (isset($c->isPost)) {      
        $query = $dbconn->prepare("INSERT INTO PURCHASE (TRANSACTION_ID, MERCHANT_ID, PRODUCT_ID, POST_ID, PRICE, AMOUNT, METHOD, FPIN) VALUES (?, ?, NULL, ?, ?, ?, ?, ?)");
        $query->bind_param("sssiiss", $transaction_id, $merchant_id, $p_code, $price, $amount, $method, $fpin);
        $query->execute();
        $query->close();
    } else {
        $query = $dbconn->prepare("INSERT INTO PURCHASE (TRANSACTION_ID, MERCHANT_ID, PRODUCT_ID, POST_ID, PRICE, AMOUNT, METHOD, FPIN) VALUES (?, ?, ?, NULL, ?, ?, ?, ?)");
        $query->bind_param("sssiiss", $transaction_id, $merchant_id, $p_code, $price, $amount, $method, $fpin);
        $query->execute();
        $query->close();
    }
}

// insert to notification table
$query_shop_owner = $dbconn->prepare("SELECT CREATED_BY FROM SHOP WHERE CODE = '$merchant_id'");
$query_shop_owner->execute();
$fpin_shop_owner = $query_shop_owner->get_result()->fetch_assoc()['CREATED_BY'];
$query_shop_owner->close();

$type = '2';
$time = time() * 1000;
$notif_id_user = $fpin . $time;
$notif_id_merchant = $fpin_shop_owner . $time;
$query = $dbconn->prepare("INSERT INTO USER_NOTIFICATION (NOTIF_ID, TYPE, F_PIN, ENTITY_ID, TIME) VALUES (?, ?, ?, ?, ?)");
$query->bind_param("ssssi", $notif_id_user, $type, $fpin, $transaction_id, $time);
$query->execute();
$query->close();

$query = $dbconn->prepare("INSERT INTO USER_NOTIFICATION (NOTIF_ID, TYPE, F_PIN, ENTITY_ID, TIME) VALUES (?, ?, ?, ?, ?)");
$query->bind_param("ssssi", $notif_id_merchant, $type, $fpin_shop_owner, $transaction_id, $time);
$query->execute();
$query->close();

// echo "success";
header("location: /nexilis/pages/payment.php?f_pin=".$fpin."&id=" . $transaction_id, true, 301);
