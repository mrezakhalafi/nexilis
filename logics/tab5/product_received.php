<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];

// GET FROM DETAIL ORDERS FORM

$transaction_id = $_POST['transaction_id'];

// TIME MILISECONDS

list($msec, $sec) = explode(' ', microtime());
$time_milli = $sec.substr($msec, 2, 3); // '1491536422147'
$notif_id = $id_shop.$time_milli;

// UPDATE PURCHASE & INSERT TO USER NOTIFICATION

$query = "UPDATE PURCHASE SET STATUS = '4' WHERE TRANSACTION_ID = '".$transaction_id."'";

$query_notif = "INSERT INTO USER_NOTIFICATION (NOTIF_ID, TYPE, F_PIN, ENTITY_ID, 
                READ_STATUS, TIME) VALUES ('".$notif_id."','4','".$id_shop."',
                '".$transaction_id."','0','".$time_milli."')";

if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $query_notif)){
    header("Location: ../../pages/tab5-receipt.php?id=".$transaction_id."&success=true");
}else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
}

?>