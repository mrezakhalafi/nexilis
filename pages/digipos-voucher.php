<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbNewNus = newnus();
$dbconnPalio = paliolite();
$env = $_GET['env'];

$id_user = $_GET['f_pin'];

$queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$id_user' OR IMEI = '$id_user'";
$queIMEI = $dbconnPalio->prepare($queryCheckFpin);
$queIMEI->execute();
$resIMEI = $queIMEI->get_result()->fetch_assoc();
$id_user = $resIMEI['F_PIN'];
$queIMEI->close();

$store_id = $_GET['store_id'];
$url_type = $_GET['url_type'];

// $ver = time();
$tab_origin = $_GET['origin'];
$voucher = $dbNewNus->prepare("SELECT * FROM PPOB_PRODUCT WHERE TYPE = 11");
$voucher->execute();
$vouchergame = $voucher->get_result();
$voucher->close();

// print_r ($vouchergame);
$sqlBE = "SELECT be.ID
    FROM BUSINESS_ENTITY be
    LEFT JOIN USER_LIST ul ON ul.BE = be.ID
    WHERE ul.F_PIN = '$id_user'";

$queBE = $dbconnPalio->prepare($sqlBE);
$queBE->execute();
$resBE = $queBE->get_result()->fetch_assoc();
$c_id = $resBE["ID"];
$queBE->close();

$back_url = '';

$tab = '';
if ($tab_origin == '1') {
    $tab = 'tab1-main';
} else if ($tab_origin == '3') {
    $tab = 'tab3-main';
} else if ($tab_origin == '11') {
    $tab = 'tab1-main-only';
} else if ($tab_origin == '33') {
    $tab = 'tab3-main-only';
} else if ($tab_origin == '2') {
    $tab = 'tab3-profile';
}

