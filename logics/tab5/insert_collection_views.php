<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET DATA FROM FORM

$collection_code = $_POST['collection_code'];

// SELECT COLLECTION

$query = $dbconn->prepare("SELECT * FROM COLLECTION WHERE COLLECTION_CODE = '$collection_code'");
$query->execute();
$collection = $query->get_result()->fetch_array();
$query->close();

$views = $collection['TOTAL_VIEWS'];
$views = $views + 1;

// INSERT VIEWS

$query = "UPDATE COLLECTION SET TOTAL_VIEWS = '".$views."' WHERE COLLECTION_CODE = '".$collection_code."'";

if (mysqli_query($dbconn, $query)){
    echo("Berhasil");
}else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
}

?>