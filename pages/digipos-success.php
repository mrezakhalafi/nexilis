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

<body style="background-color: #b01028; display:flex; flex-direction:column; justify-content:center; min-height:100vh;">
    <div class="container-fluid my-auto">
        <div class="col-12">
            <div class="row">
                <div class="col text-center">
                    <img src="../assets/img/icons/Order-Placed-Successfully.png" alt="order-placed-successfully" style="width: 170px;">
                </div>
            </div>
            <div class="row mt-5 mb-1 text-white text-center">
                <div class="col-12" style="font-weight: 700; font-size: 25px">Order Successfully!</div>
            </div>
            <div class="row mb-4 text-white small-text text-center">
                <div class="col-12" style="font-size: 14px">Congratulations! Your order has been placed.</div>
            </div>
            <div class="row mb-2 p-4">
                <a class="small-text text-center">
                    <div class="col">
                        <a onclick="event.preventDefault(); toDigipos();"><div class="bg-white d-flex align-items-center justify-content-center rounded-pill border border-white text-purple" style="height: 40px; font-size: 14px; font-weight: 700; color:#ed0226">Buy Again</div></a>
                    </div>
                </a>
            </div>
        </div>
    </div>


    <!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script>
    function toDigipos() {
        let f_pin = new URLSearchParams(window.location.search).get('f_pin');
        let env = new URLSearchParams(window.location.search).get('env');
        let origin = new URLSearchParams(window.location.search).get('origin');
        let store_id = new URLSearchParams(window.location.search).get('store_id');

        window.location.href = 'digipos.php?f_pin=' + f_pin + "&env=" + env + "&origin=" + origin + "&store_id=" + store_id;
    }
</script>

</html>