<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET FROM TAB 5 FOLLOWING FORM

$f_pin = $_POST['f_pin'];
$store_code = $_POST['store_code'];
$id_collection = $_POST['id_collection'];

// DELETE FROM DATABASE

$query = "INSERT INTO USER_FOLLOW (STORE_CODE, F_PIN) VALUES ('".$store_code."','".$f_pin."')";

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