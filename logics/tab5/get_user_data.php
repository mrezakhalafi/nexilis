<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET DATA FROM FORM

$f_pin = $_POST['f_pin'];

// SELECT SHOP DATA ACTIVE

$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '".$f_pin."'");
$query->execute();
$users = $query->get_result()->fetch_assoc();
$query->close();

// IF DATA EXIST RETURN DATA

if (isset($users)){
    echo(json_encode($users));
}else{
    echo("");
}

?>