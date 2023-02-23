<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

session_start();

$dbconn = paliolite();
$dbNewNus = newnus();

// $f_pin = $_REQUEST['f_pin'];
if (isset($_SESSION["f_pin"])) {
    $f_pin = $_SESSION["f_pin"];
} else {
    $f_pin = $_GET['f_pin'];

    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();

    $f_pin = $getNewFPIN["F_PIN"];
    $_SESSION["f_pin"] = $f_pin;
}

// echo $f_pin;

if (isset($_GET['p'])) {
    $be_id = $_GET['p'];
}

if (isset($f_pin)) {
    $be_or_fpin = "SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin'";

    $sql_one = "
        SELECT be.COMPANY_ID
        FROM BUSINESS_ENTITY be 
        LEFT JOIN USER_LIST ul ON ul.BE = be.ID
        WHERE ul.F_PIN = '$f_pin'
    ";

    // get comp id
    $query = $dbconn->prepare($sql_one);
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $c_id = $geoloc['COMPANY_ID'];
    $query->close();

    // $sql_one = "
    //     SELECT wf.COMPANY_LOGO AS IMAGE_ID
    //     FROM WEBFORM wf
    //     LEFT JOIN COMPANY cp ON cp.ID = wf.COMPANY_ID
    //     WHERE cp.ID = '$c_id'
    //     ORDER BY wf.CREATED_AT DESC LIMIT 1;
    // ";.

    $sql_one = "
    SELECT cp.* FROM COMPANY_INFO cp WHERE cp.COMPANY = $c_id;
    ";

    // echo $sql_one;
    $query = $dbNewNus->prepare($sql_one);
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $c_id = $geoloc['COMPANY_LOGO'];
    $query->close();
} else if (isset($be_id)) {
    $be_or_fpin = "SELECT ID FROM BUSINESS_ENTITY WHERE API = '$be_id'";
    // $sql_one = "
    // SELECT g.IMAGE_ID
    // FROM GROUPS g
    // LEFT JOIN USER_LIST ul ON ul.BE = g.BUSINESS_ENTITY
    // WHERE ul.F_PIN = '$f_pin'
    // ";
    $sql_one = "
    SELECT ci.COMPANY_LOGO AS IMAGE_ID
FROM COMPANY_INFO ci
LEFT JOIN COMPANY cp ON cp.ID = ci.COMPANY
WHERE cp.API_KEY = '$be_id';
    ";
    $query = $dbNewNus->prepare($sql_one);
    $query->execute();
    $geoloc = $query->get_result()->fetch_assoc();
    $c_id = $geoloc['IMAGE_ID'];
    $query->close();
}



if ($c_id == null || $c_id == "") {
    $allPostsLogo = "../assets/img/pb_powered_nexilis.png";
} else {
    // $allPostsLogo = 'https://newuniverse.io/dashboardv2/uploads/logo/' . $c_id;

    // $sqlImage = "SELECT ul.IMAGE
    // FROM USER_LIST ul
    // LEFT JOIN USER_LIST_EXTENDED ule ON ul.F_PIN = ule.F_PIN 
    // WHERE ul.BE IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')
    // AND ule.OFFICIAL_ACCOUNT = 1";

    // $query = $dbconn->prepare($sqlImage);
    // $query->execute();
    // $getImage = $query->get_result()->fetch_assoc();
    // $query->close();


    $allPostsLogo = '/dashboardv2/uploads/logo/' . $c_id;
    // $allPostsLogo = '/filepalio/image/' . $getImage["IMAGE"];
}
?>

