<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();
    
    $shop_code = $_POST['shop_code'];

    try {
        // get item code
        $query = $dbconn->prepare("SELECT CAN_DELETE FROM SHOP WHERE CODE = ?");
        $query->bind_param("s", $shop_code);
        $query->execute();
        $item = $query->get_result()->fetch_assoc();
        $can_delete = $item['CAN_DELETE'];

        if($can_delete == '1'){
            $query = $dbconn->prepare("DELETE FROM PRODUCT WHERE SHOP_CODE = ?");
            $query->bind_param("s", $shop_code);
            $status = $query->execute();
            $query->close();
    
            $query = $dbconn->prepare("DELETE FROM SHOP_SHIPPING_ADDRESS WHERE STORE_CODE = ?");
            $query->bind_param("s", $shop_code);
            $status = $query->execute();
            $query->close();
    
            $query = $dbconn->prepare("DELETE FROM SHOP WHERE CODE = ?");
            $query->bind_param("s", $shop_code);
            $status = $query->execute();
            $query->close();
    
            echo "Success";
        } else {
            echo "Cannot delete";
        }
    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>