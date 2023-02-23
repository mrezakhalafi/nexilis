<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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

$f_pin = $_GET['f_pin'];

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

//   echo $bg_str;

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

    <title>Qiosk - Cart</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <!-- custom css -->
    <link href="../assets/css/style-cart-min.css?v=<?= time(); ?>" rel="stylesheet">

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/cart-min.js?v=<?= time(); ?>"></script>

    <style>
        body {
            background: url("<?= $bg_url ?>");
            background-size: 100% auto;
        }

        @media (max-width: 360px) {
            body {
                margin-bottom: 100px;
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
            border-bottom: 2px solid black;
        }

        .checkout-btn {
            /* background-color: <?= $setting['COLOR_PALETTE']; ?>; */
            background-color: black;
            bottom: 100px;
        }

        #confirm-delete {
            /* background-color: <?= $setting['COLOR_PALETTE']; ?>; */
            background-color: black;
        }

        #confirm-promo {
            background-color: black;
        }

        .checkout-btn a {
            color: <?= $setting['FONT_COLOR']; ?>;
        }

        .counter input.form-control:disabled {
            background-color: unset !important;
        }
    </style>

</head>

<body>
    <div class="container-fluid nav-bar">
        <div class="bg-purple mb-3" id="header">
            <div class="row bg-purple" style="padding: 10px 0 10px 0;">
                <div class="col-4">
                    <a onclick="goBack();">
                        <img src="../assets/img/tab5/Back-(Black).png" style="width:30px">
                    </a>
                </div>
                <div class="col-4 text-center d-flex align-items-center justify-content-center text-black">
                    <span id="cart-title" style="font-size: 1rem;">Cart</span>
                </div>
                <div class="col-4"></div>
            </div>
        </div>

        <!-- cart/saved tab -->
        <div class="container-fluid mt-3">
            <div class="row">
                <div onclick="changeTab('items');" id="cart-items-tab" class="col-6 p-2 text-center font-medium tab-active">
                    Your Cart
                </div>
                <div onclick="changeTab('saved');" id="cart-saved-tab" class="col-6 p-2 text-center font-medium">
                    Saved For Later
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-0" id="main-body">
        <!-- <div class="tab-content" id="tab-content"> -->
        <div class="tab-pane fade show active" id="your-cart" role="tabpanel" aria-labelledby="cart-tab">
            <div id="cart-body" class="d-none">
                <!-- shop items -->
                <div id="cart-items"></div>
                <div id="cart-saved" class="d-none"></div>

                <!-- voucher -->
                <div id="pricetag">
                    <div class="container-fluid px-4 py-2 voucher">
                        <div class="row">
                            <div class="col-6 font-semibold" id="voucher-title">
                                Voucher Nexilis
                            </div>
                            <div id="promo-code" class="col-6 text-end text-grey" onclick="enterPromoCode();">
                                Enter promo code >
                            </div>
                        </div>
                    </div>

                    <!-- total -->
                    <div class="container-fluid px-4 py-2">
                        <div class="row my-1">
                            <div id="total-item" class="col-6 font-medium"></div>
                            <div id="total-price" class="col-6 font-medium text-end"></div>
                        </div>
                        <div class="row my-1">
                            <div class="col-6 font-medium" id="delivery-span">
                                Delivery
                            </div>
                            <div id="delivery-cost" class="col-6 font-medium text-end"></div>
                        </div>
                        <div class="row my-1">
                            <div class="col-6 font-medium" id="total-all">
                                Total (Tax included)
                            </div>
                            <div id="total-price-tax-inc" class="col-6 font-medium text-end grand-total"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="cart-empty" class="mx-5 my-5 text-center">
                <p>Your cart is empty!</p>
            </div>
        </div>

        <div class="tab-pane fade" id="saved" role="tabpanel" aria-labelledby="saved-tab">
        </div>
        <!-- </div> -->

        <!-- <div class="container-fluid py-3">
            <div class="row">
                <a>
                    <div class="col-12 text-center text-white" id="checkout-1">
                        Checkout
                    </div>
                </a>
            </div>
        </div> -->
    </div>
    <!-- checkout -->
    <div id="checkout-button" class="container-fluid checkout-btn py-3 d-none">
        <div class="row">
            <a href="checkout.php?f_pin=<?= $f_pin ?>" target="_self">
                <div class="col-12 text-center" id="checkout-2">
                    Checkout
                </div>
            </a>
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
</body>
<script>
    document.addEventListener("DOMContentLoaded", async function(event) {
        // Your code to run since DOM is loaded and ready
        await populateCart();
        if (countTotal('all') == 'Rp 0') {
            document.getElementById('checkout-button').classList.add('d-none');
        }

        if (localStorage.tab == 'items') {
            changeTab('items');
        } else if (localStorage.tab == 'saved') {
            changeTab('saved');
        }
    });

    async function changeTab(tab) {
        if (tab == 'items') {
            document.getElementById('cart-items-tab').classList.add('tab-active');
            document.getElementById('cart-saved-tab').classList.remove('tab-active');
            document.getElementById('pricetag').classList.remove('d-none');
            await populateCart();
            if (countTotal('all') == 'Rp 0') {
                document.getElementById('checkout-button').classList.add('d-none');
            }
            localStorage.setItem("tab", 'items');
        } else {
            document.getElementById('cart-saved-tab').classList.add('tab-active');
            document.getElementById('cart-items-tab').classList.remove('tab-active');
            populateSaved();
            localStorage.setItem("tab", 'saved');
        }
    }

    if (localStorage.lang == 0) {
        document.getElementById("cart-title").innerText = 'Cart';
        document.getElementById("cart-items-tab").innerText = 'Your Cart';
        document.getElementById("cart-saved-tab").innerText = 'Saved for Later';
        document.getElementById("promo-code").innerText = 'Enter promo code >';
        document.getElementById("delivery-span").innerText = 'Delivery';
        document.getElementById("total-all").innerText = 'Total (tax included)';
        document.getElementById("cart-empty").innerText = 'Your cart is empty!';
        // document.getElementById("checkout-1").innerText = 'Checkout';
        document.getElementById("checkout-2").innerText = 'Checkout';
    } else {
        document.getElementById("cart-title").innerText = 'Keranjang';
        document.getElementById("cart-items-tab").innerText = 'Keranjangmu';
        document.getElementById("cart-saved-tab").innerText = 'Barang Disimpan';
        document.getElementById("promo-code").innerText = 'Masukkan kode promo >';
        document.getElementById("delivery-span").innerText = 'Pengiriman';
        document.getElementById("total-all").innerText = 'Jumlah (termasuk pajak)';
        document.getElementById("cart-empty").innerText = 'Keranjangmu kosong!';
        // document.getElementById("checkout-1").innerText = 'Pembayaran';
        document.getElementById("checkout-2").innerText = 'Pembayaran';
    }
</script>

</html>