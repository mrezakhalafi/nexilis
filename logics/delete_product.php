<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();
    
    $product_code = $_POST['product_code'];

    try {
        $query = $dbconn->prepare("DELETE FROM PRODUCT_SHIPMENT_DETAIL WHERE PRODUCT_CODE = ?");
        $query->bind_param("s", $product_code);
        $status = $query->execute();
        $query->close();
        
        $query = $dbconn->prepare("DELETE FROM PRODUCT WHERE CODE = ?");
        $query->bind_param("s", $product_code);
        $status = $query->execute();
        $query->close();

        echo "Success";

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>