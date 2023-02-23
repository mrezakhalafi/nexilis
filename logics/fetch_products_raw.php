<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

session_start();

$dbconn = paliolite();

$showLinkless = 2;
try {
    $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'SHOW_LINKLESS_STORE'");
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $showLinkless = $geoloc['VALUE'];
    $query->close();
} catch (\Throwable $th) {
}

if (!isset($store_id) && isset($_REQUEST['store_id'])) {
    $store_id = $_REQUEST['store_id'];
}
if (isset($_SESSION['f_pin'])) {
    $f_pin = $_SESSION['f_pin'];
} else {
    $f_pin = $_REQUEST['f_pin'];
    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();

    $f_pin = $getNewFPIN["F_PIN"];
    $_SESSION["f_pin"] = $f_pin;
}
if (!isset($que) && isset($_REQUEST['query'])) {
    $que = $_REQUEST['query'];
}

if (isset($_GET['p'])) {
    $be_id = $_GET['p'];
}

$limit = 10;
$offset = 0;
$seed = $_GET['seed'];
// $seed = '1234';

if (isset($_GET['limit'])) {
    $limit = (intval($_GET['limit']) != 0) ? $_GET['limit'] : 10;
}
if (isset($_GET['offset'])) {
    $offset = (intval($_GET['offset']) != 0) ? $_GET['offset'] : 0;
}

if (isset($_REQUEST['filter'])) {
    $filter = $_REQUEST['filter'];
}

$sql_where = '';
$sql_where_post = '';
$sql = "";

if (isset($que) || isset($store_id)) {
    $sql_where = $sql_where . " WHERE ";
}
if (isset($store_id)) {
    $sql_where_post = " AND p.F_PIN = '$store_id'";
    // if(isset($que)){
    //     $sql_where_post = $sql_where_post . " AND ";
    // }
}

$verified = "1";
$friends = "1";
$others = "0";
$official = "1";

$otherFilter = array();

if (isset($_GET["official"])) {
    $official = $_GET["official"];
}
if (isset($_GET["verified"])) {
    $verified = $_GET["verified"];
}
if (isset($_GET["friends"])) {
    $friends = $_GET["friends"];
}
if (isset($_GET["others"])) {
    $others = $_GET["others"];
}

array_push($otherFilter, "p.F_PIN = '$f_pin'");
if ($official == "1") {
    array_push($otherFilter, "ule.OFFICIAL_ACCOUNT = 1");
    array_push($otherFilter, "ule.OFFICIAL_ACCOUNT = 3");
}

if ($verified == "1") {
    array_push($otherFilter, "ule.OFFICIAL_ACCOUNT = 2");
}

if ($friends == "1") {
    array_push($otherFilter, "p.F_PIN IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)");
}

if ($others == "1") {
    array_push($otherFilter, "ule.OFFICIAL_ACCOUNT != 2 AND ule.OFFICIAL_ACCOUNT != 1 AND p.F_PIN NOT IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)");
}

$otherFilterStr = '';

if ($official == "1" || $verified == "1" || $friends == "1" || $others == "1") {
    $otherFilterStr = ' AND (' . implode(' OR ', $otherFilter) . ')';
}

$sqlCategory = "SELECT COUNT(*) AS CNT_CATEGORY FROM NEXILIS_CATEGORY WHERE BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')";
$queCategory = $dbconn->prepare($sqlCategory);
$queCategory->execute();
$resCategory = $queCategory->get_result()->fetch_assoc();
$queCategory->close();

$isCatExist = $resCategory['CNT_CATEGORY'] > 0;

