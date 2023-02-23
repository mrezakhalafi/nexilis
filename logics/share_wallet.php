<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();

    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    // session_start();

    $f_pin = $_POST['f_pin'];

    $queryMainWallet = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin' AND BANK = 0");
    $queryMainWallet->execute();
    $mainBalance = $queryMainWallet->get_result()->fetch_assoc();
    $queryMainWallet->close();

    $queryWallet = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET");
    $queryWallet->execute();
    $bankWallet = $queryWallet->get_result();
    $queryWallet->close();

    $bank_name = $_POST['bank_name'];
    $acc_number = $_POST['acc_number'];
    $amount = 1000000;

    $current_wallet = $mainBalance['AMOUNT'];

    $add_amount = $_POST['amount'];
    $total_amount = $current_wallet - $add_amount;

    $insertData = "UPDATE HIMBARA_WALLET SET AMOUNT = ".$total_amount." WHERE F_PIN = '$f_pin' AND BANK = 0";

    if (mysqli_query($dbconn, $insertData)) {
        // echo ("Berhasil masuk datanya lho");
        echo $insertData;

    }
    else {
        echo ("Datanya gak masuk bro.");
        http_response_code(400);
    }

?>