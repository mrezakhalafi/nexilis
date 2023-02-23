<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$product_code = $_POST['post_id'];
$report_category = $_POST['report_category'];
$created_at = time() * 1000;

// INSERT REPORT

$query = "INSERT INTO REPORT_PRODUCT (F_PIN, PRODUCT_CODE, REPORT_CATEGORY, CREATED_AT) VALUES ('".$f_pin."','".$product_code."','".$report_category."','".$created_at."')";

if (mysqli_query($dbconn, $query)){
    echo ("Berhasil");
}else{
    echo("Gagal dari Query");
}

?>