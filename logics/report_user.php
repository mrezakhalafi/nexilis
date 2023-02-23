<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

session_start();

// GET FROM COLLECTION JS

$f_pin = $_POST['f_pin'];
$f_pin = $_SESSION['f_pin'];
$f_pin_reported = $_POST['f_pin_reported'];
$report_category = $_POST['report_category'];
$count_report = $_POST['count_report'];
$created_at = time() * 1000;

// INSERT REPORT

$query = "INSERT INTO REPORT_USER (F_PIN, F_PIN_REPORTED, REPORT_CATEGORY, CREATED_AT) VALUES ('".$f_pin."','".$f_pin_reported."','".$report_category."','".$created_at."')";

if (mysqli_query($dbconn, $query)){
    echo ("Berhasil");
}else{
    echo("Gagal dari Query");
}

?>