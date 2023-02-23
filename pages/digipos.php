<?php

	// ini_set('display_errors', 1); 
	// ini_set('display_startup_errors', 1); 
	// error_reporting(E_ALL);

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
    $dbNewNus = newnus();

	// GET F_PIN

    $id_user = $_GET['f_pin'];

    $queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$id_user' OR IMEI = '$id_user'";
    $queIMEI = $dbconn->prepare($queryCheckFpin);
    $queIMEI->execute();
    $resIMEI = $queIMEI->get_result()->fetch_assoc();
    $id_user = $resIMEI['F_PIN'];
    $queIMEI->close();

    $store_id = $_GET['store_id'];
    $url_type = $_GET['url_type'];
    // print_r($store_id);
    if (!isset($_GET['env'])) {
        $env = 1;
    } else {
        $env = $_GET['env'];
    }
    
    $tab_origin = $_GET['origin'];

	// SELECT USER TRANSACTION

	$query = $dbNewNus->prepare("SELECT * FROM DIGIPOS_PURCHASE LEFT JOIN PPOB_PRODUCT ON DIGIPOS_PURCHASE.PRODUCT_CODE = PPOB_PRODUCT.CODE 
                                LEFT JOIN PPOB_PRODUCT_TYPE ON PPOB_PRODUCT.TYPE = PPOB_PRODUCT_TYPE.ID WHERE F_PIN = '".$id_user."'");
	$query->execute();
	$transaction = $query->get_result();
	$query->close();

    $sqlBE = "SELECT be.ID
    FROM BUSINESS_ENTITY be
    LEFT JOIN USER_LIST ul ON ul.BE = be.ID
    WHERE ul.F_PIN = '$id_user'";

    $queBE = $dbconn->prepare($sqlBE);
    $queBE->execute();
    $resBE = $queBE->get_result()->fetch_assoc();
    $c_id = $resBE["ID"];
    $queBE->close();

    $back_url = '';

    $tab = '';
    if ($tab_origin == '1') {
        $tab = 'tab1-main';
    } else if($tab_origin == '3'){
        $tab = 'tab3-main';
    } else if($tab_origin == '11'){
        $tab = 'tab1-main-only';
    } else if($tab_origin == '33'){
        $tab = 'tab3-main-only';
    } else if($tab_origin == '2'){
        $tab = 'tab3-profile';
    }

    if ($c_id == 282) { // gaspol
        $back_url = '/gaspol_web/pages/'.$tab.'?f_pin=' . $id_user;
    } else {
        if ($tab_origin == 2) {
            $back_url = '/nexilis/pages/'.$tab.'?f_pin=' . $id_user . '&store_id=' . $store_id ;
        }
        else {
            $back_url = '/nexilis/pages/'.$tab.'?f_pin=' . $id_user;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtoo</title>
</head>

<link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
<script src="../assets/js/xendit.min.js"></script>
<script src="../assets/js/qrcode.min.js"></script>

<style>
    /* FOR HTML NOT OFFSIDE */

    html,
    body {
        /* max-width: 100%; */
        /* overflow-x: hidden; */
    }


    body {
        font-family: 'Poppins' !important;
        visibility: hidden;
    }

    div.container-fluid {
        /* padding:0; */
    }

    body.modal-open {
        /* position:fixed; */
        overflow-y: hidden !important;
        padding: 0 !important;
        /* height: 100vh !important; */
    }

    <?php
    if ($env == 1) {
        ?>
        .header {
            height: 56px;
            width: 100%;
            background-color: #f06270;
            z-index: 10;
        }
        <?php
    }
    else {
        ?>
        .header {
            height: 56px;
            width: 100%;
            background-color: red;
            z-index: 10;
        }
        <?php
    }
    ?>

    .digipos-title {
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
        ?>
        .section-main{
            /* background-color: #f06270; */
            background-image: url(https://ecs7.tokopedia.net/assets-tokopedia-lite/v2/loki/kratos/d2ee8660.svg);
            background-repeat: no-repeat;
            background-position: right;
            /* border-radius: 0px 0px 67px 67px; */
            height: 170px;
            background: linear-gradient(360deg, rgba(255,255,255,1) 0%, rgba(240,98,112,1) 100%);
        }
        <?php
    }
    else {
        ?>
        .section-main{
            /* background-color: #f06270; */
            background-image: url(https://ecs7.tokopedia.net/assets-tokopedia-lite/v2/loki/kratos/d2ee8660.svg);
            background-repeat: no-repeat;
            background-position: right;
            /* border-radius: 0px 0px 67px 67px; */
            height: 170px;
            background: linear-gradient(360deg, rgba(255,255,255,1) 0%, red 100%);
        }
        <?php
    }
    ?>
    
    <?php
    if ($env == 1) {
        ?>
        .pay-button {
            height: 40px;
            background-color: #f06270;
        }
        <?php
    }

    else {
        ?>
        .pay-button {
            height: 40px;
            background-color: red;
        }
        <?php
    }
    ?>

    <?php
    if ($env == 1) {
        ?>
        .tab-content {
            color: #f06270;
            font-weight: bold;
        }
        <?php
    }
    else {
        ?>
        .tab-content {
            color: red;
            font-weight: bold;
        }
        <?php
    }
    ?>

    .pay-button#pay-with-qris:disabled {
        background-color:lightgray !important;
        color: #f06270;
    }
</style>

<body>

<header class="header">
    <div class="container-fluid">
        <div class="row">
            <?php
            if ($env == 1){
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
            }
            else {
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
                    if ($env == 1){
                        ?>
                        <img src="../assets/img/Virtoo Assets-20220420T065146Z-001/GTM 2.0-Virtoo Assets/Logo/Virtoo_White.png" alt="" style="margin-top: -42px; height: 150px; width: 150px">
                        <?php
                    }
                    else {
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

<div class="container-fluid section-menu">
    <ul class="nav nav-tabs horizontal-slide gx-0">
        <li class="nav-item p-2" style="width: 100px; background-color: #e6e6e6" onclick="navPulsa()">
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
        <li class="nav-item p-2" style="width: 100px" onclick="navVoucher()">
            <img class="img-slider" src="https://images.tokopedia.net/img/SnKlQx/2022/1/6/396f302d-14d7-4fb8-8457-1aba9f63d159.png">
            <span class="text-slider text-center">Voucher Game</span>
        </li>
    </ul>
</div>

<div class="container-fluid section-main">
    <div class="container p-2">
        <div class="row gx-0 mt-3" style="background-color: #f0f3f7; border-radius: 8px; margin-left: 7px; margin-right: 7px; height: 38px">
            <div class="col-12 text-center" onclick="navPrabayar()">
                <?php
                if ($env == 1) {
                    ?>
                    <div id="prabayar" style="margin:4px; background-color: #f06270; color: #FFFFFF; border-radius: 8px; font-size: 15px; height: 30px; padding-top: 3px">Pulsa</div>
                    <?php
                }
                else {
                    ?>
                    <div id="prabayar" style="margin:4px; background-color: red; color: #FFFFFF; border-radius: 8px; font-size: 15px; height: 30px; padding-top: 3px">Pulsa</div>
                    <?php 
                }
                ?>
            </div>
            <!-- <div class="col-6 text-center" onclick="navPascabayar()">
                <div id="pascabayar" style="margin:4px; background-color: #f0f3f7; color: rgba(0,0,0,0.54); border-radius: 8px; font-size: 15px; height: 30px; padding-top: 3px">Pascabayar</div>
            </div> -->
        </div>
    </div>
    <div class="container p-2">
        <div class="row p-3 mb-2 shadow" style="background-color: #FFFFFF; border-radius: 8px; margin-left: 7px; margin-right: 7px; margin-top: 5px">
            <div class="col-12">
                <input type="number" pattern="\d*" class="form-control mt-3 mb-1" id="phone-number" placeholder="Input Nomor HP" aria-describedby="emailHelp">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid section-pulsa mt-2">
    <div class="row p-3" style="height: 30px; display: inline">
        <div class="col-12 text-center shadow pb-2 pt-2" style="border-bottom: 2px solid #f06270">
            <span style="color: #f06270">Pulsa</span>
        </div>
        <p class="m-2 mt-4"><b>Provider</b></p>
        <img id="provider-image" height="auto" style="width: 150px" src="">
        <p class="m-2 mt-4 mb-3"><b>Pilih Nominal</b></p>
        <div id="list-product" class="row px-0 mx-auto">

            <!-- Append From JS -->

        </div>
    </div>
</div>



<div class="container-fluid section-promo mt-2 pt-2 pb-2">
    <ul class="nav">
        <?php
        if ($env == 1) {
            ?>
            <div class="row gx-0 shadow pt-2 pb-2" style="border-bottom: 2px solid #f06270">
                <div class="col-12 d-flex justify-content-center" onclick="navTransaction()">
                    <li class="nav-item">
                        <?php
                        if ($env == 1) {
                            ?>
                            <a id="transaction-text" style="color: #f06270; font-size: 14px" class="nav-link active" aria-current="page" href="#">Transaksi Terakhir</a>
                            <?php
                        }
                        else {
                            ?>
                            <a id="transaction-text" style="color: red; font-size: 14px" class="nav-link active" aria-current="page" href="#">Transaksi Terakhir</a>
                            <?php
                        }
                        ?>
                    </li>
                </div>
                <!-- <div class="col-6 d-flex justify-content-center" onclick="navPromo()">
                    <li class="nav-item">
                        <a id="promo-text" style="color: rgba(0,0,0,0.54)" class="nav-link" href="#">Promo</a>
                    </li>
                </div> -->
            </div>
            <?php
        }
        else {
            ?>
            <div class="row gx-0 shadow pt-2 pb-2" style="border-bottom: 2px solid red">
                <div class="col-12 d-flex justify-content-center" onclick="navTransaction()">
                    <li class="nav-item">
                        <?php
                        if ($env == 1) {
                            ?>
                            <a id="transaction-text" style="color: #f06270; font-size: 14px" class="nav-link active" aria-current="page" href="#">Transaksi Terakhir</a>
                            <?php
                        }
                        else {
                            ?>
                            <a id="transaction-text" style="color: red; font-size: 14px" class="nav-link active" aria-current="page" href="#">Transaksi Terakhir</a>
                            <?php
                        }
                        ?>
                    </li>
                </div>
                <!-- <div class="col-6 d-flex justify-content-center" onclick="navPromo()">
                    <li class="nav-item">
                        <a id="promo-text" style="color: rgba(0,0,0,0.54)" class="nav-link" href="#">Promo</a>
                    </li>
                </div> -->
            </div>
            <?php
        }
        ?>
    </ul>

    <?php if (mysqli_num_rows($transaction) > 0):

        foreach($transaction as $tr): 
        
            $seconds = $tr['LAST_UPDATE'] / 1000;

        ?>
            <div class="card m-3">
                <div class="card-body">
                    <div class="row">
                        <div><b><?= $tr['DESCRIPTION'] ?></b></div>
                        <div class="col-6">
                            <div style="color: #e59922"><b><?= $tr['PRODUCT_CODE'] ?></b></div>
                            <div style="color: grey; font-size: 13px">Rp. <?= $tr['AMOUNT'] ?></div>
                        </div>
                        <div class="col-6">

                            <div><b><?= $tr['CUSTOMER_ID'] ?></b></div>
                            <div style="color: grey; font-size: 13px"><?= date("d/m/Y H:i:s", $seconds) ?></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach;
    endif; ?>

    <div class="row gx-0">
        <div class="col-12 justify-content-center">
            <div class="row gx-0">
                <div class="col-12 p-4 d-flex justify-content-center">
                    <?php
                    if ($env == 1) {
                        ?>
                        <h3 class="mb-0 mt-2" style="font-weight: 700; font-size: 20px">Pengalaman beli pulsa di <span><img src="../assets/img/Virtoo Assets-20220420T065146Z-001/GTM 2.0-Virtoo Assets/Logo/Virtoo_Gradient.png" alt="" style="height: 70px; width: 70px; margin-top: -2px; margin-left: -5px"></span></h3>
                        <?php
                    }
                    else {
                        ?>
                        <h3 class="mb-0 mt-2" style="font-weight: 700; font-size: 20px">Pengalaman beli pulsa di <span style="font-size: 20px; color: red">DigiPos</span></h3>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="container">
                <div class="row" style="font-size: 14px">
                    <div class="col-5">
                        <img src="../assets/img/undraw-digipos.png" style="width: 95%; height: auto; margin-left: 10px">
                    </div>
                    <div class="col-7">
                        <p style="margin-top: 35px">Transaksi <span class="tab-content">cepat</span> dan <span class="tab-content">aman</span></p>
                    </div>
                </div>
                <div class="row" style="font-size: 14px">
                    <div class="col-5">
                        <img src="../assets/img/undraw-digipos-2.png" style="width: 100%; height: auto">
                    </div>
                    <div class="col-7">
                        <p style="margin-top: 35px">Banyak <span class="tab-content">metode</span> pembayaran</p>
                    </div>
                </div>
                <div class="row" style="font-size: 14px">
                    <div class="col-5">
                        <img src="../assets/img/undraw-digipos-3.png" style="width: 100%; height: auto">
                    </div>
                    <div class="col-7">
                        <p style="margin-top: 35px">Semua <span class="tab-content">kebutuhanmu</span> di satu tempat</p>
                    </div>
                </div>
            </div>

            <!-- <div class="row gx-0">
                <div class="col-12 p-3 d-flex justify-content-center">
                    <h1 class="mb-0"><span style="font-size: 56px; font-weight: 700">4.5</span>/5</h1>
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <img src="../assets/img/rating-star.png" alt="" style="width: 135px; height: 45px">
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <p>1000 Ulasan</p>
                </div>
            </div> -->
        </div>
    </div>

    <div class="row gx-0 mt-5">
        <div class="col-12 d-flex justify-content-center text-center">
            <?php
            if ($env == 1) {
                ?>
                <p style="font-size: 14px; color: grey">Isi Pulsa Online Murah, Cepat, Mudah &amp; Aman <br/> Mulai Aja Dulu di <span><img src="../assets/img/Virtoo Assets-20220420T065146Z-001/GTM 2.0-Virtoo Assets/Logo/Virtoo_Gradient.png" alt="" style="height: 50px; width: 50px; margin-top: -2px; margin-left: -3px"></span></p>
                <?php
            }
            else {
                ?>
                <p style="font-size: 14px; color: grey">Isi Pulsa Online Murah, Cepat, Mudah &amp; Aman <br/> Mulai Aja Dulu di <span style="font-size: 14px; color: red; font-weight: 700">DigiPos</span></p>
                <?php
            }
            ?>
        </div>
        <!-- <div class="col-12 d-flex justify-content-center text-center">
            <p style="color: #626262">Promo Pulsa dan Paket Data di Bulan Ini!</p>
        </div> -->
    </div>

    <div class="row gx-0">
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/simpati_2.png" alt="Pulsa simPATI" style="width: 60px; height: 30px; margin-top: 8px"><br>
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa simPATI</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/as_2.png" alt="Pulsa Kartu As" style="width: 60px; height: 30px; margin-top: 8px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa Kartu As</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/mentari_2.png" alt="Pulsa Mentari Ooredoo" style="width: 60px; height: 30px; margin-top: 7px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa Mentari<br>Ooredoo</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/im3_2.png" alt="Pulsa IM3 Ooredoo" style="width: 60px; height: 30px; margin-top: 7px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa IM3 Ooredoo</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/xl_3.png" alt="Pulsa XL" style="width: 60px; height: 30px; margin-top: 7px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa XL</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/axis_2.png" alt="Pulsa Axis" style="width: 60px; height: 30px; margin-top: 7px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa Axis</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/smartfren_2.png" alt="Pulsa SmartFren" style="width: 60px; height: 30px; margin-top: 7px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa SmartFren</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/tri_2.png" alt="Pulsa Tri" style="width: 60px; height: 30px; margin-top: 7px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Pulsa Tri</p>
        </div>
        <div class="col-4 text-center mt-3">
            <img src="https://ecs7.tokopedia.net/img/recharge/operator/pln_2.png" alt="Token Listrik" style="width: 60px; height: 30px; margin-top: 7px">
            <p class="mt-3" style="font-size: 12px; color: #b3b3b3">Token Listrik</p>
        </div>
    </div>
</div>

<div id="section-purchase" class="container-fluid">
    <div class="row gx-0 p-3">
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
                        <button id="paid-text" class="btn text-light" style="background-color: #f06270" onclick="purchasePulsa()">Bayar</button>
                        <?php
                    }
                    else {
                        ?>
                        <button id="paid-text" class="btn text-light" style="background-color: red" onclick="purchasePulsa()">Bayar</button>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-payment" tabindex="-1" role="dialog" aria-labelledby="modal-payment" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-0" id="modal-payment-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-no-pulsa" tabindex="-1" role="dialog" aria-labelledby="modal-no-pulsa" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body mt-3 text-center" id="modal-no-pulsa-body">
                <p id="fill-amount-text">Harap isi nominal pulsa/data terlebih dahulu</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-select-method" tabindex="-1" role="dialog" aria-labelledby="modal-select-method" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body mt-3 text-center" id="modal-select-method-body">
                <p id="fill-payment-text">Harap pilih metode pembayaran terlebih dahulu.</p>
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
                <button style="background-color: <?= $env == 1 ? '#f06270' : 'red' ?>" class="btn text-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    
<footer class="mt-2" style="border-top: 1px solid #d7d7d7; margin-bottom: 120px">
    <div class="row gx-0 p-1">
        <div class="col-12 d-flex justify-content-center mt-2">
            <?php
            if ($env == 1) {
                ?>
                <span><img src="../assets/img/Virtoo Assets-20220420T065146Z-001/GTM 2.0-Virtoo Assets/Logo/Virtoo_Gradient.png" alt="" style="height: 100px; width: 100px"></span>
                <?php
            }
            else {
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


</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<!-- <script src="../assets/js/xendit.min.js"></script> -->

<script>
    var product_global;

    var f_pin = '<?= $id_user ?>';
    var env = new URLSearchParams(window.location.search).get('env');
    var store_id = new URLSearchParams(window.location.search).get('store_id');
    var urlType = new URLSearchParams(window.location.search).get('url_type');

    $('.section-pulsa').hide();
    $('#section-purchase').hide();

    function navPrabayar() {
        // $('#prabayar').css('background-color', '#f06270');
        // $('#prabayar').css('color', '#FFFFFF');

        // $('#pascabayar').css('background-color', '#f0f3f7');
        // $('#pascabayar').css('color', 'rgba(0,0,0,0.54)');
    }

    function navPascabayar() {
        $('#pascabayar').css('background-color', '#f06270');
        $('#pascabayar').css('color', '#FFFFFF');

        $('#prabayar').css('background-color', '#f0f3f7');
        $('#prabayar').css('color', 'rgba(0,0,0,0.54)');
    }

    function navTransaction() {
        $('#transaction-text').css('color', '#f06270');
        $('#promo-text').css('color', 'rgba(0,0,0,0.54)');
    }

    function navPromo() {
        $('#promo-text').css('color', '#f06270');
        $('#transaction-text').css('color', 'rgba(0,0,0,0.54)');
    }

    $("#phone-number").bind("change paste keyup", function() {

        var number = $(this).val();

        //   console.log(number);

        if (number.substring(0, 4) == "0812" || number.substring(0, 4) == "0813" || number.substring(0, 4) == "0811" || number.substring(0, 4) == "0821" || number.substring(0, 4) == "0822" || number.substring(0, 4) == "0851" || number.substring(0, 4) == "0852" || number.substring(0, 4) == "0853") {

            $('#provider-image').attr('src', 'https://images.tokopedia.net/img/elTgOa/2022/3/15/572b5cbb-0dea-4cae-9ca7-2b204304d56e.png');
            $('.section-promo').hide();
            $('.section-pulsa').show();
            $('#section-purchase').show();

            if (number == "0812" || number == "0811" || number == "0813" || number == "0821" || number == "0822" || number == "0851" || number == "0852" || number == "0853") {

                console.log("Load Pulsa");
                $('#list-product').html("");

                var fd = new FormData();
                fd.append("number", number);
                fd.append("provider", "TSEL");

                $.ajax({
                    type: "POST",
                    url: "/nexilis/logics/get_pulsa",
                    data: fd,
                    enctype: 'multipart/form-data',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var obj = JSON.parse(response);

                        Object.keys(obj).forEach(function(item) {

                            // console.log(obj[item]['NEW_PRICE']);

                            var html = `<div class="col-6 mt-3">
                            <div class="pulsa-box shadow text-center" data-product="` + obj[item]['CODE'] + `" data-price="` + obj[item]['NEW_PRICE'] + `" style="border-radius: 10px; padding: 6px; padding-top: 25px !important">
                                <b>` + obj[item]['DESCRIPTION'] + `</b>
                                <p style="font-size: 12px; color: #626262">Rp ` + obj[item]['NEW_PRICE'] + `</p>
                            </div>
                        </div>`;

                            $('#list-product').append(html);
                        });

                    },
                    error: function(response) {
                        alert('Failed to get Pulsa');
                    }
                });
            }


        } else if (number.substring(0, 4) == "0878" || number.substring(0, 4) == "0817" || number.substring(0, 4) == "0818" || number.substring(0, 4) == "0819" || number.substring(0, 4) == "0859" || number.substring(0, 4) == "0877") {

            $('#provider-image').attr('src', 'https://ecs7.tokopedia.net/img/attachment/2021/11/17/80338194/80338194_7a8e9af4-a3ec-4f77-9892-69acc932df92.png');
            $('.section-promo').hide();
            $('.section-pulsa').show();
            $('#section-purchase').show();

            if (number == "0878" || number == "0817" || number == "0818" || number == "0819" || number == "0859" || number == "0877") {

                console.log("Load Pulsa");
                $('#list-product').html("");

                var fd = new FormData();
                fd.append("number", number);
                fd.append("provider", "XL");

                $.ajax({
                    type: "POST",
                    url: "/nexilis/logics/get_pulsa",
                    data: fd,
                    enctype: 'multipart/form-data',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var obj = JSON.parse(response);

                        Object.keys(obj).forEach(function(item) {

                            // console.log(obj[item]['NEW_PRICE']);

                            var html = `<div class="col-6 mt-3">
                            <div class="pulsa-box shadow text-center" data-product="` + obj[item]['CODE'] + `" data-price="` + obj[item]['NEW_PRICE'] + `" style="border-radius: 10px; padding: 6px; padding-top: 25px !important">
                                <b>` + obj[item]['DESCRIPTION'] + `</b>
                                <p style="font-size: 12px; color: #626262">Rp ` + obj[item]['NEW_PRICE'] + `</p>
                            </div>
                        </div>`;

                            $('#list-product').append(html);
                        });

                    },
                    error: function(response) {
                        alert('Failed to get Pulsa');
                    }
                });
            }

        } else if (number.substring(0, 4) == "0856" || number.substring(0, 4) == "0857" || number.substring(0, 4) == "0858") {

            $('#provider-image').attr('src', 'https://ecs7.tokopedia.net/img/recharge/operator/im3_2.png');
            $('#provider-image').css('margin-top', '40px');
            $('#provider-image').css('margin-bottom', '40px');
            $('.section-promo').hide();
            $('.section-pulsa').show();
            $('#section-purchase').show();

            if (number == "0856" || number == "0857"|| number == "0858") {

                console.log("Load Pulsa");
                $('#list-product').html("");

                var fd = new FormData();
                fd.append("number", number);
                fd.append("provider", "INDOSAT");

                $.ajax({
                    type: "POST",
                    url: "/nexilis/logics/get_pulsa",
                    data: fd,
                    enctype: 'multipart/form-data',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var obj = JSON.parse(response);

                        Object.keys(obj).forEach(function(item) {

                            // console.log(obj[item]['NEW_PRICE']);

                            var html = `<div class="col-6 mt-3">
                            <div class="pulsa-box shadow text-center" data-product="` + obj[item]['CODE'] + `" data-price="` + obj[item]['NEW_PRICE'] + `" style="border-radius: 10px; padding: 6px; padding-top: 25px !important">
                                <b>` + obj[item]['DESCRIPTION'] + `</b>
                                <p style="font-size: 12px; color: #626262">Rp ` + obj[item]['NEW_PRICE'] + `</p>
                            </div>
                        </div>`;

                            $('#list-product').append(html);
                        });

                    },
                    error: function(response) {
                        alert('Failed to get Pulsa');
                    }
                });
            }

        } else if (number.substring(0, 4) == "0836" || number.substring(0, 4) == "0832" || number.substring(0, 4) == "0833" || number.substring(0, 4) == "0838") {

            $('#provider-image').attr('src', 'https://ecs7.tokopedia.net/img/recharge/operator/axis_2.png');
            $('#provider-image').css('margin-top', '40px');
            $('#provider-image').css('margin-bottom', '40px');
            $('.section-promo').hide();
            $('.section-pulsa').show();
            $('#section-purchase').show();

            if (number == "0836" || number == "0832" || number == "0833" || number == "0838") {

                console.log("Load Pulsa");
                $('#list-product').html("");

                var fd = new FormData();
                fd.append("number", number);
                fd.append("provider", "AXIS");

                $.ajax({
                    type: "POST",
                    url: "/nexilis/logics/get_pulsa",
                    data: fd,
                    enctype: 'multipart/form-data',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var obj = JSON.parse(response);

                        Object.keys(obj).forEach(function(item) {

                            // console.log(obj[item]['NEW_PRICE']);

                            var html = `<div class="col-6 mt-3">
                            <div class="pulsa-box shadow text-center" data-product="` + obj[item]['CODE'] + `" data-price="` + obj[item]['NEW_PRICE'] + `" style="border-radius: 10px; padding: 6px; padding-top: 25px !important">
                                <b>` + obj[item]['DESCRIPTION'] + `</b>
                                <p style="font-size: 12px; color: #626262">Rp ` + obj[item]['NEW_PRICE'] + `</p>
                            </div>
                        </div>`;

                            $('#list-product').append(html);
                        });

                    },
                    error: function(response) {
                        alert('Failed to get Pulsa');
                    }
                });
            }

        } else if (number.substring(0, 4) == "0896" || number.substring(0, 4) == "0895" || number.substring(0, 4) == "0897" || number.substring(0, 4) == "0898" || number.substring(0, 4) == "0899") {

            $('#provider-image').attr('src', 'https://ecs7.tokopedia.net/img/attachment/2021/6/3/47672011/47672011_43b5c24b-99f8-48ca-bd82-c6ef0d2520c6.png');
            $('#provider-image').css('margin-top', '40px');
            $('#provider-image').css('margin-bottom', '40px');
            $('.section-promo').hide();
            $('.section-pulsa').show();
            $('#section-purchase').show();

            if (number == "0896" || number == "0895" || number == "0897" || number == "0898" || number == "0899") {

                console.log("Load Pulsa");
                $('#list-product').html("");

                var fd = new FormData();
                fd.append("number", number);
                fd.append("provider", "TRI");

                $.ajax({
                    type: "POST",
                    url: "/nexilis/logics/get_pulsa",
                    data: fd,
                    enctype: 'multipart/form-data',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var obj = JSON.parse(response);

                        Object.keys(obj).forEach(function(item) {

                            // console.log(obj[item]['NEW_PRICE']);

                            var html = `<div class="col-6 mt-3">
                                <div class="pulsa-box shadow text-center" data-product="` + obj[item]['CODE'] + `" data-price="` + obj[item]['NEW_PRICE'] + `" style="border-radius: 10px; padding: 6px; padding-top: 25px !important">
                                    <b>` + obj[item]['DESCRIPTION'] + `</b>
                                    <p style="font-size: 12px; color: #626262">Rp ` + obj[item]['NEW_PRICE'] + `</p>
                                </div>
                            </div>`;

                            $('#list-product').append(html);
                        });

                    },
                    error: function(response) {
                        alert('Failed to get Pulsa');
                    }
                });
            }

        } else if (number.substring(0, 4) == "0881" || number.substring(0, 4) == "0882" || number.substring(0, 4) == "0883" || number.substring(0, 4) == "0884" || number.substring(0, 4) == "0887" || number.substring(0, 4) == "0888" || number.substring(0, 4) == "0889") {

            $('#provider-image').attr('src', 'https://ecs7.tokopedia.net/img/recharge/operator/smartfren_3.png');
            $('#provider-image').css('margin-top', '40px');
            $('#provider-image').css('margin-bottom', '40px');
            $('.section-promo').hide();
            $('.section-pulsa').show();
            $('#section-purchase').show();

            if (number == "0881" || number == "0882" || number == "0883" || number == "0884" || number == "0887" || number == "0888" || number == "0889") {

                console.log("Load Pulsa");
                $('#list-product').html("");

                var fd = new FormData();
                fd.append("number", number);
                fd.append("provider", "SMART");

                $.ajax({
                    type: "POST",
                    url: "/nexilis/logics/get_pulsa",
                    data: fd,
                    enctype: 'multipart/form-data',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        var obj = JSON.parse(response);

                        Object.keys(obj).forEach(function(item) {

                            // console.log(obj[item]['NEW_PRICE']);

                            var html = `<div class="col-6 mt-3">
                                <div class="pulsa-box shadow text-center" data-product="` + obj[item]['CODE'] + `" data-price="` + obj[item]['NEW_PRICE'] + `" style="border-radius: 10px; padding: 6px; padding-top: 25px !important">
                                    <b>` + obj[item]['DESCRIPTION'] + `</b>
                                    <p style="font-size: 12px; color: #626262">Rp ` + obj[item]['NEW_PRICE'] + `</p>
                                </div>
                            </div>`;

                            $('#list-product').append(html);
                        });

                    },
                    error: function(response) {
                        alert('Failed to get Pulsa');
                    }
                });
            }
        }else{

            $('.section-promo').show();
            $('.section-pulsa').hide();
            $('#section-purchase').hide();

        }
    });

    var price = 0;

    $('body').on('click', '.pulsa-box', function() {

        $(".pulsa-box").css('background-color', '#FFFFFF');
        $(".pulsa-box").css('border', 'none');

        $(this).css('background-color', '#ffdcdc');
        $(this).css('border', '1px solid #f06270');

        product_global = $(this).data('product');
        price = parseInt($(this).data('price'));

        $('#price-purchase').text('Rp ' + price);

    });

    // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
    // var env = new URLSearchParams(window.location.search).get('env');
    // var store_id = new URLSearchParams(window.location.search).get('store_id');
    // var urlType = new URLSearchParams(window.location.search).get('url_type');
    

    if (env == null) {
        env = 1;
    }

    var tab_origin = new URLSearchParams(window.location.search).get('origin');

    function navPulsa() {

        window.location.replace('/nexilis/pages/digipos?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navData() {

        window.location.replace('/nexilis/pages/digipos-data?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navPLN() {

        window.location.replace('/nexilis/pages/digipos-pln?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navPDAM() {

        window.location.replace('/nexilis/pages/digipos-pdam?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navBPJS() {

        window.location.replace('/nexilis/pages/digipos_bpjs?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navTelepon() {

        window.location.replace('/nexilis/pages/digipos_pstn?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navMultifinance() {

        window.location.replace('/nexilis/pages/digipos_multifinance?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navKereta() {

        window.location.replace('/nexilis/pages/digipos-kereta?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navPesawat() {

        window.location.replace('/nexilis/pages/digipos-pesawat?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function navVoucher() {

        window.location.replace('/nexilis/pages/digipos-voucher?f_pin='+f_pin+'&origin=' + tab_origin + '&env='+env+ '&store_id=' + store_id + '&url_type=' + urlType);

    }

    function purchasePulsa() {

        if(product_global){

            var command = "PURCHASE";
            var product = product_global;
            var customer = $('#phone-number').val();
            var partner_trxid = new Date().getTime().toString(16);
            var payment = $('#dropdownMenuSelectMethod').val();

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

        }else{
            $('#modal-no-pulsa').modal('show');
        }
    }

    if (localStorage.lang == 0){
        $('#fill-amount-text').text('Please fill in the credit/data nominal first');
        $('#fill-payment-text').text('Please select a payment method first.');
        $('#paid-text').text('Pay');
    }
</script>

<script src="../assets/js/cart_digipos.js?v=<?php echo time(); ?>"></script>