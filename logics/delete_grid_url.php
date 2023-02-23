<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    // GET USER PIN

    session_start();
    $deleteURL_ID = $_POST['idURL'];

    $queryGridUrl = "DELETE FROM GRID_URL WHERE LINK_ID = '$deleteURL_ID'";

    if (mysqli_query($dbconn, $queryGridUrl)) {
        echo "Koneksi DB Berhasil";
    }
    else {
        echo "Koneksi Gagal";
    }

?>