$join = '';
if ($isCatExist) {
    $join = 'JOIN NEXILIS_CATEGORY nc ON nc.CATEGORY_ID = p.CATEGORY';
}
if (isset($filter)) {
    // $sql_where = $sql_where . "p.CATEGORY = '$filter'";
    // if(isset($f_pin)){
    //     $sql_where = $sql_where . " AND ";
    // }
    $filter = $_REQUEST['filter'];

    $filterArr = explode('-', $_REQUEST['filter']);

    $sql_where .= '(';
    $sql_where_post .= ' AND (';

    $tempArr = array();

    foreach ($filterArr as $filter) {
        $tempArr[] = "p.CATEGORY = '$filter'";
    }

    $sql_where .= implode(' OR ', $tempArr);
    $sql_where_post .= implode(' OR ', $tempArr);

    $sql_where .= ')';
    $sql_where_post .= ')';
    if (isset($f_pin)) {
        $sql_where = $sql_where . " AND ";
    }
} else {
    $dbNewNus = newnus();

    $sqlBE = "SELECT be.COMPANY_ID
    FROM BUSINESS_ENTITY be
    LEFT JOIN USER_LIST ul ON ul.BE = be.ID
    WHERE ul.F_PIN = '$f_pin'";

    $queBE = $dbconn->prepare($sqlBE);

    // echo $sqlBE;
    $queBE->execute();
    $resBE = $queBE->get_result()->fetch_assoc();
    $c_id = $resBE["COMPANY_ID"];
    $queBE->close();

    $sqlWebform = "SELECT * FROM WEBFORM WHERE COMPANY_ID = $c_id AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
    $queWebform = $dbNewNus->prepare($sqlWebform);
    $queWebform->execute();
    $resWebform = $queWebform->get_result()->fetch_assoc();
    $queWebform->close();

    // $defaultCategory = $resWebform['APP_URL_DEFAULT'];

    if ($resWebform['APP_URL'] === '0' || $resWebform['APP_URL'] === '2') {
        if ($resWebform['APP_URL_DEFAULT'] !== null && $resWebform['APP_URL_DEFAULT'] !== '') {
            $defaultCategory = explode(',', $resWebform['APP_URL_DEFAULT']);
            $sql_where_post .= " AND (";
            $categorySplit = array();
            foreach ($defaultCategory as $dc) {
                $sql_where .= "(p.CATEGORY = '$dc')";
                // $categorySplit = "(p.CATEGORY = '$dc')";
                array_push($categorySplit, "(p.CATEGORY = '$dc')");
            }
            $sql_where_post .= implode(" OR ", $categorySplit);
            $sql_where_post .= ")";
            if (isset($f_pin)) {
                $sql_where = $sql_where . " AND ";
            }
        }
    } else if ($resWebform['CONTENT_TAB_LAYOUT'] === '0' || $resWebform['CONTENT_TAB_LAYOUT'] === '2') {
        if ($resWebform['CONTENT_TAB_DEFAULT'] !== null && $resWebform['CONTENT_TAB_DEFAULT'] !== '') {
            $defaultCategory = explode(',', $resWebform['CONTENT_TAB_DEFAULT']);
            $sql_where_post .= " AND (";
            $categorySplit = array();
            foreach ($defaultCategory as $dc) {
                $sql_where .= "(p.CATEGORY = '$dc')";
                // $categorySplit = "(p.CATEGORY = '$dc')";
                array_push($categorySplit, "(p.CATEGORY = '$dc')");
            }
            $sql_where_post .= implode(" OR ", $categorySplit);
            $sql_where_post .= ")";
            if (isset($f_pin)) {
                $sql_where = $sql_where . " AND ";
            }
        }
    }
}
if (isset($que)) {

    $quelike = "%" . $que . "%";
    $sql_where = $sql_where . "(l.TITLE like '$quelike' OR l.DESC like '$quelike')";
    $sql_where_post = $sql_where_post . " AND ((p.TITLE like '$quelike' OR p.DESCRIPTION like '$quelike') OR (CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) LIKE '$quelike'))";
}

$is_filter_score = 0;

if ($is_filter_score == 1) {
    $is_filter_score = " ORDER BY p.SCORE DESC ";
} else if ($is_filter_score == 0) {
    $is_filter_score = " ORDER BY RAND($seed) ";
}

