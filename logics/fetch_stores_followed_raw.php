<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$f_pin = $_GET['f_pin'];
// SELECT USER PROFILE
if(!isset($f_pin) && isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
$rows = array();
if (isset($f_pin)) {
    $query = $dbconn->prepare("SELECT STORE_CODE FROM SHOP_FOLLOW WHERE F_PIN = ?");
    $query->bind_param("s", $f_pin);
    // SELECT USER PROFILE
    $query->execute();
    $groups  = $query->get_result();
    $query->close();
    
    while ($group = $groups->fetch_assoc()) {
        $rows[] = $group;
    };
};
return $rows;
?>