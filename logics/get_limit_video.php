<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbconn = paliolite();

session_start();

if (isset($_GET['f_pin'])) {
    $f_pin = $_GET['f_pin'];
    $_SESSION['user_f_pin'] = $f_pin;
} else if (isset($_SESSION['user_f_pin'])) {
    $f_pin = $_SESSION['user_f_pin'];
}

$f_pin = $_POST['f_pin'];
$f_pin = $_SESSION['f_pin'];

$limit = 5;

if (isset($f_pin)) {
    $be_or_fpin = "SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'";
} else if (isset($be_id)) {
    $be_or_fpin = "SELECT ID FROM BUSINESS_ENTITY WHERE API = '$be_id'";
}

$sql_where_post = '';
$sql = "";

if (isset($_POST['verified']) && $_POST['verified'] == 1) {
    $sql_where_post .= " AND USER_LIST_EXTENDED.OFFICIAL_ACCOUNT = 2";
}

if (isset($_POST['friends']) && $_POST['friends'] == 1) {
    $sql_where_post .= " AND POST.F_PIN IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)";
}

if (isset($_POST['others']) && $_POST['others'] == 1) {
    $sql_where_post .= " AND USER_LIST_EXTENDED.OFFICIAL_ACCOUNT = !=2 AND POST.F_PIN NOT IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)";
}

if (isset($_POST['filter'])) {
    $filter = $_POST['filter'];

    $filterArr = explode('-', $_REQUEST['filter']);

    $sql_where_post .= ' AND (';

    $tempArr = array();

    foreach ($filterArr as $filter) {
        $tempArr[] = "POST.CATEGORY = '$filter'";
    }
    $sql_where_post .= implode(' OR ', $tempArr);
    $sql_where_post .= ')';
}

if (isset($_POST['query'])) {
    $que = $_POST['query'];
    $quelike = "%" . $que . "%";
    // $sql_where = $sql_where . "(l.TITLE like '$quelike' OR l.DESC like '$quelike')";
    $sql_where_post = $sql_where_post . " AND ((POST.TITLE like '$quelike' OR POST.DESCRIPTION like '$quelike') OR (USER_LIST.FIRST_NAME LIKE '$quelike' OR USER_LIST.LAST_NAME LIKE '$quelike'))";
}

$sql_where_post = $sql_where_post . " GROUP BY POST.F_PIN";
$sql_not_blocked_user = "SELECT * FROM BLOCK_USER WHERE F_PIN = '$f_pin' AND L_PIN = POST.F_PIN";
$sql_not_blocked_content = "SELECT * FROM BLOCK_POST WHERE F_PIN = '$f_pin' AND POST_ID = POST.POST_ID";

$sql = "SELECT TITLE,
POST.POST_ID, 
POST.F_PIN
FROM POST 
LEFT JOIN USER_LIST ON USER_LIST.F_PIN = POST.F_PIN AND IFNULL(USER_LIST.AC, 0) = (SELECT IFNULL(AC, 0) FROM USER_LIST WHERE F_PIN = '$f_pin')
LEFT JOIN USER_LIST_EXTENDED ON USER_LIST.F_PIN = USER_LIST_EXTENDED.F_PIN
LEFT JOIN FOLLOW_LIST 
    ON USER_LIST.F_PIN = FOLLOW_LIST.L_PIN,
(SELECT F_PIN, MAX(SCORE) SCORE FROM POST WHERE F_PIN IN (SELECT F_PIN FROM USER_LIST WHERE BE IN (" . $be_or_fpin . ")) AND EC_DATE IS NULL AND FILE_ID LIKE '%.mp4%' GROUP BY F_PIN) PS
WHERE FILE_ID LIKE '%.mp4%' 
AND POST.SCORE = PS.SCORE
AND POST.F_PIN = PS.F_PIN
AND POST.EC_DATE IS NULL
AND USER_LIST.BE IN (" . $be_or_fpin . ")
AND NOT EXISTS (" .$sql_not_blocked_user. ")
AND NOT EXISTS (" .$sql_not_blocked_post. ")"
. $sql_where_post;

// echo $sql;

$query = $dbconn->prepare($sql);
$query->execute();
$userData = $query->get_result();
$query->close();

if (isset($userData)) {
    echo (mysqli_num_rows($userData));
} else {
    echo ("KONEKSI DB GAGAL");
}
