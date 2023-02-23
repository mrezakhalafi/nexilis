<?php 

    include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

    $dbconn = paliolite();

    $store_code = $_POST['store_code'];
    $is_show = $_POST['is_show'];

    try {
        $query = $dbconn->prepare("UPDATE SHOP SET SHOW_FOLLOWS = ? WHERE CODE = ?");
        $query->bind_param("ss", $is_show, $store_code);
        $status = $query->execute();
        $query->close();
        
        $query = $dbconn->prepare("SELECT SHOW_FOLLOWS FROM SHOP WHERE CODE = ?");
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