<ul>
    <li id="all-store" class='has-story'>
        <div class="story">
            <img draggable="false" src="<?php echo $allPostsLogo; ?>">
        </div>
        <span class="user" id="story-all-posts"></span>
    </li>
    <!-- <li id="store-nexilis" class='has-story'>
        <div class="story">
            <img src="../assets/img/nxsport_icons/nx.png">
        </div>
        <span class="user" id="story-nexilis">Nexilis</span>
    </li> -->
    <script>
        // $('#addtocart-success').on('hidden.bs.modal', function() {
        //   location.reload();
        // });
        try {
            let getLangURL = new URLSearchParams(window.location.search).get('lang');
            if (getLangURL != null) {
               
                localStorage.lang = getLangURL;

            }else{

                if (localStorage.lang != null && typeof (localStorage.lang) != undefined){
                    
                    // Do nothing

                }else{

                    localStorage.lang = 0;

                }
            }
            if (localStorage.lang == 0) {
                $('input#query').attr('placeholder', 'Search');
                $('#story-all-posts').text("All Posts");
            } else {
                $('input#query').attr('placeholder', 'Pencarian');
                $('#story-all-posts').text("Semua Post");
            }
        } catch (e) {}
    </script>
    <?php
    $shop_blacklist = array("17b0ae770cd", "239"); //isi manual 

    $showLinkless = 2;
    try {
        $query = $dbconn->prepare("SELECT `VALUE` FROM `SHOP_SETTINGS` WHERE `PROPERTY` = 'SHOW_LINKLESS_STORE'");
        $query->execute();
        $geoloc = $query->get_result()->fetch_assoc();
        $showLinkless = $geoloc['VALUE'];
        $query->close();
    } catch (\Throwable $th) {
    }

    if (!isset($_GET['horizontal_seed'])) {
        $horizontal_seed = time();
    } else {
        $horizontal_seed = $_GET['horizontal_seed'];
    }

    $sqlCategory = "SELECT COUNT(*) AS CNT_CATEGORY FROM NEXILIS_CATEGORY WHERE BE_ID IN (SELECT BE FROM USER_LIST WHERE F_PIN = '$f_pin')";
    $queCategory = $dbconn->prepare($sqlCategory);
    $queCategory->execute();
    $resCategory = $queCategory->get_result()->fetch_assoc();
    $queCategory->close();

    $isCatExist = $resCategory['CNT_CATEGORY'] > 0;

    $join = '';
    $defaultCategory = "";
    if ($isCatExist) {
        $join = 'JOIN NEXILIS_CATEGORY nc ON nc.CATEGORY_ID = p.CATEGORY';
        if (isset($_REQUEST['filter'])) {
            $filter = $_REQUEST['filter'];

            $filterArr = explode('-', $_REQUEST['filter']);

            $sql_where = 'AND (';

            $tempArr = array();

            foreach ($filterArr as $filter) {
                $tempArr[] = "p.CATEGORY = '$filter'";
            }

            $sql_where .= implode(' OR ', $tempArr);

            $sql_where .= ')';
        } else {
            // echo "nofilter";
            $dbNewNus = newnus();

            $sqlBE = "SELECT be.COMPANY_ID
        FROM BUSINESS_ENTITY be
        LEFT JOIN USER_LIST ul ON ul.BE = be.ID
        WHERE ul.F_PIN = '$f_pin'";

            $queBE = $dbconn->prepare($sqlBE);
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

            if ($resWebform['APP_URL'] === '1' || $resWebform['APP_URL'] === '2') {
                if ($resWebform['APP_URL_DEFAULT'] !== null && $resWebform['APP_URL_DEFAULT'] !== '') {

                    // $sql_where = 'AND ';
                    // $defaultCategory = $resWebform['APP_URL_DEFAULT'];
                    // $sql_where .= "(p.CATEGORY = '$defaultCategory')";
                    // $sql_where_post .= " AND (p.CATEGORY = '$defaultCategory')";
                    // if (isset($f_pin)) {
                    //     $sql_where = $sql_where . " AND ";
                    // }
                    $defaultCategory = explode(',', $resWebform['APP_URL_DEFAULT']);
                    $sql_where = " AND (";
                    $categorySplit = array();
                    foreach ($defaultCategory as $dc) {
                        // $sql_where .= "(p.CATEGORY = '$dc')";
                        // $categorySplit = "(p.CATEGORY = '$dc')";
                        array_push($categorySplit, "(p.CATEGORY = '$dc')");
                    }
                    $sql_where .= implode(" OR ", $categorySplit);
                    $sql_where .= ")";
                }
            } else if ($resWebform['CONTENT_TAB_LAYOUT'] === '1' || $resWebform['CONTENT_TAB_LAYOUT'] === '2') {
                if ($resWebform['CONTENT_TAB_DEFAULT'] !== null && $resWebform['CONTENT_TAB_DEFAULT'] !== '') {

                    // $sql_where = 'AND ';
                    // $defaultCategory = $resWebform['CONTENT_TAB_DEFAULT'];
                    // $sql_where .= "(p.CATEGORY = '$defaultCategory')";
                    // $sql_where_post .= " AND (p.CATEGORY = '$defaultCategory')";
                    // if (isset($f_pin)) {
                    //     $sql_where = $sql_where . " AND ";
                    // }
                    $defaultCategory = explode(',', $resWebform['CONTENT_TAB_DEFAULT']);
                    $sql_where = " AND (";
                    $categorySplit = array();
                    foreach ($defaultCategory as $dc) {
                        // $sql_where .= "(p.CATEGORY = '$dc')";
                        // $categorySplit = "(p.CATEGORY = '$dc')";
                        array_push($categorySplit, "(p.CATEGORY = '$dc')");
                    }
                    $sql_where .= implode(" OR ", $categorySplit);
                    $sql_where .= ")";
                }
            }

            
        }
    }

    if (isset($_REQUEST['query'])) {
        $query = $_REQUEST['query'];
        $quelike = "%" . $query . "%";
        $sql_where = $sql_where . " AND ((p.TITLE like '$quelike' OR p.DESCRIPTION like '$quelike') OR (CONCAT(s.FIRST_NAME, ' ', s.LAST_NAME) like '$quelike'))";
    }

    // echo $sql_where;
    // if (isset($_REQUEST['f_pin'])) {
    //     $sql_where .= " AND p.F_PIN = '$f_pin' OR p.F_PIN IN (SELECT fl.L_PIN from POST sp LEFT JOIN FRIEND_LIST fl on sp.F_PIN = fl.L_PIN WHERE fl.F_PIN = '$f_pin')";
    // }

    // $sql_where .= " GROUP BY (s.CODE) ORDER BY RAND($horizontal_seed)";

    // $sql = '(SELECT s.ID, s.CODE, s.THUMB_ID, s.NAME, s.IS_VERIFIED, s.IS_LIVE_STREAMING, be.ID AS BE_ID
    // FROM PRODUCT p 
    // LEFT JOIN CONTENT_CATEGORY cc ON p.CATEGORY = cc.CATEGORY 
    // LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
    // LEFT JOIN SHOP s ON p.SHOP_CODE = s.CODE
    // LEFT JOIN BUSINESS_ENTITY be ON s.PALIO_ID = be.COMPANY_ID
    // WHERE (s.IS_VERIFIED = 1 AND c.EDUCATIONAL = 3 AND p.IS_DELETED = 0 ' . $sql_filter . ')' . $sql_where . ' GROUP BY s.CODE)
    // UNION
    // (SELECT s.ID, s.CODE, s.THUMB_ID, s.NAME, s.IS_VERIFIED, s.IS_LIVE_STREAMING, be.ID AS BE_ID
    // FROM POST p 
    // LEFT JOIN CONTENT_CATEGORY cc ON p.POST_ID = cc.POST_ID 
    //   LEFT JOIN CATEGORY c ON cc.CATEGORY = c.ID 
    // LEFT JOIN SHOP s ON p.MERCHANT = s.CODE
    // LEFT JOIN BUSINESS_ENTITY be ON s.PALIO_ID = be.COMPANY_ID
    // WHERE (s.IS_VERIFIED = 1 AND c.EDUCATIONAL = 3 ' . $sql_filter_post . ')' . $sql_where . ' GROUP BY s.CODE)
    // ORDER BY RAND(' .$horizontal_seed . ');
    // ';

    
    $verified = "1";
    $friends = "1";
    $others = "0";
    $official = "1";
    
    $otherFilter = array();

    if (isset($_GET['official'])) {
        $official = $_GET['official'];
        // array_push($otherFilter, "ule.OFFICIAL_ACCOUNT = 1");
    }

    if (isset($_GET['verified'])) {
        $verified = $_GET['verified'];
        // array_push($otherFilter, "ule.OFFICIAL_ACCOUNT = 2");
    }
    
    if (isset($_GET['friends'])) {
        $friends = $_GET['friends'];
        // array_push($otherFilter, "p.F_PIN IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)");
    }

    if (isset($_GET['others'])) {
        $others = $_GET['others'];
        // array_push($otherFilter, "ule.OFFICIAL_ACCOUNT != 2 AND ule.OFFICIAL_ACCOUNT != 1 AND p.F_PIN NOT IN (SELECT L_PIN FROM FRIEND_LIST WHERE F_PIN = '$f_pin' AND `BLOCK` = 0 AND `STATUS` = 1)");
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

    $sql = "
    SELECT 
    p.POST_ID,
  s.ID, 
  s.F_PIN AS `CODE`, 
  s.IMAGE AS THUMB_ID, 
  CONCAT(s.FIRST_NAME, ' ', s.LAST_NAME) AS `NAME`, 
  ule.OFFICIAL_ACCOUNT AS IS_VERIFIED, 
  0 AS IS_LIVE_STREAMING,
  s.IMAGE AS PROFPIC, 
  be.ID AS BE_ID,
  MAX(p.SCORE) AS SCORE
FROM 
  POST p 
  LEFT JOIN USER_LIST s ON p.F_PIN = s.F_PIN AND IFNULL(s.AC, 0) = (SELECT IFNULL(AC, 0) FROM USER_LIST WHERE F_PIN = '$f_pin')
  LEFT JOIN USER_LIST_EXTENDED ule ON ule.F_PIN = s.F_PIN
  LEFT JOIN BUSINESS_ENTITY be ON s.BE = be.ID 
  " . $join . "
WHERE 
  s.BE IN (
    " . $be_or_fpin . "
)
  " . $otherFilterStr . $sql_where . "
  AND p.EC_DATE IS NULL
  AND p.IS_REMOVED != 1
  AND s.IS_CHANGED_PROFILE = 1
GROUP BY 
  s.F_PIN 
ORDER BY 
SCORE DESC;
    ";

    //     AND p.F_PIN = '024d4727a8' 
    //   OR p.F_PIN IN (
    //     SELECT 
    //       fl.L_PIN 
    //     FROM 
    //       POST sp 
    //       LEFT JOIN FRIEND_LIST fl ON sp.F_PIN = fl.L_PIN 
    //     WHERE 
    //       fl.F_PIN = '024d4727a8'
    //   ) 


    // echo $sql;

    $query = $dbconn->prepare($sql);
    $query->execute();
    $groups  = $query->get_result();
    $query->close();

    $stores_final = array();
    while ($group = $groups->fetch_assoc()) {
        if ($showLinkless == 2 || ($showLinkless == 1 && empty($group["LINK"])) || ($showLinkless == 0 && !empty($group["LINK"]))) {
            $stores_final[] = $group;
        }
    };

    $blocked_users_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_blocked_users.php');
    $blocked_users = array();
    foreach ($blocked_users_raw as $blocked_user) {
        $blocked_users[] = $blocked_user["L_PIN"];
    }

    $users_reported_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_users_reported_raw.php');
    $users_reported = array();
    foreach ($users_reported_raw as $user_reported) {
        $users_reported[] = $user_reported["F_PIN_REPORTED"];
    }

    $user_reports_arr = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_reported_users.php');

    // echo "<pre>";
    // print_r($users_reported_raw);
    // echo "</pre>";

    // echo "<pre>";
    // print_r($user_reports_arr);
    // echo "</pre>";


    for ($i = 0; $i < count($stores_final); $i++) {

        $post_id = $stores_final[$i]["POST_ID"];
        $idStore = $stores_final[$i]["ID"];
        $codeStore = $stores_final[$i]["CODE"];
        $urlStore = $stores_final[$i]["THUMB_ID"];
        $nameStore = $stores_final[$i]["NAME"];
        $is_verified = $stores_final[$i]["IS_VERIFIED"];
        $is_live_streaming = $stores_final[$i]["IS_LIVE_STREAMING"];
        $imgs = $stores_final[$i]["PROFPIC"];

        if (in_array($codeStore, $shop_blacklist)) {
            continue;
        }

        if (in_array($codeStore, $blocked_users)) {
            continue;
        }

        // echo $codeStore . ' ';
        // echo $user_reports_arr[$codeStore];
        if ((in_array($codeStore, $user_reports_arr) && $user_reports_arr[$codeStore]['TOTAL_REPORTS'] >= 100) || in_array($codeStore, $users_reported)) {
            continue;
        }

        $imgs = explode('|', $urlStore);
        if ($imgs[0] == null || strlen($imgs[0]) == 0) {
            $thumb = '/nexilis/assets/img/ic_person_boy.png';
        } else {
            if (substr($imgs[0], 0, 5) !== "https") {
                $thumb = "/filepalio/image/" . $imgs[0];
            } else {
                $thumb = $imgs[0];
            }
        }


        $lazy = "";

        if ($i > 5) {
            $lazy = " loading='lazy'";
        }

        echo '<li id="store-' . $codeStore .  '" class="has-story">';
        // echo $post_id;
        // echo "<a href='timeline.php?store_id=" . $idStore . "'>";
        echo "<div class='story'>";
        echo "<img draggable=\"false\" src='$thumb' $lazy>";

        if ($is_live_streaming > 0) {
            // echo '<div class="icon-live">';
            echo '<img class="icon-live" src="/nexilis/assets/img/live_indicator.png"/>';
            // echo '</div>';
        }

        echo "</div>";
        // echo "</a>";
        if ($is_verified == 2) { // verified green
            echo "<span class='user'><img src='/nexilis/assets/img/ic_verified_flag.png'/>" . $nameStore . "</span>";
        } else if ($is_verified == 1 || $is_verified == 3) { //official blue
            echo "<span class='user'><img src='/nexilis/assets/img/ic_official_flag.webp'/>" . $nameStore . "</span>";
        }else {
            echo "<span class='user'>" . $nameStore . "</span>";
        }
        echo "</li>";
    }


    ?>
</ul>