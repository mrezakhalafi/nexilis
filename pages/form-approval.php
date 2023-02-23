<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
else if(isset($_SESSION['f_pin'])){
    $f_pin = $_SESSION['f_pin'];
}

$dbconn = paliolite();
$ver = time();

// SELECT DATA VERIFICATION

$id = $_GET['id'];
$data = [];

$query = $dbconn->prepare("SELECT * FROM REQUEST_DETAIL rd LEFT JOIN REQUEST_HEADER rh ON rd.REQUEST_HEADER = rh.ID WHERE REQUEST_HEADER = '$id'");
$query->execute();
$rawData = $query->get_result();
$query->close();

// print_r($rawData);

foreach ($rawData as $rd){

    $keys = $rd['KEY'];
    $values = $rd['VALUES'];

    if ($rd['CODE']){

        $data['CODE'] = $rd['CODE'];

    }

    $data[$keys] = $values;

}

// print_r($data);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Approval Verification</title>

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
        .modal {
            z-index: 9999;
        }

        #modal-payment .modal-content{
            margin: 0;
            width: 100%;
        }

        .form-submit {
            margin-top: 10px;
        }

        .star {
            position: absolute;
        }

        html,
		body {
			max-width: 100%;
			overflow-x: hidden;
		}

        input[type="radio"]{
            accent-color: #0089ff;
        }

        .form-check-input:checked {
            accent-color: #0089ff;
        }

        .collapse{
            border: 1px solid lightgrey;
            border-radius: 20px;
            padding: 20px;
        }

        .selectize-input.focus {
            box-shadow: none !important;
        }

        .selectize-control.single .selectize-input, .selectize-control.single .selectize-input input {
            border: none;
            border-bottom: 2px solid #ebebeb;
            border-radius: 0px;
        }

        input, select {
            padding-left: 12px;
        }

        .selectize-control .selectize-input.disabled {
            opacity: 1 !important;
        }

        .selectize-control.single .selectize-input:after {
            content: none !important;
        }

    </style>

