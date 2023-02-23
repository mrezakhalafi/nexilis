<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include class
include_once 'paxel.php';

// initialize child
$saver = new Paxel();

// make sure data is not empty
if (

    // origin
    !empty($_POST['awb_code']) &&
    !empty($_POST['shipping_cost']) &&
    !empty($_POST['est_pickup']) &&
    !empty($_POST['est_arrival']) &&
    !empty($_POST['created_datetime']) &&

    !empty($_POST['fpin']) &&
    !empty($_POST['merchant_name'])

) {

    try {
        $saver->store_awb($_POST['awb_code'], $_POST['shipping_cost'], $_POST['est_pickup'], $_POST['est_arrival'], $_POST['created_datetime'], $_POST['fpin'], $_POST['merchant_name']);

    } catch (Throwable $error) {
        http_response_code(500);
        echo "Error: {$error->getMessage()}";

    }

}

// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("Error: " => "Data is incomplete."));
}