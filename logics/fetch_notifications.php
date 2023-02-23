<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = paliolite();

$f_pin = $_GET['f_pin'];
// $f_pin = '0217ff7003';

$sql = "SELECT * FROM `USER_NOTIFICATION` WHERE F_PIN = '$f_pin' ORDER BY TIME DESC";

$query = $dbconn->prepare($sql);
$query->execute();
$results = $query->get_result();
$query->close();

$rows = array();
if ($results->num_rows > 0) {
    while ($res = $results->fetch_assoc()) {
        $rows[] = $res;
    };
} else {
    // no new notifs
}

$notifs_activity = array();
$notifs_orders = array();

$i = 0;
$j = 0;


foreach ($rows as $row) {
    if ($row["TYPE"] == 1) { // follow
        $l_pin = $row["ENTITY_ID"];
        $sql = $dbconn->prepare("SELECT FIRST_NAME, LAST_NAME FROM USER_LIST WHERE F_PIN = '$l_pin'");
        $sql->execute();
        $user = $sql->get_result()->fetch_assoc();
        $notifs_activity[$j]['notif_id'] = $row['NOTIF_ID'];
        $notifs_activity[$j]['read_status'] = $row['READ_STATUS'];
        $notifs_activity[$j]['follower_name'] = $user['FIRST_NAME'] . ' ' . $user['LAST_NAME'];
        $notifs_activity[$j]["time"] = date("d M", ($row["TIME"] / 1000));
        $j++;
    } elseif ($row["TYPE"] == 2) { // payment
        $trx_id = $row["ENTITY_ID"];
        $que = $dbconn->prepare("SELECT pu.TRANSACTION_ID, pr.THUMB_ID, pu.FPIN AS BUYER, s.CODE AS SHOP_CODE, s.CREATED_BY AS MERCHANT
        FROM PURCHASE pu
        LEFT JOIN PRODUCT pr ON pu.PRODUCT_ID = pr.CODE
        LEFT JOIN SHOP s ON pu.MERCHANT_ID = s.CODE
        WHERE pu.TRANSACTION_ID = '$trx_id'");
        $que->execute();
        $data = $que->get_result()->fetch_assoc();
        $que->close();
        $notifs_orders[$i]['notif_id'] = $row['NOTIF_ID'];
        $notifs_orders[$i]['read_status'] = $row['READ_STATUS'];
        $notifs_orders[$i]['trx_id'] = $trx_id;
        $notifs_orders[$i]['time'] = date("d M", ($row["TIME"] / 1000));
        $notifs_orders[$i]['buyer'] = $data['BUYER'];
        $notifs_orders[$i]['merchant_fpin'] = $data['MERCHANT'];
        $notifs_orders[$i]['merchant_code'] = $data['SHOP_CODE'];
        $notifs_orders[$i]['product_thumb'] = $data['THUMB_ID'];
        $notifs_orders[$i]['state'] = $row["TYPE"];
        $i++;
    } elseif ($row["TYPE"] == 3) { // product sent
        $trx_id = $row["ENTITY_ID"];
        $que = $dbconn->prepare("SELECT pu.TRANSACTION_ID, pr.THUMB_ID, pu.FPIN AS BUYER, s.CODE AS SHOP_CODE, s.CREATED_BY AS MERCHANT
        FROM PURCHASE pu
        LEFT JOIN PRODUCT pr ON pu.PRODUCT_ID = pr.CODE
        LEFT JOIN SHOP s ON pu.MERCHANT_ID = s.CODE
        WHERE pu.TRANSACTION_ID = '$trx_id'");
        $que->execute();
        $data = $que->get_result()->fetch_assoc();
        $que->close();
        $notifs_orders[$i]['notif_id'] = $row['NOTIF_ID'];
        $notifs_orders[$i]['read_status'] = $row['READ_STATUS'];
        $notifs_orders[$i]['trx_id'] = $trx_id;
        $notifs_orders[$i]['time'] = date("d M", ($row["TIME"] / 1000));
        $notifs_orders[$i]['buyer'] = $data['BUYER'];
        $notifs_orders[$i]['merchant_fpin'] = $data['MERCHANT'];
        $notifs_orders[$i]['merchant_code'] = $data['SHOP_CODE'];
        $notifs_orders[$i]['product_thumb'] = $data['THUMB_ID'];
        $notifs_orders[$i]['state'] = $row["TYPE"];
        $i++;
    } elseif ($row["TYPE"] == 4) { // product sent
        $trx_id = $row["ENTITY_ID"];
        $que = $dbconn->prepare("SELECT pu.TRANSACTION_ID, pr.THUMB_ID, pu.FPIN AS BUYER, s.CODE AS SHOP_CODE, s.CREATED_BY AS MERCHANT
        FROM PURCHASE pu
        LEFT JOIN PRODUCT pr ON pu.PRODUCT_ID = pr.CODE
        LEFT JOIN SHOP s ON pu.MERCHANT_ID = s.CODE
        WHERE pu.TRANSACTION_ID = '$trx_id'");
        $que->execute();
        $data = $que->get_result()->fetch_assoc();
        $que->close();
        $notifs_orders[$i]['notif_id'] = $row['NOTIF_ID'];
        $notifs_orders[$i]['read_status'] = $row['READ_STATUS'];
        $notifs_orders[$i]['trx_id'] = $trx_id;
        $notifs_orders[$i]['time'] = date("d M", ($row["TIME"] / 1000));
        $notifs_orders[$i]['buyer'] = $data['BUYER'];
        $notifs_orders[$i]['merchant_fpin'] = $data['MERCHANT'];
        $notifs_orders[$i]['merchant_code'] = $data['SHOP_CODE'];
        $notifs_orders[$i]['product_thumb'] = $data['THUMB_ID'];
        $notifs_orders[$i]['state'] = $row["TYPE"];
        $i++;
    }
}

$compile_array = array(
    'notifs_activity' => $notifs_activity,
    'notifs_orders' => $notifs_orders
);

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

echo json_encode(utf8ize($compile_array));

?>