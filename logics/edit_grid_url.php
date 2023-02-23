<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    session_start();

    // IMG LOCATION TO PC STORAGE
    $targetLoc =  $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
    $targetDir = array();

    $link_id = rand(0000000000, 9999999999);

    $imageUploader = strtolower(pathinfo($_FILES["modify-image"]["name"],PATHINFO_EXTENSION));
    $fotoProfile = "linkURL_" . $link_id .  ".webp";
    $targetDir["modify-image"] = $targetLoc . $fotoProfile;

    $uploadPhoto = true;
    $uploadPhoto = move_uploaded_file($_FILES["modify-image"]["tmp_name"], $targetDir["modify-image"]);
    // END HERE

    $editURL_ID = $_POST['idURL'];
    $editURL_name = $_POST['nameURL'];

    $queryGridUrl = "UPDATE GRID_URL SET LINK_IMG = '$fotoProfile', LINK_URL = '$editURL_name' WHERE LINK_ID = '$editURL_ID'";

    if (mysqli_query($dbconn, $queryGridUrl)) {
        echo $queryGridUrl . " " . $targetLoc;
    }
    else {
        echo "Koneksi Gagal";
    }

?>
