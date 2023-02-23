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

    #section-one{
        background-color: #0095b2;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-two{
        background-color: #0095b2;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-two-2{
        background-color: #0095b2;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-balance{
        background-color: #a8a800;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-three{
        background-color: #eeffff;
        padding-bottom: 20px;
        border-top: 5px solid #055160;
    }

    #button-1{
        width: 55px; 
        height: 55px; 
        border-radius: 200px; 
        font-size: 13px;
        margin-top: 25px;
        background-image: url('https://cdn-icons-png.flaticon.com/512/190/190119.png');
        background-size: cover;
    }

    .desc{
        font-size: 10px;
        font-weight: bolder;
    }

    .form-control {
        color: #777777;
        font-weight: 600;
    }

    .input-desc {
        font-weight: unset;
        color: #c0c0c0;
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

<div class="row">
    <div class="col-12 col-md-4">

    

    </div>
    <div class="col-12 col-md-4">
        <div class="row p-3" style="border: 1px solid #e1e1e1">
            <div class="col-2">
                <img src="../../assets/img/no-avatar.jpg" alt="" style="width: 50px; height: 50px; border-radius: 50%">    
            </div>
            <div class="col-10">
                <span id="costumer-name" style="font-weight: bold"></span>
                <br>
                <span style="color: #c0c0c0; font-size: 14px">Mandiri <span id="costumer-number"></span></span>
            </div>
        </div>

        <div class="card shadow mx-3 mt-5">
            <div class="card-body">
                <div class="row p-3">
                    <span style="font-weight: 500; margin-bottom: 10px">Input Transfer Amount</span>
                    <div class="col-3">
                        <span style="font-size: 22px; font-weight: 400">Rp</span>
                    </div>
                    <div class="col-9">
                        <input type="number" class="form-control" id="total-saldo" style="border: none; border-bottom: 1px solid #c0c0c0; border-radius: unset">
                    </div>
                    <div class="col-12">
                        <div class="row mt-3">
                            <div class="col-6 text-start" style="font-size: 13px">
                                <span>Active Balance</span>
                            </div>
                            <div class="col-6 text-end" style="font-size: 15px">
                                <span>Rp 1.0000.000</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <div class="col-12">
                        <input type="text" class="form-control input-desc" id="note-desc" style="border: none; border-bottom: 1px solid #c0c0c0; border-radius: unset; padding: 0" placeholder="Notes">
                    </div>
                    <span style="margin-top: 10px; color: #c0c0c0; font-size: 13px">Opsional</span>
                </div>
            </div>
        </div>
        
        
    </div>
    <div class="col-12 col-md-4">

    </div>
    <div class="fixed-bottom" style="border-top: 5px solid #055160; background-color: white">
        <div class="row pt-2 mb-3 mx-3">
            <div class="row gx-0 mt-2 mb-4">
                <div class="col-9 ps-3">
                    <div style="color: grey">Himbara Wallet</div>
                    <div style="font-weight: bold; font-size: 17px" id="total">Rp <span id="total-payment">0</span></div>
                </div>
                <div class="col-3">
                    <!-- <img src="https://cdn.iconscout.com/icon/free/png-256/more-horizontal-3114524-2598156.png" style="width: 100%; margin-left: 20px"> -->
                    <a href="himbara_wallet_2.php?f_pin=<?= $f_pin ?>"><button class="btn btn-sm btn-success">Top Up</button></a>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <button class="btn btn-info" style="width: 100%; background-color: #055160; border: none; color: white">Pay Now</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

    var situation1 = {
        nama: "CHIKA CLARENSIA",
        norek: "0840995678"
    }

    var situation2 = {
        nama: "ALDO PRATMONO",
        norek: "0794322344"
    }

    var situationMap = new Map();

    var situation = "<?= $_GET['env'] ?>";

    if (situation == 1){
        situationMap.set('1', JSON.stringify(situation1));
    }else if(situation == 2){
        situationMap.set('1', JSON.stringify(situation2));
    }

    var a = JSON.parse(situationMap.get("1"));

    console.log(a);

    $("#costumer-name").text(a.nama);
    $("#costumer-number").text(a.norek);

    $("#total-saldo").change(function() {
        var totalSaldo = $(this).val();
        $("#total-payment").text(totalSaldo);
    });

</script>