<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

session_start();

$f_pin = $_SESSION['f_pin'];
$offset = $_POST['offset'];
$limit = $_POST['limit'];
$offset = $_POST['offset'];
$verified = 0;
$friends = 0;
$others = 0;

if (isset($f_pin)) {
    $be_or_fpin = "SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'";
} else if (isset($be_id)) {
    $be_or_fpin = "SELECT ID FROM BUSINESS_ENTITY WHERE API = '$be_id'";
}

$sql_where_post = '';
$sql = "";

$otherFilter = array();
if (isset($_POST['verified']) && $_POST['verified'] == 1) {
    $verified = $_POST['verified'];
    array_push($otherFilter, "ule.OFFICIAL_ACCOUNT = 2");
}

if (isset($_POST['friends']) && $_POST['friends'] == 1) {
    $friends = $_POST['friends'];
    array_push($otherFilter, "POST.F_PIN IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)");
}

if (isset($_POST['others']) && $_POST['others'] == 1) {
    $others = $_POST['others'];
    array_push($otherFilter, "ule.OFFICIAL_ACCOUNT != 2 AND POST.F_PIN NOT IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)");
}

$otherFilterStr = '';

if ($verified == 1 || $friends == 1 || $others == 1) {
    $otherFilterStr = ' AND (' . implode(' OR ', $otherFilter) . ')';
}

if (isset($_POST['filter'])) {

    $filter = $_POST['filter'];

    $filterArr = explode('-', $_REQUEST['filter']);

    $sql_where .= '(';
    $sql_where_post .= ' AND (';

    $tempArr = array();

    foreach ($filterArr as $filter) {
        $tempArr[] = "POST.CATEGORY = '$filter'";
    }

    $sql_where .= implode(' OR ', $tempArr);
    $sql_where_post .= implode(' OR ', $tempArr);

    $sql_where .= ')';
    $sql_where_post .= ')';
    if (isset($f_pin)) {
        $sql_where = $sql_where . " AND ";
    }
}

if (isset($_POST['query'])) {
    $que = $_POST['query'];
    $quelike = "%" . $que . "%";
    $sql_where_post = $sql_where_post . " AND ((POST.TITLE like '$quelike' OR POST.DESCRIPTION like '$quelike') OR (USER_LIST.FIRST_NAME LIKE '$quelike' OR USER_LIST.LAST_NAME LIKE '$quelike'))";
}

$sql_where_post = $sql_where_post . " GROUP BY POST.F_PIN ORDER BY POST.SCORE DESC LIMIT $limit OFFSET $offset";
$sql_not_blocked_user = "SELECT * FROM BLOCK_USER WHERE F_PIN = '$f_pin' AND L_PIN = POST.F_PIN";
$sql_not_blocked_content = "SELECT * FROM BLOCK_POST WHERE F_PIN = '$f_pin' AND POST_ID = POST.POST_ID";

