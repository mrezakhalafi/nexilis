<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

if (!isset($_POST['code'])) {
    die();
}

$dbconn = paliolite();

$collection_code = $_POST['code'];
$status = $_POST['status'];
$name = $_POST['name'];
$desc = $_POST['desc'];
$f_pin = $_POST['f_pin'];
// $items = explode('|', $_POST['items']);
$items = json_decode(base64_decode($_POST['items']));

$arr = array();
foreach ($items as $it) {
    array_push($arr, "('" . $collection_code . "', '" . $it->productCode . "', '" . $it->isPost . "')");
}

$str = implode(",", $arr);

try {

    $query = $dbconn->prepare("INSERT INTO `COLLECTION` (`F_PIN`, `COLLECTION_CODE`, `NAME`, `DESCRIPTION`, `TOTAL_VIEWS`, `STATUS`) VALUES ('$f_pin', '$collection_code', '$name', '$desc', 0, '$status')");
    $query->execute();
    $query->close();

    $query = $dbconn->prepare("INSERT INTO `COLLECTION_PRODUCT` (`COLLECTION_CODE`, `PRODUCT_CODE`, `IS_POST`) VALUES " . $str);
    $query->execute();
    $query->close();

    echo 'success';

} catch (\Throwable $th) {
    echo $th->getMessage() . " on line " . $th->getLine();
}

?>