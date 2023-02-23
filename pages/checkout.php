<?php

// KONEKSI
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();

$id = 2;
$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION where `ID` = '$id'");
$query->execute();
$setting = $query->get_result()->fetch_assoc();
$query->close();

$dbconn = paliolite();

$sql = "SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = 2";

$que = $dbconn->prepare($sql);
$que->execute();
$setting = $que->get_result()->fetch_assoc();
$que->close();

$dbNewNus = newnus();

if (isset($_SESSION['f_pin'])) {
    $f_pin = $_SESSION['f_pin'];
  } else {
    $f_pin = $_GET['f_pin'];
    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
    $query = $dbconn->prepare($queryCheckFpin);
    $query->execute();
    $getNewFPIN = $query->get_result()->fetch_assoc();
    $query->close();
  
    $f_pin = $getNewFPIN["F_PIN"];
    $_SESSION["f_pin"] = $f_pin;
  }

if (isset($_GET['p'])) {
  $api_key = $_GET['p'];
}

$bg_url = "";

if (isset($f_pin) && $f_pin != "") {
    // $sqlBE = "SELECT be.COMPANY_ID
    // FROM BUSINESS_ENTITY be
    // LEFT JOIN USER_LIST ul ON ul.BE = be.ID
    // WHERE ul.F_PIN = '$f_pin'";
    $sqlBE = "SELECT BE AS BE_ID FROM USER_LIST WHERE F_PIN = '$f_pin'";
  } else if (isset($api_key) && $api_key != "") {
    // $sqlBE = "SELECT be.COMPANY_ID
    // FROM BUSINESS_ENTITY be
    // WHERE be.API = '$api_key'";
    $sqlBE = "SELECT ID AS BE_ID FROM BUSINESS_ENTITY WHERE API = '$api_key'";
  }
  
  $queBEID = $dbconn->prepare($sqlBE);
  $queBEID->execute();
  $resBEID = $queBEID->get_result()->fetch_assoc();
  $queBEID->close();
  
  $be = $resBEID["BE_ID"];
  
  $sqlBG = "SELECT `VALUE` FROM `PREFS` WHERE `BE` = $be AND `KEY` = 'app_builder_background'";
  $queBG = $dbconn->prepare($sqlBG);
  $queBG->execute();
  $resBG = $queBG->get_result()->fetch_assoc();
  $queBG->close();
  // echo $sqlBG;
  
  $bg_str = $resBG["VALUE"];
  
  // echo $bg_str;
  if ($bg_str != null && $bg_str != "") {
    $bg_arr = explode(",", $bg_str);
  
    // echo "<pre>";
    // print_r($bg_arr);
    // echo "</pre>";
  
    $rand_bg = $bg_arr[array_rand($bg_arr, 1)];
  
    $bg_url = "/dashboardv2/uploads/background/" . $rand_bg;
  } else if ($bg_str == "" || $bg_str == null) {
    $bg_url = "";
  }
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <!-- <script src="../assets/js/xendit.min.js"></script> -->
    <script type="text/javascript" src="https://js.xendit.co/v1/xendit.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/qrcode.min.js"></script>
    <script src="../assets/js/cart.js?v=<?= time(); ?>"></script>
    <!-- <script src="../assets/js/cart copy.js?v=<?= time(); ?>"></script> -->

    <style>
        body {
            background: url("<?= $bg_url ?>");
            background-size: 100% auto;
        }

        @media (max-width: 360px) {
            body {
                margin-bottom: 125px;
            }
        }

        .text-purple {
            color: <?= $setting['FONT_COLOR']; ?>;
        }

        .bg-purple {
            background-color: <?= $setting['COLOR_PALETTE']; ?>;
        }

        a {
            color: <?= $setting['FONT_COLOR']; ?>;
        }

        a:hover {
            color: <?= $setting['FONT_COLOR']; ?>;
        }

        .tab-active {
            border-bottom: 2px solid <?= $setting['COLOR_PALETTE']; ?>;
        }

        .checkout-btn {
            background-color: <?= $setting['COLOR_PALETTE']; ?>;
            bottom: 70px;
        }

        #confirm-delete {
            background-color: <?= $setting['COLOR_PALETTE']; ?>;
        }

        #confirm-promo {
            background-color: <?= $setting['COLOR_PALETTE']; ?>;
        }

        .payment-button {
            background-color: <?= $setting['COLOR_PALETTE']; ?>;
        }

        .pay-button#pay-with-qris:disabled {
            background-color:lightgray;
            color:black;
        }
    </style>

