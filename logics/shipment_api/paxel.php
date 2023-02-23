<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

class Paxel {

    public const API_KEY = "ECOM.J8T88O9E51PR572";
    public const API_KEY_SECRET = "ECOM.X79HDFXGS18P042";

    /*
    Signature Formula:
    1. Concatenate first 2 characters of invoice_number, first 2 characters of origin.name, first 2 characters of destination.name, first 2 characters of first items.name and API secret into a string.
    2. Hash the string above with SHA-256 algorithm.
    3. Example:
    invoice_number = "A8HGK893J8"
    origin.name = "Jhon Doe"
    destination.name = "Jhon Lenon"
    First items.name = "Samsung Galaxy S9"
    API Secret = "GK8BGUE0B2"
    Signature = "8dc40976acaf29f423aa60c2ea9e2b826a5c7f804dc74b1ff116a8bfbddd7ef9"
    */
    public function generate_signature($invoice_number, $origin_name, $destination_name, $item_name) {
        
        $invoice_number_2 = substr($invoice_number, 0, 2);
        $origin_name_2 = substr($origin_name, 0, 2);
        $destination_name_2 = substr($destination_name, 0, 2);
        $item_name_2 = substr($item_name, 0, 2);

        $concat = $invoice_number_2 . $origin_name_2 . $destination_name_2 . $item_name_2 . self::API_KEY_SECRET;
        $hash = hash('sha256', $concat);

        return $hash;
    }

    public function store_awb($awb_code, $shipping_cost, $est_pickup, $est_arrival, $created_datetime, $fpin, $merchant_name) {

        $dbconn = paliolite();

        // save payment
        $query = $dbconn->prepare("INSERT INTO PAXEL_AIRWAYBILL (AWB_CODE, SHIPPING_COST, EST_PICKUP, EST_ARRIVAL, CREATED_DATETIME, FPIN, MERCHANT_NAME) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("sisssss", $awb_code, $shipping_cost, $est_pickup, $est_arrival, $created_datetime, $fpin, $merchant_name);
        $query->execute();
        $query->close();

    }

    public function get_orders($fpin) {

        $dbconn = paliolite();

        $result = array();

        // get orders
        $query = $dbconn->prepare("SELECT * FROM PAXEL_AIRWAYBILL WHERE FPIN = ?");
        $query->bind_param("s",$fpin);
        $query->execute();
        $orders = $query->get_result();
        $query->close();

        foreach ($orders as $order) {
            array_push($result, $order);
        }

        echo json_encode($result);

    }

}