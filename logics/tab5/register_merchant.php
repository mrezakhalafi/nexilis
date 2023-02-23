<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // KONEKSI

    include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
    $dbconn = paliolite();
    session_start();

    $hex = $_POST['hex'];
    $company_name = $_POST['companyname'];
    $sent_time = $_POST['sent_time'];

    $link = $_POST['shop_link'];
    $created_by = $_POST['created_by'];
    $description = $_POST['description'];

    $category = 0;

    $logoName = null;
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/nexilis/images/";

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $_FILES['file']['name'])) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/nexilis/images/";
        $logoName = $_FILES['file']['name'];
        $uploadedFile = $uploadDir . $logoName;
    }

    $location = $_POST['location'];
    $phone_number = $_POST['phone_number'];

    // SELECT PALIO ID

    $query = $dbconn->prepare("SELECT * FROM USER_LIST WHERE F_PIN = '".$created_by."'");
	$query->execute();
	$user = $query->get_result()->fetch_assoc();
	$query->close();

    $query = $dbconn->prepare("SELECT * FROM BUSINESS_ENTITY WHERE COMPANY_ID = '".$user['BE']."'");
	$query->execute();
	$store_palio_id = $query->get_result()->fetch_assoc();
	$query->close();

    print_r("Hex".$hex);
    print_r("Company Name =".$company_name);
    print_r("Description =".$description);
    print_r("Sent Time =".$sent_time);
    print_r("Logo Name =".$logoName);
    print_r("Website =".$link);
    print_r("Palio ID =".$store_palio_id['COMPANY_ID']);
    print_r("Created By =".$created_by);
    print_r("Location =".$location);
    print_r("Phone Number =".$phone_number);

    $query = $dbconn->prepare(
        "INSERT INTO SHOP(`CODE`, `NAME`, `CREATED_DATE`, `DESCRIPTION`, `FILE_TYPE`, `THUMB_ID`, `LINK`, `CATEGORY`, `USE_ADBLOCK`, `PALIO_ID`, `CREATED_BY`, `IS_QIOSK`) VALUES (?, ?, ?, '".$description."', 1, ?, ?, '".$category."', 0, ?, ?, '".$store_palio_id['COMPANY_ID']."')");
    $query->bind_param("sssssss", $hex, $company_name, $sent_time, $logoName, $link, $store_palio_id['COMPANY_ID'], $created_by);
    $status = $query->execute();
    $query->close();

    // QUERY ADD ADDRESS LOCATION

    $queryAddress = "INSERT INTO SHOP_SHIPPING_ADDRESS (STORE_CODE, ADDRESS,
                        PHONE_NUMBER) VALUES ('".$hex."','".$location."','".$phone_number."');";

    if (mysqli_query($dbconn, $queryAddress)){
        header("Location: ../../pages/tab3-commerce?f_pin=".$created_by);
    }else{
        echo "Data gagal ditambahkan ke database." . mysqli_error($dbconn);
    }


?>