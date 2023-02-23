<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET DATA FROM FORM

$reels_id = $_POST['reels_id'];

// SELECT REELS

$query = $dbconn->prepare("SELECT * FROM SHOP_REELS WHERE ID = '$reels_id'");
$query->execute();
$reels = $query->get_result()->fetch_array();
$query->close();

$old_views = $reels['VIEWS'];
$views = $old_views + 1;

// INSERT VIEWS

$query = "UPDATE SHOP_REELS SET VIEWS = '".$views."' WHERE ID = '".$reels_id."'";

if (mysqli_query($dbconn, $query)){
    echo("Berhasil");
}else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
}

?>