<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();
    
    $product_code = $_POST['product_code'];

    try {

        $queryDelete = "UPDATE PRODUCT SET IS_REMOVED = 1 WHERE CODE = '$product_code'";

        if (mysqli_query($dbconn, $queryDelete)) {
            echo "Success";
        }

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>