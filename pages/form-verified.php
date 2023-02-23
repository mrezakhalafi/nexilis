<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/url_function.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbconn = paliolite();
$ver = time();

if(isset($_GET['f_pin'])){
    $f_pin = $_GET['f_pin'];
}
else if(isset($_SESSION['f_pin'])){
    $f_pin = $_SESSION['f_pin'];
}

// NEW F_PIN SET METHOD

$queryCheckFpin = "SELECT F_PIN FROM USER_LIST WHERE F_PIN = '$f_pin' OR IMEI = '$f_pin'";
$query = $dbconn->prepare($queryCheckFpin);
$query->execute();
$getNewFPIN = $query->get_result()->fetch_assoc();
$query->close();

$f_pin = $getNewFPIN['F_PIN'];

// print_r($f_pin);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request Verification</title>

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

    </style>

</head>
<body class="d-none">
    <div class="main" style="padding: 0px">
        <form method="POST" class="main-form" id="verified-form" style="padding: 0px" action="/logics/insert_form_verified" enctype="multipart/form-data">

            <div class="p-3 shadow-sm" style="border-bottom: 1px solid #e4e4e4">
                <div class="row">
                        <img src="../assets/img/membership-back.png" style="width: 55px; height: auto; position:absolute" onclick="closeAndroid()">
                    <div class="col-12 pt-1 text-center">
                        <b id="text-header" style="font-size: 14px">Request Verification Form</b>
                    </div>
                </div>
            </div>

            <div class="mt-4 container mx-auto" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseIdentificationXXX" aria-expanded="false" aria-controls="collapseIdentification">
                <div style="background-color: #0089ff; border-radius: 200px; width: 23px; height: 23px; font-size: 12px; color: white; text-align: center; display: inline-block;">1</div>
                <b id="verification-type" style="margin-left: 10px; font-size: 14px">Verification Type</b>
            </div>

            <div class="container mx-auto">
                <div class="mt-3 mx-auto" id="collapseIdentification">

                    <select class="mt-3 mb-2" id="app_type" name="app_type" aria-label="" style="font-size: 16px">
                        <option id="type-1" value="1" selected>Official Account</option>
                        <option id="type-2" value="2">Verified Account</option>
                    </select>
                    <!-- <span class="startype text-danger" style="position: absolute; z-index: 999; margin-top: -46px; margin-left: 125px">*</span> -->
                    <span id="app_type-error" class="error" style="color: red"></span>
                </div>
            </div>

            <div class="mt-2 container mx-auto" data-bs-toggle="collapse" style="font-size: 18px" data-bs-target="#collapseIdentificationXXX" aria-expanded="false" aria-controls="collapseIdentification">
                <div style="background-color: #0089ff; border-radius: 200px; width: 23px; height: 23px; font-size: 12px; color: white; text-align: center; display: inline-block;">2</div>
                <b id="verification-type-2" style="margin-left: 10px; font-size: 14px">Official Account</b>
                <!-- <img style="width: 20px; height: 20px; margin-left: 10px" src="../assets/img/ic_verified_flag.png"> -->
            </div>

            <div class="container mx-auto">
                <div class="mt-2 mx-auto" id="collapseIdentification">

                    <div class="row gx-0" id="app_company_name-section">
                        <div class="col-12">
                            <input type="text" name="app_company_name" id="app_company_name" placeholder="Company Name" required />
                        </div>
                        <span class="app_company_name text-danger" style="position: absolute; margin-top: 10px; margin-left: 127px; width: 10px">
                            *
                        </span>
                        <label id="app_company_name-exist" class="text-danger"></label>
                        <label id="app_company_name-not-exist" class="text-success"></label>
                    </div>

                    <div class="row gx-0" id="app_company_address-section">
                        <div class="col-12">
                            <input type="text" name="app_company_address" id="app_company_address" placeholder="Company Address" required />
                        </div>
                        <span class="app_company_address text-danger" style="position: absolute; margin-top: 10px; margin-left: 141px; width: 10px">
                            *
                        </span>
                        <label id="app_company_address-exist" class="text-danger"></label>
                        <label id="app_company_address-not-exist" class="text-success"></label>
                    </div>

                    <div class="row gx-0" id="companyapp_npwp-section">
                        <div class="col-12">
                            <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "15" onkeydown="return event.keyCode !== 69" name="app_npwp_company" id="app_npwp_company" placeholder="Company NPWP" required />
                        </div>
                        <span class="starnoapp_npwp_company text-danger" style="position: absolute; margin-top: 11px; margin-left: 125px; width: 10px">*</span>
                        <label id="app_npwp_company-exist" class="text-danger"></label>
                        <label id="app_npwp_company-not-exist" class="text-success"></label>
                        <label id="app_npwp_company-16" class="text-danger"></label>
                    </div>

                    <p class="mb-2 mt-3"><b id="text-ktaPhoto">Self Photo</b> &nbsp;<span class="starppimg text-danger">*</span> </p>
                    <!-- <div id="photo-method"  class="row" style="margin-bottom: 10px; margin-top: 10px">
                        <div class="col-6">
                            <input type="radio" id="radioProfileFile" name="profile_radio" class="radio" value="File" checked>
                            <label class="text-fromFile" for="radioProfileFile">&nbsp;&nbsp;From File</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="radioProfileOcr" name="profile_radio" class="radio" value="OCR">
                            <label class="text-takePhoto" for="radioProfileOcr">&nbsp;&nbsp;Take Photo</label><br>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-5">
                            <img id="imageProfile" src="../assets/img/avatar.svg" style="width: 100px; height: 100px; border-radius: 10px; object-fit: cover; object-position: center">
                        </div>
                        <div class="col-7">
                            <div class="row mt-3">
                                <label for="app_self_photo" id="profileLabelBtn" style="font-size: 15px; color: black; width: 80%; border-radius: 20px; background-color: white; border: 1px solid black; padding-left: 10px; padding-right: 10px; margin-right: 10px; margin-bottom: 10px" class="btn">Upload Photo</label>
                                <small id="text-pp" class="text-secondary" style="font-weight: 500; font-size: 8.5px">* Profile photo will be used for verification</small>
                            </div>
                            <div class="row">
                                <input type="file" style="display:none;" accept="image/*,profile_file/*" name="app_self_photo" id="app_self_photo" class="photo" placeholder="Foto Profile" required onchange="loadFile(event)"/>
                            </div>
                        </div>
                    </div>
                    <span id="app_self_photo-error" class="error" style="color: red"></span>

                    <div class="row gx-0 mt-3">
                        <div class="col-12">
                            <input type="email" name="app_email" id="app_email" placeholder="Email Address" required />
                        </div>
                        <span class="starmail text-danger" style="position: absolute; margin-top: 10px; margin-left: 112px; width: 10px">
                            *
                        </span>
                        <label id="username-exist" class="text-danger"></label>
                        <label id="username-not-exist" class="text-success"></label>
                    </div>
                    <div class="row gx-0">
                        <div class="col-12">
                            <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "12" onkeydown="return event.keyCode !== 69" name="app_phone" id="app_phone" placeholder="Phone Number" required />
                        </div>
                        <span class="app_phonenumber ps-3 text-danger" style="position: absolute; margin-top: 9px; margin-left: 100px; width: 10px">
                            *
                        </span>
                    </div>
                    <p class="mt-3 mb-2"><b id="text-IDcard">ID Card Photo</b> &nbsp;<span class="starktp text-danger">*</span> </p>
                    <!-- <div id="id-photo-method" class="row" style="margin-bottom:10px; margin-top: 10px">
                        <div class="col-6">
                            <input type="radio" id="radioapp_ektpFile" name="app_ektp_radio" class="radio" value="File" checked>
                            <label class="text-fromFile" for="radioapp_ektpFile">&nbsp;&nbsp;From File</label>
                        </div>
                        <div class="col-6">
                            <input type="radio" id="radioapp_ektpOcr" name="app_ektp_radio" class="radio" value="OCR">
                            <label class="text-takePhoto" for="radioapp_ektpOcr">&nbsp;&nbsp;Take Photo</label><br>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-12">
                            <label for="app_ektp_photo" id="app_ektpLabelBtn"> 
                                <img id="imagapp_ektp" src="../assets/img/ktp.svg" style="border-radius: 10px">
                            </label>
                        </div>
                        <input type="file" style="display:none;" accept="image/*,ocr_file/*" name="app_ektp_photo" id="app_ektp_photo" class="photo" placeholder="Foto Fisik E-KTP" required onchange="loadFile2(event)"/>
                    </div>
                    <span id="app_ektp_photo-error" class="error" style="color: red"></span>

                    <div class="row gx-0 mt-3">
                        <div class="col-1" style="margin-top: 11px">
                            <span><b>NIK</b></span>
                        </div>
                        <div class="col-11" style="padding-left: 15px">
                            <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "16" onkeydown="return event.keyCode !== 69" name="app_ektp" id="app_ektp" placeholder="Identification Number" required />
                        </div>
                        <span class="starnoktp text-danger" style="position: absolute; margin-top: 11px; margin-left: 211px; width: 10px">*</span>
                        <label id="ktp-exist" class="text-danger"></label>
                        <label id="ktp-not-exist" class="text-success"></label>
                        <label id="ktp-16" class="text-danger" style="margin-left: 45px"></label>
                    </div>

                    <div class="row gx-0 mt-3">
                        <div class="col-1" style="margin-top: 11px">
                            <span><b>NPWP</b></span>
                        </div>
                        <div class="col-11" style="padding-left: 15px">
                            <input oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" type = "number" maxlength = "15" onkeydown="return event.keyCode !== 69" name="app_npwp" id="app_npwp" placeholder="Identification Number" pattern="[0-9]*" required />
                        </div>
                        <span class="starnoapp_npwp text-danger" style="position: absolute; margin-top: 11px; margin-left: 211px; width: 10px">*</span>
                        <label id="app_npwp-exist" class="text-danger"></label>
                        <label id="app_npwp-not-exist" class="text-success"></label>
                        <label id="app_npwp-16" class="text-danger"></label>
                    </div>
                </div>
            </div>
            
            <div class="mt-4" style="width: 100%; height: 5px; background-color: #e5e5e5"></div>
            <div style="background-color: #eee; padding-top: 25px">
                <div class="form-submit d-flex justify-content-center pb-5" style="height: 170px">
                    <button type="submit" class="btn p-2" style="border-radius: 20px; font-size: 13px; background-color: #0089ff; width: 50%; height: 50px; color: white" onclick="selectizeValid()"><b id="text-payNow">APPLY</b></button>
                </div>
            </div>
        </form>

        <div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="modalLoading" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center pb-3" id="modalLoading">
                    <img src="../assets/img/loading_build.gif" style="width: 75%"><br />
                    <p><b>Submitting data in progress</b></p>
                    <p>Please wait...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSuccess" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalSuccess" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0 text-center" id="modalSuccess">
                        <img src="../assets/img/success.png" style="width: 100px">
                        <h1 class="mt-3">Request Verification Success!</h1>
                        <p class="mt-3">Verifying your information data, usually takes within 24 hours or less for approval.</p>
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

    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-labelledby="modal-error" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-error-body">
                    <p id="error-modal-text"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-validation" tabindex="-1" role="dialog" aria-labelledby="modal-validation" aria-hidden="true">
        <div class="modal-dialog" role="document" style="margin-top: 200px">
            <div class="modal-content">
                <div class="modal-body p-0 text-center" id="modal-validation-body">
                    <p style="font-size: 12px" id="validation-text"></p>
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
    var global_self_photo_name = "";
    var global_ektp_photo_name = "";
    var date = new Date();
    let global_message_id = date.getTime();

    $(document).ready(function(e) {

        let selectizeType = $('#app_type').selectize();

    })

    $("#app_type").bind("change", function() {

        var selectType = $(this).val();
        $('.startype').hide();

        if (selectType == 1){

            if (localStorage.lang == 1){
                $('#verification-type-2').text('Akun Official');
            }else{
                $('#verification-type-2').text('Official Account');
            }

            $('#app_company_name-section').show();
            $('#app_company_address-section').show();
            $('#companyapp_npwp-section').show();

        }else if (selectType == 2){

            if (localStorage.lang == 1){
                $('#verification-type-2').text('Akun Verified');
            }else{
                $('#verification-type-2').text('Verified Account');
            }

            $('#app_company_name-section').hide();
            $('#app_company_address-section').hide();
            $('#companyapp_npwp-section').hide();

        }

    });

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
    var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];

    var loadFile = function(event) {

        var fileFormat = event.target.files[0].type.split('/')[1];
        var img, vid, canvas, ctx;
        var reader = new FileReader();
        reader.onload = createImage;
        reader.readAsDataURL(event.target.files[0]);

        function createImage() {

            if ($image_type_arr.includes(fileFormat)) {
                img = new Image();
                img.onload = imageLoaded;
                img.src = reader.result;
            } else if ($video_type_arr.includes(fileFormat)) {
                setImage(reader.result, number);
            }

        }

        var dataURLToBlob = function(dataURL) {

            var BASE64_MARKER = ';base64,';
            if (dataURL.indexOf(BASE64_MARKER) == -1) {
                var parts = dataURL.split(',');
                var contentType = parts[0].split(':')[1];
                var raw = parts[1];

                return new Blob([raw], {
                    type: contentType
                });
            }

            var parts = dataURL.split(BASE64_MARKER);
            var contentType = parts[0].split(':')[1];
            var raw = window.atob(parts[1]);
            var rawLength = raw.length;

            var uInt8Array = new Uint8Array(rawLength);

            for (var i = 0; i < rawLength; ++i) {
                uInt8Array[i] = raw.charCodeAt(i);
            }

            return new Blob([uInt8Array], {
                type: contentType
            });

        }

        function imageLoaded() {

            let imgDataURL, container, fileInput;

            canvas = document.createElement('canvas');
            ctx = canvas.getContext("2d");
            if (img.width > 480) {
                var max_size = 480
                    width = img.width,
                    height = img.height;
                if (width > height) {
                    if (width > max_size) {
                        height *= max_size / width;
                        width = max_size;
                    }
                } else {
                    if (height > max_size) {
                        width *= max_size / height;
                        height = max_size;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(img, 0, 0, width, height);
                imgDataURL = canvas.toDataURL('image/webp');
            } else {
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                ctx.drawImage(img, 0, 0);
                imgDataURL = canvas.toDataURL("image/webp");
            }
            let blobData = dataURLToBlob(imgDataURL);
            container = new DataTransfer();
            file = new File([blobData], "kta-M_pfp.webp",{type:"image/webp", lastModified:new Date().getTime()});
            container.items.add(file);
            let fileInputElement = document.getElementById('app_self_photo');
            fileInputElement.files = container.files;
            // console.log(fileInputElement.files);
            $('#imageProfile').attr('src', imgDataURL);
        }
    };

    var loadFile2 = function(event) {
      var reader = new FileReader();
      reader.onload = function() {
        
        $('#app_ektp_photo-error').text("");
        $('#imagapp_ektp').attr('src', reader.result);

        }
        reader.readAsDataURL(event.target.files[0]);
    };

    var $input_address = $('#address')
    $input_address.keyup(function(e) {
        var max = 60;
        if ($input_address.val().length > max) {
            $input_address.val($input_address.val().substr(0, max));
        }
    });

    var max = 1

    var $input = $('#app_ektp')
    $input.keyup(function(e) {
        var max = 18;
        if ($input.val().length > max) {
            $input.val($input.val().substr(0, max));
        }
    });

    $(".fullname").show();
    $(".starmail").show();
    $(".staraddress").show();
    $(".starppimg").show();
    $(".starnoktp").show();
    $(".starktp").show();
    
    $("#name").bind("change paste keyup", function() {
        var namevalue = $(this).val();

        if (namevalue) {
            $(".fullname").hide();
        }

        else {
            $(".fullname").show();
        }
    });

    $("#app_email").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".starmail").hide();
        }

        else {
            $(".starmail").show();
        }
    });

    $("#app_self_photo").change(function() {
        var ppimg = $(this).val();

        if (ppimg) {
            $(".starppimg").hide();
        }

        else {
            $(".starppimg").show();
        }
    });

    $("#app_ektp").bind("change paste keyup", function() {
        var ppimg = $(this).val();

        if (ppimg) {
            $(".starnoktp").hide();
        }

        else {
            $(".starnoktp").show();
        }
    });

    $("#app_ektp_photo").change(function() {
        var ppktp = $(this).val();

        if (ppktp) {
            $(".starktp").hide();
        }

        else {
            $(".starktp").show();
        }
    });

    $("#app_company_name").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".app_company_name").hide();
        }

        else {
            $(".app_company_name").show();
        }
    });

    $("#app_company_address").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".app_company_address").hide();
        }

        else {
            $(".app_company_address").show();
        }
    });

    $("#app_company_address").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".app_company_address").hide();
        }

        else {
            $(".app_company_address").show();
        }
    });

    $("#app_npwp").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".starnoapp_npwp").hide();
        }

        else {
            $(".starnoapp_npwp").show();
        }
    });

    $("#app_npwp_company").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".starnoapp_npwp_company").hide();
        }

        else {
            $(".starnoapp_npwp_company").show();
        }
    });

    $("#app_phone").bind("change paste keyup", function () {
        var mailvalue = $(this).val();

        if (mailvalue) {
            $(".app_phonenumber").hide();
        }

        else {
            $(".app_phonenumber").show();
        }
    });

    // FOR SELECTIZED VALIDATION

    function selectizeValid(){

        var app_self_photo = $('#app_self_photo').val();
        var app_ektp_photo = $('#app_ektp_photo').val();

        if(!app_ektp_photo){
            if (localStorage.lang == 1) {
                $('#app_ektp_photo-error').text("Kolom ini wajib diisi.");
                // $('#app_ektp_photo-error').text("This field is required.");
            }
            else {
                $('#app_ektp_photo-error').text("This field is required.");
            }
        }else{
            $('#app_ektp_photo-error').text("");
        }

        if(!app_self_photo){
            if (localStorage.lang == 1) {
                $('#app_self_photo-error').text("Kolom ini wajib diisi.");
                // $('#app_ektp_photo-error').text("This field is required.");
            }
            else {
                $('#app_self_photo-error').text("This field is required.");
            }
        }else{
            $('#app_self_photo-error').text("");
        }
    }

    // CHECK app_email ALREADY TAKEN

    // $("#app_email").bind("change paste keyup", function() {
    //     var app_email = $(this).val();

    //     // console.log(name);

    //     var formData = new FormData();

    //     formData.append('app_email', app_email);

    //     if (app_email != ""){

    //         let xmlHttp = new XMLHttpRequest();
    //         xmlHttp.onreadystatechange = function(){
    //             if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
    //                 // console.log(xmlHttp.responseText);

    //                 var result = xmlHttp.responseText;

    //                 if (result == "Ada"){
    //                     console.log("Username Ada");
    //                     $('#username-not-exist').text("");
    //                     if (localStorage.lang == 1) {
    //                         $('#username-exist').text("app_email sudah terdaftar. Harap masukkan app_email lain.");
    //                     }
    //                     else {
    //                         $('#username-exist').text("That app_email is taken, try another.");
    //                     }

    //                     is_takken = 0;
    //                 }else if(result == "Tidak ada"){
    //                     console.log("Username Tidak Ada");
    //                     // $('#username-not-exist').text("That app_email is available.");
    //                     $('#username-exist').text("");

    //                     is_takken = 1;
    //                 }

    //             }
    //         }
    //         xmlHttp.open("post", "../logics/check_kta_app_email");
    //         xmlHttp.send(formData);

    //     }else{
    //         $('#username-not-exist').text("");
    //         $('#username-exist').text("");
    //     }
    // });

    // CHECK KTP

    // $("#app_ektp").bind("change paste keyup", function() {
    //     var app_ektp = $(this).val();

    //     // console.log(name);

    //     var formData = new FormData();

    //     formData.append('app_ektp', app_ektp);

    //     if (app_ektp != ""){

    //         if(app_ektp.length < 16){
    //             if (localStorage.lang == 1) {
    //                 $('#ktp-16').text("No. KTP harus 16 digit");
    //             }
    //             else {
    //                 $('#ktp-16').text("KTP Number must be 16 digits.");
    //             }
    //             $('#ktp-exist').text("");
    //             $('#ktp-not-exist').text("");
    //         } else {
    //             let xmlHttp = new XMLHttpRequest();
    //             xmlHttp.onreadystatechange = function(){
    //                 if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                        
    //                     // console.log(xmlHttp.responseText);

    //                     var result = xmlHttp.responseText;

    //                     if (result == 1){
    //                         console.log("KTP Ada");
    //                         $('#ktp-not-exist').text("");
    //                         $('#ktp-16').text("");
    //                         if (localStorage.lang == 1) {
    //                             $('#ktp-exist').text("No. KTP sudah terdaftar, harap coba nomor lain.");
    //                         }
    //                         else {
    //                             $('#ktp-exist').text("That KTP Number is taken, try another.");
    //                         }

    //                         is_takken_ktp = 0;
    //                     }else if(result == 0){
    //                         console.log("KTP Tidak Ada");
    //                         if (localStorage.lang == 1) {
    //                             $('#ktp-not-exist').text("No. KTP dapat digunakan");
    //                         }
    //                         else {
    //                             $('#ktp-not-exist').text("That KTP Number is available.");
    //                         }
    //                         $('#ktp-16').text("");
    //                         $('#ktp-exist').text("");

    //                         is_takken_ktp = 1;
    //                     }

    //                 }
    //             }
    //             xmlHttp.open("post", "../logics/check_ktp");
    //             xmlHttp.send(formData);
    //         }
            
    //     }else{
    //         $('#ktp-not-exist').text("");
    //         $('#ktp-exist').text("");
    //     }
    // });

    // if (!window.Android) {
    //     $("#photo-method").hide();
    //     $("#id-photo-method").hide();
    // }
    // else {
    //     $("#photo-method").show();
    //     $("#id-photo-method").show();
    // }

    var file;

    var loadFile3 = function(event) {

        var reader = new FileReader();
        reader.onload = function() {

        console.log(reader.result);
        $("#photo-error").hide();
        $("#choose-button").hide();
        file = reader.result;

        event.target.value = '';

        }
        reader.readAsDataURL(event.target.files[0]);

    }

    // SCRIPT CONVERT BASE64 TO OBJECT

    function dataURLtoFile(dataurl, filename){
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);

        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }

        return new File([u8arr], filename, {type:mime});
    }

    if (localStorage.lang == 1) {
        // $("#text-header").text("KTA Mobility - Registrasi");
        // $("#text-identification").text("Identitas");
        // $("#text-ktaPhoto").text("Foto KTA");
        // $(".text-fromFile").text("Dari File");
        // $(".text-takePhoto").text("Ambil Gambar");
        // $("#profileLabelBtn").text("Mengunggah Foto");
        // $("#text-pp").text("* Foto Profil akan digunakan untuk kartu KTA");
        // $("#text-IDcard").text("Foto ID Card");
        // $("#app_ektp").attr("placeholder", "No. Identitas");
        // $("#name").attr("placeholder", "Nama Lengkap");
        // $("#app_email").attr("placeholder", "Alamat app_email");
        // $("#address").attr("placeholder", "Alamat Lengkap");

        // $(".starnoktp").attr("style", "position: absolute; margin-top: 11px; margin-left: 145px; width: 10px");
        // $(".fullname").attr("style", "position: absolute; margin-top: 9px; margin-left: 103px; width: 10px");
        // $(".starmail").attr("style", "position: absolute; margin-top: 10px; margin-left: 107px; width: 10px");
        // $(".starbp").attr("style", "position: absolute; z-index: 999; margin-top: -46px; margin-left: 103px");
        // $(".starbt").attr("style", "position: absolute; z-index: 999; margin-top: -46px; margin-left: 84px");
        // $(".staraddress").attr("style", "position: absolute; margin-top: 10px; margin-left: 128px; width: 10px");
        
    }

    $("input[name=profile_radio]:radio").on("click", function () {

        if ($(this).val() == "File") {
            $('#app_self_photo').prop('required', true);
            $("#profileLabelBtn").text("Choose File")
            $("#app_self_photo").prop('accept', "image/*,profile_file/*")

            $('#imageProfile').attr('src', '../assets/img/avatar.svg');
            $('#profileFileName').text("No file chosen");
        } else {
            $('#app_self_photo').prop('required', false);
            $("#profileLabelBtn").text("Take Photo")
            $("#app_self_photo").prop('accept', "image/*,profile_photo/*")

            $('#imageProfile').attr('src', '../assets/img/avatar.svg');
            $('#profileFileName').text("No file chosen");
        }

    });

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

    if (localStorage.lang == 1) {
        var nameError = "Kolom ini wajib diisi.";    
        var app_emailError = "Harap masukkan alamat app_email dengan benar.";
    }
    else {
        var nameError = "This field is required.";    
        var app_emailError = "Please enter a valid app_email address.";
    }

    var is_submit = 0;

    $('#verified-form').validate({
        rules: {
            app_ektp: {
                number: true
            },
            app_ektp_photo: {
                photoSize: 4000000,
                checkPhoto: true
            },
            fotoProfil: {
                photoSize: 4000000,
                checkPhoto: true
            },
        },
        messages: {
            'app_company_name': {
                required: nameError
            },
            'app_company_address': {
                required: nameError
            },
            'app_npwp_company': {
                required: nameError,
            },
            'app_email': {
                required: nameError
            },
            'app_phone': {
                required: nameError
            },
            'app_ektp': {
                required: nameError
            },
            'app_npwp': {
                required: nameError
            }
        },
        submitHandler: function (form, event) {

            event.preventDefault();

            // VALIDATION

            let app_type = $('#app_type').val();
            let app_company_name = $('#app_company_name').val();
            let app_company_address = $('#app_company_address').val();
            let app_npwp_company = $('#app_npwp_company').val();
            let app_self_photo = $('#app_self_photo').val();
            let app_email = $('#app_email').val();
            let app_phone = $('#app_phone').val();
            let app_ektp_photo = $('#app_ektp_photo').val();
            let app_ektp = $('#app_ektp').val();
            let app_npwp = $('#app_npwp').val();

            console.log("app_company_name = "+app_company_name);
            console.log("app_company_address = "+app_company_address);
            console.log("app_npwp_company = "+app_npwp_company);
            console.log("app_self_photo = "+app_self_photo);
            console.log("app_email = "+app_email);
            console.log("app_phone = "+app_phone);
            console.log("app_ektp_photo = "+app_ektp_photo);
            console.log("app_ektp = "+app_ektp);
            console.log("app_npwp = "+app_npwp);

            // if (name && app_email && address &&
            //     app_self_photo && app_ektp_photo && app_ektp && is_takken == 1 && is_takken_ktp == 1) {
                
            if (app_type == 1){

                if (app_company_name && app_company_address && app_npwp_company && app_self_photo && app_email && app_phone && app_ektp_photo && app_ektp && app_npwp) {

                    if (is_submit == 0){
                        is_submit = 1;
                        submitForm();
                    }

                } else {

                    if (localStorage.lang == 1) {
                        $('#validation-text').text("Harap isi semua form");    
                        // $('#validation-text').text("Please fill all required form");
                    }
                    else {
                        $('#validation-text').text("Please fill all required form");
                    }

                    $('#modal-validation').modal('show');

                }

            }else if(app_type == 2){

                if (app_self_photo && app_email && app_phone && app_ektp_photo && app_ektp && app_npwp) {

                    if (is_submit == 0){
                        is_submit = 1;
                        submitForm();
                    }

                } else {

                    if (localStorage.lang == 1) {
                        $('#validation-text').text("Harap isi semua form");    
                        // $('#validation-text').text("Please fill all required form");
                    }
                    else {
                        $('#validation-text').text("Please fill all required form");
                    }

                    $('#modal-validation').modal('show');

                }
            }

            $("#modal-validation").on('hidden.bs.modal', function(e) {
                $("html").attr("style", "");
            });
        }
    });

    function submitForm() {

        let app_type = $('#app_type').val();

        if (app_type == 1){
            app_type = "OA";
        }else if(app_type == 2){
            app_type = "VA";
        }

        var myform = $("#verified-form")[0];
        let fd = new FormData(myform);
        fd.append("f_pin", F_PIN);
        fd.append("id", app_type + "-" + global_message_id);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/insert_form_verified",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {

                console.log("Success to insert Form!");

                global_self_photo_name = response.split("|")[0];
                global_ektp_photo_name = response.split("|")[1];
               
                $("#submit").prop("disabled", false);
                $('#modalLoading').modal('show');

                getOfficer();

            },
            error: function (response) {
               
                $("#submit").prop("disabled", false);
                alert(response.responseText);
            }
        });
    }

    function finishForm(){

        if (window.Android) {
            window.Android.finishForm();
        }

    }

    function getOfficer(){

        // GET ALL OFFICER

        let fd = new FormData();
        fd.append('f_pin', F_PIN);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/get_officer",
            enctype: 'multipart/form-data',
            cache: false,
            data: fd,
            processData: false,
            contentType: false,
            success: function (response) {
               
                let officer = JSON.parse(response);
                console.log("Success to get Officer!");
                console.log(officer);

                let arrLPIN = [];

                // LOOP TO SEND MESSAGE

                for (var i=0; i<officer.length; i++){

                    arrLPIN.push(officer[i].CREATED_BY);

                    // if (officer[i].F_PIN == '02d6ab85b0'){
                    //     arrLPIN.push(officer[i].F_PIN);
                    // }

                }

                console.log(arrLPIN.join(","));
                sendMessage(arrLPIN.join(","));

            },
            error: function (response) {
               
                alert(response.responseText);
            
            }
        });

    }

    function sendMessage(l_pin){

        let app_type = $('#app_type').val();
        let app_company_name = $('#app_company_name').val();
        let app_company_address = $('#app_company_address').val();
        let app_npwp_company = $('#app_npwp_company').val();
        let app_self_photo = global_self_photo_name;
        let app_email = $('#app_email').val();
        let app_phone = $('#app_phone').val();
        let app_ektp_photo = global_ektp_photo_name;
        let app_ektp = $('#app_ektp').val();
        let app_npwp = $('#app_npwp').val();

        let content = '';
        let file_id = '';

        if (app_type == 1){

            content = {
                "form_id" : "128648",
                "form_title" : "Offical+Account+Form",
                "A01" : "",
                "app_company_name" : app_company_name,
                "app_company_address" : app_company_address,
                "app_npwp_company" : app_npwp_company,
                "app_self_photo" : "['"+app_self_photo+"']",
                "app_email" : app_email,
                "app_phone" : app_phone,
                "app_ektp_photo" : "['"+app_ektp_photo+"']",
                "app_ektp" : app_ektp,
                "app_npwp" : app_npwp
            };

            file_id = "128648";

        }else if(app_type == 2){

            content = {
                "form_id" : "168534",
                "form_title" : "Verification+Account+Form",
                "A01" : "",
                "app_self_photo" : "['"+app_self_photo+"']",
                "app_email" : app_email,
                "app_phone" : app_phone,
                "app_ektp_photo" : "['"+app_ektp_photo+"']",
                "app_ektp" : app_ektp,
                "app_npwp" : app_npwp
            };

            file_id = "168534";
        }

        var scope = 18;

        if (app_type == 1){
            app_type = "OA";
        }else if(app_type == 2){
            app_type = "VA";
        }

        let message_id = app_type + "-" + global_message_id;
        let originator = F_PIN;
        let destination = l_pin;
        let reply_to = app_type + "-" + global_message_id;

        let fd = new FormData();
        fd.append('message_id', message_id);
        fd.append('originator', originator);
        fd.append('destination', destination);
        fd.append('content', btoa(JSON.stringify(content)));
        fd.append('scope', scope);
        fd.append('reply_to', reply_to);
        fd.append('file_id', file_id);

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/send_message",
            enctype: 'multipart/form-data',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
               
                console.log("Success send to Officer!");
                console.log(JSON.parse(response));

                $('#modalLoading').modal('hide');
                $('#modalSuccess').modal('show');

            },
            error: function (response) {
               
                alert(response.responseText);
            
            }
        });

    }


    $("#app_ektp").bind("change paste keyup", function() {
        
        var ektp = $(this).val();

        if(ektp.length < 16){
            
            if (localStorage.lang == 1) {
                $('#ktp-16').text("No. KTP harus 16 digit");
            }
            else {
                $('#ktp-16').text("KTP Number must be 16 digits.");
            }
            
        }else{

            $('#ktp-16').text("");

        }

    });

    if (localStorage.lang == 1){

        $('#text-header').text('Formulir Pengajuan Verifikasi');
        $('#verification-type').text('Tipe Verifikasi');

        $('#verification-type-2').text('Akun Official');

        $('#app_company_name').attr('placeholder','Nama Perusahaan');
        $('#app_company_address').attr('placeholder','Alamat Perusahaan');
        $('#app_npwp_company').attr('placeholder','NPWP Perusahaan');

        $('#app_email').attr('placeholder','Alamat Email');
        $('#app_phone').attr('placeholder','Nomer Telepon');
        $('#app_ektp').attr('placeholder','Nomer Identitas');
        $('#app_npwp').attr('placeholder','Nomer Identitas');

        $('#type-1').text('Akun Official');
        $('#type-2').text('Akun Verified');

        $('#text-ktaPhoto').text('Foto Pribadi');
        $('#text-IDcard').text('Foto ID Card');

        $('#profileLabelBtn').text('Unggah Foto');
        $('#text-pp').text('* Foto profil akan digunakan untuk verifikasi');

        $('#text-payNow').text('KIRIM');

        $('.app_company_name').css('margin-left','144px');
        $('.app_company_address').css('margin-left','153px');
        $('.starnoapp_npwp_company').css('margin-left','142px');
        $('.starmail').css('margin-left','108px');
        $('.app_phonenumber').css('margin-left','106px');
        $('.starnoktp').css('margin-left','170px');
        $('.starnoapp_npwp').css('margin-left','170px');

        $('body').removeClass('d-none');

    }else{

        $('#text-header').text('Request Verification Form');
        $('#verification-type').text('Verification Type');

        $('#verification-type-2').text('Official Account');

        $('#app_company_name').attr('placeholder','Company Name');
        $('#app_company_address').attr('placeholder','Company Address');
        $('#app_npwp_company').attr('placeholder','Company NPWP');

        $('#app_email').attr('placeholder','Email Address');
        $('#app_phone').attr('placeholder','Phone Number');
        $('#app_ektp').attr('placeholder','Identification Number');
        $('#app_npwp').attr('placeholder','Identification Number');

        $('#type-1').text('Official Account');
        $('#type-2').text('Verified Account');

        $('#text-ktaPhoto').text('Self Photo');
        $('#text-IDcard').text('ID Card Photo');

        $('#profileLabelBtn').text('Upload Photo');
        $('#text-pp').text('* Profile photo will be used for verification');

        $('#text-payNow').text('APPLY');

        $('.app_company_name').css('margin-left','127px');
        $('.app_company_address').css('margin-left','141px');
        $('.starnoapp_npwp_company').css('margin-left','125px');
        $('.starmail').css('margin-left','112px');
        $('.app_phonenumber').css('margin-left','100px');
        $('.starnoktp').css('margin-left','211px');
        $('.starnoapp_npwp').css('margin-left','211px');

        $('body').removeClass('d-none');

    }

</script>