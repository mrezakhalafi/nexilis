<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

session_start();

// GET FROM COLLECTION JS

if (isset($_SESSION["f_pin"])) {
    $f_pin = $_SESSION["f_pin"];
} else {
    $f_pin = $_POST['f_pin'];

    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();

    $f_pin = $getNewFPIN["F_PIN"];
    $_SESSION["f_pin"] = $f_pin;
}
$post_id = $_POST['post_id'];
$report_category = $_POST['report_category'];
$count_report = $_POST['count_report'];
$created_at = time() * 1000;

// $new_report = $count_report + 1;

// INSERT REPORT

$query = "INSERT INTO REPORT_POST (F_PIN, POST_ID, REPORT_CATEGORY, CREATED_AT) VALUES ('".$f_pin."','".$post_id."','".$report_category."','".$created_at."')";

$query2 = "UPDATE POST SET REPORT = REPORT + 1 WHERE POST_ID = '".$post_id."'";


$query3 = "INSERT INTO REPORT_PRODUCT (F_PIN, PRODUCT_CODE, REPORT_CATEGORY, CREATED_AT) VALUES ('".$f_pin."','".$post_id."','".$report_category."','".$created_at."')";

$query4 = "UPDATE PRODUCT SET REPORT = REPORT + 1 WHERE CODE = '".$post_id."'";

if (mysqli_query($dbconn, $query) && mysqli_query($dbconn, $query2) && mysqli_query($dbconn, $query3) && mysqli_query($dbconn, $query4)){
    echo ("Berhasil");
}else{
    echo("Gagal dari Query");
}

?>