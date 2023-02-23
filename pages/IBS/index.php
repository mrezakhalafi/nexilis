<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

$dbconn = paliolite();

$name = '';
$f_pin = '';

if (isset($_GET['f_pin'])) {
    $f_pin_balance = $_GET['f_pin'];
}

if (isset($_REQUEST["userid"])) {
    $name = $_REQUEST["userid"];
    $queryStr = "
    SELECT F_PIN, CONCAT(FIRST_NAME, ' ', LAST_NAME) AS firstlast 
    FROM USER_LIST
    WHERE CONCAT(FIRST_NAME, ' ', LAST_NAME) = '$name'
    AND BE = 312";
    $query = $dbconn->prepare($queryStr);
    $query->execute();
    $queryResult = $query->get_result()->fetch_assoc();
    $query->close();
    $f_pin = $queryResult["F_PIN"];
}

$queryBalance = $dbconn->prepare("SELECT * FROM HIMBARA_WALLET WHERE F_PIN = '$f_pin_balance' AND BANK = 0");
$queryBalance->execute();
$walletBalance = $queryBalance->get_result();
$queryBalance->close();

if (mysqli_num_rows($walletBalance) > 0) {
    foreach ($walletBalance as $wb) {
        $totalBalance = $wb['AMOUNT'];
    }
}
else {
    $totalBalance = 0;
}

?>

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

    #navbar {
        background-color: #2a2a2a;
        border-bottom: 7px solid #055160;
        height: 50px;
        width: 100%;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding-top: 12px;
    }

    #section-one {
        background-color: #0095b2;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-two {
        background-color: #0095b2;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-two-2 {
        background-color: #0095b2;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-balance {
        background-color: #a8a800;
        border-radius: 20px;
        margin: 20px;
        padding: 10px;
        color: white;
        font-size: 13px;
    }

    #section-three {
        background-color: #eeffff;
        padding-bottom: 20px;
        border-top: 5px solid #055160;
    }

    /* #button-1{
        width: 55px; 
        height: 55px; 
        border-radius: 200px; 
        font-size: 13px;
        margin-top: 25px;
        background-image: url('https://cdn-icons-png.flaticon.com/512/190/190119.png');
        background-size: cover;
    } */

    .desc {
        font-size: 12px;
        font-weight: bold;
    }

    .img-border {
        width: 100%;
        height: auto;
        padding: 20px;
        /* border: 1px solid lightgray; */
        border-radius: 15px;
    }
</style>

