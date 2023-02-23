<?php

// include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
// include_once($_SERVER['DOCUMENT_ROOT'] . '/gaspol_web/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $f_pin = $_GET['f_pin'];

// $dbconn = paliolite();

// $ver = time();

// $sqlData = "SELECT COUNT(*) as exist
//   FROM KTA
//   WHERE F_PIN = '$f_pin'";

// //   echo $sqlData;

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $resDATA = $queDATA->get_result()->fetch_assoc();
// $exist = $resDATA["exist"];
// $queDATA->close();

// if ($exist > 0) {
//     header("Location: /gaspol_web/pages/card-kta-mobility?f_pin=$f_pin");
//     die();
// }

// NATIONALITY

// $sqlData = "SELECT * FROM COUNTRIES";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $countries = $queDATA->get_result();
// $queDATA->close();

// HOBBIES

// $sqlData = "SELECT * FROM KTA_HOBBY";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $hobby = $queDATA->get_result();
// $queDATA->close();

// HOBBIES LAINNYA

// $queDATAS = $dbconn->prepare("SELECT * FROM KTA_HOBBY");
// $queDATAS->execute();
// $hobbies = $queDATAS->get_result()->fetch_assoc();
// $hobby_id = $hobbies["ID"];
// $queDATAS->close();

// PROVINCE

// $sqlData = "SELECT * FROM PROVINCE ORDER BY PROV_NAME ASC";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $province = $queDATA->get_result();
// $queDATA->close();

// BIRTHPLACE / CITY

// $sqlData = "SELECT * FROM CITY ORDER BY CITY_NAME ASC";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $birthplace = $queDATA->get_result();
// $queDATA->close();

// POSTAL CODE

// $sqlData = "SELECT * FROM POSTAL_CODE";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $postal = $queDATA->get_result();
// $queDATA->close();

// PRICE

// $sqlData = "SELECT * FROM REGISTRATION_TYPE WHERE REG_ID = '2'";

// $queDATA = $dbconn->prepare($sqlData);
// $queDATA->execute();
// $price = $queDATA->get_result()->fetch_assoc();
// $queDATA->close();

// $upgradeFee = $price['REG_FEE'];
// $adminFee = $price['ADMIN_FEE'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APP BUILDER FORM</title>

    <script src="../assets/js/xendit.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link href="../assets/css/checkout-style.css?v=<?= time(); ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/form-e-sim.css?v=<?php echo $ver; ?>">

    <style>
        /* .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content{
            margin: 0;
            width: 100%;
        }

        .form-submit {
            margin-top: 10px;
        }

        #name {
            /* position: absolute; */
        }

        /* .star {
            position: absolute;
        }

        html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

        input[type="radio"]{
            accent-color: #f66701;
        }

        .form-check-input:checked {
            accent-color: #f66701;
        }  */

    </style>


</head>

<body>
    <!-- CODINGAN DIO -->
    <!-- <div class="row gx-0" style="background-color: rgb(103, 58, 183)">
        <div class="col-6 p-3 mt-1 d-flex justify-content-center">
            <p class="mb-0">APPBUILDER FORM</p>
        </div>
        <div class="col-1 p-3 d-flex justify-content-center">
            <img src="assets GoToMalls/appIcon.png" alt="" style="width: 30px; height: 30px">
        </div>
        <div class="col-1 p-3 d-flex justify-content-center">
            <img src="assets GoToMalls/appIcon.png" alt="" style="width: 30px; height: 30px">
        </div>
        <div class="col-1 p-3 d-flex justify-content-center">
            <img src="assets GoToMalls/appIcon.png" alt="" style="width: 30px; height: 30px">
        </div>
        <div class="col-1 p-3 d-flex justify-content-center">
            <img src="assets GoToMalls/appIcon.png" alt="" style="width: 30px; height: 30px">
        </div>
        <div class="col-2 p-2 d-flex justify-content-center">
            <button class="btn btn-success">Send</button>
        </div>
    </div>

    <div class="row gx-0 bg-light p-2" style="border-bottom: 1px solid #e5e5e5">
        <div class="col-4 d-flex justify-content-end">
            <p class="mb-0">Questions</p>
        </div>
        <div class="col-4 d-flex justify-content-center">
            <p class="mb-0">Responses</p>
        </div>
        <div class="col-4 d-flex justify-content-start">
            <p class="mb-0">Settings</p>
        </div>
    </div>

    <div class="row gx-0 bg-light p-2">
        <div class="col-6 d-flex justify-content-center">
            <img src="assets GoToMalls/appIcon.png" alt="" style="width: 30px; height: 30px">
        </div>
        <div class="col-6 d-flex justify-content-center">
            <img src="assets GoToMalls/appIcon.png" alt="" style="width: 30px; height: 30px">
        </div>
    </div> -->

    <div class="main" style="padding: 0px">

        <form method="POST" class="main-form" id="kta-form" style="padding: 0px" action="/gaspol_web/logics/register_new_kta" enctype="multipart/form-data">
            <div class="row gx-0 p-2" style="border-bottom: 2px #e5e5e5 solid; background-image: url(../assets/img/lbackground_2.png)">
                <div class="col-1 d-flex justify-content-start">
                    <a href="menu_membership.php?f_pin=<?= $f_pin ?>"><img src="../assets/img/icons/Back-(Black).png" alt="" style="height: 36px"></a>
                </div>
                <div class="col-11 d-flex justify-content-center">
                    <h2 style="margin-bottom: 0px">KTA Mobility Registration</h2>
                </div>
            </div>

            <!-- <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div> -->

            <div class="container pt-4">
                <h2 class="text-center"><span style="font-size: 22px">Formulir <span style="color: #f66701">Keanggotaan</span></span><br><span style="font-size: 16px; color: #626262">(Kartu Tanda Anggota)</span></h2>
            </div>

            <div style="width: 100%; height: 10px; background-color: #e5e5e5"></div>

            
        </form>
    
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script>
        var F_PIN = "<?php echo $f_pin; ?>";
        var REG_TYPE = 2;
        localStorage.setItem('grand-total', <?= $upgradeFee+$adminFee ?>);
    </script>
    <script src="../assets/js/membership_payment_mobility.js?v=<?php echo $ver; ?>"></script>
    <script src="../assets/js/form-kta-mobility.js?v=<?php echo $ver; ?>"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <!-- Javascript -->
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<script>