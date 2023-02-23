<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();
    
    $product_code = $_POST['post_id'];
    $ec_date = $_POST['ec_date'];

    try {
        $sql = "UPDATE POST SET EC_DATE = ".$ec_date." WHERE POST_ID = '$product_code';";
        
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