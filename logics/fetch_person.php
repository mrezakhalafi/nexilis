<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

if(isset($_REQUEST['f_pin'])){
    $f_pin = $_REQUEST['f_pin'];
}

$query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN='$f_pin' LIMIT 1");
$query->execute();
$groups  = $query->get_result();
$query->close();

$rows = array();
while ($group = $groups->fetch_assoc()) {
    $rows[] = $group;
};

echo json_encode($rows);

// return $rows;
