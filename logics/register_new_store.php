<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();
    
    $title = $_POST['shop_name'];
    $link = $_POST['shop_link'];
    $description = $_POST['shop_description'];

    $sent_time = $_POST['sent_time'];
    $hex = $_POST['hex'];
    $adblock = '0';

    try {
        // fetch file type

        $image_name = '';

        
        // insert into table
        $query = $dbconn->prepare(
            "INSERT INTO SHOP(`CODE`, `NAME`, `CREATED_DATE`, `DESCRIPTION`, `FILE_TYPE`, `THUMB_ID`, `LINK`, `CATEGORY`, `USE_ADBLOCK`) VALUES (?, ?, ?, ?, 1, ?, ?, 1, ?)");
        $query->bind_param("sssssss", $hex, $title, $sent_time, $description, $image_name, $link, $adblock);
        $status = $query->execute();
        $query->close();

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>