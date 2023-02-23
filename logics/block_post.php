<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

session_start();

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
$time = $_POST['time'];

// INSERT BLOCK

$sql = "INSERT INTO BLOCK_POST (F_PIN, POST_ID, CREATED_DATE) VALUES ('$f_pin','$post_id',$time)";

$sql2 = "INSERT INTO BLOCK_PRODUCT (F_PIN, PRODUCT_CODE, CREATED_DATE) VALUES ('$f_pin','$post_id',$time)";

try {
    if (mysqli_query($dbconn, $sql) && mysqli_query($dbconn, $sql2)){
        echo ("Success");
    }else{
        echo("Gagal dari Query");
    }
} catch (Exception $e) {
    echo $e;
}

?>