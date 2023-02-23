<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();

    try {
        $settings_data = explode(",",$_POST['settings_data']);
        $rows = array();

        foreach ($settings_data as $product_setting_str) {
            $product_setting_arr = explode("~",$product_setting_str);
            $product_code = $product_setting_arr[0];
            $product_show = $product_setting_arr[1];
            
            $query = $dbconn->prepare("UPDATE PRODUCT SET IS_SHOW = ? WHERE CODE = ?");
            $query->bind_param("ss", $product_show, $product_code);
            $status = $query->execute();
            $query->close();
            
            $query = $dbconn->prepare("SELECT IS_SHOW FROM PRODUCT WHERE CODE = ?");
            $query->bind_param("s", $product_code);
            // SELECT USER PROFILE
            $query->execute();
            $groups = $query->get_result();
            $query->close();
            
            while ($group = $groups->fetch_assoc()) {
                $rows[] = $group;
            };
        }
        
        echo json_encode($rows);

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>