<body style="background-color: gainsboro;">
    <div class="container-fluid" style="max-height: 93vh !important;">
        <div class="col-12">
            <div class="row" style="background-color: #fff; padding: 10px 0 10px 0; position: sticky; z-index: 10; top: 0;">
                <div class="col-4">
                    <a onclick="window.history.back();">
                        <img src="../assets/img/tab5/Back-(Black).png" style="width:30px">
                    </a>
                </div>
                <div class="col-4 text-center d-flex align-items-center justify-content-center text-black">
                    <span>Cart</span>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row bg-white small-text p-2 pt-3 pb-4 mb-1">
                <div class="container">
                    <div class="col-12">
                        <div class="row mb-3">
                            <div class="col fw-bold">
                                Delivery Address
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-9 fw-bold" id="receiver-name"></div>
                            <div class="col-3 text-end fw-bold orange-text" onclick="changeDeliveryAddress();">Change</div>
                        </div>
                        <div class="row gray-text">
                            <div id="delivery-address" class="col-6"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-white small-text p-2 pt-3 pb-4 mb-1">
                <div class="container">
                    <div class="col-12">
                        <div class="row mb-3">
                            <div class="col fw-bold">
                                Delivery Options
                            </div>
                        </div>
                        <span id="delivery-options"></span>
                    </div>
                </div>
            </div>
            <div class="row bg-white small-text p-2">
                <div class="container">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6 font-semibold">
                                Voucher Nexilis
                            </div>
                            <div class="col-6 text-end gray-text" onclick="enterPromoCode();" id="promo-code">
                                Enter promo code >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-white p-2" style="margin-top: 1px;">
                <div class="container">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6 payment-method font-semibold">
                                Payment Method
                            </div>
                            <div class="col-6 text-end payment-method">
                                <div class="dropdown">
                                    <a class="dropdown-toggle px-0 font-semibold" type="button" id="dropdownMenuSelectMethod" data-bs-toggle="dropdown" aria-expanded="false">
                                        QPay >
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li onclick="selectMethod(this);" class="dropdown-item">CARD</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">OVO</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">DANA</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">LINKAJA</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">SHOPEEPAY</li>
                                        <li onclick="selectMethod(this);" class="dropdown-item">QRIS</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bg-white p-2" style="margin-top: 1px;">
                <div class="container">
                    <div class="col-12">
                        <div class="row small-text pt-1 pb-1">
                            <div id="total-item" class="col-6 font-medium"></div>
                            <div id="total-price" class="col-6 text-end font-medium"></div>
                        </div>
                        <div class="row small-text pt-1 pb-1">
                            <div class="col-6 font-medium">
                                Delivery
                            </div>
                            <div id="delivery-cost" class="col-6 text-end"></div>
                        </div>
                        <div class="row pt-1 pb-1">
                            <div class="col-6 small-text font-medium">
                                Total (Tax Included)
                            </div>
                            <div id="total-price-tax-inc" class="col-6 font-medium grand-total text-end"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center" style="opacity: 0;">
            <div class="small-text text-black p-3">
                Make Payment
            </div>
        </div>
        <div class="row text-center" style="opacity: 0;">
            <div class="small-text text-black p-3">
                Make Payment
            </div>
        </div>
        <div class="row text-center payment-button" style="position: fixed;
    bottom: 100px;
    z-index: 10;">
            <!-- <div class="small-text text-white p-3 bg-dark" onclick="palioPay();"> -->
            <div class="small-text text-white p-3 bg-dark" onclick="checkStock();">
                Make Payment
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-warning-stock" tabindex="-1" role="dialog" aria-labelledby="modal-warning-stock" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-addcart" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-address" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-address-body">
                </div>
            </div>
        </div>
    </div>


    <!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        // Your code to run since DOM is loaded and ready
        payment();
        deliveryAddress().then((val) => {
            document.getElementById('receiver-name').innerHTML = `${JSON.parse(val).FIRST_NAME + JSON.parse(val).LAST_NAME}`;
            if (val == '') {
                window.open('/nexilis/pages/tab5-change-address', '_self')
            }
            document.getElementById('delivery-address').innerHTML = JSON.parse(val).ADDRESS;
        });
    });
</script>

</html>