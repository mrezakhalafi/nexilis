<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET ID SHOP

session_start();
$id_shop = $_SESSION['id_shop'];

// GET FROM DETAIL ORDERS FORM

$transaction_id = $_POST['transaction_id'];

// UPDATE PURCHASE

$query = "UPDATE PURCHASE SET STATUS = '5' WHERE TRANSACTION_ID = '".$transaction_id."'";

if (mysqli_query($dbconn, $query)){
    echo("Berhasil");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>