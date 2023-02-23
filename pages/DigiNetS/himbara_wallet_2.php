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
                    <div class="col-6 mt-3 text-center text-success">
                        <select id="bank-list" class="form-select" aria-label="Default select example">
                            <option value="" selected>Bank</option>
                            <option value="1">BRI</option>
                            <option value="2">BNI</option>
                            <option value="3">Mandiri</option>
                            <option value="4">BTN</option>
                            <option value="5">BSI</option>
                        </select>
                    </div>
                    <div class="col-6 mt-3 text-center text-success">
                        <input type="text" class="form-control" id="acc-number" placeholder="No. Rekening">
                    </div>
                    <div class="col-12 mt-3">
                        <span class="fs-tc">Note:</span>
                        <br>
                        <span class="fs-tc">1. Pastikan nomor rekening yang dimasukkan benar.</span>
                        <br>
                        <span class="fs-tc">2. Proses top up memerlukan waktu maksimal 1x24 jam.</span>
                    </div>
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button class="btn text-white" style="background-color: #129659; border-radius: 20px; font-weight: 600; width: 100%; border: 1px solid green">Tambahkan</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="total-saldo" class="row p-4" style="border-radius: 10px">
            <div class="col-12">
                <div class="row p-3" style="background-color: #f5f5f5; border-radius: 20px">
                    <span style="font-weight: 600">Tentukan Sumber Rekening Top Up</span>
                    <div class="col-12 mt-3">
                        <label for="BRIrange" class="form-label" style="font-weight: 600">BRI : <span style="color: #129659">0840347676</span></label>
                        <input type="range" class="form-range" min="0" max="10000000" value="0" step="1000000" id="BRIrange">
                        Value: <span id="BRIval"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="BNIrange" class="form-label" style="font-weight: 600">BNI (Not Registered)</label>
                        <input type="range" class="form-range" min="0" max="10000000" value="0" step="1000000" id="BNIrange">
                        Value: <span id="BNIval"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="rangeMandiri" class="form-label" style="font-weight: 600">Mandiri : <span style="color: #129659">8490765432</span></label>
                        <input type="range" class="form-range" min="0" max="10000000" value="0" step="1000000" id="rangeMandiri">
                        Value: <span id="valMandiri"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="BTNrange" class="form-label" style="font-weight: 600">BTN (Not Registered)</label>
                        <input type="range" class="form-range" min="0" max="10000000" value="0" step="1000000" id="BTNrange">
                        Value: <span id="BTNval"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="BSIrange" class="form-label" style="font-weight: 600">BSI (Not Registered)</label>
                        <input type="range" class="form-range" min="0" max="10000000" value="0" step="1000000" id="BSIrange">
                        Value: <span id="BSIval"></span>
                    </div>
                    <div class="col-12 d-flex justify-content-center mt-3">
                        <button class="btn text-white" style="background-color: #129659; border-radius: 20px; font-weight: 600; width: 100%; border: 1px solid green">Top Up</button>
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
                    <input type="range" class="form-range" min="0" max="10000000" value="0" step="1000000" id="rangeFriend">
                    Value: <span id="valFriend"></span>
                </div>
                <div class="col-6 p-2 text-center text-success mt-3">
                    <div style="border: 1px solid green; background-color: #129659; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Ayah</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success mt-3">
                    <div style="border: 1px solid green; background-color: #129659; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Ibu</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #129659; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Kakak</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #129659; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Teman ke-1</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #129659; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Teman ke-2</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #129659; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Teman ke-3</p>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center mt-3">
                    <button class="btn text-white" style="background-color: #129659; border-radius: 20px; font-weight: 600; width: 100%; border: 1px solid green">Bagikan</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>

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
    }

    bniRange.oninput = function() {
        bniValue.innerHTML = numberWithDots(this.value);
    }

    mandiriRange.oninput = function() {
        mandiriValue.innerHTML = numberWithDots(this.value);
    }

    btnRange.oninput = function() {
        btnValue.innerHTML = numberWithDots(this.value);
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

    $("#total-wallet").text(numberWithDots(1000000));

</script>