if ($c_id == 282) { // gaspol
    $back_url = '/gaspol_web/pages/' . $tab . '?f_pin=' . $id_user;
} else {
    if ($tab_origin == 2) {
        $back_url = '/nexilis/pages/' . $tab . '?f_pin=' . $id_user . '&store_id=' . $store_id;
    } else {
        $back_url = '/nexilis/pages/' . $tab . '?f_pin=' . $id_user;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiPOS</title>
</head>

<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">

<style>
    /* FOR HTML NOT OFFSIDE */

    html,
    body {
        /* max-width: 100%;
        overflow-x: hidden; */
    }


    body {
        font-family: 'Poppins' !important;
        visibility: hidden;
    }

    <?php
    if ($env == 1) {
    ?>.header {
        height: 56px;
        width: 100%;
        background-color: #f06270;
        z-index: 10;
    }

    <?php
    } else {
    ?>.header {
        height: 56px;
        width: 100%;
        background-color: red;
        z-index: 10;
    }

    <?php
    }
    ?>.digipos-title {
        width: 7rem;
        height: 56px;
        display: block;
        margin: 0 auto;
        padding: 8px 0;
        color: white;
        font-size: 25px;
        font-weight: 700;
    }

    .horizontal-slide {
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        display: block;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .horizontal-slide::-webkit-scrollbar {
        display: none;
    }

    .horizontal-slide li {
        display: inline-block;
    }

    .nav {
        display: block !important;
    }

    .img-slider {
        width: auto;
        height: 32px;
        display: block;
        margin: auto;
        margin-bottom: 8px;
    }

    .text-slider {
        font-size: 11px;
        font-weight: 400;
        color: rgba(0, 0, 0, 0.54);
        display: block;
        margin-top: 2px;
        line-height: 1.35em;
        -webkit-transition: color 0.1s;
        transition: color 0.1s;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    <?php
    if ($env == 1) {
    ?>.section-main {
        /* background-color: #f06270; */
        background-image: url(https://ecs7.tokopedia.net/assets-tokopedia-lite/v2/loki/kratos/d2ee8660.svg);
        background-repeat: no-repeat;
        background-position: right;
        /* border-radius: 0px 0px 67px 67px; */
        height: 170px;
        background: linear-gradient(360deg, rgba(255, 255, 255, 1) 0%, rgba(240, 98, 112, 1) 100%);
    }

    <?php
    } else {
    ?>.section-main {
        /* background-color: #f06270; */
        background-image: url(https://ecs7.tokopedia.net/assets-tokopedia-lite/v2/loki/kratos/d2ee8660.svg);
        background-repeat: no-repeat;
        background-position: right;
        /* border-radius: 0px 0px 67px 67px; */
        height: 170px;
        background: linear-gradient(360deg, rgba(255, 255, 255, 1) 0%, red 100%);
    }

    <?php
    }
    ?><?php
        if ($env == 1) {
        ?>.pay-button {
        height: 40px;
        background-color: #f06270;
    }

    <?php
        } else {
    ?>.pay-button {
        height: 40px;
        background-color: red;
    }

    <?php
        }
    ?>.form-check-input:checked {
        background-color: #ed0226;
        border-color: #ed0226;
    }

    .pay-button#pay-with-qris:disabled {
        background-color: lightgray !important;
        color: #f06270;
    }
</style>

<body>

    <header class="header">
        <div class="container-fluid">
            <div class="row">
                <?php
                if ($env == 1) {
                ?>
                    <div class="col-2 d-flex justify-content-center" style="margin-top: 15px">
                        <!-- <a href="<?= $back_url ?>">
                        <span id="back-text" style="font-weight:bold; color:white;">Back</span>
                    </a> -->
                        <a onclick="window.history.back()">
                            <span id="back-text" style="font-weight:bold; color:white;">Back</span>
                        </a>
                    </div>
                <?php
                } else {
                ?>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <!-- <a href="<?= $back_url ?>" class="ms-4">
                        <span id="back-text" style="font-weight:bold; color:white;">Back</span>
                    </a> -->
                        <a onclick="window.history.back()" class="ms-4">
                            <span id="back-text" style="font-weight:bold; color:white;">Back</span>
                        </a>
                    </div>
                <?php
                }
                ?>

                <div class="col-8">

                    <div class="text-center">
                        <?php
                        if ($env == 1) {
                        ?>
                            <img src="../assets/img/Virtoo Assets-20220420T065146Z-001/GTM 2.0-Virtoo Assets/Logo/Virtoo_White.png" alt="" style="margin-top: -42px; height: 150px; width: 150px">
                        <?php
                        } else {
                        ?>
                            <span style="font-size: 35px; color: white; font-weight: 700">DigiPos</span>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-2">
                </div>
            </div>
        </div>
    </header>

    <div class="section-menu">
        <ul class="nav nav-tabs horizontal-slide gx-0">
            <li class="nav-item p-2" style="width: 100px" onclick="navPulsa()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/2/2/3f060ab5-9a19-43e5-bbe1-d1bd21c274ed.png">
                <span class="text-slider text-center">Pulsa</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navData()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/2/2/3f060ab5-9a19-43e5-bbe1-d1bd21c274ed.png">
                <span class="text-slider text-center">Paket Data</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navPLN()">
                <img class="img-slider" src=" https://images.tokopedia.net/img/SnKlQx/2022/2/2/866b38b0-baa5-4db7-bb23-09e717d05b8a.png">
                <span class="text-slider text-center">Listrik PLN</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navPDAM()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/2/7/05cab95b-f8db-4566-baf0-f65d5aa6395a.png">
                <span class="text-slider text-center">Air PDAM</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navBPJS()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/43ce1986-acdf-4535-80cb-49d73638cf96.png">
                <span class="text-slider text-center">BPJS</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navTelepon()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/5b533d76-76b8-48a3-b7b7-9fd70550adc0.png">
                <span class="text-slider text-center">Telepon PSTN</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navMultifinance()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2021/12/21/37b4cbb0-ea5e-41a2-90e0-120cc3fbb2f8.png">
                <span class="text-slider text-center">Multifinance</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navKereta()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/9ab4258c-cbb5-459e-8552-7a708e4082b5.png">
                <span class="text-slider text-center">Kereta Api</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navPesawat()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/a8f59a63-8c56-4208-9620-66178183b077.png">
                <span class="text-slider text-center">Pesawat</span>
            </li>
            <li class="nav-item p-2 selected" style="width: 100px; background-color: #e6e6e6" onclick="navVoucher()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/396f302d-14d7-4fb8-8457-1aba9f63d159.png">
                <span class="text-slider text-center">Voucher Game</span>
            </li>
        </ul>
    </div>

    <div class="section-main">
        <div class="container p-2">
            <div class="row gx-0 mt-3" style="background-color: #f0f3f7; border-radius: 8px; margin-left: 7px; margin-right: 7px; height: 38px">
                <div class="col-12 text-center">
                    <?php
                    if ($env == 1) {
                    ?>
                        <div id="voucher" style="margin:4px; background-color: #f06270; color: #FFFFFF; border-radius: 8px; font-size: 15px; height: 30px; padding-top: 3px">Voucher Game</div>
                    <?php
                    } else {
                    ?>
                        <div id="voucher" style="margin:4px; background-color: red; color: #FFFFFF; border-radius: 8px; font-size: 15px; height: 30px; padding-top: 3px">Voucher Game</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="game-list" class="container p-2">
            <div class="row p-3 mb-2 shadow" style="background-color: #FFFFFF; border-radius: 8px; margin-left: 7px; margin-right: 7px; margin-top: 5px">
                <div class="col-12 text-start">

                    <b>Voucher Game</b>
                    <div class="text-center">
                        <img src="../assets/img/play.png" style="width: 120px">
                    </div>

                    <select id="game-select" class="form-select mt-3" aria-label="Default select example">
                        <option value="" selected>Pilih Jenis Voucher</option>
                        <!-- <option value="1">Rp. 5000</option>
                    <option value="2">Rp. 10.000</option> -->

                        <?php
                        foreach ($vouchergame as $vg) {
                        ?>
                            <option data-price="<?= $vg['NEW_PRICE'] ?>" value="<?= $vg['CODE'] ?>"><?= $vg['DESCRIPTION'] ?></option>
                        <?php
                        }
                        ?>

                    </select>

                    <input type="number" pattern="\d*" class="form-control mt-3 mb-1" id="voucher-number" placeholder="User ID / Username Game" aria-describedby="emailHelp">

                    <div id="btn-voucher" class="btn btn-light mt-3 mb-2" style="width: 100%; border: 1px solid #cacaca; color: #cacaca" onclick="voucherCheck()">Beli Voucher</div>

                </div>
            </div>
        </div>
        <div id="game-info" class="container p-2">

            <!-- Append From JS -->

        </div>
    </div>

    <div id="section-purchase" class="row gx-0 p-3" style="margin-top: 280px">
        <select id="dropdownMenuSelectMethod" class="form-select" style="border: 1px solid #d7d7d7" onchange="selectMethod(this.value);">
            <option value="" selected>Select Payment Method</option>
            <option value="CARD">CARD</option>
            <option value="OVO">OVO</option>
            <option value="DANA">DANA</option>
            <option value="LINKAJA">LINKAJA</option>
            <option value="SHOPEEPAY">SHOPEEPAY</option>
            <option value="QRIS">QRIS</option>
        </select>
        <div class="col-12 pt-3 pb-3 d-flex justify-content-center">
            <!-- <div class="row gx-0" style="border: 1px solid #e5e5e5; border-radius: 5px; width: 100%">
            <div class="col-2 d-flex justify-content-center">
                <img src="../assets/img/voucher-icon.png" alt="" style="width: 30px; height: 30px; margin-top: 10px">
            </div>
            <div class="col-5 d-flex justify-content-start">
                <p style="font-size: 13px; margin-top: 16px" class="text-center">Pakai Voucher <span><img src="../assets/img/Virtoo Assets-20220420T065146Z-001/GTM 2.0-Virtoo Assets/Logo/Virtoo_Gradient.png" alt="" style="height: 50px; width: 50px; margin-top: -16px; position: absolute"></span></p>
            </div>
            <div class="col-5 d-flex justify-content-end">
                <p style="font-size: 13px; margin-top: 16px" class="me-4">Lihat</p>
            </div>
        </div> -->
        </div>
        <div class="col-12 d-flex justify-content-center">
            <div class="row gx-0 p-3" style="width: 100%; margin-top: -15px">
                <div class="col-6 d-flex justify-content-start">
                    <div class="row">
                        <div class="col-12">
                            <p class="ms-1 mb-0" style="font-size: 12px">Total Pembayaran</p>
                        </div>
                        <div class="col-12">
                            <p id="price-purchase" class="ms-1 mb-0" style="font-size: 14px; font-weight: 700">Rp 0</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <?php
                    if ($env == 1) {
                    ?>
                        <button class="btn text-light" style="background-color: #f06270" onclick="purchaseVoucher()">Bayar</button>
                    <?php
                    } else {
                    ?>
                        <button class="btn text-light" style="background-color: red" onclick="purchaseVoucher()">Bayar</button>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <footer id="footer" style="border-top: 1px solid #d7d7d7; margin-top: 430px; margin-bottom: 120px">
        <div class="row gx-0 p-1">
            <div class="col-12 d-flex justify-content-center mt-2">
                <?php
                if ($env == 1) {
                ?>
                    <span><img src="../assets/img/Virtoo Assets-20220420T065146Z-001/GTM 2.0-Virtoo Assets/Logo/Virtoo_Gradient.png" alt="" style="height: 100px; width: 100px"></span>
                <?php
                } else {
                ?>
                    <span class="p-4" style="font-size: 20px; color: red; font-weight: 700">DigiPos</span>
                <?php
                }
                ?>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <p style="font-size: 11px; color: #909090">© 2022 - <!-- -->2022<!-- --></p>
            </div>
            <!-- <div class="col-12 d-flex justify-content-center">
            <p style="font-size: 11px; color: #909090">Bantuan &nbsp; | &nbsp; Syarat & Ketentuan</p>
        </div> -->
        </div>
    </footer>

    <div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-addtocart" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0" id="modal-payment-body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-select-method" tabindex="-1" role="dialog" aria-labelledby="modal-select-method" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body mt-3 text-center" id="modal-select-method-body">
                    <p>Harap pilih metode pembayaran terlebih dahulu.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-payment-status" tabindex="-1" role="dialog" aria-labelledby="modal-payment-status" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Payment status
                </div>
                <div class="modal-body" id="modal-payment-status-body">
                </div>
                <div class="modal-footer">
                    <button style="background-color: #f06270" class="btn text-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="../assets/js/xendit.min.js"></script>

<script>
    // FOR AUTO SCROLLING MENU SELECTED 

    function horizontalScrollPos() {
        let selectedPos = 0;
        try {
            selectedPos = document.querySelector('.horizontal-slide .nav-item.selected').offsetLeft;
        } catch (e) {

        }
        document.querySelector('ul.horizontal-slide').scrollBy({
            left: selectedPos,
            behavior: 'smooth'
        });
    }

    window.onload = function() {
        horizontalScrollPos();
    }


    var product_global;
    var price;

    $('#section-purchase').hide();
    $('#game-info').hide();

    $("#voucher-number").bind("change paste keyup", function() {
        var number = $(this).val();
        var game = $("#game-select").val()

        // console.log(number);

        if (number && game != "") {

            $('#btn-voucher').attr('class', 'btn text-light mt-3 mb-2');

            <?php
            if ($env == 1) {
            ?>
                $('#btn-voucher').css('background-color', '#f06270');
                $('#btn-voucher').css('border', '#f06270');
            <?php
            } else {
            ?>
                $('#btn-voucher').css('background-color', 'red');
                $('#btn-voucher').css('border', 'red');
            <?php
            }
            ?>

        } else {

            $('#btn-voucher').attr('class', 'btn btn-light mt-3 mb-2');
            $('#btn-voucher').attr('style', 'background-color: none; width: 100%');
            $('#btn-voucher').css('color', '#cacaca');
            $('#btn-voucher').css('border', '1px solid #cacaca');

        }

    });

    function voucherCheck() {

        var checkUserID = $('#voucher-number').val();
        var checkGame = $('#game-select').val();

        if (checkUserID && checkGame) {

            $('#section-purchase').show();
            $('#footer').css('margin-top', '0px');

        }

    }

    $("#game-select").change(function() {

        var game = $(this).val();
        var number = $("#voucher-number").val()

        // console.log(number);

        if (number && game != "") {

            $('#btn-voucher').attr('class', 'btn text-light mt-3 mb-2');

            <?php
            if ($env == 1) {
            ?>
                $('#btn-voucher').css('background-color', '#f06270');
                $('#btn-voucher').css('border', '#f06270');
            <?php
            } else {
            ?>
                $('#btn-voucher').css('background-color', 'red');
                $('#btn-voucher').css('border', 'red');
            <?php
            }
            ?>

        } else {

            $('#btn-voucher').attr('class', 'btn btn-light mt-3 mb-2');
            $('#btn-voucher').attr('style', 'background-color: none; width: 100%');
            $('#btn-voucher').css('color', '#cacaca');
            $('#btn-voucher').css('border', '1px solid #cacaca');

        }

        var harga = $('#game-select').find(':selected').data('price');
        $('#price-purchase').text("Rp " + harga);

    });

    var f_pin = '<?= $id_user ?>';
    var env = new URLSearchParams(window.location.search).get('env');
    var store_id = new URLSearchParams(window.location.search).get('store_id');
    var urlType = new URLSearchParams(window.location.search).get('url_type');

    var tab_origin = new URLSearchParams(window.location.search).get('origin');

    function navPulsa() {

        window.location.replace('/nexilis/pages/digipos?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navData() {

        window.location.replace('/nexilis/pages/digipos-data?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navPLN() {

        window.location.replace('/nexilis/pages/digipos-pln?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navPDAM() {

        window.location.replace('/nexilis/pages/digipos-pdam?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navBPJS() {

        window.location.replace('/nexilis/pages/digipos_bpjs?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navTelepon() {

        window.location.replace('/nexilis/pages/digipos_pstn?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navMultifinance() {

        window.location.replace('/nexilis/pages/digipos_multifinance?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navKereta() {

        window.location.replace('/nexilis/pages/digipos-kereta?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navPesawat() {

        window.location.replace('/nexilis/pages/digipos-pesawat?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navVoucher() {

        window.location.replace('/nexilis/pages/digipos-voucher?f_pin=' + f_pin + '&origin=' + tab_origin + '&env=' + env + '&store_id=' + store_id + '&url_type=' + urlType);

    }

    $('body').on('click', '.game-box', function() {
        $ver;
        var images = $(this).data('img');
        var product = $(this).dpurchasePulata('propurchasePulduct');

        console.log(images);

        var html = `<div class="row p-3 mb-2 shadow" style="background-color: #FFFFFF; border-radius: 8px; margin-left: 7px; margin-right: 7px; margin-top: 5px"> 
                        <div class="col-12 text-center">

                            

                            <select class="form-select mt-3" aria-label="Default select example">
                                <option selected>Pilih Nominal</option>
                                // <option value="1">Rp. 5000</option>
                                // <option value="2">Rp. 10.000</option>
                            </select>

                            <input type="number" class="form-control mt-3 mb-1" id="voucher-number" placeholder="User ID" aria-describedby="emailHelp">

                            <div id="btn-voucher" class="btn btn-danger mt-3 mb-2" style="width: 100%; background-color: grey; border: 1px solid grey" onclick="voucherCheck()">Bayar</div>
                
                        </div>
                    </div>`;

        $('#game-info').html(html);

        $('#game-list').hide();
        $('#game-info').show();

    });

    function purchaseVoucher() {

        var command = "PURCHASE";
        var product = $('#game-select').val();
        var customer = $('#voucher-number').val();
        var partner_trxid = new Date().getTime().toString(16);
        var payment = $('#dropdownMenuSelectMethod').val();
        price = $('#game-select').find(':selected').data('price');

        if (payment == "" || payment == null) {
            $('#modal-select-method').modal('show');
            return;
        }

        var f_pin = '<?= $id_user ?>';

        var digipos_cart = {
            "command": command,
            "product": product,
            "customer": customer,
            "partner_trxid": partner_trxid,
            "amount": price,
            "method": payment,
            "f_pin": f_pin,
            "last_update": new Date().getTime()
        }

        console.log('cart', digipos_cart);

        localStorage.setItem('digipos_cart', JSON.stringify(digipos_cart));

        // console.log("Command : "+command);
        // console.log("Product : "+product);
        // console.log("Customer : "+customer);
        // console.log("Partner TRX ID : "+partner_trxid);

        palioPay();
        // vbotAPI(digipos_cart);

    }
</script>

<script src="../assets/js/cart_digipos.js?v=<?php echo time(); ?>"></script>