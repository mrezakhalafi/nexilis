<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();
$dbNewNus = newnus();

if (isset($_GET['f_pin'])) {

    $f_pin = $_GET['f_pin'];
}


if (isset($f_pin) && $f_pin != "") {
    $sqlBE = "SELECT be.COMPANY_ID
    FROM BUSINESS_ENTITY be
    LEFT JOIN USER_LIST ul ON ul.BE = be.ID
    WHERE ul.F_PIN = '$f_pin'";
} else if (isset($api_key) && $api_key != "") {
    $sqlBE = "SELECT be.COMPANY_ID
    FROM BUSINESS_ENTITY be
    WHERE be.API = '$api_key'";
}

$queBE = $dbconn->prepare($sqlBE);
$queBE->execute();
$resBE = $queBE->get_result()->fetch_assoc();
$c_id = $resBE["COMPANY_ID"];
$queBE->close();

// get BG
$sqlBG = "SELECT CONTENT_TAB_LAYOUT FROM WEBFORM WHERE COMPANY_ID = " . $c_id . " AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
$queBG = $dbNewNus->prepare($sqlBG);
$queBG->execute();
$resBG = $queBG->get_result()->fetch_assoc();
$layout = $resBG["CONTENT_TAB_LAYOUT"];
$queBG->close();
?>

<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/payment-style.css">

<body style="background-color: #000000; display:flex; flex-direction:column; justify-content:center; min-height:100vh;">
    <div class="container-fluid my-auto">
        <div class="col-12">
            <div class="row">
                <div class="col text-center">
                    <img src="../assets/img/icons/Order-Placed-Successfully.png" alt="order-placed-successfully" style="width: 170px;">
                </div>
            </div>
            <div class="row mt-5 mb-1 text-white text-center">
                <div class="col-12">Order Placed Successfully!</div>
            </div>
            <div class="row mb-4 text-white small-text text-center">
                <div class="col-12">Congratulations! Your order has been placed. <br> You can track your order number #123454!</div>
            </div>
            <div class="row mb-2 p-4 d-none">
                <a class="small-text text-center" href="tab5-receipt.php?id=<?= $_GET['id']; ?>">
                    <div class="col">
                        <div class="bg-white d-flex align-items-center justify-content-center rounded-pill border border-white text-purple" style="height: 30px;">Track order</div>
                    </div>
                </a>
            </div>
            <div class="row">
                <a class="text-white small-text text-center" id="continue-shopping">
                    <div class="col-12">
                        Continue shopping
                    </div>
                </a>
            </div>
        </div>
    </div>


    <!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script>
    document.getElementById("continue-shopping").addEventListener("click", function() {
        console.log("ngapain ya")
        let f_pin = "";
        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = localStorage.getItem("save_f_pin");
        }
        if (localStorage.getItem('currentTab') != null) {
            let currentTab = localStorage.getItem('currentTab');

            if (currentTab == '0') {
                window.location.href = 'tab1-main-only?f_pin=' + f_pin;
            } else if (currentTab == '1') {
                window.location.href = 'tab3-main-only?f_pin=' + f_pin;
            } else if (currentTab == '2') {
                // window.location.href = 'tab3-main-only?f_pin=' + f_pin;
                let mode = localStorage.getItem('is_grid')
                if (mode) {
                    if (mode == '0') {
                        window.location.href = 'tab1-main?f_pin=' + f_pin;
                    } else {
                        window.location.href = 'tab3-main?f_pin=' + f_pin;
                    }
                } else {
                    window.location.href = 'tab1-main?f_pin=' + f_pin;
                }
            } else if (currentTab == '4') {
                window.location.href = 'tab1-video?f_pin=' + f_pin;
            }
        }
    })
</script>

</html>