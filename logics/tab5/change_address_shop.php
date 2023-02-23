<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET FROM CHANGE SHIPMENT ADDRESS FORM

$name = $_POST['name'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];

$city = $_POST['city'];
$province = $_POST['province'];
$zip_code = $_POST['zip_code'];
$courier_note = $_POST['courier_note'];

$id_shop = $_POST['id_shop'];

// UPDATE SHOP & SHIPPING ADDRESS

$query_name = "UPDATE SHOP SET NAME = '$name' WHERE CODE = '$id_shop'";

$query = "UPDATE SHOP_SHIPPING_ADDRESS SET ADDRESS = '$address', PHONE_NUMBER =
            '$phone_number', CITY = '$city', PROVINCE = '$province', ZIP_CODE = 
            '$zip_code', COURIER_NOTE = '$courier_note' WHERE STORE_CODE = '$id_shop'";

if (mysqli_query($dbconn, $query_name) && mysqli_query($dbconn, $query)){
    header("Location: ../../pages/tab5-shipping.php");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>