<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// DELETE FROM LISTING FORM

$product_code = $_GET['id'];

// DELETE FROM PRODUCT

$query = "UPDATE PRODUCT SET IS_DELETED = 1 WHERE CODE = '".$product_code."'";

if (mysqli_query($dbconn, $query)){
    header("Location: ../../pages/tab5-listing.php?success=true");
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>