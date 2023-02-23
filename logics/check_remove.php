<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();
    
    $product_code = $_POST['post_id'];

    $isPost = 0;
    $isProduct = 0;

    try {
        $query = $dbconn->prepare("SELECT * FROM POST WHERE POST_ID = '$product_code'");
        $query->execute();
        $checkPost = $query->get_result()->fetch_assoc();
        $query->close();

        if ($checkPost["IS_REMOVED"] == 1){

            $isPost = 0;

        }else{

            $isPost = 1;

        }

        $query = $dbconn->prepare("SELECT * FROM PRODUCT WHERE CODE = '$product_code'");
        $query->execute();
        $checkProduct = $query->get_result()->fetch_assoc();
        $query->close();

        if ($checkProduct['IS_DELETED'] == 1){

            $isProduct = 0;

        }else{

            $isProduct = 1;

        }

        echo ($isPost."|".$isProduct);

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>