<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include class
include_once 'grab.php';

// initialize child
$grab = new Grab();

// make sure data is not empty
if (

    // check input
    !empty($_POST['delivery_id'])

) {

    try {
        $access_token = json_decode($grab->load_token());
        if(is_null($access_token)){
            $access_token = json_decode($grab->get_access_token());
        }
        $grab->cancel_delivery($access_token->access_token, $_POST['delivery_id']);

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