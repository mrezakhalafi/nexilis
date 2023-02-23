<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// include class
include_once 'grab.php';

// initialize child
$grab = new Grab();

try {
    echo $grab->get_access_token();

} catch (Throwable $error) {
    http_response_code(500);
    echo "Error: {$error->getMessage()}";
}