<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET FROM SHOP MANAGER - FOLLOWERS FORM

$f_pin = $_POST['f_pin'];
$store_code = $_POST['store_code'];
$id_collection = $_POST['id_collection'];

// DELETE FROM DATABASE

$query = "DELETE FROM USER_FOLLOW WHERE F_PIN = '".$f_pin."' AND STORE_CODE = '".$store_code."'";

if (mysqli_query($dbconn, $query)){
    if ($_GET['src']=="profile"){
        header("Location: ../../pages/tab5-profile.php?id_visit=".$f_pin);
    }elseif($_GET['src']=="collection"){
        header("Location: ../../pages/tab5-collection.php?id_visit=".$f_pin."&collection_code=".$id_collection);    
    }else{
        header("Location: ../../pages/tab5-followers.php");
    }
}else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
}

?>