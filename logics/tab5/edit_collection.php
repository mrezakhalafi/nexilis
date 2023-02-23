<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET FROM DETAIL ORDERS FORM

session_start();
$collection_id = $_POST['collection_id'];

// UPDATE FROM COLLECTION

$name = $_POST['name'];
$description = $_POST['description'];

$query = "UPDATE COLLECTION SET NAME = '".$name."', DESCRIPTION = '".$description."' 
            WHERE COLLECTION_CODE = '".$collection_id."'";

if (mysqli_query($dbconn, $query)){
    echo "Berhasil";
}else{
    echo("ERROR: Data gagal diubah. $sql. " . mysqli_error($dbconn));
}

?>