$sql = "SELECT POST.TITLE,
        POST.POST_ID, 
        POST.FILE_ID, 
        POST.THUMB_ID,
        POST.TOTAL_LIKES, 
        POST.CATEGORY,
        ule.OFFICIAL_ACCOUNT,
        CONCAT(USER_LIST.FIRST_NAME, ' ', USER_LIST.LAST_NAME) AS USERNAME, 
        (SELECT COUNT(FOLLOW_LIST.L_PIN) 
            FROM FOLLOW_LIST 
            WHERE USER_LIST.F_PIN = FOLLOW_LIST.L_PIN) 
            AS FOLLOW_SHOP, 
        (SELECT COUNT(POST_COMMENT.POST_ID) 
            FROM POST_COMMENT 
            WHERE POST.POST_ID = POST_COMMENT.POST_ID) 
            AS COMMENT_USER, 
        (SELECT COUNT(REPORT_POST.POST_ID) 
            FROM REPORT_POST 
            WHERE POST.POST_ID = REPORT_POST.POST_ID) 
            AS REPORT_POST, 
        (SELECT COUNT(REPORT_USER.F_PIN_REPORTED) 
            FROM REPORT_USER 
            WHERE POST.F_PIN = REPORT_USER.F_PIN_REPORTED) 
            AS REPORT_USER, 
        USER_LIST.F_PIN AS USER_PIN 
        FROM POST 
        LEFT JOIN USER_LIST ON USER_LIST.F_PIN = POST.F_PIN AND IFNULL(USER_LIST.AC, 0) = (SELECT IFNULL(AC, 0) FROM USER_LIST WHERE F_PIN = '$f_pin')
        LEFT JOIN USER_LIST_EXTENDED ule ON ule.F_PIN = USER_LIST.F_PIN                                
        LEFT JOIN FOLLOW_LIST 
            ON USER_LIST.F_PIN = FOLLOW_LIST.L_PIN,
        (SELECT F_PIN, MAX(SCORE) SCORE FROM POST WHERE F_PIN IN (SELECT F_PIN FROM USER_LIST WHERE BE IN (" . $be_or_fpin . ")) AND EC_DATE IS NULL AND FILE_ID LIKE '%.mp4%' GROUP BY F_PIN) PS
        WHERE FILE_ID LIKE '%.mp4%' 
        AND POST.SCORE = PS.SCORE
        AND POST.F_PIN = PS.F_PIN
        AND POST.EC_DATE IS NULL

        AND USER_LIST.BE IN (" . $be_or_fpin . ")
        AND NOT EXISTS (" .$sql_not_blocked_user. ")
        AND NOT EXISTS (" .$sql_not_blocked_content. ")"
        . $otherFilterStr
        . $sql_where_post;

// echo $sql;

$query = $dbconn->prepare($sql);
$query->execute();
$userData = $query->get_result();
$query->close();

$arrayVid = [];
$arrayLike = [];
$arrayComment = [];

// CHECK LIKES

$query = $dbconn->prepare("SELECT POST_ID FROM POST_REACTION WHERE F_PIN = '$f_pin' AND FLAG = 1");
$query->execute();
$allLikes = $query->get_result();
$query->close();

$likes = array();
while ($row = $allLikes->fetch_assoc()) {
    array_push($likes, $row["POST_ID"]);
}

// CHECK COMMENT

$query = $dbconn->prepare("SELECT POST_ID FROM POST_COMMENT WHERE F_PIN = '$f_pin'");
$query->execute();
$allComments = $query->get_result();
$query->close();

$comments = array();
while ($row = $allComments->fetch_assoc()) {
    array_push($comments, $row["POST_ID"]);
}

// CHECK FOLLOW

$query = $dbconn->prepare("SELECT L_PIN FROM FOLLOW_LIST WHERE F_PIN = '$f_pin'");
$query->execute();
$allFollow = $query->get_result();
$query->close();

$follow = array();
while ($row = $allFollow->fetch_assoc()) {
    array_push($follow, $row["L_PIN"]);
}

// LOOP AND SET TO ARRAY

while ($row = $userData->fetch_assoc()) {

    $row['IS_LS'] = 0;

    if (in_array($row['POST_ID'], $likes)) {

        $row["IS_LIKED"] = 1;
    } else {

        $row["IS_LIKED"] = 0;
    }

    if (in_array($row['POST_ID'], $comments)) {

        $row["IS_COMMENT"] = 1;
    } else {

        $row["IS_COMMENT"] = 0;
    }

    if (in_array($row['USER_PIN'], $follow)) {

        $row["IS_FOLLOW"] = 1;
    } else {

        $row["IS_FOLLOW"] = 0;
    }

    if ($row['REPORT_POST'] >= 100){
        continue;
    }

    if ($row['REPORT_USER'] >= 100){
        continue;
    }

    $arrayVid[] = $row;
}

if (isset($arrayVid)) {
    print_r(json_encode($arrayVid));
} else {
    echo ("");
}