<body style="visibility:hidden;">

    <div id="navbar">
        <div class="col-12 text-center">
            IBS
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4">

        </div>
        <div class="col-12 col-md-4">

            <?php 
                // $name = 'Joko Anwar';
                // if (isset($_GET['name'])) {
                //     $name = $_GET['name'];
                // }
            ?>

            <div id="section-one" class="text-center mt-4">
                Hello <?= $name ?>, today is <span id="current-time"></span> and the weather is 31 degrees, Have a nice day.
                <!-- <div style="background-color: #0095b2; width: 20px; height: 20px; position: absolute; transform: rotate(45deg); margin-left: 30px"></div> -->
            </div>

            <div id="section-two" class="text-center d-none">
                Usually at 18th August you checked your balance or you do something like transfer balance to Aldo Pratmono and Chika Clarensia at the afternoon, is that correct?
                <!-- <div style="background-color: #0095b2; width: 20px; height: 20px; position: absolute; transform: rotate(45deg); margin-left: 30px"></div> -->
            </div>

            <div id="section-two-2" class="text-center">
                <span id="statement" style="font-weight: 600"></span>
                <div id="section-two-arrow" style="background-color: #0095b2; width: 20px; height: 20px; position: absolute; transform: rotate(45deg); margin-left: 30px"></div>
            </div>

            <img style="width: 90%; margin-left: 15px; height: auto" src="../../assets/img/chatbot-robot.gif">

            <div id="section-balance" class="text-center" style="display: none">
                <div style="background-color: #a8a800; width: 20px; height: 20px; position: absolute; transform: rotate(45deg); margin-left: 30px; margin-top: -20px"></div>
                <?php 
                    if (mysqli_num_rows($walletBalance) > 0) {
                        ?>
                        Your wallet balance is: Rp. <span id="current-balance"><?= $totalBalance; ?></span>
                        <?php
                    }
                    else {
                        echo "You have no account registered. Please Register first!";
                    }
                ?>
            </div>

            <div id="section-three" class="container" style="padding-top: 30px">
                <p style="font-size: 14px">And here's my recomendation :</p>
                <div class="row mt-4">
                    <div class="col-4" id="button-1" onclick="checkBalance()">
                        <div class="img-border" style="border:2px solid darkorange;">
                            <img id="icon-1" style="width: 100%">
                        </div>
                        <div id="desc-1" class="text-center desc" style="margin-top: 7px"></div>
                    </div>
                    <div class="col-4" id="button-2">
                        <div class="img-border" style="border:2px solid green;">
                            <img id="icon-2" style="width: 100%">
                        </div>
                        <div id="desc-2" class="text-center desc" style="margin-top: 7px"></div>
                    </div>
                    <div class="col-4" id="button-3">
                        <div class="img-border" style="border:2px solid purple;">
                            <img id="icon-3" style="width: 100%">
                        </div>
                        <div id="desc-3" class="text-center desc" style="margin-top: 7px"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12 col-md-4">

        </div>
    </div>

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    function openURL(url) {
        if (url != "") {
            window.location.href = url;
        }
    }

    function checkBalance() {
        // $("#section-two-arrow").toggle();
        $("#section-balance").toggle();
    }

    $(document).ready(function() {
        var situation1 = {
            content: "Your electricity and water bills are due in 30 September 2022",
            button1: "Check Balance",
            url1: "",
            icon1: "../../assets/img/check-balance.png",
            button2: "Pay Electricity",
            url2: "payment_ibs?env=1&f_pin=<?= $f_pin ?>",
            icon2: "../../assets/img/cashless-payment.png",
            button3: "Pay Water",
            url3: "payment_ibs?env=2&f_pin=<?= $f_pin ?>",
            icon3: "../../assets/img/cashless-payment.png",
        }

        var situation2 = {
            content: "Your gold's gym membership bill payment are due in 29 September 2022",
            button1: "Check Balance",
            url1: "",
            icon1: "../../assets/img/check-balance.png",
            button2: "Pay Membership",
            url2: "payment_ibs?env=3&f_pin=<?= $f_pin ?>",
            icon2: "../../assets/img/cashless-payment.png",
            button3: "Paid Leave",
            url3: "payment_ibs?env=4&f_pin=<?= $f_pin ?>",
            icon3: "../../assets/img/cashless-payment.png",
        }

        var situation3 = {
            content: "Usually you do transfer to Aldo Pratmono and Chika Clarensia at 2 PM today",
            button1: "Check Balance",
            url1: "",
            icon1: "../../assets/img/check-balance.png",
            button2: "Transfer to Chika Clarensia",
            url2: "transfer_ibs?env=1&f_pin=<?= $f_pin ?>",
            icon2: "../../assets/img/money-transaction.png",
            button3: "Transfer to Aldo Pratmono",
            url3: "transfer_ibs?env=2&f_pin=<?= $f_pin ?>",
            icon3: "../../assets/img/money-transaction.png",
        }

        var situationMap = new Map();

        situationMap.set('1', JSON.stringify(situation1));
        situationMap.set('2', JSON.stringify(situation2));
        situationMap.set('3', JSON.stringify(situation3));

        var arr_question = ["1", "2", "3"];
        var arr_answer = Math.floor(Math.random() * arr_question.length);

        console.log(JSON.parse(situationMap.get(arr_question[arr_answer])));

        var a = JSON.parse(situationMap.get(arr_question[arr_answer]));

        $("#statement").text(a.content);
        $("#desc-1").text(a.button1);
        $("#desc-2").text(a.button2);
        $("#desc-3").text(a.button3);
        $('#icon-1').attr('src', a.icon1);
        $('#icon-2').attr('src', a.icon2);
        $('#icon-3').attr('src', a.icon3);

        let username = new URLSearchParams(window.location.search).get('name');
        let unameQuery = username != null ? '&name=' + username : '';

        if (username != null) {

        }

        if (arr_question[arr_answer] == "1") {
            $('#button-2').click(function() {
                openURL(a.url2 + unameQuery);
            })
            $('#button-3').click(function() {
                openURL(a.url3 + unameQuery);
            })
        } else if (arr_question[arr_answer] == "2") {
            $('#button-2').click(function() {
                openURL(a.url2 + unameQuery);
            })
            $('#button-3').click(function() {
                openURL(a.url3 + unameQuery);
            })
        } else if (arr_question[arr_answer] == "3") {
            $('#button-2').click(function() {
                openURL(a.url2 + unameQuery);
            })
            $('#button-3').click(function() {
                openURL(a.url3 + unameQuery);
            })
        }

        $('body').css('visibility', 'visible');

        var today = new Date();
        var date = today.getDate();
        var month = today.getMonth();
        var year = today.getFullYear();;

        const monthNames = ["January", "February", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        var month_name = monthNames[month];
        
        $("#current-time").text(date+" "+month_name+" "+year);
    });

    function numberWithDots(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    var moneyBalance = document.getElementById("current-balance").innerHTML;
    $("#current-balance").text(numberWithDots(moneyBalance));

</script>