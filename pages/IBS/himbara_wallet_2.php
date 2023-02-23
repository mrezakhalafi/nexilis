<?php

    // ini_set('display_errors', 1); 
	// ini_set('display_startup_errors', 1); 
	// error_reporting(E_ALL);

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
    session_start();

    if (isset($_GET['f_pin'])) {
        $f_pin = $_GET['f_pin'];
    }

    // print_r($f_pin);

    $queryBank = $dbconn->prepare("SELECT * FROM HIMBARA_BANK");
    $queryBank->execute();
    $bankData = $queryBank->get_result();
    $queryBank->close();

    $queryWallet = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET LEFT JOIN HIMBARA_BANK ON HIMBARA_WALLET.BANK = HIMBARA_BANK.CODE WHERE F_PIN = '$f_pin'");
    $queryWallet->execute();
    $showBank = $queryWallet->get_result();
    $queryWallet->close();

    $queryMainWallet = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin' AND BANK = 0");
    $queryMainWallet->execute();
    $mainBalance = $queryMainWallet->get_result()->fetch_assoc();
    $queryMainWallet->close();
    
    $queryWalletBRI = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin' AND BANK = 10");
    $queryWalletBRI->execute();
    $isBRI = $queryWalletBRI->get_result()->fetch_assoc();
    $queryWalletBRI->close();

    $queryWalletBNI = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin' AND BANK = 20");
    $queryWalletBNI->execute();
    $isBNI = $queryWalletBNI->get_result()->fetch_assoc();
    $queryWalletBNI->close();
    
    $queryWalletM = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin' AND BANK = 30");
    $queryWalletM->execute();
    $isMandiri = $queryWalletM->get_result()->fetch_assoc();
    $queryWalletM->close();

    $queryWalletBTN = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin' AND BANK = 40");
    $queryWalletBTN->execute();
    $isBTN = $queryWalletBTN->get_result()->fetch_assoc();
    $queryWalletBTN->close();

    $arrayBank = [];
    $f_pinWallet = [];

    foreach ($showBank as $sb) {
        array_push($arrayBank, $sb['BANK']);
        array_push($f_pinWallet, $sb['F_PIN']);
    }

    $queryWalletBNI = $dbconn->prepare("SELECT USER_LIST.ID, FIRST_NAME FROM FRIEND_LIST LEFT JOIN USER_LIST ON FRIEND_LIST.L_PIN = USER_LIST.F_PIN WHERE FRIEND_LIST.F_PIN = '".$f_pin."' GROUP BY USER_LIST.F_PIN LIMIT 10");
    $queryWalletBNI->execute();
    $friendList = $queryWalletBNI->get_result();
    $queryWalletBNI->close();

    $queryMainWallets = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin' AND BANK = 0");
    $queryMainWallets->execute();
    $mainBalances = $queryMainWallets->get_result();
    $queryMainWallets->close();

    // if (mysqli_num_rows($mainBalances)) {
    // foreach ($mainBalances as $mbs) {
    //     print_r($mbs);
    // }
    // }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="assets/vendor/jquery/jquery-3.6.0.min.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Himbara Wallet</title>
    <style>

        html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

        input[type="range"]::-webkit-slider-thumb {
            background: #129659;
        }

        .fs-tc {
            color: grey;
            font-size: 12px;
        }

        #bank-list {
            color: grey;
        }

    </style>