</head>
<body>
    <div class="main" style="padding: 0px">
        <form method="POST" class="main-form" id="verified-form" style="padding: 0px" action="/logics/insert_form_verified" enctype="multipart/form-data">

            <div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4">
                <div class="row">
                        <img src="../assets/img/membership-back.png" style="width: 55px; height: auto; position:absolute" onclick="closeAndroid()">
                    <div class="col-12 pt-1 text-center">
                        <b id="text-header" style="font-size: 14px">Approval Verification Form</b>
                    </div>
                </div>
            </div>

            <div class="mt-4 container mx-auto" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseIdentificationXXX" aria-expanded="false" aria-controls="collapseIdentification">
                <div style="background-color: #0089ff; border-radius: 200px; width: 23px; height: 23px; font-size: 12px; color: white; text-align: center; display: inline-block;">1</div>
                <b style="margin-left: 10px; font-size: 14px">Verification Type</b>
            </div>

            <div class="container mx-auto">
                <div class="mt-3 mx-auto" id="collapseIdentification">

                    <select class="mt-3 mb-2" id="app_type" name="app_type" disabled aria-label="" style="font-size: 16px">
                        <option value="1" <?= $data['CODE'] == 'OA' ? 'selected' : '' ?> >Official Account</option>
                        <option value="2" <?= $data['CODE'] == 'VA' ? 'selected' : '' ?>>Verified Account</option>
                    </select>
                    <!-- <span class="startype text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 125px">*</span> -->
                    <span id="app_type-error" class="error" style="color: red"></span>
                </div>
            </div>

            <div class="mt-2 container mx-auto" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseIdentificationXXX" aria-expanded="false" aria-controls="collapseIdentification">
                <div style="background-color: #0089ff; border-radius: 200px; width: 23px; height: 23px; font-size: 12px; color: white; text-align: center; display: inline-block;">2</div>
                
                <?php if ($data['CODE'] == 'OA'): ?>
                    <b id="verification-type" style="margin-left: 10px; font-size: 14px">Official Account</b>
                <?php elseif ($data['CODE'] == 'VA'): ?>
                    <b id="verification-type" style="margin-left: 10px; font-size: 14px">Verified Account</b>
                <?php endif; ?>                
                
                <!-- <img style="width: 20px; height: 20px; margin-left: 10px" src="../assets/img/ic_verified_flag.png"> -->
            </div>

            <div class="container mx-auto">
                <div class="mt-2 mx-auto" id="collapseIdentification">

                    <div class="row gx-0 <?= $data['CODE'] == 'VA' ? 'd-none' : '' ?>" id="app_company_name-section">
                        <div class="col-4" style="margin-top: 11px">
                            <span><b>Comp. Name</b></span>
                        </div>
                        <div class="col-8">
                            <input type="text" name="app_company_name" id="app_company_name" placeholder="Company Name" readonly value="<?= $data['app_company_name'] ?>"/>
                        </div>
                    </div>

                    <div class="row gx-0 <?= $data['CODE'] == 'VA' ? 'd-none' : '' ?>" id="app_company_address-section">
                    <div class="col-4" style="margin-top: 11px">
                            <span><b>Comp. Address</b></span>
                        </div>
                        <div class="col-8">
                            <input type="text" name="app_company_address" id="app_company_address" placeholder="Company Address" readonly value="<?= $data['app_company_address'] ?>"/>
                        </div>
                    </div>

                    <div class="row gx-0 <?= $data['CODE'] == 'VA' ? 'd-none' : '' ?>" id="companyapp_npwp-section">
                    <div class="col-4" style="margin-top: 11px">
                            <span><b>Comp. NPWP</b></span>
                        </div>
                        <div class="col-8">
                            <input type="text" maxlength="16" name="app_npwp_company" id="app_npwp_company" placeholder="Company NPWP" pattern="[0-9]*" readonly value="<?= $data['app_npwp_company'] ?>" />
                        </div>
                    </div>

                    <p class="mb-2 mt-3"><b id="text-ktaPhoto">Self Photo</b></p>

                    <div class="row">
                        <div class="col-12">
                            <img id="imageProfile" src="../images/<?= $data['app_profile_photo'] ?>" style="max-width: 100%; border-radius: 10px; object-fit: cover; object-position: center">
                        </div>
                    </div>

                    <div class="row gx-0 mt-3">
                        <div class="col-2" style="margin-top: 11px">
                            <span><b>Email</b></span>
                        </div>
                        <div class="col-10">
                            <input type="email" name="app_email" id="app_email" placeholder="Email Address" readonly value="<?= $data['app_email'] ?>" />
                        </div>
                    </div>
                    <div class="row gx-0">
                        <div class="col-2" style="margin-top: 11px">
                            <span><b>Phone</b></span>
                        </div>
                        <div class="col-10">
                            <input type="text" name="app_phone" id="app_phone" placeholder="Phone Number" readonly value="<?= $data['app_phone'] ?>" />
                        </div>
                    </div>
                    <p class="mt-3 mb-2"><b id="text-IDcard">ID Card Photo</b></p>
                    <div class="row">
                        <div class="col-12">
                            <img id="imagapp_ektp" src="../images/<?= $data['app_ektp_photo'] ?>" style="border-radius: 10px">
                        </div>
                    </div>

                    <div class="row gx-0 mt-3">
                        <div class="col-1" style="margin-top: 11px">
                            <span><b>NIK</b></span>
                        </div>
                        <div class="col-11" style="padding-left: 15px">
                            <input type="text" maxlength="16" name="app_ektp" id="app_ektp" placeholder="Identification Number" pattern="[0-9]*" readonly value="<?= $data['app_ektp'] ?>" />
                        </div>
                    </div>

                    <div class="row gx-0 mt-3">
                        <div class="col-1" style="margin-top: 11px">
                            <span><b>NPWP</b></span>
                        </div>
                        <div class="col-11" style="padding-left: 15px">
                            <input type="text" maxlength="16" name="app_npwp" id="app_npwp" placeholder="Identification Number" pattern="[0-9]*" readonly value="<?= $data['app_npwp'] ?>" />
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4" style="width: 100%; height: 5px; background-color: #e5e5e5"></div>
            <div style="background-color: #eee; padding-top: 25px">
                <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px">
                    <button type="button" class="btn p-2 mx-3" style="border-radius: 20px; font-size: 13px; background-color: #d10e0e; width: 50%; height: 50px; color: white" onclick="rejectForm()"><b id="text-payNow">REJECT</b></button>
                    <button type="button" class="btn p-2 mx-3" style="border-radius: 20px; font-size: 13px; background-color: #21a300; width: 50%; height: 50px; color: white" onclick="approveForm()"><b id="text-payNow">APPROVE</b></button>
                </div>
            </div>
        </form>

        <div class="modal fade" id="modalSuccessApprove" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center" id="modalSuccess">
                        <img src="../assets/img/success.png" style="width: 100px">
                        <h1 class="mt-3">Approve Verification Success!</h1>
                        <p class="mt-3">We will give a notification status to this person.</p>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-center">
                                <button onclick="finishForm()" type="button" class="btn btn-dark mt-3" style="background-color: #0089ff; border: 1px solid #0089ff">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSuccessReject" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center" id="modalSuccess">
                        <img src="../assets/img/success.png" style="width: 100px">
                        <h1 class="mt-3">Reject Verification Success!</h1>
                        <p class="mt-3">We will give a notification status to this person.</p>
                        <div class="row mt-2">
                            <div class="col-12 d-flex justify-content-center">
                                <button onclick="finishForm()" type="button" class="btn btn-dark mt-3" style="background-color: #0089ff; border: 1px solid #0089ff">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

<script>

    var F_PIN = "<?php echo $f_pin; ?>";
    var ID = "<?php echo $_GET['id']; ?>";

    $(document).ready(function(e) {

        let selectizeType = $('#app_type').selectize();

    })

    function closeAndroid(){

        if (window.Android){

            if (window.Android.finishForm) {
                window.Android.finishForm();
            } else {
                window.history.back();
            }

        }else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.finishForm) {

            window.webkit.messageHandlers.finishForm.postMessage({
                param1: ""
            });
            return;

        }else{

            history.back();

        }
    }

    function approveForm(){

        var fd = new FormData();
        fd.append("f_pin", F_PIN);
        fd.append("status", 1);
        fd.append("id", ID);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/approval_form_verified",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                
                $('#modalSuccessApprove').modal('show');

            },
            error: function (response) {
               
                alert(response.responseText);
            }
        });

    }

    function rejectForm(){

        var fd = new FormData();
        fd.append("f_pin", F_PIN);
        fd.append("status", 2);
        fd.append("id", ID);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/approval_form_verified",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
                
                $('#modalSuccessReject').modal('show');

            },
            error: function (response) {
               
                alert(response.responseText);
            }
        });
    }

    function finishForm(){

        if (window.Android) {
            window.Android.finishForm();
        }else{
            
        }

    }

</script>