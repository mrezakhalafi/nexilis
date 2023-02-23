<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include class
include_once 'paxel.php';

// initialize child
$getter = new Paxel();

// make sure data is not empty
if (

    !empty($_POST['fpin'])

) {

    try {
        $orders = $getter->get_orders($_POST['fpin']);
        echo $orders;

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