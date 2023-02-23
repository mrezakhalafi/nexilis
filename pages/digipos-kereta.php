<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dbconn = newnus();
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

// WILAYAH

$sqlData = "SELECT * FROM PPOB_PRODUCT WHERE TYPE = 99";

$queDATA = $dbconn->prepare($sqlData);
$queDATA->execute();
$wilayah = $queDATA->get_result();
$queDATA->close();
$tab_origin = $_GET['origin'];
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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<style>
    /* FOR HTML NOT OFFSIDE */

    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
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
    ?><?php
        if ($env == 1) {
        ?>.choice-tiket {
        background-color: #f06270;
    }

    <?php
        } else {
    ?>.choice-tiket {
        background-color: red;
    }

    <?php
        }
    ?><?php
        if ($env == 1) {
        ?>.form-check-input:checked {
        background-color: #f06270;
        border-color: #f06270;
    }

    <?php
        } else {
    ?>.form-check-input:checked {
        background-color: red;
        border-color: red;
    }

    <?php
        }
    ?>#modal-content-penumpang {
        position: absolute;
        bottom: 0;
    }

    .text-danger {
        font-weight: 700;
    }

    .pay-button#pay-with-qris:disabled {
        background-color: lightgray !important;
        color: #f06270;
    }

    /* */
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
            <li class="nav-item p-2 selected" style="width: 100px; background-color: #e6e6e6" onclick="navKereta()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/9ab4258c-cbb5-459e-8552-7a708e4082b5.png">
                <span class="text-slider text-center">Kereta Api</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navPesawat()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/a8f59a63-8c56-4208-9620-66178183b077.png">
                <span class="text-slider text-center">Pesawat</span>
            </li>
            <li class="nav-item p-2" style="width: 100px" onclick="navVoucher()">
                <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/396f302d-14d7-4fb8-8457-1aba9f63d159.png">
                <span class="text-slider text-center">Voucher Game</span>
            </li>
        </ul>
    </div>

    <div class="section-main"></div>
    <div class="container p-2" style="margin-top: -160px">
        <div class="row gx-0 mt-3" style="background-color: #f0f3f7; border-radius: 8px; margin-left: 7px; margin-right: 7px; height: 38px">
            <div class="col-12 text-center">
                <?php
                if ($env == 1) {
                ?>
                    <div id="kereta" style="margin:4px; background-color: #f06270; color: #FFFFFF; border-radius: 8px; font-size: 15px; height: 30px; padding-top: 3px">Kereta Api</div>
                <?php
                } else {
                ?>
                    <div id="kereta" style="margin:4px; background-color: red; color: #FFFFFF; border-radius: 8px; font-size: 15px; height: 30px; padding-top: 3px">Kereta Api</div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="container p-2">
        <div id="train-schedule" class="row p-3 mb-2 shadow" style="background-color: #FFFFFF; border-radius: 8px; margin-left: 7px; margin-right: 7px; margin-top: 5px">
            <div class="col-12 d-flex justify-content-start p-2">
                <h1 class="mb-0" style="font-size: 18px; font-weight: 600">Beli Tiket Kereta Api</h1>
            </div>

            <div class="row gx-0 p-2 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                <div id="pergi" class="col-5">
                    <p class="mb-0" style="font-size: 14px; color: var(--NN600,#6D7588)">Asal</p>
                    <br>
                    <p id="kota-asal" class="mb-0" style="margin-top: -27px; font-size: 25px; font-weight: 700">BD</p>
                    <br>
                    <p id="kode-kota" class="mb-0" style="margin-top: -27px; font-size: 14px">Bandung</p>
                </div>
                <div class="col-2 p-3">
                    <img id="switch" src="../assets/img/switch.png" alt="" style="width: 20px; height: 20px" class="mt-2">
                </div>
                <div id="balik" class="col-5">
                    <p class="mb-0 text-end" style="font-size: 14px; color: var(--NN600,#6D7588)">Tujuan</p>
                    <br>
                    <p id="kota-tujuan" class="mb-0 text-end" style="margin-top: -27px; font-size: 25px; font-weight: 700">CMI</p>
                    <br>
                    <p id="kode-kota-2" class="mb-0 text-end" style="margin-top: -27px; font-size: 14px">Cimahi</p>
                </div>
            </div>

            <div class="row gx-0 pt-3 p-2 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                <div class="col-12">
                    <p style="font-size: 14px; color: var(--NN600,#6D7588)" class="mb-0">Berangkat</p>
                </div>
                <div class="col-10" style="z-index: 999">
                    <input id="depart-date" type="date" style="width: 100%; border: none; background: transparent">
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <div class="form-check form-switch" style="margin-top: -20px">
                        <span style="font-size: 10px">Pulang?</span>
                        <input class="ms-1 form-check-input" type="checkbox" id="depart" onclick="showPulang()">
                    </div>
                </div>
            </div>

            <div id="pulang" class="row gx-0 pt-3 p-2 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                <div class="col-12">
                    <p style="font-size: 14px; color: var(--NN600,#6D7588)" class="mb-0">Pulang</p>
                </div>
                <div class="col-12">
                    <input id="back-date" type="date" style="width: 100%; border: none; background: transparent">
                </div>
            </div>

            <div id="penumpang" class="row gx-0 pt-3 p-2 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                <div class="col-12">
                    <p style="font-size: 14px; color: var(--NN600,#6D7588)" class="mb-0">Jumlah Penumpang</p>
                </div>
                <div class="col-12">
                    <p id="total-penumpang" class="mb-0"><span id="dewasaval">1</span> Dewasa<span id="anak">, <span id="anakval">1</span> anak</span><span id="bayi">, <span id="bayival">1</span> bayi</span></p>
                </div>
            </div>

            <div class="col-12">
                <?php
                if ($env == 1) {
                ?>
                    <div id="btn-cari-tiket" class="btn text-light mt-3 mb-2" style="width: 100%; background-color: #f06270; border: 1px solid #f06270" onclick="cariTiket()">Cari Tiket</div>
                <?php
                } else {
                ?>
                    <div id="btn-cari-tiket" class="btn text-light mt-3 mb-2" style="width: 100%; background-color: red; border: 1px solid #f06270" onclick="cariTiket()">Cari Tiket</div>
                <?php
                }
                ?>
            </div>
        </div>

        <div id="train-data" class="row p-3 mb-2 shadow" style="background-color: #FFFFFF; border-radius: 8px; margin-left: 7px; margin-right: 7px; margin-top: 5px">
            <div class="row gx-0 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                <div class="col-2">
                    <img id="back-btn" src="../assets/img/icons/Back-(White).png" alt="" style="width: 30px; height: 30px; margin-left: 10px; margin-top: 10px">
                </div>
                <div class="col-10">
                    <p class="mb-0" style="font-size: 18px; font-weight: 700"><span class="kota-cetak"></span> - <span class="kota-cetak-2"></span></p>
                    <p class="mb-0" style="color: var(--NN600,#6D7588)"><span class="tanggal-cetak"></span> | <span class="penumpang-cetak"></span></p>
                </div>
            </div>
            <div id="train-schedule-list">

                <!-- APPEND FROM DB -->

            </div>
            <div id="train-schedule-list-back" class="d-none">

                <!-- APPEND FROM DB -->

            </div>
        </div>

        <div id="section-penumpang" class="row p-3 mb-2 shadow" style="background-color: #FFFFFF; border-radius: 8px; margin-left: 7px; margin-right: 7px; margin-top: 5px">
            <div class="row gx-0 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                <div class="col-2">
                    <img id="back-btn-2" src="../assets/img/icons/Back-(White).png" alt="" style="width: 30px; height: 30px; margin-left: 10px; margin-top: 10px">
                </div>
                <div class="col-10">
                    <p class="mb-0" style="font-size: 18px; font-weight: 700"><span class="kota-cetak-confirm"></span> - <span class="kota-cetak-2-confirm"></span></p>
                    <p class="mb-0" style="color: var(--NN600,#6D7588)"><span class="tanggal-cetak-confirm"></span> | <span class="penumpang-cetak-confirm"></span></p>
                    <p id="return-cetak" class="mb-0 d-none" style="font-size: 18px; font-weight: 700"><span class="kota-cetak-confirm-return"></span> - <span class="kota-cetak-2-confirm-return"></span></p>
                    <p id="return-cetak-2" class="mb-0 d-none" style="color: var(--NN600,#6D7588)"><span class="tanggal-cetak-confirm-return"></span> | <span class="penumpang-cetak-confirm-return"></span></p>
                </div>
            </div>
            <div id="section-data-pemesan" class="row gx-0">
                <div class="col-12">
                    <h5><b>Data Pemesan (Untuk E-Tiket)</b></h5>
                </div>
                <div class="col-12 text-center">
                    <?php
                    if ($env == 1) {
                    ?>
                        <div id="btn-data-pemesan" class="choice-tiket btn text-light mt-3 mb-4 w-50" style="width: 100%; background-color: #f06270; border: 1px solid #f06270" onclick="isiDataPemesan()">Isi Data Pemesan</div>
                    <?php
                    } else {
                    ?>
                        <div id="btn-data-pemesan" class="choice-tiket btn text-light mt-3 mb-4 w-50" style="width: 100%; background-color: red; border: 1px solid #f06270" onclick="isiDataPemesan()">Isi Data Pemesan</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <!-- <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->
            <div id="section-data-penumpang" class="row gx-0">
                <div class="col-12 mt-2">
                    <h5><b>Data Penumpang</b></h5>
                </div>
                <div class="col-12 text-center">
                    <div id="btn-data-penumpang" class="btn btn-secondary mt-3 mb-2 w-50" style="width: 100%" onclick="isiDataPenumpang()">Isi Data Penumpang</div>
                </div>
            </div>
            <div class="col-12 text-center">
                <div id="btn-booking-tiket" class="btn btn-secondary mt-5 mb-2 w-75" style="width: 100%; height: 50px; padding-top: 12px" onclick="bookingTiket()">Booking Tiket</div>
            </div>
        </div>
    </div>
    </div>

    <div id="section-purchase" class="row gx-0 p-3">
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
                <div class="col-10 d-flex justify-content-start">
                    <div class="row">
                        <b class="mt-2">Info Pembayaran</b>
                        <div class="col-12 mt-3">
                            <p class="ms-1 mb-0" style="font-size: 12px">Transaction ID</p>
                        </div>
                        <div class="col-12">
                            <p id="name-purchase" class="ms-1 mb-0" style="font-size: 14px; font-weight: 700"></p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="ms-1 mb-0" style="font-size: 12px">Fee</p>
                        </div>
                        <div class="col-12">
                            <p id="fee-purchase" class="ms-1 mb-0" style="font-size: 14px; font-weight: 700">Rp 0</p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="ms-1 mb-0" style="font-size: 12px">Admin Fee</p>
                        </div>
                        <div class="col-12">
                            <p id="admin-purchase" class="ms-1 mb-0" style="font-size: 14px; font-weight: 700">Rp 0</p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="ms-1 mb-0" style="font-size: 12px">Total Fee</p>
                        </div>
                        <div class="col-12">
                            <p id="price-purchase" class="ms-1 mb-0" style="font-size: 14px; font-weight: 700">Rp 0</p>
                        </div>
                    </div>
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <?php
                    if ($env == 1) {
                    ?>
                        <button class="btn text-light" style="margin-top: 160px; background-color: #f06270" onclick="purchaseBooking()">Bayar</button>
                    <?php
                    } else {
                    ?>
                        <button class="btn text-light" style="margin-top: 160px; background-color: red" onclick="purchaseBooking()">Bayar</button>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-3" id="footer" style="border-top: 1px solid #d7d7d7; margin-bottom: 120px">
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

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body mt-3 text-center" id="modal-validation-body">
                    <p id="validation-text" style="font-weight: 700"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PENUMPANG -->
    <div class="modal fade" id="modal-penumpang" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div id="modal-content-penumpang" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Jumlah Penumpang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row p-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                        <div class="col-6">
                            <p class="mb-0">Dewasa</p>
                            <p class="mb-0" style="font-size: 12px; color: var(--N700,rgba(49,53,59,0.68))">Usia 12 tahun ke atas</p>
                        </div>
                        <div class="col-6 d-flex justify-content-center">
                            <button style="height: 30px; color: #f06270" class="btn btn-light col-2 min" onclick="minAdult()">-</button>
                            <input style="height: 30px" type="number" min="1" max="4" class="col-8 text-center" id="inputDewasa" readonly value="1" style="border-top: none">
                            <button style="height: 30px; color: #f06270" class="btn btn-light col-2 add" onclick="plusAdult()">+</button>
                        </div>
                    </div>
                    <div class="row p-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                        <div class="col-6">
                            <p class="mb-0">Anak-Anak</p>
                            <p class="mb-0" style="font-size: 12px; color: var(--N700,rgba(49,53,59,0.68))">Usia 3-12 tahun</p>
                        </div>
                        <div class="col-6 d-flex justify-content-center">
                            <button style="height: 30px; color: #f06270" class="btn btn-light col-2 min" onclick="minAnak()">-</button>
                            <input style="height: 30px" type="number" min="1" max="4" class="col-8 text-center" id="inputAnak" readonly value="0" style="border-top: none">
                            <button style="height: 30px; color: #f06270" class="btn btn-light col-2 add" onclick="plusAnak()">+</button>
                        </div>
                    </div>
                    <div class="row p-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                        <div class="col-6">
                            <p class="mb-0">Bayi</p>
                            <p class="mb-0" style="font-size: 12px; color: var(--N700,rgba(49,53,59,0.68))">Usia di bawah 3 tahun</p>
                        </div>
                        <div class="col-6 d-flex justify-content-center">
                            <button style="height: 30px; color: #f06270" class="btn btn-light col-2 min" onclick="minBayi()">-</button>
                            <input style="height: 30px" type="number" min="1" max="4" class="col-8 text-center" id="inputBayi" readonly value="0" style="border-top: none">
                            <button style="height: 30px; color: #f06270" class="btn btn-light col-2 add" onclick="plusBayi()">+</button>
                        </div>
                    </div>
                    <div class="row p-3">
                        <div class="col-12 d-flex justify-content-center">
                            <button id="btn-simpan" style="width: 100%; background-color: #f06270; color: #FFFFFF; border-radius: 8px" class="btn" onclick="simpanJumlahPenumpang()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PERGI -->
    <div class="modal fade" id="modal-pergi" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div id="modal-content-penumpang" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Asal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" style="text-transform: capitalize" disabled class="form-control" placeholder="Cari Stasiun..." id="cari-stasiun-berangkat">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p style="font-size: 18px; font-weight: 800">STASIUN POPULER</p>
                        </div>
                    </div>
                    <p id="loading-1">Sedang Memuat...</p>
                    <div id="list-stasiun-berangkat">

                        <!-- APPEND FROM DB -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PULANG -->
    <div class="modal fade" id="modal-pulang" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div id="modal-content-penumpang" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Tujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" style="text-transform: capitalize" disabled placeholder="Cari Stasiun..." id="cari-stasiun-pulang">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p style="font-size: 18px; font-weight: 800">STASIUN POPULER</p>
                        </div>
                    </div>
                    <p id="loading-2">Sedang Memuat...</p>
                    <div id="list-stasiun-pulang">

                        <!-- APPEND FROM DB -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PEMESAN -->

    <div class="modal fade" id="modal-data-pemesan" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold" id="exampleModalToggleLabel">Data Pemesan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" id="nama_depan_pemesan" placeholder="Nama Depan">
                            </div>
                            <small id="nama_depan_pemesan_error" class="text-danger d-none">This field is required.</small>
                        </div>
                        <div class="col-6">
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" id="nama_belakang_pemesan" placeholder="Nama Belakang">
                            </div>
                            <small id="nama_belakang_pemesan_error" class="text-danger d-none">This field is required.</small>
                        </div>
                    </div>
                    <div class="input-group mt-3">
                        <input type="text" class="form-control" id="email_pemesan" placeholder="Email">
                    </div>
                    <small id="email_pemesan_error" class="text-danger d-none">This field is required.</small>
                    <div class="input-group mt-3">
                        <input type="number" class="form-control" id="phone_pemesan" placeholder="Phone">
                    </div>
                    <small id="phone_pemesan_error" class="text-danger d-none">This field is required.</small>
                    <!-- <select class="form-select mt-3" id="salutation_pemesan" aria-label="Default select example">
                    <option value="" selected>Salutation</option>
                    <option value="Mr">Mr.</option>
                    <option value="Mrs">Mrs.</option>
                </select>
                <small id="salutation_pemesan_error" class="text-danger d-none">This field is required.</small> -->
                    <select class="form-select mt-3" id="type_pemesan" aria-label="Default select example">
                        <option value="" selected>Type</option>
                        <option value="adult">Adult</option>
                        <option value="child">Child</option>
                        <option value="baby">Baby</option>
                    </select>
                    <small id="type_pemesan_error" class="text-danger d-none">This field is required.</small>
                    <div class="input-group mt-3">
                        <small style="margin-right: 20px">Birth Date :</small>
                        <input type="date" class="form-control" id="birth_date_pemesan">
                    </div>
                    <small id="birth_date_pemesan_error" class="text-danger d-none">This field is required.</small>
                    <!-- <select class="form-select mt-3" id="nationality_pemesan" aria-label="Default select example">
                    <option value="" selected>Nationality</option>
                    <option value="id">Indonesia</option>
                    <option value="others">Foreign</option>
                </select>
                <small id="nationality_pemesan_error" class="text-danger d-none">This field is required.</small> -->
                    <div class="input-group mt-3">
                        <input type="number" class="form-control" id="card_number_pemesan" placeholder="Identity Number (KTP)">
                    </div>
                    <small id="card_number_pemesan_error" class="text-danger d-none">This field is required.</small>
                    <!-- <div class="input-group mt-3">
                    <small style="margin-right: 20px">Expiry Date :</small>
                    <input type="date" class="form-control" id="expiry_date_pemesan">
                </div>
                <small id="expiry_date_pemesan_error" class="text-danger d-none">This field is required.</small> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?php
                    if ($env == 1) {
                    ?>
                        <button type="button" class="btn text-light" style="background-color: #f06270" onclick="simpanPemesan()">Save</button>
                    <?php
                    } else {
                    ?>
                        <button type="button" class="btn text-light" style="background-color: red" onclick="simpanPemesan()">Save</button>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PENUMPANG -->

    <div class="modal fade" id="modal-data-penumpang" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-weight: bold" id="exampleModalToggleLabel">Data Penumpang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" id="nama_depan_penumpang" placeholder="Nama Depan">
                            </div>
                            <small id="nama_depan_penumpang_error" class="text-danger d-none">This field is required.</small>
                        </div>
                        <div class="col-6">
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" id="nama_belakang_penumpang" placeholder="Nama Belakang">
                            </div>
                            <small id="nama_belakang_penumpang_error" class="text-danger d-none">This field is required.</small>
                        </div>
                    </div>
                    <div class="input-group mt-3">
                        <input type="text" class="form-control" id="email_penumpang" placeholder="Email">
                    </div>
                    <small id="email_penumpang_error" class="text-danger d-none">This field is required.</small>
                    <div class="input-group mt-3">
                        <input type="number" class="form-control" id="phone_penumpang" placeholder="Phone">
                    </div>
                    <small id="phone_penumpang_error" class="text-danger d-none">This field is required.</small>
                    <!-- <select class="form-select mt-3" id="salutation_penumpang" aria-label="Default select example">
                    <option value="" selected>Salutation</option>
                    <option value="Mr">Mr.</option>
                    <option value="Mrs">Mrs.</option>
                </select>
                <small id="salutation_penumpang_error" class="text-danger d-none">This field is required.</small> -->
                    <select class="form-select mt-3" id="type_penumpang" aria-label="Default select example">
                        <option value="" selected>Type</option>
                        <option value="adult">Adult</option>
                        <option value="child">Child</option>
                        <option value="baby">Baby</option>
                    </select>
                    <small id="type_penumpang_error" class="text-danger d-none">This field is required.</small>
                    <div class="input-group mt-3">
                        <small style="margin-right: 20px">Birth Date :</small>
                        <input type="date" class="form-control" id="birth_date_penumpang">
                    </div>
                    <small id="birth_date_penumpang_error" class="text-danger d-none">This field is required.</small>
                    <!-- <select class="form-select mt-3" id="nationality_penumpang" aria-label="Default select example">
                    <option value="" selected>Nationality</option>
                    <option value="id">Indonesia</option>
                    <option value="others">Foreign</option>
                </select>
                <small id="nationality_penumpang_error" class="text-danger d-none">This field is required.</small> -->
                    <div class="input-group mt-3">
                        <input type="number" class="form-control" id="card_number_penumpang" placeholder="Identity Number (KTP)">
                    </div>
                    <small id="card_number_penumpang_error" class="text-danger d-none">This field is required.</small>
                    <!-- <div class="input-group mt-3">
                    <small style="margin-right: 20px">Expiry Date :</small>
                    <input type="date" class="form-control" id="expiry_date_penumpang">
                </div>
                <small id="expiry_date_penumpang_error" class="text-danger d-none">This field is required.</small> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?php
                    if ($env == 1) {
                    ?>
                        <button type="button" class="btn text-light" style="background-color: #f06270" onclick="simpanPenumpang()">Save</button>
                    <?php
                    } else {
                    ?>
                        <button type="button" class="btn text-light" style="background-color: red" onclick="simpanPenumpang()">Save</button>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-details" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Perjalanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Loading...

                    <!-- <div class="train-details">
            <div class="row">
                <div class="col-6">
                    <b>Nama Stasiun :</b>
                </div>
                <div class="col-6">
                    <p id="details-station"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Harga Orang Dewasa :</b>
                </div>
                <div class="col-6">
                    <p id="details-adult"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Harga Anak-anak :</b>
                </div>
                <div class="col-6">
                    <p id="details-child"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Harga Bayi :</b>
                </div>
                <div class="col-6">
                    <p id="details-baby"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Waktu Keberangkatan :</b>
                </div>
                <div class="col-6">
                    <p id="details-departure"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Waktu Tiba :</b>
                </div>
                <div class="col-6">
                    <p id="details-arrival"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Kelas :</b>
                </div>
                <div class="col-6">
                    <p id="details-class"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <b>Kursi Tersedia :</b>
                </div>
                <div class="col-6">
                    <p id="details-seat"></p>
                </div>
            </div>
        </div> -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    $(document).ready(function() {

        $("#depart-date").val(getToday());
        $("#back-date").val(getTomorrow());

    });

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


    $('#section-purchase').hide();
    $("#pulang").hide();
    $("#bayi").hide();
    $("#anak").hide();
    $("#train-data").hide();
    $('#section-penumpang').hide();

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
</script>

<!-- SPESIFIC SCRIPT FOR TRAIN -->

<script>
    function showPulang() {

        var depart = document.getElementById("depart");

        if (depart.checked == true) {
            $("#pulang").show();
        } else {
            $("#pulang").hide();
        }
    };

    function minAdult() {
        var number = parseInt($('#inputDewasa').val());

        if (number > 1) {
            number = number - 1;
        }

        $('#inputDewasa').val(number);
    }

    function plusAdult() {
        var number = parseInt($('#inputDewasa').val());

        if (number < 4) {
            number = number + 1;
        }

        $('#inputDewasa').val(number);
    }

    function minBayi() {
        var number = parseInt($('#inputBayi').val());

        if (number > 0) {
            number = number - 1;
        }

        $('#inputBayi').val(number);
    }

    function plusBayi() {
        var number = parseInt($('#inputBayi').val());
        if (number < 4) {
            number = number + 1;
            $('#inputBayi').val(number);
        }
    }

    function minAnak() {
        var number = parseInt($('#inputAnak').val());

        if (number > 0) {
            number = number - 1;
        }

        $('#inputAnak').val(number);
    }

    function plusAnak() {
        var number = parseInt($('#inputAnak').val());
        if (number < 4) {
            number = number + 1;
            $('#inputAnak').val(number);
        }
    }

    $("#penumpang").click(function() {
        $("#modal-penumpang").modal('show');
    });

    $("#pergi").click(function() {
        $("#modal-pergi").modal('show');
    });

    $("#balik").click(function() {
        $("#modal-pulang").modal('show');
    });

    $("#back-btn").click(function() {

        $("#train-data").hide();
        $("#train-schedule").show();

        $('#btn-cari-tiket').removeClass('btn-secondary');
        $('#btn-cari-tiket').css('background-color', '#f06270');
        $('#btn-cari-tiket').css('border', '1px solid #f06270');

        $('#section-purchase').hide();
    });

    $("#back-btn-2").click(function() {
        $("#section-penumpang").hide();
        $("#train-data").show();

        $('#section-purchase').hide();
    });

    function simpanJumlahPenumpang() {
        let dewasaval = document.getElementById("dewasaval").innerHTML;
        let bayival = document.getElementById("bayival").innerHTML;
        let anakval = document.getElementById("anakval").innerHTML;
        var inputdewasa = $("#inputDewasa").val();
        var inputanak = $('#inputAnak').val();
        var inputbayi = $("#inputBayi").val();

        document.getElementById("dewasaval").innerHTML = dewasaval.replace(dewasaval, inputdewasa);

        if (inputbayi > 0) {
            $("#bayi").show();
            document.getElementById("bayival").innerHTML = bayival.replace(bayival, inputbayi);
        } else {
            $("#bayi").hide();
        }

        if (inputanak > 0) {
            $("#anak").show();
            document.getElementById("anakval").innerHTML = anakval.replace(anakval, inputanak);
        } else {
            $("#anak").hide();
        }

        $("#modal-penumpang").modal('hide');
    };

    function getToday() {
        const local = new Date();
        local.setMinutes(local.getMinutes() - local.getTimezoneOffset());
        return local.toJSON().slice(0, 10);
    }

    function getTomorrow() {
        const local = new Date();
        local.setMinutes(local.getMinutes() - local.getTimezoneOffset());
        return local.toJSON().slice(0, 10);
    }
</script>

<!-- SCRIPT WITH API -->

<script>
    var obj;

    // LOOP ALL STATION WHEN PAGE OPEN

    function getTrainStation() {

        var command = "LIST_STATION";
        var product = "KERETA";

        var form_data = new FormData();
        form_data.append("command", command);
        form_data.append("product", product);

        let url = "../logics/digipos/vbot_list_station_airport";

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {

            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

                console.log(xmlHttp.responseText);

                obj = JSON.parse(xmlHttp.responseText).data.data;

                console.log(obj);

                obj.forEach((item) => {

                    var htmlStart = `<div class="row pt-3 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)" onclick="selectStart('` + item.code + `','` + item.name + `','')">
                                        <div class="col-12">
                                            <p id="` + item.code + `" class="mb-0" style="font-size: 1rem; font-weight: 700">` + item.code + `</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mt-2 mb-0" style="font-size: 12px; color: var(--N700,rgba(49,53,59,0.68))">` + item.name + `</p>
                                        </div>
                                    </div>`;

                    var htmlEnd = `<div class="row pt-3 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)" onclick="selectEnd('` + item.code + `','` + item.name + `','')">
                                        <div class="col-12">
                                            <p id="` + item.code + `" class="mb-0" style="font-size: 1rem; font-weight: 700">` + item.code + `</p>
                                        </div>
                                        <div class="col-12">
                                            <p class="mt-2 mb-0" style="font-size: 12px; color: var(--N700,rgba(49,53,59,0.68))">` + item.name + `</p>
                                        </div>
                                    </div>`;

                    $('#list-stasiun-berangkat').append(htmlStart);
                    $('#list-stasiun-pulang').append(htmlEnd);

                });

                $('#kota-asal').text(obj[0]['code']);
                $('#kode-kota').text(obj[0]['name']);
                $('#kota-tujuan').text(obj[3]['code']);
                $('#kode-kota-2').text(obj[3]['name']);

                $('#loading-1').hide();
                $('#loading-2').hide();

                $('#cari-stasiun-berangkat').attr('disabled', false);
                $('#cari-stasiun-pulang').attr('disabled', false);

                // localStorage.setItem('start_class','');
                // localStorage.setItem('end_class','');

            }
        }
        xmlHttp.open("post", url);
        xmlHttp.send(form_data);

    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // SEARCH STATION

    $("#cari-stasiun-berangkat").bind("change paste keyup", function() {

        var keyword = capitalizeFirstLetter($(this).val());

        if (keyword != null || keyword != "") {

            var obj_new;
            obj_new = obj.filter(o => o.name.includes(keyword));

        } else {

            obj_new = obj;

        }

        console.log("Search :" + obj_new);

        if (obj_new) {

            $('#list-stasiun-berangkat').html("");

            obj_new.forEach((item) => {

                var htmlStart = `<div class="row pt-3 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)" onclick="selectStart('` + item.code + `','` + item.name + `','')">
                                    <div class="col-12">
                                        <p id="` + item.code + `" class="mb-0" style="font-size: 1rem; font-weight: 700">` + item.code + `</p>
                                    </div>
                                    <div class="col-12">
                                        <p class="mt-2 mb-0" style="font-size: 12px; color: var(--N700,rgba(49,53,59,0.68))">` + item.name + `</p>
                                    </div>
                                </div>`;

                $('#list-stasiun-berangkat').append(htmlStart);

            });

            $('#loading-1').hide();
        }
    });

    $("#cari-stasiun-pulang").bind("change paste keyup", function() {

        var keyword = capitalizeFirstLetter($(this).val());

        if (keyword != null || keyword != "") {

            var obj_new;
            obj_new = obj.filter(o => o.name.includes(keyword));

        } else {

            obj_new = obj;

        }

        console.log("Search :" + obj_new);

        if (obj_new) {

            $('#list-stasiun-pulang').html("");

            obj_new.forEach((item) => {

                var htmlEnd = `<div class="row pt-3 pb-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)" onclick="selectEnd('` + item.code + `','` + item.name + `','')">
                                    <div class="col-12">
                                        <p id="` + item.code + `" class="mb-0" style="font-size: 1rem; font-weight: 700">` + item.code + `</p>
                                    </div>
                                    <div class="col-12">
                                        <p class="mt-2 mb-0" style="font-size: 12px; color: var(--N700,rgba(49,53,59,0.68))">` + item.name + `</p>
                                    </div>
                                </div>`;

                $('#list-stasiun-pulang').append(htmlEnd);

            });

            $('#loading-2').hide();
        }
    });

    // RUN GET STATION FUNCTION

    getTrainStation();

    // ON CHANGE START STATION

    function selectStart(start_short, start_long, train_class) {

        var station_short = start_short;
        var station_long = start_long;
        var start_class = train_class;

        $('#kota-asal').text(station_short);
        $('#kode-kota').text(station_long);

        localStorage.setItem("departure_code", station_short);
        localStorage.setItem("start_class", start_class);

        $('#modal-pergi').modal('hide');

    }

    // ON CHANGE END STATION

    function selectEnd(end_short, end_long, train_class) {

        var station_short = end_short;
        var station_long = end_long;
        var end_class = train_class;

        $('#kota-tujuan').text(station_short);
        $('#kode-kota-2').text(station_long);

        localStorage.setItem("arrival_code", station_short);
        localStorage.setItem("end_class", end_class);

        $('#modal-pulang').modal('hide');

    }

    // SWITCH STATION

    $("#switch").click(function() {

        let kotaAsal = document.getElementById("kota-asal").innerHTML;
        let kotaTujuan = document.getElementById("kota-tujuan").innerHTML;
        let kodeKotaAsal = document.getElementById("kode-kota").innerHTML;
        let kodeKotaTujuan = document.getElementById("kode-kota-2").innerHTML;

        document.getElementById("kota-asal").innerHTML = kotaAsal.replace(kotaAsal, kotaTujuan);
        document.getElementById("kode-kota").innerHTML = kodeKotaAsal.replace(kodeKotaAsal, kodeKotaTujuan);
        document.getElementById("kota-tujuan").innerHTML = kotaTujuan.replace(kotaTujuan, kotaAsal);
        document.getElementById("kode-kota-2").innerHTML = kodeKotaTujuan.replace(kodeKotaTujuan, kodeKotaAsal);

        localStorage.setItem("departure_code", kotaTujuan);
        localStorage.setItem("arrival_code", kotaAsal);

        // localStorage.setItem("start_class", localStorage.getItem('end_class'));
        // localStorage.setItem("end_class", localStorage.getItem('start_class'));

    });

    // SEARCH TICKET / GET SCHEDULE VBOT

    function cariTiket() {

        $('#btn-cari-tiket').css('background-color', 'grey');
        $('#btn-cari-tiket').css('border', '1px solid grey');

        $('.kota-cetak').text($('#kode-kota').text());
        $('.kota-cetak-2').text($('#kode-kota-2').text());
        $('.tanggal-cetak').text($('#depart-date').val());

        $('.penumpang-cetak').text($('#inputDewasa').val() + " Dewasa, " + $('#inputAnak').val() + " Anak, " + $('#inputBayi').val() + " Bayi");

        var command = "SCHEDULE";
        var product = "KERETA";
        var departure_code = $('#kota-asal').text();
        var arrival_code = $('#kota-tujuan').text();
        var date = $('#depart-date').val();
        var adult = $('#inputDewasa').val();

        // OPTIONAL

        var return_date = $('#back-date').val();
        var child = $('#inputAnak').val();
        var baby = $('#inputBayi').val();
        var train_class = "";
        var return_class = "";

        if ($('#depart').is(':checked')) {

            var data = {
                "departure_code": departure_code,
                "arrival_code": arrival_code,
                "date": date,
                "adult": adult,
                "return_date": return_date,
                "child": child,
                "infant": baby,
                "class": train_class,
                "return_class": return_class
            }

        } else {

            var data = {
                "departure_code": departure_code,
                "arrival_code": arrival_code,
                "date": date,
                "adult": adult,
                "child": child,
                "infant": baby,
                "class": train_class,
                "return_class": return_class
            }

        }

        var digipos_cart = {
            "command": command,
            "product": product,
            "data": btoa(JSON.stringify(data))
        }

        localStorage.setItem('digipos_cart', JSON.stringify(digipos_cart));

        vbotAPI(digipos_cart);
        console.log(data);
        console.log(digipos_cart);

    }

    // BOOKING TIKET

    function choiceTiketBerangkat(schedule_id, sub_class) {

        if ($('#depart').is(':checked')) {

            localStorage.setItem('schedule_id', schedule_id);
            localStorage.setItem('class_start', sub_class);

            var old_kota_start = $('.kota-cetak').text();
            var old_kota_end = $('.kota-cetak-2').text();

            $('.kota-cetak').text(old_kota_end);
            $('.kota-cetak-2').text(old_kota_start);
            $('.tanggal-cetak').text($('#back-date').val());

            $('#train-schedule-list').addClass('d-none');
            $('#train-schedule-list-back').removeClass('d-none');

        } else {

            localStorage.setItem('schedule_id', schedule_id);
            localStorage.setItem('class_start', sub_class);

            // SHOW FORM

            $('#train-schedule').hide();
            $('#train-data').hide();
            $('#section-penumpang').show();

            // NEW UP TEXT ONE WAY

            $('.kota-cetak-confirm').text($('#kode-kota').text());
            $('.kota-cetak-2-confirm').text($('#kode-kota-2').text());
            $('.tanggal-cetak-confirm').text($('#depart-date').val());
            $('.penumpang-cetak-confirm').text($('.penumpang-cetak').text());

        }

    }

    function choiceTiketTiba(schedule_id, sub_class) {

        localStorage.setItem('schedule_id_return', schedule_id);
        localStorage.setItem('class_end', sub_class);

        // SHOW FORM

        $('#train-schedule').hide();
        $('#train-data').hide();
        $('#section-penumpang').show();

        // NEW UP TEXT TWO WAY

        $('.kota-cetak-confirm').text($('#kode-kota').text());
        $('.kota-cetak-2-confirm').text($('#kode-kota-2').text());
        $('.tanggal-cetak-confirm').text($('#depart-date').val());
        $('.penumpang-cetak-confirm').text($('.penumpang-cetak').text());

        $('.kota-cetak-confirm-return').text($('#kode-kota-2').text());
        $('.kota-cetak-2-confirm-return').text($('#kode-kota').text());
        $('.tanggal-cetak-confirm-return').text($('#back-date').val());
        $('.penumpang-cetak-confirm-return').text($('.penumpang-cetak').text());

        $('#return-cetak').removeClass('d-none');
        $('#return-cetak-2').removeClass('d-none');

    }

    function isiDataPemesan() {
        $('#modal-data-pemesan').modal('show');
    }


    function isiDataPenumpang() {
        $('#modal-data-penumpang').modal('show');
    }

    var passengers_pemesan = {};
    var passengers_penumpang = [];
    var jumlah_penumpang = 1;

    function simpanPemesan() {

        var first_name = $('#nama_depan_pemesan').val();
        var last_name = $('#nama_belakang_pemesan').val();
        var email = $('#email_pemesan').val();
        var phone = $('#phone_pemesan').val();
        // var salutation = $('#salutation_pemesan').val();
        var type = $('#type_pemesan').val();
        var birth_date = $('#birth_date_pemesan').val();
        // var nationality = $('#nationality_pemesan').val();
        var card_number = $('#card_number_pemesan').val();
        // var card_expiry_date = $('#expiry_date_pemesan').val();

        if (!first_name) {
            $('#nama_depan_pemesan_error').removeClass('d-none');
        } else {
            $('#nama_depan_pemesan_error').addClass('d-none');
        }

        if (!last_name) {
            $('#nama_belakang_pemesan_error').removeClass('d-none');
        } else {
            $('#nama_belakang_pemesan_error').addClass('d-none');
        }

        if (!email) {
            $('#email_pemesan_error').removeClass('d-none');
        } else {
            $('#email_pemesan_error').addClass('d-none');
        }

        if (!phone) {
            $('#phone_pemesan_error').removeClass('d-none');
        } else {
            $('#phone_pemesan_error').addClass('d-none');
        }

        // if(!salutation){
        //     $('#salutation_pemesan_error').removeClass('d-none');
        // }else{
        //     $('#salutation_pemesan_error').addClass('d-none');
        // }

        if (!type) {
            $('#type_pemesan_error').removeClass('d-none');
        } else {
            $('#type_pemesan_error').addClass('d-none');
        }

        if (!birth_date) {
            $('#birth_date_pemesan_error').removeClass('d-none');
        } else {
            $('#birth_date_pemesan_error').addClass('d-none');
        }

        // if(!nationality){
        //     $('#nationality_pemesan_error').removeClass('d-none');
        // }else{
        //     $('#nationality_pemesan_error').addClass('d-none');
        // }

        if (!card_number) {
            $('#card_number_pemesan_error').removeClass('d-none');
        } else {
            $('#card_number_pemesan_error').addClass('d-none');
        }

        // if(!card_expiry_date){
        //     $('#expiry_date_pemesan_error').removeClass('d-none');
        // }else{
        //     $('#expiry_date_pemesan_error').addClass('d-none');
        // }

        if (first_name && last_name && email && phone && type && birth_date) {

            $('#modal-data-pemesan').modal('hide');

            passengers_pemesan = {
                "first_name": first_name,
                "last_name": last_name,
                "email": email,
                "phone": phone,
                "primary": true, // As Pemesan
                // "salutation": salutation,
                "type": type,
                "birth_date": birth_date,
                // "nationality": nationality,
                "card_number": card_number
                // "card_expiry_date": card_expiry_date
            };

            passengers_penumpang.push(passengers_pemesan);

            console.log(passengers_pemesan);
            $('#btn-data-penumpang').addClass('text-light');
            <?php
            if ($env == 1) {
            ?>
                $('#btn-data-penumpang').attr("style", "width: 100%; background-color: #f06270; border: 1px solid #f06270")
            <?php
            } else {
            ?>
                $('#btn-data-penumpang').attr("style", "width: 100%; background-color: red; border: 1px solid #f06270")
            <?php
            }
            ?>

            $('#section-data-pemesan').append('<p style="margin-top: 10px"><b>Penumpang ' + jumlah_penumpang + '</b></p>');
            $('#section-data-pemesan').append('<div><b>Nama :</b> ' + first_name + " " + last_name + '</div>');
            $('#section-data-pemesan').append('<div><b>Email :</b> ' + email + '</div>');
            $('#section-data-pemesan').append('<div><b>Phone :</b> ' + phone + '</div>');

            jumlah_penumpang = jumlah_penumpang + 1;

        } else {

            $('#modal-data-pemesan').modal('hide');
            $('#validation-text').text('Lengkapi Data Diri Pemesan');
            $('#modal-validation').modal('show');

        }
    }

    function simpanPenumpang() {

        var first_name = $('#nama_depan_penumpang').val();
        var last_name = $('#nama_belakang_penumpang').val();
        var email = $('#email_penumpang').val();
        var phone = $('#phone_penumpang').val();
        // var salutation = $('#salutation_penumpang').val();
        var type = $('#type_penumpang').val();
        var birth_date = $('#birth_date_penumpang').val();
        // var nationality = $('#nationality_penumpang').val();
        var card_number = $('#card_number_penumpang').val();
        // var card_expiry_date = $('#expiry_date_penumpang').val();

        if (!first_name) {
            $('#nama_depan_penumpang_error').removeClass('d-none');
        } else {
            $('#nama_depan_penumpang_error').addClass('d-none');
        }

        if (!last_name) {
            $('#nama_belakang_penumpang_error').removeClass('d-none');
        } else {
            $('#nama_belakang_penumpang_error').addClass('d-none');
        }

        if (!email) {
            $('#email_penumpang_error').removeClass('d-none');
        } else {
            $('#email_penumpang_error').addClass('d-none');
        }

        if (!phone) {
            $('#phone_penumpang_error').removeClass('d-none');
        } else {
            $('#phone_penumpang_error').addClass('d-none');
        }

        // if(!salutation){
        //     $('#salutation_penumpang_error').removeClass('d-none');
        // }else{
        //     $('#salutation_penumpang_error').addClass('d-none');
        // }

        if (!type) {
            $('#type_penumpang_error').removeClass('d-none');
        } else {
            $('#type_penumpang_error').addClass('d-none');
        }

        if (!birth_date) {
            $('#birth_date_penumpang_error').removeClass('d-none');
        } else {
            $('#birth_date_penumpang_error').addClass('d-none');
        }

        // if(!nationality){
        //     $('#nationality_penumpang_error').removeClass('d-none');
        // }else{
        //     $('#nationality_penumpang_error').addClass('d-none');
        // }

        if (!card_number) {
            $('#card_number_penumpang_error').removeClass('d-none');
        } else {
            $('#card_number_penumpang_error').addClass('d-none');
        }

        // if(!card_expiry_date){
        //     $('#expiry_date_penumpang_error').removeClass('d-none');
        // }else{
        //     $('#expiry_date_penumpang_error').addClass('d-none');
        // }

        if (first_name && last_name && email && phone && type && birth_date) {

            $('#modal-data-penumpang').modal('hide');

            pnmp = {
                "first_name": first_name,
                "last_name": last_name,
                "email": email,
                "phone": phone,
                "primary": false, // As Pemesan
                // "salutation": salutation,
                "type": type,
                "birth_date": birth_date,
                // "nationality": nationality,
                "card_number": card_number
                // "card_expiry_date": card_expiry_date
            };

            passengers_penumpang.push(pnmp);

            console.log(passengers_penumpang);

            $('#nama_depan_penumpang').val("");
            $('#nama_belakang_penumpang').val("");
            $('#email_penumpang').val("");
            $('#phone_penumpang').val("");
            $('#salutation_penumpang').val("");
            $('#type_penumpang').val("");
            $('#birth_date_penumpang').val("");
            $('#nationality_penumpang').val("");
            $('#card_number_penumpang').val("");
            $('#expiry_date_penumpang').val("");

            $('#btn-booking-tiket').addClass('btn-success');

            $('#section-data-penumpang').append('<p style="margin-top: 10px"><b>Penumpang ' + jumlah_penumpang + '</b></p>');
            $('#section-data-penumpang').append('<div><b>Nama :</b> ' + first_name + " " + last_name + '</div>');
            $('#section-data-penumpang').append('<div><b>Email :</b> ' + email + '</div>');
            $('#section-data-penumpang').append('<div><b>Phone :</b> ' + phone + '</div>');

            jumlah_penumpang = jumlah_penumpang + 1;

        } else {

            $('#modal-data-penumpang').modal('hide');
            $('#validation-text').text('Lengkapi Data Diri Penumpang.');
            $('#modal-validation').modal('show');

        }
    }

    function bookingTiket() {

        var command = "BOOKING";
        var product = "KERETA";
        var partner_trxid = new Date().getTime().toString(16);

        var departure_code = $('#kota-asal').text();
        var arrival_code = $('#kota-tujuan').text();
        var date = $('#depart-date').val();
        var adult = $('#inputDewasa').val();

        var schedule_id = localStorage.getItem("schedule_id");
        var schedule_id_return = localStorage.getItem("schedule_id_return");

        var classes = localStorage.getItem('class_start');
        var return_classes = localStorage.getItem('class_end');

        // OPTIONAL

        var return_date = $('#back-date').val();
        var child = $('#inputAnak').val();
        var baby = $('#inputBayi').val();

        var passengers = passengers_penumpang;

        // CANNOT CLICK BOOKING TICKET IF PEMESAN IS NULL

        if (passengers_penumpang.length > 0) {

            $('#btn-data-pemesan').removeClass('text-light');
            $('#btn-data-pemesan').addClass('btn-secondary');
            $('#btn-data-pemesan').attr("style", "width: 100%");
            $('#btn-data-penumpang').removeClass('text-light');
            $('#btn-data-penumpang').attr("style", "width: 100%");
            $('#btn-booking-tiket').removeClass('btn-success');

            if ($('#depart').is(':checked')) {

                var data = {
                    "departure_code": departure_code,
                    "arrival_code": arrival_code,
                    "date": date,
                    "return_date": return_date,
                    "adult": adult,
                    "child": child,
                    "class": classes,
                    "return_class": return_classes,
                    "infant": baby,
                    "schedule_id": schedule_id,
                    "return_schedule_id": schedule_id_return,
                    "passengers": passengers
                }

            } else {

                var data = {
                    "departure_code": departure_code,
                    "arrival_code": arrival_code,
                    "date": date,
                    "adult": adult,
                    "child": child,
                    "class": classes,
                    "infant": baby,
                    "schedule_id": schedule_id,
                    "passengers": passengers
                }
            }

            var digipos_cart = {
                "command": command,
                "product": product,
                "partner_trxid": partner_trxid,
                "data": btoa(JSON.stringify(data))
            }

            localStorage.setItem('digipos_cart', JSON.stringify(digipos_cart));

            console.log(passengers);
            console.log(data);
            console.log(digipos_cart);

            vbotAPI(digipos_cart);

        } else {

            $('#modal-data-pemesan').modal('hide');
            $('#validation-text').text('Harap Isi Data Diri Pemesan Dan Penumpang Terlebih Dahulu.');
            $('#modal-validation').modal('show');

        }

    }

    var trx_id;
    var amount_price;
    var admin_fee;
    var total_price;

    function purchaseBooking() {

        var command = "CONFIRM";
        var product = "KERETA";
        var trxid = trx_id;
        var partner_trxid = new Date().getTime().toString(16);
        var amount = total_price;
        var payment = $('#dropdownMenuSelectMethod').val();

        if (payment == "" || payment == null) {
            $('#modal-select-method').modal('show');
            return;
        }

        var f_pin = '<?= $id_user ?>';

        var digipos_cart = {
            "command": command,
            "product": product,
            "trxid": trxid,
            "partner_trxid": partner_trxid,
            "amount": amount,
            // "method": payment,
            "f_pin": f_pin,
            "last_update": new Date().getTime()
        }

        localStorage.setItem('digipos_cart', JSON.stringify(digipos_cart));

        // console.log(digipos_cart);
        // palioPay();
        vbotAPI(digipos_cart);

    }
</script>

<script src="../assets/js/cart_digipos.js?v=<?php echo time(); ?>"></script>