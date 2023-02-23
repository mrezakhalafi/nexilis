<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

session_start();

// GET FROM TAB 3 PROFILE

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
$l_pin = $_POST['l_pin'];

// INSERT REPORT

$query = "DELETE FROM BLOCK_USER WHERE F_PIN = '".$f_pin."' AND L_PIN = '".$l_pin."'";

if (mysqli_query($dbconn, $query)){
    echo ("Berhasil");
}else{
    echo("Gagal dari Query");
}

?>