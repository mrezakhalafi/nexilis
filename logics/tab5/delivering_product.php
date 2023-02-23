<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];
$id_user = $_SESSION["user_f_pin"];

// GET FROM DETAIL ORDERS FORM

$transaction_id = $_POST['transaction_id'];

// TIME MILISECONDS

list($msec, $sec) = explode(' ', microtime());
$time_milli = $sec.substr($msec, 2, 3); // '1491536422147'
$notif_id = $id_user.$time_milli;

// UPDATE DATABASE PURCHASE & INSERT TO DATABASE NOTIFICATIONS

$query ="UPDATE PURCHASE SET STATUS = '3' WHERE TRANSACTION_ID = '".$transaction_id."'";

$query_notif = "UPDATE USER_NOTIFICATION SET TYPE = '3' WHERE ENTITY_ID = '".$transaction_id."'";

if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $query_notif)){
    header("Location: ../../pages/tab5-order-details.php?transaction_id=".$transaction_id."");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>