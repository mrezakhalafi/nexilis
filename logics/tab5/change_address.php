<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET FROM CHANGE SHIPMENT ADDRESS FORM

$name = $_POST['name'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];
$f_pin = $_POST['f_pin'];

// UPDATE USER LIST & USER LIST EXTENDED

$query_name = "UPDATE USER_LIST SET FIRST_NAME = '$name', MSISDN = '$phone_number' WHERE F_PIN = '$f_pin'";

$query = "UPDATE USER_LIST_EXTENDED SET ADDRESS = '$address' WHERE F_PIN = '$f_pin'";

if (mysqli_query($dbconn, $query_name) && mysqli_query($dbconn, $query)){
    // header("Location: ../../pages/tab5-delivery-address.php");
    echo("<script>window.history.back();</script>");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>