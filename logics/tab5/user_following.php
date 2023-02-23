<?php 

// KONEKSI

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

// GET FROM PROFILE VISIT

$f_pin = $_POST['f_pin'];
$l_pin = $_POST['l_pin'];

// DELETE FROM DATABASE

$query = "INSERT INTO FOLLOW_LIST (F_PIN, L_PIN, CREATED_DATE) VALUES ('".$f_pin."','".$l_pin."','".date('Y-m-d h:i:s')."')";

if (mysqli_query($dbconn, $query)){
    if ($_GET['src']=="profile"){
        header("Location: ../../pages/tab5-profile.php?id_visit=".$l_pin);
    }elseif($_GET['src']=="collection"){
        header("Location: ../../pages/tab5-collection.php?id_visit=".$l_pin."&collection_code=".$id_collection);    
    }else{
        header("Location: ../../pages/tab5-followers.php");
    }
}else{
    echo("ERROR: Data failed to add. $sql. " . mysqli_error($dbconn));
}

?>