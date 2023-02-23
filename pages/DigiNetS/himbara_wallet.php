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
                <span id="wallet-detail" class="text-white" style="font-size: 30px; font-weight: bold">Rp. 1.000.000 &nbsp;<i class="fa fa-angle-down"></i></span>
            </div>
        </div>
    </section>
    <div class="section-body" style="margin-top: -15px; border-radius: 20px 20px 0px 0px; background-color: white">
        <div class="row p-4">
            <div class="col-12">
                <div class="row p-3" style="background-color: #f5f5f5">
                    <div id="topup-button" class="col-6 text-center text-success" onclick="">Top-up &nbsp; &nbsp;<i class="fa fa-angle-down"></i></div>
                    <div class="col-6 text-center text-success">History</div>
                </div>
            </div>
        </div>

        <div id="topup-services" class="row p-4">
            <div class="col-12">
                <div class="row p-3" style="background-color: #f5f5f5">
                    <span style="font-weight: 600">Layanan Top Up</span>
                    <div class="col-12 mt-3">
                        <label for="BRIrange" class="form-label" style="font-weight: 600">BRI</label>
                        <input type="range" class="form-range" min="10000" max="10000000" value="5000000" step="10000" id="BRIrange">
                        Value: <span id="BRIval"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="BNIrange" class="form-label" style="font-weight: 600">BNI</label>
                        <input type="range" class="form-range" min="10000" max="10000000" value="5000000" step="10000" id="BNIrange">
                        Value: <span id="BNIval"></span>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="BCArange" class="form-label" style="font-weight: 600">BCA</label>
                        <input type="range" class="form-range" min="10000" max="10000000" value="5000000" step="10000" id="BCArange">
                        Value: <span id="BCAval"></span>
                    </div>
                </div>
            </div>
        </div>

        <div id="top-up" class="row p-4 d-none">
            <div class="col-12">
                <div class="row p-3" style="background-color: #f5f5f5">
                    <span style="font-weight: 600">Masukkan Data Akun Anda</span>
                    <div class="col-6 mt-3 text-center text-success">
                        <select class="form-select" aria-label="Default select example">
                            <option value="" selected>Bank</option>
                            <option value="1">BRI</option>
                            <option value="2">BNI</option>
                            <option value="3">BCA</option>
                        </select>
                    </div>
                    <div class="col-6 mt-3 text-center text-success">
                        <input type="text" class="form-control" id="acc-number" placeholder="No. Rekening">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="topup-nominal" class="row p-4 d-none">
        <div class="col-12">
            <div class="row p-3" style="background-color: #f5f5f5">
                <span style="font-weight: 600">Pilih Nominal Top Up</span>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/cash-icon.png" alt="" style="width: 75px; height: 75px"><br>
                        <p>Rp. 5.000</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/cash-icon.png" alt="" style="width: 75px; height: 75px"><br>
                        <p>Rp. 10.000</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/cash-icon.png" alt="" style="width: 75px; height: 75px"><br>
                        <p>Rp. 20.000</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/cash-icon.png" alt="" style="width: 75px; height: 75px"><br>
                        <p>Rp. 50.000</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/cash-icon.png" alt="" style="width: 75px; height: 75px"><br>
                        <p>Rp. 75.000</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/cash-icon.png" alt="" style="width: 75px; height: 75px"><br>
                        <p>Rp. 100.000</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="topup-friend" class="row p-4">
        <div class="col-12">
            <div class="row p-3" style="background-color: #f5f5f5">
                <span style="font-weight: 600">Bagikan Ke Teman</span>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Ayah</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Ibu</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Kakak</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Teman ke-1</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Teman ke-2</p>
                    </div>
                </div>
                <div class="col-6 p-2 text-center text-success">
                    <div style="border: 1px solid green; background-color: #17b46b; border-radius: 5px; height: 149px" class="text-white p-3">
                        <img src="../../assets/img/ic_person_boy.png" alt="" style="width: 75px; height: 75px; opacity: 0.4"><br>
                        <p>Teman ke-3</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>

    $("#topup-button").click(function(){

        if($(this).find('i').hasClass('fa-angle-down')){
            $("#top-up").removeClass("d-none");
            $("#topup-nominal").removeClass("d-none");
            $('i').addClass('fa-angle-down');
            $(this).find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            $("#top-up").addClass("d-none");
            $("#topup-nominal").addClass("d-none");
            $(this).find('i').addClass('fa-angle-down').removeClass('fa-angle-up');
        }
        
    });

    var briRange = document.getElementById("BRIrange");
    var briValue = document.getElementById("BRIval");

    var bniRange = document.getElementById("BNIrange");
    var bniValue = document.getElementById("BNIval");

    var bcaRange = document.getElementById("BCArange");
    var bcaValue = document.getElementById("BCAval");

    briRange.oninput = function() {
        briValue.innerHTML = numberWithDots(this.value);
    }

    bniRange.oninput = function() {
        bniValue.innerHTML = numberWithDots(this.value);
    }

    bcaRange.oninput = function() {
        bcaValue.innerHTML = numberWithDots(this.value);
    }

    function numberWithDots(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

</script>