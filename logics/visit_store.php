<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();

    $store_code = $_POST['store_code'];
    $flag_visit = $_POST['flag_visit'];
    $f_pin = $_POST['f_pin'];

    try {
        if($flag_visit == '1'){
            $query = $dbconn->prepare("DELETE FROM SHOP_VISIT WHERE F_PIN = ?");
            $query->bind_param("s", $f_pin);
            $status = $query->execute();
            $query->close();

            $query = $dbconn->prepare("INSERT IGNORE INTO SHOP_VISIT (F_PIN, STORE_CODE) VALUES (?,?)");
            $query->bind_param("ss", $f_pin, $store_code);
            $status = $query->execute();
            $query->close();

            $query = $dbconn->prepare("UPDATE SHOP SET TOTAL_VISITOR=(SELECT COUNT(DISTINCT F_PIN) FROM SHOP_VISIT WHERE STORE_CODE = ?) WHERE CODE = ?");
            $query->bind_param("ss", $store_code, $store_code);
            $status = $query->execute();
            $query->close();
        } else {
            $query = $dbconn->prepare("DELETE FROM SHOP_VISIT WHERE F_PIN = ? AND STORE_CODE = ?");
            $query->bind_param("ss", $f_pin, $store_code);
            $status = $query->execute();
            $query->close();

            $query = $dbconn->prepare("UPDATE SHOP SET TOTAL_VISITOR=(SELECT COUNT(DISTINCT F_PIN) FROM SHOP_VISIT WHERE STORE_CODE = ?) WHERE CODE = ?");
            $query->bind_param("ss", $store_code, $store_code);
            $status = $query->execute();
            $query->close();
        }
        $query = $dbconn->prepare("SELECT TOTAL_VISITOR FROM SHOP WHERE CODE = ?");
        $query->bind_param("s", $store_code);
        // SELECT USER PROFILE
        $query->execute();
        $groups = $query->get_result();
        $query->close();
        
        $rows = array();
        while ($group = $groups->fetch_assoc()) {
            $rows[] = $group;
        };

        echo json_encode($rows);

    } catch (\Throwable $th) {
        //throw $th;
        echo $th->getMessage();
    }
?>