$sql_where = $sql_where . $is_filter_score . " LIMIT $limit OFFSET $offset";
$sql_where_post = $sql_where_post . $is_filter_score . " LIMIT $limit OFFSET $offset";


$be_or_fpin = "";

if (isset($f_pin)) {
    $be_or_fpin = "SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'";
} else if (isset($be_id)) {
    $be_or_fpin = "SELECT ID FROM BUSINESS_ENTITY WHERE API = '$be_id'";
}

// p.TOTAL_COMMENT AS TOTAL_COMMENTS, 

// p.TOTAL_LIKES,

$sql = "
    SELECT 
      p.POST_ID AS CODE, 
      p.TITLE, 
      p.DESCRIPTION, 
      p.F_PIN,
      CONCAT(ul.FIRST_NAME, ' ', ul.LAST_NAME) AS STORE_NAME,
      p.CREATED_DATE, 
      p.LINK AS STORE_LINK,
      (
        SELECT COUNT(pr.POST_ID)
        FROM POST_REACTION pr
        WHERE pr.POST_ID = p.POST_ID
        AND pr.FLAG = 1
      ) AS TOTAL_LIKES,  
      (
        SELECT COUNT(pc1.COMMENT_ID)
        FROM POST_COMMENT pc1
        LEFT JOIN POST_COMMENT pc2 ON pc1.REF_COMMENT_ID = pc2.COMMENT_ID
        WHERE pc1.POST_ID = p.POST_ID
        AND pc1.IS_DELETE IS NULL
        AND pc2.IS_DELETE IS NULL
      ) AS TOTAL_COMMENTS,
      p.REPORT,
      p.THUMB_ID AS THUMBNAIL,
      p.FILE_ID AS THUMB_ID,
      ul.IMAGE AS PROFPIC,
      ule.OFFICIAL_ACCOUNT,
      1 AS IS_POST,
      p.CATEGORY,
      p.PARENT,
      p.IS_REMOVED,
      p.SCORE,
      (
        SELECT 
          COUNT(s.CODE)
        FROM 
          SHOP s
        WHERE 
          s.CREATED_BY = p.F_PIN
        ) AS HAS_SHOP,
      (
        SELECT COUNT(bp.POST_ID)
        FROM BLOCK_POST bp
        WHERE bp.POST_ID = p.POST_ID
        AND bp.F_PIN = '$f_pin'
      ) AS IS_BLOCK_POST
    FROM 
      POST p 
      LEFT JOIN USER_LIST ul ON ul.F_PIN = p.F_PIN AND IFNULL(ul.AC, 0) = (SELECT IFNULL(AC, 0) FROM USER_LIST WHERE F_PIN = '$f_pin')
      LEFT JOIN USER_LIST_EXTENDED ule ON ule.F_PIN = ul.F_PIN
      LEFT JOIN ACCESS_CATEGORY acc ON ul.AC = acc.ID
      " . $join . "
      WHERE ul.BE IN (
		" . $be_or_fpin . "
      )
     AND p.EC_DATE IS NULL
     AND p.IS_REMOVED != 1
      " . $otherFilterStr .
    $sql_where_post . "
";

// echo $sql;

$query = $dbconn->prepare($sql);

// SELECT USER PROFILE
$query->execute();
$groups  = $query->get_result();
$query->close();


$news_fpin = array("02f041b31a", "02d7c16d7a");
$rows = array();
while ($group = $groups->fetch_assoc()) {
    if ($showLinkless == 2 || ($showLinkless == 1 && empty($group["LINK"])) || ($showLinkless == 0 && !empty($group["LINK"])) && (!in_array($group["F_PIN"], $news_fpin))) {
        $rows[] = $group;
        // echo 'asda ';
    }
};


// echo "<pre>";
// print_r($rows);
// echo "</pre>";
return $rows;
