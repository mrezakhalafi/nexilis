<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IBS</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<style>

    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }

    #navbar{
        background-color: #2a2a2a;
        border-bottom: 7px solid #055160;
        height: 50px;
        width: 100%;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding-top: 12px;
    }

</style>

<body>

    <?php 
        $name = 'Joko Anwar';
        $f_pin = '';
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
        }
        if (isset($_GET['f_pin'])) {
            $f_pin = $_GET['f_pin'];
        }
    ?>
        
    <div id="navbar">
        <div class="row">
            <div class="col-4 text-start">
                <img onclick="history.back()" class="ms-3" src="../../assets/img/cart/back_white.png" alt="" style="width: 30px; height: 30px">    
            </div>
            <div class="col-4 text-center">
                <span>IBS</span>
            </div>
            <div class="col-4">
                
            </div>
        </div>
    </div>

    <img style="width: 95%; margin-left: 10px; position: absolute; margin-top: -25%; z-index: -9999" src="../../assets/img/robot2.png">

    <div class="row mx-3" style="margin-top: 260px; margin-bottom: 180px">
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <h4 style="color: darkorange; font-weight: 700; font-size: 18px" id="title"></h4>
                        </div>
                    </div>
                    <div class="row mt-4 mx-1">
                        <div class="col-6">
                            <div><b style="font-size: 12px">Nama</b></div>
                            <div style="color: grey" id="nama"></div>
                        </div>
                        <div class="col-6">
                            <div><b style="font-size: 12px">Tagihan</b></div>
                            <div style="color: grey" id="tagihan"></div>
                        </div>
                    </div>
                    <div class="row mt-3 mx-1">
                        <div class="col-6">
                            <div><b style="font-size: 12px">Alamat</b></div>
                            <div style="color: grey" id="alamat"></div>
                        </div>
                        <div class="col-6">
                            <div><b style="font-size: 12px">No. Pelanggan</b></div>
                            <div style="color: grey" id="nopel"></div>
                        </div>
                    </div>
                    <div class="row mt-3 mx-1">
                        <div class="col-6">
                            <div><b style="font-size: 12px">Tagihan</b></div>
                            <div style="color: grey" id="price"></div>
                        </div>
                        <div class="col-6">
                            <div><b style="font-size: 12px">Tenggat Waktu</b></div>
                            <div style="color: grey" id="time"></div>
                        </div>
                    </div>
                    <div class="row mt-3 mx-1 mb-3">
                        <div class="col-6">
                            <div><b style="font-size: 12px">Admin</b></div>
                            <div style="color: grey" id="admin"></div>
                        </div>
                        <div class="col-6">
                            <div><b style="font-size: 12px">Bulan Bayar</b></div>
                            <div style="color: grey" id="month"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-bottom" style="border-top: 5px solid #055160; background-color: white">
        <div class="row pt-2 mb-3 mx-3">
            <div class="row mt-2 mb-4">
                <div class="col-9">
                    <div style="color: grey">Himbara Wallet</div>
                    <div style="font-weight: bold; font-size: 17px" id="total"></div>
                </div>
                <div class="col-3">
                    <!-- <img src="https://cdn.iconscout.com/icon/free/png-256/more-horizontal-3114524-2598156.png" style="width: 100%; margin-left: 20px;" onclick="openModal()"> -->
                    <a href="himbara_wallet_2.php?f_pin=<?= $f_pin ?>"><button class="btn btn-sm btn-success">Top Up</button></a>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn btn-info" style="width: 100%; background-color: #055160; border: none; color: white">Pay Now</button>
            </div>
        </div>
    </div>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

    var situation1 = {
        title: "Electricity Bills",
        nama: "<?= $name ?>",
        tagihan: "PLN",
        alamat: "Jl. Cipinang Elok 1 C/13",
        nopel: "019836183618",
        price: "Rp 452.000",
        time: "10 Sep 2022",
        admin: "Rp 2.500",
        month: "Aug 2022",
        total: "Rp 454.000"
    }

    var situation2 = {
        title: "Water Bills",
        nama: "<?= $name ?>",
        tagihan: "PDAM",
        alamat: "Jl. Cipinang Elok 1 C/13",
        nopel: "0826544321092",
        price: "Rp 178.000",
        time: "15 Sep 2022",
        admin: "Rp 2.500",
        month: "Aug 2022",
        total: "Rp 180.500"
    }

    var situation3 = {
        title: "Gym Membership Bills",
        nama: "<?= $name ?>",
        tagihan: "Gold's Gym",
        alamat: "Jl. Tebet Barat No.16",
        nopel: "019836183618",
        price: "Rp 500.000",
        time: "26 Sep 2022",
        admin: "Rp 0",
        month: "Aug 2022",
        total: "Rp 500.000"
    }

    var situation4 = {
        title: "Gym Paid Leave",
        nama: "<?= $name ?>",
        tagihan: "Gold's Gym",
        alamat: "Jl. Tebet Barat No.16",
        nopel: "019836183618",
        price: "Rp 100.000",
        time: "04 Sep 2022",
        admin: "Rp 0",
        month: "Aug 2022",
        total: "Rp 100.000"
    }

    var situationMap = new Map();

    var situation = "<?= $_GET['env'] ?>";

    if (situation == 1){
        situationMap.set('1', JSON.stringify(situation1));
    }else if(situation == 2){
        situationMap.set('1', JSON.stringify(situation2));
    }else if(situation == 3){
        situationMap.set('1', JSON.stringify(situation3));
    }else if(situation == 4){
        situationMap.set('1', JSON.stringify(situation4));
    }

    console.log(situationMap.get('1'));

    var a = JSON.parse(situationMap.get('1'));

    $("#title").text(a.title);
    $("#nama").text(a.nama);
    $("#tagihan").text(a.tagihan);
    $("#alamat").text(a.alamat);
    $("#nopel").text(a.nopel);
    $("#price").text(a.price);
    $("#time").text(a.time);
    $("#admin").text(a.admin);
    $("#month").text(a.month);
    $("#total").text(a.total);

</script>