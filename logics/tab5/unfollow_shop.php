<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET FROM TAB 5 FOLLOWING FORM

$f_pin = $_POST['f_pin'];
$shop_id = $_POST['shop_id'];

// DELETE FROM DATABASE

$query = "DELETE FROM SHOP_FOLLOW WHERE F_PIN = '".$f_pin."' AND STORE_CODE = '".$shop_id."'";

if (mysqli_query($dbconn, $query)){
    header("Location: ../../pages/tab5-following.php");
}else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
}

?>