<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();
    
    $post_id = $_GET['post_id'];

    try {
        $sql = "UPDATE POST SET IS_REMOVED = 1 WHERE POST_ID = '$post_id'";
        
        // echo $sql;
        $query = $dbconn->prepare($sql);
        $status = $query->execute();
        $query->close();

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>