</head>
<body>
    <section style="padding-bottom: 40px; background-image: url('https://media.gettyimages.com/videos/abstract-blocks-moving-rectangle-looping-magical-shiny-motion-square-video-id1195796046?s=640x640')">
        <div class="row gx-0 p-3">
            <div class="col-6 d-flex justify-content-start">
                <span class="mt-2 text-white" style="font-size: 20px; font-weight: 900">HIMBARA</span>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <button class="btn btn-light text-success" style="font-weight: 600; border-radius: 20px; font-size: 13px;">Promo</button>
            </div>
        </div>
        <div class="row gx-0 p-3 mt-2">
            <div class="col-12">
                <span class="text-white">HIMBARA Wallet</span>
            </div>
            <div class="col-12">
                <span id="wallet-detail" class="text-white" style="font-size: 30px; font-weight: bold">Rp. <span id="total-wallet"></span> &nbsp;<i class="fa fa-angle-up"></i></span>
            </div>
        </div>
    </section>
    <div class="section-body" style="margin-top: -15px; border-radius: 20px 20px 0px 0px; background-color: white">

        <div class="row p-4">
            <div class="col-12">
                <div class="row p-3" style="background-color: #f5f5f5; border-radius: 20px">
                    <div id="topup-button" class="col-6 text-center text-success" onclick="">Top-up &nbsp; &nbsp;<i class="fa fa-angle-up"></i></div>
                    <div class="col-6 text-center text-success">History</div>
                </div>
            </div>
        </div>

        <div id="top-up" class="row p-4">
            <div class="col-12">
                <div class="row p-3" style="background-color: #f5f5f5; border-radius: 20px">
                    <span style="font-weight: 600">Masukkan Data Akun Anda</span>
                    <form method="POST" class="main-form" id="himbara-wallet-form">
                        <div class="row">
                            <div class="col-6 mt-3 text-center text-success">
                                <select id="bank-list" name="bank_name" class="form-select" aria-label="Default select example">
                                    <option value="" selected>Bank</option>
                                        <?php
                                            foreach ($bankData as $bd) {
                                                if (in_array($bd['CODE'], $arrayBank)) {
                                                    ?>
                                                    <option class="d-none" value="<?= $bd['CODE'] ?>"><?= $bd['NAME'] ?></option>
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <option value="<?= $bd['CODE'] ?>"><?= $bd['NAME'] ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                </select>
                            </div>
                            <div class="col-6 mt-3 text-center text-success">
                                <input type="text" name="acc_number" maxlength="9" class="form-control" id="acc-number" placeholder="No. Rekening">
                            </div>
                            <div class="col-12 mt-3">
                                <span class="fs-tc">Note:</span>
                                <br>
                                <span class="fs-tc">1. Pastikan nomor rekening yang dimasukkan benar.</span>
                                <br>
                                <span class="fs-tc">2. Proses top up memerlukan waktu maksimal 1x24 jam.</span>
                            </div>
                            <div class="col-12 d-flex justify-content-center mt-3">
                                <button id="btnAdd" disabled type="button" class="btn text-white" style="background-color: #129659; border-radius: 20px; font-weight: 600; width: 100%; border: 1px solid green" onclick="insertWallet()">Tambahkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="total-saldo" class="row p-4" style="border-radius: 10px">
            <div class="col-12">
                <div class="row p-3" style="background-color: #f5f5f5; border-radius: 20px">
                    <span style="font-weight: 600">Tentukan Sumber Rekening Top Up</span>
                    <div class="col-12 mt-3">
                        <label for="BRIrange" class="form-label" style="font-weight: 600">BRI <?php if ($isBRI): echo ": ".$isBRI['NO_REK']; else: ?><span style="color: red">(Not Registered)</span><?php endif; ?></label>
                        <input type="range" name="bri_range" class="form-range" min="0" max="<?= $isBRI['AMOUNT'] ?>" value="0" step="100000" id="BRIrange" <?php if (!$isBRI): ?> disabled <?php endif; ?>>
                        Rp. <span id="BRIval"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="BNIrange" class="form-label" style="font-weight: 600">BNI <?php if ($isBNI): echo ": ".$isBNI['NO_REK']; else: ?><span style="color: red">(Not Registered)</span><?php endif; ?></label>
                        <input type="range" name="bni_range" class="form-range" min="0" max="<?= $isBNI['AMOUNT'] ?>" value="0" step="100000" id="BNIrange" <?php if (!$isBNI): ?> disabled <?php endif; ?>>
                        Rp. <span id="BNIval"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="rangeMandiri" class="form-label" style="font-weight: 600">Mandiri <?php if ($isMandiri): echo ": ".$isMandiri['NO_REK']; else: ?><span style="color: red">(Not Registered)</span><?php endif; ?></label>
                        <input type="range" name="mandiri_range" class="form-range" min="0" max="<?= $isMandiri['AMOUNT'] ?>" value="0" step="100000" id="rangeMandiri" <?php if (!$isMandiri): ?> disabled <?php endif; ?>>
                        Rp. <span id="valMandiri"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="BTNrange" class="form-label" style="font-weight: 600">BTN <?php if ($isBTN): echo ": ".$isBTN['NO_REK']; else: ?><span style="color: red">(Not Registered)</span><?php endif; ?></label>
                        <input type="range" name="btn_range" class="form-range" min="0" max="<?= $isBTN['AMOUNT'] ?>" value="0" step="100000" id="BTNrange" <?php if (!$isBTN): ?> disabled <?php endif; ?>>
                        Rp. <span id="BTNval"></span>
                    </div>
                    <div class="col-12 mt-3 d-none">
                        <label for="BSIrange" class="form-label" style="font-weight: 600"></label>
                        <input type="range" class="form-range" min="0" max="10000000" value="0" step="1000000" id="BSIrange">
                        Rp. <span id="BSIval"></span>
                    </div>
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button id="btnTopUp" onclick="topUp()" disabled class="btn text-white" style="background-color: #129659; border-radius: 20px; font-weight: 600; width: 100%; border: 1px solid green">Top Up</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="topup-friend" class="row p-4">
        <div class="col-12">
            <div class="row p-3" style="background-color: #f5f5f5; border-radius: 20px">
                <span style="font-weight: 600">Jumlah Saldo Wallet</span>
                <div class="col-12 mt-3">
                    <label for="rangeFriend" class="form-label" style="font-weight: 600">Bagikan ke Teman</label>
                    <input <?php if(mysqli_num_rows($friendList) == 0): ?> disabled <?php endif; ?> type="range" class="form-range" min="0" max="<?= $mainBalance['AMOUNT'] ?>" value="0" step="1000000" id="rangeFriend">
                    Rp. <span id="valFriend"></span>
                </div>

                <?php if (mysqli_num_rows($friendList) > 0): ?>
                    <?php foreach($friendList as $fl): ?>
                        <div class="col-6 p-2 text-center text-success mt-3" onclick="selectFriend('<?= $fl['ID'] ?>','<?= $fl['FIRST_NAME'] ?>')">
                            <div id="friend-list-<?= $fl['ID'] ?>" style="border: 1px solid green; background-color: #129659; border-radius: 5px; height: 149px" class="p-3 friend-list-all">
                                <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                                <p id="friend-name-<?= $fl['ID'] ?>" class="friend-name-all" style="color: white"><?= $fl['FIRST_NAME'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>

                        <div class="container m-3 pt-2">
                            <p>Anda belum mempunyai list teman</p>
                        </div>

                    <?php endif; ?>

                <div class="col-12 d-flex justify-content-center mt-3">
                    <button id="btn-share" disabled class="btn text-white" style="background-color: #129659; border-radius: 20px; font-weight: 600; width: 100%; border: 1px solid green" onclick="shareWallet()">Bagikan</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    $('#bank-list').on('change', function(){
        $('#btnAdd').attr('disabled',false);
    });

    $("#topup-button").click(function(){

        if($(this).find('i').hasClass('fa-angle-up')){
            $("#top-up").addClass("d-none");
            $("#total-saldo").addClass("d-none");
            $('i').addClass('fa-angle-down');
            $(this).find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        } else {
            $("#top-up").removeClass("d-none");
            $("#total-saldo").removeClass("d-none");
            $(this).find('i').addClass('fa-angle-up').removeClass('fa-angle-down');
        }
        
    });

    $("#wallet-detail").click(function(){

        if($(this).find('i').hasClass('fa-angle-up')){
            $("#topup-friend").addClass("d-none");
            $("#topup-services").addClass("d-none");
            $('i').addClass('fa-angle-down');
            $(this).find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        } else {
            $("#topup-friend").removeClass("d-none");
            $("#topup-services").removeClass("d-none");
            $(this).find('i').addClass('fa-angle-up').removeClass('fa-angle-down');
        }

    });

    var briRange = document.getElementById("BRIrange");
    var briValue = document.getElementById("BRIval");
    briValue.innerHTML = numberWithDots(briRange.value);

    var bniRange = document.getElementById("BNIrange");
    var bniValue = document.getElementById("BNIval");
    bniValue.innerHTML = numberWithDots(bniRange.value);

    var mandiriRange = document.getElementById("rangeMandiri");
    var mandiriValue = document.getElementById("valMandiri");
    mandiriValue.innerHTML = numberWithDots(mandiriRange.value);

    var btnRange = document.getElementById("BTNrange");
    var btnValue = document.getElementById("BTNval");
    btnValue.innerHTML = numberWithDots(btnRange.value);

    var bsiRange = document.getElementById("BSIrange");
    var bsiValue = document.getElementById("BSIval");
    bsiValue.innerHTML = numberWithDots(bsiRange.value);

    var friendRange = document.getElementById("rangeFriend");
    var friendValue = document.getElementById("valFriend");
    friendValue.innerHTML = numberWithDots(friendRange.value);

    briRange.oninput = function() {
        briValue.innerHTML = numberWithDots(this.value);
        $('#btnTopUp').attr('disabled',false);
    }

    bniRange.oninput = function() {
        bniValue.innerHTML = numberWithDots(this.value);
        $('#btnTopUp').attr('disabled',false);
    }

    mandiriRange.oninput = function() {
        mandiriValue.innerHTML = numberWithDots(this.value);
        $('#btnTopUp').attr('disabled',false);
    }

    btnRange.oninput = function() {
        btnValue.innerHTML = numberWithDots(this.value);
        $('#btnTopUp').attr('disabled',false);
    }

    bsiRange.oninput = function() {
        bsiValue.innerHTML = numberWithDots(this.value);
    }

    friendRange.oninput = function() {
        friendValue.innerHTML = numberWithDots(this.value);
    }

    function numberWithDots(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    <?php
        if (mysqli_num_rows($mainBalances) > 0) {
            foreach ($mainBalances as $mbs) {
                ?>
                $("#total-wallet").text(numberWithDots(<?= $mbs['AMOUNT'] ?>));
                <?php
            }
        }
        else {
            ?>
            $("#total-wallet").text(0);
            <?php
        }
    ?>

</script>

<script>
    
    function insertWallet() {

        var f_pin = new URLSearchParams(window.location.search).get('f_pin');

        var myform = $("#himbara-wallet-form")[0];
        var fd = new FormData(myform);

        fd.append("f_pin", f_pin);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/register_wallet",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            async: false, 
            contentType: false,
            success: function (response) {
                // alert("Data berhasil masuk.");
                addBank();
            },
            error: function (response) {
                alert("Data gagal keinput.");
                // addBank();
            }
        });

    }

    var friendName = "";
    var friendID = "";

    function selectFriend(id, name){

        $('.friend-list-all').css('background-color','#129659');
        $('.friend-name-all').css('color','white');

        $('#friend-list-'+id).css('background-color','white');
        $('#friend-name-'+id).css('color','green');

        friendID = id;
        friendName = name;

        $('#btn-share').attr('disabled',false);

    }

    function shareWallet(){

        var f_pin = new URLSearchParams(window.location.search).get('f_pin');
        var amount = $('#rangeFriend').val();

        // console.log(amount);

        var fd = new FormData();
        fd.append("f_pin", f_pin);
        fd.append("amount", amount);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/share_wallet",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            async: false, 
            contentType: false,
            success: function (response) {
                
                Swal.fire(
                    'Success!',
                    'Anda membagikan sebesar Rp.'+numberWithDots(amount)+' kepada '+friendName,
                    'success'
                ).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/nexilis/pages/IBS/himbara_wallet_2?f_pin='+f_pin+'';
                }});

            },
            error: function (response) {
                alert("Data gagal keinput.");
            }
        });

    }

    function addBank() {

        var f_pin = new URLSearchParams(window.location.search).get('f_pin');

        Swal.fire(
            'Success!',
            'Your Bank Account has successfully registered!',
            'success'
        ).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/nexilis/pages/IBS/himbara_wallet_2?f_pin='+f_pin+'';
        }});

    }

    function topUp(){

        var briAmount = parseInt($('#BRIrange').val());
        var bniAmount = parseInt($('#BNIrange').val());
        var mandiriAmount = parseInt($('#rangeMandiri').val());
        var btnAmount = parseInt($('#BTNrange').val());

        var total = briAmount + bniAmount + mandiriAmount + btnAmount;

        console.log(total);

        var f_pin = new URLSearchParams(window.location.search).get('f_pin');

        var fd = new FormData();
        fd.append("f_pin", f_pin);
        fd.append("amount", total);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/topup_wallet",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            async: false, 
            contentType: false,
            success: function (response) {
                Swal.fire(
                    'Success!',
                    'Your has successfully added Rp. '+numberWithDots(total)+' to main wallet.',
                    'success'
                ).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/nexilis/pages/IBS/himbara_wallet_2?f_pin='+f_pin+'';
                }});

            },
            error: function (response) {
                alert("Data gagal keinput.");
            }
        });

    }

</script>