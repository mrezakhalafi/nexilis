<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // session_start();

    $f_pin = $_POST['f_pin'];

    $queryWalletData = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin'");
    $queryWalletData->execute();
    $walletData = $queryWalletData->get_result();
    $queryWalletData->close();

    $bank_name = $_POST['bank_name'];
    $bank_code = 0;
    $acc_number = $_POST['acc_number'];
    $amount = 1000000;

    if (mysqli_num_rows($walletData) < 1) {
        $insertData = "INSERT INTO HIMBARA_WALLET (F_PIN, BANK, NO_REK, AMOUNT) VALUES ('".$f_pin."', ".$bank_name.", ".$acc_number.", ".$amount.")";
        $insertDataWallet = "INSERT INTO HIMBARA_WALLET (F_PIN, BANK, NO_REK, AMOUNT) VALUES ('".$f_pin."', ".$bank_code.", ".$acc_number.", '0')";

        if (mysqli_query($dbconn, $insertData) && mysqli_query($dbconn, $insertDataWallet)) {
            echo ("Berhasil masuk datanya lho");
        }
        else {
            echo ("Datanya gak masuk bro.");
            http_response_code(400);
        }    
    }
    else {
        $insertData = "INSERT INTO HIMBARA_WALLET (F_PIN, BANK, NO_REK, AMOUNT) VALUES ('".$f_pin."', ".$bank_name.", ".$acc_number.", ".$amount.")";   

        if (mysqli_query($dbconn, $insertData)) {
            echo ("Berhasil masuk datanya lho");
        }
        else {
            echo ("Datanya gak masuk bro.");
            http_response_code(400);
        }
    }

?>