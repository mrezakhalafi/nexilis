<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = newnus();

$number = $_POST['number'];
$provider = $_POST['provider'];

try {

    if ($provider == "TSEL"){

        $query = $dbconn->prepare("SELECT * FROM PPOB_PRODUCT WHERE TYPE = 3 ORDER BY ABS(DENOM) ASC");
        $query->execute();
        $data = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        if (isset($rows)){
            echo(json_encode($rows));
        }else{
            echo("");
        }

    }else if($provider == "XL"){

        $query = $dbconn->prepare("SELECT * FROM PPOB_PRODUCT WHERE TYPE = 2 ORDER BY ABS(DENOM) ASC");
        $query->execute();
        $data = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        if (isset($rows)){
            echo(json_encode($rows));
        }else{
            echo("");
        }

    }else if($provider == "INDOSAT"){

        $query = $dbconn->prepare("SELECT * FROM PPOB_PRODUCT WHERE TYPE = 4 ORDER BY ABS(DENOM) ASC");
        $query->execute();
        $data = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        if (isset($rows)){
            echo(json_encode($rows));
        }else{
            echo("");
        }
        
    }else if($provider == "AXIS"){

        $query = $dbconn->prepare("SELECT * FROM PPOB_PRODUCT WHERE TYPE = 1 ORDER BY ABS(DENOM) ASC");
        $query->execute();
        $data = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        if (isset($rows)){
            echo(json_encode($rows));
        }else{
            echo("");
        }
        
    }else if($provider == "TRI"){

        $query = $dbconn->prepare("SELECT * FROM PPOB_PRODUCT WHERE TYPE = 5 ORDER BY ABS(DENOM) ASC");
        $query->execute();
        $data = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        if (isset($rows)){
            echo(json_encode($rows));
        }else{
            echo("");
        }
        
    }else if($provider == "SMART"){

        $query = $dbconn->prepare("SELECT * FROM PPOB_PRODUCT WHERE TYPE = 15 ORDER BY ABS(DENOM) ASC");
        $query->execute();
        $data = $query->get_result();
        $query->close();

        $rows = [];
        while ($row = $data->fetch_assoc()){
            $rows[] = $row;
        }

        if (isset($rows)){
            echo(json_encode($rows));
        }else{
            echo("");
        }
        
    }

} catch (\Throwable $th) {

    echo $th->getMessage();

}