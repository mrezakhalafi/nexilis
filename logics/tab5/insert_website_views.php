<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET DATA FROM FORM

$f_pin = $_POST['f_pin'];
$store_id = $_POST['store_id'];

// INSERT VIEWS

$query = "INSERT INTO SHOP_WEBSITE (F_PIN, SHOP_CODE) VALUES ('".$f_pin."','".$store_id."')";
if (mysqli_query($dbconn, $query)){
    echo("Berhasil");
}else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
}

?>